<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

/*
 * This is the web service interface to check accessibility on a given URI
 * Expected parameters:
 * id: to identify the user. must be given
 * uri: The URL of the document to validate. must be given
 * guide: The guidelines to validate against. 
 *        can be multiple guides, separated by comma (,)
 * output: html or rest
 * offset: The line offset on the html output from uri where the validation starts.
 */

define('AC_INCLUDE_PATH', 'include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
//include_once(AC_INCLUDE_PATH. "classes/HTMLRpt.class.php");
include_once(AC_INCLUDE_PATH. "classes/HTMLRptVamola.class.php");
include_once(AC_INCLUDE_PATH. 'classes/RESTWebServiceOutput.class.php');
include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/Decision.class.php');

// parse parameters
// use two loops on $_REQUEST is to ensure 'reverse' is set before parsing decisions on sequence IDs
foreach ($_REQUEST as $name => $value)
{
	if ($name == 'uri') $uri = trim(urldecode($value));
	if ($name == 'id') $web_service_id = trim($value);
	if ($name == 'session') $session_id = trim($value);
	if ($name == 'output') $output = trim(strtolower($value));
	if ($name == 'reverse') $reverse = trim($value);
}

foreach ($_REQUEST as $name => $value)
{
	if (is_int($name))
	{
		if ($reverse == 'true')  // reverse decisions. set to "No Decision"
			$decisions[$name] = AC_NO_DECISION;
		else
			$decisions[$name] = $value;
	}
}
// end of parsing parameters

// initialize defaults for the ones not set or not set right but with default values
if ($output <> 'html' && $output <> 'rest') 
	$output = DEFAULT_WEB_SERVICE_OUTPUT;
// end of initialization

// validate parameters
if ($uri == '')
{
	$errors[] = 'AC_ERROR_EMPTY_URI';
}

if ($web_service_id == '')
{
	$errors[] = 'AC_ERROR_EMPTY_WEB_SERVICE_ID';
}
else
{ // validate web service id
	$usersDAO = new UsersDAO();
	$user_row = $usersDAO->getUserByWebServiceID($web_service_id);
	
	if (!$user_row)
	{ 
		$errors[] = 'AC_ERROR_INVALID_WEB_SERVICE_ID';
	}
	else
	{
		$user_id = $user_row['user_id'];
	}
}

if (!is_array($decisions)) $errors[] = 'AC_ERROR_SEQUENCEID_NOT_GIVEN';

// return errors
if (is_array($errors))
{
	if ($output == 'rest')
		echo RESTWebServiceOutput::generateErrorRpt($errors);
	else
		echo HTMLRpt::generateErrorRpt($errors);
	
	exit;
}

// make decisions
$decision = new Decision($user_id, $uri, $output, $session_id);

if ($decision->hasError())
{
	$decision_error = $decision->getErrorRpt();  // displays in checker_input_form.tmpl.php
}
else
{
	// make decsions
	$decision->makeDecisions($decisions);

	if ($output == 'rest')
		echo RESTWebServiceOutput::generateSuccessRpt();
	else
		echo HTMLRpt::generateSuccessRpt();
}
?>
