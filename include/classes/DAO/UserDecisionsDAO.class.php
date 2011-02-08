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
	public function DeleteByUserIDs($userIDs)
	{
		// delete customized guidelines created by user but yet open to public
		$sql = "DELETE FROM ".TABLE_PREFIX."user_decisions
		         WHERE user_link_id in (SELECT user_link_id 
		                                  FROM ".TABLE_PREFIX."user_links
		                                 WHERE user_id in (".implode(",", $userIDs)."))";

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
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'user_decisions ORDER BY user_link_id, line_num, column_num, check_id';
		return $this->execute($sql);
	}

	/**
	 * Return row by given user link id and sequence id
	 * @access  public
	 * @param   user_link_id,
	 *          line_num,
	 *          column_num,
	 *          check_id
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
		if (!$rows) {
			return false;
		} else {
			return $rows[0];
		}
	}

	/**
	 * Validate fields for insert and update
	 * @access  public
	 * @param   $user_link_id: required
	 *          $line_num: required
	 *          $col_num: required
	 *          $check_id: required
	 *          $decision: required
	 *          $decision 
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function setDecision($user_link_id, $line_num, $col_num, $check_id, $decision)
	{
		global $addslashes;
		
		$user_link_id = intval($user_link_id);
		$line_num = intval($line_num);
		$col_num = intval($col_num);
		$check_id = intval($check_id);
		$decision = $addslashes($decision);
		
		$row = $this->getByUserLinkIDAndLineNumAndColNumAndCheckID
        ($user_link_id, $line_num, $col_num, $check_id);
		
		// Note that "No decison" is not saved in db
		// If the decision is pass/fail:
		// 1. not in db yet -> create a new row;
		// 2. in db but the decision is modified -> update the row with the new decision
		// 3. otherwise, do nothing.
		// If the decision is "no decision"
		// 1. the row, identified by user_link_id, line_num, col_num, check_id, is in db
		//    with a different decision -> delete this row
		// 2. otherwise, do nothing. 
        if ($decision == AC_DECISION_PASS || $decision == AC_DECISION_FAIL) {
			if (!is_array($row)) {
				$sql = "INSERT INTO ".TABLE_PREFIX."user_decisions
		              (user_link_id,
		               line_num,
		               column_num,
		               check_id,
		               decision,
		               last_update
		               )
		               VALUES (".$user_link_id.",
		               ".$line_num.",
		               ".$col_num.",
		               ".$check_id.",
		               '".$decision."',
		               now())";
				return $this->execute($sql);
			} else if ($row['decision'] != $decision) {
		        $sql = "UPDATE ".TABLE_PREFIX."user_decisions 
				           SET decision='".$decision."'
				         WHERE user_link_id = ".$user_link_id."
				           AND line_num = ".$line_num."
				           AND column_num = ".$col_num."
				           AND check_id = ".$check_id;
				return $this->execute($sql);
			}
		} else if (is_array($row) && $decision == AC_NO_DECISION) {
			$sql = "DELETE FROM ".TABLE_PREFIX."user_decisions 
			         WHERE user_link_id = ".$user_link_id."
			           AND line_num = ".$line_num."
			           AND column_num = ".$col_num."
			           AND check_id = ".$check_id;
			return $this->execute($sql);
		}
		return true;
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
//	public function saveErrors($user_link_id, $errors)
//	{
//		$user_link_id = intval($user_link_id);
//		
//		foreach ($errors as $error)
//		{
//			$rows = $this->getByUserLinkIDAndLineNumAndColNumAndCheckID
//			        ($user_link_id, $error['line_number'], $error['col_number'], $error['check_id']);
//			        
//			if (!is_array($rows))
//			{
//				$this->Create($user_link_id, $error['line_number'], $error['col_number'], $error['check_id'], AC_NO_DECISION);
//			}
//		}
//	}
}
?>