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

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	Header('Location: ../index.php');
	exit;
}

if (isset($_POST['submit'])) {
	$missing_fields = array();

	if (!$_POST['first_name']) {
		$missing_fields[] = _AC('first_name');
	}

	if (!$_POST['last_name']) {
		$missing_fields[] = _AC('last_name');
	}

	$_POST['first_name'] = str_replace('<', '', $_POST['first_name']);
	$_POST['last_name'] = str_replace('<', '', $_POST['last_name']);

	if ($missing_fields) {
		$missing_fields = implode(', ', $missing_fields);
		$msg->addError(array('EMPTY_FIELDS', $missing_fields));
	}
	$login = strtolower($_POST['login']);

	if (!$msg->containsErrors()) {
		// insert into the db.
		if (!$_current_user->setName($_POST['first_name'], $_POST['last_name'])) 
		{
			$msg->printErrors('DB_NOT_UPDATED');
			exit;
		}

		$msg->addFeedback('PROFILE_UPDATED');

		header('Location: index.php');
		exit;
	}
}

$row = $_current_user->getInfo();

if (!isset($_POST['submit'])) {
	$_POST = $row;
}

/* template starts here */
$plate['row'] = $row;

global $onload;
$onload = 'document.form.first_name.focus();';

echo $plates->render('profile/profile.tmpl.php', $plate);
?>
