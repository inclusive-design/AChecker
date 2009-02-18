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
* DAO for "config" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class LanguagesDAO extends DAO {

	/**
	* Return all languages
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAll()
	{
	    $sql = "SELECT * FROM ".TABLE_PREFIX."languages ORDER BY native_name";
	    return $this->execute($sql);
	}

	/**
	* Return language with given language code
	* @access  public
	* @param   $langCode
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getByLangCode($langCode)
	{
	    $sql = "SELECT * FROM ".TABLE_PREFIX."languages 
	             WHERE language_code = '".$langCode."'
	             ORDER BY native_name";
	    return $this->execute($sql);
	}

	/**
	* Return all languages except the ones with language code in the given string 
	* @access  public
	* @param   $langCode : one language codes, for example: en
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllExceptLangCode($langCode)
	{
		if (trim($langCode) == '')
			return $this->getAll();
		else
		{
			$sql = "SELECT * FROM ".TABLE_PREFIX."languages 
			         WHERE language_code <> '".$langCode."' 
			         ORDER BY native_name";
		    return $this->execute($sql);
		}
	}
}
?>