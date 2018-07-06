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

if (!defined('AC_INCLUDE_PATH')) { exit; } 

if (isset($prev_page)) $plate['prev_page'] = $prev_page;
if (isset($next_page)) $plate['next_page'] = $next_page;


$plate['pages'] = $_pages;
$plate['base_path'] = AC_BASE_HREF;

echo $plates->render('include/handbook_footer.tmpl.php', $plate);
?>
