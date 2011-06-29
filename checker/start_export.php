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
include_once(AC_INCLUDE_PATH. 'classes/AccessibilityValidator.class.php');

include_once(AC_INCLUDE_PATH. 'classes/FileExportRptGuideline.class.php');
include_once(AC_INCLUDE_PATH. 'classes/FileExportRptLine.class.php');

include_once(AC_INCLUDE_PATH. 'fileExport/tfpdf/acheckerTFPDF.class.php');
include_once(AC_INCLUDE_PATH. 'fileExport/acheckerEARL.class.php');
include_once(AC_INCLUDE_PATH. 'fileExport/acheckerCSV.class.php');

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

$aValidator = new AccessibilityValidator($validate_content, $_gids, $uri);
$aValidator->validate();
$errors = $aValidator->getValidationErrorRpt();

// get page title
$title = '';
if (preg_match("/<title>(.+)<\/title>/siU", $validate_content, $matches)) {
	$title = $matches[1]; //html_entity_decode($matches[1]); //mb_convert_encoding(html_entity_decode($matches[1]), "Windows-1251", "utf-8");
//	debug_to_log(mb_detect_encoding($title, 'auto'));
}

// create file
if ($file == 'pdf') {
//	$title = mb_convert_encoding($title, "ISO-8859-1", "Windows-1251"); // mb_detect_encoding($title, 'auto')
	
	if ($mode == 'guideline') $a_rpt = new FileExportRptGuideline($errors, $_gids[0], $user_link_id);
	else if ($mode == 'line') $a_rpt = new FileExportRptLine($errors, $user_link_id);
	
	list($known, $likely, $potential) = $a_rpt->generateRpt();
	list($error_nr_known, $error_nr_likely, $error_nr_potential) = $a_rpt->getErrorNr();

	$pdf = new acheckerTFPDF($known, $likely, $potential, $error_nr_known, $error_nr_likely, $error_nr_potential);
	$pdf->getPDF($title, $uri, $problem, $mode, $_gids);	
			
} else if ($file == 'earl' || $file == 'csv') {	
	$a_rpt = new FileExportRptLine($errors, $user_link_id);
	list($known, $likely, $potential) = $a_rpt->generateRpt();
	list($error_nr_known, $error_nr_likely, $error_nr_potential) = $a_rpt->getErrorNr();
	
	if ($file == 'earl') {
		$earl = new acheckerEARL($known, $likely, $potential, $error_nr_known, $error_nr_likely, $error_nr_potential);
		$earl->getEARL($problem, $input_content_type, $title, $_gids);
	} else if ($file == 'csv') {	
		$csv = new acheckerCSV($known, $likely, $potential, $error_nr_known, $error_nr_likely, $error_nr_potential);
		$csv->getCSV($problem, $input_content_type, $title, $_gids);
	}
}


?>