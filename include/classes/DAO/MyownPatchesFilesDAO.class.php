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
	                '".mysql_real_escape_string($uploaded_file)."')";
		
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