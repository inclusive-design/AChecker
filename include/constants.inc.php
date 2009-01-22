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

/**
 * constants, some more constants are loaded from table 'config' @ include/vitals.inc.php
 **/

define('VERSION',		'0.1');

$_config['default_language']          = 'en';

// check confidence
define('KNOWN', 0);
define('LIKELY', 1);
define('POTENTIAL', 2);

function get_confidence_by_code($confidence_code)
{
	if ($confidence_code == KNOWN)
		 return _AC('known');
	else if ($confidence_code == LIKELY)
		 return _AC('likely');
	else if ($confidence_code == POTENTIAL)
		 return _AC('potential');
	else
		return '';
}

/* User group type */
define('AC_USER_GROUP_ADMIN', 1);
define('AC_USER_GROUP_USER', 2);

/* User status */
define('AC_STATUS_DISABLED', 0);
define('AC_STATUS_ENABLED', 1);
define('AC_STATUS_DEFAULT', 2);
define('AC_STATUS_UNCONFIRMED', 3);

function get_status_by_code($status_code)
{
	if ($status_code == AC_STATUS_DISABLED)
		 return _AC('disabled');
	else if ($status_code == AC_STATUS_ENABLED)
		 return _AC('enabled');
	else if ($status_code == AC_STATUS_DEFAULT)
		 return _AC('default');
	else if ($status_code == AC_STATUS_UNCONFIRMED)
		 return _AC('unconfirmed');
	else
		return '';
}

/* language text prefix. Note that all prefixes must be unique */
// table "checks"
define('LANG_PREFIX_CHECKS_NOTE', '_NOTE_');
define('LANG_PREFIX_CHECKS_NAME', '_CNAME_');
define('LANG_PREFIX_CHECKS_ERR', '_ERR_');
define('LANG_PREFIX_CHECKS_DESC', '_DESC_');
define('LANG_PREFIX_CHECKS_RATIONALE', '_RATIONALE_');
define('LANG_PREFIX_CHECKS_HOW_TO_REPAIR', '_HOWTOREPAIR_');
define('LANG_PREFIX_CHECKS_REPAIR_EXAMPLE', '_REPAIREXAMPLE_');
define('LANG_PREFIX_CHECKS_QUESTION', '_QUESTION_');
define('LANG_PREFIX_CHECKS_DECISION_PASS', '_DECISIONPASS_');
define('LANG_PREFIX_CHECKS_DECISION_FAIL', '_DECISIONFAIL_');

// table "guidelines"
define('LANG_PREFIX_GUIDELINES_LONG_NAME', '_GNAME_');

// table "guideline_groups"
define('LANG_PREFIX_GUIDELINE_GROUPS_NAME', '_GROUPNAME_');

// table "guideline_subgroups"
define('LANG_PREFIX_GUIDELINE_SUBGROUPS_NAME', '_SUBGROUPNAME_');

// table "test_expected"
define('LANG_PREFIX_TEST_EXPECTED_STEP', '_EXPECTEDSTEP_');

// table "test_fail"
define('LANG_PREFIX_TEST_FAIL_STEP', '_FAILSTEP_');

// table "test_files"
define('LANG_PREFIX_TEST_FILES_DESC', '_FILEDESC_');

// table "test_procedure"
define('LANG_PREFIX_TEST_PROCEDURE_STEP', '_PROCEDURESTEP_');

/* end of language text prefix */

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
// 1. public pages
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

// 2. profile pages
$_pages['profile/index.php']['title_var'] = 'Profile';
$_pages['profile/index.php']['parent']    = AC_NAV_TOP;
$_pages['profile/index.php']['children']  = array_merge(array('profile/change_password.php', 
                                                              'profile/change_email.php'), 
                                                        isset($_pages['profile/index.php']['children']) ? $_pages['profile/index.php']['children'] : array());

$_pages['profile/change_password.php']['title_var'] = 'change_password';
$_pages['profile/change_password.php']['parent']    = 'profile/index.php';

$_pages['profile/change_email.php']['title_var'] = 'change_email';
$_pages['profile/change_email.php']['parent']    = 'profile/index.php';

// 3. guideline pages
$_pages['guideline/index.php']['title_var'] = 'guideline_manage';
$_pages['guideline/index.php']['parent']    = AC_NAV_TOP;
$_pages['guideline/index.php']['children']  = array_merge(array('guideline/create_edit_guideline.php'), 
                                                        isset($_pages['guideline/index.php']['children']) ? $_pages['guideline/index.php']['children'] : array());

$_pages['guideline/create_edit_guideline.php']['title_var'] = 'create_guideline';
$_pages['guideline/create_edit_guideline.php']['parent']    = 'guideline/index.php';

$_pages['guideline/view_guideline.php']['title_var'] = 'view_guideline';
$_pages['guideline/view_guideline.php']['parent']    = 'guideline/index.php';

// 4. user pages
?>
