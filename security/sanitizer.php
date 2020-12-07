<?php

class sanitizer
{
	public static function sanitize_email ($email)
	{
		$result = filter_var($email, FILTER_SANITIZE_EMAIL);

		if (!$result)
			return (null);
		else
			return ($result);
	}

	public static function sanitize_string ($string)
	{
		$result = filter_var($string, FILTER_SANITIZE_STRING);

		if (!$result)
			return (null);
		else
			return ($result);
	}

	public static function escape ($string)
	{
		$result = filter_var ($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if (!$result)
			return (null);
		else
			return ($result);
	}
}