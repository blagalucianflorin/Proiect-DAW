<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/tickets.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/trains_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_activity_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/pdf/pdf_manager.php');

class tickets_controller
{
	/*
	 * Reserves a seat on a train and adds a ticket to the user's account
	 *
	 * @param	int		$user_id
	 * @param	int		$train_id
	 * @param	string	$start_station
	 * @param	string	$end_station
	 * @param	string	$start_date
	 * @param	string	$end_date
	 * @param	int		$class
	 *
	 * @return	void					No return value
	 */
	public static function buy_ticket ($user_id, $train_id, $price, $start_station, $end_station,
										$start_date, $end_date, $class)
	{
		if (trains_controller::user_has_seat ($train_id, $user_id))
			controller::redirect_with_data ('/',
				[
					'ERROR_MSG' => 'You already have a ticket on that train'
				]);
		$position = trains_controller::reserve_seat ($train_id, $class, $user_id);

		pretty_print ($position);

		if ($position == null)
			controller::redirect_with_data ('/',
			[
				'ERROR_MSG'	=> 'No seats available on that train'
			]);

		$wagon 	= $position['wagon'];
		$seat	= $position['seat'];

		$ticket_id = tickets::buy_ticket ($user_id, $train_id, $price, $start_station, $end_station, $start_date,
											$end_date, $class, $wagon, $seat);

		email_manager::ticket_email (users_controller::get_email (), users_controller::get_full_name (), $ticket_id);

		users_activity_controller::create ($user_id, "Bought ticket");

		controller::redirect_with_data ('/views/tickets/see_tickets.php',
			[
				'NORMAL_MSG' => 'The ticket has been sent to your email'
			]);
	}

	/*
	 * Get all the tickets for the logged in user. Redirects to home page if user isn't logged in
	 *
	 * @return	array	The array of tickets the user has bought
	 */
	public static function get_tickets ()
	{
		$user_id	= users_controller::is_logged_in ();

		if ($user_id == false)
			controller::redirect ('/');
		$tickets = tickets::get_tickets ($user_id);

		return ($tickets);
	}

	/*
	 *  Get a ticket by a given ID
	 */
	public static function get_ticket ($ticket_id)
	{
		return (tickets::get_ticket ($ticket_id));
	}
}

if (isset($_POST['_action']) || isset($_GET['_action']))
{
	if ($_POST['_action'] == 'BUY_TICKET')
	{
		$user_id 		= users_controller::is_logged_in ();
		$train_id		= $_POST['train_id'];
		$price			= $_POST['price'];
		$start_station	= $_POST['start_station'];
		$end_station	= $_POST['end_station'];
		$start_date		= $_POST['start_date'];
		$end_date		= $_POST['end_date'];
		$class			= $_POST['class'];

		tickets_controller::buy_ticket ($user_id, $train_id, $price, $start_station, $end_station, $start_date,
										$end_date, $class);
	}
}