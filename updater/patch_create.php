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
require (AC_INCLUDE_PATH.'vitals.inc.php');

// URL called by form action
$savant->assign('url', dirname($_SERVER['PHP_SELF']) . "/patch_creator.php");

$savant->display('updater/patch_create_edit.tmpl.php');
?>
