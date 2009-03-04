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
* Utility functions 
* @access	public
* @author	Cindy Qi Li
*/

if (!defined('AC_INCLUDE_PATH')) exit;

class Utility {

	/**
	* return a unique session id based on timestamp
	* @access  public
	* @param   none
	* @return  language code
	* @author  Cindy Qi Li
	*/
	public static function getSessionID() 
	{
		return sha1(mt_rand() . microtime(TRUE));
	}

	 /**
	 * check if the given $uri is valid.
	 * @access  public
	 * @param   string $uri  The uri address
	 * @return  true: if valid; false: if invalid
	 * @author  Cindy Qi Li
	 */
	function isURIValid($uri)
	{
		$connection = @file_get_contents($uri);
		
		if (!$connection) 
			return false;
		else
			return true;
	}

}
?>