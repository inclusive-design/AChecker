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
* DAO for "check_prerequisites" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class CheckPrerequisitesDAO extends DAO {

	/**
	* Create a new entry
	* @access  public
	* @param   $checkID
	*          $prerequisiteCheckID
	* @return  created row : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Create($checkID, $prerequisiteCheckID)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."check_prerequisites (check_id, prerequisite_check_id) 
		        VALUES (".intval($checkID).", ".intval($prerequisiteCheckID).")";
		return $this->execute($sql);
	}
	
	/**
	* Delete by primary key
	* @access  public
	* @param   $checkID
	*          $prerequisiteCheckID
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function Delete($checkID, $prerequisiteCheckID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."check_prerequisites 
		         WHERE check_id=".intval($checkID)." AND prerequisite_check_id=".intval($prerequisiteCheckID);
		return $this->execute($sql);
	}
	
	/**
	* Delete prerequisites by given check ID
	* @access  public
	* @param   $checkID
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function DeleteByCheckID($checkID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."check_prerequisites WHERE check_id=".intval($checkID);
		return $this->execute($sql);
	}
	
	/**
	* Return prerequisite check IDs by given check ID
	* @access  public
	* @param   $checkID
	* @return  table rows : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function getPreChecksByCheckID($checkID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."checks 
		         WHERE check_id in (SELECT prerequisite_check_id 
		                              FROM ".TABLE_PREFIX."check_prerequisites 
		                             WHERE check_id=".intval($checkID).")";
		return $this->execute($sql);
	}
	
}
?>