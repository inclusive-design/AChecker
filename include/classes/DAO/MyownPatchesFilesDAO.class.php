<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
 * DAO for "myown_patches_files" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class MyownPatchesFilesDAO extends DAO {

	/**
	 * Create new row
	 * @access  public
	 * @param   $myown_patch_id, $action, $name, $location,
	 *          $code_from, $code_to, $uploaded_file
	 * @return  myown_patches_files_id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($myown_patch_id, $action, $name, $location,
	                       $code_from, $code_to, $uploaded_file)
	{
		global $addslashes;

		$sql = "INSERT INTO ".TABLE_PREFIX."myown_patches_files
               (myown_patch_id, 
               	action,
               	name,
               	location,
               	code_from,
                code_to,
                uploaded_file)
	        VALUES ('".$myown_patch_id."', 
	                '".$action."', 
	                '".$name."', 
	                '".$location."', 
	                '".$code_from."', 
	                '".$code_to."',
	                '".$addslashes($uploaded_file)."')";
		
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

	/**
	 * Create new user
	 * @access  public
	 * @param   user_group_id: user group ID (1 [admin] or 2 [user])
	 *          login: login name
	 *          pwd: password
	 *          email: email
	 *          first_name: first name
	 *          last_name: last name
	 * @return  user id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Update($userID, $user_group_id, $login, $email, $first_name, $last_name, $status)
	{
		global $addslashes, $msg;

		/* email check */
		$login = $addslashes(strtolower(trim($login)));
		$email = $addslashes(trim($email));
		$first_name = $addslashes(str_replace('<', '', trim($first_name)));
		$last_name = $addslashes(str_replace('<', '', trim($last_name)));

		if ($this->isFieldsValid('update', $user_group_id,$login, $email,$first_name, $last_name))
		{
			/* insert into the db */
			$sql = "UPDATE ".TABLE_PREFIX."users
			           SET login = '".$login."',
			               user_group_id = '".$user_group_id."',
			               first_name = '".$first_name."',
			               last_name = '".$last_name."',
			               email = '".$email."',
			               status = '".$status."'
			         WHERE user_id = ".$userID;

			return $this->execute($sql);
		}
	}

	/**
	 * Delete rows by given patch id
	 * @access  public
	 * @param   patchID
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function DeleteByPatchID($patchID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."myown_patches_files
		         WHERE myown_patch_id = ".$patchID;
		
		return $this->execute($sql);
	}

	/**
	 * Return the patch files info with the given patch id
	 * @access  public
	 * @param   $patchID
	 * @return  patch row
	 * @author  Cindy Qi Li
	 */
	public function getByPatchID($patchID)
	{
		$sql = "SELECT * from ".TABLE_PREFIX."myown_patches_files
		         WHERE myown_patch_id=". $patchID."
		         ORDER BY myown_patches_files_id";
		
		return $this->execute($sql);
	}

}
?>