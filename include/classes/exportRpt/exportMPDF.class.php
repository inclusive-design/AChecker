<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2018                                         */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id:

/**
* acheckerTFPDF
* Class to generate error report in PDF file (both by lines and by guidelines)
* for each of types: known, likely, potential, html, css and all selected 
* @access	public
* @author	Casian Olga
*/
if (!defined("AC_INCLUDE_PATH")) exit;
 
include_once(AC_INCLUDE_PATH. 'lib/output.inc.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');

use Mpdf\Mpdf as Mpdf;

class acheckerMPDF extends Mpdf {

	function __construct()
	{
		//Call parent constructor
		parent::__construct([
			'debug' => true,
			'mode' => 'utf-8',
			'format' => 'A4-L', 
			'allow_output_buffering' => true,
			'tempDir' => AC_EXPORT_RPT_DIR
		]);
	
	}
	

	public function getPDF() 
	{		
		// set filename
		$date = AC_date('%Y-%m-%d');
		$time = AC_date('%H-%i-%s');
		$filename = 'achecker_'.$date.'_'.$time.$rand_str;		
		$this->SetHeader('Document Title');
		$this->WriteHTML('Document text');
		// close and save PDF document		
		$path = AC_EXPORT_RPT_DIR.$filename.'.pdf';  
		$this->Output($filename.'.pdf', 'F');	
		
		return $path;	
	}
	
	
}
?>