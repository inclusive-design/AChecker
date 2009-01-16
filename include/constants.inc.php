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

if (!defined('AC_INCLUDE_PATH')) { exit; }

/**
 * constants, some more constants are loaded from table 'config' @ include/vitals.inc.php
 **/

define('VERSION',		'0.1');

$_config['default_language']          = 'en';

// check confidence
define('KNOWN', 0);
define('LIKELY', 1);
define('POTENTIAL', 2);

/* User group type */
define('AC_USER_GROUP_ADMIN', 1);
define('AC_USER_GROUP_USER', 2);

/* User status */
define('AC_STATUS_DISABLED', 0);
define('AC_STATUS_ENABLED', 1);
define('AC_STATUS_DEFAULT', 2);
define('AC_STATUS_UNCONFIRMED', 3);

/* how many days until the password reminder link expires */
define('AC_PASSWORD_REMINDER_EXPIRY', 2);

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

$dir_deep	 = substr_count(AC_INCLUDE_PATH, '..');
$url_parts	 = explode('/', $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$_base_href	 = array_slice($url_parts, 0, count($url_parts) - $dir_deep-1);
$_base_href	 = $server_protocol . implode('/', $_base_href).'/';

$endpos = strlen($_base_href); 

$_base_href	 = substr($_base_href, 0, $endpos);
$_base_path  = substr($_base_href, strlen($server_protocol . $_SERVER['HTTP_HOST']));

define('AC_BASE_HREF', $_base_href);

/* relative uri */
$_rel_url = '/'.implode('/', array_slice($url_parts, count($url_parts) - $dir_deep-1));

/* constants used for menu item generation. Used in class Menu (include/classes/Menu.class.php) */
define('AC_NAV_PUBLIC', 'AC_NAV_PUBLIC');  // public menus, when no user login
define('AC_NAV_TOP', 'AC_NAV_TOP');        // top tab menus

/* initialize pages accessed by public */
$_pages[AC_NAV_PUBLIC] = array('index.php' => array('parent'=>AC_NAV_PUBLIC));

/* define all accessible pages */
$_pages['translator.php']['title_var'] = 'translator';
$_pages['translator.php']['parent']    = AC_NAV_PUBLIC;

$_pages['register.php']['title_var'] = 'registration';
$_pages['register.php']['parent']    = AC_NAV_PUBLIC;

$_pages['confirm.php']['title_var'] = 'confirm';
$_pages['confirm.php']['parent']    = AC_NAV_PUBLIC;

$_pages['login.php']['title_var'] = 'login';
$_pages['login.php']['parent']    = AC_NAV_PUBLIC;
$_pages['login.php']['children']  = array_merge(array('password_reminder.php'), isset($_pages['login.php']['children']) ? $_pages['login.php']['children'] : array());

$_pages['logout.php']['title_var'] = 'logout';
$_pages['logout.php']['parent']    = AC_NAV_PUBLIC;

$_pages['password_reminder.php']['title_var'] = 'password_reminder';
$_pages['password_reminder.php']['parent']    = 'login.php';

$_pages['checker/suggestion.php']['title_var'] = 'Suggestion';
$_pages['checker/suggestion.php']['parent']    = AC_NAV_PUBLIC;

$_pages['profile/index.php']['title_var'] = 'Profile';
$_pages['profile/index.php']['parent']    = AC_NAV_TOP;
$_pages['profile/index.php']['children']  = array_merge(array('profile/change_password.php', 
                                                              'profile/change_email.php'), 
                                                        isset($_pages['profile/index.php']['children']) ? $_pages['profile/index.php']['children'] : array());

$_pages['profile/change_password.php']['title_var'] = 'change_password';
$_pages['profile/change_password.php']['parent']    = 'profile/index.php';

$_pages['profile/change_email.php']['title_var'] = 'change_email';
$_pages['profile/change_email.php']['parent']    = 'profile/index.php';

?>
