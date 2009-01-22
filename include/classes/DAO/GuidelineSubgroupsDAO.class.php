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
* DAO for "guideline_subgroups" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class GuidelineSubgroupsDAO extends DAO {

	/**
	* Create a new guideline subgroup
	* @access  public
	* @param   $groupID : guideline group id
	*          $name
	*          $abbr
	* @return  subgroup_id : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Create($groupID, $name, $abbr)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."guideline_subgroups
				(`group_id`, `abbr`) 
				VALUES
				(".$groupID.", '".$abbr."')";

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			$subgroup_id = mysql_insert_id();

			if ($name <> '')
			{
				$term = LANG_PREFIX_GUIDELINE_SUBGROUPS_NAME.$subgroup_id;
				
				require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
				$langTextDAO = new LanguageTextDAO();
				
				if ($langTextDAO->Create('en', '_template',$term,$name,''))
				{
					$sql = "UPDATE ".TABLE_PREFIX."guideline_subgroups 
					           SET name='".$term."' WHERE subgroup_id=".$subgroup_id;
					$this->execute($sql);
				}
			}
			return $subgroup_id;
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
		$sql = "DELETE FROM ".TABLE_PREFIX."guideline_subgroups
				WHERE group_id IN (SELECT group_id 
				                     FROM ".TABLE_PREFIX."guideline_groups
				                    WHERE guideline_id = ".$guidelineID.")";

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
	* Return array of subgroup ids of the given group id
	* @access  public
	* @param   $groupID : group id
	* @return  subgroup id rows : array of subgroup ids, if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function getSubgroupByGuidelineID($groupID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."guideline_subgroups
                 WHERE group_id = ".$groupID;

		return $this->execute($sql);
	}
}
?>