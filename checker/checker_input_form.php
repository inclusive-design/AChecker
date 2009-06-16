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

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined in checker_input_form.php.");

global $_current_user, $decision_error;

$default_uri_value = "";
$num_of_guidelines_per_row = 3;  // default number of guidelines to display in a row on the page

if (!isset($_POST["gid"])) $_POST["gid"] = array(DEFAULT_GUIDELINE);

$guidelinesDAO = new GuidelinesDAO();
$open_guidelines = $guidelinesDAO->getOpenGuidelines();

$savant->assign('default_uri_value', $default_uri_value);
$savant->assign('num_of_guidelines_per_row', $num_of_guidelines_per_row);

if (isset($_current_user))
{
	$user_guidelines = $guidelinesDAO->getClosedEnabledGuidelinesByUserID($_SESSION['user_id']);
	if (is_array($user_guidelines)) 
		$guidelines = array_merge($open_guidelines, $user_guidelines);
	else
		$guidelines = $open_guidelines;
}
else
{
	$guidelines = $open_guidelines;
	
}

if (isset($decision_error)) $savant->assign('error', $decision_error);
$savant->assign('rows', $guidelines);

$savant->display('checker/checker_input_form.tmpl.php');
?>
