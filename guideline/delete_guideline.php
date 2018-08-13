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
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');

$guidelinesDAO = new GuidelinesDAO();

if (isset($_POST['submit_no'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 
else if (isset($_POST['submit_yes']))
{
	if ($guidelinesDAO->Delete($_POST['id']))
	{
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		header('Location: index.php');
		exit;
	}
}

$rows = $guidelinesDAO->getGuidelineByIDs($_GET['id']);

unset($hidden_vars);
$hidden_vars['id'] = $_GET['id'];

require(AC_INCLUDE_PATH.'header.inc.php');

$msg->addConfirm(array('DELETE_GUIDELINE', $rows[0]['title']), $hidden_vars);
$msg->printConfirm();

require(AC_INCLUDE_PATH.'footer.inc.php');
?>
