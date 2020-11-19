<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/trains.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/essentials.php');

class trains_controller
{
	/*
	 * Add a new train to the database
	 *
	 * @param	int		$route_id				Route ID the train is based on
	 * @param	float	$km_cost				Cost per KM of the train
	 * @param	int		$first_class_wagons		Number of first class wagons the train will have
	 * @param	int		$second_class_wagons	Number of station class wagons the train will have
	 * @param	string	$start_date				Date the train will leave from its first station
	 * @param	array	$aux_stations			The stations the train will stop at
	 *
	 * @return	void							No return value
	 */
	public static function add_train ($route_id, $km_cost, $first_class_wagons, $second_class_wagons, $start_date,
										$aux_stations)
	{
		$stations 	= array ();

		foreach ($aux_stations as $station => $value)
		{
			$new_station = array ();
			$numbers = '0123456789';

			$i = 0;
			for (; $i < strlen ($value); $i++)
				if (substr_count ($numbers, substr ($value, $i, 1)) != 0)
					break;

			array_push ($new_station, substr ($value, 0, $i));
			$aux = explode (' ', substr ($value, $i, strlen($value) - $i));
			array_push ($new_station, $aux[0]);
			array_push ($new_station, $aux[1]);

			array_push ($stations, $new_station);
		}

		trains::add_train ($route_id, $km_cost, $stations, $start_date, $first_class_wagons, $second_class_wagons);

		controller::redirect ('/');
	}

	/*
	 * Search for trains based on criteria. Will redirect to the results page
	 *
	 * @param	string	$start_station	Name of the station the trains should leave from
	 * @param	string	$end_station	Name of the station the trains should arrive at
	 * @param	string	$date			Date the train should leave at
	 *
	 * @return	void					No return value
	 */
	public static function search_trains ($start_station, $end_station, $date)
	{
		$trains 				= trains::get_trains ();
		$found_trains			= array ();

		if ($start_station != $end_station)
			foreach ($trains as $train)
			{
				$found_start_station	= false;
				$found_end_station		= false;

				foreach ($train['stations'] as $station)
				{
					if (trim($station[0]) == $start_station && explode (' ', $station[2])[0] == $date)
						$found_start_station = $station;

					if (trim($station[0]) == $end_station)
						$found_end_station = $station;

					if ($found_start_station != false && $found_end_station != false)
						break;
				}
				if ($found_start_station != false && $found_end_station != false)
					array_push ($found_trains,
						[
							'start_station'	=> $found_start_station,
							'end_station'	=> $found_end_station,
							'train_id'		=> $train['train_id'],
							'km_cost'		=> $train['km_cost']
						]);
			}

		controller::redirect_with_data (
			'/views/tickets/search_trains.php',
			[
				'trains'	=> $found_trains
			]
		);
	}

	/*
	 * Returns all trains with only the first and last station, the train id and the cost
	 *
	 * @return	array	The trains
	 */
	public static function get_all_trains ()
	{
		$trains 		= trains::get_trains ();
		$found_trains	= array ();

		foreach ($trains as $train)
		{
			$found_start_station = $train['stations'][0];
			$found_end_station = end($train['stations']);
			array_push ($found_trains,
				[
					'start_station'	=> $found_start_station,
					'end_station'	=> $found_end_station,
					'train_id'		=> $train['train_id'],
					'km_cost'		=> $train['km_cost']
				]);
		}

		return ($found_trains);
	}

	/*
	 * Reserves a seat on a train at a specific class
	 *
	 * @param	int	$train_id	Train to reserve the seat in
	 * @param	int	$class		Class to reserve the seat at
	 * @param	int	$user_id	User to assign seat to
	 *
	 * @return	mixed			Array with wagon and seat number if a seat is found, null otherwise
	 */
	public static function reserve_seat ($train_id, $class, $user_id)
	{
		return (trains::reserve_seat ($train_id, $class, $user_id));
	}

	/* Checks if there's seats available at a certain class in a certain train
     *
	 * @param	int	$train_id	Train to check for tickets
	 * @param	int	$class		Class to search for tickets in
	 *
	 * @return	bool			True if seats are available, false otherwise
	 */
	public static function has_seats_available ($train_id, $class)
	{
		return (trains::has_seats_available ($train_id, $class));
	}

	/*
	 * Checks if a user has bought a seat on a train
	 *
	 * @param	int	$train_id	Train to check for tickets
	 * @param	int	$user_id	User to check for tickets
	 *
	 * @return	bool			True if user has a seat on that train, false otherwise
	 */
	public static function user_has_seat ($train_id, $user_id)
	{
		return (trains::user_has_seat ($train_id, $user_id));
	}
}

if (isset($_POST['_action']) || isset($_GET['_action']))
{
	if ($_POST['_action'] == 'ADD_TRAIN')
	{
		$route_id 				= $_POST['route_id'];
		$km_cost				= $_POST['km_cost'];
		$first_class_wagons		= $_POST['first_class_wagons'];
		$second_class_wagons	= $_POST['second_class_wagons'];
		$start_date				= $_POST['start_date'];
		$aux_stations 			= $_POST['stations'];

		trains_controller::add_train ($route_id, $km_cost, $first_class_wagons, $second_class_wagons, $start_date,
										$aux_stations);
	}

	if ($_POST['_action'] == 'SEARCH_TRAIN')
	{
		$start_station	= $_POST['start_station'];
		$end_station	= $_POST['end_station'];
		$date			= $_POST['date'];

		trains_controller::search_trains ($start_station, $end_station, $date);
	}
}