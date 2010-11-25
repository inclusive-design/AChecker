<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
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
 
if (!isset($_REQUEST["myown_patch_id"]))
{
	$msg->addError('NO_ITEM_SELECTED');
	exit;
}

$myown_patch_id = $_REQUEST["myown_patch_id"];

$myownPatchesDAO = new MyownPatchesDAO();
$myownPatchesDependentDAO = new MyownPatchesDependentDAO();
$myownPatchesFilesDAO = new MyownPatchesFilesDAO();

// URL called by form action
$savant->assign('url', dirname($_SERVER['PHP_SELF']) . "/patch_creator.php?myown_patch_id=" . $myown_patch_id);

$savant->assign('patch_row', $myownPatchesDAO->getByID($myown_patch_id));
$savant->assign('dependent_rows', $myownPatchesDependentDAO->getByPatchID($myown_patch_id));
$savant->assign('file_rows', $myownPatchesFilesDAO->getByPatchID($myown_patch_id));

$savant->display('updater/patch_create_edit.tmpl.php');
?>
