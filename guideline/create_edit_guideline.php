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
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');

global $_current_user;

if (isset($_GET["id"])) $gid = intval($_GET["id"]);
if ($gid == 0) unset($gid);

$guidelinesDAO = new GuidelinesDAO();
$guidelineGroupsDAO = new GuidelineGroupsDAO();
$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
$subgroupChecksDAO = new SubgroupChecksDAO();

// handle submits
if (isset($_POST['cancel'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 
// check on isset($_POST['title']) is to handle javascript submit request for unsaved changes
else if (isset($_POST['save_no_close']) || isset($_POST['save_and_close']) || isset($_POST['title']))
{
	if (isset($gid))  // edit existing guideline
	{
		$guidelinesDAO->update($gid,
		                       $_POST['user_id'], 
		                       $_POST['title'], 
		                       $_POST['abbr'],
		                       $_POST['long_name'],
		                       $_POST['published_date'],
		                       $_POST['earlid'],
		                       '',
		                       $_POST['status'],
		                       $_POST['open_to_public']);
	}
	else  // create a new guideline
	{
		$gid = $guidelinesDAO->Create($_SESSION['user_id'], 
		                       $_POST['title'], 
		                       $_POST['abbr'],
		                       $_POST['long_name'],
		                       $_POST['published_date'],
		                       $_POST['earlid'],
		                       '',
		                       $_POST['status'],
		                       $_POST['open_to_public']);
		
		if (intval($gid) == 0) unset($gid);
	}
		                       
	if (!$msg->containsErrors())
	{
		// add checks
		if (is_array($_POST['add_checks_id'])) $guidelinesDAO->addChecks($gid, $_POST['add_checks_id']);
		
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	}
	
	if (isset($_POST['save_and_close']))
	{
		header('Location: index.php');
		exit;
	}
	else if (isset($gid))
	{
		header('Location: create_edit_guideline.php?id='.$gid);
		exit;
	}
}
else if (isset($_POST['remove']))
{
	foreach ($_POST as $name => $value)
	{
		// the check ids need to be removed are in an array
		if (substr($name, 0, 13) == 'del_checks_id' && is_array($value))
		{
			$value_prefix = substr($name, 14);
			$action_on = explode('_', $value_prefix);
			$action_on_element = $action_on[0];
			$action_on_id = $action_on[1];
			
			if ($action_on_element == 'g')
			{
				foreach ($value as $del_check_id)
					$subgroupChecksDAO->deleteChecksByTypeAndID('guideline', $action_on_id, substr($del_check_id, strlen($value_prefix)+1));
			}
			if ($action_on_element == 'gg')
			{
				foreach ($value as $del_check_id)
					$subgroupChecksDAO->deleteChecksByTypeAndID('group', $action_on_id, substr($del_check_id, strlen($value_prefix)+1));
			}
					if ($action_on_element == 'gsg')
			{
				foreach ($value as $del_check_id)
					$subgroupChecksDAO->deleteChecksByTypeAndID('subgroup', $action_on_id, substr($del_check_id, strlen($value_prefix)+1));
			}
		}
	}
}

// remove groups and subgroups
if ($_GET['action'] == 'remove')
{
	if (isset($_GET['gsg']))
		$guidelineSubgroupsDAO->Delete($_GET['gsg']);
	if (isset($_GET['gg']))
		$guidelineGroupsDAO->Delete($_GET['gg']);
	header('Location: create_edit_guideline.php?id='.$gid);
	exit;
}

// interface display
if (!isset($gid))
{
	// create guideline
	$checksDAO = new ChecksDAO();
	
	$savant->assign('author', $_current_user->getUserName());
	
}
else
{
	// edit existing guideline
	$checksDAO = new ChecksDAO();
	$rows = $guidelinesDAO->getGuidelineByIDs($gid);

	// get author name
	$usersDAO = new UsersDAO();
	$user_name = $usersDAO->getUserName($rows[0]['user_id']);

	if (!$user_name) $user_name = _AC('author_not_exist');
	
	$savant->assign('gid', $gid);
	$savant->assign('row', $rows[0]);
	$savant->assign('author', $user_name);
	$savant->assign('checksDAO', $checksDAO);
}

if (isset($_current_user)) $savant->assign('is_admin', $_current_user->isAdmin());

$savant->display('guideline/create_edit_guideline.tmpl.php');
?>
