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
// $Id:

// Called by ajax request ==============================================================
// @ see checker/js/checker.js
 
define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');

if ($_POST["file"] && $_POST["problem"]) {
	$file = $_POST["file"];
	$problem = $_POST["problem"];
	debug_to_log($problem);
	debug_to_log($file);
			
	// further processing here
	
} else {
	debug_to_log("not");
}
?>