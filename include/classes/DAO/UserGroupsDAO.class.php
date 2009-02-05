<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

/**
 * DAO for "user_groups" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class UserGroupsDAO extends DAO {

	/**
	 * Create a new user group
	 * @access  public
	 * @param   title
	 *          description
	 * @return  user id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($title, $description)
	{
		global $addslashes, $msg;

		$missing_fields = array();

		/* email check */
		$title = $addslashes(trim($title));

		/* login name check */
		if ($title == '')
		{
			$missing_fields[] = _AC('title');
		}

		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}

		if (!$msg->containsErrors())
		{
			/* insert into the db */
			$sql = "INSERT INTO ".TABLE_PREFIX."user_groups
			              (title,
			               description,
			               create_date
			               )
			       VALUES ('".$title."',
			               '".$description."',
			               now()
			              )";

			if (!$this->execute($sql))
			{
				$msg->addError('DB_NOT_UPDATED');
				return false;
			}
			else
			{
				return mysql_insert_id();
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Update an existing user group
	 * @access  public
	 * @param   user_group_id
	 *          title
	 *          description
	 * @return  user id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Update($user_group_id, $title, $description)
	{
		global $addslashes, $msg;

		$missing_fields = array();

		/* email check */
		$title = $addslashes(trim($title));

		/* login name check */
		if ($title == '')
		{
			$missing_fields[] = _AC('title');
		}

		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}

		if (!$msg->containsErrors())
		{
			/* insert into the db */
			$sql = "UPDATE ".TABLE_PREFIX."user_groups
			           SET title = '".$title."',
			               description = '".$description."',
			               last_update = now()
			         WHERE user_group_id = ".$user_group_id;

			return $this->execute($sql);
		}
	}

	/**
	 * delete user group by given user id
	 * @access  public
	 * @param   user group ids
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function deleteUserGroupByIDs($user_group_ids)
	{
		$sql = 'DELETE FROM '.TABLE_PREFIX.'user_groups WHERE user_group_id in ('.$user_group_ids.')';
		return $rows = $this->execute($sql);
	}
	
	/**
	 * Return all user groups' information
	 * @access  public
	 * @param   none
	 * @return  user rows
	 * @author  Cindy Qi Li
	 */
	public function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_groups ORDER BY title';
		return $this->execute($sql);
	}

	/**
	 * Return user information by given user id
	 * @access  public
	 * @param   user group id
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getUserGroupByID($user_group_id)
		{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_groups WHERE user_group_id='.$user_group_id;
		if ($rows = $this->execute($sql))
		{
			return $rows[0];
		}
	}
}
?>