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
		global $addslashes;
		
		$name = $addslashes(trim($name));	
		$abbr = $addslashes(trim($abbr));
		
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
				
				if ($langTextDAO->Create($_SESSION['lang'], '_guideline',$term,$name,''))
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
	* Update an existing guideline subgroup
	* @access  public
	* @param   $subgroupID : subgroup id
	*          $name
	*          $abbr
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Update($subgroupID, $name, $abbr)
	{
		global $addslashes;
		
		$name = $addslashes(trim($name));	
		$abbr = $addslashes(trim($abbr));
		
		$sql = "UPDATE ".TABLE_PREFIX."guideline_subgroups
				   SET abbr='".$abbr."' 
				 WHERE subgroup_id = ".$subgroupID;

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			if ($name <> '')
			{
				$term = LANG_PREFIX_GUIDELINE_SUBGROUPS_NAME.$subgroupID;
				$this->updateLang($subgroupID, $term, $name, 'name');
			}
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
//	public function Delete($guidelineID)
//	{
//		$sql = "DELETE FROM ".TABLE_PREFIX."guideline_subgroups
//				WHERE group_id IN (SELECT group_id 
//				                     FROM ".TABLE_PREFIX."guideline_groups
//				                    WHERE guideline_id = ".$guidelineID.")";
//
//		if (!$this->execute($sql))
//		{
//			$msg->addError('DB_NOT_UPDATED');
//			return false;
//		}
//		else
//		{
//			return true;
//		}
//	}

	/**
	* Delete all entries of given subgroup ID
	* @access  public
	* @param   $subgroupID : subgroup id
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Delete($subgroupID)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		
		// Delete all checks in this subgroup
		$subgroupChecksDAO = new SubgroupChecksDAO();
		if ($subgroupChecksDAO->DeleteBySubgroupID($subgroupID))
		{
			// delete language for subgroup name
			$sql = "DELETE FROM ".TABLE_PREFIX."language_text 
			         WHERE variable='_guideline' 
			           AND term=(SELECT name 
			                       FROM ".TABLE_PREFIX."guideline_subgroups
			                      WHERE subgroup_id=".$subgroupID.")";
			$this->execute($sql);
				
			// delete guideline_subgroups
			$sql = "DELETE FROM ".TABLE_PREFIX."guideline_subgroups WHERE subgroup_id=".$subgroupID;
			
			return $this->execute($sql);
		}
		else
			return false;
	}

	/**
	* Add checks into guideline subgroup
	* @access  public
	* @param   $groupID : subgroup id
	*          $cids : array of check ids to be added into group
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function addChecks($subgroupID, $cids)
	{
		global $msg;
		
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		
		if (intval($subgroupID) == 0)
		{
			$msg->addError('MISSING_GID');
			return false;
		}
		
		$subgroupChecksDAO = new SubgroupChecksDAO();
		
		if (is_array($cids))
		{
			foreach ($cids as $cid)
				$subgroupChecksDAO->Create($subgroupID, $cid);
		}
		
		return true;
	}
	
	/**
	* Return subgroup info of the given group id
	* @access  public
	* @param   $subgroupID : subgroup id
	* @return  table row: if success
	*          false : if fail
	* @author  Cindy Qi Li
	*/
	public function getSubgroupByID($subgroupID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."guideline_subgroups 
                 WHERE subgroup_id = ".$subgroupID;

		$rows = $this->execute($sql);
		return $rows[0];
	}
	
	/**
	* Return subgroup info of the given check id and guideline id
	* @access  public
	* @param   $checkID : check id
	*          $guidelineID: guideline id
	* @return  table row: if success
	*          false : if fail
	* @author  Cindy Qi Li
	*/
	public function getSubgroupByCheckIDAndGuidelineID($checkID, $guidelineID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."guideline_subgroups 
                 WHERE subgroup_id in (SELECT subgroup_id 
                                         FROM ".TABLE_PREFIX."subgroup_checks
                                        WHERE check_id=".$checkID.")
                   AND group_id in (SELECT group_id 
                                           FROM ".TABLE_PREFIX."guideline_groups gg
                                          WHERE gg.guideline_id = ".$guidelineID.")";

		return $this->execute($sql);
	}
	
	/**
	* Return array of subgroups info whose name is NOT null, and belong to the given group id
	* @access  public
	* @param   $groupID : group id
	* @return  subgroup id rows : array of subgroup ids, if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function getNamedSubgroupByGroupID($groupID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."guideline_subgroups gs, ".TABLE_PREFIX."language_text l
                 WHERE gs.group_id = ".$groupID."
                   AND gs.name is not NULL
                   AND gs.name = l.term
                   AND l.language_code = '".$_SESSION['lang']."'
                 ORDER BY l.text";

		return $this->execute($sql);
	}
	
	/**
	* Return array of subgroups info whose name is null, and belong to the given group id
	* @access  public
	* @param   $groupID : group id
	* @return  subgroup id rows : array of subgroup ids, if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function getUnnamedSubgroupByGroupID($groupID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."guideline_subgroups
                 WHERE group_id = ".$groupID."
                   AND name is NULL";

		return $this->execute($sql);
	}

	/**
	 * insert/update guideline subgroup term into language_text and update according record in table "guideline_subgroups"
	 * @access  private
	 * @param   $subgroupID
	 *          $term      : term to create/update into 'language_text' table
	 *          $text      : text to create/update into 'language_text' table
	 *          $fieldName : field name in table 'guideline_groups' to update
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	private function updateLang($subgroupID, $term, $text, $fieldName)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
		$langTextDAO = new LanguageTextDAO();
		
		$langs = $langTextDAO->getByTermAndLang($term, $_SESSION['lang']);

		if (is_array($langs))
		{// term already exists. Only need to update modified text
			if ($langs[0]['text'] <> mysql_real_escape_string($text)) $langTextDAO->setText($_SESSION['lang'], '_guideline',$term,$text);
		}
		else
		{
			$langTextDAO->Create($_SESSION['lang'], '_guideline',$term,$text,'');
			
			$sql = "UPDATE ".TABLE_PREFIX."guideline_subgroups SET ".$fieldName."='".$term."' WHERE subgroup_id=".$subgroupID;
			debug($sql);exit;
			$this->execute($sql);
		}
		
		return true;
	}
}
?>