<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

if (!defined('AT_INCLUDE_PATH')) { exit; }

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

//require(AT_INCLUDE_PATH . 'lib/menu_pages.php');
//
//$current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));
//
//if (!isset($_pages[$current_page])) 
//{
//	// re-direct to first $_pages URL 
//	$cnt = 0;
//	
//	foreach ($_pages[AC_NAV_TOP] as $url)
//	{
//		$cnt++;
//		if ($current_page != $url)
//		{
//			header('location: '.AT_BASE_HREF. $url);
//			// reset current_page after re-direction
//			$current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));
//		}
//		
//		if ($cnt == 1) break;
//	}
//}
//
//$_top_level_pages        = get_main_navigation($current_page);
//
//$_current_top_level_page = get_current_main_page($current_page);
//
//$_sub_level_pages        = get_sub_navigation($current_page);
//
//$_current_sub_level_page = get_current_sub_navigation_page($current_page);
//
//$_path = get_path($current_page);
//debug($_top_level_pages);
include(AT_INCLUDE_PATH.'classes/Menu.class.php');

$menu =new Menu();
$_top_level_pages = $menu->get_top_pages();

$_all_pages =  $menu->get_all_pages();

$_current_root_page = $menu->get_root_page();

$_breadcrumb_path = $menu->get_breadcrumb_path();

$current_page = $menu->get_current_page();
//debug($_top_level_pages);
//exit;

$savant->assign('top_level_pages', $_top_level_pages);
$savant->assign('current_top_level_page', $_current_root_page);
$savant->assign('path', $_breadcrumb_path);

$savant->assign('page_title', _AC($_all_pages[$current_page]['title_var']));

//if (empty($_top_level_pages)) {
//	if (!$_SESSION['member_id'] && !$_SESSION['course_id']) {
//		$_top_level_pages = get_main_navigation($_pages[AT_NAV_PUBLIC][0]);
//	} else if ($_SESSION['course_id'] < 0) {
//		$_top_level_pages = get_main_navigation($_pages[AT_NAV_ADMIN][0]);
//	} else if (!$_SESSION['course_id']) {
//		$_top_level_pages = get_main_navigation($_pages[AT_NAV_START][0]);
//	} else {
//		$_top_level_pages = get_main_navigation($_pages[AT_NAV_COURSE][0]);
//	}
//}

$savant->assign('lang_code', $_SESSION['lang']);
$savant->assign('lang_charset', $myLang->getCharacterSet());
$savant->assign('base_path', AT_BASE_HREF);
$savant->assign('theme', $_SESSION['prefs']['PREF_THEME']);

$theme_img  = $_base_path . 'themes/'. $_SESSION['prefs']['PREF_THEME'] . '/images/';
$savant->assign('img', $theme_img);

if (isset($_custom_css)) {
	$custom_head = '<link rel="stylesheet" href="'.$_custom_css.'" type="text/css" />';
}

if (isset($_custom_head)) {
	$custom_head .= '
' . $_custom_head;
}

$savant->assign('custom_head', $custom_head);

if ($onload)	$savant->assign('onload', $onload);

$savant->display('include/header.tmpl.php');

?>
