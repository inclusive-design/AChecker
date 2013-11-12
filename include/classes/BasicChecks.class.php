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
* Basic Checks.class.php
* Class for accessibility validate
* This class contains basic functions called by BasicFunctions.class.php
*
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH. 'classes/DAO/LangCodesDAO.class.php');
define("DEFAULT_FONT_SIZE",12);
define("DEFAULT_FONT_FORMAT","pt");
//MB added for check 245
//include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
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
                        //MB 
                        //do not check child table
			else if($child->tag != "table") 
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
	public static function hasDuplicateAttribute($e, $attr, $id_array)
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
		// The allowed characters in a valid language code are letters, numbers or dash(-).
		if (!preg_match("/^[a-zA-Z0-9-]+$/", $code)) {
			return false;
		}
		
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
		if (preg_match('/http.*(\:\/\/).*/', $src_file)) {
			$file = $src_file;
		} else {
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
					$prefix_uri = $base_href;
				}
				else if (isset($uri) && $uri <> '')
				{
					$prefix_uri = $uri;
				}
				
				if (isset($prefix_uri) && $prefix_uri <> '') 
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
	/**
	Check if the luminosity contrast ratio between $color1 and $color2 is at least 5:1
	Input: color values to compare: $color1 & $color2. Color value can be one of: rgb(x,x,x), #xxxxxx, colorname
	Return: true or false
	*/
	public static function has_good_contrast_waiert($color1, $color2)
	{
		include_once (AC_INCLUDE_PATH . "classes/ColorValue.class.php");
		$color1 = new ColorValue($color1);
		$color2 = new ColorValue($color2);
		
		if (!$color1->isValid() || !$color2->isValid())
			return true;
		
		$colorR1 = $color1->getRed();
		$colorG1 = $color1->getGreen();
		$colorB1 = $color1->getBlue();
		
		$colorR2 = $color2->getRed();
		$colorG2 = $color2->getGreen();
		$colorB2 = $color2->getBlue();
		$brightness1 = (($colorR1 * 299) + 
							($colorG1 * 587) + 
							($colorB1 * 114)) / 1000;
		$brightness2 = (($colorR2 * 299) + 
							($colorG2 * 587) + 
							($colorB2 * 114)) / 1000;
		$difference = 0;
		if ($brightness1 > $brightness2)
		{
			$difference = $brightness1 - $brightness2;
		}
		else 
		{
			$difference = $brightness2 - $brightness1;
		}
		if ($difference < 125)
		{
			return false;
		}
		// calculate the color difference
		$difference = 0;
		// red
		if ($colorR1 > $colorR2)
		{
			$difference = $colorR1 - $colorR2;
		}
		else
		{
			$difference = $colorR2 - $colorR1;
		}
		// green
		if ($colorG1 > $colorG2)
		{
			$difference += $colorG1 - $colorG2;
		}
		else
		{
			$difference += $colorG2 - $colorG1;
		}
		// blue
		if ($colorB1 > $colorB2)
		{
			$difference += $colorB1 - $colorB2;
		}
		else
		{
			$difference += $colorB2 - $colorB1;
		}
		return ($difference > 499);
	}	
	
		/**
	* Check recursively to find if $e has a parent with tag $parent_tag
	* return true if found, otherwise, false
	*/
	public static function has_parent($e, $parent_tag)
	{
		if ($e->parent() == NULL) return false;
		
		if ($e->parent()->tag == $parent_tag)
			return true;
		else
			return BasicChecks::has_parent($e->parent(), $parent_tag);
	}
	
	
		/**
	* cut out language code from given $lang
	* return language code
	*/
	public static function cut_out_lang_code($lang)
	{
		$words = explode("-", $lang);
		return trim($words[0]);
	}
	
		/**
	* check if $code is a valid language code
	* return true if valid, otherwise, return false
	*/
	public static function valid_lang_code($code)
	{
		global $db;
		$code = BasicChecks::cut_out_lang_code($code);
		$sql = "SELECT COUNT(*) cnt FROM ". TABLE_PREFIX ."lang_codes WHERE ";
		
		if (strlen($code) == 2) $sql .= "code_2letters = '".$code ."'";
		else if (strlen($code) == 3) $sql .= "code_3letters = '".$code ."'";
		else return false;
		
		$result	= mysql_query($sql, $db) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		return ($row["cnt"] > 0);
	}
	
	
	
	/**
	* find language code defined in html
	* return language code
	*/
	public static function get_lang_code($content_dom)
	{
		// get html language
		$e_htmls = $content_dom->find("html");
		foreach ($e_htmls as $e_html)
		{
			if (isset($e_html->attr["xml:lang"])) 
			{
				$lang = trim($e_html->attr["xml:lang"]);
				break;
			}
			else if (isset($e_html->attr["lang"]))
			{
				$lang = trim($e_html->attr["lang"]);
				break;
			}
		}
		
		return BasicChecks::cut_out_lang_code($lang);
	}
		
	
		/**
	Check if the luminosity contrast ratio between $color1 and $color2 is at least 5:1
	Input: color values to compare: $color1 & $color2. Color value can be one of: rgb(x,x,x), #xxxxxx, colorname
	Return: true or false
	*/
	public static function get_luminosity_contrast_ratio($color1, $color2)
	{
		include_once (AC_INCLUDE_PATH . "classes/ColorValue.class.php");
		$color1 = new ColorValue($color1);
		$color2 = new ColorValue($color2);
		
		if (!$color1->isValid() || !$color2->isValid())
			return true;
		
		$linearR1 = $color1->getRed()/255;
		$linearG1 = $color1->getRed()/255;
		$linearB1 = $color1->getRed()/255;
		$lum1 = (pow ($linearR1, 2.2) * 0.2126) +
			(pow ($linearG1, 2.2) * 0.7152) +
			(pow ($linearB1, 2.2) * 0.0722) + .05;
			
		$linearR2 = $color2->getRed()/255;
		$linearG2 = $color2->getRed()/255;
		$linearB2 = $color2->getRed()/255;
		$lum2 = (pow ($linearR2, 2.2) * 0.2126) +
			(pow ($linearG2, 2.2) * 0.7152) +
			(pow ($linearB2, 2.2) * 0.0722) + .05;
			
		$ratio = max ($lum1, $lum2) / min($lum1, $lum2);
		// round the ratio to 2 decimal places
		$factor = pow(10,2);
		// Shift the decimal the correct number of places
		// to the right.
		$val = $ratio * $factor;
		// Round to the nearest integer.
		$tmp = round($val);
		// Shift the decimal the correct number of places back to the left.
		$ratio2 = $tmp / $factor;
		return $ratio2;
	}
	
		/**
	* Check recursively if there are duplicate $attr defined in children of $e
	* set global var $has_duplicate_attribute to true if there is, otherwise, set it to false
	*/
	public static function has_duplicate_attribute($e, $attr, $id_array)
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
				BasicChecks::has_duplicate_attribute($child, $attr, $id_array);
			}
		}
	}
	
	
	/**
	* check if $e has associated label
	* return true if has, otherwise, return false
	*/
	public static function has_associated_label($e, $content_dom)
	{
		// 1. The element $e is contained by a "label" element
		// 2. The element $e has a "title" attribute
		if ($e->parent()->tag == "label" || isset($e->attr["title"])) return true;
		
		// 3. The element $e has an "id" attribute value that matches the "for" attribute value of a "label" element
		$input_id = $e->attr["id"];
		
		if ($input_id == "") return false;  // attribute "id" must exist
		
		foreach ($content_dom->find("label") as $e_label)
		  if (strtolower(trim($e_label->attr["for"])) == strtolower(trim($e->attr["id"])))
		    return true;
	  
	  return false;
	}
	
		/**
	* ADD CODE FOR THIS!!!
	* check if the label for $e is closely positioned to $e
	* return true if closely positioned, otherwise, return false
	*/
	public static function is_label_closed($e)
	{
		return true;
	}
	
		/**
	* Check radio button groups are marked using "fieldset" and "legend" elements
	* Return: use global variable $is_radio_buttons_grouped to return true (grouped properly) or false (not grouped)
	*/
	public static function is_radio_buttons_grouped($e)
	{
		$radio_buttons = array();
		
		foreach ($e->find("input") as $e_input)
		{
			if (strtolower(trim($e_input->attr["type"])) == "radio")
				array_push($radio_buttons, $e_input);
		}
		for ($i=0; $i < count($radio_buttons); $i++)
		{
		  for ($j=0; $j < count($radio_buttons); $j++)
		  {
		    if ($i <> $j && strtolower(trim($radio_buttons[$i]->attr["name"])) == strtolower(trim($radio_buttons[$j]->attr["name"]))
		        && !BasicChecks::has_parent($radio_buttons[$i], "fieldset") && !BasicChecks::has_parent($radio_buttons[$i], "legend"))
		      return false;
		  }
		}
		
		return true;
	}
	
		/**
	* Makes a guess about the table type.
	* Returns true if this should be a data table, false if layout table.
	*/
	public static function is_data_table($e)
	{
		global $is_data_table;
		
		// "table" element containing <th> is considered a data table
		if ($is_data_table) return;
		foreach ($e->children() as $child)
		{
			if ($child->tag == "th") 
				$is_data_table = true;
			else 
				BasicChecks::is_data_table($child);
		}
	}
	
	
	/**
	* check if associated label of $e has text
	* return true if has, otherwise, return false
	*/
	public static function associated_label_has_text($e, $content_dom)
	{
		// 1. The element $e has a "title" attribute
		if (trim($e->attr["title"]) <> "") return true;
		// 2. The element $e is contained by a "label" element
		if ($e->parent()->tag == "label")
		{
			$pattern = "/(.*)". preg_quote($e->outertext, '/') ."/";
			preg_match($pattern, $e->parent->innertext, $matches);
			if (strlen(trim($matches[1])) > 0) return true;
		}
		
		// 3. The element $e has an "id" attribute value that matches the "for" attribute value of a "label" element
		$input_id = $e->attr["id"];
		
		if ($input_id == "") return false;  // attribute "id" must exist
		
		foreach ($content_dom->find("label") as $e_label)
		{
			if ($e_label->attr["for"] == $input_id)
			{
				// label contains text
				if (trim($e_label->plaintext) <> "") return true;
				
				// label contains an image with alt text
				foreach ($e_label->children as $e_label_child)
					if ($e_label_child->tag == "img" && strlen(trim($e_label_child->attr["alt"])) > 0)
						return true;
			}
		}
		
		return false;
	}
	
	
		public static function check_next_header_not_in ($content_dom, $line_number, $col_number, $not_in_array)
	{
		global $header_array;
		
		// find the next header after $line_number, $col_number
		foreach ($header_array as $e)
		{
			if ($e->linenumber > $line_number || ($e->linenumber == $line_number && $e->colnumber > $col_number))
			{
				if (!isset($next_header)) 
					$next_header = $e;
				else if ($e->linenumber < $next_header->line_number || ($e->linenumber == $next_header->line_number && $e->colnumber > $next_header->col_number))
					$next_header = $e;
			}
		}
		if (isset($next_header) && !in_array($next_header->tag, $not_in_array))
			return false;
		else
			return true;
	}
	
	public static function find_all_headers($elements, $header_array)
	{
		foreach ($elements as $e)
		{
			if (substr($e->tag, 0, 1) == "h" and intval(substr($e->tag, 1)) <> 0)
				array_push($header_array, $e);
			
			BasicChecks::find_all_headers($e->children(), $header_array);
		}
		
		return $header_array;
	}
	
	
	/**
	* Check recursively to find the number of children in $e with tag $child_tag
	* return number of qualified children
	*/
	public static function count_children_by_tag($e, $tag)
	{
		$num = 0;
		
		foreach($e->children() as $child)
			if ($child->tag == $tag) $num++;
			else $num += BasicChecks::count_children_by_tag($child, $tag);
		return $num;
	}
	
	/**
	* Get number of header rows and number of rows that have header column
	* return array of (num_of_header_rows, num_of_rows_with_header_col)
	*/
	public static function get_num_of_header_row_col($e)
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
		
	//CSS basic checks
	public static function getSiteUri($uri){
			
			
			if(stripos($uri,".php")!==false || stripos($uri,".html")!==false || stripos($uri,".asp")!==false || stripos($uri,".htm")!==false || stripos($uri,".xhtml")!==false || stripos($uri,".xhtm")!==false)
			{	
				// must remove part after finished
				//devo eliminare la parte dopo l'ultimo /
				$uri=strrev($uri);
				$posizione= stripos($uri,"/");
				$uri=strrev($uri);
				$uri=substr($uri,0,-$posizione);
			}
			// if there ', delete the / at the end of the url
			//se c'e', elimino lo / alla fine dell'url
			if(substr($uri,-1)=="/")
				$uri=substr($uri,0,-1);
				
			return $uri;
	}
	// removes all elements figlil children of $ e returns the contents as "plaintext"
	//rimuove tutti gli elementi figlil figli di $e e restituisce il contenuto sotto forma di "plaintext"
	public static function remove_children($e)
	{
		$contenuto_obj=$e->plaintext;
		$figli=$e->children();
		
		foreach ($figli as $obj)
		{
			
			$txt=$obj->plaintext;
			if($txt!=null || $txt!='')
			{
				$arr=explode($txt, $contenuto_obj, 2);
				$contenuto_obj=implode($arr);
			}
		}
		
		return $contenuto_obj;
	}	
	
	
	
	/*
	* Search and returns the value of a property 'CSS (the value between "" and ";")
	* Searches in style and inline style sheet (id, class, property name)
	* It takes as parameters the item and the name of the property 
	*
	* Ricerca e restituisce il valore di una proprieta' CSS (il valore compreso tra ":" e ";")
	* Esegue la ricerca nello stile inline e nel foglio di stile (id, class, nome proprietà)
	* Prende come parametri l'elemento e il nome della proprieta'
	*/
	public static function get_p_css($e, $p, $pseudoClass = null) {
		
		//controllo sullo stile inline
		if (isset ( $e->attr ["style"] )) {
			$inline = BasicChecks::GetElementStyleInline ( $e->attr ["style"], $p );
                        //verifico "!important"
			$posizione = stripos ( $inline, "!important" );
			if ($posizione !== false) {
				//tolgo "!important" e ritorno il valore della proprietà
				//echo str_ireplace("!important", "", $inlinea_inline);
				$inline = str_ireplace ( $p, "", $inline );
				$inline = str_ireplace ( ":", "", $inline );
				$inline = str_ireplace ( "!important", "", $inline );
				return $inline;
			}
		}
		//Internal control over the style and the external styles
		//$best: will store 'the value of the priority rule that has'more' contained in the high-style indoor / outdoor
		//about the item and its $ e '$ p
		//controllo sullo stile interno e sugli stili esterni
		//$best: memorizzera' il valore della regola che ha priorita' piu' alta contenuta nello stile interno/esterno
		//relativamente all'elemento $e e alla proprita' $p
		$best = null;
		
		//id
		if (isset ( $e->id )) {
			$id = BasicChecks::GetElementStyleId ( $e, $e->id, $p, $pseudoClass );
			$best = BasicChecks::getPriorityInfo ( $best, $id );
		}
	
		//class
		if (isset ( $e->class )) {
                    
                        $classes = trim($e->class);
                        //removing doble spaces
                        while (stripos($classes, '  '))
                            $classes = str_replace('  ', ' ', $classes);
                        $classes = explode(' ',$classes);
                        for($cClass = 0; $res == false,$cClass < sizeof($classes); $cClass++)
                        {
                            $class = BasicChecks::GetElementStyleClass ( $e, $classes[$cClass], $p, $pseudoClass );
                            $best = BasicChecks::getPriorityInfo ( $best, $class );
                        }
		}
		
		//tag
		$tag = BasicChecks::GetElementStyle ( $e, $e->tag, $p, $pseudoClass );
		
		$best = BasicChecks::getPriorityInfo ( $best, $tag );
		
		// * try, in the instructions inside / outside
		// apply any property 'of * if:
		// internal or external style is not 'declare the property' for the element $ p $ and
		// if that is declared in * important, but not that of the IOE and style '
		//
		//cerco *, nel foglio interno/esterno
		//applico l'eventuale proprieta' di * se:
		//nello stile interno o esterno non e' dichiarata la proprieta' $p per l'elemento $e
		//se quella dichiarata in * è important, ma quella dello stile i o e non lo e'
		
		$best_all = BasicChecks::GetElementStyle ( $e, '*', $p );
		
		if ($best == null || (stripos ( $best ["valore"], "!important" ) === false && stripos ( $best_all ["valore"], "!important" ) !== false))
			$best = $best_all;
			
		// if coming here was not the style inline! important since the early control
		// inline style has always priority 'rule, unless a rule in a style indoor / outdoor does not contain! important
		//
		//se arrivo qui lo stile inline non ha !important dato che lo controllo all'inizio
		//lo stile inline ha sempre priorita' massima, a meno che una regola in uno stile interno/esterno non contenga  !important
		
		if ($inline != null && $inline != "") {
			if (stripos ( $best ["valore"], "!important" ) === false) //non c'e' !important nel foglio di stile
				
				return $inline;
		}
		// inline style if there is not $ p 'returns the value of $ best
		//
		//se nello stile inline $p non c'e' restituisco il valore di $best
		//echo("<p>regola</p>");
		//print_r($best["css_rule"]);
		
		//css array containing the CSS rules are printed in output
		//array css contiene le regole dei css che verranno stampate in output
		global $array_css;
		
		if (isset ( $best ["css_rule"] )) {
			$same = false;
			if (sizeof ( $array_css ) > 0) {
				$size_of_best = sizeof ( $best ["css_rule"] ["prev"] );
				foreach ( $array_css as $rule ) {
					$size_of_prev_rules = sizeof ( $rule ["prev"] );
					if ($size_of_prev_rules == $size_of_best) {
						for($i = 0; $i < $size_of_prev_rules; $i ++) {
							if ($rule ["prev"] [$i] == $best ["css_rule"] ["prev"] [$i])
								$same = true;
							else {
								$same = false;
								break;
							}
						}
						if ($same == true) break;
					}
				}
			}
			
			if ($same == false) array_push ( $array_css, $best ["css_rule"] );
		}
		
		
		
		return $best ["valore"];
	
	}
	
        
	
	
	/*
	 It takes in input two data structures representing two css rules
	(every frame contains the value of property 'and the number of id, class, and tag content in the selector)
	Returns the rule that has highest priority according to the type of selectors
	If the two rules have the same priority, it returns the position with more
	
	Prende in input due strutture dati rappresentanti due regole css
	(ogni struttura contiene il valore della proprieta' e il numero di id, class e tag contenuti nel selettore)
	Restituisce la regola che ha priorità più alta in base alla tipologia dei selettori
	Se le due regole hanno identica priorita, restituisce quella con posizione maggiore
	*/
	public static function getPriorityInfo($info1, $info2) {
	
	
	
	
		if ($info1 == null || $info1 == "")
			return $info2;
		if ($info2 == null || $info2 == "")
			return $info1;
	
	
		if (stripos ( $info1 ["valore"], "!important" ) !== false && stripos ( $info2 ["valore"], "!important" ) === false) {
			$best = $info1;
		} elseif (stripos ( $info1 ["valore"], "!important" ) === false && stripos ( $info2 ["valore"], "!important" ) !== false) {
			$best = $info2;
		} else //have both! important or do not have any of the two, so I check the id
			//hanno entrambe !important o non lo hanno nessuna della due, quindi verifico il numeo di id
		{
				
			if ($info1 ["num_id"] > $info2 ["num_id"]) {
				$best = $info1;
			} elseif ($info1 ["num_id"] < $info2 ["num_id"]) {
				$best = $info2;
			} else { // same id number, control the number of class
				//stesso numero di id, controllo il numero di class
	
	
				if ($info1 ["num_class"] > $info2 ["num_class"]) {
					$best = $info1;
				} elseif ($info1 ["num_class"] < $info2 ["num_class"]) {
					$best = $info2;
				} else { // same ids and classes number, check number of pseudoclasses
					//stesso numero di id e class, controllo in numero di pseudoclasse
						
	
					if($info1 ["num_pseudoclass"] + $info1 ["num_attr_name"] + $info1 ["num_attr_value"] > $info2 ["num_pseudoclass"] + $info2 ["num_attr_name"] + $info2 ["num_attr_value"]){
						$best = $info1;
					}elseif($info1 ["num_pseudoclass"] + $info1 ["num_attr_name"] + $info1 ["num_attr_value"] > $info2 ["num_pseudoclass"] + $info2 ["num_attr_name"] + $info2 ["num_attr_value"]){
						$best = $info2;
					}else{
	
						if ($info1 ["num_tag"] > $info2 ["num_tag"]) {
	
							$best = $info1;
						} elseif ($info1 ["num_tag"] < $info2 ["num_tag"]) {
							$best = $info2;
						} else {
							// same  number of id, class, pseudoclasses and tags: is the priority of the new rule
							//stesso  numero di id, class e tag: la priorità è della nuova regola
	
							// the two rules are completely equivalent, and returns
							// the one with a smaller css id (the inner leaf idcss == 0).
							//le due regole sono perfettamente equivalenti, quindi restituisco quella
							// con id css piu' piccolo (idcss == 0 � il foglio interno).
							if ($info1 ["css_rule"] ["idcss"] > $info2 ["css_rule"] ["idcss"])
								$best = $info1;
							elseif ($info1 ["css_rule"] ["idcss"] < $info2 ["css_rule"] ["idcss"])
							$best = $info2;
							else { // the two rules are equivalent in the same css (internal or external)
								//le due regole equivalenti sono nello stesso css (interno o esterno)
	
	
								if ($info1 ["css_rule"] ["posizione"] > $info2 ["css_rule"] ["posizione"])
									$best = $info1;
								else
									$best = $info2;
							}
	
						}
					}
	
				}
			}
		}
	
		return $best;
	
	}
	
	/*
	* Check for text-decoration: blink
	* Controlla la presenza di text-decoration: blink
	*/
	public static function check_blink($e, $content_dom) {
		
		$inlinea = BasicChecks::get_p_css ( $e, "text-decoration" );
		//echo("<p>blink--->".$inlinea."</p>");	
		if (strpos ( $inlinea, "blink" ) !== false)
			return false;
		
		return true;
	}
	
	/**
	 * Function that tries to separate the structure of a style (internal or external) and derives from the selectors and attributes
	 * Funzione che cerca suddivide la struttura di uno stile (interno o esterno) e ne ricava i selettori e gli attributi
	 */
	public static function GetCSSDom($css_content, $b) {
		
		global $selettori;
		global $attributi;
		global $attributo_selettore;
                //try to remove css errors
                $css_content = str_replace("*//", "*/", $css_content);
                $css_content = str_replace("//*", "/*", $css_content);
                $out=array();                
				//removing comments
				//$css_content = preg_replace ( '/\/\*(.|\s)*?\*\//', '', $css_content );
//                 while(preg_match('/\/\*(.|\s)*?\*\//', $css_content))
//                 {
//                 	$out = preg_split('/\/\*(.|\s)*?\*\//', $css_content, 2);
//                 	$css_content = $out[0].$out[1];                
//                 }
                
                while(stripos( $css_content, '/*') !==false )
                {
                
                	$out = explode('/*', $css_content, 2); //  out[1]: part after /*
                	$out2 = explode('*/', $out[1], 2); // out2[1] part after }}
                	$css_content = $out[0].$out2[1];
                
                }
               
                //removing @media ... from css                
                
                //echo($css_content);
                
                //keep only content of @media screen and @media all
//                $css_content = preg_replace('/@media(\s)*all(\s)*{/', '', $css_content);
//                $css_content = preg_replace('/@media(\s)*screen(\s)*{/', '', $css_content);
                while(preg_match('/@media(\s)*all(\s)*{/', $css_content))
                {
                    
                    $out = preg_split('/@media(\s)*all(\s)*{/', $css_content, 2); //  out[1]: part after @media
                    $out2 = preg_split('/}(\s)*}/', $out[1], 2); // out2[1] part after }}
                    $css_content = $out[0].$out2[0].'}'.$out2[1];
                    
                }   
                
            
                while(preg_match('/@media(\s)*screen(\s)*{/', $css_content))
                {
                    
                    $out = preg_split('/@media(\s)*screen(\s)*{/', $css_content, 2); //  out[1]: part after @media
                    $out2 = preg_split('/}(\s)*}/', $out[1], 2); // out2[1] part after }}
                    $css_content = $out[0].$out2[0].'}'.$out2[1];
                    
                }                
                
                //removing empty @media (ie: @media all{ })
                //$css_content = preg_replace('/@media(\s)*(.)*(\s)*{(\s)*}/', '', $css_content);
                
                //preg_replace cause a segmentation fault when strings are too long maybe
                //seems preg_replace can't handle content between '{' and '}' when it's too long
                //$css_content = preg_replace('/@media(\s)*(.)*{*(.|\s)*?}(\s)*}/', '', $css_content);
                //removing remaining @media in a different way
                while( stripos($css_content,'@media') !== false)
                {
                    
                    $out = preg_split('/@media/', $css_content, 2); //  out[1]: part after @media
                    
                    $out2 = preg_split('/}(\s)*}/', $out[1], 2); // out2[1] part after }}
                    $css_content = $out[0].$out2[1];
                    
                }
  
                              
		/* Inserted at the beginning of the CSS code brace '}' to facilitate
				the extraction of elements: each reading taken from '}' to '}'
		Inserisco all'inizio del codice del CSS la parentesi graffa '}' per facilitare
			   l'estrazione degli elementi: ad ogni lettura prendo da '}' a '}' */
		$css_content = '}' . $css_content;
		$i = 0;
		while ( preg_match( '/}([^}]*)}/i', $css_content, $elemento ) ) {
			$elemento [1] = $elemento [1] . '}';
			$css_content = substr ( $css_content, strlen ( $elemento [1] ) );
			$elemento [$i] = trim ( $elemento [1] );
			$selettore = substr ( $elemento [1], 0, strpos ( $elemento [1], '{' ) );
                        
                        
			$selettori [$b] [$i] = trim ( $selettore ) . "{";
			// Inside  list $selettori have selectors;
			// Dentro $selettori ho la lista dei selettori;
			//if (eregi ( '\{(.*)\}', $elemento [1], $attributo )) {
			if (preg_match ( '/\{(.*)\}/', $elemento [1], $attributo )) {
				$attributo [1] = trim ( $attributo [1] );
				$attributi [$b] [$i] = $attributo [1];
                                
			}
			$cont = 0;
                        	
                        //add a ";" at the end of a list of css rules
                        if(substr($attributi [$b] [$i], strlen($attributi [$b] [$i])-1) != ';')
                                $attributi [$b] [$i] .= ';';
			//while ( eregi ( '^([^;]*);', $attributi [$b] [$i], $singolo ) ) {
			while ( preg_match ( '/^([^;]*);/i', $attributi [$b] [$i], $singolo ) ) {
				$attributi [$b] [$i] = substr ( $attributi [$b] [$i], strlen ( $singolo [1] ) + 1 );
				$attributo_selettore [$b] [$i] [$cont] = trim ( $singolo [1] );
				// controls to eliminate the white spaces by the selectors
				//Controlli per eliminiare gli spazi bianchi dai selettori
				$pos_spazio = strpos ( $attributo_selettore [$b] [$i] [$cont], ':' );
				$stringa_prima = substr ( $attributo_selettore [$b] [$i] [$cont], 0, $pos_spazio );
				$stringa_prima = trim ( $stringa_prima );
				$stringa_dopo = substr ( $attributo_selettore [$b] [$i] [$cont], $pos_spazio + 1, strlen ( $attributo_selettore [$b] [$i] [$cont] ) - strlen ( $string_prima ) - 1 );
				$stringa_dopo = trim ( $stringa_dopo );
				$attributo_selettore [$b] [$i] [$cont] = $stringa_prima . ':' . $stringa_dopo;
				$attributo_selettore [$b] [$i] [$cont] = trim ( $attributo_selettore [$b] [$i] [$cont] );
				
				$cont ++;
			}
			$i ++;
		}
                
	}
	// return the property value of $val in inline style $stile
	//restituisce il valore della proprieta $val in uno stile inline $stile
	// return the property value of $val in inline style $stile
	//restituisce il valore della proprieta $val in uno stile inline $stile
	public static function GetElementStyleInline($stile, $val) {
		// create an array containing all the rules are separated by ";"
		//creo un array contenente tutte le regole separate da ";"
		//$array_pr = split ( ";", $stile );
		$array_pr = preg_split ( "/;/", $stile );
		$arr_val = array ();
		$valore_proprieta = "";
		
		$i = 0;
		foreach ( $array_pr as $regola ) {
			// break every rule, separated by ':' in: property => value
			//spezzo ogni regola, separata dai ":" in: proprieta=>valore
			//$appoggio = split ( ":", trim ( $regola ) );
			$appoggio = preg_split ( "/:/", trim($regola) );
			if (isset ( $array_val [trim ( $appoggio [0] )] ) && stripos ( $array_val [$appoggio [0]] ["val"], "!important" ) !== false) {
				if (stripos ( $appoggio [1], "!important" ) !== false) {
					$array_val [$appoggio [0]] ["val"] = trim ( $appoggio [1] );
					$array_val [$appoggio [0]] ["pos"] = trim ( $i );
				}
			
			} else {
				$array_val [$appoggio [0]] ["val"] = trim ( $appoggio [1] );
				$array_val [$appoggio [0]] ["pos"] = trim ( $i );
			}
			$i ++;
		}
		// Find if the prpertiy $val is defined and returned
		//cerco se la proprieta' $val è definita e la restituisco
		switch ($val) {
			case "margin-top" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["margin"] ))
					$valore_proprieta = BasicChecks::getTop ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["margin"] ) );
				break;
			
			case "margin-bottom" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["margin"] ))
					$valore_proprieta = BasicChecks::getBottom ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["margin"] ) );
				break;
			
			case "margin-left" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["margin"] ))
					$valore_proprieta = BasicChecks::getLeft ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["margin"] ) );
				break;
			
			case "margin-right" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["margin"] ))
					$valore_proprieta = BasicChecks::getRight ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["margin"] ) );
				break;
			
			case "padding-top" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["padding"] ))
					$valore_proprieta = BasicChecks::getTop ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["padding"] ) );
				break;
			
			case "padding-bottom" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["padding"] ))
					$valore_proprieta = BasicChecks::getBottom ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["padding"] ) );
				break;
			
			case "padding-left" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["padding"] ))
					$valore_proprieta = BasicChecks::getLeft ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["padding"] ) );
				break;
			
			case "padding-right" :
				if (isset ( $array_val [$val] ) || isset ( $array_val ["padding"] ))
					$valore_proprieta = BasicChecks::getRight ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["padding"] ) );
				break;
			
//			case "background-color" :
//				// Check if there is a background image, if the property exists set to -1
//				//verifico se c'è un'immagine di sfondo, nel caso setto la proprietà a -1
//				if (isset ( $array_regole ["regole"] ["background-image"] ))
//					$valore_proprieta_new = "undetermined";
//				elseif (isset ( $array_regole ["regole"] ["background"] ) && stripos ( $array_regole ["regole"] ["background"] ["val"], "url" ) !== false)
//					$valore_proprieta_new = "undetermined";
//				
//				elseif (isset ( $array_val [$val] ) || isset ( $array_val ["background"] ))
//					$valore_proprieta = BasicChecks::getBgColor ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["background"] ) );
//				break;
                                
			case "background-image" :
				// Check if there is a background image, if the property exists set to -1
				//verifico se c'è un'immagine di sfondo, nel caso setto la proprietà a -1
				if (isset ( $array_val ["regole"] ["background-image"] ))
					$valore_proprieta_new = "undetermined";
				elseif (isset ( $array_val ["regole"] ["background"] ) && stripos ( $array_val ["regole"] ["background"] ["val"], "url" ) !== false)
					$valore_proprieta_new = "undetermined";
				
				break;
                                
			case "background-color" :
				// Check if there is a background image, if the property exists set to -1
				//verifico se c'è un'immagine di sfondo, nel caso setto la proprietà a -1
				if (isset ( $array_val ["regole"] ["background"] ) && stripos ( $array_val ["regole"] ["background"] ["val"], "url" ) !== false)
					$valore_proprieta_new = "undetermined";
				
				elseif (isset ( $array_val [$val] ) || isset ( $array_val ["background"] ))
					$valore_proprieta = BasicChecks::getBgColor ( BasicChecks::get_priority_prop ( $array_val [$val], $array_val ["background"] ) );
				break;                                
			
			default :
				if (isset ( $array_val [$val] ))
					$valore_proprieta = $array_val [$val] ["val"];
				break;
		
		}
		
		return $valore_proprieta;
	
	}
	// gets the contents of the 'background / background-color
	// and returns the background color if defined
	//riceve il contenuto della proprieta' background/background-color
	//e restituisce il colore di background se definito
	public static function getBgColor($stringa_valori) {
		
		$nomi_colori = array ('black', 'silver', 'gray', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'gold', 'navy' );
		
		//$array_valori = split ( " ", $stringa_valori );
		$array_valori = preg_split ( "/  /", $stringa_valori );		
		foreach ( $array_valori as $val ) {
			if (stripos ( $val, "#" ) !== false || stripos ( $val, "rgb(" ) !== false) {
				return $val;
			} else // controllo i nomi dei colori
{
				foreach ( $nomi_colori as $colore )
					if (stripos ( $val, $colore ) !== false)
						return $colore;
			}
		}
	
	}
	// gets the contents of the property 'margin / padding and returns the value of the margin / padding left
	//riceve il contenuto della proprieta' margin/padding e restituisce il valore del margin/padding sinistro
	public static function getLeft($stringa_valori) {
		
		$has_important = stripos ( $stringa_valori, "!important" );
		// remove if there is! important and attach at the end
		//se c'è rimuovo !important e lo attacco alla fine
		if ($has_important !== false) {
			$stringa_valori = str_ireplace ( "!important", "", $stringa_valori );
			$stringa_valori = trim ( $stringa_valori );
		}
		//$array_valori = split ( " ", $stringa_valori );
		$array_valori = preg_split ( "/ /", $stringa_valori );
		$size = sizeof ( $array_valori );
		if ($size <= 0)
			return "";
		else
			$val_ret = $array_valori [$size - 1]; //last value, then left -ultimo valore, quindi left
		
		if ($has_important === false)
			return $val_ret;
		else
			return "" . $val_ret . " !important";
	
	}
	// gets the contents of the property margin / padding and returns the value of the margin / padding right
	//riceve il contenuto della proprieta' margin/padding e restituisce il valore del margin/padding destro
	public static function getRight($stringa_valori) {
		$has_important = stripos ( $stringa_valori, "!important" );
		// if there is !important, remove it
		//se c'è rimuovo !important
		if ($has_important !== false) {
			$stringa_valori = str_ireplace ( "!important", "", $stringa_valori );
			$stringa_valori = trim ( $stringa_valori );
		}
	
		//$array_valori = split ( " ", $stringa_valori );		
		$array_valori = preg_split ( "/ /", $stringa_valori );
		$size = sizeof ( $array_valori );
		if ($size <= 0)
			return "";
		else {
			if ($size >= 2)
				$val_ret = $array_valori [1]; //second value, then right - secondo valore, quindi right
			else
				
				$val_ret = $array_valori [0]; //first value - primo valore
		}
		
		if ($has_important === false)
			return $val_ret;
		else
			return "" . $val_ret . " !important";
	
	}
	// gets the contents of the property margin / padding and returns the value of the margin / padding top
	//riceve il contenuto della proprietà margin/padding e restituisce il valore del margin/padding alto
	public static function getTop($stringa_valori) {
		
		$has_important = stripos ( $stringa_valori, "!important" );
		//if there 'remove !important - se c'e' rimuovo !important
		if ($has_important !== false) {
			$stringa_valori = str_ireplace ( "!important", "", $stringa_valori );
			$stringa_valori = trim ( $stringa_valori );
		}
		
		//$array_valori = split ( " ", $stringa_valori );
		$array_valori = preg_split ( "/ /", $stringa_valori );
		if (sizeof ( $array_valori ) <= 0)
			return "";
		else
			$val_ret = $array_valori [0]; //first value, then top - primo valore, quindi top
		
		if ($has_important === false)
			return $val_ret;
		else
			return "" . $val_ret . " !important";
	
	}
	// gets the contents of the property margin / padding and returns the value of the margin / padding bottom
	//riceve il contenuto della proprietà margin/padding e restituisce il valore del margin/padding basso
	public static function getBottom($stringa_valori) {
		
		$has_important = stripos ( $stringa_valori, "!important" );
		//if there 'remove !important - se c'e' rimuovo !important
		if ($has_important !== false) {
			$stringa_valori = str_ireplace ( "!important", "", $stringa_valori );
			$stringa_valori = trim ( $stringa_valori );
		}
		
		//$array_valori = split ( " ", $stringa_valori );
		$array_valori = preg_split ( "/ /", $stringa_valori );
		$size = sizeof ( $array_valori );
		if ($size <= 0)
			return "";
		else {
			if ($size >= 3)
				$val_ret = $array_valori [2]; //thied value, then bottom - terzo valore, quindi bottom
			else
				$val_ret = $array_valori [$size - 1]; //second or first value -secondo o primo valore	
		}
		
		if ($has_important === false)
			return $val_ret;
		else
			return "" . $val_ret . " !important";
	
	}
	// function to parameterize the search in the style sheets id, class or generic elements (tags).
	// $ marker contains "#", ". " or "" for id, respectively, classes, or generics.
	//funzione per parametrizzare la ricerca nei fogli di stile di id, class o elementi generici (tag).
	//$marker contiene "#", "." o "" rispettivamente per id, classi o elementi generici.
	//vecchia: public static function getElementStyleGeneric($e,$marker,$tag,$val,$idcss){
	public static function getElementStyleGeneric($e, $marker, $tag, $val, $pseudoClass = null) {
            
                
		global $selettori_appoggio;
		$info_proprieta = null;
		$elemento = $marker . $tag;
		
		//if(isset ($selettori_appoggio[$idcss][$marker.$tag]))
		if (isset ( $selettori_appoggio [$marker . $tag] )) {
			//$array_subset_selettori= $selettori_appoggio[$idcss][$marker.$tag];
			$array_subset_selettori = $selettori_appoggio [$marker . $tag];
			$info_proprieta = BasicChecks::get_proprieta ( $array_subset_selettori, $val, $e, $marker . $tag, $pseudoClass );
		}
		
		return $info_proprieta;
	
	}
	// returns the value of the property  'priority of' higher based on location or "! important"
	// for example is used to those rules that contain both the definition of margin  and margin-top
	//restituisce il valore della proprieta' di priorita' più alta in base alla posizione o a "!important"
	//ad esempio viene usata per quelle regole che contengono sia la definizione di margin che di margin-top
	public static function get_priority_prop($reg1, $reg2) {
		
		if (! isset ( $reg1 ))
			$valore_proprieta_new = $reg2 ["val"];
		elseif (! isset ( $reg2 ))
			$valore_proprieta_new = $reg1 ["val"];
		elseif (stripos ( $reg1 ["val"], "!important" ) === false && stripos ( $reg2 ["val"], "!important" ) === false) {
			if ($reg1 ["pos"] > $reg2 ["pos"])
				$valore_proprieta_new = $reg1 ["val"];
			else
				$valore_proprieta_new = $reg2 ["val"];
		} elseif (stripos ( $reg1 ["val"], "!important" ) !== false) {
			$valore_proprieta_new = $reg1 ["val"];
		} else {
			$valore_proprieta_new = $reg2 ["val"];
		}
		
		return $valore_proprieta_new;
	}
	/*
	 $ array_subset_selettori contains all the rules (simple and compound) that ultimately
	position of the selectors of the rule (eg for elem p: p {} div> p {}. class {p}), the element
	$ elemento_radice (ie, a tag, id or class)
	$ val = property to search
	e_original = $item itself, necessary to verify the association of rules made,
	checking the children ($ e-> parent () for "or "> ", $ e-> prev_sibling () for" + ")
	
	$array_subset_selettori contiene tutte le regole (semplici e composte) che hanno in ultima
	posizione dei selettori della regola (es per elem p: p{}, div>p{}, .class p{}) l'elemento
	$elemento_radice (cioè un tag, un id o un class)
	$val= prorieta' da ricercare
	$e_original = l'elemento vero e proprio, necessario per verificare l'associazione delle regole composte,
	verificando le discendenze ($e->parent() per " " o ">", $e->prev_sibling() per "+")
	*/
	public static function get_proprieta($array_subset_selettori, $val, $e_original, $elem_radice, $pseudoClass = null) {
	
	
		global $selettori_appoggio;
		$valore_proprieta = null;
		$num_id = 0;
		$num_class = 0;
		$num_tag = 0;
		$num_regola = 0;
	
	
		// use the foreach to track the location of the rule priority associated with $elem_radice
		//lo uso nel foreach per tenere traccia della posizione della regola di priorita' maggiore associata a $elem_radice
	
	
		$spazio = "{_}"; // used for cases in which a space between the two is significant. eg: "div.class" and "div .class"
		$mio_cont_test = 0;				//serve per i casi in cui uno spazio tra due elementi è significativo. es: "div.class" e "div .class"
		foreach ( $array_subset_selettori as $array_regole ) {
	
	
			// Check if [$regalo]['regole'] contained the property' $val and store it in $valore_proprieta_new
			// use a case for special properties like margin and padding
			// for these properties' function BasicChecks: get_priority_prop consider what property has priority more
			// eg between margin and margin-top (that is, if one then overwrite the other)
	
			//verifico se in [$regola]["regole"] e' contenuta la proprieta' $val e la memorizzo in $valore_proprieta_new
			//uso un case per le proprietà particolari come margin e padding
			//per queste proprieta' la funzione BasicChecks::get_priority_prop valuta quale proprieta' ha la priorita' maggiore
			//ad es tra margin-top e margin (cioe' se una delle due "sovrascrive" l'altra)
				
	
			$num_id_new = 0;
			$num_class_new = 0;
			$num_tag_new = 0;
			$num_pseudoclass_new = 0;
			$num_attr_name_new = 0;
			$num_attr_value_new = 0;
			$valore_proprieta_new = null;
	
	
			// NOTE: This switch may be included in a function also reused getElementStyleInline
			//NOTA: questo switch potrebbe essere incluso in una funzione riutilizzata anche da getElementStyleInline
			switch ($val) {
	
				case "margin-top" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["margin"] ))
						$valore_proprieta_new = BasicChecks::getTop ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["margin"] ) );
					break;
	
				case "margin-bottom" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["margin"] ))
						$valore_proprieta_new = BasicChecks::getBottom ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["margin"] ) );
					break;
	
				case "margin-left" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["margin"] ))
						$valore_proprieta_new = BasicChecks::getLeft ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["margin"] ) );
					break;
	
				case "margin-right" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["margin"] ))
						$valore_proprieta_new = BasicChecks::getRight ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["margin"] ) );
					break;
	
				case "padding-top" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["padding"] ))
						$valore_proprieta_new = BasicChecks::getTop ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["padding"] ) );
					break;
	
				case "padding-bottom" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["padding"] ))
						$valore_proprieta_new = BasicChecks::getBottom ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["padding"] ) );
					break;
	
				case "padding-left" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["padding"] ))
						$valore_proprieta_new = BasicChecks::getLeft ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["padding"] ) );
					break;
	
				case "padding-right" :
					if (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["padding"] ))
						$valore_proprieta_new = BasicChecks::getRight ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["padding"] ) );
					break;
	
				case "font-size" :
					if (isset ( $array_regole ["regole"] ["font"]) && stripos(trim($array_regole ["regole"] ["font"]['val']),' ')!==false  && isset ( $array_regole ["regole"] [$val]))
						$valore_proprieta_new = BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["font"] );
					elseif (isset ( $array_regole ["regole"] [$val] ))
					$valore_proprieta_new = $array_regole ["regole"] [$val]['val'];
					elseif (isset ( $array_regole ["regole"] ["font"] ) && stripos(trim($array_regole ["regole"] ["font"]['val']),' ')!==false)
					$valore_proprieta_new = $array_regole ["regole"] ['font']['val'];
	
					$valore_proprieta_new = trim($valore_proprieta_new);
	
					if(stripos($valore_proprieta_new,  ' ') !== false)
					{
						//handling "font"
						//get font-size from font
						//remove double spaces, ", " and " ,"
	
						$font = $valore_proprieta_new;
						$valore_proprieta_new = null;
						//remove font-family values with spaces
						$font = preg_replace('/"(.|\s)*"/', '__', $font);
						while (stripos($font,'  ') !== false)
							$font = str_replace ('  ', ' ', $font);
						$font = str_replace (', ', ',', $font);
						$font = str_replace (' ,', ',', $font);
						$font = explode(' ', $font);
						if(sizeof($font) >= 2)
						{
							$valore_proprieta_new = $font[sizeof($font)-2];
							$valore_proprieta_new = explode('/', $valore_proprieta_new);
							$valore_proprieta_new = $valore_proprieta_new[0];
						}
	
					}
					break;
	
				case "font-weight" :
					if (isset ( $array_regole ["regole"] ["font"]) && stripos(trim($array_regole ["regole"] ["font"]['val']),' ')!==false  && isset ( $array_regole ["regole"] [$val]))
						$valore_proprieta_new = BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["font"] );
					elseif (isset ( $array_regole ["regole"] [$val] ))
					$valore_proprieta_new = $array_regole ["regole"] [$val]['val'];
					elseif (isset ( $array_regole ["regole"] ["font"] ) && stripos(trim($array_regole ["regole"] ["font"]['val']),' ')!==false)
					$valore_proprieta_new = $array_regole ["regole"] ['font']['val'];
	
					$valore_proprieta_new = trim($valore_proprieta_new);
	
					if(stripos($valore_proprieta_new,  ' ') !== false)
					{
						//handling "font"
						//get font-weight from font
						//remove double spaces, ", " and " ,"
	
						$font = $valore_proprieta_new;
						$valore_proprieta_new = null;
						//remove font-family values with spaces
						$font = preg_replace('/"(.|\s)*"/', '__', $font);
						while (stripos($font,'  ') !== false)
							$font = str_replace ('  ', ' ', $font);
						$font = str_replace (', ', ',', $font);
						$font = str_replace (' ,', ',', $font);
						$font = explode(' ', $font);
						if(sizeof($font) > 2)
						{
							for($j=0; $j< (sizeof($font) - 2); $j++ )
								if ($font[$j] == 'bold' || $font[$j] == 'bolder' || is_int($font[$j]) )
								{
									$valore_proprieta_new = $font[$j];
									$j = sizeof($font);
								}
						}
	
	
					}
					break;
	
					//				case "background-color" :
					//
					//					//verifico se c'e' un'immagine di sfondo, nel caso setto la proprietà a -1
					//					if (isset ( $array_regole ["regole"] ["background-image"] ))
							//						$valore_proprieta_new = "-1";
							//					elseif (isset ( $array_regole ["regole"] ["background"] ) && stripos ( $array_regole ["regole"] ["background"] ["val"], "url" ) !== false)
							//						$valore_proprieta_new = "-1";
							//					elseif (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["background"] ))
							//						$valore_proprieta_new = BasicChecks::getBgColor ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["background"] ) );
					//					break;
	
	
					case "background-image" :
						
					//verifico se c'e' un'immagine di sfondo, nel caso setto la proprietà a -1
									if (isset ( $array_regole ["regole"] ["background-image"] ))
						$valore_proprieta_new = "undetermined";
						elseif (isset ( $array_regole ["regole"] ["background"] ) && stripos ( $array_regole ["regole"] ["background"] ["val"], "url" ) !== false)
								$valore_proprieta_new = "undetermined";
								break;
	
								case "background-color" :
									
								//verifico se c'e' un'immagine di sfondo, nel caso setto la proprietà a -1
	
								if (isset ( $array_regole ["regole"] ["background"] ) && stripos ( $array_regole ["regole"] ["background"] ["val"], "url" ) !== false)
								$valore_proprieta_new = "undetermined";
								elseif (isset ( $array_regole ["regole"] [$val] ) || isset ( $array_regole ["regole"] ["background"] ))
								$valore_proprieta_new = BasicChecks::getBgColor ( BasicChecks::get_priority_prop ( $array_regole ["regole"] [$val], $array_regole ["regole"] ["background"] ) );
								break;
	
								default :
								if (isset ( $array_regole ["regole"] [$val] ))
								$valore_proprieta_new = $array_regole ["regole"] [$val] ["val"];
								break;
									
			}
				
				
	
			// if the value of a property  was found, confirm it can be applied to the element considered
			// se il valore di una proprieta è stato trovato verifico se puo' essere applicata all'elemento considerato
			if ($valore_proprieta_new != null) {
	
			if (stripos ( $elem_radice, "#" ) !== false)
				$num_id_new = 1;
			elseif (stripos ( $elem_radice, "." ) !== false)
				$num_class_new = 1;
				else
					$num_tag_new = 1;
	
					$size_of_regole = sizeof ( $array_regole ["prev"] );
					$i =1;
					if ($size_of_regole == 1) // the current rule is '"simple", there are no predecessors
					//la regola corrente e' "semplice", non ci sono predecessori
					{
					$res = true;
	
			} else  // the rule is '"compound" (ie: div > p a)
			//la regola e' "composta" (es: div > p a)
			{
				
					// verification takes into account that a compound rule takes precedence over a simple rule, even if it follows!
					// eg: "div > p {}" & "{p}" => to <div><p></p></ div> wins over "div > p {}"
			// check whether the item falls under the "compound"
				// if so, I check if [$ rule] ['rules'] contained the $ val
	
			//la verifica tiene conto che una regola composta ha priorità su una "semplice", anche se la semplice è successiva!
			//es: "div > p{}" & "p{}" => per <div><p></p></div> vince "div > p{}"
			//controllo se l'elemento rientra nella regola "composta"
			//se si, verifico se in [$regola]["regole"] � contenuta la propriet� $val
	
	
			$i = 1; //start from the first parent of the current element
			//inizio dal primo padre dell'elemento corrente
			$e = $e_original;
	
			$res = true;
	
			while ($e!=null && $e->tag!='root' && $e->tag != 'doctype' && $i < $size_of_regole && ($res !== false || ($res === false && isset($forks) && sizeof($forks) >0) )) {
	
			 
			// check if $res == false and I have others parent elements which could satisfy the current css rule
			//example: rule html *[an_attr] p {...} (which modified is: htm {_} [an_attr * {_} p)
			//in html code i have: <html> -> <body an_attr='...'> -> <div> <p>
			// 1) p is the current element and is the last element of the rule: OK
				// 2) div is a parent and matches *: OK
					// 3) div doesn't have an_attr: KO
						// 4) come back to * and check next parent, wich is body, which has an_attr: OK
						// 5) next parent is html and match the first element of the rule (html): OK
	
	
						if($res == false && isset($forks) && sizeof($forks)>0)
						{
						$fork = array_pop($forks);
								$e = $fork['e'];
						$i = $fork['i'];
						$pseudoClass = $fork['pseudoclass'];
								$num_class_new = $fork['num_class_new'];
						$num_id_new = $fork['num_id_new'];
						$num_pseudoclass_new = $fork['num_pseudoclass_new'];
								$num_attr_name_new = $fork['num_attr_name_new'];
								$num_attr_value_new = $fork['num_attr_value_new'];
			}
						// NOTE: This series of if / elseif and switch could be next
								// be merged into a single set of if / else
							// $ element can 'contain'> ',' + ', id, class, a tag
								//NOTA: questa serie di if/elseif e lo switch successivo potrebbero
						//essere unificati in un unica serie di if/else
							//$elemento puo' contenere '>', '+', un id, una classe un tag
								if ($array_regole ["prev"] [$i] == ">") {
								$tipo = ">";
			} elseif ($array_regole ["prev"] [$i] == "+") {
								$tipo = "+";
	
			} elseif ($array_regole ["prev"] [$i] == $spazio) {
							$tipo = "spazio";
						} elseif (stripos ( $array_regole ["prev"] [$i], "." ) !== false) //classe
						{
						$tipo = ".";
	
						} elseif (stripos ( $array_regole ["prev"] [$i], "#" ) !== false) //id
						{
						$tipo = "#";
						} elseif (stripos ( $array_regole ["prev"] [$i], '[' ) !== false) //attr
						{
						$tipo = "[";
						} elseif (stripos ( $array_regole ["prev"] [$i], ":" ) !== false) //pseudo class
						{
						$tipo = ":";
						} elseif (trim($array_regole ["prev"] [$i]) == "*") //any element
						{
						$tipo = "star";
	
						} else //tag
						{
						$tipo = "tag";
	
						}
	
	
	
	
						switch ($tipo) {
	
	
						case ":" :
	
	
						//                                                              if(':'.$pseudoClass == trim($array_regole ["prev"] [$i]) )
						//                                                              {
						//
						//                                                                  $res = true;
						//                                                              }
						//                                                              else
							//                                                              {
						//                                                                  $res = false;
							//
							//                                                              }
							$pseudoClassesArray = explode(':',substr($array_regole ["prev"] [$i], 1));
								$countTrue = 0;
								$res = false;
								for ($pCCount=0; $pCCount < sizeof( $pseudoClassesArray); $pCCount ++)
								{
								$pC = $pseudoClassesArray[$pCCount];
	
								if($pC == 'first-child')
							{
	
								if($e->prev_sibling() == null)
								{
								$num_pseudoclass_new++;
								$countTrue++;
			}
								}
									elseif ($pC == 'link'){
									if($e->tag == 'a')
									{
									$num_pseudoclass_new ++;
										$countTrue++;
	
									}
									}
									elseif(stripos($pC,'lang') ===0)
										{
										$lang = str_replace('lang(', '', $pC);
										$lang = str_replace(')', '', $lang);
										//check if the element or a perent has the attribute lang
										$tempE = $e;
										$langFound =false;
	
											while($tempE != null && $langFound == false )
												{
													if(isset ($tempE->attr['lang']) && $tempE->attr['lang'] == $lang)
														$langFound = true;
														$tempE = $tempE->parent();
										}
										if($langFound == true)
										{
										$num_pseudoclass_new++;
										$countTrue++;
	
										}
										else
											$res =false;
	
										}
										else
										{
	
	
										if($pseudoClass == $pC)
										{
	
										$num_pseudoclass_new++;
										$countTrue++;
										 
										}
										}
										}
	
	
										if($countTrue == sizeof( $pseudoClassesArray))// all pseudoclassess match
										{
	
										$res = true;
										}
										else
											$res = false;
										break;
	
										 
										case "[" :
	
										//casi: a[href], a[title][href="www.w3c.it"]
	
	
										$attrArray = explode('[',substr($array_regole ["prev"] [$i], 1));
	
										$res = false;
										for($aa=0;  $aa < sizeof($attrArray); $aa++)
										{
	
										$attr = $attrArray[$aa];
										$attr = str_replace('"', '', $attr);
										$attr = str_replace("'", '', $attr);
										$attrOp = null;
										if(stripos($attr, '=')!== false) //[attrName=attrValue]
										{
										$attr = explode('=',$attr);
										$attrName = $attr[0];
										$attrValue = $attr[1];
											$attrOp = '=';
										}
											elseif(stripos($attr, '~=')!== false) //[attrName~=attrValue]
											{
											$attr = explode('~=',$attr);
											$attrName = $attr[0];
											$attrValue = $attr[1];
											$attrOp = '~=';
										}
										elseif(stripos($attr, '|=')!== false) //[attrName|=attrValue]
												{
													$attr = explode('|=',$attr);
													$attrName = $attr[0];
													$attrValue = $attr[1];
													$attrOp = '|=';
													}
														else //[attrName]
															{
																$attrName = $attr;
													}
	
													if(isset($e->attr[$attrName]))
													{
	
													$res = true;
													$num_attr_name_new ++;
													if(isset($attrOp))
													{
													if($attrOp == '=')
													{
													if($e->attr[$attrName] == $attrValue)
														{
															$res = true;
																$num_attr_value_new ++;
													}
													else
													{
													$res = false;
													}
													}
														elseif($attrOp == '~=')
														{
														$attrValues = explode(' ',$e->attr[$attrName]);
	
														//setting ris to false to do for cicle
														$res = false;
														for($v = 0; $res === false, $v < sizeof($attrValues); $v++)
														{
														if($attrValues[$v] == $attrValue)
														{
														$res = true;
														$num_attr_value_new ++;
											}
											}
											}
												elseif($attrOp == '|=')
													{
														$attrValues = explode(' ',$e->attr[$attrName]);
	
														//setting ris to false to do for cicle
														$res = false;
														for($v = 0; $res === false, $v < sizeof($attrValues); $v++)
														{
														//  lang=en or lang=en-US
														if($attrValues[$v] == $attrValue || stripos($attrValues[$v],$attrValue.'-') ===0)
														{
														$res = true;
														$num_attr_value_new ++;
											}
											}
											}
											}
											}
											else
											{
											$res = false;
											}
											}
	
											break;
	
											case "star" :
											//do nothing, any element is ok
												$res = true;
													break;
	
													case "tag" :
													if($array_regole["prev"][$i] == $e->tag)
													{
													$res = true;
													$num_tag_new++;
											}
											else
												$res = false;
											break;
	
											case "." :
											$classes = trim($e->class);
											//removing doble spaces
											while (stripos($classes, '  '))
												$classes = str_replace('  ', ' ', $classes);
	
												$classes = explode(' ',$classes);
												$res = false;
												for($cc = 0; $res == false,$cc < sizeof($classes); $cc++)
													{
														if($array_regole["prev"][$i] == '.'.$classes[$cc])
															$res = true;
														$num_class_new++;
	
												}
												break;
	
												case "#" :
												if($array_regole["prev"][$i] == '#'.trim($e->id))
												{
												$res = true;
												$num_id_new++;
											}
											else
												$res = false;
											break;
	
	
											case ">" :
											if($e->parent()->tag != 'root')
												$e = $e->parent();
											else
												$res = false;
											break;
	
											case "+" :
											if($e->prev_sibling() != null)
												$e = $e->prev_sibling();
												else
													$res = false;
													break;
	
														case "spazio" :
														$res = false;
														//                                                                    if(($i+1 < sizeof($array_regole["prev"])))
															$pred = $array_regole ["prev"] [$i + 1];
															//                                                                    else
																//                                                                        $pred = null;
	
	
															 
	
															while(isset($pred) && $e->parent()->tag!= 'root' && $res !== true)
															{
															$e = $e->parent();
															//check if predecessor is a class, id or tag
															if($pred == '*')
															{
															$res = true;
	
										}
										elseif (stripos ( $pred, "." ) === 0) //class
										{
	
										$classes = trim($e->class);
										//removing doble spaces
										while (stripos($classes, '  '))
										{
	
										$classes = str_replace('  ', ' ', $classes);
	
										}
											$classes = explode(' ',$classes);
	
												for($cClass = 0; $res == false,$cClass < sizeof($classes); $cClass++)
												{
												if('.'.$classes[$cClass] == $pred)
													$res = true;
										}
										 
										}
										elseif (stripos ( $pred, "#" ) === 0) //id
												{
												if('#'.$e->id == $pred)
													$res = true;
										}
										else //tag
										{
											if($e->tag == $pred)
												$res = true;
										}
	
										}
										 
	
										if($e->parent()->tag != 'root' && $res == true)
										{
										$fork['i'] = $i;
										$fork['e'] = $e;
										$fork['pseudoclass'] = $pseudoClass;
										$fork['num_class_new'] = $num_class_new;
										$fork['num_id_new'] = $num_id_new;
										$fork['num_pseudoclass_new'] = $num_pseudoclass_new;
										$fork['num_attr_name_new'] = $num_attr_name_new;
										$fork['num_attr_value_new'] = $num_attr_value_new;
	
										if(!isset($forks))
											$forks = array();
	
										array_push($forks, $fork);
										}
	
										break;
	
	
										} //end switch
										$i ++;
											
										} //end while
	
	
										} //end else regola composta - compound rule
	
	
	
											//                                if($pseudoClass != null)//it means I was searching a pseudoclass (ie a:hover), but I didn't find it
											//                                {
											//                                   $res =false;
											//
											//                                }
	
											$forks = array();
											$old_best = $num_regola_best;
											if ($res === true && $i == $size_of_regole) { // analyze and apply the new rule
											// check if the priority of the new greater than previous
											//la nuova regola analizzata è applicabile
											//controllo se la priorita della nuova supera quella della precedente
												//echo("<p>".$array_regole ["prev_string"]."</p>");
	
												if (stripos ( $valore_proprieta_new, "!important" ) !== false && stripos ( $valore_proprieta, "!important" ) === false) {
												// $proprieta is not !important while $proprietà_new is, then override $proprieta
												//$proprieta non è !important mentre $proprietà_new si, quindi sovrascrivo $proprieta
												$valore_proprieta = $valore_proprieta_new;
												$num_id = $num_id_new;
												$num_class = $num_class_new;
												$num_tag = $num_tag_new;
												$num_pseudoclass = $num_pseudoclass_new;
													$num_attr_name = $num_attr_name_new;
													$num_attr_value = $num_attr_value_new;
													$num_regola_best = $num_regola;
												} elseif (stripos ( $valore_proprieta_new, "!important" ) === false && stripos ( $valore_proprieta, "!important" ) === false || stripos ( $valore_proprieta_new, "!important" ) !== false && stripos ( $valore_proprieta, "!important" ) !== false)
	
												// have both  !important or neither is, then check the id
												// hanno entrambe !important o non lo hanno nessuna della due, quindi verifico il numeo di id
												{
												//b
												if ($num_id_new > $num_id) {
													$valore_proprieta = $valore_proprieta_new;
													$num_id = $num_id_new;
													$num_class = $num_class_new;
													$num_tag = $num_tag_new;
													$num_pseudoclass = $num_pseudoclass_new;
													$num_attr_name = $num_attr_name_new;
													$num_attr_value = $num_attr_value_new;
													$num_regola_best = $num_regola;
												} elseif ($num_id_new == $num_id) { // same ID number, control the number of class
												//stesso numero di id, controllo il numero di class
													
	
												//							if ($num_class_new > $num_class) {
												//								$valore_proprieta = $valore_proprieta_new;
													//								$num_id = $num_id_new;
														//								$num_class = $num_class_new;
														//								$num_tag = $num_tag_new;
														//                                                                $num_pseudoclass = $num_pseudoclass_new;
														//                                                                $num_attr_name = $num_attr_name_new;
															//                                                                $num_attr_value = $num_attr_value_new;
																//								$num_regola_best = $num_regola;
																//							} elseif ($num_class_new == $num_class) { // same id and class number, check number of tags
																//stesso numero di id e class, controllo il numero di tag
	
																//c
																if ($num_class_new + $num_pseudoclass_new + $num_attr_name_new + $num_attr_value_new > $num_class+ $num_pseudoclass + $num_attr_name + $num_attr_value) { // greater number of id, class and :link pseudoclasses: is the priority of the new rule
																	$valore_proprieta = $valore_proprieta_new;
																	$num_id = $num_id_new;
																	$num_class = $num_class_new;
																	$num_tag = $num_tag_new;
																		$num_pseudoclass = $num_pseudoclass_new;
																		$num_attr_name = $num_attr_name_new;
																		$num_attr_value = $num_attr_value_new;
																		$num_regola_best = $num_regola;
																	}elseif($num_class_new + $num_pseudoclass_new + $num_attr_name_new + $num_attr_value_new == $num_class + $num_pseudoclass + $num_attr_name + $num_attr_value)
																	{
																	//d
																		if($num_tag_new >= $num_tag) //same or greater number of id, class ":link" pseudoclasses and tag:is the priority of the new rule
																	{
																	$valore_proprieta = $valore_proprieta_new;
																	$num_id = $num_id_new;
																	$num_class = $num_class_new;
																	$num_tag = $num_tag_new;
																	$num_pseudoclass = $num_pseudoclass_new;
																		$num_attr_name = $num_attr_name_new;
																		$num_attr_value = $num_attr_value_new;
																		$num_regola_best = $num_regola;
	
																	}
																	}
																		
																	//							}
																	}
																	}
																		
	
																	$valore_proprieta_new = null;
																	}
																		
																	}
																		
	
																	//eliminare
																	//                        echo("<p>vince</p>");
																	//                        echo($array_subset_selettori [$num_regola_best]);
																		//                        echo("<p>contro</p>");
																			//                        echo($array_subset_selettori [$num_regola]);
	
	
																			$num_regola ++;
	
	
																			}
	
																				if ($valore_proprieta == null)
																					return null;
	
																					// create the structure info_proprieta
																					// store the id number, class and tag  necessary to verify the priority
																					// rules are starting from an id, a class or a tag
																					// it is not always a rule that has the selectors as the last (or only) a descendant of id or a class that takes
																					// ends with a tag
	
																					//creo la struttura info_proprieta'
																					//memorizzare il numero di id, class e tag è necessario per verificare la priorita'
																						//delle regole trovate partendo da un id, una class o un tag
																						// non sempre infatti una regola che nei selettori ha come ultimo (o unico) discendente un id o class batte una che
																						//termina con un tag
																						//es:
																						// "#id2 p{}" batte "#id1{}" (se p contiene id="id1")
	
	
																						$info_proprieta = array ("valore" => $valore_proprieta, "num_id" => $num_id, "num_class" => $num_class, "num_tag" => $num_tag, "num_pseudoclass" => $num_pseudoclass, "num_attr_name" => $num_attr_name, "num_attr_value" => $num_attr_value, "css_rule" => $array_subset_selettori [$num_regola_best] );
																							//echo("<p>regola per ". $elem_radice."</p>");
																							//print_r($array_subset_selettori[$num_regola-1]);
	
	
																							return $info_proprieta;
																						}
																						
																						
	// reorganize the style sheets, starting from the array of Filippo, in a data structure more 'structured
	//riorganizzo i fogli di stile, partendo dagli array di Filippo, in una struttura dati piu' articolata
	public static function setCssSelectors($content_dom) {
		
			
		
		global $selettori;
		global $attributo_selettore;
		global $selettori_appoggio;
		global $array_css;
		global $flag_selettori_appoggio;
                global $csslist;
		$array_css = array ();
		//echo("<p>entro in cssselector</p>");
		
		//echo("<p>isset selettori_appoggio=".isset ($selettori_appoggio)."</p>");
		//print_r($selettori_appoggio);
		
		if (isset ( $flag_selettori_appoggio )) {
			
			//return ($selettori_appoggio);
			
			return true;
		} else {
			$flag_selettori_appoggio = true;
		}
		
		
		$spazio = "{_}";
		$csslist = BasicChecks::get_style_external ( $content_dom );
		$cssinternal = BasicChecks::get_style_internal ( $content_dom );
		//echo("<p>Stampo la lista di stili</p>");
		//print_r($csslist);
		//print_r($cssinternal);
		// MB I create the data structure containing the css information
		//MB creo la struttura dati contenente i dati dei css
		BasicChecks::prepare_css_arrays ($csslist, $cssinternal );
			
                
		//fine parte in prova - end part in test
		$size_of_selettori = sizeof ( $selettori );
		for($idcss = 0; $idcss < $size_of_selettori; $idcss ++) {
			
			//$selettori_appoggio[$idcss] =array();
			for($i = 0; $i < count ( $selettori [$idcss] ); $i ++) {
				$sel_string = str_ireplace ( '{', '', $selettori [$idcss] [$i] ); //rimuovo "{"
				
				
				$selettori_array = explode ( ',', $sel_string );  // create an array of selectors that are separated by ", "
																//creo un array dei selettori che sono separati da ","
				foreach ( $selettori_array as $sel ) {
					$sel = trim ( $sel );
                                        $selectorsString = $sel;
                                        
                                        $sel = str_ireplace ( '>', ' > ', $sel ); // metto gli spazi tra i ">"
                                        $sel = str_ireplace ( '+', ' + ', $sel ); // metto gli spazi tra i "+"
                                        $sel = str_ireplace ( '=', ' = ', $sel ); // metto gli spazi tra i "="
                                        $sel = str_ireplace ( '~=', ' ~= ', $sel ); // metto gli spazi tra i "~="
                                        $sel = str_ireplace ( '|=', ' |= ', $sel ); // metto gli spazi tra i "~="
                                        while ( stripos ( $sel, '  ' ) !== false ) //rimuovo gli spazi multipli
                                        {
                                                $sel = str_ireplace ( '  ', ' ', $sel );
                                                //echo ("<p>sel_string =".$sel."</p>");
                                        }
                                        $sel = str_ireplace ( ' > ', '>', $sel );
                                        $sel = str_ireplace ( ' + ', '+', $sel );
                                        $sel = trim(str_ireplace ( ' , ', ',', $sel ));
                                        $sel = str_ireplace ( ' ~= ', '~=', $sel ); // rimuovo gli spazi tra i "~="
                                        $sel = str_ireplace ( ' |= ', '|=', $sel ); // rimuovo gli spazi tra i "~="
                                        $sel = str_ireplace ( '( ', '(', $sel ); // rimuovo gli spazi tra "("; for lang(...)
                                        $sel = str_ireplace ( ' )', ')', $sel ); // rimuovo gli spazi tra ")"; for lang(...)
                                        $sel = str_ireplace ( ' ', ' ' . $spazio . ' ', $sel ); // put $spazio				
                                        $sel = str_ireplace ( '>', ' > ', $sel ); // metto gli spazi tra i ">"
                                        $sel = str_ireplace ( '+', ' + ', $sel ); // metto gli spazi tra i "+"                                 
                                        //$sel = str_ireplace ( ':', ' :', $sel ); // metto gli spazi tra i ":"
                                        // use the $spazio symbol to indicate that  an id or class is preceded by a space
                                        // in fact "p.nome_classe" is different from "p .nome_classe"
                                        //uso il simbolo $spazio e per indicare che un id o una classe e' preceduta da uno spazio
                                        //infatti "p.nome_classe" è diverso da "p .nome_classe"
                                        $sel = str_ireplace ( '.', ' .', $sel ); //put a space before - metto uno $spazio prima di "."
                                        $sel = str_ireplace ( '#', ' #', $sel ); // metto uno $spazio prima di "#"
                                        $sel = str_ireplace ( ':', ' :', $sel ); // metto uno $spazio prima di ":"
                                        $sel = str_ireplace ( ' ' . $spazio . ' [', ' ' . $spazio . ' * [', $sel ); 
                                        $sel = str_ireplace ( ' ' . $spazio . ' :', ' ' . $spazio . ' * :', $sel );  
                                        $sel = str_ireplace ( '[', ' [', $sel ); // metto gli spazi tra i "["                                
                                        $sel = str_ireplace ( '] [', '[', $sel ); // sostituisco "] [" con "["
                                        $sel = str_ireplace ( ']', '', $sel ); // remove "]"                                
                                        while ( stripos ( $sel, '  ' ) !== false ) //rimuovo gli spazi multipli
                                        {
                                                $sel = str_ireplace ( '  ', ' ', $sel );
                                                //echo ("<p>sel_string =".$sel."</p>");
                                        }                                          
                                        
                                        
                                        
					//rimuovo eventuali $spazio all'inizio della stringa (remove spaces from beginning of strings )
					$sel = preg_replace ( "/^" . $spazio . "/", "", $sel );
					//rimuovo eventuali $spazio alla fine della stringa (remove spaces from ends of strings)
					$sel = preg_replace ( "/" . $spazio . "$/", "", $sel );
					$sel = trim ( $sel );
                                        
                                        
					//$selettore_array = split ( " ", $sel );                                        
					$selettore_array = preg_split ( "/ /", $sel );
                                        //edit selectors in this way: a [href] :hover ---> [href] a :hover --> [href] :hover a 
                                        if(sizeof($selettore_array) >1)
                                        {
                                                for($kk = 0; $kk< sizeof($selettore_array); $kk++)
                                                {
                                                    if(stripos($selettore_array[$kk], '[') === 0 )
                                                    {
                                                        $temp = $selettore_array[$kk-1];
                                                        $selettore_array[$kk-1] = $selettore_array[$kk];
                                                        $selettore_array[$kk] = $temp;
                                                        //if a.someclass[href]; a.someid[href]
                                                        //edit selectors in this way: a#someid [href] ---> [href] a#someid 
                                                        if($kk >1 && (stripos($selettore_array[$kk],'.') === 0 || stripos($selettore_array[$kk],'#') === 0)
                                                                && stripos($selettore_array[$kk-2],'.') !== 0 
                                                                && stripos($selettore_array[$kk-2],'#') !== 0 
                                                                && $selettore_array[$kk-2] != '{_}'
                                                                && $selettore_array[$kk-2] != '>'
                                                                && $selettore_array[$kk-2] != '+')
                                                        {
                                                            $temp = $selettore_array[$kk-2];
                                                            $selettore_array[$kk-2] = $selettore_array[$kk-1];
                                                            $selettore_array[$kk-1] = $temp;
                                                        }
                                                        $kk++;
                                                    }
                                                }
                                                
                                                for($kk = 0; $kk< sizeof($selettore_array); $kk++)
                                                {
                                                    if( stripos($selettore_array[$kk], ':') === 0)
                                                    {
                                                        $temp = $selettore_array[$kk-1];
                                                        $selettore_array[$kk-1] = $selettore_array[$kk];
                                                        $selettore_array[$kk] = $temp;
                                                        //if a.someclass:hover; a.someid:hover
                                                        //edit selectors in this way: a#someid :hover --->  :hover a#someid
                                                        if($kk >1 && (stripos($selettore_array[$kk],'.') === 0 || stripos($selettore_array[$kk],'#') === 0)
                                                                && stripos($selettore_array[$kk-2],'.') !== 0 
                                                                && stripos($selettore_array[$kk-2],'#') !== 0 
                                                                && $selettore_array[$kk-2] != '{_}'
                                                                && $selettore_array[$kk-2] != '>'
                                                                && $selettore_array[$kk-2] != '+')
                                                        {
                                                            $temp = $selettore_array[$kk-2];
                                                            $selettore_array[$kk-2] = $selettore_array[$kk-1];
                                                            $selettore_array[$kk-1] = $temp;
                                                        }
                                                        $kk++;
                                                    }
                                                }                                                
                                        }
					// in the final position of $selettore_array ????
					//nell'ultima posizione di $selettore_array c'è il selettore piu' a dx prima di una "," o di "{" 
					$size_of_selettore = sizeof ( $selettore_array ) - 1;
					$last = $selettore_array [$size_of_selettore]; //ultimo elemento a dx, es: "div > p br" ---> br 
					
					$array_appoggio = array ();
					$array_appoggio ["idcss"] = $idcss;
					$array_appoggio ["posizione"] = $i;
					//"regole" contiene: $prorieta =>valore
					$regole = $attributo_selettore [$idcss] [$i];
					
					if (sizeof ( $regole ) > 0) {
						$pos_prop = 0;
						foreach ( $regole as $regola ) {
							
							//print_r($array_appoggio);							
							$regola = trim ( $regola );
							$regola = explode ( ":", $regola );
							if (sizeof ( $regola == 2 )) {
								$proprieta = trim ( $regola [0] );
								$valore = trim ( $regola [1] );
								
								if (! isset ( $array_appoggio ["regole"] [$proprieta] )) {
									$array_appoggio ["regole"] [$proprieta] ["val"] = $valore;
									$array_appoggio ["regole"] [$proprieta] ["pos"] = $pos_prop;
								} elseif (stripos ( $array_appoggio ["regole"] [$proprieta] ["val"], "!important" ) !== false) //la propriet  gi stata impostata ed  !important, la posso sovrascrivere solo se anche quella che sto analizzando  !important
								{
									if (stripos ( $valore, "!important" ) !== false)
										$array_appoggio ["regole"] [$proprieta] ["val"] = $valore;
									$array_appoggio ["regole"] [$proprieta] ["pos"] = $pos_prop;
								} else {
									$array_appoggio ["regole"] [$proprieta] ["val"] = $valore;
									$array_appoggio ["regole"] [$proprieta] ["pos"] = $pos_prop;
								}
							
							}
							$pos_prop ++;
						
						}
					}
                                        
					//memorizzo i "predecessori". es: il selettore =" div > p br", allora i predecessori di br (considero anche br stesso) sono br, p, > e div. li memorizzo da dx a sx
					for($j = $size_of_selettore, $k = 0; $j >= 0; $j --, $k ++) {
						$array_appoggio ["prev"] [$k] = $selettore_array [$j];
					}
					
                                        $array_appoggio ["prev_string"] = $selectorsString;
                                        
					//if(isset($selettori_appoggio[$idcss][$last])) //ho gi inserito questo elemento (tag, id, class) almeno una volta 
					if (isset ( $selettori_appoggio [$last] )) //ho gia' inserito questo elemento (tag, id, class) almeno una volta 
					{
						$posizione = sizeof ( $selettori_appoggio [$last] );
						$selettori_appoggio [$last] [$posizione] = $array_appoggio;
					} else {
						$selettori_appoggio [$last] [0] = $array_appoggio;
					}
				}
			}
		}  
                return true;
	}
	
	// Function to search within a particular attribute associated with an id tag
	//Funzione che ricerca un determinato attributo all'interno dell'id associato ad un tag
	public static function GetElementStyleId($e, $id, $val, $pseudoClass = null) {
		
		return BasicChecks::getElementStyleGeneric ( $e, '#', $id, $val, $pseudoClass );
	}
	// A function that searches for a particular attribute within the class associated with a tag in an external style sheet
	//Funzione che ricerca un determinato attributo all'interno della class associata ad un tag, in un foglio di stile esterno
	public static function GetElementStyleClass($e, $class, $val, $pseudoClass = null) {
		return BasicChecks::getElementStyleGeneric ( $e, '.', $class, $val, $pseudoClass );
	}
	// A function that searches for a particular attribute in a tag identified by the selector in an external style sheet
	//Funzione che ricerca un determinato attributo all'interno di un selettore identificato con il tag in un foglio di stile esterno
	public static function GetElementStyle($e, $child, $val, $pseudoClass = null) {
               
		//return BasicChecks::getElementStyleGeneric($e,'',$child,$val,$idcss);
		return BasicChecks::getElementStyleGeneric ( $e, '', $child, $val, $pseudoClass );
	}
	// Function for requirement 21 which retrieves the values of them away Vetical
	//Funzione per il requsitio 21 che recupera i valori di distanza veticali di un li
	public static function GetVerticalDistance($e) {
		
		global $m_bottom;
		global $p_bottom;
		global $m_top;
		global $p_top;
		
		$m_bottom = "";
		$p_bottom = "";
		$m_top = "";
		$p_top = "";
		
		$m_bottom = BasicChecks::get_p_css ( $e->prev_sibling (), "margin-bottom" );
		$p_bottom = BasicChecks::get_p_css ( $e->prev_sibling (), "padding-bottom" );
		$m_top = BasicChecks::get_p_css ( $e, "margin-top" );
		$p_top = BasicChecks::get_p_css ( $e, "padding-top" );
		
		$m_bottom = trim ( str_ireplace ( "!important", "", $m_bottom ) );
		$p_bottom = trim ( str_ireplace ( "!important", "", $p_bottom ) );
		$m_top = trim ( str_ireplace ( "!important", "", $m_top ) );
		$p_top = trim ( str_ireplace ( "!important", "", $p_top ) );
	
	}
	// Function for requirement 21 which retrieves the values of a horizontal distance of them
	//Funzione per il requsitio 21 che recupera i valori di distanza orizzontali di un li
	public static function GetHorizontalDistance($e) {
		
		global $m_left;
		global $p_left;
		global $m_right;
		global $p_right;
		
		$m_left = "";
		$p_left = "";
		$m_right = "";
		$p_right = "";
		
		$m_right = BasicChecks::get_p_css ( $e->prev_sibling (), "margin-right" );
		$p_right = BasicChecks::get_p_css ( $e->prev_sibling (), "padding-right" );
		$m_left = BasicChecks::get_p_css ( $e->prev_sibling (), "margin-left" );
		$p_left = BasicChecks::get_p_css ( $e->prev_sibling (), "padding-left" );
		
		$m_right = trim ( str_ireplace ( "!important", "", $m_right ) );
		$p_right = trim ( str_ireplace ( "!important", "", $p_right ) );
		$m_left = trim ( str_ireplace ( "!important", "", $m_left ) );
		$p_left = trim ( str_ireplace ( "!important", "", $p_left ) );
	
	}
	// Function for requirement 21 which retrieves the values of distance down the listings vetical
	//Funzione per il requsitio 21 che recupera i valori di distanza veticali basso delle liste
	public static function GetVerticalListBottomDistance($tag) {
		
		global $m_bottom;
		global $p_bottom;
		$m_bottom = "";
		$p_bottom = "";
		
		$m_bottom = BasicChecks::get_p_css ( $tag, "margin-bottom" );
		$p_bottom = BasicChecks::get_p_css ( $tag, "padding-bottom" );
		$m_bottom = trim ( str_ireplace ( "!important", "", $m_bottom ) );
		$p_bottom = trim ( str_ireplace ( "!important", "", $p_bottom ) );
	
	}
	// Function for requirement  21 which retrieves the values of distance Vetical top of the lists
	//Funzione per il requsitio 21 che recupera i valori di distanza veticali alto delle liste
	public static function GetVerticalListTopDistance($tag) {
		
		global $m_top;
		global $p_top;
		$m_top = "";
		$p_top = "";
		$m_top = BasicChecks::get_p_css ( $tag, "margin-top" );
		$p_top = BasicChecks::get_p_css ( $tag, "padding-top" );
		$m_top = trim ( str_ireplace ( "!important", "", $m_top ) );
		$p_top = trim ( str_ireplace ( "!important", "", $p_top ) );
	
	}
	// Function for requirment 21 which retrieves the values of horizontal distance from the left of the lists
	//Funzione per il requsitio 21 che recupera i valori di distanza orizzontale sinistra delle liste
	public static function GetHorizontalListLeftDistance($tag) {
		
		global $m_left;
		global $p_left;
		$m_left = "";
		$p_left = "";
		$m_left = BasicChecks::get_p_css ( $tag, "margin-left" );
		$p_left = BasicChecks::get_p_css ( $tag, "padding-left" );
		$m_left = trim ( str_ireplace ( "!important", "", $m_left ) );
		$p_left = trim ( str_ireplace ( "!important", "", $p_left ) );
	}
	// Function for requirment 21 which retrieves the values of horizontal distance right of the list
	//Funzione per il requsitio 21 che recupera i valori di distanza orizzontale destra delle liste
	public static function GetHorizontalListRightDistance($tag) {
		
		global $m_right;
		global $p_right;
		$m_right = "";
		$p_right = "";
		$m_right = BasicChecks::get_p_css ( $tag, "margin-right" );
		$p_right = BasicChecks::get_p_css ( $tag, "padding-right" );
		$m_right = trim ( str_ireplace ( "!important", "", $m_right ) );
		$p_right = trim ( str_ireplace ( "!important", "", $p_right ) );
	}
	
	
	public static function getForeground($e, $pseudoClass = null) {
		// Find the value of foreground explicitly defined for the element $e
		//cerco il valore di foreground esplicitamente definito per l'elemento $e
		$foreground = BasicChecks::get_p_css ( $e, "color", $pseudoClass );
		// links do not inherit the "color"defined style
		//i link non ereditano "color" definito in style
		if ($foreground == "" && $e->tag == "a")
			return $foreground;
		// for the normal elements if foreground == "" means that the value is not defined for $e: Searches its parents
		//per gli elementi normali se foreground == "" significa che il valore non è stato definito per $e: ricerco tra i suoi genitori
		while ( ($foreground == "" || $foreground == null) && $e->tag != null && $e->tag != "body" && $e->tag != "html") {
			$e = $e->parent ();
			$foreground = BasicChecks::get_p_css ( $e, "color", $pseudoClass );
		}
		// if a foreground, is found, check if it is defined in the body, if not check the if it is  black
		// NOTE: must be added to the control link, alink, ...
		//se non trovo nessun foreground, controllo se è definito nel body, se no gli assegno il nero
		//NOTA: va aggiunto il controllo su link, alink, ...
		if ($foreground == "" || $foreground == null) {
			if (($e->tag == "body" || $e->tag == "html") && isset ( $e->attr ["text"] ))
				$foreground = $e->attr ["text"];
			else
				$foreground = "#000000";
		}
		
		$foreground = str_replace ( "'", "", $foreground );
		$foreground = str_replace ( "\"", "", $foreground );
		$foreground = str_replace ( "!important", "", $foreground );
		return $foreground;
	
	}
	
	public static function getBackground($e, $pseudoClass = null) {
		// Find the value of explicitly defined background for the element $ e
		//cerco il valore di background esplicitamente definito per l'elemento $e
            
            
                $bgImage = BasicChecks::get_p_css ( $e, "background-image", $pseudoClass );
                if($bgImage != '')
                    return "undetermined";
                        
                
		$background = BasicChecks::get_p_css ( $e, "background-color", $pseudoClass );
                
		// if background == "" means that the value is not defined for $e: Searches its parents
		//se background == "" significa che il valore non è stato definito per $e: ricerco tra i suoi genitori
		while ( ($background == "" || $background == null) && $e->tag != null && $e->tag != "body" && $e->tag != "html") {
			$e = $e->parent ();
			
                        $bgImage = BasicChecks::get_p_css ( $e, "background-image", $pseudoClass );
                        if($bgImage != '')
                            return "undetermined";
                        
			$background = BasicChecks::get_p_css ( $e, "background-color" , $pseudoClass);
			if ($background == "" || $background == null) //controllo se c'e bgcolor che ha priorita' inferiore dello stile
			{
				if (($e->tag == "table" || $e->tag == "tr" || $e->tag == "td") && isset ( $e->attr ["bgcolor"] ))
					$background = $e->attr ["bgcolor"];
			}
			// if the element has an absolute position and background not defined (default: transparent)
			//se l'elemento ha posizione assoluta e background non definito (default:transparent)
			if (BasicChecks::get_p_css ( $e, "position", $pseudoClass ) == "absolute" && ($background == "" || $background == null))
				$background = - 1;
		}
		
		// if I find any background check that is defined within the body, if not assign white
		//se non trovo nessun background controllo che sia definito nel body, se no gli assegno il bianco
		if ($background == "" || $background == null || $background == "transparent") {
			
			if (($e->tag == "body" || $e->tag == "html") && isset ( $e->attr ["bgcolor"] ))
				$background = $e->attr ["bgcolor"];
			else
				$background = "#ffffff";
		}
		
		$background = str_replace ( "'", "", $background );
		$background = str_replace ( "\"", "", $background );
		$background = str_replace ( "!important", "", $background );
		return $background;
	
	}
	// traverse the tree until you find a parent element that has the $propriety  style value $value
	//sale l'albero fino a trovare un elemento genitore che abbia nel suo stile $propriety di valore $value
	public static function isProprietyInerited($e, $propriety, $value) {
		// Find the value of $propriety explicitly defined for the element $e
		//cerco il valore di $propriety esplicitamente definito per l'elemento $e
		$p = BasicChecks::get_p_css ( $e, $propriety );
		// if background == "" means that the value is not defined for $e: Searches the parents
		//se background == "" significa che il valore non è stato definito per $e: ricerco tra i suoi genitori
		while ( ($p == "" || $p == null || $p !== $value) && $e->tag != null && $e->tag != "html" ) {
			$e = $e->parent ();
			$p = BasicChecks::get_p_css ( $e, $propriety );
		}
		
		if ($p == "" || $p == null)
			return false;
		else
			return true;
	}
	
	// check if the item is contained in an element with absolute position outside the page
	//controllo se l'elemento è contenuto in un elemento con posizione assoluta esterna alla pagina
	public static function isPositionOutOfPage($e) {
		$p = BasicChecks::get_p_css ( $e, "position" );
		while ( ($p == "" || $p == null || $p !== "absolute") && $e->tag != null && $e->tag != "html" ) {
                    
                        $e = $e->parent ();
			$p = BasicChecks::get_p_css ( $e, "position" );
		}
                
		if ($p == "" || $p == null)
			return false;
		else {
			$top = BasicChecks::get_p_css ( $e, "top" );
			if (stripos ( $top, "-" ) === 0)
                                return true;
                        
			$left = BasicChecks::get_p_css ( $e, "left" );
			if (stripos ( $left, "-" ) === 0)
                                return true;
		
		}
		
		return false;
	}
	// check that the element is visible
	// controllo che l'elemento sia visibile
	public static function isElementVisible($e) {
		//visibility:hidden o display:none
                //note: actually overflow: hidden does not make alway an element invisible, it depens on element's width and height 
		if (BasicChecks::isProprietyInerited ( $e, "visible", "hidden" ) || BasicChecks::isProprietyInerited ( $e, "display", "none" ) || BasicChecks::isProprietyInerited ( $e, "overflow", "hidden" ))
			return false;
		// check if the item is within an element with absolute position outside the page	
		//controllo se l'elemento è all'interno di un elemento con posizione assoluta esterna alla pagina
		if (BasicChecks::isPositionOutOfPage ( $e ))
			return false;
		
		return true;
	
	}
	// Return true if the amount of $ value is relative
	//Restituisce true se la misura di $value è relativa
	public static function isRelative($value) {
		
		$value = trim ( str_ireplace ( "!important", "", $value ) );
		
		$a_value = preg_split ( '/ /', $value );
		//print_r($a_value);
		
		foreach ( $a_value as $value ) {
			if ($value == "auto" || $value == ' ' || $value == 0)
				; //ok
			elseif ((substr ( $value, strlen ( $value ) - 2, 2 ) != "em") && (substr ( $value, strlen ( $value ) - 1, 1 ) != "%") 
                                && $value != 'large' && $value != 'larger' && $value != 'medium' && $value != 'small' && $value != 'smaller' 
                                && $value != 'x-large' && $value != 'xx-large' && $value != 'xx-small' && $value != 'x-small' /*&& (substr ( $value, strlen ( $value ) - 2, 2 ) != "px")*/)
				return false;
			//else 
		//	return true;
		}
		return true;
	}
	// check to see if the size of the property $val associated with the element $e' is relative
	//check per verificare se la misura della proprieta' $val associata all'elemento $e e' relativa
	public static function checkRelative($e, $val) {
	
		$fs = BasicChecks::get_p_css ( $e, $val );
		if ($fs != "" && $fs != null) {
				
			return BasicChecks::isRelative ( $fs );
		} else
			return true;
	}	
	// Return true if the amount of $value is in px
	//Restituisce true se la misura di $value è in px
	public static function isPx($value) {
		
		$value = trim ( str_ireplace ( "!important", "", $value ) );
		
		$a_value = preg_split ( '/ /', $value );
		
		$ret = false;
		foreach ( $a_value as $value ) {
			if (substr ( $value, strlen ( $value ) - 2, 2 ) == "px")
				$ret = true;
			//else
		//	return false;
		}
		return $ret;
	}
	// check for the presence of px in the property $val relative to the element $e
	//check per verificare la presenza di px nella proprieta' $val relativa all'elemento $e
	public static function checkPx($e, $val) {
		
		$fs = BasicChecks::get_p_css ( $e, $val );
		if ($fs != "" && $fs != null) {
			
			return ! BasicChecks::isPx ( $fs );
		} else
			return true;
	}
	// FUNCTION TO CALCULATE THE RATIO OF BRILLIANCE
	//FUNZIONE PER CALCOLARE IL RAPPORTO DI BRILLANTEZZA
	public static function CalculateBrightness($color1, $color2) {
		
		include_once (AC_INCLUDE_PATH . "classes/ColorValue.class.php");
		
		//echo("<p>CalcolateBrightness</p>");
		//echo("<p>Colori prima di ColorValue: color1=".$color1.  "color2=".$color2. "</p>");
		
		$color1 = new ColorValue ( $color1 );
		$color2 = new ColorValue ( $color2 );
		
		//echo("<p>Colori dopo ColorValue: color1=".$color1.  "color2=".$color2. "</p>");
		
		if (! $color1->isValid () || ! $color2->isValid ())
			return true;
		
		$colorR1 = $color1->getRed ();
		$colorG1 = $color1->getGreen ();
		$colorB1 = $color1->getBlue ();
		
		$colorR2 = $color2->getRed ();
		$colorG2 = $color2->getGreen ();
		$colorB2 = $color2->getBlue ();
		
		$brightness1 = (($colorR1 * 299) + ($colorG1 * 587) + ($colorB1 * 114)) / 1000;
		
		$brightness2 = (($colorR2 * 299) + ($colorG2 * 587) + ($colorB2 * 114)) / 1000;
		
		$difference = 0;
		if ($brightness1 > $brightness2) {
			$difference = $brightness1 - $brightness2;
		} else {
			$difference = $brightness2 - $brightness1;
		}
		
		return $difference;
	}
	
	//TOSI e VIRRUSO WCAG2
	public static function ContrastRatio($color1, $color2) {
		include_once (AC_INCLUDE_PATH . "classes/ColorValue.class.php");
		
		$color1 = new ColorValue ( $color1 );
		$color2 = new ColorValue ( $color2 );
		
		if (! $color1->isValid () || ! $color2->isValid ()) {
			return true;
		}
		
		$colorR1 = $color1->getRed () / 255;
		$colorG1 = $color1->getGreen () / 255;
		$colorB1 = $color1->getBlue () / 255;
		
		$colorR2 = $color2->getRed () / 255;
		$colorG2 = $color2->getGreen () / 255;
		$colorB2 = $color2->getBlue () / 255;
		
		if ($colorR1 <= 0.03928)
			$colorR1 = $colorR1 / 12.92;
		else
			$colorR1 = pow ( (($colorR1 + 0.055) / 1.055), 2.4 );
		
		if ($colorG1 <= 0.03928)
			$colorG1 = $colorG1 / 12.92;
		else
			$colorG1 = pow ( (($colorG1 + 0.055) / 1.055), 2.4 );
		
		if ($colorB1 <= 0.03928)
			$colorB1 = $colorB1 / 12.92;
		else
			$colorB1 = pow ( (($colorB1 + 0.055) / 1.055), 2.4 );
		
		if ($colorR2 <= 0.03928)
			$colorR2 = $colorR2 / 12.92;
		else
			$colorR2 = pow ( (($colorR2 + 0.055) / 1.055), 2.4 );
		
		if ($colorG2 <= 0.03928)
			$colorG2 = $colorG2 / 12.92;
		else
			$colorG2 = pow ( (($colorG2 + 0.055) / 1.055), 2.4 );
		
		if ($colorB2 <= 0.03928)
			$colorB2 = $colorB2 / 12.92;
		else
			$colorB2 = pow ( (($colorB2 + 0.055) / 1.055), 2.4 );
		
		$Lum1 = ($colorR1 * 0.2126) + ($colorG1 * 0.7152) + ($colorB1 * 0.0722);
		$Lum2 = ($colorR2 * 0.2126) + ($colorG2 * 0.7152) + ($colorB2 * 0.0722);
		
		$ContrastRatio = 0;
		if ($Lum1 > $Lum2) {
			$ContrastRatio = ($Lum1 + 0.05) / ($Lum2 + 0.05);
		} else {
			$ContrastRatio = ($Lum2 + 0.05) / ($Lum1 + 0.05);
		}
		
		return $ContrastRatio;
	}
	
	//TOSI e VIRRUSO convert font size to pt
	public static function fontSizeToPt($e) {
		global $tag_size;
		$tag_size = BasicChecks::get_p_css ( $e, "font-size" );
		while ( $tag_size == null && ($e->tag != "body" && $e->tag != "html")) {
			$h = BasicChecks::checkHeadingLevel ( $e );
			if ($h != null && $tag_size == null) {
				$tag_size = $h * BasicChecks::fontSizeToPt ( $e->parent () );
				//heading tag found
				return $tag_size;
			} else {
				//not an heading
				if ($e != null) {
					$e = $e->parent();
					$tag_size = BasicChecks::get_p_css ( $e, "font-size" );
				}
			}
		}
		if ($tag_size == null) {
			$tag_size = DEFAULT_FONT_SIZE;
			$format = DEFAULT_FONT_FORMAT;
			return $tag_size;
		} else {
			if (substr ( $tag_size, - 1 ) == "%") {
				//percent
				$s = substr ( $tag_size, 0, (strlen ( $tag_size ) - 1) ) / 100;
				
				if ($e->tag == "body" || $e->tag == "html") {
					$tag_size = DEFAULT_FONT_SIZE * $s;
					return $tag_size;
				} else {
					$tag_size = $s * BasicChecks::fontSizeToPt ( $e->parent () );
					return $tag_size;
				}
			} else {
				//em,px,pt
				$format = substr ( $tag_size, - 2 );
				$s = substr ( $tag_size, 0, (strlen ( $tag_size ) - 2) );
				switch ($format) {
					case "pt" :
						$tag_size = $s;
						return $tag_size;
						break;
					case "px" :
						$tag_size = $s / 1.32;
						return $tag_size;
						break;
					case "em" :
						if ($e->tag == "body" || $e->tag == "html") {
							$tag_size = DEFAULT_FONT_SIZE * $s;
							return $tag_size;
						} else {
							$tag_size = $s * BasicChecks::fontSizeToPt ( $e->parent () );
							return $tag_size;
						}
						break;
					default :
						//formato non supportato
						return - 1;
				}
			}
		}
	}
	// Return the multiplication factor of heading tags
	//Ritorna il fattore moltiplicativo degli heading tag
	private static function checkHeadingLevel($e) {
		switch ($e->tag) {
			case "h1" : //def 24pt
				return 2;
			case "h2" : //def 18pt
				return 1.5;
			case "h3" : //def 14pt
				return 1.17;
			case "h4" : //def 12pt
				return 1;
			case "h5" : //def 10pt
				return 0.83;
			case "h6" : //def 8pt
				return 0.67;
			default :
				return null;
		}
	
	}
	//LINE CHANGE: A function to convert color
	//MODIFICA FILO: Funzione per la conversione del colore
	public static function convert_color_to_hex($f_color) {
		/* Se il colore e' indicato in esadecimale lo restituisco cos com' */
		$a = strpos ( $f_color, "#" );
		
		//MBif($a!=0){
		if ($a !== false) {
			$f_color = substr ( $f_color, $a + 1 );
			return $f_color;
		} /* Se  in formato RGB lo converto in esadecimale poi lo restituisco */
		//elseif (eregi ( 'rgb', $f_color )) {
		elseif (preg_match ( '/rgb/i', $f_color )) {
			//if (eregi ( '\(([^,]+),', $f_color, $red )) {
			if (preg_match ( '/\(([^,]+/i),', $f_color, $red )) {

				$red = dechex ( $red [1] );
			}
			//if (eregi ( ',([^,]+),', $f_color, $green )) {
			if (preg_match ( '/,([^,]+),/i', $f_color, $green )) {

				$green = dechex ( $green [1] );
			}
			//if (eregi ( ',([^\)]+)\)', $f_color, $blue )) {
			if (preg_match ( '/,([^\)]+)\/i)', $f_color, $blue )) {
				$blue = dechex ( $blue [1] );
			}
			$f_color = $red . $green . $blue;
			return $f_color;
		} /* La stessa cosa faccio se  indicato con il proprio nome */
		else {
			switch ($f_color) {
				
				case 'black' :
					return '000000';
				case 'silver' :
					return 'c0c0c0';
				case 'gray' :
					return '808080';
				case 'white' :
					return 'ffffff';
				case 'maroon' :
					return '800000';
				case 'red' :
					return 'ff0000';
				case 'purple' :
					return '800080';
				case 'fuchsia' :
					return 'ff00ff';
				case 'green' :
					return '008800';
				case 'lime' :
					return '00ff00';
				case 'olive' :
					return '808000';
				case 'yellow' :
					return 'ffff00';
				case 'navy' :
					return '000080';
				case 'blue' :
					return '0000ff';
				case 'teal' :
					return '008080';
				case 'aqua' :
					return '00ffff';
				case 'gold' :
					return 'ffd700';
				case 'navy' :
					return '000080';
			}
		}
	}
	// get input and returns an item on the table
	//prende in input un elemento e restituisce la relativa table
	public static function getTable($e) {
		
		while ( $e->parent ()->tag != "table" && $e->parent ()->tag != null )
			$e = $e->parent ();
		
		if ($e->parent ()->tag == "html")
			return null;
		else
			return $e->parent ();
	
	}
	// gets an array of id (headers attribute of a td element) and verifies that each id is associated with an th
	//prende un array di id (attributo headers di un elemento td) e verifica che ogni id sia associato a un th
	public static function checkIdInTable($t, $ids) {
		
		$th = $t->find ( "th" );
		$num = 0;
		$size_of_ids = sizeof ( $ids );
		
//		for($i = 0; $i < $size_of_ids; $i ++) {
//			for($j = 0; $j < sizeof ( $th ); $j ++) {
//				
//				if (isset ( $th [$j]->attr ['id'] ) && $th [$j]->attr ['id'] == $ids [$i]) {
//					
//					$num ++;
//					break;
//				}
//			
//			}
//		}
		
		foreach($ids as $one_id) {
			foreach($th as $one_th) {
				if (isset ( $one_th->attr ['id'] ) && $one_th->attr ['id'] == $one_id) {
					$num ++;
					break;
				}
			}
		}
		
		if ($num == $size_of_ids) //ho trovato un id in un th per ogni id di un td
			return true;
		else
			return false;
	}
	// verify the existence of a row for an element td
	//verifica l'esistenza di un'intestazione di riga per un elemento td
	public static function getRowHeader($e) {
		
		while ( $e->prev_sibling () != null && $e->prev_sibling ()->tag != "th" ) {
			
			$e = $e->prev_sibling ();
		}
		
		if ($e->prev_sibling () == null)
			return null;
		else
			
			return $e->prev_sibling ();
		/*
			if(isset($e->attr["scope"]) && $e->attr["scope"]=="row")
				return $e;
			else
				return null;
			*/
	
	}
	// checks for the existence of a column header for a td element
	//verifica l'esistenza di un'intestazione di colonna per un elemento td
	public static function getColHeader($e) {
		
		$pos = 0;
		$e_count = $e;
		//find the position in the row of td
		//trovo la posizione nella riga di td
		while ( $e_count->prev_sibling () != null ) {
			$pos ++;
			$e_count = $e_count->prev_sibling ();
		}
		
		$t = BasicChecks::getTable ( $e );
		// there isn't a <table> tag
		//non c'è il tag <table>
		if ($t == null) {
			return true; //tabella mal composta
		}
		
		$tr = $t->find ( "tr" );
		$size_of_tr = sizeof ( $tr );
		
		if ($tr == null || $size_of_tr == 0)
			return true; //tabella mal composta - table is not well formed
		
		for($i = 0; $i < $size_of_tr - 1; $i ++) {
			$th_next = $tr [$i + 1]->find ( "th" );
			if ($th_next == null || sizeof ( $th_next ) == 0)
				break; //l'i-esima tr contiene l'intestazione pi interna
		}
		
		$h = $tr [$i]->childNodes ();
		// Verify that the header box in place $pos  is actually a header
		//verifico che la casella in posizione $pos della presunta riga di intestazione sia effettivamente un'intestazione 
		if (isset ( $h [$pos] ) && $h [$pos]->tag == "th" /*&& isset($h[$pos]->attr["scope"]) && $h[$pos]->attr["scope"]=="col"*/)
			return $h [$pos];
		else
			return null;
	
	}
	
	public static function rec_check_15005($e) {
		if ($e->tag == 'script' || $e->tag == 'object' || $e->tag == 'applet' || isset ( $e->attr ['onload'] ) || isset ( $e->attr ['onunload'] ) || isset ( $e->attr ['onclick'] ) || isset ( $e->attr ['ondblclick'] ) || isset ( $e->attr ['onmousedown'] ) || isset ( $e->attr ['onmouseup'] ) || isset ( $e->attr ['onmouseover'] ) || isset ( $e->attr ['onmousemove'] ) || isset ( $e->attr ['onmouse'] ) || isset ( $e->attr ['onblur'] ) || isset ( $e->attr ['onkeypress'] ) || isset ( $e->attr ['onkeydown'] ) || isset ( $e->attr ['onkeyup'] ) || isset ( $e->attr ['onsubmit'] ) || isset ( $e->attr ['onreset'] ) || isset ( $e->attr ['onselect'] ) || isset ( $e->attr ['onchange'] ))
			return false;
		
		else
			$c = $e->children ();
		$res = true;
		foreach ( $c as $elem ) {
			$res = BasicChecks::rec_check_15005 ( $elem );
			if ($res == false)
				return $res;
		}
		return $res;
	
	}
	
	
	/* returns the list of external styles on the page */
	/* ritorna la lista degli stili esterni presenti nella pagina */
	public static function get_style_external($content_dom) {
		
		global $csslist;
		
		//MB
		//$dom=str_get_dom($content);
		$dom = $content_dom;
		$vettore_link = $dom->find ( 'link' );
		//$vettore_link=array_reverse($vettore_link);
		$i = 0;
		foreach ( $vettore_link as $link ) {
			if ($link->attr ["type"] == "text/css" && $link->attr ["rel"] == "stylesheet" && (! isset ( $link->attr ["media"] ) || $link->attr ["media"] == "all" || $link->attr ["media"] == "screen")) {
				$csslist [$i]['href'] = $link->attr ["href"];
				$i ++;
			}
		}
		
		if ($csslist == "")
			return $csslist;
			//MB ripulisco gli url dei css
		global $uri, $base_href;
// 		$uri2 = BasicChecks::getSiteUri ( $uri );
		$i = 0;
		// change the relative addresses of style sheets
		//modifico gli indirizzi relativi dei fogli di stile
		foreach ( $csslist as $foglio ) {
			
			//$foglio = str_replace ( '"', '', $foglio );
                        
                        if(stripos($foglio['href'], './') === 0)
                            $foglio['href'] = substr ($foglio['href'], 1);
                    
			
			if (stripos ( $foglio['href'], "http://" ) === false) //indirizzo relativo
			{
// 				if (substr ( $foglio['href'], 0, 1 ) == "/")
// 					$foglio['href'] = $uri2 . $foglio['href'];
// 				else
// 					$foglio['href'] = $uri2 . "/" . $foglio['href'];
				$indirizzo = $foglio['href'];
								
				$foglio['href'] = BasicChecks::getFile($indirizzo, $base_href, $uri);
			}
			
			$csslist [$i]['absolute'] = $foglio['href'];
			$i ++;
		}
		return $csslist;
	}
	// The function returns the css of a page
	//La funzione ritorna i css di una pagina
	public static function get_style_internal($content_dom) {
		
		//MB
		//$dom=str_get_dom($content);
		$dom = $content_dom;
		//echo("<p> contenuto di style: </p>");
		// change the URL of the site to be validated in order to set add the address of a relative css
		//modifico l'url del sito da validare in modo da porterci aggiungere l'indirizzo di un css relativo
		global $uri, $base_href;
		
		
		$uri2 = BasicChecks::getSiteUri ( $uri );
		
		$vettore_stili_interni = $dom->find ( 'style' );
		$cssint = "";
		foreach($vettore_stili_interni as $one) {
			if (! isset ( $one->attr ["media"] ) || $one->attr ["media"] == "all" || $one->attr ["media"] == "screen") {
				$cssint = $cssint . $one->innertext;
				$cssint = trim ( $cssint );
				while (stripos($cssint,'@import') !== false){
					$posStart = stripos($cssint,'@import');
					$posEnd = stripos($cssint,';', $posStart);
					
					$match = substr ($cssint, $posStart, $posEnd - $posStart +1);
					
					if(stripos($match,'"') !== false) //@import "an.url.css" or @import ("an.url.css")
					{
						$indirizzo = substr($match, stripos($match,'"')+1,strripos($match,'"') - stripos($match,'"') -1);
					}
					elseif(stripos($match,"'") !== false) //@import "an.url.css" or @import ("an.url.css")
					{
						$indirizzo = substr($match, stripos($match,"'")+1,strripos($match,"'") - stripos($match,"'") -1);
					}
					elseif(stripos($match,'(') !== false) //@import (an.url.css)
					{
						$indirizzo = substr($match, stripos($match,'(')+1,strripos($match,')') - stripos($match,'(') -1);
					}
					
					if (stripos ( $indirizzo, "http://" ) === false) //indirizzo relativo
					{
// 						if (substr ( $indirizzo, 0, 1 ) == "/")
// 							$indirizzo = $uri2 . $indirizzo;
// 						else
// 							$indirizzo = $uri2 . "/" . $indirizzo;
						$indirizzo = BasicChecks::getFile($indirizzo, $base_href, $uri);
											
					}
					
					$atImportContent = @file_get_contents ( $indirizzo );
                                        
                                        
					//$cssint = str_replace($match, $atImportContent, $cssint);
					$cssint1 = substr ($cssint, 0, $posStart);
					$cssint2 = substr ($cssint, $posEnd+1);
					$cssint = $cssint1 .$atImportContent . $cssint2;
					
					
				}
			}
		}
		return $cssint;
	}
	// The function creates an array of styles (internal and external) to be submitted for validation.
	//La funzione crea l'array degli stili (interni ed esterni) da sottoporre alla validazione.
	public static function prepare_css_arrays($array_css_esterni, $ci) {
            
		for($b = 0; $b < count ( $array_css_esterni ); $b ++) {
                    
                        $absolute = $array_css_esterni [$b]['absolute'];
                        $relative = $array_css_esterni [$b]['href'];
                        
                        if(stripos($relative, './') === 0)
                                $relative = substr ($relative, 1);
                        
			$css_content = @file_get_contents ( $absolute );
                        $whileFlag = false;
                        //workaround for wrong urls
                        //try to found css in parents directories
                        $count = 0;
                        while ($css_content == false && $whileFlag == false && $count < 50)
                        {
                            $count++;
                            
                            if($relative == $absolute)
                            {   
                                $whileFlag = true;
                                break; //unreachable
                            }
                            //remove relative part
                            $absolute = str_replace($relative, "", $absolute);
                            //remove last position '/' if present
                            if(substr($absolute, strlen($absolute)-1) == '/' )
                                $absolute = substr($absolute, 0, -1);
                            
                            //remove minor directory
                            $lastSlashPos = strripos($absolute, "/", -1);
                            if($lastSlashPos == false)
                            {
                                $whileFlag = true;
                                break;
                            }
                            $absolute = substr($absolute, 0, $lastSlashPos);
                            //create new absolute path
                            if(stripos($relative,'/') === 0)
                                    $absolute = $absolute.''.$relative;
                            else
                               $absolute = $absolute.'/'.$relative;
                            
                            
                            $css_content = @file_get_contents ( $absolute );
                            //correct absolute css url
                            if($css_content)
                            {
                                $array_css_esterni [$b]['absolute'] = $absolute;
                            }
                        }
			BasicChecks::GetCSSDom ( $css_content, $b );
                        
	
                        
		}
		//MB
		// last position: interior style
		if ($ci != "") {
                    
			BasicChecks::GetCSSDom ( $ci, $b );
		}
             
                
	}
	// get the css code that caused an error, for the last check that was performed on css
	// is called in after the Procedure AccessibilityValidator of each check
	// return $ css_code that, if the check has not found errors on a CSS internal / external or
	// not a check on the css is set to ""
	//restituisce il codice css che ha provocato un errore, relativamente all'ultimo check sui css che � stato eseguito
	//viene richiamata in AccessibilityValidator dopo l'esecuizione di ogni check
	//restituisce $css_code che, nel caso in cui il check non abbia riscontrato errori su un css interno/esterno o
	//non sia un check sui css, viene impostata a ""
	public static function getCssOutput() {
            
		// MB: To print the check on the rules of CSS
		// css rulesrelating the error
		// CSS: default font size and default font format
		//MB:per stampare le regole dei check sui CSS														
		//regole css relative all'errore
		//CSS: default font size e default font format
		define("DEFAULT_FONT_SIZE",12);
		define("DEFAULT_FONT_FORMAT","pt");
		global $tag_size;
		global $font_weight;
		global $csslist;
		global $array_css;
		global $background, $foreground;
		global $showContrastExample;
		global $showSurroundingTextExample;
		
		$back = $background;
		$fore = $foreground;
		$f_w = $font_weight;
		$background = $foreground = $font_weight = '';
		
		$spazio = "{_}";
		$css_code = "";
		
			
				if($showContrastExample)
				{				
								$tag_size = round($tag_size,2);
								$css_code = $css_code.
				//"<p style='font-size:20px;width:80px;text-align:center;background-color:#".
								"<p>"._AC("fixed_size_example_text").": <span style='font-size:15px;background-color:#".        
								$back.";color:#".$fore."'>"._AC("color_contrast_example")."</span></p>";
		
//				$bold = BasicChecks::get_p_css($e,"font-weight");
//                                if($bold == 'inherit')
//                                {
//                                    $ePar = $e;
//                                    while ($ePar->parent()->tag != 'root' && $bold == 'inherit' )
//                                    {
//                                        $ePar = $ePar->parent();
//                                        $bold = BasicChecks::get_p_css($ePar,"font-weight");
//                                    }
//                                }
				//if($e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6")
				//	$bold="bold";
				$real_size_text = '';
				if($tag_size != 0 && trim($tag_size)!='')
				{
					if($f_w =="bold" || $f_w =="bolder" ||  $f_w >= 700 || ($f_w == "" && ($e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6")))				
					{
						//$css_code = $css_code."Font size: ".$tag_size."points bold";
						$real_size_text="<p>"._AC("real_size_example_text")." (".$tag_size." "._AC("points")." "._AC("bold")."): <span style='font-weight:bold;font-size:".$tag_size."pt;background-color:#".       
						$back.";color:#".$fore."'>"._AC("color_contrast_example")."</span></p>";
					}
					else
					{
	                                    
						//$css_code = $css_code."Font size: ".$tag_size."points";
						$real_size_text="<p>"._AC("real_size_example_text")." (".$tag_size." "._AC("points")."): <span style='font-size:".$tag_size."pt;background-color:#".       
						$back.";color:#".$fore."'>"._AC("color_contrast_example")."<span></p>";
					}
				}	
				$css_code = $css_code.$real_size_text;
                                $showContrastExample = false;
					
						
                }
                    
                    if($showSurroundingTextExample)
                    {
                        $css_code = $css_code. 
                                "<p>"._AC("surrounding_example_text").": 
                                    <span style='font-size:15px;color:#".$back."'>"._AC("colored_surrounding_text")."</span> --  
                                <span style='font-size:15px;color:#".$fore."'>"._AC("colored_inner_text")."</span>
                                    -- <span style='font-size:15px;color:#".$back."'>"._AC("colored_surrounding_text")."</span>
                                 </p>";
                        $showSurroundingTextExample = false;
                    }
			
                    if (isset ( $array_css ) && $array_css != null) {			
			//------>
			$css_code .= "<p style='padding:1em'>" . _AC ( "element_CSS_rules" ) . ": </p>\n\t\n\t<pre>\n\t\n\t";
			$int_css = '';
			$ext_css = '';
			$size_of_css_list = sizeof ( $csslist );
			
			foreach ( $array_css as $rule ) {
				
				$temp_css_code = '';
				$num_to_end = sizeof ( $rule ["prev"] ) - 1;
				
                                $temp_css_code = $rule ["prev_string"];
                                
//				for($i = $num_to_end; $i >= 0; $i --) {
//					$temp_css_code .= " " . $rule ["prev"] [$i];
//				}
                                
//				$temp_css_code = str_ireplace ( " .", ".", $temp_css_code );
//				$temp_css_code = str_ireplace ( " #", "#", $temp_css_code );
//				$temp_css_code = str_ireplace ( ">.", "> .", $temp_css_code );
//				$temp_css_code = str_ireplace ( ">#", "> #", $temp_css_code );
//				$temp_css_code = str_ireplace ( "+.", "+ .", $temp_css_code );
//				$temp_css_code = str_ireplace ( "+#", "+ #", $temp_css_code );
				$temp_css_code = str_ireplace ( " " . $spazio, "", $temp_css_code );
				
				$temp_css_code = $temp_css_code . "{\n\t\n\t";
				
				foreach ( $rule ["regole"] as $prop => $value ) {
					$temp_css_code = $temp_css_code . "            " . $prop . ":" . $value ["val"] . ";\n\t";
				}
				$temp_css_code = $temp_css_code . "      }\n\t\n\t";
				
				if ($rule ["idcss"] == $size_of_css_list) //ultimo posto, stile interno
					$int_css .= $temp_css_code;
					//$css_code=$css_code._AC("internal_CSS").":\n\t\n\t      ";
				else
					$ext_css [$csslist [$rule ["idcss"]]['absolute']] .= $temp_css_code;
				//$css_code=$css_code._AC("external_CSS")." (<a title='external CSS link' href='".$csslist[$rule["idcss"]]."'>".$csslist[$rule["idcss"]]."</a>):\n\t\n\t      ";
			}
			if ($int_css != '')
				$css_code .= _AC ( "internal_CSS" ) . ":\n\t\n\t " . $int_css;
			
			if ($ext_css != '')
				foreach ( $ext_css as $url => $val ) {
					$css_code .= _AC ( "external_CSS" ) . " (<a title='external CSS link' href='" . $url . "'>" . $url . "</a>):\n\t\n\t      " . $val;
				}
			
			$css_code .= "</pre>\n\t";
		}
		$array_css = array ();
		return $css_code;
	}
	
	public static function checkLinkContrastWcag2AA($pseudoClass, $bodyAttribute) {
		global $background, $foreground, $font_weight;
		global $global_e, $global_content_dom;
		global $stringa_testo_prova;
		global $showContrastExample;
		$e = $global_e;
		$content_dom = $global_content_dom;
                
		if(!BasicChecks::setCssSelectors ( $content_dom ))
			return true;
		
		if (! BasicChecks::isElementVisible ( $e ))
			return true;
		
		$background = '';
		$foreground = '';
		$font_weight = '';
		
		if (trim ( BasicChecks::remove_children ( $e ) ) == "" || trim ( BasicChecks::remove_children ( $e ) ) == "&nbsp;") //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
		// the element has no visible text: Do not run the contrast control
			return true;
		
		$foreground = BasicChecks::getForeground ( $e, $pseudoClass );
		if ($foreground == "undetermined")
			return true;
		if (($foreground == "" || $foreground == null) && $bodyAttribute != null) {
			$app = $e->parent ();
			while ( $app->tag != "body" && $app->tag != "html" && $app->tag != null )
				$app = $app->parent ();
			if ($app != null && isset ( $app->attr [$bodyAttribute] ))
				$foreground = $app->attr [$bodyAttribute];
		
		}
		if ($foreground == "undetermined")
			return true;
		
		if ($foreground == "" || $foreground == null)
			return true;
		
		$background = BasicChecks::getBackground ( $e, $pseudoClass );
		if ($background == "undetermined")
			return true;
		
		if ($background == "" || $background == null)
			$background = BasicChecks::getBackground ( $e );
		
		if ($background == "" || $background == null || $background == "-1")
			return true;
		
		if ($background == "undetermined")
			return true;
		
		$background = BasicChecks::convert_color_to_hex ( $background );
		$foreground = BasicChecks::convert_color_to_hex ( $foreground );
		
		$res = BasicChecks::ContrastRatio ( strtolower ( $background ), strtolower ( $foreground ) );
		//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $res; echo "<br>";
		
		$size = BasicChecks::fontSizeToPt ( $e , $pseudoClass);
                $font_weight = BasicChecks::getFontWeight($e, $pseudoClass);
//                $font_weight = BasicChecks::get_p_css ( $e, "font-weight", $pseudoClass );
//                        echo($e->plaintext."\n");
//                        echo("f_w: $font_weight");
//                
//                while ( ($font_weight == "" || $font_weight == null) && $e->tag != null && $e->tag != "html")
//			$e = $e->parent ();
//                        
//                if($font_weight == '' || $font_weight == null)
//                    $font_weight = BasicChecks::get_p_css ( $e, "font-weight" );
		if ($size < 0) //formato non supportato
			return true;
		elseif ($size >= 18 || ($font_weight == "bold" || $font_weight == "bolder" || $font_weight >= 700 ) && $size >= 14)
			$threashold = 3;
		else
			$threashold = 4.5;
		
		
		
		
		if ($res < $threashold) {
			$showContrastExample = true;
			return false;
		} else {
			return true;
		}
	
		
		return true;
	}	
	public static function checkLinkContrastWcag2AAA($pseudoClass, $bodyAttribute) 
        {
		global $background, $foreground, $font_weight;
		global $global_e, $global_content_dom;
		global $stringa_testo_prova;
		global $showContrastExample;
                
		$e = $global_e;
		$content_dom = $global_content_dom;
                
		
		if(!BasicChecks::setCssSelectors ( $content_dom ))
			return true;
		
		if (! BasicChecks::isElementVisible ( $e ))
			return true;
		
		$background = '';
		$foreground = '';
		$font_weight = '';
		
		if (trim ( BasicChecks::remove_children ( $e ) ) == "" || trim ( BasicChecks::remove_children ( $e ) ) == "&nbsp;") //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
		// the element has no text "visible": Do not run the contrast control
			return true;
		
		$foreground = BasicChecks::getForeground ( $e, $pseudoClass );
		if ($foreground == "undetermined")
			return true;
		if (($foreground == "" || $foreground == null) && $bodyAttribute != null) {
			$app = $e->parent ();
			while ( $app->tag != "body" && $app->tag != "html" && $app->tag != null )
				$app = $app->parent ();
			if ($app != null && isset ( $app->attr [$bodyAttribute] ))
				$foreground = $app->attr [$bodyAttribute];
		
		}
		if ($foreground == "undetermined")
			return true;
		
		if ($foreground == "" || $foreground == null)
			return true;
		
		$background = BasicChecks::getBackground ( $e, $pseudoClass );
		if ($background == "undetermined")
			return true;
		
		if ($background == "" || $background == null)
			$background = BasicChecks::getBackground ( $e );
		
		if ($background == "" || $background == null || $background == "undetermined")
			return true;
		
		
		$background = BasicChecks::convert_color_to_hex ( $background );
		$foreground = BasicChecks::convert_color_to_hex ( $foreground );
		$res = '';
		$res = BasicChecks::ContrastRatio ( strtolower ( $background ), strtolower ( $foreground ) );
		//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $res; echo "<br>";
		
		$size = BasicChecks::fontSizeToPt ( $e , $pseudoClass );
		$font_weight = BasicChecks::getFontWeight($e, $pseudoClass);
                
		if ($size < 0) //formato non supportato
			return true;
		elseif ($size >= 18 || ($font_weight == "bold" || $font_weight == "bolder" || $font_weight >= 700 ) && $size >= 14)
			$threashold = 4.5;
		else
			$threashold = 7;
		
		
		if ($res < $threashold) {
			
                        $showContrastExample = true;
			return false;
		} else {
			return true;
		}
		
		return true;
	
	}
	
	
	//return text in the element which is not in a child element (for <div>simple text <p>text in p</p></div> returns "simple text"
	public static function getElementPlainText($e)
	{
		 
		$innerText = $e->innertext;
		$children = $e->children();
	
		for($i=0; $i<count($children); $i++){
	
			$cOuterText = $children[$i] ->outertext;
			$innerText = str_replace($cOuterText, '', $innerText);
	
		}
	
		return trim($innerText);
	
	}
	
	//return text of the current a element only (removes text of its children image elements)
	public static function aText($e)
	{
	
		$content_a=$e->plaintext;
		$figli_a=$e->find('img');
	
		foreach ($figli_a as $img)
		{
	
			$txt=$img->plaintext;
			if($txt!=null || $txt!='')
			{
				$arr=explode($txt, $content_a, 2);
				$content_a=implode($arr);
			}
		}
	
		return $content_a;
	}
	
	
	//return text of the current object element only (removes text of its children object elements)
	public static function objectText($e)
	{
	
		$contenuto_obj=$e->plaintext;
		$figli_obj=$e->find('object');
	
		foreach ($figli_obj as $obj)
		{
	
			$txt=$obj->plaintext;
			if($txt!=null || $txt!='')
			{
				$arr=explode($txt, $contenuto_obj, 2);
				$contenuto_obj=implode($arr);
			}
		}
	
		return $contenuto_obj;
	}
	
	
	
	
	//recursive check for alternative text in objects (called in objectAltText())
	public static function recursiveObjectAltText($e, $content_dom)
	{
	
	
		$text = BasicChecks::objectText($e);
		if (isset($text) && trim($text)!='')//text found
		{
	
			return true;
		}
		else //children
		{
			$obj_children = $e->children();
	
			if ($obj_children==null || sizeof($obj_children)==0)
			{
				 
				return false;
				 
			}
			$flag=0;
			foreach ($obj_children as $child)
			{
	
				if($child->tag=="object")
				{
					$flag=1; //at least one child object found
					if(BasicChecks::recursiveObjectAltText($child, $content_dom)==false)
					{
						 
						return false;
					}
				}
	
				//checking image alt
				if ($child->tag=="img")
				{
					if(isset($child->attr["alt"]) && (trim($child->attr["alt"]) != ''))
					{
						 
						return true;
					}
				}
	
			}
			if($flag==0)
			{
	
				return false;
			}
			return true;
		}
	
	}
	
	/* unibo - roberto montebelli*/
	/**
	 * This method searches recursively to find if $e has a parent with tag $parent_tag
	 *
	 * @param $e: The element to start searching from.
	 * @param $parent_tag: The parent tag to search.
	 * @return The parent element if found, null otherwise.
	 */
	public static function getParent($e, $parent_tag)
	{
		if ($e->parent() == null) return null;
	
		if ($e->parent()->tag == $parent_tag)
			return $e->parent();
		else
			return BasicChecks::getParent($e->parent(), $parent_tag);
	}
	
	//check if for certain $pseudoclass a particular $propriety change its value
	public static function doesElementPseudoclassEffectExist($property, $pseudoClass)
	{
	global $global_e;
	$e = $global_e;
	
	$baseP = BasicChecks::get_p_css($e, $property);
	
	
	$pseudoClassP = BasicChecks::get_p_css($e, $property, $pseudoClass );
	
	//            echo("<p>tag: ".$e->tag."</p>");
			//            echo("<p>text: ".$e->plaintext."</p>");
	//            echo("<p>prop: ".$property."</p>");
					//            echo("<p>baseP = ".$baseP."</p>");
					//            echo("<p>pseudoClassP = ".$pseudoClassP."</p>");
	
	
					if($pseudoClassP == $baseP )
		return false;
	
	
		return true;
	
	
	}
	
	//Check if document is well formed using HTML W3C Validator
	public static function isDocumentWellFormed()
	{
	
	global $htmlValidator;
	global $hV;
	
	
	if (isset($htmlValidator))
	{
	$hV = $htmlValidator;
	
	
	}
	elseif(!isset($hV))
	{
	$hV = BasicChecks::createHtmlValidator();
	
	}
	if (!isset($hV) || $hV == null)
		return true;
	
	
	$out = $hV->getValidationRpt();
	
	//search for one of the following error messages:
	//"... document type does not allow element "[element_name]" here ";
	//"end tag for "[element_name]" omitted...";
	//"end tag for element "[element_name]" which is not open";
	//"Element [element_name] not allowed as child of element [parent_name] in this context".
	
		if (preg_match('/document type does not allow element (\S)* here/', $out) ||
		preg_match('/end tag for (\S)* omitted/', $out) ||
		preg_match('/end tag for element (\S)* which is not open/', $out) ||
		preg_match('/Element (\S)* not allowed as child of element (\S)* in this context/', $out))
			return false;
			//echo $out;
		return true;
	
	}
	
	//Check if any document's element contains a duplicate attribute using HTML W3C Validator
	public static function doesDocumentElementsContainDuplicateAttribute()
	{
	
	global $htmlValidator;
	global $hV;
	
	
	if (isset($htmlValidator))
	{
	$hV = $htmlValidator;
	
	
	}
	elseif(!isset($hV))
	{
	
	$hV = BasicChecks::createHtmlValidator();
	
	}
	if (!isset($hV) || $hV == null)
	return true;
	
	$out = $hV->getValidationRpt();
	
	//search for one of the following error messages:
	//"... duplicate specification of attribute "[attribute_name]"
	
			if (stripos($out, "duplicate specification of attribute ") !== false ||
					stripos($out, "Duplicate attribute ") !== false)
							return false;
							//echo $out;
							return true;
	
	}
	
							//call W3C validator if user didn't enable it
							public static function createHtmlValidator()
							{
	
							include_once(AC_INCLUDE_PATH. "classes/HTMLValidator.class.php");
	
	if (isset($_REQUEST["validate_uri"]) || isset($_GET["output"]))
			{
			 
			global $addslashes;
	
			$uri = Utility::getValidURI($addslashes($_REQUEST["uri"]));
	
			// Check if the given URI is connectable
			if ($uri === false)
			{
			return null;
	}
	
	// don't accept localhost URI
				if (stripos($uri, '://localhost') > 0)
				{
				return null;
			}
	
			$_POST['uri'] = $_REQUEST['uri'] = $uri;
			$hV = new HTMLValidator("uri", $uri);
			}
			elseif (isset($_REQUEST["validate_file"]))
			{
			$validate_content = @file_get_contents($_FILES['uploadfile']['tmp_name']);
			$hV = new HTMLValidator("fragment", $validate_content);
			}
			elseif (isset($_REQUEST["validate_paste"]))
			{
			global $stripslashes;
			$validate_content = $_POST["pastehtml"] = $stripslashes($_POST["pastehtml"]);
			$hV = new HTMLValidator("fragment", $validate_content);
			}
	
			return $hV;
	
			}
	
			//search the css value for "font-weight" of the $e element
			public static function getFontWeight($e, $pseudoclass){
	
			$font_weight = BasicChecks::get_p_css ( $e, "font-weight", $pseudoClass );
	
	
			if($font_weight == '' || $font_weight == null)
				$font_weight = BasicChecks::get_p_css ( $e, "font-weight" );
	
			while ( ($font_weight == '' || $font_weight == null) && $e->tag != null && $e->tag != "html")
	{
	$e = $e->parent ();
		$font_weight = BasicChecks::get_p_css ( $e, "font-weight" );
			}
	
			return $font_weight;
	
			}
	
	
	
}
?>