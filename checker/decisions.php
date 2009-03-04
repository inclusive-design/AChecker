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

define("AC_INCLUDE_PATH", '../include/');

global $msg;

if (!isset($_REQUEST['jsessionid']) || !isset($_REQUEST['URI']) || !isset($_REQUEST['output']))
{
	$msg->addError('MUST_PROVIDE_REQUEST_VARS');
}
?>
