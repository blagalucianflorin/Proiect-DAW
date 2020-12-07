<?php

class validator
{
	public static function validate ($conditions) : bool
	{
		foreach ($conditions as $condition)
		{
			switch ($condition[0])
			{
				case 'min_length':
					if (!self::min_length (array_slice ($condition, 1)))
						return (false);
					break;

				case 'max_length':
					if (!self::max_length (array_slice ($condition, 1)))
						return (false);
					break;

				case 'matches':
					if (!self::matches (array_slice ($condition, 1)))
						return (false);
					break;

				case 'exists':
					if (!self::exists (array_slice ($condition, 1)))
						return (false);
					break;

				case 'is_email':
					if (!self::is_email (array_slice ($condition, 1)))
						return (false);
					break;

				case 'includes':
					if (!self::includes (array_slice ($condition, 1)))
						return (false);
					break;
			}
		}
		return (true);
	}

	private static function includes ($input_raw) : bool
	{
		$input		= $input_raw[0];
		$symbols	= $input_raw[1];
		$min_count	= $input_raw[2];
		$count		= 0;

		foreach (str_split($symbols) as $symbol)
			$count += substr_count ($input, $symbol);

		if ($count < $min_count)
			return (false);
		else
			return (true);
	}

	private static function min_length ($input_raw) : bool
	{
		$input	= $input_raw[0];
		$length	= $input_raw[1];

		if (strlen ($input) < $length)
			return (false);
		else
			return (true);
	}

	private static function max_length ($input_raw) : bool
	{
		$input	= $input_raw[0];
		$length	= $input_raw[1];

		if (strlen ($input) > $length)
			return (false);
		else
			return (true);
	}

	private static function exists ($input_raw) : bool
	{
		if ($input_raw[0] == null)
			return (false);
		else
			return (true);
	}

	private static function matches ($input_raw) : bool
	{
		$first_value	= $input_raw[0];
		$second_value	= $input_raw[1];

		if ($first_value == $second_value)
			return (true);
		else
			return (false);
	}

	private static function is_email ($input_raw) : bool
	{
		$email	= $input_raw[0];

		if (filter_var($email, FILTER_VALIDATE_EMAIL))
			return (true);
		else
			return (false);
	}

	public static function validate_email ($email, $email_confirm)
	{
		if (self::validate ([
			['exists', $email],
			['exists', $email_confirm],
			['is_email', $email],
			['max_length', $email, 255],
			['matches', $email, $email_confirm]
		]))
			return (true);
		else
			return (false);
	}

	public static function validate_password ($password, $password_confirm)
	{
		if (self::validate ([
			['exists', $password],
			['exists', $password_confirm],
			['min_length', $password, 8],
			['max_length', $password, 64],
			['matches', $password, $password_confirm],
			['includes', $password, '0123456789', 1],
			['includes', $password, 'qwertyuiopasdfghjklzxcvbnm', 1],
			['includes', $password, 'QWERTYUIOPASDFGHJKLZXCVBNM', 1]
		]))
			return (true);
		else
			return (false);
	}

	public static function validate_name ($name)
	{
		if (self::validate ([
			['exists', $name],
			['min_length', $name, 6],
			['max_length', $name, 128]
		]))
			return (true);
		else
			return (false);
	}
}