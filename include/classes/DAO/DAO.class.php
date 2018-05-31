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

	function DAO($db_host, $db_user, $db_pass, $db_name, $db_port)
	{
		if(isset($db_host) && isset($db_user) && isset($db_pass) && isset($db_name))
		{
			if (!isset($this->db))
			{
				$this->db = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
				if (!$this->db) {
					die('Unable to connect to db.');
				}
				if (!mysqli_select_db($this->db, $db_name)) {
					die('DB connection established, but database "'.$db_name.'" cannot be selected.');
				}
		    }
		}else
		{
			if (!isset($this->db))
			{
				$this->db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
				if (!$this->db) {
					die('Unable to connect to db.');
				}
				if (!mysqli_select_db($this->db, DB_NAME)) {
					die('DB connection established, but database "'.DB_NAME.'" cannot be selected.');
				}
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

	function addSlashes($string){
		if ( get_magic_quotes_gpc() == 1 ) {
			$string = stripslashes($string);
		} 
		return mysqli_real_escape_string($this->db, $string);

	}

	function getInsertID(){
		return mysqli_insert_id($this->db);
	}
}
?>
