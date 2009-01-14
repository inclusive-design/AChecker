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
* DAO for "language_text" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class LanguageTextDAO extends DAO {

	/**
	* Return message text of given term and language
	* @access  public
	* @param   term : language term
	*          lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getMsgByTermAndLang($term, $lang)
	{
		$sql	= 'SELECT * FROM '.TABLE_PREFIX.'language_text 
						WHERE term="' . $term . '" 
						AND variable="_msgs" 
						AND language_code="'.$lang.'" 
						ORDER BY variable';

    return $this->execute($sql);
  }

	/**
	* Return text of given term and language
	* @access  public
	* @param   term : language term
	*          lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getByTermAndLang($term, $lang)
	{
		$sql	= 'SELECT * FROM '.TABLE_PREFIX.'language_text 
						WHERE term="' . $term . '" 
						AND language_code="'.$lang.'" 
						ORDER BY variable';

    return $this->execute($sql);
  }

	/**
	* Return all template info of given language
	* @access  public
	* @param   lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllTemplateByLang($lang)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."language_text 
						WHERE language_code='".$_SESSION['lang']."' 
						AND variable='_template' 
						ORDER BY variable ASC";

    return $this->execute($sql);
  }
}
?>