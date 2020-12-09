<?php

if (session_id () == '') session_start();

require_once ($_SERVER['DOCUMENT_ROOT'] . '/globals/config.php');

class recaptcha
{
	public static function verify ()
	{
		if (!isset ($_POST['g-recaptcha-response']))
			return (false);

		$secret_key		= RECAPTCHAKEY;
		$public_key		= $_POST['g-recaptcha-response'];
		$user_ip		= $_SERVER['REMOTE_ADDR'];
		$request_url 	= "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$public_key";
		$request_url	.= "&remoteip=$user_ip";

		$response	= json_decode(file_get_contents ($request_url));

		return ($response -> {'success'});
	}
}