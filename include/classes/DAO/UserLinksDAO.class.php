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
 * DAO for "user_links" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');

class UserLinksDAO extends DAO {

	/**
	 * Create new user link
	 * @access  public
	 * @param   $user_id
	 *          $guideline_ids
	 *          $URI
	 * @return  user link id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($user_id, $guideline_ids, $URI)
	{
		global $addslashes;
		
		$user_id = intval($user_id);
		$URI = $addslashes($URI);
		
		if ($this->isFieldsValid($guideline_ids, $URI))
		{
			/* insert into the db */
			$sql = "INSERT INTO ".TABLE_PREFIX."user_links
			              (user_id,
			               last_guideline_ids,
			               URI,
			               last_update
			               )
			       VALUES (".$user_id.",
			               '".$guideline_ids."',
			               '".$URI."',
			               now())";

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
	 * Update
	 * @access  public
	 * @param   $user_link_id: required
	 *          $user_id
	 *          $guideline_ids
	 *          $URI
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Update($user_link_id, $user_id, $guideline_ids, $URI)
	{
		global $addslashes;

		if ($this->isFieldsValid($guideline_ids, $URI))
		{
			$user_link_id = intval($user_link_id);
			$user_id = intval($user_id);
			$URI = $addslashes($URI);
			
			/* insert into the db */
			$sql = "UPDATE ".TABLE_PREFIX."user_links
			           SET user_id = ".$user_id.",
			               last_guideline_ids = '".$guideline_ids."',
			               URI = '".$URI."',
			               last_update = now()
			         WHERE user_link_id = ".$user_link_id;

			return $this->execute($sql);
		}
	}

	/**
	 * Delete user link
	 * @access  public
	 * @param   user_link_id : required
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Delete($user_link_id)
	{
		// delete customized guidelines created by user but yet open to public
		include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');
		$userDecisionsDAO = new UserDecisionsDAO();
		
		$user_link_id = intval($user_link_id);
		
		// delete according records from table user_decisions
		if (!$userDecisionsDAO->DeleteByUserLinkID($user_link_id))
			return false;
		
		$sql = "DELETE FROM ".TABLE_PREFIX."user_links
		         WHERE user_link_id = ".$user_link_id;

		return $this->execute($sql);
	}

	/**
	 * Delete by user ID. This function deletes from tables user_links, user_decisions
	 * @access  public
	 * @param   user_id : required
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function DeleteByUserID($userIDs)
	{
		// delete customized guidelines created by user but yet open to public
		include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');
		$userDecisionsDAO = new UserDecisionsDAO();
		
		// delete according records from table user_decisions
		if (!$userDecisionsDAO->DeleteByUserIDs($userIDs))
			return false;
		
		$sql = "DELETE FROM ".TABLE_PREFIX."user_links
		         WHERE user_id in (".implode(",", $userIDs).")";

		return $this->execute($sql);
	}

	/**
	 * set last sessionID
	 * Session ID is used to validate the user decisions received is the response 
	 * for questions sent by server which embed with the same session id  
	 * @access  public
	 * @param   $user_link_id
	 *          $sessionID
	 * @return  user rows
	 * @author  Cindy Qi Li
	 */
	public function setLastSessionID($user_link_id, $sessionID)
	{
		global $addslashes;
		
		$user_link_id = intval($user_link_id);
		$sessionID = $addslashes($sessionID);
		
		$sql = "UPDATE ".TABLE_PREFIX."user_links SET last_sessionID = '".$sessionID."'
		         WHERE user_link_id = ".$user_link_id;
		return $this->execute($sql);
	}

	/**
	 * Return all user link information
	 * @access  public
	 * @param   none
	 * @return  user rows
	 * @author  Cindy Qi Li
	 */
	public function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_links ORDER BY user_link_id';
		return $this->execute($sql);
	}

	/**
	 * Return row by given user link id
	 * @access  public
	 * @param   $user_link_id
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getByUserLinkID($user_link_id)
	{
		$user_link_id = intval($user_link_id);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_links WHERE user_link_id='.$user_link_id;
		if ($rows = $this->execute($sql))
		{
			return $rows[0];
		}
	}

	/**
	 * Return row by given user id and URI
	 * @access  public
	 * @param   $user_id
	 *          $URI
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getByUserIDAndURI($user_id, $URI)
	{
		global $addslashes;

		$user_id = intval($user_id);
		$URI = $addslashes($URI);
		
		$sql = "SELECT * FROM ".TABLE_PREFIX."user_links 
		         WHERE user_id=".$user_id."
		           AND URI ='".$URI."'";
		
		return $this->execute($sql);
	}

	/**
	 * Return row by given user id, URI and session ID
	 * @access  public
	 * @param   $user_id
	 *          $URI
	 *          $sessionID
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getByUserIDAndURIAndSession($user_id, $URI, $sessionID)
	{
		global $addslashes;
		
		$user_id = intval($user_id);
		$URI = $addslashes($URI);
		$sessionID = $addslashes($sessionID);

		$sql = "SELECT * FROM ".TABLE_PREFIX."user_links 
		         WHERE user_id=".$user_id."
		           AND URI ='".$URI."'
		           AND last_sessionID = '".$sessionID."'";
		
		return $this->execute($sql);
	}

	/**
	 * If row with given $user_id, $URI already exists, return existing user_link_id;
	 * otherwise, create a new row and return the new user_link_id
	 * @access  public
	 * @param   $user_id
	 *          $URI
	 *          $gids
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getUserLinkID($user_id, $URI, $gids)
	{
		// sanitize array gids
		if (!is_array($gids)) return false;
		
		$sanitized_gids = Utility::sanitizeIntArray($gids);
		$sanitized_gids_str = implode(",", $sanitized_gids);
		
		$rows = $this->getByUserIDAndURI($user_id, $URI);
			
		if (is_array($rows))
		{
			$user_link_id = $rows[0]['user_link_id'];

			// if guidelines selected are changed, save into table
			if ($rows[0]['last_guideline_ids'] <> $sanitized_gids_str)
			{
				$this->Update($user_link_id, $user_id, $sanitized_gids_str, $URI);
			}
		}
		else
		{
			$user_link_id = $this->Create($user_id, $sanitized_gids_str, $URI);
		}
		
		return $user_link_id;
	}
	
	/**
	 * Validate fields for insert and update
	 * @access  private
	 * @param   $guideline_ids
	 *          $URI
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	private function isFieldsValid($guideline_ids, $URI)
	{
		global $msg;
		
		$missing_fields = array();
		/* login name check */
		if ($guideline_ids == '')
		{
			$missing_fields[] = _AC('guideline_ids');
		}
		if ($URI == '')
		{
			$missing_fields[] = _AC('URI');
		}
		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}
		
		if ($msg->containsErrors())
			return false;
		else
			return true;
	}
}
?>