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
* DAO for "guidelines" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class GuidelinesDAO extends DAO {

	/**
	* Create a new guideline
	* @access  public
	* @param   $userID : user id
	*          $title
	*          $abbr
	*          $long_name
	*          $published_date
	*          $earlid
	*          $preamble
	*          $status
	*          $open_to_public
	* @return  guidelineID : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Create($userID, $title, $abbr, $long_name, $published_date, $earlid, $preamble, $status, $open_to_public)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."guidelines
				(`user_id`, `title`, `abbr`, `published_date`,  
				 `earlid`, `preamble`, `status`, `open_to_public`) 
				VALUES
				(".$userID.",'".$title."', '".$abbr."', '".$published_date."',
				 '".$earlid."','".$preamble."', ".$status.",".$open_to_public.")";

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			$guidelineID = mysql_insert_id();

			if ($long_name <> '')
			{
				$term = LANG_PREFIX_GUIDELINES_LONG_NAME.$guidelineID;

				require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
				$langTextDAO = new LanguageTextDAO();
				
				if ($langTextDAO->Create($_SESSION['lang'], '_guideline',$term,$long_name,''))
				{
					$sql = "UPDATE ".TABLE_PREFIX."guidelines SET long_name='".$term."' WHERE guideline_id=".$guidelineID;
					$this->execute($sql);
				}
			}
			return $guidelineID;
		}
	}
	
	/**
	* Update an existing guideline
	* @access  public
	* @param   $guidelineID
	*          $userID : user id
	*          $title
	*          $abbr
	*          $long_name
	*          $published_date
	*          $earlid
	*          $preamble
	*          $status
	*          $open_to_public
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function update($guidelineID, $userID, $title, $abbr, $long_name, $published_date, $earlid, $preamble, $status, $open_to_public)
	{
		$sql = "UPDATE ".TABLE_PREFIX."guidelines
				   SET `user_id`=".$userID.", 
				       `title` = '".$title."', 
				       `abbr` = '".$abbr."', 
				       `published_date` = '".$published_date."',  
				       `earlid` = '".$earlid."', 
				       `preamble` = '".$preamble."', 
				       `status` = ".$status.", 
				       `open_to_public` = ".$open_to_public." 
				 WHERE guideline_id = ".$guidelineID;

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			// find language term to update	
			$rows = $this->getGuidelineByIDs($guidelineID);
			$term = $rows[0]['term'];
			
			require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
			$langTextDAO = new LanguageTextDAO();
			
			if ($langTextDAO->setText($_SESSION['lang'],'_guideline',$term,$long_name))
				return true;
			else
				return false;
		}
	}
	
	/**
	* Add checks into guideline
	* @access  public
	* @param   $guidelineID : guideline id
	*          $cids : array of check ids to be added into guideline
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function addChecks($guidelineID, $cids)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		
		$guidelineGroupsDAO = new GuidelineGroupsDAO();
		$groups = $guidelineGroupsDAO->getGroupByGuidelineID($guidelineID);
		
		if (is_array($groups))
			$group_id = $groups[0]['group_id'];
		else
			$group_id = $guidelineGroupsDAO->Create($guidelineID, '','','');
		
		if ($group_id)
		{
			$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
			$subgroups = $guidelineSubgroupsDAO->getSubgroupByGuidelineID($group_id);
			
			if (is_array($subgroups))
				$subgroup_id = $subgroups[0]['subgroup_id'];
			else
				$subgroup_id = $guidelineSubgroupsDAO->Create($group_id, '','');
			
			if ($subgroup_id)
			{
				$subgroupChecksDAO = new SubgroupChecksDAO();
				
				if (is_array($cids))
				{
					foreach ($cids as $cid)
						$subgroupChecksDAO->Create($subgroup_id, $cid);
				}
			}
			else return false;
		}
		else return false;
		
		return true;
	}
	
	/**
	* Delete guideline by ID
	* @access  public
	* @param   $guidelineID : guideline id
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function Delete($guidelineID)
	{
		if ($this->deleteAllChecks($guidelineID))
		{
			// delete language for long name
			$sql = "DELETE FROM ".TABLE_PREFIX."language_text 
			         WHERE variable='_guideline' 
			           AND term=(SELECT long_name 
			                       FROM ".TABLE_PREFIX."guidelines
			                      WHERE guideline_id=".$guidelineID.")";
			$this->execute($sql);
			
			$sql = "DELETE FROM ".TABLE_PREFIX."guidelines WHERE guideline_id=".$guidelineID;
			
			return $this->execute($sql);
		}
		else return false;
	}
	
	/**
	* Delete all checks from guideline
	* @access  public
	* @param   $guidelineID : guideline id
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function deleteAllChecks($guidelineID)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		
		$subgroupChecksDAO = new SubgroupChecksDAO();
		
		if ($subgroupChecksDAO->Delete($guidelineID))
		{
			$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
			if ($guidelineSubgroupsDAO->Delete($guidelineID))
			{
				$guidelineGroupsDAO = new GuidelineGroupsDAO();
				if ($guidelineGroupsDAO->Delete($guidelineID))
					return true;
				else
					return false;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	* Delete given check, identified by check ID, from given guideline
	* @access  public
	* @param   $guidelineID : guideline id
	*          $checkID : check ID to delete
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function deleteCheckByID($guidelineID, $checkID)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."subgroup_checks
		         WHERE subgroup_id in (SELECT distinct subgroup_id 
		                                 FROM ".TABLE_PREFIX."guidelines g, "
		                                       .TABLE_PREFIX."guideline_groups gg, "
		                                       .TABLE_PREFIX."guideline_subgroups gs
		                                 WHERE g.guideline_id=".$guidelineID."
		                                   AND g.guideline_id = gg.guideline_id
		                                   AND gg.group_id = gs.group_id)
		           AND check_id = ".$checkID;
		
		return $this->execute($sql);
	}
	
	/**
	* Return guideline info by given guideline id
	* @access  public
	* @param   $guidelineIDs : a string of all guideline ids, for example: 1, 2, 3
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getGuidelineByIDs($guidelineIDs)
	{
		$sql = "select *
						from ". TABLE_PREFIX ."guidelines
						where guideline_id in (" . $guidelineIDs . ")
						order by title";

    
		return $this->execute($sql);
  	}

	/**
	* Return guideline info by given user id
	* @access  public
	* @param   $userID : user id
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getGuidelineByUserID($userID)
	{
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where user_id = " . $userID . "
				order by title";

	    return $this->execute($sql);
  	}

	/**
	* Return open-to-public guideline info by given user id
	* @access  public
	* @param   $userID : user id
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getEnabledGuidelinesByUserID($userID)
	{
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where user_id = " . $userID . "
				and status = ".AC_STATUS_ENABLED."
				order by title";

	    return $this->execute($sql);
  	}

	/**
	* Return rows by guideline title
	* @access  public
	* @param   $title
	*          $ignoreCase: 1: ignore case; 0: don't ignore; set to 1 by default
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getEnabledGuidelinesByTitle($title, $ignoreCase=1)
	{
		if ($ignoreCase) $sql_title = "lower(title) = '".strtolower($title)."'";
		else $sql_title = "title = '".$title."'";
		
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where ".$sql_title."
				order by title";

	    return $this->execute($sql);
  	}

  	/**
	* Return open-to-public guideline info by given user id
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getOpenGuidelines()
	{
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where open_to_public = 1
				order by title";

	    return $this->execute($sql);
  	}

  	/**
	* Return customized guidelines
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getCustomizedGuidelines()
	{
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where user_id <> 0
				order by title";

    	return $this->execute($sql);
  	}

	/**
	* Return standard guidelines
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getStandardGuidelines()
	{
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where user_id = 0
				order by title";

    	return $this->execute($sql);
  	}

  	/**
	* set guideline status
	* @access  public
	* @param   $guidelineID : guideline ID
	*          $status : guideline status
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function setStatus($guidelineID, $status)
	{
		$sql = "update ". TABLE_PREFIX ."guidelines
				set status = " . $status . "
				where guideline_id=".$guidelineID;

    return $this->execute($sql);
  }

	/**
	* set open_to_public
	* @access  public
	* @param   $guidelineID : guideline ID
	*          $open_to_public : open to public flag
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function setOpenToPublicFlag($guidelineID, $open_to_public)
	{
		$sql = "update ". TABLE_PREFIX ."guidelines
				set open_to_public = " . $open_to_public . "
				where guideline_id=".$guidelineID;

    return $this->execute($sql);
  }
}
?>