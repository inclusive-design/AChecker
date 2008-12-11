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

require(AT_INCLUDE_PATH.'phpCache/phpCache.inc.php'); // cache library

define('AT_DEVEL', 1);
define('AT_ERROR_REPORTING', E_ALL ^ E_NOTICE); // default is E_ALL ^ E_NOTICE, use E_ALL or E_ALL + E_STRICT for developing

// Emulate register_globals off. src: http://php.net/manual/en/faq.misc.php#faq.misc.registerglobals
unset($_SESSION);
function unregister_GLOBALS() {
   if (!ini_get('register_globals')) { return; }

   // Might want to change this perhaps to a nicer error
   if (isset($_REQUEST['GLOBALS'])) { die('GLOBALS overwrite attempt detected'); }

   // Variables that shouldn't be unset
   $noUnset = array('GLOBALS','_GET','_POST','_COOKIE','_REQUEST','_SERVER','_ENV', '_FILES');
   $input = array_merge($_GET,$_POST,$_COOKIE,$_SERVER,$_ENV,$_FILES,isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
  
   foreach ($input as $k => $v) {
       if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) { unset($GLOBALS[$k]); }
   }
}

/*
 * structure of this document (in order):
 *
 * 0. load config.inc.php
 * 1. load constants
 * 2. initilize session
 * 3. initilize db connection
 * 4. start language block
 * 5. load common libraries
 * 6. initialize theme and template management
 ***/

/**** 0. start system configuration options block ****/
	error_reporting(0);
	if (!defined('AT_REDIRECT_LOADED')){
		include_once(AT_INCLUDE_PATH.'config.inc.php');
	}
	error_reporting(AT_ERROR_REPORTING);

	if (!defined('AT_INSTALL') || !AT_INSTALL) {
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Pragma: no-cache');

		$relative_path = substr(AT_INCLUDE_PATH, 0, -strlen('include/'));
		header('Location: ' . $relative_path . 'install/not_installed.php');
		exit;
	}
/*** end system config block ****/

/*** 1. constants ***/
	require_once(AT_INCLUDE_PATH.'constants.inc.php');

/*** 2. initilize session ***/
	@set_time_limit(0);
	@ini_set('session.gc_maxlifetime', '36000'); /* 10 hours */
	@session_cache_limiter('private, must-revalidate');

	session_name('CheckerID');
	error_reporting(AT_ERROR_REPORTING);

	ob_start();
	session_set_cookie_params(0, $_base_path);
	session_start();
	$str = ob_get_contents();
	ob_end_clean();
	unregister_GLOBALS();

/***** end session initilization block ****/

/* 3. database connection */
if (!defined('AT_REDIRECT_LOADED')){
	require_once(AT_INCLUDE_PATH.'lib/mysql_connect.inc.php');
}
/***** end database connection ****/

/***** 4. start language block *****/
	// set current language
	require(AT_INCLUDE_PATH . 'classes/Language/LanguageManager.class.php');
	$languageManager =& new LanguageManager();

	$myLang =& $languageManager->getMyLanguage();

	if ($myLang === FALSE) {
		echo 'There are no languages installed!';
		exit;
	}

	$myLang->saveToSession();

//	if (isset($_GET['lang']) && $_SESSION['valid_user']) {
//		if ($_SESSION['course_id'] == -1) {
//			$myLang->saveToPreferences($_SESSION['login'], 1);	//1 for admin			
//		} else {
//			$myLang->saveToPreferences($_SESSION['member_id'], 0);	//0 for non-admin
//		}
//	}
//	$myLang->sendContentTypeHeader();

	/* set right-to-left language */
	$rtl = '';
	if ($myLang->isRTL()) {
		$rtl = 'rtl_'; /* basically the prefix to a rtl variant directory/filename. eg. rtl_tree */
	}
/***** end language block ****/

/* 5. load common libraries */
	require(AT_INCLUDE_PATH.'lib/output.inc.php');           /* output functions */
/***** end load common libraries ****/

/* 6. initialize theme and template management */
	require(AT_INCLUDE_PATH.'classes/Savant2/Savant2.php');

	// set default template paths:
	$savant =& new Savant2();

	if (isset($_SESSION['prefs']['PREF_THEME']) && file_exists(AT_INCLUDE_PATH . '../themes/' . $_SESSION['prefs']['PREF_THEME']) && isset($_SESSION['valid_user']) && $_SESSION['valid_user']) 
	{
		if (!is_dir(AT_INCLUDE_PATH . '../themes/' . $_SESSION['prefs']['PREF_THEME']))
		{
			$_SESSION['prefs']['PREF_THEME'] = 'default';
		} 
		else 
		{
			//check if enabled
			$sql    = "SELECT status FROM ".TABLE_PREFIX."themes WHERE dir_name = '".$_SESSION['prefs']['PREF_THEME']."'";
			$result = mysql_query($sql, $db);
			$row = mysql_fetch_assoc($result);
			if ($row['status'] == 0) 
			{
				// get default
				$_SESSION['prefs']['PREF_THEME'] = get_default_theme();
			}
		}
	} else 
	{
		$_SESSION['prefs']['PREF_THEME'] = get_default_theme();
	}

	$savant->addPath('template', AT_INCLUDE_PATH . '../themes/' . $_SESSION['prefs']['PREF_THEME'] . '/');

	require(AT_INCLUDE_PATH . '../themes/' . $_SESSION['prefs']['PREF_THEME'] . '/theme.cfg.php');

	require(AT_INCLUDE_PATH.'classes/Message/Message.class.php');
	$msg = new Message($savant);

/* end of initialize theme and template management */

 /**
 * This function is used for printing variables for debugging.
 * @access  public
 * @param   mixed $var	The variable to output
 * @param   string $title	The name of the variable, or some mark-up identifier.
 * @author  Joel Kronenberg
 */
function debug($var, $title='') {
	if (!defined('AT_DEVEL') || !AT_DEVEL) {
		return;
	}
	
	echo '<pre style="border: 1px black solid; padding: 0px; margin: 10px;" title="debugging box">';
	if ($title) {
		echo '<h4>'.$title.'</h4>';
	}
	
	ob_start();
	print_r($var);
	$str = ob_get_contents();
	ob_end_clean();

	$str = str_replace('<', '&lt;', $str);

	$str = str_replace('[', '<span style="color: red; font-weight: bold;">[', $str);
	$str = str_replace(']', ']</span>', $str);
	$str = str_replace('=>', '<span style="color: blue; font-weight: bold;">=></span>', $str);
	$str = str_replace('Array', '<span style="color: purple; font-weight: bold;">Array</span>', $str);
	echo $str;
	echo '</pre>';
}

 /**
 * This function is used for checking if the given $uri is valid.
 * @access  public
 * @param   string $uri  The uri address
 * @author  Cindy Qi Li
 */
function is_uri_valid($uri)
{
	$connection = @file_get_contents($uri);
	
	if (!$connection) 
		return false;
	else
		return true;
}

/****************************************************/
/* compute the $_my_uri variable					*/
	$bits	  = explode(SEP, getenv('QUERY_STRING'));
	$num_bits = count($bits);
	$_my_uri  = '';

	for ($i=0; $i<$num_bits; $i++) {
//		if (	(strpos($bits[$i], 'enable=')	=== 0) 
//			||	(strpos($bits[$i], 'disable=')	=== 0)
//			||	(strpos($bits[$i], 'expand=')	=== 0)
//			||	(strpos($bits[$i], 'collapse=')	=== 0)
//			||	(strpos($bits[$i], 'lang=')		=== 0)
//			) {
		if (	(strpos($bits[$i], 'lang=')		=== 0)
			) {
			/* we don't want this variable added to $_my_uri */
			continue;
		}

		if (($_my_uri == '') && ($bits[$i] != '')) {
			$_my_uri .= '?';
		} else if ($bits[$i] != ''){
			$_my_uri .= SEP;
		}
		$_my_uri .= $bits[$i];
	}
	if ($_my_uri == '') {
		$_my_uri .= '?';
	} else {
		$_my_uri .= SEP;
	}
	$_my_uri = $_SERVER['PHP_SELF'].$_my_uri;

function my_add_null_slashes( $string ) {
    return mysql_real_escape_string(stripslashes($string));
}

function my_null_slashes($string) {
	return $string;
}

if ( get_magic_quotes_gpc() == 1 ) {
	$addslashes   = 'my_add_null_slashes';
	$stripslashes = 'stripslashes';
} else {
	$addslashes   = 'mysql_real_escape_string';
	$stripslashes = 'my_null_slashes';
}

function get_default_theme() {
	global $db;

	$sql	= "SELECT dir_name FROM ".TABLE_PREFIX."themes WHERE status=2";
	$result = mysql_query($sql, $db);
	$row = mysql_fetch_assoc($result);

	if (!is_dir(AT_INCLUDE_PATH . '../themes/' . $row['dir_name']))
		return 'default';
	else
		return $row['dir_name'];
}

?>
