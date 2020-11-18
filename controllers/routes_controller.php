<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/routes.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/essentials.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');

class routes_controller
{
	/*
	 * Gets all the routes in the database. Return array will only contain the first and last station
	 * of that specific route, along with the Route ID
	 *
	 * @return	array	An array with all the routes in the database
	 */
	public static function get_routes()
	{
		return (routes::get_routes ());
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
		return (routes::get_route_stations ($route_id));
	}

	/*
	 * Adds a new route. Redirects to /views/admin/trains/select_route.php
	 *
	 * @param	array	$stations	Station's names
	 * @param	array	$distances	Distance from start associated with each station
	 * @param	array	$times		Time from start associated with each station
	 *
	 * @return	void				No return value
	 */
	public static function add_route ($stations, $distances, $times)
	{
		$final_stations = array ();

		foreach ($stations as $station)
			if ($station == '')
				controller::redirect ('/views/admin/routes/route_builder.php');

		foreach ($distances as $distance)
			if ($distance == '')
				controller::redirect ('/views/admin/routes/route_builder.php');

		foreach ($times as $time)
			if ($time == '')
				controller::redirect ('/views/admin/routes/route_builder.php');

		for ($i = 0; $i < count ($stations); $i++)
			array_push ($final_stations,
			[
				$stations[$i],
				$distances[$i],
				$times[$i]
			]);

		routes::add_route ($final_stations);

		controller::redirect ('/views/admin/trains/select_route.php');
	}
}

if (isset($_POST['_action']) || isset($_GET['_action']))
{
	if ($_POST['_action'] == 'ADD_ROUTE')
	{
		$stations 	= $_POST['station_name'];
		$distances 	= $_POST['distance'];
		$times 		= $_POST['time'];

		routes_controller::add_route ($stations, $distances, $times);
	}
}