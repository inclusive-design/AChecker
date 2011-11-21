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
// $Id$

define('AC_INCLUDE_PATH', '../include/');
include_once(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/CheckFuncUtility.class.php');

global $msg, $stripslashes;

if (isset($_GET['id'])) $check_id = intval($_GET['id']);

if ($check_id <= 0)
{
	$msg->addError('ID_ZERO');
	
	include_once(AC_INCLUDE_PATH.'header.inc.php');
	$msg->printAll();
	include_once(AC_INCLUDE_PATH.'footer.inc.php');
	exit;
}

// handle submit
if (isset($_POST['cancel'])) 
{
	header('Location: index.php');
	exit;
} 
else if (isset($_POST['save']) || isset($_POST['save_and_close'])) 
{
	// check syntax
	$func = $stripslashes(trim($_POST['func']));
	
	if (!CheckFuncUtility::validateSyntax($func))
	{
		$msg->addError('SYNTAX_ERROR');
	}
	
	// Prevent the php built-in functions and php super global variables
	// being called in the check function. Only allows the AChecker-defined
	// check functions being called for the security concern.
	CheckFuncUtility::validateSecurity($func);
	
	if (!$msg->containsErrors())
	{
		$checksDAO = new ChecksDAO();
		
		$checksDAO->setFunction($check_id, $func);
	
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		
		if (isset($_POST['save_and_close']))
		{
			header('Location: index.php');
		}
		else
		{
			header('Location: '.$_SERVER['PHP_SELF'].'?id='.$check_id);
		}
		exit;
	}
}
// end of handle submit

// initialize page 
$checksDAO = new ChecksDAO();

if (isset($check_id)) // edit existing check function
{
	$check_row = $checksDAO->getCheckByID($check_id);
	
	if (!$check_row)
	{
		$msg->addError('INVALID_CHECK_ID');
		require(AC_INCLUDE_PATH.'header.inc.php');
		$msg->printAll();
		require(AC_INCLUDE_PATH.'footer.inc.php');
		exit;
	}
	
	$savant->assign('check_row', $check_row);
}

/*****************************/
/* template starts down here */

$savant->display('check/check_function_edit.tmpl.php');

?>