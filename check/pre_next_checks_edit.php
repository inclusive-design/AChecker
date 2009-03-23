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
include_once(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/CheckPrerequisitesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/TestPassDAO.class.php');

if (isset($_GET['id'])) $check_id = $_GET['id'];
$checkPrerequisitesDAO = new CheckPrerequisitesDAO();
$testPassDAO = new TestPassDAO();

// handle submit
if (isset($_POST['cancel'])) {
	header('Location: index.php');
	exit;
} else if (isset($_POST['remove_pre']))
{
	if (is_array($_POST['del_pre_checks_id']))
	{
		foreach ($_POST['del_pre_checks_id'] as $del_check_id)
			$checkPrerequisitesDAO->Delete($check_id, $del_check_id);
	}
}
else if (isset($_POST['remove_next']))
{
	if (is_array($_POST['del_next_checks_id']))
	{
		foreach ($_POST['del_next_checks_id'] as $del_check_id)
			$testPassDAO->Delete($check_id, $del_check_id);
	}
}
// end of handle submit

// initialize page 
$savant->assign('pre_rows', $checkPrerequisitesDAO->getPreChecksByCheckID($check_id));
$savant->assign('next_rows', $testPassDAO->getNextChecksByCheckID($check_id));

/*****************************/
/* template starts down here */

$savant->display('check/pre_next_checks_edit.tmpl.php');

?>