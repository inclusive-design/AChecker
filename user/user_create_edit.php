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
include_once(AC_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');

// handle submit
if (isset($_POST['cancel'])) {
	header('Location: index.php');
	exit;
} else if (isset($_POST['submit'])) {
	require_once(AC_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');
	$usersDAO = new UsersDAO();
	
	/* password check: password is verified front end by javascript. here is to handle the errors from javascript */
	if ($_POST['password_error'] <> "")
	{
		$pwd_errors = explode(",", $_POST['password_error']);

		foreach ($pwd_errors as $pwd_error)
		{
			if ($pwd_error == "missing_password")
				$missing_fields[] = _AC('password');
			else
				$msg->addError($pwd_error);
		}
	}
	else
	{
		if (!isset($_GET['id']))  // create new user
		{
			$user_id = $usersDAO->Create($_POST['user_group_id'],
	                  $_POST['login'],
			              $_POST['form_password_hidden'],
			              $_POST['email'],
			              $_POST['first_name'],
			              $_POST['last_name'],
			              $_POST['status']);
			
			if (is_int($user_id) && $user_id > 0)
			{
				if (defined('AC_EMAIL_CONFIRMATION') && AC_EMAIL_CONFIRMATION) {
					$msg->addFeedback('REG_THANKS_CONFIRM');
		
					$code = substr(md5($_POST['email'] . $now . $user_id), 0, 10);
					
					$confirmation_link = $_base_href . 'confirm.php?id='.$user_id.SEP.'m='.$code;
		
					/* send the email confirmation message: */
					require(AC_INCLUDE_PATH . 'classes/phpmailer/acheckermailer.class.php');
					$mail = new ACheckerMailer();
		
					$mail->From     = $_config['contact_email'];
					$mail->AddAddress($_POST['email']);
					$mail->Subject = SITE_NAME . ' - ' . _AC('email_confirmation_subject');
					$mail->Body    = _AC('email_confirmation_message', SITE_NAME, $confirmation_link)."\n\n";
		
					$mail->Send();
				} 
				else 
				{
					$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
					header('Location: index.php');
					exit;
				}
			}
		}
		else  // edit existing user
		{
			if ($usersDAO->Update($_GET['id'], 
			                  $_POST['user_group_id'],
	                          $_POST['login'],
			                  $_POST['email'],
			                  $_POST['first_name'],
			                  $_POST['last_name'],
			                  $_POST['status']))
			
			{
				$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
				header('Location: index.php');
				exit;
			}
		}
	}
}
// end of handle submit

// initialize page 
$userGroupsDAO = new UserGroupsDAO();

if (isset($_GET['id'])) // edit existing user
{
	$usersDAO = new UsersDAO();
	$savant->assign('user_row', $usersDAO->getUserByID($_GET['id']));
	$savant->assign('show_password', false);
	
}
else  // create new user
{
	$savant->assign('show_password', true);
	
}
/*****************************/
/* template starts down here */

global $onload;
$onload = 'document.form.login.focus();';

$savant->assign('show_user_group', true);
$savant->assign('show_status', true);
$savant->assign('all_user_groups', $userGroupsDAO->getAll());
$savant->assign('title', _AC('create_edit_user'));
$savant->assign('submit_button_text', _AC('save'));

$savant->display('register.tmpl.php');

?>