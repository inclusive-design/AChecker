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
* DAO for "checks" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class ChecksDAO extends DAO {

	/**
	* Return all checks' info
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAll()
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks";
		return $this->execute($sql);
	}

	/**
	* Return all open-to-public checks 
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllOpenChecks()
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks WHERE open_to_public=1";
		return $this->execute($sql);
	}

	/**
	* Return check info of given check id
	* @access  public
	* @param   $checkID : check id
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getCheckByID($checkID)
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks WHERE check_id=". $checkID;
		$rows = $this->execute($sql);

	    if (is_array($rows))
	    	return $rows[0];
	    else
	    	return false;
	  }

	/**
	* Return checks for all html elements by given guideline ids
	* @access  public
	* @param   $gid : guideline ID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getChecksByGuidelineID($gid)
	{
		$sql = "select distinct c.*
						from ". TABLE_PREFIX ."guidelines g, 
								". TABLE_PREFIX ."guideline_groups gg, 
								". TABLE_PREFIX ."guideline_subgroups gs, 
								". TABLE_PREFIX ."subgroup_checks gc,
								". TABLE_PREFIX ."checks c
						where g.guideline_id = ".$gid."
							and g.guideline_id = gg.guideline_id
							and gg.group_id = gs.group_id
							and gs.subgroup_id = gc.subgroup_id
							and gc.check_id = c.check_id
						order by c.html_tag";

    	return $this->execute($sql);
  	}

  /**
	* Return checks for all html elements by given guideline ids
	* @access  public
	* @param   $gids : guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getChecksForAllByGuidelineIDs($gids)
	{
		$sql = "select distinct gc.check_id, c.html_tag
						from ". TABLE_PREFIX ."guidelines g, 
								". TABLE_PREFIX ."guideline_groups gg, 
								". TABLE_PREFIX ."guideline_subgroups gs, 
								". TABLE_PREFIX ."subgroup_checks gc,
								". TABLE_PREFIX ."checks c
						where g.guideline_id in (".$gids.")
							and g.guideline_id = gg.guideline_id
							and gg.group_id = gs.group_id
							and gs.subgroup_id = gc.subgroup_id
							and gc.check_id = c.check_id
							and c.html_tag = 'all elements'
						order by c.html_tag";

    return $this->execute($sql);
  }

	/**
	* Return checks NOT for all html elements by given guideline ids
	* @access  public
	* @param   $gids : guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getChecksNotForAllByGuidelineIDs($gids)
	{
			$sql = "select distinct gc.check_id, c.html_tag
							from ". TABLE_PREFIX ."guidelines g, 
									". TABLE_PREFIX ."guideline_groups gg, 
									". TABLE_PREFIX ."guideline_subgroups gs, 
									". TABLE_PREFIX ."subgroup_checks gc,
									". TABLE_PREFIX ."checks c
							where g.guideline_id in (".$gids.")
								and g.guideline_id = gg.guideline_id
								and gg.group_id = gs.group_id
								and gs.subgroup_id = gc.subgroup_id
								and gc.check_id = c.check_id
								and c.html_tag <> 'all elements'
							order by c.html_tag";

    return $this->execute($sql);
  }

	/**
	* Return prerequisite checks by given guideline ids
	* @access  public
	* @param   $gids : guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getPreChecksByGuidelineIDs($gids)
	{
		$sql = "select distinct c.check_id, cp.prerequisite_check_id
					from ". TABLE_PREFIX ."guidelines g, 
					     ". TABLE_PREFIX ."guideline_groups gg, 
					     ". TABLE_PREFIX ."guideline_subgroups gs, 
					     ". TABLE_PREFIX ."subgroup_checks gc,
					     ". TABLE_PREFIX ."checks c,
					     ". TABLE_PREFIX ."check_prerequisites cp
					where g.guideline_id in (".$gids.")
					  and g.guideline_id = gg.guideline_id
					  and gg.group_id = gs.group_id
					  and gs.subgroup_id = gc.subgroup_id
					  and gc.check_id = c.check_id
					  and c.check_id = cp.check_id
					order by c.check_id, cp.prerequisite_check_id";

    return $this->execute($sql);
  }

	/**
	* Return next checks by given guideline ids
	* @access  public
	* @param   $gids : guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getNextChecksByGuidelineIDs($gids)
	{
		$sql = "select distinct c.check_id, tp.next_check_id
							from ". TABLE_PREFIX ."guidelines g, 
							     ". TABLE_PREFIX ."guideline_groups gg, 
							     ". TABLE_PREFIX ."guideline_subgroups gs, 
							     ". TABLE_PREFIX ."subgroup_checks gc,
							     ". TABLE_PREFIX ."checks c,
							     ". TABLE_PREFIX ."test_pass tp
							where g.guideline_id in (".$gids.")
							  and g.guideline_id = gg.guideline_id
							  and gg.group_id = gs.group_id
							  and gs.subgroup_id = gc.subgroup_id
							  and gc.check_id = c.check_id
							  and c.check_id = tp.check_id
							order by c.check_id, tp.next_check_id";

    return $this->execute($sql);
  }

}
?>