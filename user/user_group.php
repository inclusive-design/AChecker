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
include_once(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/PrivilegesDAO.class.php');

// handle submit
if ( isset($_GET['edit']) && isset($_GET['id']) && count($_GET['id']) > 1) {
	$msg->addError('SELECT_ONE_ITEM');
} else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location: user_group_create_edit.php?id='.$_GET['id'][0]);
	exit;
} else if ( isset($_GET['delete'], $_GET['id'])) {
	// cannot delete "admin" and "user" groups
	foreach ($_GET['id'] as $id)
	{
		if ($id == AC_USER_GROUP_ADMIN || $id == AC_USER_GROUP_USER)
		{
			$msg->addError('USER_GROUP_CANNOT_DELETE');
			break;
		}
	}
	
	if (!$msg->containsErrors())
	{
		$ids = implode(',', $_GET['id']);
		header('Location: user_group_delete.php?id='.$ids);
		exit;
	}
} else if (isset($_GET['edit']) || isset($_GET['delete']) ) {
	$msg->addError('NO_ITEM_SELECTED');
}

$userGroupsDAO = new UserGroupsDAO();
$privilegesDAO = new PrivilegesDAO();

$plate['user_group_rows'] = $userGroupsDAO->getAll();
$plate['privilegesDAO'] = $privilegesDAO;

echo $plates->render('user/user_group.tmpl.php', $plate);
?>
