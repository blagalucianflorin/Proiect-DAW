<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/database_manager.php');

class routes
{
	/*
	 * Get all the routes in the database
	 *
	 * @return	array	Returns all routes with: Route ID, First station
	 * 					and Last station for all routes
	 */
	public static function get_routes ()
	{
		$db_manager	= new database_manager ();
		$routes		= array ();

		$routes_raw	= $db_manager -> select ('routes');
		foreach ($routes_raw as $route)
		{
			$stations = json_decode ($route['stations']);
			array_push ($routes,
				[
					'route_id' 		=> $route['route_id'],
					'start_station'	=> $stations[0][0],
					'end_station'	=> end($stations)[0]
				]);
		}

		return ($routes);
	}

	/*
	 * Get all stations for a given Route ID.
	 *
	 * @param	int		$route_id
	 *
	 * @return	array	Returns all stations with: Station name, distance from first
	 * 					station of that route and time to that station from the first station
	 */
	public static function get_route_stations ($route_id)
	{
		$db_manager	= new database_manager ();

		$result = $db_manager -> select (
			'routes',
			['stations'],
			"route_id='$route_id'"
		);
		return (json_decode ($result[0]['stations']));
	}

	/*
	 * Add a new route to the database
	 *
	 * @param	array	$stations	The array of stations the route will have. It also contains
	 * 								the distances and times for each station
	 *
	 * @return	mixed				True if route was successfully added, and a query error otherwise
	 */
	public static function add_route ($stations)
	{
		$db_manager = new database_manager ();
		$stations = json_encode ($stations);

		$result = $db_manager -> insert (
			'routes',
			['stations'],
			[$stations]
		);

		return ($result);
	}
}