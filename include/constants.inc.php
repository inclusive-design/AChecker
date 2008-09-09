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
 * constants
 **/
 
define('VERSION',		'0.1');

/* how long cache objects can persist	*/
/* in seconds. should be low initially, but doesn't really matter. */
/* in practice should be 0 (ie. INF)    */
define('CACHE_TIME_OUT',	60);

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

?>
