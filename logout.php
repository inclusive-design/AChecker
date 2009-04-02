<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('AC_INCLUDE_PATH', 'include/');
require(AC_INCLUDE_PATH.'vitals.inc.php');

// unset all session variables
session_unset();
session_destroy();
$_SESSION = array();

$msg->addFeedback('LOGOUT');
header('Location: index.php');
?>