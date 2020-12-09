<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/users.php');

require_once ($_SERVER['DOCUMENT_ROOT'] . '/email/email_manager.php');

require_once ($_SERVER['DOCUMENT_ROOT'] . '/security/recaptcha.php');

require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_activity_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/login_tokens_controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/register_tokens_controller.php');

class users_controller extends controller
{
	/*
	 * Tries to create a new user. Will redirect the main page with a message if created
	 * successfully. Will redirect to the register page with and error message if an error occurred
	 *
	 * Generates a user activity
	 *
	 * @param	string	$full_name			Full name of the new user
	 * @param	string	$email				Email of the user
	 * @param	string	$email_confirm		Email confirmation of the user
	 * @param	string	$password			Password of the user
	 * @param	string	$password_confirm	Password confirmation of the user
	 *
	 * @return	void						No return value
	 */
	public static function create ($full_name, $email, $email_confirm, $password, $password_confirm)
	{
		if (recaptcha::verify () == false)
			self::redirect_with_data ('/views/account/register.php', ['ERROR_MSG' => 'Complete the reCaptcha to login']);

		$result = users::register ($full_name, $email, $email_confirm, $password, $password_confirm);
		if (is_array ($result))
			self::redirect_with_data ('/views/account/register.php', $result);

		$user_id = $result;

		$register_token = register_tokens_controller::create ($user_id);
		email_manager::activation_email ($email, $full_name, $register_token);
		users_activity_controller::create ($user_id, 'Account created');

		self::redirect_with_data ('/',
									['NORMAL_MSG' => 'Account created. Check your email for the account confirmation']);
	}

	/*
	 * Confirms the registration process of a user based on his registration token
	 * Redirects to the main page if an error occurred
	 * Redirects to the login page with a confirmation message if token is valid
	 * Deletes the register token from the database in the process
	 *
	 * Generates a user activity
	 *
	 * @param	string	$token	Registration token to be checked
	 *
	 * @return	void			No return value
	 */
	public static function confirm_register ($token)
	{
		if ($token == null)
			self::redirect ('/');

		$user_id = register_tokens_controller::check ($token);

		if ($user_id == false)
			self::redirect ('/');

		users::activate ($user_id);
		users_activity_controller::create ($user_id, 'Account activated');

		self::redirect_with_data ('/views/account/login.php',
									['NORMAL_MSG' => 'Account activated. You can login now']);
	}

	/*
	 * Check if a user's account is activated based on a User ID
	 *
	 * Generates a user activity
	 *
	 * @param	int	$user_id	User ID to be checked
	 *
	 * @return	mixed			Null if user doesn't exist and true/false if user's account has been activated
	 */
	private static function is_activated ($user_id)
	{
		return (users::is_activated ($user_id));
	}

	/*
	 * Logs user in based on login info. Redirects to the login page if an error occurs
	 * Redirects to the main page if logged in successfully
	 * If $remember_me is true, a login token will be saved in the users tokens. Otherwise,
	 * the token will be saved only in his session
	 *
	 * Generates a user activity
	 *
	 * @param	string	$email			User email
	 * @param	string	$password		User password
	 * @param	bool	$remember_me	Option to remember the user after the session ended
	 *
	 * @return	void					No return value
	 */
	public static function login ($email, $password, $remember_me)
	{
		if (recaptcha::verify () == false)
			self::redirect_with_data ('/views/account/login.php', ['ERROR_MSG' => 'Complete the reCaptcha to login']);

		if ($email == null)
			self::redirect_with_data ('/views/account/login.php', ['ERROR_MSG' => 'Please enter an email']);

		if ($password == null)
			self::redirect_with_data ('/views/account/login.php', ['ERROR_MSG' => 'Please enter a password',
										'email' => $password]);

		$result = users::check_credentials ($email, $password);
		if ($result == null)
			self::redirect_with_data ('/views/account/login.php', ['ERROR_MSG' => 'Email or password incorrect',
										'email'	=> $email]);

		if (is_array ($result))
		{
			users_activity_controller::create ($result[0], 'Attempted to log in');
			self ::redirect_with_data ('/views/account/login.php', ['ERROR_MSG' => 'Email or password incorrect',
										'email'	=> $email]);
		}

		$user_id = $result;
		if (self::is_activated ($user_id) == 0)
		{
			users_activity_controller::create ($user_id, 'Attempted to log in');
			self ::redirect_with_data ('/views/account/login.php',
				[
					'ERROR_MSG' => "Account hasn't been activated yet",
					'email'		=> $email
				]);
		}

		$expiry_time = time () + 7 * 24 * 3600;
		$login_token = login_tokens_controller::create ($user_id);

		if ($remember_me == true)
		{
			if (setcookie ('user_token', $login_token, $expiry_time, '/', 'blagalucianflorin.ro', true, true) == false)
				self ::redirect_with_data ('/views/account/login.php',
					['ERROR_MSG' => 'Failed to log in. Please try again later']);
		}
		else
			$_SESSION['user_token'] = $login_token;
 
		users_activity_controller::create ($user_id, 'Logged in');

		self::redirect ('/');
	}

	/*
	 * Logs a user out based on his login token. Deletes the token from his cookies and from
	 * his session. Also deletes the cookie from the database. Destroys the user session
	 * in the process. Redirects to the main page
	 *
	 * Generates a user activity
	 *
	 * @param	string	$user_token	Login token saved in cookies/session
	 *
	 * @return	voi					No return value
	 */
	public static function logout ($user_token)
	{
		$user_id = login_tokens_controller::remove_token ($user_token);

		if ($user_id == null)
			self::redirect ('/');

		users_activity_controller::create($user_id, 'Logged out');
		setcookie('user_token', '', 1, '/', 'blagalucianflorin.ro', true, true);
		unset ($_SESSION['user_token']);
		session_destroy();

		self::redirect ('/');
	}

	/*
	 * Checks if a user is currently logged in based on a login token saved in the cookies/session
	 *
	 * @return	mixed	False if user is not logged in and the User ID if a user is logged in
	 */
	public static function is_logged_in ()
	{
		$login_token 	= isset($_COOKIE['user_token']) == True ? $_COOKIE['user_token'] : null;
		if ($login_token == null)
			$login_token = isset($_SESSION['user_token']) == True ? $_SESSION['user_token'] : null;

		if ($login_token == null)
			return false;

		$result = login_tokens_controller::check_token ($login_token);
		if ($result == false)
			return false;

		return $result;
	}

	/*
	 * Gets the full name of the logged in user
	 *
	 * @return	mixed	Null if no user is logged in and a string containing his full name
	 * 					if a user is logged in
	 */
	public static function get_full_name ()
	{
		$is_logged_in 	= self::is_logged_in ();

		if ($is_logged_in == false)
			return null;

		$user_id = $is_logged_in;
		return (users::get_full_name ($user_id));
	}
}

if (isset($_POST['_action']) || isset($_GET['_action']))
{
	if ($_POST['_action'] == 'REGISTER')
	{
		$full_name 			= isset($_POST['full_name']) == True ? $_POST['full_name'] : null;
		$email 				= isset($_POST['email']) == True ? $_POST['email'] : null;
		$email_confirm 		= isset($_POST['email_confirm']) == True ? $_POST['email_confirm'] : null;
		$password 			= isset($_POST['password']) == True ? $_POST['password'] : null;
		$password_confirm 	= isset($_POST['password_confirm']) == True ? $_POST['password_confirm'] : null;

		users_controller::create ($full_name, $email, $email_confirm, $password, $password_confirm);
	}

	if ($_POST['_action'] == 'LOGIN')
	{
		$email 			= isset($_POST['email']) == True ? $_POST['email'] : null;
		$password 		= isset($_POST['password']) == True ? $_POST['password'] : null;
		$remember_me	= isset($_POST['remember_me']);

		users_controller::login ($email, $password, $remember_me);
	}

	if ($_POST['_action'] == 'LOGOUT')
	{
		$user_token 	= isset($_COOKIE['user_token']) == True ? $_COOKIE['user_token'] : null;
		if ($user_token == null)
			$user_token = isset($_SESSION['user_token']) == True ? $_SESSION['user_token'] : null;

		users_controller::logout ($user_token);
	}

	if ($_GET['_action'] == 'CONFIRM_REGISTER')
	{
		$token = isset($_GET['token']) == True ? $_GET['token'] : null;

		users_controller::confirm_register ($token);
	}
}