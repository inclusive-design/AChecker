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
	* @return  guideline_id : if successful
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
			$guideline_id = mysql_insert_id();
			
			if ($long_name <> '')
			{
				$term = LANG_PREFIX_GUIDELINES_LONG_NAME.$guideline_id;
				
				require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
				$langTextDAO = new LanguageTextDAO();
				
				if ($langTextDAO->Create('en', '_template',$term,$long_name,''))
				{
					$sql = "UPDATE ".TABLE_PREFIX."guidelines SET long_name='".$term."' WHERE guideline_id=".$guideline_id;
					$this->execute($sql);
				}
			}
			return $guideline_id;
		}
	}
	
	/**
	* Add checks into guideline
	* @access  public
	* @param   $gid : guideline id
	*          $cids : array of check ids to be added into guideline
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function addChecks($gid, $cids)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		
		$guidelineGroupsDAO = new GuidelineGroupsDAO();
		$groups = $guidelineGroupsDAO->getGroupByGuidelineID($gid);
		
		if (is_array($groups))
			$group_id = $groups[0]['group_id'];
		else
			$group_id = $guidelineGroupsDAO->Create($gid, '','','');
		
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
				
				foreach ($cids as $cid)
					$subgroupChecksDAO->Create($subgroup_id, $cid);
			}
			else return false;
		}
		else return false;
		
		return true;
	}
	
	/**
	* Delete guideline by ID
	* @access  public
	* @param   $guideline_id : guideline id
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function Delete($guideline_id)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."guidelines WHERE guideline_id=".$guideline_id;
		
		return $this->execute($sql);
	}
	
	/**
	* Delete all checks from guideline
	* @access  public
	* @param   $gid : guideline id
	* @return  true : if successful
	*          false : if unsuccessful
	* @author  Cindy Qi Li
	*/
	public function deleteAllChecks($gid)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		
		$subgroupChecksDAO = new SubgroupChecksDAO();
		
		if ($subgroupChecksDAO->Delete($gid))
		{
			$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
			if ($guidelineSubgroupsDAO->Delete($gid))
			{
				$guidelineGroupsDAO = new GuidelineGroupsDAO();
				if ($guidelineGroupsDAO->Delete($gid))
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
	* Return guideline info by given guideline id
	* @access  public
	* @param   $gids : a string of all guideline ids, for example: 1, 2, 3
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getGuidelineByIDs($gids)
	{
		$sql = "select *
						from ". TABLE_PREFIX ."guidelines
						where guideline_id in (" . $gids . ")
						order by title";

    
		$rows = $this->execute($sql);
		
		if (is_array($rows))
			return $rows[0];
		else
			return false;
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
	* @param   $gid : guideline ID
	*          $status : guideline status
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function setStatus($gid, $status)
	{
		$sql = "update ". TABLE_PREFIX ."guidelines
				set status = " . $status . "
				where guideline_id=".$gid;

    return $this->execute($sql);
  }

	/**
	* set open_to_public
	* @access  public
	* @param   $gid : guideline ID
	*          $open_to_public : open to public flag
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function setOpenToPublicFlag($gid, $open_to_public)
	{
		$sql = "update ". TABLE_PREFIX ."guidelines
				set open_to_public = " . $open_to_public . "
				where guideline_id=".$gid;

    return $this->execute($sql);
  }
}
?>