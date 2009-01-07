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

if (!defined('AC_INCLUDE_PATH')) { exit; }
error_reporting(E_ALL ^ E_NOTICE);

if ($step < 3) {
	error_reporting(0);
	include('../include/config.inc.php');
	error_reporting(E_ALL ^ E_NOTICE);
	if (defined('AC_INSTALL')) {
		echo 'AChecker appears to have been installed already.';
		exit;
	}
}

$install_steps[0] = array('name' => 'Introduction');
$install_steps[1] = array('name' => 'Terms of Use');
$install_steps[2] = array('name' => 'Database');
$install_steps[3] = array('name' => 'Save Configuration');
$install_steps[4] = array('name' => 'Anonymous Usage Collection');
$install_steps[5] = array('name' => 'Done!');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="utf-8"> 
<head>
	<title>AChecker Installation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<body>
<div style="height: 70px; vertical-align: bottom; background-color: #354A81">
	<h1 id="header">AChecker <?php echo $new_version; ?> Installation</h1>
</div>
<div class="content">