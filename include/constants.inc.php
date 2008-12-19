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

/**
 * constants, some more constants are loaded from table 'config' @ include/vitals.inc.php
 **/

define('VERSION',		'0.1');

$_config['default_language']          = 'en';

// check confidence
define('KNOWN', 0);
define('LIKELY', 1);
define('POTENTIAL', 2);

/* User groups */
define('ADMIN_GROUP_ID', 1);
define('USER_GROUP_ID', 2);
define('GUIDELINE_CHECK_CREATOR_GROUP_ID', 3);

/* how long cache objects can persist	*/
/* in seconds. should be low initially, but doesn't really matter. */
/* in practice should be 0 (ie. INF)    */
define('CACHE_TIME_OUT',	60);

// separator used in composing URL
if (strpos(@ini_get('arg_separator.input'), ';') !== false) {
	define('SEP', ';');
} else {
	define('SEP', '&');
}

/* get the base url	*/
if (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on')) {
	$server_protocol = 'https://';
} else {
	$server_protocol = 'http://';
}

$dir_deep	 = substr_count(AT_INCLUDE_PATH, '..');
$url_parts	 = explode('/', $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$_base_href	 = array_slice($url_parts, 0, count($url_parts) - $dir_deep-1);
$_base_href	 = $server_protocol . implode('/', $_base_href).'/';

$endpos = strlen($_base_href); 

$_base_href	 = substr($_base_href, 0, $endpos);
$_base_path  = substr($_base_href, strlen($server_protocol . $_SERVER['HTTP_HOST']));

define('AT_BASE_HREF', $_base_href);

/* relative uri */
$_rel_url = '/'.implode('/', array_slice($url_parts, count($url_parts) - $dir_deep-1));

/* $_pages sections */
define('AC_NAV_PUBLIC', 'AC_NAV_PUBLIC');  // public menus, when no user login
define('AC_NAV_TOP', 'AC_NAV_TOP');        // top tab menus

/**
 * define all the pages that used in each priviledge section. 
 * section id (1-5) must be same as according privileges.privilege_id 
 * Note that 1st item in each section pages array is not set. It's dynamically set in script.
 **/
define('PRIV_WEB_ACCESSIBILITY_CHECKER', 1);
define('PRIV_USER_MANAGE', 2);
define('PRIV_GUIDELINE_MANAGE', 3);
define('PRIV_CHECK_MANAGE', 4);
define('PRIV_LANGUAGE_MANAGE', 5);

$_section_pages[PRIV_WEB_ACCESSIBILITY_CHECKER] = array('index.php' => array('title_var'=>'web_accessibility_checker')
                          );

$_section_pages[PRIV_USER_MANAGE] = array('user/index.php' => array('title_var'=>'user_manage')
                          );

$_section_pages[PRIV_GUIDELINE_MANAGE] = array('guideline/index.php' => array('title_var'=>'guideline_manage')
                          );

$_section_pages[PRIV_CHECK_MANAGE] = array('check/index.php' => array('title_var'=>'check_manage')
                          );

$_section_pages[PRIV_LANGUAGE_MANAGE] = array('language/index.php' => array('title_var'=>'language_manage')
                          );

$_pages_constant[AC_NAV_PUBLIC] = $_section_pages[1];

?>
