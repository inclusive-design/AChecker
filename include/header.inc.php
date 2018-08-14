<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

if (!defined('AC_INCLUDE_PATH')) { exit; }

global $myLang;
global $plates;
global $onload;
global $_custom_css;
global $_custom_head;
global $_base_path;
global $_pages;
global $_current_user;
global $validate_content;

include_once(AC_INCLUDE_PATH.'classes/Menu.class.php');

$menu =new Menu();
$_top_level_pages = $menu->getTopPages();

$_all_pages =  $menu->getAllPages();

$_current_root_page = $menu->getRootPage();

//$_breadcrumb_path = $menu->getPath();

$current_page = $menu->getCurrentPage();

$_sub_menus = $menu->getSubMenus();
$back_to_page = $menu->getBackToPage();

$plate['top_level_pages'] = $_top_level_pages;
$plate['current_top_level_page'] = $_current_root_page;
$plate['sub_menus'] = $_sub_menus;
$plate['back_to_page'] = $back_to_page;
$plate['current_page'] = $_base_path.$current_page;

$plate['page_title'] = _AC($_all_pages[$current_page]['title_var']);

if (isset($_current_user))
{
  $plate['user_name'] = $_current_user->getUserName();
}

if ($myLang->isRTL()) {
	
	$plate['rtl_css'] = '<link rel="stylesheet" href="'.$_base_path.'themes/'.$_SESSION['prefs']['PREF_THEME'].'/rtl.css" type="text/css" />';
} else {
	$plate['rtl_css'] = '';
}

$plate['lang_code'] = $_SESSION['lang'];
$plate['lang_charset'] = $myLang->getCharacterSet();
$plate['base_path'] = AC_BASE_HREF;
$plate['theme'] = $_SESSION['prefs']['PREF_THEME'];

$theme_img  = $_base_path . 'themes/'. $_SESSION['prefs']['PREF_THEME'] . '/images/';

$plate['img'] = $theme_img;

if (isset($validate_content))
{
	$plate['show_jump_to_report'] = 1;
}

$custom_head = "";

if (isset($_custom_css)) {
	$custom_head = '<link rel="stylesheet" href="'.$_custom_css.'" type="text/css" />';
}

if (isset($_custom_head)) {
	$custom_head .= '
' . $_custom_head;
}

if (isset($_pages[$current_page]['guide'])) 
{
	$script_name = substr($_SERVER['PHP_SELF'], strlen($_base_path));
	$plate['guide'] = AC_GUIDES_PATH .'index.php?p='. htmlentities($script_name);
}

$plate['custom_head'] = $custom_head;

if ($onload)	$plate['onload'] = $onload;

echo $plates->render('include/header.tmpl.php', $plate);
?>
