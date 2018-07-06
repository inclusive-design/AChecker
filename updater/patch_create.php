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

// URL called by form action
$plate['url'] = dirname($_SERVER['PHP_SELF']) . "/patch_creator.php";

echo $plates->render('updater/patch_create_edit.tmpl.php', $plate);
?>
