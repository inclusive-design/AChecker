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
// $Id: index.php 495 2011-02-10 21:27:00Z cindy $

// Called by ajax request from guidelineline view report -> "make decision(s)" buttons
// @ see checker/js/checker.js

define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH. 'classes/Decision.class.php');

// main process to save decisions
$decision = new Decision($_SESSION['user_id'], $_POST['uri'], $_POST['output'], $_POST['jsessionid']);

if ($decision->hasError())
{
	$decision_error = $decision->getErrorRpt();  // displays in checker_input_form.tmpl.php
	Utility::returnError($decision_error);
}
else
{
	// make decisions
	$decision->makeDecisions($_POST['d']);
	Utility::returnSuccess(_AC('saved_successfully'));
}

exit;
?>
