<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

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
	public function Create($userID, $title, $abbr, $long_name, $published_date, $earlid, $preamble, $status, $open_to_public)
	{
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
		$sql = "DELETE FROM ".TABLE_PREFIX."check_prerequisites WHERE check_id=".$checkID;
		return $this->execute($sql);
	}
	
}
?>