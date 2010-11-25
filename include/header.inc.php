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

//Timer, to display "Time Spent" in footer, debug information
$mtime = microtime(); 
$mtime = explode(' ', $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 
//Timer Ends

global $myLang;
global $savant;
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

//debug($_base_path.$current_page);
//debug($_sub_menus);
//exit;

//debug($_top_level_pages);
//debug($_all_pages);
//debug($_current_root_page);
//debug($_current_page);

//$savant->assign('path', $_breadcrumb_path);
$savant->assign('top_level_pages', $_top_level_pages);
$savant->assign('current_top_level_page', $_current_root_page);
$savant->assign('sub_menus', $_sub_menus);
$savant->assign('back_to_page', $back_to_page);
$savant->assign('current_page', $_base_path.$current_page);

$savant->assign('page_title', _AC($_all_pages[$current_page]['title_var']));

if (isset($_current_user))
{
  $savant->assign('user_name', $_current_user->getUserName());
}

if ($myLang->isRTL()) {
	$savant->assign('rtl_css', '<link rel="stylesheet" href="'.$_base_path.'themes/'.$_SESSION['prefs']['PREF_THEME'].'/rtl.css" type="text/css" />');
} else {
	$savant->assign('rtl_css', '');
}

$savant->assign('lang_code', $_SESSION['lang']);
$savant->assign('lang_charset', $myLang->getCharacterSet());
$savant->assign('base_path', AC_BASE_HREF);
$savant->assign('theme', $_SESSION['prefs']['PREF_THEME']);

$theme_img  = $_base_path . 'themes/'. $_SESSION['prefs']['PREF_THEME'] . '/images/';
$savant->assign('img', $theme_img);

if (isset($validate_content))
{
	$savant->assign('show_jump_to_report', 1);
}

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
	$savant->assign('guide', AC_GUIDES_PATH .'index.php?p='. htmlentities($script_name));
}

$savant->assign('custom_head', $custom_head);

if ($onload)	$savant->assign('onload', $onload);

$savant->display('include/header.tmpl.php');

?>
