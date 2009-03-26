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
* DAO for "test_files" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class TestFiles extends DAO {

	/**
	* Delete all entries with given check id
	* @access  public
	* @param   $checkID
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function DeleteByCheckID($checkID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."test_files
				WHERE check_id = ".$checkID;

		return $this->execute($sql);
	}
	
}
?>