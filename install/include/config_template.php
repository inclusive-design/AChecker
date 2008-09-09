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

function write_config_file($filename, $comments) {
	global $config_template;

	$tokens = array('{USER}',
					'{PASSWORD}',
					'{HOST}',
					'{PORT}',
					'{DBNAME}',
					'{TABLE_PREFIX}',
					'{GENERATED_COMMENTS}',
				);

		$values = array(urldecode($_POST['step2']['db_login']),
					addslashes(urldecode($_POST['step2']['db_password'])),
					$_POST['step2']['db_host'],
					$_POST['step2']['db_port'],
					$_POST['step2']['db_name'],
					$_POST['step2']['tb_prefix'],
					$comments,
				);

	$config_template = str_replace($tokens, $values, $config_template);

	if (!$handle = @fopen($filename, 'wb')) {
         return false;
    }
	@ftruncate($handle,0);
    if (!@fwrite($handle, $config_template, strlen($config_template))) {
		return false;
    }
        
    @fclose($handle);
	return true;
}

$config_template = "<"."?php 
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
{GENERATED_COMMENTS}
/************************************************************************/
/************************************************************************/
/* the database user name                                               */
define('DB_USER',                      '{USER}');

/* the database password                                                */
define('DB_PASSWORD',                  '{PASSWORD}');

/* the database host                                                    */
define('DB_HOST',                      '{HOST}');

/* the database tcp/ip port                                             */
define('DB_PORT',                      '{PORT}');

/* the database name                                                    */
define('DB_NAME',                      '{DBNAME}');

/* The prefix to add to table names to avoid conflicts with existing    */
/* tables. Default: AT_                                                 */
define('TABLE_PREFIX',                 '{TABLE_PREFIX}');

/* DO NOT ALTER THIS LAST LINE                                          */
define('AT_INSTALL', TRUE);

?".">";

?>