<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/database_manager.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/security/validator.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/security/sanitizer.php');

class users
{
	/*
	 * Tries to create a new user
	 *
	 * @param	string	$full_name			Full name of the new user
	 * @param	string	$email				Email of the user
	 * @param	string	$email_confirm		Email confirmation of the user
	 * @param	string	$password			Password of the user
	 * @param	string	$password_confirm	Password confirmation of the user
	 *
	 * @return	mixed						The User ID if user is created successfully and an array with
	 * 										an error message and form data otherwise
	 */
	public static function register ($full_name, $email, $email_confirm, $password, $password_confirm)
	{
		$db_manager			= new database_manager ();

		$full_name			= sanitizer::sanitize_string ($full_name);
		$email				= sanitizer::sanitize_email ($email);
		$email_confirm		= sanitizer::sanitize_email ($email_confirm);
		$password			= sanitizer::sanitize_string ($password);
		$password_confirm	= sanitizer::sanitize_string ($password_confirm);

		if (validator::validate_name ($full_name) == false)
			return (["ERROR_MSG" => "Invalid name", "email" => $email, "email_confirm" => $email_confirm]);

		if (validator::validate_email ($email, $email_confirm) == false)
			return (["ERROR_MSG" => "Invalid email", "full_name" => $full_name]);

		if (validator::validate_password ($password, $password_confirm) == false)
			return (["ERROR_MSG" => "Password doesn't meet requirements", "full_name" => $full_name, "email" => $email,
					"email_confirm" => $email_confirm]);

		$full_name	= sanitizer::escape ($full_name);
		$email		= sanitizer::escape ($email);
		$password 	= password_hash (sanitizer::escape ($password), PASSWORD_BCRYPT);

		if (count ($db_manager -> select ('users', null, "email='$email'")) > 0)
			return (["ERROR_MSG" => "An user with that email already exists", "full_name" => $full_name]);

		$result = $db_manager -> insert (
			'users',
			['email', 'password', 'full_name', 'permissions'],
			[$email, $password, $full_name, '{ "normal": true, "admin": false }']
		);

		if ($result != false)
			return ($db_manager -> select ('users', ['user_id'], "email='$email'")[0]['user_id']);
		else
			return (["ERROR_MSG" => "Problem creating user. Please try again later"]);
	}

	/*
	 * Checks if a user is an admin based on his User ID
	 *
	 * @param	int		User ID to be checked
	 *
	 * @return	bool	True is the user is an admin, false if he's not or if no user is logged in
	 */
	public static function is_admin ($user_id)
	{
		$db_manager = new database_manager ();

		$result = $db_manager -> select ('users', ['permissions'], "user_id='$user_id'");
		if (count ($result) == 1)
		{
			$permissions = json_decode ($result[0]['permissions']);
			return ($permissions -> {'admin'} == true);
		}

		return (false);
	}

	/*
	 * Activates a user's account is an admin based on his User ID
	 *
	 * @param	int	$user_id	Account to be activated User's ID
	 *
	 * @return	bool			True is activated successfully, false otherwise
	 */
	public static function activate ($user_id)
	{
		$db_manager = new database_manager ();

		$result = $db_manager -> update ('users', ['activated' => 1], "user_id='$user_id'");

		return ($result == true);
	}

	/*
	 * Checks if a user's account is activated base on his User ID
	 *
	 * @param	int	$user_id	Account to be checked User's ID
	 *
	 * @return	mixed			Null if User ID is invalid and true/false is account has been activated
	 */
	public static function is_activated ($user_id)
	{
		$db_manager = new database_manager ();

		$result = $db_manager -> select ('users', ['activated'], "user_id='$user_id'");
		if (count ($result) == 1)
			return ($result[0]['activated']);

		return null;
	}

	/*
	 * Check if a set of credentials present in the database and are valid
	 *
	 * @param	string	$email		User's email
	 * @param	string	$password	User's password
	 *
	 * @return	mixed				User's ID if credentials are valid
	 * 								False if email exists but password is incorrect
	 * 								Null otherwise
	 */
	public static function check_credentials ($email, $password)
	{
		$db_manager	= new database_manager ();

		$result = $db_manager -> select ('users', ['user_id', 'password'], "email='$email'");
		if (count ($result) == 1)
		{
			if (password_verify ($password, $result[0]['password']) == true)
				return ($result[0]['user_id']);
			else
				return ([$result[0]['user_id']]);
		}

		return null;
	}

	/*
	 * Get the full name of an user based on his User ID
	 *
	 * @param	int	$user_id	User's ID
	 *
	 * @return	mixed			User's full name is User ID exists and null otherwise
	 */
	public static function get_full_name ($user_id)
	{
		$db_manager = new database_manager ();

		$result = $db_manager -> select ('users', ['full_name'], "user_id='$user_id'");
		if (count ($result) == 1)
			return ($result[0]['full_name']);

		return null;
	}
}


?>