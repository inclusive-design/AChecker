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
 * DAO for "myown_patches_dependent" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class MyownPatchesDependentDAO extends DAO {

	/**
	 * Create new patch
	 * @access  public
	 * @param   myown_patch_id, dependent_patch_id
	 * @return  myown_patches_dependent_id, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Create($myown_patch_id, $dependent_patch_id)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."myown_patches_dependent 
               (myown_patch_id, 
                dependent_patch_id)
	        VALUES ('".$myown_patch_id."', 
	                '".$dependent_patch_id."')";
		
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
		$sql = "DELETE FROM ".TABLE_PREFIX."myown_patches_dependent
		         WHERE myown_patch_id = ".$patchID;

		return $this->execute($sql);
	}

	/**
	 * Return the patch dependent info with the given patch id
	 * @access  public
	 * @param   $patchID
	 * @return  patch row
	 * @author  Cindy Qi Li
	 */
	public function getByPatchID($patchID)
	{
		$sql = "SELECT * from ".TABLE_PREFIX."myown_patches_dependent
		         WHERE myown_patch_id=". $patchID." 
		         ORDER BY dependent_patch_id";
		
		return $this->execute($sql);
	}

}
?>