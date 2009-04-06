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
include_once(AC_INCLUDE_PATH. 'classes/Decision.class.php');

global $_current_user;

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
		if (isset($_POST['make_decision'])) $decision->makeDecisions($_POST['d'], $_current_user->getUserName());
		
		// reverse decision
		if (isset($_POST['reverse'])) 
		{
			foreach ($_POST['reverse'] as $sequenceID => $garbage)
				$sequences[] = $sequenceID;
			
			$decision->reverseDecisions($sequences, $_current_user->getUserName());
		}
	}
}
// end of process to made decision

// display initial validation form: input URI or upload a html file 
include ("checker_input_form.php");

// a flag to record if there's problem validating html thru 3rd party web service
$error_happen = false;

if ($_POST["validate_uri"] || $_POST["validate_file"])
{
	// validate html
	if (isset($_POST["enable_html_validation"]))
		include(AC_INCLUDE_PATH. "classes/HTMLValidator.class.php");

	if ($_POST["validate_uri"])
	{
		$uri = $_POST["uri"];
		if (!Utility::isURIValid($uri))
		{
			echo "Error: Cannot connect to <strong>".$uri. "</strong>";
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

// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>
