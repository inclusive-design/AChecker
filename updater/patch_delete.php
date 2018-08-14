<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

define('AC_INCLUDE_PATH', '../include/');
require(AC_INCLUDE_PATH.'vitals.inc.php');
require_once(AC_INCLUDE_PATH.'classes/DAO/MyownPatchesDAO.class.php');
require_once(AC_INCLUDE_PATH.'classes/DAO/MyownPatchesDependentDAO.class.php');
require_once(AC_INCLUDE_PATH.'classes/DAO/MyownPatchesFilesDAO.class.php');

$myownPatchesDAO = new MyownPatchesDAO();

if (isset($_POST['submit_no'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: myown_patches.php');
	exit;
} else if (isset($_POST['submit_yes'])) {
	/* delete has been confirmed, delete this category */
	$myown_patch_id	= intval($_POST['myown_patch_id']);

	$myownPatchesDependentDAO = new MyownPatchesDependentDAO();
	$myownPatchesFilesDAO = new MyownPatchesFilesDAO();
	
	$myownPatchesDAO->Delete($myown_patch_id);
	$myownPatchesDependentDAO->DeleteByPatchID($myown_patch_id);
	$myownPatchesFilesDAO->DeleteByPatchID($myown_patch_id);

	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: myown_patches.php');
	exit;
}

//require('../../include/header.inc.php');
require(AC_INCLUDE_PATH.'header.inc.php');

$_GET['myown_patch_id'] = intval($_GET['myown_patch_id']); 
$row = $myownPatchesDAO->getByID($_GET[myown_patch_id]);

if (!$row) {
	$msg->printErrors('ITEM_NOT_FOUND');
} else {
	$hidden_vars['achecker_patch_id']= $row['achecker_patch_id'];
	$hidden_vars['myown_patch_id']	= $row['myown_patch_id'];

	$confirm = array('DELETE_MYOWN_UPDATE', $row['achecker_patch_id']);
	$msg->addConfirm($confirm, $hidden_vars);
	
	$msg->printConfirm();
}

require(AC_INCLUDE_PATH.'footer.inc.php');

?>