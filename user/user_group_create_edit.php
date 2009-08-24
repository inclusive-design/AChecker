<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UserGroupPrivilegeDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/PrivilegesDAO.class.php');

unset($id);  // clean up the temporary id values set by vitals.inc.php

if (isset($_GET["id"])) $id = intval($_GET["id"]);

$userGroupsDAO = new UserGroupsDAO();

// handle submits
if (isset($_POST['cancel'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: user_group.php');
	exit;
} 
else if (isset($_POST['save']))
{
	$title = $addslashes(trim($_POST['title']));	
	
	if ($title == '')
	{
		$msg->addError(array('EMPTY_FIELDS', _AC('title')));
	}
	
	if (!$msg->containsErrors())
	{
		if (isset($id))  // edit existing user group
		{
			$userGroupsDAO->update($id,
			                       $title, 
			                       $addslashes(trim($_POST['description'])));
		}
		else  // create a new user group
		{
			$id = $userGroupsDAO->Create($title, 
			                       $addslashes(trim($_POST['description'])));
		}
			                       
		if (!$msg->containsErrors())
		{
			// add checks
			if (is_array($_POST['add_privileges_id'])) 
			{
				$userGroupPrivilegeDAO = new UserGroupPrivilegeDAO();

				foreach ($_POST['add_privileges_id'] as $add_priv_id)
					$userGroupPrivilegeDAO->Create($id, $add_priv_id);
			}
			
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: user_group.php');
			exit;
		}
	}
}
else if (isset($_POST['remove']))
{
	$userGroupPrivilegeDAO = new UserGroupPrivilegeDAO();
	
	if (is_array($_POST['del_privileges_id']))
	{
		foreach ($_POST['del_privileges_id'] as $del_priv_id)
			$userGroupPrivilegeDAO->Delete($id, $del_priv_id);
	}
}

// interface display
if (!isset($id))
{
	// create user group
	$privilegesDAO = new PrivilegesDAO();
	
	$savant->assign('privs_to_add_rows', $privilegesDAO->getAll());
}
else
{
	// edit existing user group
	$privilegesDAO = new PrivilegesDAO();
	$privs_rows = $privilegesDAO->getUserGroupPrivileges($id);

	// get privs that are not in user group
	unset($str_existing_privs);
	if (is_array($privs_rows))
	{
		foreach($privs_rows as $priv_row)
			$str_existing_privs .= $priv_row['privilege_id'] .',';
		$str_existing_privs = substr($str_existing_privs, 0, -1);
	}
	
	$savant->assign('user_group_row', $userGroupsDAO->getUserGroupByID($id));
	$savant->assign('privs_rows', $privs_rows);
	$savant->assign('privs_to_add_rows', $privilegesDAO->getAllPrivsExceptListed($str_existing_privs));
}

$savant->display('user/user_group_create_edit.tmpl.php');
?>
