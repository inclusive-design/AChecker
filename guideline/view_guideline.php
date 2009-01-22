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

$gid = intval($_GET["id"]);

$guidelinesDAO = new GuidelinesDAO();
$row = $guidelinesDAO->getGuidelineByIDs($gid);
//debug($row);
if (!$row)
{
	global $msg;
	
	$msg->addError('GUIDELINE_NOT_FOUND');
	header('Location: index.php');	
}
else
{
	$checksDAO = new ChecksDAO();
	$savant->assign('row', $row);
	$savant->assign('checks_rows', $checksDAO->getChecksByGuidelineID($gid));
	$savant->display('guideline/view_guideline.tmpl.php');
}
?>
