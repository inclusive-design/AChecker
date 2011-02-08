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
* DAO for "guidelines" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');

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
		global $addslashes;
		
		$userID = intval($userID);
		$title = $addslashes(trim($title));	
		$abbr = $addslashes(trim($abbr));	
		$long_name = trim($long_name);   // $addslashes is not necessary as it's called in LanguageTextDAO->Create()
		$earlid = $addslashes(trim($earlid));
		$preamble = $addslashes(trim($preamble));
		if ($published_date == '' || is_null($published_date)) $published_date = '0000-00-00';
		
		if (!$this->isFieldsValid($title, $abbr, true)) return false;
		
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
		global $addslashes;
		
		$guidelineID = intval($guidelineID);
		$userID = intval($userID);
		$title = $addslashes(trim($title));	
		$abbr = $addslashes(trim($abbr));	
		$long_name = trim($long_name);   // $addslashes is not necessary as it's called in LanguageTextDAO->setText()
		$earlid = $addslashes(trim($earlid));
		$preamble = $addslashes(trim($preamble));
		
		if (!$this->isFieldsValid($title, $abbr, false, $guidelineID)) return false;
		
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
			$term = $rows[0]['long_name'];
			
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
		
		$guidelineID = intval($guidelineID);
		if ($guidelineID == 0)
		{
			$msg->addError('MISSING_GID');
			return false;
		}
		
		$guidelineGroupsDAO = new GuidelineGroupsDAO();
		$groups = $guidelineGroupsDAO->getUnnamedGroupsByGuidelineID($guidelineID);
		
		if (is_array($groups))
			$group_id = $groups[0]['group_id'];
		else
			$group_id = $guidelineGroupsDAO->Create($guidelineID, '','','');
		
		if ($group_id)
		{
			$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
			$subgroups = $guidelineSubgroupsDAO->getUnnamedSubgroupByGroupID($group_id);
			
			if (is_array($subgroups))
				$subgroup_id = $subgroups[0]['subgroup_id'];
			else
				$subgroup_id = $guidelineSubgroupsDAO->Create($group_id, '','');
			
			if ($subgroup_id)
			{
				$subgroupChecksDAO = new SubgroupChecksDAO();
				
				if (is_array($cids))
				{
					foreach ($cids as $cid) {
						$cid = intval($cid);
						
						if ($cid > 0) {
							$subgroupChecksDAO->Create($subgroup_id, $cid);
						}
					}
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
		require_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
		
		$guidelineID = intval($guidelineID);
		
		// Delete all subgroups
		$guidelineGroupsDAO = new GuidelineGroupsDAO();
		$sql = "SELECT group_id FROM ".TABLE_PREFIX."guideline_groups
		         WHERE guideline_id = ".$guidelineID;
		$rows = $this->execute($sql);
		
		if (is_array($rows))
		{
			foreach ($rows as $row)
				$guidelineGroupsDAO->Delete($row['group_id']);
		}
		
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
	
	/**
	* Return guideline info by given guideline id
	* @access  public
	* @param   $guidelineIDs : an array of guideline ids or one guide id
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getGuidelineByIDs($guidelineIDs)
	{
		// sanitize array gids
		if (is_array($guidelineIDs)) {
			$sanitized_gids = Utility::sanitizeIntArray($guidelineIDs);
			$sanitized_gids_str = implode(",", $sanitized_gids);
		} else {
			$sanitized_gids_str = intval($guidelineIDs);
		}
		
		$sql = "select * from ". TABLE_PREFIX ."guidelines
						where guideline_id in (" . $sanitized_gids_str . ")
						order by title";

		return $this->execute($sql);
  	}

	/**
	* Return guideline info by given guideline abbreviation
	* @access  public
	* @param   $abbr : guideline abbreviation
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getGuidelineByAbbr($abbr)
	{
		global $addslashes;
		
		$abbr = $addslashes($abbr);
		
		$sql = "select * from ". TABLE_PREFIX ."guidelines
						where abbr = '" . $abbr . "'";
    
		return $this->execute($sql);
  	}

  	/**
	* Return guideline info by given user id
	* @access  public
	* @param   $userID : user id
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getGuidelineByUserIDs($userIDs)
	{
		include_once(AC_INCLUDE_PATH.'classes/Utility.class.php');
		$userIDs = Utility::sanitizeIntArray($userIDs);
		
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where user_id in (" . implode(",", $userIDs) . ")
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
	public function getClosedEnabledGuidelinesByUserID($userID)
	{
		$userID = intval($userID);
		
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where user_id = " . $userID . "
				and status = ".AC_STATUS_ENABLED."
				and open_to_public = 0
				order by title";

	    return $this->execute($sql);
  	}

	/**
	* Return open-to-public guideline info by given check id
	* @access  public
	* @param   $checkID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	public function getEnabledGuidelinesByCheckID($checkID)
	{
		$checkID = intval($checkID);
		
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where guideline_id in 
				     (SELECT distinct gg.guideline_id 
				        FROM ". TABLE_PREFIX ."guideline_groups gg, 
								". TABLE_PREFIX ."guideline_subgroups gs, 
								". TABLE_PREFIX ."subgroup_checks gc,
								". TABLE_PREFIX ."checks c
				       WHERE gg.group_id = gs.group_id
						 AND gs.subgroup_id = gc.subgroup_id
						 AND gc.check_id = ".$checkID.")
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
	public function getEnabledGuidelinesByAbbr($abbr, $ignoreCase=1)
	{
		global $addslashes;
		
		$abbr = $addslashes($abbr);
		
		if ($ignoreCase) $sql_abbr = "lower(abbr) = '".strtolower($abbr)."'";
		else $sql_abbr = "abbr = '".$abbr."'";
		
		$sql = "select *
				from ". TABLE_PREFIX ."guidelines
				where ".$sql_abbr."
				order by abbr";

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
		$guidelineID = intval($guidelineID);
		$status = intval($status);
		
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
		$guidelineID = intval($guidelineID);
		$open_to_public = intval($open_to_public);
		
		$sql = "update ". TABLE_PREFIX ."guidelines
				set open_to_public = " . $open_to_public . "
				where guideline_id=".$guidelineID;

    return $this->execute($sql);
  }

	/**
	 * Validate fields preparing for insert and update
	 * @access  private
	 * @param   $title  
	 *          $abbr
	 *          $create_new: flag to indicate if this is creating new record or update.
	 *                       true is to create new record, false is update record.
	 *                       if update record, only check abbr uniqueness when abbr is modified.
	 *          $guidelineID: must be given at updating record, when $create_new == false
	 * @return  true    if all fields are valid
	 *          false   if any field is not valid
	 * @author  Cindy Qi Li
	 */
	private function isFieldsValid($title, $abbr, $create_new, $guidelineID = 0)
	{
		global $msg;
		
		// check missing fields
		$missing_fields = array();

		if ($title == '')
		{
			$missing_fields[] = _AC('title');
		}
		if ($abbr == '')
		{
			$missing_fields[] = _AC('abbr');
		}
		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}
		
		if (!$create_new)
		{
			$current_grow = $this->getGuidelineByIDs($guidelineID);
		}
		
		if ($create_new || (!$create_new && $current_grow[0]['abbr'] <> $abbr))
		{
			// abbr must be unique
			$sql = "SELECT * FROM ".TABLE_PREFIX."guidelines WHERE abbr='".$abbr."'";
	
			if (is_array($this->execute($sql)))
			{
				$msg->addError('ABBR_EXISTS');
			}
		}
			
		if (!$msg->containsErrors())
			return true;
		else
			return false;
	}
	
	// Simo: aggiunto per vedere quali check hanno il tag img, per il controllo visivo delle immagini
	// Restituisce una stringa con tutti gli id dei check, separati da virgola, che riguardano la guideline e l'elemento specificato
	public function getCheckByTagAndGuideline($tag, $guideline)
	{
		global $addslashes;
		
		$tag = $addslashes($tag);
		$guideline = intval($guideline);
		
		$sql = "select distinct c.check_id,c.html_tag
					from ". TABLE_PREFIX ."guidelines g, 
					     ". TABLE_PREFIX ."guideline_groups gg, 
					     ". TABLE_PREFIX ."guideline_subgroups gs, 
					     ". TABLE_PREFIX ."subgroup_checks gc,
					     ". TABLE_PREFIX ."checks c
					where g.guideline_id = '". $guideline ."'
					  and g.guideline_id = gg.guideline_id
					  and gg.group_id = gs.group_id
					  and gs.subgroup_id = gc.subgroup_id
					  and gc.check_id = c.check_id
					  and c.html_tag = '" . $tag ."'
					order by c.html_tag";
		$check = ",";
		
		return $this->execute($sql);
	}	
}
?>