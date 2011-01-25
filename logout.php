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

define('AC_INCLUDE_PATH', 'include/');
require(AC_INCLUDE_PATH.'vitals.inc.php');

// unset all session variables
session_unset();
$_SESSION = array();

$msg->addFeedback('LOGOUT');
header('Location: index.php');
exit;
?>