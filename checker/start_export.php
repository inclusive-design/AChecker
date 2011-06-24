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
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/FileExportRptGuideline.class.php');
include_once(AC_INCLUDE_PATH. 'classes/FileExportRptLine.class.php');
include_once(AC_INCLUDE_PATH. 'fileExport/fpdf/acheckerFPDF.class.php');
include_once(AC_INCLUDE_PATH. 'fileExport/tcpdf/acheckerTCPDF.class.php');

// get user choise on file format
if (isset($_POST['file']) && isset($_POST['problem'])) {
	$file = $_POST['file'];
	$problem = $_POST['problem'];
} 
	
$uri = '';
// content to validate	
if (isset($_SESSION['input_form']['uri'])) {
	$uri = $_SESSION['input_form']['uri'];
	$validate_content = @file_get_contents($uri);
}

if (isset($_SESSION['input_form']['file'])) 		$validate_content = $_SESSION['input_form']['file'];

if (isset($_SESSION['input_form']['paste']))		$validate_content = $_SESSION['input_form']['paste'];

// guidelines	
if (isset($_SESSION['input_form']['gids'])) 		$_gids = $_SESSION['input_form']['gids'];

// report mode
if (isset($_SESSION['input_form']['mode'])) 		$mode = $_SESSION['input_form']['mode'];

// user link id
if (isset($_SESSION['input_form']['user_link_id'])) $user_link_id = $_SESSION['input_form']['user_link_id'];

$aValidator = new AccessibilityValidator($validate_content, $_gids, $uri);

// get page title
$title = '';
if (preg_match("/<title>(.+)<\/title>/siU", $validate_content, $matches)) {
	$title = html_entity_decode($matches[1]);
}

$aValidator->validate();
$errors = $aValidator->getValidationErrorRpt();

$guidelinesDAO = new GuidelinesDAO();
$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_gids);
unset($guidelines_text);	
if (is_array($guideline_rows))
{
	foreach ($guideline_rows as $id => $row)
	{
		$guidelines_text .= $row["title"]. ', ';
	}
}
$guidelines_text = substr($guidelines_text, 0, -2); // remove ending space and ,

if ($mode == 'guideline') $a_rpt = new FileExportRptGuideline($errors, $_gids[0], $user_link_id);
else if ($mode == 'line') $a_rpt = new FileExportRptLine($errors, $user_link_id);

list($known, $likely, $potential) = $a_rpt->generateRpt();
list($error_nr_known, $error_nr_likely, $error_nr_potential) = $a_rpt->getErrorNr();

if ($file == 'pdf') {
	$pdf = new acheckerFPDF($known, $likely, $potential, $error_nr_known, $error_nr_likely, $error_nr_potential, $user_link_id);
	$pdf->getGuidelinePDF($title, $uri, $problem, $mode, $guidelines_text);			
}

// uncomment to use TCPDF instead of FPDF
//if ($file == 'pdf') {
//	$pdf = new acheckerTCPDF($problem);
//	$pdf->getGuidelinePDF($known);			
//}

?>