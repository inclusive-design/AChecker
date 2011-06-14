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
include_once(AC_INCLUDE_PATH. 'classes/FileExportRpt.class.php');
//include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');

// get user choise on file format
if (isset($_POST['file']) && isset($_POST['problem'])) {
	$file = $_POST['file'];
	$problem = $_POST['problem'];
} 

// guidelines	
if (isset($_SESSION['input_form']['gids'])) {
	$_gids = $_SESSION['input_form']['gids'];
}
	
// content to validate	
if (isset($_SESSION['input_form']['uri'])) {
	$uri = $_SESSION['input_form']['uri'];
	$validate_content = @file_get_contents($uri);
}

if (isset($_SESSION['input_form']['file'])) {
	$validate_content = $_SESSION['input_form']['file'];
}

if (isset($_SESSION['input_form']['paste'])) {
	$validate_content = $_SESSION['input_form']['paste'];
}

// user link id
if (isset($_SESSION['user_link_id'])) {
	$user_link_id = $_SESSION['user_link_id'];
}

$aValidator = new AccessibilityValidator($validate_content, $_gids, $uri);
$aValidator->validate();

$guidelinesDAO = new GuidelinesDAO();
$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_gids);

unset($guidelines_text);	
if (is_array($guideline_rows))
{
	foreach ($guideline_rows as $id => $row)
	{
		$guidelines_text .= '<a title="'.$row["title"]._AC('link_open_in_new').'" target="_new" href="'.AC_BASE_HREF.'guideline/view_guideline.php?id='.$row["guideline_id"].'">'.$row["title"]. '</a>, ';
	}
}
$guidelines_text = substr($guidelines_text, 0, -2); // remove ending space and ,

$num_of_total_a_errors = $aValidator->getNumOfValidateError();

$errors = $aValidator->getValidationErrorRpt();	

$a_rpt = new FileExportRpt($errors, $_gids[0], $user_link_id);
$a_rpt->setAllowSetDecisions('false');
$a_rpt->generateRpt();
$a_rpt->getFile($file, $problem);
/*
$num_of_errors = $a_rpt->getNumOfErrors();
$num_of_likely_problems = $a_rpt->getNumOfLikelyProblems();
$num_of_potential_problems = $a_rpt->getNumOfPotentialProblems();
	
// no any problems or all problems have pass decisions, display seals when no errors
if ($num_of_errors == 0 && 
	$num_of_likely_problems == 0 && 
	$num_of_potential_problems == 0) 
{
	$utility = new Utility();
	$seals = $utility->getSeals($guideline_rows);
}*/
?>