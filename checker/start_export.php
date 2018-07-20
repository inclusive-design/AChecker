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

// Called by ajax request; main file to generate files
// @ see checker/js/checker.js

define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');

// first of all delete too old files from temp folder
define(MINUTE, time() - 60); // minute ago
define(HOUR, time() - 60*60); // hour ago
define(DAY, time() - 60*60*24); // day ago
define(WEEK, time() - 60*60*24*7); // week ago

if ($handle = opendir(AC_EXPORT_RPT_DIR)) {
    while (false !== ($file = readdir($handle))) {
        $file_delete_pattern = '/achecker_(.*)/';
        if(preg_match($file_delete_pattern, $file, $match)) {
			// chose 1 from MINUTE|HOUR|DAY|WEEK
        	if (MINUTE > filectime(AC_EXPORT_RPT_DIR.$file)) {
        		unlink(AC_EXPORT_RPT_DIR.$file);
        	}
        }
    }
    closedir($handle);
}

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


$html = '';
$error_nr_html = -1;
$html_error = '';

// validate html
if ($_SESSION['input_form']['enable_html_validation'] == true) {
	include(AC_INCLUDE_PATH. "classes/HTMLValidator.class.php");

	if ($input_content_type == 'file' || $input_content_type == 'paste') {
		if ($file == 'html') {
			$htmlValidator = new HTMLValidator("fragment", $validate_content);
			$html = $htmlValidator->getValidationRpt();
		} else {
			$htmlValidator = new HTMLValidator("fragment", $validate_content, true);
			$html = $htmlValidator->getValidationRptArray();
		}

	} else {
		if ($file == 'html') {
			$htmlValidator = new HTMLValidator("uri", $input_content_type);
			$html = $htmlValidator->getValidationRpt();
		} else {
			$htmlValidator = new HTMLValidator("uri", $uri, true);
			$html = $htmlValidator->getValidationRptArray();
		}
	}

	if ($htmlValidator->containErrors()) {
		$html_error = $htmlValidator->getErrorMsg();
	}

	$error_nr_html = $htmlValidator->getNumOfValidateError();
}

$css = '';
$error_nr_css = -1;
$css_error = '';

// validate css
if ($_SESSION['input_form']['enable_css_validation'] == true) {
	include(AC_INCLUDE_PATH. "classes/CSSValidator.class.php");

	if ($input_content_type == $uri) {
		$cssValidator = new CSSValidator("uri", $input_content_type, true);
		if ($file == 'html') $css = $cssValidator->getValidationRpt();
		else $css = $cssValidator->getValidationRptArray();
		$error_nr_css = $cssValidator->getNumOfValidateError();

		if ($cssValidator->containErrors())
			$css_error = $cssValidator->getErrorMsg();
	} else {
		// css validator is only available at validating url, not at validating a uploaded file or pasted html
		$css_error = _AC("css_validator_unavailable");
	}
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

// create file depending on user choice
if ($file == 'pdf') {
		include_once(AC_INCLUDE_PATH. 'classes/exportRpt/exportMPDF.class.php');

	$pdf = new acheckerMPDF();
	return $pdf->getPDF();

	// include_once(AC_INCLUDE_PATH. 'classes/exportRpt/exportTFPDF.class.php');

	// $pdf = new acheckerTFPDF($known, $likely, $potential, $html, $css,
	// 	$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error, $html_error);
	// $path = $pdf->getPDF($title, $uri, $problem, $mode, $_gids);


} else {
	if ($problem != 'html' && $problem != 'css') {
		$a_rpt = new FileExportRptLine($errors, $user_link_id);
		list($known, $likely, $potential) = $a_rpt->generateRpt();
		list($error_nr_known, $error_nr_likely, $error_nr_potential) = $a_rpt->getErrorNr();
	}

	if ($file == 'earl') {
		include_once(AC_INCLUDE_PATH. 'classes/exportRpt/exportEARL.class.php');

		$earl = new acheckerEARL($known, $likely, $potential, $html, $css,
			$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error, $html_error);
		$path = $earl->getEARL($problem, $input_content_type, $title, $_gids);

	} else if ($file == 'csv') {
		include_once(AC_INCLUDE_PATH. 'classes/exportRpt/exportCSV.class.php');

		$csv = new acheckerCSV($known, $likely, $potential, $html, $css,
			$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error, $html_error);
		$path = $csv->getCSV($problem, $input_content_type, $title, $_gids);

	} else if ($file == 'html') {
		include_once(AC_INCLUDE_PATH. 'classes/exportRpt/exportHTML.class.php');
		$html_file = new acheckerHTML($known, $likely, $potential, $html, $css,
			$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error, $html_error);
		$path = $html_file->getHTMLfile($problem, $_gids, $errors, $user_link_id);

	}
}

echo $path;
exit();

?>
