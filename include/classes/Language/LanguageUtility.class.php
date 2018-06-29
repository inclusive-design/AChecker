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
* Utility functions for language 
* @access	public
* @author	Cindy Qi Li
*/

if (!defined('AC_INCLUDE_PATH')) exit;

class LanguageUtility {

	/**
	* return language code from given AChecker language code
	* @access  public
	* @param   $code
	* @return  language code
	* @author  Cindy Qi Li
	*/
	public static function getParentCode($code = '') {
		$code = !isset($code) ? self::code:$code;
		$peices = explode(AC_LANGUAGE_LOCALE_SEP, $code, 2);
		return $peices[0];
	}

	/**
	* return charset from given AChecker language code
	* @access  public
	* @param   $code
	* @return  charset
	* @author  Cindy Qi Li
	*/
	public static function getLocale($code = '') {
		$code = !isset($code) ? self::code:$code;
		$peices = explode(AC_LANGUAGE_LOCALE_SEP, $code, 2);
		return $peices[1];
	}
}
?>