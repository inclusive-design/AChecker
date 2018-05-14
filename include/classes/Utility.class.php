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
	 * Return the valid format of given $uri. Otherwise, return FALSE
	 * Return $uri itself if it has valid content, 
	 * otherwise, return the first listed uri that has valid content: 
	 * "http://".$uri
	 * "https://".$uri
	 * "http://www.".$uri
	 * "https://www.".$uri
	 * If none of above has valid content, return FALSE
	 * @access  public
	 * @param   string $uri  The uri address
	 * @return  true: if valid; false: if invalid
	 * @author  Cindy Qi Li
	 */
	public static function getValidURI($uri)
	{
		$uri_prefixes = array('http://', 'https://', 'http://www.', 'https://www.');
		$already_a_uri = false;
		
		$uri = trim($uri);
		
		// Check whether the URI prefixes are already in place
		foreach($uri_prefixes as $prefix)
		{
			if (substr($uri, 0, strlen($prefix)) == $prefix)
			{
				$already_a_uri = true;
				break;
			}
		}
		if (!$already_a_uri)
		{
			// try adding uri prefixes in front of given uri
			foreach($uri_prefixes as $prefix)
			{
				if (substr($uri, 0, strlen($prefix)) <> $prefix)
				{
					$prefixed_uri = $prefix.$uri;
					$connection = @file_get_contents($prefixed_uri);
					
					if (!$connection)
					{
						continue;
					}
					else
					{
						return $prefixed_uri;
					}
				}
			}
		}
		else
		{
			$connection = @file_get_contents($uri);
			
			if ($connection) return $uri;
			else return $uri;
		}
		
		// no matching valid uri
		return false;
	}

	/**
	* convert text new lines to html tag <br/>
	* @access  public
	* @param   string
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
	* Return array of seals to display
	* Some guidelines are in the same group. This is defined in guidelines.subset. 
	* The format of guidelines.subset is [group_name]-[priority].
	* When the guidelines in the same group are validated, only the seal for the guideline
	* with the highest [priority] number is displayed.
	* @access  public
	* @param   $guidelines : array of guideline table rows
	* @return  converted string
	* @author  Cindy Qi Li
	*/
	public static function getSeals($guidelines)
	{
		foreach ($guidelines as $guideline)
		{
			if ($guideline['subset'] == '0')
			{
				$seals[] = array('title' => $guideline['title'],
				                 'guideline' => $guideline['abbr'], 
				                 'seal_icon_name' => $guideline['seal_icon_name']);
			}
			else
			{
				list($group, $priority) = explode('-', $guideline['subset']);
				
				if (!isset($highest_priority[$group]['priority']) || $highest_priority[$group]['priority'] < $priority)
				{
					$highest_priority[$group]['priority'] = $priority;
					$highest_priority[$group]['guideline'] = $guideline;
				}
			}// end of outer if
		} // end of foreach
		
		if (is_array($highest_priority))
		{
			foreach ($highest_priority as $group => $guideline_to_display)
				$seals[] = array('title' => $guideline_to_display['guideline']['title'], 
						         'guideline' => $guideline_to_display['guideline']['abbr'], 
				                 'seal_icon_name' => $guideline_to_display['guideline']['seal_icon_name']);
		}
		
		return $seals;
	}
	
	/**
	* Check if the free memory is big enough to process the given file size
	* @access  public
	* @param   $filesize : file size
	* @return  true if enough, otherwise, return false
	* @author  Cindy Qi Li
	*/
	public static function hasEnoughMemory($filesize)
	{
		$memory_limit = ini_get( 'memory_limit' );
		if ($memory_limit != '')
		{
		    switch ( $memory_limit{strlen( $memory_limit ) - 1} )
		    {
		        case 'G':
		            $memory_limit *= 1024;
		        case 'M':
		            $memory_limit *= 1024;
		        case 'K':
		            $memory_limit *= 1024;
		    }
		}
		else
			return true;
		
		$used_memory = memory_get_usage();
		
		if (($filesize * 160) > ($memory_limit - $used_memory))
			return false;
		else
			return true;
	}

	/**
	* Sort $inArray in the order of the number presented in the field with name $fieldName
	* @access  public
	* @param   $inArray : input array
	*          $fieldName : the name of the field to sort by
	* @return  sorted array
	* @author  Cindy Qi Li
	*/
	public static function sortArrayByNumInField($inArray, $fieldName)
	{
		if (is_array($inArray))
		{
			foreach ($inArray as $num => $element)
			{
				preg_match('/[^\d]*(\d*(\.)*(\d)*(\.)*(\d)*)[^\d]*/', $element[$fieldName], $matches);
				if ($matches[1] <> '')
				{
					$outArray[$matches[1]] = $element;
				}
				else
					$outArray[$num] = $element;
			}
			ksort($outArray);
			return $outArray;
		}
		else
			return $inArray;
	}

	/**
	* This function deletes $dir recrusively without deleting $dir itself.
	* @access  public
	* @param   string $charsets_array	The name of the directory where all files and folders under needs to be deleted
	* @author  Cindy Qi Li
	*/
	public static function clearDir($dir) {
		if(!$opendir = @opendir($dir)) {
			return false;
		}
		
		while(($readdir=readdir($opendir)) !== false) {
			if (($readdir !== '..') && ($readdir !== '.')) {
				$readdir = trim($readdir);
	
				clearstatcache(); /* especially needed for Windows machines: */
	
				if (is_file($dir.'/'.$readdir)) {
					if(!@unlink($dir.'/'.$readdir)) {
						return false;
					}
				} else if (is_dir($dir.'/'.$readdir)) {
					/* calls lib function to clear subdirectories recrusively */
					if(!Utility::clrDir($dir.'/'.$readdir)) {
						return false;
					}
				}
			}
		} /* end while */
	
		@closedir($opendir);
		
		return true;
	}

	/**
	* Enables deletion of directory if not empty
	* @access  public
	* @param   string $dir		the directory to delete
	* @return  boolean			whether the deletion was successful
	* @author  Joel Kronenberg
	*/
	public static function clrDir($dir) {
		if(!$opendir = @opendir($dir)) {
			return false;
		}
		
		while(($readdir=readdir($opendir)) !== false) {
			if (($readdir !== '..') && ($readdir !== '.')) {
				$readdir = trim($readdir);
	
				clearstatcache(); /* especially needed for Windows machines: */
	
				if (is_file($dir.'/'.$readdir)) {
					if(!@unlink($dir.'/'.$readdir)) {
						return false;
					}
				} else if (is_dir($dir.'/'.$readdir)) {
					/* calls itself to clear subdirectories */
					if(!Utility::clrDir($dir.'/'.$readdir)) {
						return false;
					}
				}
			}
		} /* end while */
	
		@closedir($opendir);
		
		if(!@rmdir($dir)) {
			return false;
		}
		return true;
	}

	/**
	 * This function accepts an array that is supposed to only have integer values.
	 * The function returns a sanitized array by ensuring all the array values are integers.
	 * To pervent the SQL injection. 
	 * @access  public
	 * @param   $int_array : an array
	 * @return  $sanitized_int_array : an array that all the values are sanitized to integer
	 * @author  Cindy Qi Li
	 */
	public static function sanitizeIntArray($int_array) {
		if (!is_array($int_array)) return false;
		
		$sanitized_array = array();
		foreach ($int_array as $i => $value) {
			$sanitized_array[$i] = intval($value);
		}
		return $sanitized_array;
	}
	
	/**
	 * Return http fail status & message. Used to return error message on ajax call. 
	 * @access  public
	 * @param   $errString: error message
	 * @author  Cindy Qi Li
	 */
	public static function returnError($errString)
	{
	    header("HTTP/1.0 400 Bad Request");
	    header("Status: 400");
	    echo $errString;
	}
	
	/**
	 * Return http success status & message. Used to return success message on ajax call. 
	 * @access  public
	 * @param   $errString: error message
	 * @author  Cindy Qi Li
	 */
	public static function returnSuccess($successString)
	{
	    header("HTTP/1.0 200 OK");
	    header("Status: 200");
	    echo $successString;
	}

	/**
	 * Return true or false to indicate if the extension of the given file name is in the list.
	 * @access  public
	 * @param   a string of a file name
	 * @param   an array of all file extensions
	 * @return  true or false
	 * @author  Cindy Qi Li
	 */
	public static function is_extension_in_list($filename, $extension_list)
	{
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if (in_array($ext, $extension_list)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Verify that a string is Sha_1
	 * @access  public
	 * @param   $str : Sha_1 Encryted String
	 */

	public static function is_sha1($str)
	{
		return strlen($str) == 40 && ctype_xdigit($str);
	}
		
}
?>