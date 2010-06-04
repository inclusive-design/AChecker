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
* BasicChecks.class.php
* Class for accessibility validate
* This class contains basic functions called by BasicFunctions.class.php
*
* @access	public
* @author	Cindy Qi Li
* @package checker
*/

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH. 'classes/DAO/LangCodesDAO.class.php');

class BasicChecks {
	/**
	* cut out language code from given $lang
	* return language code
	*/
	public static function cutOutLangCode($lang)
	{
		$words = explode("-", $lang);
		return trim($words[0]);
	}

	/**
	* return array of all the 2-letter & 3-letter language codes with direction 'rtl'
	*/
	public static function getRtlLangCodes()
	{
		$langCodesDAO = new LangCodesDAO();
		
		return $langCodesDAO->GetLangCodeByDirection('rtl');
	}
	
	/**
	* check if the text is in one of the search string defined in $search_strings
	* @param $text: text to check
	*        $search_strings: array of match string. The string could be %[string]% or %[string] or [string]%
	* @return true if in, otherwise, return false 
	*/
	public static function inSearchString($text, $search_strings)
	{
		foreach ($search_strings as $str)
		{
			$str = trim($str);
			$prefix = substr($str, 0 , 1);
			$suffix = substr($str, -1);
			
			if ($prefix == '%' && $suffix == '%')
			{  // match '%match%' 
				if (stripos($text, substr($str, 1, -1)) > 0) return true;
			}
			else if ($prefix == '%')
			{  // match '%match'
				$match = substr($str, 1);
				if (substr($text, strlen($match)*(-1)) == $match) return true;
			} 
			else if ($suffix == '%')
			{  // match 'match%'
				$match = substr($str, 0, -1);
				if (substr($text, 0, strlen($match)) == $match) return true;
			} 
			else if ($text == $str)
			{ 
				return true;
			}
		}
		
		return false;
	}
	
	/**
	* check if the inner text is in one of the search string defined in checks.search_str
	* return true if in, otherwise, return false 
	*/
	public static function isTextInSearchString($text, $check_id, $e)
	{
		$text = strtolower(trim($text));

		$checksDAO = new ChecksDAO();
		$row = $checksDAO->getCheckByID($check_id);
		
		$search_strings = explode(',', strtolower(_AC($row['search_str'])));
		
		if (!is_array($search_strings)) return true;
		else
		{
			return BasicChecks::inSearchString($text, $search_strings);
		}
	}

	/**
	* Makes a guess about the table type.
	* Returns true if this should be a data table, false if layout table.
	*/
	public static function isDataTable($e)
	{
		global $is_data_table;
		
		// "table" element containing <th> is considered a data table
		if ($is_data_table) return;

		foreach ($e->children() as $child)
		{
			if ($child->tag == "th") 
				$is_data_table = true;
			else 
				BasicChecks::isDataTable($child);
		}
	}
	
	/**
	* Check recursively to find if $global_e has a parent with tag $parent_tag
	* return true if found, otherwise, false
	*/
	public static function hasParent($e, $parent_tag)
	{
		if ($e->parent() == NULL) return false;
		
		if ($e->parent()->tag == $parent_tag)
			return true;
		else
			return BasicChecks::hasParent($e->parent(), $parent_tag);
	}
	
	/**
	* Check recursively to find the number of children in $e with tag $child_tag
	* return number of qualified children
	*/
	public static function getNumOfTagRecursiveInChildren($e, $tag)
	{
		$num = 0;
		
		foreach($e->children() as $child)
			if ($child->tag == $tag) $num++;
			else $num += BasicChecks::getNumOfTagRecursiveInChildren($child, $tag);

		return $num;
	}
	
	/**
	* Check recursively if there are duplicate $attr defined in children of $e
	* set global var hasDuplicateAttribute to true if there is, otherwise, set it to false
	*/
	public static function hasDuplicateAttribute($e, $attr, &$id_array)
	{
		global $has_duplicate_attribute;
		
		if ($has_duplicate_attribute) return;
		
		foreach($e->children() as $child)
		{
			$id_val = strtolower(trim($child->attr[$attr]));
			
			if ($id_val <> "" && in_array($id_val, $id_array)) $has_duplicate_attribute = true;
			else 
			{
				if ($id_val <> "") array_push($id_array, $id_val);
				BasicChecks::hasDuplicateAttribute($child, $attr, $id_array);
			}
		}
	}

	/**
	* Get number of header rows and number of rows that have header column
	* return array of (num_of_header_rows, num_of_rows_with_header_col)
	*/
	public static function getNumOfHeaderRowCol($e)
	{
		$num_of_header_rows = 0;
		$num_of_rows_with_header_col = 0;
		
		foreach ($e->find("tr") as $row)
		{
			$num_of_th = count($row->find("th"));
			
			if ($num_of_th > 1) $num_of_header_rows++;
			if ($num_of_th == 1) $num_of_rows_with_header_col++;
		}
		
		return array($num_of_header_rows, $num_of_rows_with_header_col);
	}

	/**
	 * called by BasicFunctions::hasFieldsetOnMultiCheckbox()
	 * Check if form has "fieldset" and "legend" to group multiple checkbox buttons.
	 * @return true if has, otherwise, false
	 */
	public static function hasFieldsetOnMultiCheckbox($e)
	{
		// find if there are radio buttons with same name
		$children = $e->children();
		$num_of_children = count($children);
		
		foreach ($children as $i => $child)
		{
			if (strtolower(trim($child->attr["type"])) == "checkbox")
			{
				$this_name = strtolower(trim($child->attr["name"]));
				
				for($j=$i+1; $j <=$num_of_children; $j++)
					// if there are radio buttons with same name,
					// check if they are contained in "fieldset" and "legend" elements
					if (strtolower(trim($children[$j]->attr["name"])) == $this_name)
						if (BasicChecks::hasParent($e, "fieldset"))
							return BasicChecks::hasParent($e, "legend");
						else
							return false;
			}
			else
				return BasicChecks::hasFieldsetOnMultiCheckbox($child);
		}
		
		return true;
	}

	/**
	* check if value in the given attribute is a valid language code
	* return true if valid, otherwise, return false
	*/
	public static function isValidLangCode($code)
	{
		$code = BasicChecks::cutOutLangCode($code);
		$langCodesDAO = new LangCodesDAO();

		if (strlen($code) == 2) 
		{
			$rows = $langCodesDAO->GetLangCodeBy2LetterCode($code);
		}
		else if (strlen($code) == 3)
		{
			$rows = $langCodesDAO->GetLangCodeBy3LetterCode($code);
		}
		else 
		{
			return false;
		}

		return (is_array($rows));
	}

	/**
	* Return file location based on base href or uri
	* return file itself if both base href and uri are empty.
	*/
	public static function getFile($src_file, $base_href, $uri)
	{
		if (preg_match('/http.*(\:\/\/).*/', $src_file))
			$file = $src_file;
		else
		{
			// URI that image relatively located to
			// Note: base_href is from <base href="...">
			if (isset($base_href) && $base_href <> '') 
			{
				if (substr($base_href, -1) <> '/') $base_href .= '/';
			}
			else if (isset($uri) && $uri <> '')
			{
				preg_match('/^(.*\:\/\/.*\/).*/', $uri, $matches);
				if (!isset($matches[1])) $uri .= '/';
				else $uri = $matches[1];
			}
				
			if (substr($src_file, 0, 1) == '/')  //absolute path
			{
				if (isset($base_href) && $base_href <> '') 
				{
					$file = $base_href.substr($src_file, 1);
				}
				else if (isset($uri) && $uri <> '')
				{
					preg_match('/^(.*\:\/\/)(.*)/', $uri, $matches);
					$root_uri = $matches[1].substr($matches[2], 0, strpos($matches[2], '/'));
					$file = $root_uri.$src_file;
				}
			}
			else // relative path
			{
				if (isset($base_href) && $base_href <> '') 
				{
					$file = $base_href.$src_file;
				}
				else if (isset($uri) && $uri <> '')
				{
					$file = $uri.$src_file;
				}
			}
		}
		
		if (!isset($file)) $file = $src_file;
		
		return $file;
	}
}
?>