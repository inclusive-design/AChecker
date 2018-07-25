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
include(AC_INCLUDE_PATH.'vitals.inc.php');

if (isset($_GET['p'])) {
	$this_page = htmlentities($_GET['p'], ENT_QUOTES, 'UTF-8');
} else {
	$this_page = 'index.php';
}

require('handbook_header.inc.php');

if (isset($_pages[$this_page]['guide']))
{
	echo _AC($_pages[$this_page]['guide']);
}

require('handbook_footer.inc.php');
?>
