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

define('AT_INCLUDE_PATH', 'include/');
require(AT_INCLUDE_PATH.'common.inc.php');

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

require(AT_INCLUDE_PATH.'header.php');

/* agree to terms of use */
if ($step == 1) {
	require(AT_INCLUDE_PATH.'step1.php');
}

/* db */
if ($step == 2) {
	require(AT_INCLUDE_PATH.'step2.php');
}

/* directory permissions and generating the config.inc.php file */
if ($step == 3) {	
	require(AT_INCLUDE_PATH.'step3.php');
}

/* anonymous data collection */
if ($step == 4) {	
	require(AT_INCLUDE_PATH.'step4.php');
}

/* done! */
if ($step == 5) {	
	require(AT_INCLUDE_PATH.'step5.php');
}

require(AT_INCLUDE_PATH.'footer.php');
?>