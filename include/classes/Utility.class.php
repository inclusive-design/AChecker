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
	public static function isURIValid($uri)
	{
		$connection = @file_get_contents($uri);
		
		if (!$connection) 
			return false;
		else
			return true;
	}

	/**
	* convert text new lines to html tag <br/>
	* @access  public
	* @return  converted string
	* @author  Cindy Qi Li
	*/
	public static function convertHTMLNewLine($str)
	{
		$new_line_array = array("\n", "\r", "\n\r", "\r\n");

		$found_match = false;
		
		if (strlen(trim($str))==0) return "";
		
		foreach ($new_line_array as $new_line)
			if (preg_match('/'.preg_quote($new_line).'/', $str) > 0)
			{
				$search_new_line = $new_line;
				$found_match = true;
			}
		 
		if ($found_match)
			return preg_replace('/'. preg_quote($search_new_line) .'/', "<br />", $str);
		else
			return $str;
	}

	/**
	* check syntax of the code that is used in eval()
	* @access  public
	* @param   $code
	* @return  true: correct syntax; 
	*          false: wrong syntax
	* @author  Cindy Qi Li
	*/
	private function check_eval_code_syntax($code) 
	{
    	return @eval('return true;' . $code);
	}
}
?>