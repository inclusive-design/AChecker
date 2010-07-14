<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

if (!defined('AC_INCLUDE_PATH')) { exit; } 
include(AC_INCLUDE_PATH.'handbook_pages.inc.php');

global $handbook_pages;
global $merged_array;

$merged_array = array();

// straighten multi-dimensional array $pages into one-dimension array
function merge_page_array($pages_array)
{
	global $merged_array;
	
	if(is_array($pages_array))
	{
		foreach ($pages_array as $page_key => $page_value) 
		{
			if (is_array($page_value))
			{
				$merged_array[] = $page_key;
				merge_page_array($page_value);
			}
			else 
			{
				$merged_array[] = $page_value;
			}
		}
	}
	
}
merge_page_array($handbook_pages);

// find previous and next page of the current page from merged_array
if (is_array($merged_array))
{
	foreach ($merged_array as $key => $page)
	{
		if (strcmp($page, $this_page) == 0)
		{
			if ($key >= 1) $prev_page = $merged_array[$key - 1];
			if ($key < count($merged_array) - 1) $next_page = $merged_array[$key + 1];
			break;
		}
	}
}
if (isset($prev_page)) $savant->assign('prev_page', $prev_page);
if (isset($next_page)) $savant->assign('next_page', $next_page);

$savant->assign('pages', $_pages);
$savant->assign('this_page', $this_page);
$savant->assign('base_path', AC_BASE_HREF);
$savant->assign('theme', $_SESSION['prefs']['PREF_THEME']);

$savant->display('include/handbook_header.tmpl.php');

?>