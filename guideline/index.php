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
include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');

global $_current_user;

$guidelinesDAO = new GuidelinesDAO();

if ((isset($_POST['delete']) || isset($_POST['view']) || isset($_POST['edit']) || isset($_POST['open_to_public']) || isset($_POST['close_from_public'])) && !isset($_POST['id']))
{
	$msg->addError('NO_ITEM_SELECTED');
} 
else if ($_POST['view'])
{
	header('Location: view_guideline.php?id='.$_POST['id']);
	exit;
}
else if ($_POST['delete'])
{
	header('Location: delete_guideline.php?id='.$_POST['id']);
	exit;
}
else if ($_POST['edit'])
{
	header('Location: create_edit_guideline.php?id='.$_POST['id']);
	exit;
}
else if ($_POST['open_to_public'])
{
	$guidelinesDAO->setOpenToPublicFlag($_POST['id'], 1);
}
else if ($_POST['close_from_public'])
{
	$guidelinesDAO->setOpenToPublicFlag($_POST['id'], 0);
}

include(AC_INCLUDE_PATH.'header.inc.php');

if ($_current_user->isAdmin())
{
	$my_guidelines = $guidelinesDAO->getCustomizedGuidelines();

	$plate['title'] = _AC('customized_guidelines');
}
else
{
	$my_guidelines = $guidelinesDAO->getGuidelineByUserIDs(array($_SESSION['user_id']));

	$plate['title']  = _AC('my_guidelines');
}

// generate section of "my guidelines" 
if (is_array($my_guidelines))
{
	$plate['rows'] = $my_guidelines;
	$plate['buttons'] = array('edit', 'delete');
	$plate['showStatus'] = true;
	$plate['formName'] = 'myGuideline';
	$plate['isAdmin'] = $_current_user->isAdmin();

	echo $plates->render('guideline/index.tmpl.php', $plate);
}

// generate section of "standard guidelines" 
if ($_current_user->isAdmin())
{
	// admin can set standard guidelines open to or close from public
	$plate['buttons'] = array('view', 'edit', 'open_to_public', 'close_from_public');
}
else
{
	$plate['buttons'] = array('view');
}

$plate['title'] = _AC('standard_guidelines');
$plate['rows'] = $guidelinesDAO->getStandardGuidelines();
$plate['showStatus'] = false;
$plate['formName'] = 'standardGuideline';
$plate['isAdmin'] = $_current_user->isAdmin();

echo $plates->render('guideline/index.tmpl.php', $plate);

// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');
?>
