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
include_once(AC_INCLUDE_PATH.'classes/DAO/UsersDAO.class.php');

$usersDAO = new UsersDAO();

$ids = explode(',', $_REQUEST['id']);

if (isset($_POST['submit_no'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 
else if (isset($_POST['submit_yes']))
{
	$usersDAO->Delete($ids);

	$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
	header('Location: index.php');
	exit;
}

require(AC_INCLUDE_PATH.'header.inc.php');

unset($hidden_vars);

foreach($ids as $id) 
{
	$names[] = $usersDAO->getUserName($id);
}

$names_html = '<ul>'.html_get_list($names).'</ul>';
$hidden_vars['id'] = $_REQUEST['id'];

$msg->addConfirm(array('DELETE_USER', $names_html), $hidden_vars);
$msg->printConfirm();

require(AC_INCLUDE_PATH.'footer.inc.php');
?>
