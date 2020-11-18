<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/users_activity.php');

class users_activity_controller
{
	/*
	 * Adds a new user activity to the database
	 *
	 * @param	int		$user_id	User ID the action should be associated to
	 * @param	string	$action		Action the user performed
	 *
	 * @return	mixed				True is added successfully and the DB error plus the query otherwise
	 */
	public static function create ($user_id, $action)
	{
		return (users_activity::add_activity ($user_id, $action));
	}

	/*
	 * Returns all the activities associated with a user
	 *
	 * @param	int	$user_id	User's ID
	 *
	 * @return	array			List of activities without the user_id column
	 */
	public static function get_activities ($user_id)
	{
		return (users_activity::get_activities ($user_id));
	}
}