<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/models/users.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/users_controller.php');

class permissions_controller
{
	/*
	 * Checks if the currently logged in user is an admin
	 *
	 * @return	bool	True is the user is an admin, false if he's not or if no user is logged in
	 */
	public static function is_admin ()
	{
		$user_id = users_controller::is_logged_in ();
		if ($user_id == false)
			return (false);

		return (users::is_admin ($user_id));
	}
}