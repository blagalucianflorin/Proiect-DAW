<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/login_tokens.php');

class login_tokens_controller
{
	/*
	 * Adds a new login token to the database
	 *
	 * @param	int		$user_id		User ID associated with the token
	 * @param	string	$expiry_date	Data the token will expiry at
	 *
	 * @return	bool					The token if added successfully, false otherwise
	 */
	public static function create ($user_id, $expiry_date = null)
	{
		return (login_tokens::add_token ($user_id, $expiry_date));
	}

	/*
	 * Checks if a given token is valid
	 *
	 * @param	string	$token	Login token to be checked
	 *
	 * @return	mixed			User ID associated with the token if it's valid and false otherwise
	 */
	public static function check_token ($token)
	{
		return (login_tokens::check_token ($token));
	}

	/*
	 * Deletes a token from the database
	 *
	 * @param	string	$token	Login token to be deleted
	 *
	 * @return	mixed			User ID associated with the token if it's valid and null otherwise
	 */
	public static function remove_token ($token)
	{
		return (login_tokens::remove_token ($token));
	}
}

?>