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
include_once(AC_INCLUDE_PATH.'classes/DAO/guidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');

global $_current_user;

if (isset($_GET["id"])) $gid = intval($_GET["id"]);

$guidelinesDAO = new GuidelinesDAO();

// handle submits
if (isset($_POST['save']))
{
	$title = $addslashes(trim($_POST['title']));	
	
	if ($title == '')
	{
		$msg->addError(array('EMPTY_FIELDS', _AC('title')));
	}
	
	if (!$msg->containsErrors())
	{
		if (isset($gid))  // edit existing guideline
		{
			$guidelinesDAO->update($gid,
			                       $_SESSION['user_id'], 
			                       $title, 
			                       $addslashes(trim($_POST['abbr'])),
			                       $addslashes(trim($_POST['long_name'])),
			                       $addslashes(trim($_POST['published_date'])),
			                       $addslashes(trim($_POST['earlid'])),
			                       '',
			                       $_POST['status'],
			                       $_POST['open_to_public']);
		}
		else  // create a new guideline
		{
			$gid = $guidelinesDAO->Create($_SESSION['user_id'], 
			                       $title, 
			                       $addslashes(trim($_POST['abbr'])),
			                       $addslashes(trim($_POST['long_name'])),
			                       $addslashes(trim($_POST['published_date'])),
			                       $addslashes(trim($_POST['earlid'])),
			                       '',
			                       $_POST['status'],
			                       $_POST['open_to_public']);
		}
			                       
		if (!$msg->containsErrors())
		{
			// add checks
			if (is_array($_POST['add_checks_id'])) $guidelinesDAO->addChecks($gid, $_POST['add_checks_id']);
			
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		}
	}
	header('Location: index.php');
	exit;
}
else if (isset($_POST['remove']))
{
	if (is_array($_POST['del_checks_id']))
	{
		foreach ($_POST['del_checks_id'] as $del_check_id)
			$guidelinesDAO->deleteCheckByID($gid, $del_check_id);
	}
}

// interface display
if (!isset($gid))
{
	// create guideline
	$checksDAO = new ChecksDAO();
	
	$savant->assign('checks_to_add_rows', $checksDAO->getAllOpenChecks());
}
else
{
	// edit existing guideline
	$rows = $guidelinesDAO->getGuidelineByIDs($gid);
	$checksDAO = new ChecksDAO();
	$checks_rows = $checksDAO->getChecksByGuidelineID($gid);

	// get checks that are open to public and not in guideline
	unset($str_existing_checks);
	if (is_array($checks_rows))
	{
		foreach($checks_rows as $check_row)
			$str_existing_checks .= $check_row['check_id'] .',';
		$str_existing_checks = substr($str_existing_checks, 0, -1);
	}
	
	$savant->assign('row', $rows[0]);
	$savant->assign('checks_rows', $checks_rows);
	$savant->assign('checks_to_add_rows', $checksDAO->getAllOpenChecksExceptListed($str_existing_checks));
}

if (isset($_current_user)) $savant->assign('is_admin', $_current_user->isAdmin());

$savant->display('guideline/create_edit_guideline.tmpl.php');
?>
