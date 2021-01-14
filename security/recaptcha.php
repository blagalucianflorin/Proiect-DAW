<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/globals/config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class recaptcha
{
	public static function verify ()
	{
		if (!isset ($_POST['g-recaptcha-response']))
			return (false);

		$secret_key		= RECAPTCHAKEY_PRIVATE;
		$public_key		= $_POST['g-recaptcha-response'];
		$user_ip		= $_SERVER['REMOTE_ADDR'];
		$request_url 	= "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$public_key";
		$request_url	.= "&remoteip=$user_ip";

		$response	= json_decode(file_get_contents ($request_url));

		return ($response -> {'success'});
	}

	public static function public_key ()
	{
		return (RECAPTCHAKEY_PUBLIC);
	}

	public static function invisible_public_key ()
	{
		return (RECAPTCHAKEY_INVISIBLE_PUBLIC);
	}

	public static function verify_invisible ()
	{
		echo ('also here 1    ');
		if (!isset($_POST['g-recaptcha-response']))
			return (false);
		echo ('also here 2    ');

		$secret_key 	= RECAPTCHAKEY_INVISIBLE_PRIVATE;
		$public_key		= $_POST['g-recaptcha-response'];
		$user_ip		= $_SERVER['REMOTE_ADDR'];
		$request_url	= 'https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key."&response=$public_key";
		$request_url	.= "&remoteip=$user_ip";

		$response	= json_decode(file_get_contents ($request_url));
		echo ('also here 3    ');

		return ($response);
		return ($responseCaptchaData->success);
	}
}