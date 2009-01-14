<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
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
	* Return all guidelines
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllGuidelines()
	{
		$sql = "select guideline_id, title
						from ". TABLE_PREFIX ."guidelines
						order by title";

    return $this->execute($sql);
  }

	/**
	* Return guideline info by given guideline id
	* @access  public
	* @param   $gids : a string of all guideline ids, for example: 1, 2, 3
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getGuidelineByIDs($gids)
	{
		$sql = "select title
						from ". TABLE_PREFIX ."guidelines
						where guideline_id in (" . $gids . ")
						order by title";

    return $this->execute($sql);
  }
}
?>