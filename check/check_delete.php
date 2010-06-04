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
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');

$checksDAO = new ChecksDAO();

if (isset($_POST['submit_no'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 
else if (isset($_POST['submit_yes']))
{
	$checksDAO->Delete($_REQUEST['id']);

	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: index.php');
	exit;
}

require(AC_INCLUDE_PATH.'header.inc.php');

unset($hidden_vars);

$row = $checksDAO->getCheckByID($_REQUEST['id']);

$name_html = '<ul><li>'._AC($row['name']).'</li></ul>';
$hidden_vars['id'] = $_REQUEST['id'];

$msg->addConfirm(array('DELETE_CHECK', $name_html), $hidden_vars);
$msg->printConfirm();

require(AC_INCLUDE_PATH.'footer.inc.php');
?>
