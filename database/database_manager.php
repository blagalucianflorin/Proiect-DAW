<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/globals/config.php');

class database_manager
{
	private static $conn = null;

	/*
	 * Constructs the database_manager object and creates the database connection with the
	 * credentials stored in the '/globals/config.php' file
	 *
	 * @return	mixed	True if connection is successful and the error message otherwise
	 */
	function __construct ()
	{
		self::$conn = new mysqli(
			DB_HOST,
			DB_USER,
			DB_PASS,
			DB_NAME
		);

		if (self::$conn -> connect_error)
			return (self::$conn -> connect_error);
		return (true);
	}

	/*
	 * Performs a raw query on the database. Usage should be avoided
	 *
	 * @param	string	$query	Query to be performed
	 *
	 * @return	mixed			Query result if successful and query error if it failed
	 */
	public function query ($query)
	{
		$result = self::$conn -> query ($query);

		if ($result == false)
			return (self::$conn -> error . " " . $query);
		return ($result);
	}

	/*
	 * Inserts data into a table
	 *
	 * @param	string	$table		Table's name
	 * @param	array	$columns	Column's names
	 * @param	array	$values		Values to be inserted
	 *
	 * @return	mixed				True if inserted successfully, query error along with query otherwise
	 */
	public function insert ($table, $columns, $values)
	{
		$query = "INSERT INTO $table(";

		foreach ($columns as $column)
			$query .= "$column,";
		$query = substr($query, 0, -1);
		$query .= ") VALUES (";

		foreach ($values as $value)
			$query .= "'$value',";
		$query = substr($query, 0, -1);
		$query .= ")";

		$result = self::$conn -> query ($query);

		if ($result == false)
			return (self::$conn -> error . " " . $query);
		return (true);
	}

	/*
	 * Select data from a table
	 *
	 * @param	string	$table		Table's name
	 * @param	array	$columns	Columns to select. Can be left out to select all columns
	 * @param	string	$condition	Raw mysql condition. Can be left out to select every row
	 *
	 * @return	mixed				An associative array if query was successful and the query error otherwise
	 */
	public function select ($table, $columns = null, $condition = null)
	{
		$query = "SELECT ";

		if ($columns != null)
		{
			foreach ($columns as $column)
				$query .= "$column,";
			$query = substr ($query, 0, -1);
		}
		else
			$query .= "* ";

		$query .= " FROM $table ";
		if ($condition != null)
			$query .=  "WHERE $condition";

		$result = self::$conn -> query ($query);

		if ($result == false)
			return (self::$conn -> error . " " . $query);
		return ($result -> fetch_all (MYSQLI_ASSOC));
	}

	/*
	 * Delete data from a table
	 *
	 * @param	string	$table		Table's name
	 * @param	string	$condition	Raw mysql condition. Can be left out to delete all rows
	 *
	 * @return	mixed				True if query was successful and a query error otherwise
	 */
	public function delete ($table, $condition = null)
	{
		$query = "DELETE FROM $table ";

		if ($condition != null)
			$query .= "WHERE $condition";

		if (self::$conn -> query ($query) == true)
			return (true);
		else
			return (self::$conn -> error);
	}

	/*
	 * Updates rows in a table
	 *
	 * @param	string	$table		Table's name
	 * @param	array	$new_values	New values to be set. Array must have the structure 'column_name' => 'new_value'
	 * @param	string	$condition	Raw mysql condition. Can be left out to update all rows
	 *
	 * @return	mixed				True if query was successful and a query error otherwise
	 */
	public function update ($table, $new_values, $condition = null)
	{
		$query = "UPDATE $table SET ";

		foreach ($new_values as $column => $new_value)
			$query .= "$column='$new_value',";
		$query = substr ($query, 0, -1);

		if ($condition != null)
			$query .= " WHERE $condition";

		if (self::$conn -> query ($query) == true)
			return (true);
		else
			return (self::$conn -> error);
	}
}
