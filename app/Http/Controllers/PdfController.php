<?php

namespace App\Http\Controllers;

use \Barryvdh\DomPDF\PDF;

class PdfController extends Controller
{

	public function generatePdf(){

		$pdf = app()->make('dompdf.wrapper');
		$pdf->loadHTML('<h1>Test</h1>');
		$pdf->loadFile(base_path().'/resources/views/tes.html');
		return $pdf->stream();
	}
}
