<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2011                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
* Root data access object
* Each table has a DAO class, all inherits from this class
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

class DAO {

	// private
	private $db;     // global database connection
	
	function DAO()
	{
		if (!isset(self::$db))
		{
			self::$db = @mysql_connect(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASSWORD);
			if (!self::$db) {
				die('Unable to connect to db.');
				/* AC_ERROR_NO_DB_CONNECT 
				require_once(AC_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
				$err = new ErrorHandler();
				trigger_error('VITAL#Unable to connect to db.', E_USER_ERROR);
				exit;
				*/
			}
			if (!@mysql_select_db(DB_NAME, self::$db)) {
				die('DB connection established, but database "'.DB_NAME.'" cannot be selected.');
				/*
				require_once(AC_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
				$err = new ErrorHandler();
				trigger_error('VITAL#DB connection established, but database "'.DB_NAME.'" cannot be selected.',
								E_USER_ERROR);
				exit;
				*/
			}
		}
	}
	
	/**
	* Execute SQL
	* @access  protected
	* @param   $sql : SQL statment to be executed
	* @return  $rows: for 'select' sql, return retrived rows, 
	*          true:  for non-select sql
	*          false: if fail
	* @author  Cindy Qi Li
	*/
	function execute($sql)
	{
		$sql = trim($sql);
		$result = mysql_query($sql, self::$db) or die($sql . "<br />". mysql_error());

		// Deal with "select" statement: return false if no row is returned, otherwise, return an array
		if ($result !== true && $result !== false) {
			$rows = false;
			
			while ($row = mysql_fetch_assoc($result)){
				if (!$rows) $rows = array();
				
			    $rows[] = $row;
			}
			mysql_free_result($result);
			return $rows;
		}
		return true;
	}

}
?>
