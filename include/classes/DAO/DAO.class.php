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

	// protected
	protected $db;     // global database connection
	protected $addslashes;

	function DAO()
	{
		if (!isset($this->db))
		{
			$this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
			if (!$this->db) {
				die('Unable to connect to db.');
				/* AC_ERROR_NO_DB_CONNECT 
				require_once(AC_INCLUDE_PATH . 'classes/ErrorHandler/ErrorHandler.class.php');
				$err = new ErrorHandler();
				trigger_error('VITAL#Unable to connect to db.', E_USER_ERROR);
				exit;
				*/
			}
			if (!mysqli_select_db($this->db, DB_NAME)) {
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
		$result = mysqli_query($this->db, $sql) or die($sql . "<br />". mysqli_error($this->db));

		// Deal with "select" statement: return false if no row is returned, otherwise, return an array
		if ($result !== true && $result !== false) {
			$rows = false;
			
			while ($row = mysqli_fetch_assoc($result)){
				if (!$rows) $rows = array();
				
			    $rows[] = $row;
			}
			mysqli_free_result($result);
			return $rows;
		}
		return true;
	}

	function addSlashes($sql){
		return $this->addslashes = mysqli_real_escape_string($this->db,$sql);
	}

	function insertID(){
		return mysqli_insert_id($this->db);
	}
}
?>
