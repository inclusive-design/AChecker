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
		
		$uri = trim($uri);
		$connection = @file_get_contents($uri);
		
		if (!$connection)
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
			return $uri;
		
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
		
		if (($filesize * 157) > ($memory_limit - $used_memory))
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
}
?>