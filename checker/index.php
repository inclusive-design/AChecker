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
include(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include(AC_INCLUDE_PATH. 'classes/DAO/ChecksDAO.class.php');

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
		if (!is_uri_valid($uri))
		{
			echo "Error: Cannot connect to <strong>".$uri. "</strong>";
		}
		else
		{
			$validate_content = @file_get_contents($uri);
			if (isset($_POST["enable_html_validation"]))
				$htmlValidator = new HTMLValidator("uri", $uri);
		}
	}

	if ($_POST["validate_file"])
	{
		$validate_content = file_get_contents($_FILES['uploadfile']['tmp_name']);

		if (isset($_POST["enable_html_validation"]))
			$htmlValidator = new HTMLValidator("fragment", $validate_content);
	}
	// end of validating html

	// check accessibility
	include(AC_INCLUDE_PATH. "classes/AccessibilityValidator.class.php");

	if (isset($validate_content))
	{
		$aValidator = new AccessibilityValidator($validate_content, $_POST["gid"]);
		$aValidator->setLineOffset(10);
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
