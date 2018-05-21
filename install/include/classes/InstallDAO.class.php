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
* Each table has a InstallDAO class, all inherits from this class
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

class InstallDAO {

	// protected
	protected $db;     // global database connection

	function __construct($db_host, $db_login, $db_password, $db_name, $db_port)
	{
		if (!isset($this->db))
		{
			$this->db = mysqli_connect($db_host, $db_login, $db_password, $db_name, $db_port);
			if (!$this->db) {
				die('Unable to connect to db.');
			}
			if (!mysqli_select_db($this->db, $db_name)) {
				die('DB connection established, but database "'.$db_name.'" cannot be selected.');
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
		return $result = mysqli_query($this->db, $sql);
	}

	function my_add_null_slashes($string) {
		$string = stripslashes($string);
		return mysqli_real_escape_string($this->db, $string);
	}

	function my_null_slashes($string) {
		return mysqli_real_escape_string($this->db, $string);
	} 

	function addSlashes($string){
		if ( get_magic_quotes_gpc() == 1 ) {
			return $this->my_add_null_slashes($string);
		} else {
			return $this->my_null_slashes($string);
		}
		
	}
}
?>
