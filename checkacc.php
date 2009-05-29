<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
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
include_once(AC_INCLUDE_PATH. 'classes/HTMLRpt.class.php');
include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/UserLinksDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/AccessibilityValidator.class.php');
include_once(AC_INCLUDE_PATH. 'classes/HTMLWebServiceOutput.class.php');
include_once(AC_INCLUDE_PATH. 'classes/RESTWebServiceOutput.class.php');

$uri = trim(urldecode($_REQUEST['uri']));
$web_service_id = trim($_REQUEST['id']);
$guide = trim(strtolower($_REQUEST['guide']));
$output = trim(strtolower($_REQUEST['output']));
$offset = intval($_REQUEST['offset']);

// initialize defaults for the ones not set or not set right but with default values
if ($output <> 'html' && $output <> 'rest') 
	$output = DEFAULT_WEB_SERVICE_OUTPUT;
// end of initialization

// validate parameters
if ($uri == '')
{
	$errors[] = 'AC_ERROR_EMPTY_URI';
}
else
{
	if (Utility::getValidURI($uri) === false) $errors[] = 'AC_ERROR_INVALID_URI';
}

if ($web_service_id == '')
{
	$errors[] = 'AC_ERROR_EMPTY_WEB_SERVICE_ID';
}
else
{ // validate web service id
	$usersDAO = new UsersDAO();
	$user_row = $usersDAO->getUserByWebServiceID($web_service_id);

	if (!$user_row) $errors[] = 'AC_ERROR_INVALID_WEB_SERVICE_ID';
	
	$user_id = $user_row['user_id'];
}

// return errors
if (is_array($errors))
{
	if ($output == 'rest')
		echo RESTWebServiceOutput::generateErrorRpt($errors);
	else
		echo HTMLRpt::generateErrorRpt($errors);
	
	exit;
}

// generate guidelines
$guides = explode(',',$guide);

$guidelinesDAO = new GuidelinesDAO();
foreach ($guides as $abbr)
{
	if ($abbr == '') continue;

	$row = $guidelinesDAO->getEnabledGuidelinesByAbbr($abbr);

	if ($row[0]['guideline_id'] <> '') $gids[] = $row[0]['guideline_id'];
}

// set to default guideline if no input guidelines
if (!is_array($gids)) $gids[] = DEFAULT_GUIDELINE;

foreach ($gids as $gid)
	$gidStr .= $gid . ",";

$gidStr = substr($gidStr, 0, -1);

// retrieve user link ID
$userLinksDAO = new UserLinksDAO();
$user_link_id = $userLinksDAO->getUserLinkID($user_id, $uri, $gidStr);

// set new session id
$userLinksDAO->setLastSessionID($user_link_id, Utility::getSessionID());

// validating uri content
$validate_content = @file_get_contents($uri);

if (isset($validate_content))
{
	$aValidator = new AccessibilityValidator($validate_content, $gids);
	$aValidator->setLineOffset($offset);
	$aValidator->validate();
	$errors = $aValidator->getValidationErrorRpt();

	// save errors into user_decisions 
	$userDecisionsDAO = new UserDecisionsDAO();
	$userDecisionsDAO->saveErrors($user_link_id, $errors);
	
	if ($output == 'html')
	{ // generate html output
		$htmlWebServiceOutput = new HTMLWebServiceOutput($aValidator, $user_link_id, $gids);
		echo $htmlWebServiceOutput->getWebServiceOutput();
	}

	if ($output == 'rest')
	{ // generate html output
		$restWebServiceOutput = new RESTWebServiceOutput($errors, $user_link_id, $gids);
		echo $restWebServiceOutput->getWebServiceOutput();
	}
}

?>
