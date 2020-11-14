<?php
session_start();

include_once 'database/database_manager.php';
include_once 'users_activity.php';

class users
{
	public static function register()
	{
		# Read all inputs
		$full_name 			= isset($_POST['full_name']) == True ? $_POST['full_name'] : null;
		$email 				= isset($_POST['email']) == True ? $_POST['email'] : null;
		$email_confirm 		= isset($_POST['email_confirm']) == True ? $_POST['email_confirm'] : null;
		$password 			= isset($_POST['password']) == True ? $_POST['password'] : null;
		$password_confirm 	= isset($_POST['password_confirm']) == True ? $_POST['password_confirm'] : null;
		$db_manager 		= new database_manager ();
		$account_activity	= new users_activity ();

		# Check all inputs exist
		if ($full_name == null)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Please enter your full name",
				"email" 		=> $email,
				"email_confirm" => $email_confirm
			));

		if ($email == null)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" => "Please enter an email",
				"full_name" => $full_name
			));

		if ($email_confirm == null)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" => "Please confirm the email",
				"full_name" => $full_name
			));

		if ($password == null)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Please enter a password",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		if ($password_confirm == null)
			self::redirect_with_data ('/views/users/register.php', array
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
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Invalid email",
				"full_name" 	=> $full_name
			));

		if ($email != $email_confirm)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Emails don't match",
				"full_name" 	=> $full_name
			));

		if (strlen ($email) > 255)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Email is too long",
				"full_name" 	=> $full_name
			));

		# Validate password
		$password_validation = account_::validate_password ($password);
		if ($password_validation != True)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> $password_validation,
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		if ($password != $password_confirm)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Passwords don't match",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		# Validate Full name
		if (strlen ($full_name) > 512)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "Name is too long",
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));


		# Check if user is already registered
		$query = "SELECT * FROM users WHERE email='$email'";
		$result = $db_manager -> query ($query);

		if ($result -> num_rows > 0)
			self::redirect_with_data ('/views/users/register.php', array
			(
				"ERROR_MSG" 	=> "An users with that email already exists",
				"full_name" 	=> $full_name,
				"email"			=> $email,
				"email_confirm"	=> $email_confirm
			));

		# Hash the password and add a salt
		$password = password_hash ($password, PASSWORD_BCRYPT);

		# Create new users
		$query = "INSERT INTO users (email, password, full_name) VALUES ('$email', '$password', '$full_name')";
		$result = $db_manager -> query ($query);

		# Check users was created successfully
		if ($result != False)
		{

			# Log activity into db
			$query		= "SELECT user_id FROM users WHERE email='$email'";
			$user_id 	= $db_manager -> query ($query)-> fetch_assoc()['user_id'];
			$account_activity -> add_activity ($user_id, "Account created");

			header('Location: /');
		}
		else
		{
			self::redirect_with_data ('/views/users/register.php', array (
				"ERROR_MSG" => "Problem creating users. Please try again later"
			));
		}
	}

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

		return True;
	}

	private static function redirect_with_data ($url, $data = false)
	{
		if ($data != false)
			foreach ($data as $name => $value)
				$_SESSION[$name] = $value;

		header("Location: $url");
		exit;
	}
}

if(isset($_POST['register']))
{
	users::register ();
}

?>