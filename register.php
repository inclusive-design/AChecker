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

define('AC_INCLUDE_PATH', 'include/');
require (AC_INCLUDE_PATH.'vitals.inc.php');
require (AC_INCLUDE_PATH."securimage/securimage.php");

if (isset($_POST['cancel'])) {
	header('Location: index.php');
	exit;
} else if (isset($_POST['submit'])) {
	$has_error = false;
	
	if ($_SERVER['HTTP_REFERER'] <> AC_BASE_HREF.'register.php') exit;
	
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
		$has_error = true;
	} 
	//CAPTCHA
	if (isset($_POST['captcha_in_use']) && $_POST['captcha_in_use']){
		$img = new Securimage();
		$valid = $img->check($_POST['secret']);
		if (!$valid) {
			$has_error = true;
			$msg->addError('SECRET_ERROR');
		}
	}
	
	if (!$has_error) {
		$user_id = $usersDAO->Create(AC_USER_GROUP_USER,
                  $_POST['login'],
		              $_POST['form_password_hidden'],
		              $_POST['email'],
		              $_POST['first_name'],
		              $_POST['last_name'],
		              '');
		
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
			} else {
				// auto login
				$usersDAO->setLastLogin($user_id);
				$_SESSION['user_id'] = $user_id;
				
				// show web service ID in success message
				$row = $usersDAO->getUserByID($user_id);
				$msg->addFeedback(array('REGISTER_SUCCESS', $row['web_service_id']));
				header('Location: index.php');
				exit;
			}
		}
	}
}

/*****************************/
/* template starts down here */

global $onload;
$onload = 'document.form.login.focus();';

$savant->assign('title', _AC('registration'));
$savant->assign('submit_button_text', _AC('register'));
$savant->assign('show_user_group', false);
$savant->assign('show_status', false);
$savant->assign('show_password', true);
$savant->assign('show_captcha', true);

$savant->display('register.tmpl.php');

?>