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
require_once(AC_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');

$usersDAO = new UsersDAO();

if (isset($_POST['cancel']))
{
	$msg->addFeedback('CANCELLED');
	header('Location: login.php');
	exit;
}
else if (isset($_POST['form_password_reminder']))
{
	//get database info to create & email change-password-link
	$_POST['form_email'] = $addslashes($_POST['form_email']);

		if ($row = $usersDAO->getUserByEmail($_POST['form_email']))
		{
		//date link was generated (# days since epoch)
		$gen = intval(((time()/60)/60)/24);

		$hash = sha1($row['user_id'] + $gen + $row['password']);
		$hash_bit = substr($hash, 5, 15);

		$change_link = $_base_href.'password_reminder.php?id='.$row['user_id'].'&g='.$gen.'&h='.$hash_bit;
		if($row['first_name'] != ''){
			$reply_name = $row['first_name'];
		}else{
			$reply_name = $row['login'];
		}
		$tmp_message  = _AC(array('password_request2',$reply_name, $_base_href, AC_PASSWORD_REMINDER_EXPIRY, $change_link));

		//send email
		require(AC_INCLUDE_PATH . 'classes/acheckermailer.class.php');
		$mail = new ACheckerMailer;
		$mail->From     = $_config['contact_email'];
		$mail->AddAddress($row['email']);
		$mail->Subject = $_config['site_name'] . ': ' . _AC('password_forgot');
		$mail->Body    = $tmp_message;

		if(!$mail->Send()) {
		   $msg->addError('SENDING_ERROR');
		   $savant->display('password_reminder_feedback.tmpl.php');
		   exit;
		}

		$msg->addFeedback('CONFIRM_EMAIL2');
		unset($mail);

		$savant->display('password_reminder_feedback.tmpl.php');

	} else {
		$msg->addError('EMAIL_NOT_FOUND');
		$savant->display('password_reminder.tmpl.php');
	}

} else if (isset($_REQUEST['id']) && isset($_REQUEST['g']) && isset($_REQUEST['h']))
{
//coming from an email link

	//check if expired
	$current = intval(((time()/60)/60)/24);
	$expiry_date =  $_REQUEST['g'] + AC_PASSWORD_REMINDER_EXPIRY; //2 days after creation

	if ($current > $expiry_date)
	{
		$msg->addError('INVALID_LINK');
		$savant->display('password_reminder_feedback.tmpl.php');
		exit;
	}

	/* check if already visited (possibley add a "last login" field to members table)... if password was changed, won't work anyway. do later. */

	//check for valid hash
	if ($row = $usersDAO->getUserByID(intval($_REQUEST['id'])))
	{
		$email = $row['email'];

		$hash = sha1($_REQUEST['id'] + $_REQUEST['g'] + $row['password']);
		$hash_bit = substr($hash, 5, 15);

		if ($_REQUEST['h'] != $hash_bit)
		{
			$msg->addError('INVALID_LINK');
			$savant->display('password_reminder_feedback.tmpl.php');
		}
		else if (($_REQUEST['h'] == $hash_bit) && !isset($_POST['form_change']))
		{
			$savant->assign('id', $_REQUEST['id']);
			$savant->assign('g', $_REQUEST['g']);
			$savant->assign('h', $_REQUEST['h']);
			$savant->display('change_password.tmpl.php');
		}
	}
	else
	{
		$msg->addError('INVALID_LINK');
		$savant->display('password_reminder_feedback.tmpl.php');
		exit;
	}

	//changing the password
	if (isset($_POST['form_change']))
	{
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

		if (!$msg->containsErrors())
		{
			//save data
			$password   = $addslashes($_POST['form_password_hidden']);

			$usersDAO->setPassword(intval($_REQUEST['id']), $password);

			//send confirmation email
			require(AC_INCLUDE_PATH . 'classes/acheckermailer.class.php');

			$tmp_message  = _AC(array('password_change_confirm', $_config['site_name'], $_base_href))."\n\n";

			$mail = new ACheckerMailer;
			$mail->From     = $_config['contact_email'];
			$mail->AddAddress($email);
			$mail->Subject = $_config['site_name'] . ': ' . _AC('password_forgot');
			$mail->Body    = $tmp_message;

			if(!$mail->Send()) {
			   $msg->printErrors('SENDING_ERROR');
			   exit;
			}

			$msg->addFeedback('PASSWORD_CHANGED');
			unset($mail);

			header('Location:index.php');

		} else {
			$savant->assign('id', $_REQUEST['id']);
			$savant->assign('g', $_REQUEST['g']);
			$savant->assign('h', $_REQUEST['h']);
			$savant->display('change_password.tmpl.php');
		}
	}

} else {
	$savant->display('password_reminder.tmpl.php');
}

?>
