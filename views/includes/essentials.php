<?php

function value_of_input ($variable)
{
	if (isset ($_SESSION[$variable]))
	{
		$saved = $_SESSION[$variable];
		unset ($_SESSION[$variable]);
		return $saved;
	}
	return "";
}

function pretty_print ($variable)
{
	print("<pre>'".print_r($variable,true)."'</pre>");
}
