<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2011                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id:

// Called by ajax request ==============================================================
// @ see checker/js/checker.js
 
define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');

// get user choise on file format
if (isset($_POST['file']) && isset($_POST['problem'])) {
	$file = $_POST['file'];
	$problem = $_POST['problem'];
} 
	
// content to validate	
$uri = '';
if (isset($_SESSION['input_form']['uri'])) {
	$uri = $_SESSION['input_form']['uri'];
	$validate_content = @file_get_contents($uri);
	$input_content_type = $uri;
}

if (isset($_SESSION['input_form']['file'])) {
	$validate_content = $_SESSION['input_form']['file'];
	$input_content_type = 'file';
}

if (isset($_SESSION['input_form']['paste'])) {
	$validate_content = $_SESSION['input_form']['paste'];
	$input_content_type = 'paste';
}

// guidelines	
if (isset($_SESSION['input_form']['gids'])) 		$_gids = $_SESSION['input_form']['gids'];

// report mode
if (isset($_SESSION['input_form']['mode'])) 		$mode = $_SESSION['input_form']['mode'];

// user link id
if (isset($_SESSION['input_form']['user_link_id'])) $user_link_id = $_SESSION['input_form']['user_link_id'];


$html = array();
$error_nr_html = -1;

// validate html
if ($_SESSION['input_form']['enable_html_validation'] == true) {
	include(AC_INCLUDE_PATH. "classes/HTMLValidator.class.php");

	if ($input_content_type == 'file' || $input_content_type == 'paste') {
		$htmlValidator = new HTMLValidator("fragment", $validate_content, true);
	} else {
		$htmlValidator = new HTMLValidator("uri", $input_content_type, true);
	}

	$html = $htmlValidator->getValidationRptArray();
	$error_nr_html = $htmlValidator->getNumOfValidateError();
}

$css = array();
$error_nr_css = -1;
$css_error = '';

// validate css
if ($_SESSION['input_form']['enable_css_validation'] == true) {
	include(AC_INCLUDE_PATH. "classes/CSSValidator.class.php");

	if ($input_content_type == $uri) {
		$cssValidator = new CSSValidator("uri", $input_content_type, true);
	} else {
		// css validator is only available at validating url, not at validating a uploaded file or pasted html
		$css_error = _AC("css_validator_unavailable");
	}
	$css = $cssValidator->getValidationRptArray();
	$error_nr_css = $cssValidator->getNumOfValidateError();
}

if ($problem != 'html' && $problem != 'css') {
	include_once(AC_INCLUDE_PATH. 'classes/AccessibilityValidator.class.php');
	include_once(AC_INCLUDE_PATH. 'classes/FileExportRptGuideline.class.php');
	include_once(AC_INCLUDE_PATH. 'classes/FileExportRptLine.class.php');

	$aValidator = new AccessibilityValidator($validate_content, $_gids, $uri);
	$aValidator->validate();
	$errors = $aValidator->getValidationErrorRpt();
}

// get page title
$title = '';
if (preg_match("/<title>(.+)<\/title>/siU", $validate_content, $matches)) $title = $matches[1];


$known = array();
$likely = array();
$potential = array();
$error_nr_known = 0;
$error_nr_likely = 0;
$error_nr_potential = 0;

// create file
if ($file == 'pdf') {	
	if ($problem != 'html' && $problem != 'css') {
		if ($mode == 'guideline') $a_rpt = new FileExportRptGuideline($errors, $_gids[0], $user_link_id);
		else if ($mode == 'line') $a_rpt = new FileExportRptLine($errors, $user_link_id);
	
		list($known, $likely, $potential) = $a_rpt->generateRpt();
		list($error_nr_known, $error_nr_likely, $error_nr_potential) = $a_rpt->getErrorNr();
	}
	include_once(AC_INCLUDE_PATH. 'fileExport/tfpdf/acheckerTFPDF.class.php');

			header('Content-Type: application/force-download');
		header('Content-transfer-encoding: binary'); 
		header('Content-Disposition: attachment; filename='.$filename.'.csv');
		
		header('x-Sendfile: ', TRUE);
		header('Content-Type: '.pdf);
	
	$pdf = new acheckerTFPDF($known, $likely, $potential, $html, $css, 
		$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error);
	$pdf->getPDF($title, $uri, $problem, $mode, $_gids);	
			
} else if ($file == 'earl' || $file == 'csv') {	
	if ($problem != 'html' && $problem != 'css') {
		$a_rpt = new FileExportRptLine($errors, $user_link_id);
		list($known, $likely, $potential) = $a_rpt->generateRpt();
		list($error_nr_known, $error_nr_likely, $error_nr_potential) = $a_rpt->getErrorNr();
	}
	
	if ($file == 'earl') {
		include_once(AC_INCLUDE_PATH. 'fileExport/acheckerEARL.class.php');
		
		$earl = new acheckerEARL($known, $likely, $potential, $html, $css, 
			$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error);
		$path = $earl->getEARL($problem, $input_content_type, $title, $_gids);
		echo $path;
		exit();
		
	} else if ($file == 'csv') {
		// headers	
//		@header("Last-Modified: " . @gmdate("D, d M Y H:i:s",$_GET['timestamp']) . " GMT");
//		@header("Content-type: text/x-csv");
//		header("Cache-Control: no-cache, must-revalidate");
//	    header("Content-Disposition: attachment; filename=".$filename.".csv");

//!		header('Content-Type: application/force-download');
//		header('Content-transfer-encoding: binary'); 
//		header('Content-Disposition: attachment; filename='.$filename.'.csv');
//		header('x-Sendfile: ', TRUE);
//		header('Content-Type: '.csv);
		
//		header("Pragma: public");
//		header("Expires: 0");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
//		header("Content-Type: application/force-download");
//		header("Content-Type: application/octet-stream");
//		header("Content-Type: application/download");
//		header("Content-Disposition: attachment;filename=data.xls ");
//		header("Content-Transfer-Encoding: binary ");
	    
//	    header('Content-Description: File Transfer');
//		header('Content-Type: application/octet-stream');
//		header('Content-Disposition: attachment; filename='.basename($file));
//		header('Content-Transfer-Encoding: binary');
//		header('Expires: 0');
//		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//		header('Pragma: public');
//		header('Content-Length: ' . filesize($file));
		
	    // file generation
		include_once(AC_INCLUDE_PATH. 'fileExport/acheckerCSV.class.php');		
		$csv = new acheckerCSV($known, $likely, $potential, $html, $css, 
			$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error);
		$path = $csv->getCSV($problem, $input_content_type, $title, $_gids);
		echo $path;
		exit();
	}
}


?>