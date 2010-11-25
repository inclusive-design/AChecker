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

if (!defined('AC_INCLUDE_PATH')) { exit; } 

if (isset($prev_page)) $savant->assign('prev_page', $prev_page);
if (isset($next_page)) $savant->assign('next_page', $next_page);

$savant->assign('pages', $_pages);
$savant->assign('base_path', AC_BASE_HREF);

$savant->display('include/handbook_footer.tmpl.php');
?>
