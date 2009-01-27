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
* DAO for "guideline_groups" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class GuidelineGroupsDAO extends DAO {

	/**
	* Create a new guideline group
	* @access  public
	* @param   $guidelineID : guideline id
	*          $name
	*          $abbr
	*          $principle
	* @return  group_id : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Create($guidelineID, $name, $abbr, $principle)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."guideline_groups
				(`guideline_id`, `abbr`, `principle`) 
				VALUES
				(".$guidelineID.", '".$abbr."', '".$principle."')";

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			$group_id = mysql_insert_id();

			if ($name <> '')
			{
				$term = LANG_PREFIX_GUIDELINE_GROUPS_NAME.$group_id;
				
				require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
				$langTextDAO = new LanguageTextDAO();
				
				if ($langTextDAO->Create('en', '_template',$term,$name,''))
				{
					$sql = "UPDATE ".TABLE_PREFIX."guideline_groups 
					           SET name='".$term."' WHERE group_id=".$group_id;
					$this->execute($sql);
				}
			}
			return $group_id;
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
		$sql = "DELETE FROM ".TABLE_PREFIX."guideline_groups
                 WHERE guideline_id = ".$guidelineID;

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
	* Return array of group ids of the given guideline id
	* @access  public
	* @param   $guidelineID : guideline id
	* @return  group id rows : array of group ids, if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function getGroupByGuidelineID($guidelineID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."guideline_groups 
                 WHERE guideline_id = ".$guidelineID;

		return $this->execute($sql);
	}
}
?>