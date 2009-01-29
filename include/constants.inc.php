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

define('VERSION',		'0.2');

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
define('AC_GUIDES_PATH', $_base_path . 'documentation/');

/* relative uri */
$_rel_url = '/'.implode('/', array_slice($url_parts, count($url_parts) - $dir_deep-1));

?>
