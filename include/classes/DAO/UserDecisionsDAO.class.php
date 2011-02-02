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
 * DAO for "user_decisions" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class UserDecisionsDAO extends DAO {

	/**
	 * Create new user decisoin
	 * @access  public
	 * @param   $user_link_id
	 *          $line_num
	 *          $column_num
	 *          $check_id
	 *          $decision
	 * @return  user link id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($user_link_id, $line_num, $column_num, $check_id, $decision)
	{
		global $addslashes;
		
		$user_link_id = intval($user_link_id);
		$line_num = intval($line_num);
		$column_num = intval($column_num);
		$check_id = intval($check_id);
		$decision = $addslashes($decision);
		
		/* insert into the db */
		$sequence_id = $this->getMaxSequenceID($user_link_id)+1;
		
		$sql = "INSERT INTO ".TABLE_PREFIX."user_decisions
		              (user_link_id,
		               line_num,
		               column_num,
		               check_id,
		               sequence_id,
		               decision,
		               last_update
		               )
		       VALUES (".$user_link_id.",
		               ".$line_num.",
		               ".$column_num.",
		               ".$check_id.",
		               ".$sequence_id.",
		               '".$decision."',
		               now())";

		if (!$this->execute($sql)) return false;
		else return $sequence_id;
	}

	/**
	 * Delete user decision by user link id
	 * @access  public
	 * @param   user_link_id : required
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function DeleteByUserLinkID($user_link_id)
	{
		$user_link_id = intval($user_link_id);
		
		// delete customized guidelines created by user but yet open to public
		$sql = "DELETE FROM ".TABLE_PREFIX."user_decisions
		         WHERE user_link_id = ".$user_link_id;

		return $this->execute($sql);
	}

	/**
	 * Delete user decision by user id
	 * @access  public
	 * @param   user_id : required
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function DeleteByUserID($user_id)
	{
		$user_id = intval($user_id);
		
		// delete customized guidelines created by user but yet open to public
		$sql = "DELETE FROM ".TABLE_PREFIX."user_decisions
		         WHERE user_link_id in (SELECT DISTINCT user_link_id 
		                                  FROM ".TABLE_PREFIX."user_links
		                                 WHERE user_id = ".$user_id.")";

		return $this->execute($sql);
	}

	/**
	 * Delete decision
	 * @access  public
	 * @param   $user_link_id : required
	 *          $line_num: required
	 *          $column_num: required
	 *          $check_id :required
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function DeleteDecision($user_link_id, $line_num, $column_num, $check_id)
	{
		$user_link_id = intval($user_link_id);
		$line_num = intval($line_num);
		$column_num = intval($column_num);
		$check_id = intval($check_id);
		
		$sql = "DELETE FROM ".TABLE_PREFIX."user_decisions 
		         WHERE user_link_id = ".$user_link_id."
		           AND line_num = ".$line_num."
		           AND column_num = ".$column_num."
		           AND check_id = ".$check_id;
		return $this->execute($sql);
	}

	/**
	 * Return all users' information
	 * @access  public
	 * @param   none
	 * @return  user rows
	 * @author  Cindy Qi Li
	 */
	public function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_decisions ORDER BY user_link_id, sequence_id';
		return $this->execute($sql);
	}

	/**
	 * Return row by given user link id and sequence id
	 * @access  public
	 * @param   user_link_id
	 *          sequence_id
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getByUserLinkIDAndSequenceID($user_link_id, $sequence_id)
	{
		$user_link_id = intval($user_link_id);
		$sequence_id = intval($sequence_id);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_decisions 
		         WHERE user_link_id='.$user_link_id.'
		           AND sequence_id = '.$sequence_id;
		return $this->execute($sql);
	}

	/**
	 * Return row by given user link id and sequence id
	 * @access  public
	 * @param   user_link_id
	 *          sequence_id
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getByUserLinkIDAndLineNumAndColNumAndCheckID($user_link_id, $line_num, $column_num, $check_id)
	{
		$user_link_id = intval($user_link_id);
		$line_num = intval($line_num);
		$column_num = intval($column_num);
		$check_id = intval($check_id);
		
		$sql = "SELECT * FROM ".TABLE_PREFIX."user_decisions 
		         WHERE user_link_id = ".$user_link_id."
		           AND line_num = ".$line_num."
		           AND column_num = ".$column_num."
		           AND check_id = ".$check_id;
		$rows = $this->execute($sql);
		if (!$rows)
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			return $rows[0];
		}
	}

	/**
	 * Return max sequence id of given user link id
	 * @access  public
	 * @param   user_link_id
	 * @return  max sequence id, if sequence id not exists, return 0
	 * @author  Cindy Qi Li
	 */
	public function getMaxSequenceID($user_link_id)
	{
		$user_link_id = intval($user_link_id);
		
		$sql = 'SELECT max(sequence_id) max_sequence_id FROM '.TABLE_PREFIX.'user_decisions 
		         WHERE user_link_id='.$user_link_id;
		$rows = $this->execute($sql);
		$max_sequence_id = $rows[0]['max_sequence_id'];
		
		if ($max_sequence_id == '' || $max_sequence_id == NULL)
			$max_sequence_id = 0;
		
		return $max_sequence_id;
	}

	/**
	 * Validate fields for insert and update
	 * @access  public
	 * @param   $user_link_id : required
	 *          $sequence_id :required
	 *          $decision 
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function setDecision($user_link_id, $sequence_id, $decision)
	{
		global $addslashes;
		
		$user_link_id = intval($user_link_id);
		$sequence_id = intval($sequence_id);
		$decision = $addslashes($decision);
		
		$sql = "UPDATE ".TABLE_PREFIX."user_decisions 
		           SET decision='".$decision."'
		         WHERE user_link_id = ".$user_link_id."
		           AND sequence_id = ".$sequence_id;
		return $this->execute($sql);
	}

	/**
	 * Reverse decision
	 * @access  public
	 * @param   $user_link_id : required
	 *          $sequence_id :required
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function reverseDecision($user_link_id, $sequence_id)
	{
		$user_link_id = intval($user_link_id);
		$sequence_id = intval($sequence_id);
		
		$sql = "UPDATE ".TABLE_PREFIX."user_decisions 
		           SET decision='".AC_NO_DECISION."'
		         WHERE user_link_id = ".$user_link_id."
		           AND sequence_id = ".$sequence_id;
		return $this->execute($sql);
	}

	/**
	 * save errors
	 * loop thru error array, if the error has been saved in user_decisions, skip; 
	 * otherwise, save with decision AC_NO_DECISION
	 * @access  public
	 * @param   $user_link_id : required
	 *          $errors : an error array generated by AccessibilityValidator->getValidationErrorRpt()
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function saveErrors($user_link_id, $errors)
	{
		$user_link_id = intval($user_link_id);
		
		foreach ($errors as $error)
		{
			$rows = $this->getByUserLinkIDAndLineNumAndColNumAndCheckID
			        ($user_link_id, $error['line_number'], $error['col_number'], $error['check_id']);
			        
			if (!is_array($rows))
			{
				$this->Create($user_link_id, $error['line_number'], $error['col_number'], $error['check_id'], AC_NO_DECISION);
			}
		}
	}
}
?>