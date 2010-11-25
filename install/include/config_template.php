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

function write_config_file($filename, $comments) {
	global $config_template;

	$tokens = array('{USER}',
					'{PASSWORD}',
					'{HOST}',
					'{PORT}',
					'{DBNAME}',
					'{TABLE_PREFIX}',
					'{TEMP_DIR}',
					'{GENERATED_COMMENTS}',
				);

		$values = array(urldecode($_POST['step2']['db_login']),
					addslashes(urldecode($_POST['step2']['db_password'])),
					$_POST['step2']['db_host'],
					$_POST['step2']['db_port'],
					$_POST['step2']['db_name'],
					$_POST['step2']['tb_prefix'],
					addslashes(urldecode($_POST['step4']['content_dir'])),
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
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
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
/* tables. Default: AC_                                                 */
define('TABLE_PREFIX',                 '{TABLE_PREFIX}');

/* Where the temporary files are located.  This includes all file       */
/* manager and imported files.  If security is a concern, it is         */
/* recommended that the temporary directory be moved outside of the web	*/
/* accessible area.														*/
define('AC_TEMP_DIR', '{TEMP_DIR}');

/* DO NOT ALTER THIS LAST LINE                                          */
define('AC_INSTALL', TRUE);

?".">";

?>