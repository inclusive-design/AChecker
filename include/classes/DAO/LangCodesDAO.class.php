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
* DAO for "lang_codes" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class LangCodesDAO extends DAO {

	/**
	* Return lang code info of the given 2 letters code
	* @access  public
	* @param   $code : 2 letters code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function GetLangCodeBy2LetterCode($code)
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."lang_codes 
					WHERE code_2letters = '".$code ."'";
		
		return $this->execute($sql);
	}

	/**
	* Return lang code info of the given 3 letters code
	* @access  public
	* @param   $code : 3 letters code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function GetLangCodeBy3LetterCode($code)
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."lang_codes 
					WHERE code_3letters = '".$code ."'";
		
		return $this->execute($sql);
	}

}
?>