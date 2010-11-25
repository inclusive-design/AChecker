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

/**
* Root data access object
* Each table has a DAO class, all inherits from this class
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

class DAO {

	// private
	static private $db;     // global database connection
	
	function DAO()
	{
		if (!isset($this->db))
		{
			$this->db = @mysql_connect(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASSWORD);
			if (!$this->db) {
				die('Unable to connect to db.');
				/* AC_ERROR_NO_DB_CONNECT 
				require_once(AC_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
				$err =& new ErrorHandler();
				trigger_error('VITAL#Unable to connect to db.', E_USER_ERROR);
				exit;
				*/
			}
			if (!@mysql_select_db(DB_NAME, $this->db)) {
				die('DB connection established, but database "'.DB_NAME.'" cannot be selected.');
				/*
				require_once(AC_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
				$err =& new ErrorHandler();
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
//		debug($sql);
		$sql = trim($sql);
		$result = mysql_query($sql, $this->db) or die($sql . "<br />". mysql_error());

		// for 'select' SQL, return retrieved rows
		if (strtolower(substr($sql, 0, 6)) == 'select' && mysql_num_rows($result) > 0) 
		{
			for($i = 0; $i < mysql_num_rows($result); $i++) 
			{
				$rows[] = mysql_fetch_assoc($result);
			}
			mysql_free_result($result);
			return $rows;
		}

		return true;
	}

}
?>