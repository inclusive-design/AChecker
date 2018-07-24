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

require (AC_INCLUDE_PATH.'vitals.inc.php');
require_once(AC_INCLUDE_PATH.'classes/DAO/MyownPatchesDAO.class.php');

if (isset($_POST['remove'], $_POST['myown_patch_id'])) 
{
	header('Location: patch_delete.php?myown_patch_id='.$_POST['myown_patch_id']);
	exit;
} 
else if (isset($_POST['edit'], $_POST['myown_patch_id'])) 
{
	header('Location: patch_edit.php?myown_patch_id='.$_POST['myown_patch_id']);
	exit;
} 
else if (!empty($_POST) && !isset($_POST['myown_patch_id'])) {
	$msg->addError('NO_ITEM_SELECTED');
}

$myownPatchesDAO = new MyownPatchesDAO();
$patch_rows = $myownPatchesDAO->getAll();

$plate['patch_rows'] = $patch_rows;

echo $plates->render('updater/myown_patches.tmpl.php',$plate);
?>
