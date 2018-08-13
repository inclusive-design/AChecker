<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');

$_GET['version'] = trim($_GET['version']);
$_GET['path'] = trim($_GET['path']);

// Validate the input $_REQUEST values. The values could be $_GET from the caller URL
// or $_POST posted from the confirmation form
if ((!empty($_GET['version']) && !preg_match('/^[0-9.]+$/', $_GET['version'])) || empty($_REQUEST['path'])) {
	$msg->addError('INVALID_ID');
	header('Location: index.php');
	exit;
}

if (isset($_POST['submit_no'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 
else if (isset($_POST['submit_yes']))
{
	if ($languageManager->import_from_path($_POST['path'], true))
	{
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		header('Location: index.php');
		exit;
	}
}

unset($hidden_vars);
$hidden_vars['path'] = $_GET['path'];

require(AC_INCLUDE_PATH.'header.inc.php');

$msg->addConfirm(array('IMPORT_LANG', $_GET['version'], VERSION), $hidden_vars);
$msg->printConfirm();

require(AC_INCLUDE_PATH.'footer.inc.php');
?>
