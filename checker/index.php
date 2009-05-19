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

define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/UserLinksDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/Decision.class.php');

$guidelinesDAO = new GuidelinesDAO();

// process to make decision
if (isset($_POST['make_decision']) || isset($_POST['reverse']))
{
	$decision = new Decision($_SESSION['user_id'], $_POST['uri'], $_POST['output'], $_POST['jsessionid']);
	
	if ($decision->hasError())
	{
		$decision_error = $decision->getErrorRpt();  // displays in checker_input_form.tmpl.php
	}
	else
	{
		// make decsions
		if (isset($_POST['make_decision'])) $decision->makeDecisions($_POST['d']);
		
		// reverse decision
		if (isset($_POST['reverse'])) 
		{
			foreach ($_POST['reverse'] as $sequenceID => $garbage)
				$sequences[] = $sequenceID;
			
			$decision->reverseDecisions($sequences);
		}
	}
}
// end of process to made decision

// validate referer URI: error check and initialization
if ($_GET['uri'] == 'referer')
{
	// validate if the URI from referer matches the URI defined in user_links.user_link_id
	if (isset($_GET['id']))
	{
		$userLinksDAO = new UserLinksDAO();
		$row = $userLinksDAO->getByUserLinkID($_GET['id']);
		
		$pos_user_link_uri = strpos($row['URI'], '?');
		if ($pos_user_link_URI > 0) $user_link_uri = substr($row['URI'], 0, $pos_user_link_uri);
		else $user_link_uri = $row['URI'];

		$pos_referer_uri = strpos($_SERVER['HTTP_REFERER'], '?');
		if ($pos_referer_uri > 0) $referer_uri = substr($_SERVER['HTTP_REFERER'], 0, $pos_referer_uri);
		else $referer_uri = $_SERVER['HTTP_REFERER'];
		
		// guideline id must be given if the request is to check referer URI
		 if (!isset($_GET['gid']))
			$msg->addError('EMPTY_GID');
		else
		{
			$grow = $guidelinesDAO->getGuidelineByAbbr($_GET['gid']);
			if (!is_array($grow))
				$msg->addError('INVALID_GID');
		}
		
		if ($user_link_uri <> $referer_uri)
			$msg->addError('REFERER_URI_NOT_MATCH');
		
		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] <> $row['user_id'])
			$msg->addError('USER_NOT_MATCH');
	}
	
	if (!$msg->containsErrors())
	{
		$_POST['validate_uri'] = 1;
		$_POST['uri'] = $_SERVER['HTTP_REFERER'];
		$_POST['gid'] = array($grow[0]['guideline_id']);
	}
}

// a flag to record if there's problem validating html thru 3rd party web service
$error_happen = false;

// validate html
if (isset($_POST["enable_html_validation"]))
	include(AC_INCLUDE_PATH. "classes/HTMLValidator.class.php");

if ($_POST["validate_uri"])
{
	$uri = $_POST["uri"];
	
	// Check if the given URI is connectable
	if (!Utility::isURIValid($uri))
	{
		$msg->addError(array('CANNOT_CONNECT', $uri));
	}
	
	// don't accept localhost URI
	if (stripos($uri, '://localhost') > 0)
	{
		$msg->addError('NOT_LOCALHOST');
	}
	
	if ($msg->containsErrors())
	{
		header('Location:index.php');
		exit;
	}
	else
	{
		$validate_content = @file_get_contents($uri);
		if (isset($_POST["enable_html_validation"]))
			$htmlValidator = new HTMLValidator("uri", $uri);

		if (isset($_POST["show_source"]))
			$source_array = file($uri);
	}
}

if ($_POST["validate_file"])
{
	$validate_content = file_get_contents($_FILES['uploadfile']['tmp_name']);

	if (isset($_POST["enable_html_validation"]))
		$htmlValidator = new HTMLValidator("fragment", $validate_content);

	if (isset($_POST["show_source"]))
		$source_array = file($_FILES['uploadfile']['tmp_name']);
}
// end of validating html

// display initial validation form: input URI or upload a html file 
include ("checker_input_form.php");

// validation and display result
if ($_POST["validate_uri"] || $_POST["validate_file"])
{
	// check accessibility
	include(AC_INCLUDE_PATH. "classes/AccessibilityValidator.class.php");

	if (isset($validate_content))
	{
		$aValidator = new AccessibilityValidator($validate_content, $_POST["gid"]);
		$aValidator->validate();
	}
	// end of checking accessibility

	// display validation results
	if (isset($aValidator) || isset($htmlValidator))
	{
		include ("checker_results.php");
	}
}
else
{
	echo '<div id="output_div" class="validator-output-form">';
	echo _AC('achecker_whatis');
	echo '</div>';
}

// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>
