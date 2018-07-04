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

//global $savant;
global $plates;
if (!defined('AC_INCLUDE_PATH')) { exit; }

//$savant->display('include/footer.tmpl.php');
echo $plates->render('include/footer.tmpl.php');
?>
