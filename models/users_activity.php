<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/database_manager.php');

class users_activity
{
	/*
	 * Adds a new user activity
	 *
	 * @param 	int 	$user_id	User ID the action should be associated to
	 * @param	string	$action		Action which the user performed
	 *
	 * @return	mixed				True is added successfully and the DB error plus the query otherwise
	 */
	public static function add_activity ($user_id, $action)
	{
		$db_manager	= new database_manager ();
		$ip 		= $_SERVER['REMOTE_ADDR'];

		$result	= $db_manager -> insert (
			'users_activity',
			['user_id', 'action', 'ip'],
			[$user_id, $action, $ip]
		);

		return ($result);
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
		$db_manager = new database_manager ();
		$activities	= $db_manager -> select (
			'users_activity',
			null,
			"user_id='$user_id'"
		);

		return ($activities);
	}
}
