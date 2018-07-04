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

include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');

$gid = intval($_GET["id"]);

$guidelinesDAO = new GuidelinesDAO();
$rows = $guidelinesDAO->getGuidelineByIDs($gid);

if (!$rows)
{
	global $msg;
	
	$msg->addError('GUIDELINE_NOT_FOUND');
	header('Location: index.php');	
}
else
{
	// $savant->assign('row', $rows[0]);
	// $savant->assign('gid', $gid);

	$plate['row'] = $rows[0];
	$plate['gid'] = $gid;

	// $savant->display('guideline/view_guideline.tmpl.php');
	echo $plates->render('guideline/view_guideline.tmpl.php', $plate);
}
?>
