<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/database/database_manager.php');

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
		$db_manager	= new database_manager ();

		# Check all inputs exist
		if ($full_name == null)
			return (array
			(
				"ERROR_MSG" 	=> "Please enter your full name",
				"email" 		=> $email,
				"email_confirm" => $email_confirm
			));

		if ($email == null)
			return (array
			(
				"ERROR_MSG" => "Please enter an email",
				"full_name" => $full_name
			));

		if ($email_confirm == null)
			return (array
			(
				"ERROR_MSG" => "Please confirm the email",
				"full_name" => $full_name
			));

		if ($password == null)
			return (array
			(
				"ERROR_MSG" 	=> "Please enter a password",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		if ($password_confirm == null)
			return (array
			(
				"ERROR_MSG" 	=> "Please confirm the password",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		# Sanitize input
		$full_name 	= filter_var($full_name, FILTER_SANITIZE_STRING);
		$email 		= filter_var($email, FILTER_SANITIZE_STRING);
		$password 	= filter_var($password, FILTER_SANITIZE_STRING);


		# Validate email
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === False)
			return (array
			(
				"ERROR_MSG" 	=> "Invalid email",
				"full_name" 	=> $full_name
			));

		if ($email != $email_confirm)
			return (array
			(
				"ERROR_MSG" 	=> "Emails don't match",
				"full_name" 	=> $full_name
			));

		if (strlen ($email) > 255)
			return (array
			(
				"ERROR_MSG" 	=> "Email is too long",
				"full_name" 	=> $full_name
			));

		# Validate password
		$password_validation = users::validate_password ($password);
		if ($password_validation !== true)
			return (array
			(
				"ERROR_MSG" 	=> $password_validation,
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		if ($password != $password_confirm)
			return (array
			(
				"ERROR_MSG" 	=> "Passwords don't match",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		# Validate Full name
		if (strlen ($full_name) > 512)
			return (array
			(
				"ERROR_MSG" 	=> "Name is too long",
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));


		# Check if user is already registered
		$result = $db_manager -> select (
			'users',
			null,
			"email='$email'"
		);
		if (count($result) > 0)
			return (array
			(
				"ERROR_MSG" 	=> "An users with that email already exists",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		# Hash the password and add a salt
		$password = password_hash ($password, PASSWORD_BCRYPT);

		$permissions = '{ "normal": true, "admin": false }';

		# Create new users
		$result = $db_manager -> insert (
			'users',
			['email', 'password', 'full_name', 'permissions'],
			[$email, $password, $full_name, $permissions]
		);

		# Check if user was created successfully
		if ($result != false)
		{
			$result = $db_manager -> select (
				'users',
				['user_id'],
				"email='$email'"
			);

			return ($result[0]['user_id']);
		}
		else
		{
			return (array (
				"ERROR_MSG" => "Problem creating users. Please try again later"
			));
		}
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

		$result = $db_manager -> select (
			'users',
			['permissions'],
			"user_id='$user_id'"
		);
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

		$result = $db_manager -> update (
			'users',
			[
				'activated'	=> 1
			],
			"user_id='$user_id'"
		);
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

		$result = $db_manager -> select (
			'users',
			['activated'],
			"user_id='$user_id'"
		);
		if (count ($result) == 1)
			return ($result[0]['activated']);

		return null;
	}

	/*
	 * Checks if a password meets the criteria:
	 * 	- At least 8 characters long
	 * 	- At least a number is present
	 * 	- At least an upper case character is present
	 * 	- At least a lower case character is present
	 * 	- At least a special symbol from the '!@#$%^&*()./' list is present
	 *
	 * @param	string	$password	Password to be checked
	 *
	 * @return	mixed				True if password is valid and a string with the check the password failed
	 * 								otherwise
	 */
	private static function validate_password ($password)
	{
		if (strlen ($password) < 8)
			return ("Password must be at least 8 characters long");


		$numbers 		= '0123456789';
		$numbers_count	= 0;

		foreach (str_split($numbers) as $number)
			$numbers_count += substr_count ($password, $number);
		if ($numbers_count == 0)
			return ("Password mush contain at least a number");


		$small_letters 			= 'qwertyuiopasdfghjklzxcvbnm';
		$small_letters_count	= 0;

		foreach (str_split($small_letters) as $number)
			$small_letters_count += substr_count ($password, $number);
		if ($small_letters_count == 0)
			return ("Password mush contain at least a lower case letter");


		$big_letters 		= 'QWERTYUIOPASDFGHJKLZXCVBNM';
		$big_letters_count	= 0;

		foreach (str_split($big_letters) as $number)
			$big_letters_count += substr_count ($password, $number);
		if ($big_letters_count == 0)
			return ("Password mush contain at least a upper case letter");


		$symbols		= '!@#$%^&*()./';
		$symbols_count	= 0;

		foreach (str_split($symbols) as $number)
			$symbols_count += substr_count ($password, $number);
		if ($symbols_count == 0)
			return ("Password mush contain at least a symbol from the list: " . $symbols);

		return true;
	}

	/*
	 * Check if a set of credentials present in the database and are valid
	 *
	 * @param	string	$email		User's email
	 * @param	string	$password	User's password
	 *
	 * @return	mixed				User's ID fi credentials are valid
	 * 								Array with User's ID if email exists but password is incorrect
	 * 								Null otherwise
	 */
	public static function check_credentials ($email, $password)
	{
		$db_manager	= new database_manager ();

		$result = $db_manager -> select (
			'users',
			['user_id', 'password'],
			"email='$email'"
		);

		if (count ($result) == 1)
			if (password_verify ($password, $result[0]['password']) == true)
				return ($result[0]['user_id']);
			else
				return ([$result[0]['user_id']]);

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

		$result = $db_manager -> select (
			'users',
			['full_name'],
			"user_id='$user_id'"
		);
		if (count ($result) == 1)
			return ($result[0]['full_name']);

		return null;
	}
}


?>