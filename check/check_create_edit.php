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
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/CheckPrerequisitesDAO.class.php');

if (isset($_GET['id'])) $check_id = $_GET['id'];
$checkPrerequisitesDAO = new CheckPrerequisitesDAO();

// handle submit
if (isset($_POST['cancel'])) {
	header('Location: index.php');
	exit;
} else if (isset($_POST['submit'])) {
	require_once(AC_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');
	$checksDAO = new ChecksDAO();
	
	if (!isset($check_id))  // create new user
	{
		$check_id = $checksDAO->Create($_SESSION['user_id'],
                  $_POST['html_tag'],$_POST['confidence'],'',$_POST['note'],
		          $_POST['name'],$_POST['err'],$_POST['description'],$_POST['long_description'],
		          $_POST['rationale'],$_POST['how_to_repair'],$_POST['repair_example'],
		          $_POST['question'],$_POST['decision_pass'],$_POST['decision_fail'],
		          $_POST['test_procedure'],$_POST['test_expected_result'],
		          $_POST['test_failed_result'],$_POST['open_to_public']);
	}
	else  // edit existing check
	{
		$checksDAO->Update($check_id, $_SESSION['user_id'],
                  $_POST['html_tag'],$_POST['confidence'],'',$_POST['note'],
		          $_POST['name'],$_POST['err'],$_POST['description'],$_POST['long_description'],
		          $_POST['rationale'],$_POST['how_to_repair'],$_POST['repair_example'],
		          $_POST['question'],$_POST['decision_pass'],$_POST['decision_fail'],
		          $_POST['test_procedure'],$_POST['test_expected_result'],
		          $_POST['test_failed_result'],$_POST['open_to_public']);
	}
	
	if (!$msg->containsErrors())
	{
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		header('Location: index.php');
		exit;
	}
}
else if (isset($_POST['remove']))
{
	if (is_array($_POST['del_checks_id']))
	{
		foreach ($_POST['del_checks_id'] as $del_check_id)
			$checkPrerequisitesDAO->Delete($check_id, $del_check_id);
	}
}
// end of handle submit

// initialize page 
$checksDAO = new ChecksDAO();

if (isset($check_id)) // edit existing user
{
	$check_row = $checksDAO->getCheckByID($check_id);
	
	$pre_rows = $checkPrerequisitesDAO->getPreChecksByCheckID($check_id);

	// get author name
	$usersDAO = new UsersDAO();
	$user_name = $usersDAO->getUserName($check_row['user_id']);

	if ($user_name <> '') $savant->assign('author', $user_name);
	
	// get checks that are open to public and not in guideline
	unset($str_existing_pres);
	if (is_array($pre_rows))
	{
		foreach($pre_rows as $pre_row)
			$str_existing_pres .= $pre_row['check_id'] .',';
		$str_existing_pres = substr($str_existing_pres, 0, -1);
	}
	
	$savant->assign('check_row', $check_row);
	$savant->assign('pre_rows', $pre_rows);
	$savant->assign('pre_to_add_rows', $checksDAO->getAllOpenChecksExceptListed($str_existing_pres));
}

/*****************************/
/* template starts down here */

$savant->display('check/check_create_edit.tmpl.php');

?>