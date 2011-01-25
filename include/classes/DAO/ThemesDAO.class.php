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
* DAO for "themes" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class ThemesDAO extends DAO {

	/**
	* Return all theme' information
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'themes ORDER BY dir_name';
		return $this->execute($sql);
	}

	/**
	* Return theme by theme dir name
	* @access  public
	* @param   dirName : theme dir name
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getByID($dirName)
	{
		global $addslashes;
		$dirName = $addslashes($dirName);
		
		$sql = "SELECT * FROM '.TABLE_PREFIX.'themes WHERE dir_name='".$dirName."'";
		if ($rows = $this->execute($sql)){ 
			return $rows[0];
		}
	}

	/**
	* Return all default themes
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getDefaultTheme()
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."themes WHERE status=".AC_STATUS_DEFAULT;
		return $this->execute($sql);
	}
}
?>