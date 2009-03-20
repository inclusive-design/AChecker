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

// handle submit
if (isset($_POST['cancel'])) {
	header('Location: index.php');
	exit;
} else if (isset($_POST['submit'])) {
	require_once(AC_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');
	$checksDAO = new ChecksDAO();
	
	if (!isset($_GET['id']))  // create new user
	{
		$check_id = $checksDAO->Create($_SESSION['user_id'],
                  $_POST['html_tag'],$_POST['confidence'],'',$_POST['note'],
		          $_POST['name'],$_POST['err'],$_POST['description'],$_POST['long_description'],
		          $_POST['rationale'],$_POST['how_to_repair'],$_POST['repair_example'],
		          $_POST['question'],$_POST['decision_pass'],$_POST['decision_fail'],
		          $_POST['test_procedure'],$_POST['test_expected_result'],
		          $_POST['test_failed_result'],$_POST['open_to_public']);
		
		if (is_int($check_id) && $check_id > 0)
		{
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: index.php');
			exit;
		}
	}
	else  // edit existing check
	{
		if ($checksDAO->Update($_GET['id'], $_SESSION['user_id'],
                  $_POST['html_tag'],$_POST['confidence'],'',$_POST['note'],
		          $_POST['name'],$_POST['err'],$_POST['description'],$_POST['long_description'],
		          $_POST['rationale'],$_POST['how_to_repair'],$_POST['repair_example'],
		          $_POST['question'],$_POST['decision_pass'],$_POST['decision_fail'],
		          $_POST['test_procedure'],$_POST['test_expected_result'],
		          $_POST['test_failed_result'],$_POST['open_to_public']))
		
		{
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: index.php');
			exit;
		}
	}
}
// end of handle submit

// initialize page 
$checksDAO = new ChecksDAO();

if (isset($_GET['id'])) // edit existing user
{
	$savant->assign('check_row', $checksDAO->getCheckByID($_GET['id']));
}

/*****************************/
/* template starts down here */

global $onload;
$onload = 'document.form.html_tag.focus();';

$savant->display('check/check_create_edit.tmpl.php');

?>