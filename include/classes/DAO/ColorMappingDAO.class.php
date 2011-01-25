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
* DAO for "color_mapping" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class ColorMappingDAO extends DAO {

	/**
	* Return lang code info of the given 2 letters code
	* @access  public
	* @param   $code : 2 letters code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function GetByColorName($colorName)
	{
		global $addslashes;
		
		$sql = "SELECT * FROM ". TABLE_PREFIX ."color_mapping WHERE color_name='".$addslashes($colorName)."'";
		return $this->execute($sql);
	}

}
?>