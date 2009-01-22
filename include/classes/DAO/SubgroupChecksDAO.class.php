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
* DAO for "subgroup_checks" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class SubgroupChecksDAO extends DAO {

	/**
	* Create a new entry of subgroup_id <=> check_id relationship
	* @access  public
	* @param   $groupID : guideline subgroup id
	*          $checkID : check ID
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Create($subgroupID, $checkID)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."subgroup_checks
				(`subgroup_id`, `check_id`) 
				VALUES
				(".$subgroupID.",".$checkID.")";

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	* Delete all entries of given guideline ID
	* @access  public
	* @param   $guidelineID : guideline id
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Delete($guidelineID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."subgroup_checks
				WHERE subgroup_id IN (SELECT distinct s.subgroup_id 
				                     FROM ".TABLE_PREFIX."guideline_subgroups s, ".TABLE_PREFIX."guideline_groups g
				                    WHERE s.subgroup_id = g.subgroup_id
				                      AND g.guideline_id = ".$guidelineID;

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			return true;
		}
	}
	
}
?>