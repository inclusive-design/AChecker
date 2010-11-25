<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

define('AC_INCLUDE_PATH', 'include/');
require(AC_INCLUDE_PATH.'common.inc.php');

if (!$new_version = $_POST['new_version']) {
	$new_version = $_POST['step2']['new_version'];
}

$step = intval($_POST['step']);

if ($step == 0) {
	$step = 1;
}

if ($_POST['submit'] == 'I Disagree'){
	Header ("Location: index.php");
}

require(AC_INCLUDE_PATH.'header.inc.php');

/* agree to terms of use */
if ($step == 1) {
	require(AC_INCLUDE_PATH.'step1.php');
}

/* db */
if ($step == 2) {
	require(AC_INCLUDE_PATH.'step2.php');
}

/* create admin accounts and sytem preference */
if ($step == 3) {	
	require(AC_INCLUDE_PATH.'step3.php');
}

/* accounts & preferences */
if ($step == 4) {	
	require(AC_INCLUDE_PATH.'step4.php');
}

/* directory permissions and generating the config.inc.php file */
if ($step == 5) {	
	require(AC_INCLUDE_PATH.'step5.php');
}

/* anonymous data collection */
if ($step == 6) {	
	require(AC_INCLUDE_PATH.'step6.php');
}

/* done! */
if ($step == 7) {	
	require(AC_INCLUDE_PATH.'step7.php');
}

require(AC_INCLUDE_PATH.'footer.inc.php');
?>