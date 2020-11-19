<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/database_manager.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/views/includes/essentials.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class trains
{
	/*
	 * Adds a new train based on an existing route
	 *
	 * @param	int		$route_id
	 * @param	float	$km_cost
	 * @param	array	$stations
	 * @param	string	$start_date
	 * @param	int		$first_class_wagons
	 * @param	int		$second_class_wagons
	 *
	 * @return	array
	 */
	public static function add_train ($route_id, $km_cost, $stations, $start_date, $first_class_wagons, $second_class_wagons)
	{
		$db_manager		= new database_manager ();
		$wagons 		= array ();
		$current_wagon	= 1;

		for ($i = 0; $i < $first_class_wagons; $i++)
		{
			$seats = array();

			for ($j = 0; $j < 4; $j++)
				array_push ($seats, '0');

			array_push ($wagons, [
				'wagon_class'	=> 1,
				'wagon_number'	=> $current_wagon,
				'seats'			=> $seats
			]);
			$current_wagon++;
		}

		for ($i = 0; $i < $second_class_wagons; $i++)
		{
			$seats = array();

			for ($j = 0; $j < 8; $j++)
				array_push ($seats, '0');

			array_push ($wagons, [
				'wagon_class'	=> 2,
				'wagon_number'	=> $current_wagon,
				'seats'			=> $seats
			]);
			$current_wagon++;
		}

		for ($i = 1; $i < count ($stations); $i++)
		{
			$stations[$i][1] -= $stations[0][1];
			$stations[$i][2] -= $stations[0][2];
		}
		$stations[0][1] = 0;
		$stations[0][2] = 0;
		for ($i = 0; $i < count ($stations); $i++)
			$stations[$i][2] = date ('Y-m-d G:i',strtotime($start_date) + (60 * $stations[$i][2]));

		$result = $db_manager -> insert (
			'trains',
			['route_id', 'km_cost', 'stations', 'seats'],
			[$route_id, $km_cost, json_encode ($stations), json_encode ($wagons)]
		);

		return ($result);
	}

	/*
	 * Gets all trains with stations as wagons a JSON decoded
	 *
	 * @return	array
	 */
	public static function get_trains ()
	{
		$db_manager = new database_manager ();
		$result = $db_manager -> select ('trains');

		foreach ($result as &$train)
		{
			$train['stations'] 	= json_decode ($train['stations']);
			$train['seats']	= json_decode ($train['seats']);
		}

		return ($result);
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
		$db_manager = new database_manager ();
		$train 		= $db_manager -> select (
			'trains',
			null,
			"train_id='$train_id'"
		);

		$train = $train[0];

		$wagons = json_decode ($train['seats']);
		$wagons = json_decode(json_encode($wagons), true);

		for ($j = 0; $j < count($wagons); $j++)
			if ($wagons[$j]['wagon_class'] == $class)
			{
				for ($i = 0; $i < count ($wagons[$j]['seats']); $i++)
				{
					if ($wagons[$j]['seats'][$i] == 0)
					{
						$wagons[$j]['seats'][$i] = $user_id;
						$wagons = json_encode ($wagons);

						$db_manager -> update (
							'trains',
							[
								'seats' => $wagons
							],
							"train_id='$train_id'"
						);

						return (
							[
								'wagon' => $j + 1,
								'seat'	=> $i
							]);
					}
				}
			}
		return null;
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
		$db_manager = new database_manager ();
		$train 		= $db_manager -> select (
			'trains',
			null,
			"train_id='$train_id'"
		)[0];

		$wagons = json_decode(json_encode(json_decode ($train['seats'])), true);

		for ($j = 0; $j < count($wagons); $j++)
			if ($wagons[$j]['wagon_class'] == $class)
				for ($i = 0; $i < count ($wagons[$j]['seats']); $i++)
					if ($wagons[$j]['seats'][$i] == 0)
						return (true);

		return (false);
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
		$db_manager = new database_manager ();
		$train 		= $db_manager -> select (
			'trains',
			null,
			"train_id='$train_id'"
		)[0];

		$wagons = json_decode(json_encode(json_decode ($train['seats'])), true);

		for ($j = 0; $j < count($wagons); $j++)
				for ($i = 0; $i < count ($wagons[$j]['seats']); $i++)
					if ($wagons[$j]['seats'][$i] == $user_id)
						return (true);

		return (false);
	}
}
