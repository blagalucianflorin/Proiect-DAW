<?php

class controller
{
	/*
	 * Redirects to the specified URL with with the data saved in session variables
	 *
	 * @param	string	$url	URL to redirect to
	 * @param	array	$data	Data to be saved in the session variables
	 *
	 * @return					No return value
	 */
	public static function redirect_with_data ($url, $data)
	{
		if ($data != false)
			foreach ($data as $name => $value)
				$_SESSION[$name] = $value;

		header("Location: $url");
		exit;
	}

	/*
	 * Redirects to the specified URL
	 *
	 * @param	string	$url	URL to redirect to
	 *
	 * @return					No return value
	 */
	public static function redirect ($url)
	{
		self::redirect_with_data ($url, false);
	}
}

?>