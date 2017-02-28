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
require(AC_INCLUDE_PATH.'vitals.inc.php');
require_once(AC_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');

global $_current_user;

if (!isset($_current_user))
{
	require(AC_INCLUDE_PATH.'header.inc.php');
	$msg->printInfos('INVALID_USER');
	require(AC_INCLUDE_PATH.'footer.inc.php');
	exit;
}

if (isset($_POST['cancel']))
{
	$msg->addFeedback('CANCELLED');
	Header('Location: ../index.php');
	exit;
}

if (isset($_POST['submit']))
{
	$this_password = $_POST['form_password_hidden'];

	// password check
	if (!empty($this_password))
	{
		//check if old password entered is correct
		if ($row = $_current_user->getInfo())
		{
			if ($row['password'] != $this_password)
			{
				$msg->addError('WRONG_PASSWORD');
				Header('Location: change_email.php');
				exit;
			}
		}
	}
	else
	{
		$msg->addError(array('EMPTY_FIELDS', _AC('password')));
		header('Location: change_email.php');
		exit;
	}

	// email check
	if ($_POST['email'] == '')
	{
		$msg->addError(array('EMPTY_FIELDS', _AC('email')));
	}
	else
	{
		if(!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $_POST['email']))
		{
			$msg->addError('EMAIL_INVALID');
		}

		$usersDAO = new UsersDAO();
		$row = $usersDAO->getUserByEmail($_POST['email']);
		if ($row['user_id'] > 0 && $row['user_id'] <> $_SESSION['user_id'])
		{
			$msg->addError('EMAIL_EXISTS');
		}
	}

	if (!$msg->containsErrors())
	{
		if (defined('AC_EMAIL_CONFIRMATION') && AC_EMAIL_CONFIRMATION)
		{
			//send confirmation email
			$row    = $_current_user->getInfo();

			if ($row['email'] != $_POST['email']) {
				$code = substr(md5($_POST['email'] . $row['creation_date'] . $_SESSION['user_id']), 0, 10);
				$confirmation_link = AC_BASE_HREF . 'confirm.php?id='.$_SESSION['user_id'].SEP .'e='.urlencode($_POST['email']).SEP.'m='.$code;

				/* send the email confirmation message: */
				require(AC_INCLUDE_PATH . 'classes/acheckermailer.class.php');
				$mail = new ACheckerMailer();

				$mail->From     = $_config['contact_email'];
				$mail->AddAddress($_POST['email']);
				$mail->Subject = SITE_NAME . ' - ' . _AC('email_confirmation_subject');
				$mail->Body    = _AC('email_confirmation_message2', $_config['site_name'], $confirmation_link);

				$mail->Send();

				$msg->addFeedback('CONFIRM_EMAIL');
			} else {
				$msg->addFeedback('CHANGE_TO_SAME_EMAIL');
			}
		} else {

		//insert into database
		$_current_user->setEmail($_POST[email]);

		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		}
	}
}

$row = $_current_user->getInfo();

if (!isset($_POST['submit'])) {
	$_POST = $row;
}

/* template starts here */
$savant->assign('row', $row);
$savant->display('profile/change_email.tmpl.php');

?>
