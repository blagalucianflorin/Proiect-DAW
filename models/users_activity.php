<?php

include_once 'database/database_manager.php';

class users_activity
{
	public function add_activity ($user_id, $action)
	{
		$ip 		= $_SERVER['REMOTE_ADDR'];
		$db_manager	= new database_manager ();

		$query = "INSERT INTO users_activity (user_id, action, ip) VALUES ('$user_id', '$action', '$ip')";
		return ($db_manager -> query ($query));
	}
}

?>