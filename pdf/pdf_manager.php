<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/pdf/fpdf.php');

//require_once ($_SERVER['DOCUMENT_ROOT'] . '/controllers/tickets_controller.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class pdf_manager extends FPDF
{
	public static function ticket_pdf ($data)
	{
		$table = new pdf_manager();

		$table ->SetFont('Times','',12);
		$table -> AddPage ();
		$width = $table -> w;
		$height = $table -> h;

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 2, 15, "Ticket", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 2, 15, $data['ticket_id'], 1);
		$table -> Ln();

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 2, 15, "Train", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 2, 15, $data['train_id'], 1);
		$table -> Ln();

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 2, 15, "Price", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 2, 15, $data['Price'], 1);
		$table -> Ln();

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 6, 15, "Wagon", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 6, 15, $data['wagon'], 1);
		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 6, 15, "Seat", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 6, 15, $data['seat'], 1);
		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 6, 15, "Class", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 6, 15, $data['class'], 1);
		$table -> Ln();

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 2, 15, "Purchase date", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 2, 15, $data['purchase_date'], 1);
		$table -> Ln();

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 4, 15, "Start Station", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 4, 15, $data['start_station'], 1);
		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 4, 15, "End Station", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 4, 15, $data['end_station'], 1);
		$table -> Ln();

		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 4, 15, "Start Date", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 4, 15, $data['start_date'], 1);
		$table ->SetFont('Times','B',12);
		$table -> Cell ($width / 4, 15, "End Date", 1);
		$table ->SetFont('Times','',12);
		$table -> Cell ($width / 4, 15, $data['end_date'], 1);
		$table -> Ln();

		$pdf = $table->Output('', 'S');

		return ($pdf);
	}
// Load data
	function LoadData ($file)
	{
		// Read file lines
		$lines = file ($file);
		$data = array ();
		foreach ($lines as $line)
			$data[] = explode (';', trim ($line));
		return $data;
	}

// Simple table
	function BasicTable ($header, $data)
	{
		// Header
		foreach ($header as $col)
			$this -> Cell (40, 7, $col, 1);
		$this -> Ln ();
		// Data
		foreach ($data as $row)
		{
			foreach ($row as $col)
				$this -> Cell (40, 6, $col, 1);
			$this -> Ln ();
		}
	}

// Better table
	function ImprovedTable ($header, $data)
	{
		// Column widths
		$w = array (40, 35, 40, 45);
		// Header
		for ($i = 0; $i < count ($header); $i++)
			$this -> Cell ($w[$i], 7, $header[$i], 1, 0, 'C');
		$this -> Ln ();
		// Data
		foreach ($data as $row)
		{
			$this -> Cell ($w[0], 6, $row[0], 'LR');
			$this -> Cell ($w[1], 6, $row[1], 'LR');
			$this -> Cell ($w[2], 6, number_format ($row[2]), 'LR', 0, 'R');
			$this -> Cell ($w[3], 6, number_format ($row[3]), 'LR', 0, 'R');
			$this -> Ln ();
		}
		// Closing line
		$this -> Cell (array_sum ($w), 0, '', 'T');
	}

// Colored table
	function FancyTable ($header, $data)
	{
		// Colors, line width and bold font
		$this -> SetFillColor (255, 0, 0);
		$this -> SetTextColor (255);
		$this -> SetDrawColor (128, 0, 0);
		$this -> SetLineWidth (.3);
		$this -> SetFont ('', 'B');
		// Header
		$w = array (40, 35, 40, 45);
		for ($i = 0; $i < count ($header); $i++)
			$this -> Cell ($w[$i], 7, $header[$i], 1, 0, 'C', true);
		$this -> Ln ();
		// Color and font restoration
		$this -> SetFillColor (224, 235, 255);
		$this -> SetTextColor (0);
		$this -> SetFont ('');
		// Data
		$fill = false;
		foreach ($data as $row)
		{
			$this -> Cell ($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
			$this -> Cell ($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			$this -> Cell ($w[2], 6, number_format ($row[2]), 'LR', 0, 'R', $fill);
			$this -> Cell ($w[3], 6, number_format ($row[3]), 'LR', 0, 'R', $fill);
			$this -> Ln ();
			$fill = !$fill;
		}
		// Closing line
		$this -> Cell (array_sum ($w), 0, '', 'T');
	}

	public static function test_pdf ()
	{
		$pdf = new pdf_manager();
		// Column headings
		$header = array ('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
		// Data loading
		$data = $pdf -> LoadData ($_SERVER['DOCUMENT_ROOT'] . '/pdf/countries.txt');
		$pdf -> SetFont ('Arial', '', 14);
		$pdf -> AddPage ();
		$pdf -> BasicTable ($header, $data);
		$pdf -> AddPage ();
		$pdf -> ImprovedTable ($header, $data);
		$pdf -> AddPage ();
		$pdf -> FancyTable ($header, $data);
		//$pdf -> Output ('tickets/', 'test_pdf.pdf');
		$pdfdoc = $pdf->Output('', 'S');

		return ($pdfdoc);
	}
}

//pdf_manager::ticket_pdf (tickets_controller::get_ticket (56));