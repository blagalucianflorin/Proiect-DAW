<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/database_manager.php');

class login_tokens
{
	/*
	 * Creates a new login token
	 *
	 * @param 	int 	$user_id		User ID the token should be associated to
	 * @param 	string	$token			Login token
	 * @param	string	$expiry_date	Date the token will expire in format 'Y-M-D h:m:s'
	 *
	 * @return	bool					Returns true if added successfully and false otherwise
	 */
	public static function add_token($user_id, $token, $expiry_date)
	{
		$db_manager	= new database_manager ();

		$result = $db_manager -> insert (
			"login_tokens",
			['user_id', 'token', 'expiry_date'],
			[$user_id, $token, $expiry_date]);

		return ($result == true);
	}

	/*
	 * Check if a given token present in the database and if it is, it will check
	 * if it's past its expiry date
	 *
	 * @param	string	$token	Token which will be checked
	 *
	 * @return	mixed			Returns the User ID associated with the given token if it exists
	 * 							and false if the token doesn't exist or is expired
	 */
	public static function check_token ($token)
	{
		$db_manager = new database_manager ();

		$result = $db_manager -> select (
			'login_tokens',
			['user_id', 'expiry_date'],
			"token='$token'"
		);

		if (count ($result) == 1)
		{
			if (strtotime ($result[0]['expiry_date']) < time ())
				return (false);
			return ($result[0]['user_id']);
		}
		else
			return (false);
	}

	/*
	 * Deletes the given token
	 *
	 * @param	string	$token	Token to be deleted
	 *
	 * @return	mixed			The User ID associated with the token if the token exists and null otherwise
	 */
	public static function remove_token ($token)
	{
		$db_manager = new database_manager ();
		$user_id	= null;

		$result = $db_manager -> select ('login_tokens', ['user_id'], "token='$token'");

		if (count ($result) == 1)
			$user_id = $result[0]['user_id'];

		$db_manager -> delete ('login_tokens', "token='$token'");

		return ($user_id);
	}
}
