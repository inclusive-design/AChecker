<?php
/****************************************************************/
/* ATutor                                                       */
/****************************************************************/
/* Copyright (c) 2002-2008 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca                                             */
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id: registration.php 7865 2008-09-10 18:26:14Z greg $
$_user_location	= 'public';

define('AC_INCLUDE_PATH', 'include/');
require (AC_INCLUDE_PATH.'vitals.inc.php');

if (isset($_POST['cancel'])) {
	header('Location: index.php');
	exit;
} else if (isset($_POST['submit'])) {
	global $user;
	
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
		$user_id = $user->Create(AC_USER_GROUP_USER,
                  $_POST['login'],
		              $_POST['form_password_hidden'],
		              $_POST['email'],
		              $_POST['first_name'],
		              $_POST['last_name']);
		
		if (is_int($user_id) && $user_id > 0)
		{
			if (defined('AC_EMAIL_CONFIRMATION') && AC_EMAIL_CONFIRMATION) {
				$msg->addFeedback('REG_THANKS_CONFIRM');
	
				$code = substr(md5($_POST['email'] . $now . $m_id), 0, 10);
				
				$confirmation_link = $_base_href . 'confirm.php?id='.$m_id.SEP.'m='.$code;
	
				/* send the email confirmation message: */
				require(AC_INCLUDE_PATH . 'classes/phpmailer/acheckermailer.class.php');
				$mail = new ACheckerMailer();
	
				$mail->From     = $_config['contact_email'];
				$mail->AddAddress($_POST['email']);
				$mail->Subject = SITE_NAME . ' - ' . _AC('email_confirmation_subject');
				$mail->Body    = _AC('email_confirmation_message', SITE_NAME, $confirmation_link);
	
				$mail->Send();
			} 
			else 
			{
				// auto login
				$user->updateLastLoginTime($user_id);
				$_SESSION['user_id'] = $user_id;
				$msg->addFeedback('LOGIN_SUCCESS');
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

$savant->display('register.tmpl.php');

?>