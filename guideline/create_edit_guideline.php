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
		$guidelinesDAO = new GuidelinesDAO();
		
		$gid = $guidelinesDAO->Create($_SESSION['user_id'], 
		                       $title, 
		                       $addslashes(trim($_POST['abbr'])),
		                       $addslashes(trim($_POST['long_name'])),
		                       $addslashes(trim($_POST['published_date'])),
		                       $addslashes(trim($_POST['earlid'])),
		                       '',
		                       $_POST['status'],
		                       0);

		if (!$msg->containsErrors())
		{
			// add checks
			$guidelinesDAO->addChecks($gid, $_POST['add_checks_id']);
			
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		}
	}
	header('Location: index.php');
	exit;
}

// interface display
if (!isset($_GET["id"]))
{
	// create guideline
	$checksDAO = new ChecksDAO();
	
	$savant->assign('checks_to_add_rows', $checksDAO->getAllOpenChecks());
	$savant->display('guideline/create_edit_guideline.tmpl.php');
}
else
{
	// edit existing guideline
	$gid = intval($_GET["id"]);
	
	$guidelinesDAO = new GuidelinesDAO();
	$row = $guidelinesDAO->getGuidelineByIDs($gid);
	$checksDAO = new ChecksDAO();
	
	$savant->assign('row', $row);
	$savant->assign('checks_rows', $checksDAO->getChecksByGuidelineID($gid));
	$savant->display('guideline/create_edit_guideline.tmpl.php');
}
?>
