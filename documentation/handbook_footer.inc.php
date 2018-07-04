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

if (isset($prev_page)) $plate['prev_page'] = $prev_page; //$savant->assign('prev_page', $prev_page);
if (isset($next_page)) $plate['next_page'] = $next_page; //$savant->assign('next_page', $next_page);

// $savant->assign('pages', $_pages);
// $savant->assign('base_path', AC_BASE_HREF);

$plate['pages'] = $_pages;
$plate['base_path'] = AC_BASE_HREF;

//$savant->display('include/handbook_footer.tmpl.php');
echo $plates->render('include/handbook_footer.tmpl.php', $plate);
?>
