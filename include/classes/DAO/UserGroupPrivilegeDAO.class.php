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
 * DAO for "user_groups" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class UserGroupPrivilegeDAO extends DAO {

	/**
	 * Create
	 * @access  public
	 * @param   userGroupID
	 *          privilegeID
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($userGroupID, $privilegeID)
	{
		$userGroupID = intval($userGroupID);
		$privilegeID = intval($privilegeID);
		
		$sql = "INSERT INTO ".TABLE_PREFIX."user_group_privilege
		              (user_group_id,
		               privilege_id
		               )
		       VALUES (".$userGroupID.",
		               ".$privilegeID."
		              )";
	
		return $this->execute($sql);
	}

	/**
	 * Delete a row
	 * @access  public
	 * @param   userGroupID
	 *          privilegeID
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Delete($userGroupID, $privilegeID)
	{
		$userGroupID = intval($userGroupID);
		$privilegeID = intval($privilegeID);
		
		$sql = "DELETE FROM ".TABLE_PREFIX."user_group_privilege
		         WHERE user_group_id = ".$userGroupID."
		           AND privilege_id = ".$privilegeID;
	
		return $this->execute($sql);
	}

	/**
	 * Update an existing user group
	 * @access  public
	 * @param   userGroupID
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function DeleteByUserGroupID($userGroupID)
	{
		$userGroupID = intval($userGroupID);
		
		$sql = "DELETE FROM ".TABLE_PREFIX."user_group_privilege
		         WHERE user_group_id = ".$userGroupID;

		return $this->execute($sql);
	}

}
?>