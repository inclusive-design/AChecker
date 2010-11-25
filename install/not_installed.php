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
error_reporting(E_ALL ^ E_NOTICE);

require('../include/constants.inc.php');

$new_version = VERSION;

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

require(AC_INCLUDE_PATH.'header.inc.php');
?>


<p>AChecker does not appear to be installed. <a href="index.php">Continue on to the installation</a>.</p>


<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>