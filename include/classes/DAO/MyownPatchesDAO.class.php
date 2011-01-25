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
 * DAO for "myown_patches" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class MyownPatchesDAO extends DAO {

	/**
	 * Create new row
	 * @access  public
	 * @param   achecker_patch_id, applied_versin, description, sql_statement
	 * @return  myown_patch_id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($achecker_patch_id, $applied_version, 
	                       $description, $sql_statement)
	{
		global $addslashes;

		$sql = "INSERT INTO ".TABLE_PREFIX."myown_patches 
	               (achecker_patch_id, 
	                applied_version,
	                description,
	                sql_statement,
	                status,
	                last_modified)
		        VALUES ('".$achecker_patch_id."', 
		                '".$applied_version."', 
		                '".$description."', 
		                '".$sql_statement."', 
		                'Created',
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

	/**
	 * Update a row
	 * @access  public
	 * @param   myown_patch_id, achecker_patch_id, applied_versin, description, sql_statement
	 * @return  true, if successful. Otherwise, false
	 * @author  Cindy Qi Li
	 */
	public function Update($myown_patch_id, $achecker_patch_id, $applied_version, 
	                       $description, $sql_statement)
	{
		global $addslashes;

		$sql = "UPDATE ".TABLE_PREFIX."myown_patches 
		           SET achecker_patch_id = '". $achecker_patch_id ."',
		               applied_version = '". $applied_version ."',
		               description = '". $description ."',
		               sql_statement = '". $sql_statement ."',
		               status = 'Created',
		               last_modified = now()
		         WHERE myown_patch_id = ". $myown_patch_id;
	
		return $this->execute($sql);
	}

	/**
	 * Delete a patch
	 * @access  public
	 * @param   patchID
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Delete($patchID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."myown_patches
		         WHERE myown_patch_id = ".$patchID;

		return $this->execute($sql);
	}

	/**
	 * Return all my own patches
	 * @access  public
	 * @param   none
	 * @return  all table rows
	 * @author  Cindy Qi Li
	 */
	public function getAll()
	{
		$sql = "SELECT * from ".TABLE_PREFIX."myown_patches m order by last_modified desc";
		
		return $this->execute($sql);
	}

	/**
	 * Return the patch info with the given patch id
	 * @access  public
	 * @param   $patchID
	 * @return  patch row
	 * @author  Cindy Qi Li
	 */
	public function getByID($patchID)
	{
		$sql = "SELECT * from ".TABLE_PREFIX."myown_patches where myown_patch_id=". $patchID;
		
		$rows = $this->execute($sql);
		
		if (is_array($rows)) return $rows[0];
		else return false;
	}

}
?>