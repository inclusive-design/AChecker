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
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');

if (!isset($_REQUEST['gid']) && !isset($_REQUEST['ggid']) && !isset($_REQUEST['gsgid']) || !isset($_REQUEST['action']))
{
	include(AC_INCLUDE_PATH.'header.inc.php');
	$msg->addError('MISSING_GID');
	include(AC_INCLUDE_PATH.'footer.inc.php');
	exit;
}
	
$guidelineGroupsDAO = new GuidelineGroupsDAO();
$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();

if (isset($_POST['submit']))
{
	$name = trim($_POST['name']);	
	
	if ($name == '')
	{
		$msg->addError(array('EMPTY_FIELDS', _AC('name')));
	}
	
	if (!$msg->containsErrors())
	{
		if ($_GET['action'] == 'add')
		{
			if (isset($_GET['gid'])) // add group into guideline
				$guidelineGroupsDAO->Create($_GET['gid'], $name, '', '');
	
			if (isset($_GET['ggid'])) // add group into guideline
				$guidelineSubgroupsDAO->Create($_GET['ggid'], $name, '');
		}

		if ($_GET['action'] == 'edit')
		{
			if (isset($_GET['ggid'])) // add group into guideline
				$guidelineGroupsDAO->Update($_GET['ggid'], $name, '', '');
	
			if (isset($_GET['gsgid'])) // add group into guideline
				$guidelineSubgroupsDAO->Update($_GET['gsgid'], $name, '');
		}

		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		
		// force refresh parent window
		$javascript_run_now = '<script language="JavaScript">
<!--
window.opener.location.href = window.opener.location.href;
self.close();
//-->
</script>';
	}

}

if ($_GET['action'] == 'edit')
{
	if (isset($_GET['ggid']))
		$row = $guidelineGroupsDAO->getGroupByID($_GET['ggid']);

	if (isset($_GET['gsgid']))
		$row = $guidelineSubgroupsDAO->getSubgroupByID($_GET['gsgid']);
	
	// $savant->assign('row', $row);

	$plate['row'] = $row;
}

if (isset($javascript_run_now)) $plate['javascript_run_now'] = $javascript_run_now; //$savant->assign('javascript_run_now', $javascript_run_now);

//$savant->display('guideline/add_edit_group.tmpl.php');

echo $plates->render('guideline/add_edit_group.tmpl.php', $plate);

?>