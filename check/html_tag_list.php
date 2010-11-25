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

define('AC_INCLUDE_PATH', '../include/');
include_once(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');

$checksDAO = new ChecksDAO();

$all_html_tags = $checksDAO->getAllHtmlTags();

$savant->assign('all_html_tags', $checksDAO->getAllHtmlTags());

$savant->display('check/html_tag_list.tmpl.php');

?>