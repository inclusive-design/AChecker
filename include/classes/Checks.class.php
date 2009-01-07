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
* Checks.class.php
* Class for accessibility validate
* This class contains all functions by check_id
* Note: 
* 1. All functions are named with according check_id embedded as check_${check_id}
* 2. All functions return true for success, false for fail
*
* @access	public
* @author	Cindy Qi Li
* @package checker
*/

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once (AC_INCLUDE_PATH . "classes/BasicChecks.class.php");

class Checks {
	public static function check_1($e, $content_dom)
	{
		return array_key_exists('alt', $e->attr);
	}

	public static function check_2($e, $content_dom)
	{
		if (!isset($e->attr['alt']) || !isset($e->attr['src'])) return true;
		else return (trim($e->attr['alt']) <> trim($e->attr['src']));
	}

	public static function check_3($e, $content_dom)
	{
		return (strlen(trim($e->attr['alt'])) <= 100);
	}

	public static function check_4($e, $content_dom)
	{
		return !(intval(trim($e->attr['width'])) > 25 && intval(trim($e->attr['height'])) > 25 && $e->attr['alt'] == "");
	}

	public static function check_5($e, $content_dom)
	{
		return !(intval(trim($e->attr['width'])) > 25 && intval(trim($e->attr['height'])) > 25 && trim($e->attr['alt']) == "" && strlen($e->attr['alt']) > 0);
	}

	public static function check_6($e, $content_dom)
	{
		return !(trim($e->attr['alt']) == '&nbsp;' || trim($e->attr['alt']) == "spacer");
	}

	public static function check_7($e, $content_dom)
	{
		return !($e->parent()->tag == "a" && trim($e->attr['alt']) == "");
	}

	public static function check_8($e, $content_dom)
	{
		if (!file_exists($e->attr["src"])) return isset($e->attr['longdesc']);
		
		$dimensions = getimagesize($e->attr["src"]);
		
		return !($dimensions[0] > 50 && $dimensions[1] > 50 && !isset($e->attr['longdesc']));
	}

	public static function check_9($e, $content_dom)
	{
		return !(isset($e->attr['longdesc']) && (trim($e->next_sibling()->tag) <> "a") || trim($e->next_sibling()->innertext) <> "[d]");
	}

	public static function check_10($e, $content_dom)
	{
		if (substr(trim($e->attr['src']), -4) == ".gif" && !file_exists($e->attr['src'])) return false;
		
		return !(substr(trim($e->attr['src']), -4) == ".gif" && intval($e->attr['width']) > 25 && intval($e->attr['height']) > 25);
	}

	public static function check_11($e, $content_dom)
	{
		if (!file_exists($e->attr["src"])) return false;
		
		$dimensions = getimagesize($e->attr["src"]);
		
		return !($dimensions[0] > 50 && $dimensions[1] > 50);
	}

	public static function check_12($e, $content_dom)
	{
		return !isset($e->attr["ismap"]) || (isset($e->attr["ismap"]) && isset($e->attr["usemap"]));
	}

	public static function check_13($e, $content_dom)
	{
		if (isset($e->attr["usemap"]))
		{
			$map_name = substr($e->attr["usemap"], 1);  // remove heading #
			
			// find definition of <map> with $map_name
			$map_found = false;
			foreach($content_dom->find("map") as $map)
			{
				if ($map->attr["name"] == $map_name)
				{
					$map_found = true;
					$area_hrefs = array();

					foreach ($map->children() as $map_child)
					{
						if ($map_child->tag == "area")
							array_push($area_hrefs, array("href"=>trim($map_child->attr["href"]), "found" => false));
					}
					
					break;  // stop at finding <map> with $map_name
				}
			}
			
			// return false <map> with $map_name is not defined
			if (!$map_found) return false; 
			
			foreach($content_dom->find("a") as $a)
			{
				foreach ($area_hrefs as $i => $area_href)
					if ($a->attr["href"] == $area_href["href"])
					{
						$area_hrefs[$i]["found"] = true;
						break;
					}
			}

			$all_href_found = true;
			foreach ($area_hrefs as $area_href)
				if (!$area_href["found"])
				{
					$all_href_found = false;
					break;
				}
			
			// return false when not all area href are defined
			if (!$all_href_found) return false; 
		}
		
		// return true when usemap is not defined
		return true;
	}

	public static function check_14($e, $content_dom)
	{
		if (!file_exists($e->attr["src"])) return false;

		$dimensions = getimagesize($e->attr["src"]);
		
		return !($dimensions[0] > 100 && $dimensions[1] > 100);
	}

	public static function check_15($e, $content_dom)
	{
		return !($e->parent()->tag == "a");
	}

	public static function check_16($e, $content_dom)
	{
		return (strlen(trim($e->attr["alt"])) == 0);
	}

	public static function check_17($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["href"]), -4));
		
		return !($ext == ".wav" || $ext == ".snd" || $ext == ".mp3" || $ext == ".iff" || $ext == ".svx" || $ext == ".sam" || 
						$ext == ".vce" || $ext == ".vox" || $ext == ".pcm" || $ext == ".aif" || $ext == ".smp");
	}

	public static function check_18($e, $content_dom)
	{
		$target_val = strtolower(trim($e->attr["target"]));
		
		return (isset($e->attr["target"]) && ($target_val == "_self" || $target_val == "_top" || $target_val == "_parent"));
	}

	public static function check_19($e, $content_dom)
	{
		return (strlen(trim($e->innertext)) == 0);
	}

	public static function check_20($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["href"]), -4));
		
		return !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif");
	}

	public static function check_21($e, $content_dom)
	{
		return false;
	}

	public static function check_22($e, $content_dom)
	{
		return false;
	}

	public static function check_23($e, $content_dom)
	{
		return false;
	}

	public static function check_24($e, $content_dom)
	{
		return false;
	}

	public static function check_25($e, $content_dom)
	{
		return false;
	}

	public static function check_26($e, $content_dom)
	{
		return false;
	}

	public static function check_27($e, $content_dom)
	{
		return false;
	}

	public static function check_28($e, $content_dom)
	{
		return false;
	}

	public static function check_29($e, $content_dom)
	{
		return (count($content_dom->find("doctype")) > 0);
	}

	public static function check_30($e, $content_dom)
	{
		return false;
	}

	public static function check_31($e, $content_dom)
	{
		return (isset($e->attr["title"]));
	}

	public static function check_32($e, $content_dom)
	{
		return false;
	}

	public static function check_33($e, $content_dom)
	{
		return false;
	}

	public static function check_34($e, $content_dom)
	{
		$num_of_frame = 0;
		
		foreach ($e->children() as $child)
			if ($child->tag == "frame") $num_of_frame++;
		
		return ($num_of_frame>=3) ? isset($e->attr["longdesc"]):true;
	}

	public static function check_35($e, $content_dom)
	{
		$num_of_noframes = 0;
		
		foreach ($e->children() as $child)
			if ($child->tag == "noframes") $num_of_frame++;
		
		return ($num_of_frame>=1);
	}

	public static function check_36($e, $content_dom)
	{
		return false;
	}

	public static function check_37($e, $content_dom)
	{
		return BasicChecks::check_next_header_not_in($content_dom, $e->linenumber, $e->colnumber, array("h1", "h2"));
	}

	public static function check_38($e, $content_dom)
	{
		return BasicChecks::check_next_header_not_in($content_dom, $e->linenumber, $e->colnumber, array("h1", "h2", "h3"));
	}

	public static function check_39($e, $content_dom)
	{
		return BasicChecks::check_next_header_not_in($content_dom, $e->linenumber, $e->colnumber, array("h1", "h2", "h3", "h4"));
	}

	public static function check_40($e, $content_dom)
	{
		return BasicChecks::check_next_header_not_in($content_dom, $e->linenumber, $e->colnumber, array("h1", "h2", "h3", "h4", "h5"));
	}

	public static function check_41($e, $content_dom)
	{
		return BasicChecks::check_next_header_not_in($content_dom, $e->linenumber, $e->colnumber, array("h1", "h2", "h3", "h4", "h5", "h6"));
	}

	public static function check_42($e, $content_dom)
	{
		return false;
	}

	public static function check_43($e, $content_dom)
	{
		return false;
	}

	public static function check_44($e, $content_dom)
	{
		return false;
	}

	public static function check_45($e, $content_dom)
	{
		return false;
	}

	public static function check_46($e, $content_dom)
	{
		return false;
	}

	public static function check_47($e, $content_dom)
	{
		return false;
	}

	public static function check_48($e, $content_dom)
	{
		return (isset($e->attr["lang"]) || isset($e->attr["xml:lang"]));
	}

	public static function check_49($e, $content_dom)
	{
		$lang_code = trim($e->attr["lang"]);
		$xml_lang_code = trim($e->attr["xml:lang"]);
		
		// set default
		$is_lang_code_valid = true;
		$is_xml_lang_code_valid = true;

		if ($lang_code <> "") $is_lang_code_valid = BasicChecks::valid_lang_code($lang_code);
		if ($xml_lang_code <> "") $is_xml_lang_code_valid = BasicChecks::valid_lang_code($xml_lang_code);

		return ($is_lang_code_valid && $is_xml_lang_code_valid);
	}

	public static function check_50($e, $content_dom)
	{
		foreach ($e->children() as $child)
			if (trim($child->tag) == "title") 
				return true;

		return false;
	}

	public static function check_51($e, $content_dom)
	{
		return (strlen(trim($e->innertext)) > 0);
	}

	public static function check_52($e, $content_dom)
	{
		return (strlen(trim($e->innertext)) < 150);
	}

	public static function check_53($e, $content_dom)
	{
		$title_content = trim($e->innertext);

		return ($title_content<>"title" && $title_content<>"the title" && 
						$title_content<>"this is the title" && $title_content<>"untitled document");
	}

	public static function check_54($e, $content_dom)
	{
		return false;
	}

	public static function check_55($e, $content_dom)
	{
		return ($e->attr["type"] == "hidden");
	}

	public static function check_57($e, $content_dom)
	{
		if (trim($e->attr["type"]) <> "text") return true;

		return BasicChecks::has_associated_label($e, $content_dom);
	}

	public static function check_58($e, $content_dom)
	{
		return (trim($e->attr["type"]) <> "image" || (trim($e->attr["type"]) == "image" && isset($e->attr["alt"])));
	}

	public static function check_59($e, $content_dom)
	{
		return !(trim($e->attr["type"]) == "image");
	}

	public static function check_60($e, $content_dom)
	{
		if ($e->attr["type"] <> "image") return true;  // only check input type "image"
		
		$lang_code = BasicChecks::get_lang_code($content_dom);

		if ($lang_code == "ger" || $lang_code == "de")
			return (strlen(trim($e->attr["alt"])) <= 115);
		else if ($lang_code == "kor" || $lang_code == "ko")
			return (strlen(trim($e->attr["alt"])) <= 90);
		else
			return (strlen(trim($e->attr["alt"])) <= 100);
	}

	public static function check_61($e, $content_dom)
	{
		if ($e->attr["type"] <> "image") return true;  // only check input type "image"

		$src = trim($e->attr["src"]);
		$alt = trim($e->attr["alt"]);
		
		if ($src <> "" && $alt <> "")
			return ($src <> $alt);
	}

	public static function check_62($e, $content_dom)
	{
		if ($e->attr["type"] <> "image") return true;  // only check input type "image"

		$alt = trim($e->attr["alt"]);
		
		if ($alt == "image" || $alt == "photo" || stripos($alt, "bytes"))
			return false;
		else
			return true;
	}

	public static function check_63($e, $content_dom)
	{
		if ($e->attr["type"] <> "text") return true;  // only check input type "image"
		
		return (isset($e->attr["value"]) && strlen(trim($e->attr["value"])) > 3);
	}

	public static function check_64($e, $content_dom)
	{
		return (isset($e->attr["alt"]));
	}

	public static function check_65($e, $content_dom)
	{
		return false;
	}

	public static function check_66($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["href"]), -4));

		return !($ext == ".wav" || $ext == ".snd" || $ext == ".mp3" || $ext == ".iff" || $ext == ".svx" || $ext == ".sam" || 
						$ext == ".vce" || $ext == ".vox" || $ext == ".pcm" || $ext == ".aif" || $ext == ".smp");
	}

	public static function check_68($e, $content_dom)
	{
		$target_val = strtolower(trim($e->attr["target"]));

		return (isset($e->attr["target"]) && ($target_val == "_self" || $target_val == "_top" || $target_val == "_parent"));
	}

	public static function check_69($e, $content_dom)
	{
		return false;
	}

	public static function check_70($e, $content_dom)
	{
		foreach ($e->children() as $child)
		{
			if ($child->tag == "li" && strlen(trim($child->innertext)) > 0)
				return true;
		}
		
		return false;
	}

	public static function check_71($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["http-equiv"]))=="refresh" && substr(strtolower(trim($e->attr["content"])), 0, 7)=="http://");
	}

	public static function check_72($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["http-equiv"]))=="refresh" && intval(strtolower(trim($e->attr["content"]))) > 0);
	}

	public static function check_73($e, $content_dom)
	{
		return false;
	}

	public static function check_74($e, $content_dom)
	{
		return (!isset($e->attr["codebase"]) || trim($e->attr["codebase"]) == "");
	}

	public static function check_75($e, $content_dom)
	{
		return (!isset($e->attr["codebase"]) || trim($e->attr["codebase"]) == "");
	}

	public static function check_76($e, $content_dom)
	{
		return (!isset($e->attr["codebase"]) || trim($e->attr["codebase"]) == "");
	}

	public static function check_77($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="video");
	}

	public static function check_78($e, $content_dom)
	{
		return (isset($e->attr["title"]));
	}

	public static function check_79($e, $content_dom)
	{
		return (trim($e->attr["title"])<>"");
	}

	public static function check_80($e, $content_dom)
	{
		return (trim($e->innertext)<>"");
	}

	public static function check_81($e, $content_dom)
	{
		$count = 0;
		foreach ($e->children() as $child)
		{
			if ($child->tag == "li" && strlen(trim($child->innertext)) > 0)
				$count++;
			
			if ($count == 2) return true;
		}
		
		return false;
	}

	public static function check_82($e, $content_dom)
	{
		$children = $e->children();
		
		if (count($children) == 1)
		{
			$child = $children[0];
			
			$tag = $child->tag;
			
			if (($tag == "b" || $tag == "i" || $tag == "u" || $tag == "strong" || $tag == "font" || $tag == "em") && $child->plaintext == $e->plaintext)
				return false;
		}
		return true;
	}

	public static function check_83($e, $content_dom)
	{
		return false;
	}

	public static function check_84($e, $content_dom)
	{
		return false;
	}

	public static function check_85($e, $content_dom)
	{
		return false;
	}

	public static function check_86($e, $content_dom)
	{
		return false;
	}

	public static function check_87($e, $content_dom)
	{
		return false;
	}

	public static function check_88($e, $content_dom)
	{
		return false;
	}

	public static function check_89($e, $content_dom)
	{
		return false;
	}

	public static function check_90($e, $content_dom)
	{
		return !(BasicChecks::has_parent($e, "body") && $e->next_sibling()->tag <> "noscript");
	}

	public static function check_91($e, $content_dom)
	{
		return BasicChecks::has_associated_label($e, $content_dom);
	}

	public static function check_92($e, $content_dom)
	{
		return !(isset($e->attr["onchange"]));
	}

	public static function check_93($e, $content_dom)
	{
		return false;
	}

	public static function check_94($e, $content_dom)
	{
		return false;
	}

	public static function check_95($e, $content_dom)
	{
		return BasicChecks::has_associated_label($e, $content_dom);
	}

	public static function check_96($e, $content_dom)
	{
		return BasicChecks::is_label_closed($e);
	}

	public static function check_97($e, $content_dom)
	{
		return !(isset($e->attr["rel"]) && strtolower(trim($e->attr["rel"])) == "stylesheet");
	}

	public static function check_98($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) <= 10);
	}

	public static function check_99($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) <= 10);
	}

	public static function check_100($e, $content_dom)
	{
		return (isset($e->attr["cite"]));
	}

	public static function check_101($e, $content_dom)
	{
		return true;
	}

	public static function check_102($e, $content_dom)
	{
		return !(isset($e->attr["onclick"]) && !isset($e->attr["onkeypress"]));
	}

	public static function check_103($e, $content_dom)
	{
		return !(isset($e->attr["ondblclick"]));
	}

	public static function check_104($e, $content_dom)
	{
		return !(isset($e->attr["onmousedown"]) && !isset($e->attr["onkeydown"]));
	}

	public static function check_105($e, $content_dom)
	{
		return !(isset($e->attr["onmousemove"]));
	}

	public static function check_106($e, $content_dom)
	{
		return !(isset($e->attr["onmouseout"]) && !isset($e->attr["onblur"]));
	}

	public static function check_107($e, $content_dom)
	{
		return !(isset($e->attr["onmouseover"]) && !isset($e->attr["onfocus"]));
	}

	public static function check_108($e, $content_dom)
	{
		return !(isset($e->attr["onmouseup"]) && !isset($e->attr["onkeyup"]));
	}

	public static function check_109($e, $content_dom)
	{
		global $first_occurrence;
		
		if (!isset($first_occurrence)) $first_occurrence=false;
		
		if (isset($e->attr["style"]) && !$first_occurrence) 
		{
			$first_occurrence = true;
			return false;
		}
		else return true;
	}

	public static function check_110($e, $content_dom)
	{
		$lang_code = BasicChecks::get_lang_code($content_dom);

		if ($lang_code == "en" || $lang_code == "eng")
			return (strlen(trim($e->plaintext)) <= 10);
		else
			return true;
	}

	public static function check_111($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return !($is_data_table && !isset($e->attr["summary"]));
	}

	public static function check_112($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return !($is_data_table && isset($e->attr["summary"]) && trim($e->attr["summary"])=="");
	}

	public static function check_113($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return !($is_data_table && isset($e->attr["summary"]) && strlen(trim($e->attr["summary"]))<11);
	}

	public static function check_114($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return !(!$is_data_table && strlen(trim($e->attr["summary"]))>0);
	}

	public static function check_115($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		if ($is_data_table) return true;
		
		$children = $e->children();

		if ($children[0]->tag == "caption") return false;
		else return true;
	}

	public static function check_116($e, $content_dom)
	{
		return false;
	}

	public static function check_117($e, $content_dom)
	{
		return false;
	}

	public static function check_118($e, $content_dom)
	{
		if (strtolower(trim($e->attr["type"]))<>"password") return true;

		return BasicChecks::has_associated_label($e, $content_dom);
	}

	public static function check_119($e, $content_dom)
	{
		if (strtolower(trim($e->attr["type"]))<>"checkbox") return true;

		return BasicChecks::has_associated_label($e, $content_dom);
	}

	public static function check_120($e, $content_dom)
	{
		if (strtolower(trim($e->attr["type"]))<>"file") return true;

		return BasicChecks::has_associated_label($e, $content_dom);
	}
	
	public static function check_121($e, $content_dom)
	{
		if (strtolower(trim($e->attr["type"]))<>"radio") return true;

		return BasicChecks::has_associated_label($e, $content_dom);
	}
	
	public static function check_122($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="password" && !BasicChecks::is_label_closed($e));
	}
	
	public static function check_123($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="checkbox" && !BasicChecks::is_label_closed($e));
	}
	
	public static function check_124($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="file" && !BasicChecks::is_label_closed($e));
	}
	
	public static function check_125($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="radio" && !BasicChecks::is_label_closed($e));
	}
	
	public static function check_126($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "text" && trim($e->attr["value"]) == "");
	}
	
	public static function check_127($e, $content_dom)
	{
		return !(isset($e->attr["classid"]) && strlen(trim($e->plaintext)) > 0);
	}
	
	public static function check_128($e, $content_dom)
	{
		return !(isset($e->attr["classid"]) && strlen(trim($e->plaintext)) > 0);
	}
	
	public static function check_129($e, $content_dom)
	{
		return !(isset($e->attr["classid"]) && strlen(trim($e->plaintext)) > 0);
	}
	
	public static function check_131($e, $content_dom)
	{
		return (strlen(trim($e->plaintext))<=10);
	}
	
	public static function check_132($e, $content_dom)
	{
		return !isset($e->attr["ismap"]);
	}
	
	public static function check_133($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return $is_data_table;
	}
	
	public static function check_134($e, $content_dom)
	{
		$next_sibling = $e->next_sibling();
		
		if ($next_sibling->tag <> "a") return true;
		
		// check if there's other text in between $e and its next sibling
		$pattern = "/". preg_quote($e->outertext, '/')."(.*)". preg_quote($next_sibling->outertext, '/') ."/";
		preg_match($pattern, $e->parent->innertext, $matches);

		return (strlen(trim($matches[1])) > 0);
	}
	
	public static function check_135($e, $content_dom)
	{
		return !(intval($e->attr["width"]) > 100 && intval($e->attr["height"]) > 100 );
	}
	
	public static function check_136($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return $is_data_table;
	}
	
	public static function check_137($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		return !$is_data_table;
	}
	
	public static function check_138($e, $content_dom)
	{
		return !($e->attr["type"]=="text" && strlen(trim($e->attr["tabindex"])) < 1);
	}
	
	public static function check_139($e, $content_dom)
	{
		return !($e->attr["type"]=="radio" && strlen(trim($e->attr["tabindex"])) < 1);
	}
	
	public static function check_140($e, $content_dom)
	{
		return !($e->attr["type"]=="password" && strlen(trim($e->attr["tabindex"])) < 1);
	}
	
	
	public static function check_141($e, $content_dom)
	{
		return !($e->attr["type"]=="checkbox" && strlen(trim($e->attr["tabindex"])) < 1);
	}
	
	public static function check_142($e, $content_dom)
	{
		return !($e->attr["type"]=="file" && strlen(trim($e->attr["tabindex"])) < 1);
	}
	
	public static function check_143($e, $content_dom)
	{
		$address = $e->find("address");
		
		return (count($address) > 0);
	}
	
	public static function check_144($e, $content_dom)
	{
		return false;
	}
	
	public static function check_145($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["href"]), -4));

		return !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif");
	}
	
	public static function check_146($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "video");
	}
	
	public static function check_147($e, $content_dom)
	{
		// if no <link> element is defined or "rel" in all <link> elements are "stylesheet" or "alternate", return false
		foreach ($e->children() as $child)
		{
			if ($child->tag == "link")
			{
				$rel_val = strtolower(trim($child->attr["rel"]));
				
				if ($rel_val <> "stylesheet" && $rel_val <> "alternate")
					return true;
			}
		}
		
		return false;
	}
	
	public static function check_148($e, $content_dom)
	{
		// if no <link> element is defined or "rel" in all <link> elements are not "alternate" or href is not defined, return false
		foreach ($e->children() as $child)
		{
			if ($child->tag == "link")
			{
				$rel_val = strtolower(trim($child->attr["rel"]));
				
				if ($rel_val == "alternate" && isset($child->attr["href"]))
					return true;
			}
		}
		
		return false;
	}
	
	public static function check_151($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		if (!$is_data_table) return true;
		
		foreach ($e->children() as $child)
			if ($child->tag == "caption") return true;
			
		return false;
	}
	
	public static function check_152($e, $content_dom)
	{
		return !(strlen(trim($e->plaintext)) > 20 && !isset($e->attr["abbr"]));
	}
	
	public static function check_153($e, $content_dom)
	{
		return (strlen(trim($e->attr["abbr"])) <= 20);
	}
	
	public static function check_154($e, $content_dom)
	{
		return false;
	}
	
	public static function check_159($e, $content_dom)
	{
		return !isset($e->attr["title"]);
	}
	
	public static function check_160($e, $content_dom)
	{
		return false;
	}

	public static function check_163($e, $content_dom)
	{
		if ($e->next_sibling()->tag=="noembed") return true;
		
		foreach ($e->children() as $child)
			if ($child->tag == "noembed") return true;
			
		return false;
	}
	
	public static function check_164($e, $content_dom)
	{
		return false;
	}
	
	public static function check_165($e, $content_dom)
	{
		return isset($e->attr["alt"]);
	}
	
	public static function check_166($e, $content_dom)
	{
		return (trim($e->attr["alt"]) <> "");
	}
	
	public static function check_167($e, $content_dom)
	{
		return !isset($e->attr["longdesc"]);
	}
	
	public static function check_168($e, $content_dom)
	{
		return BasicChecks::is_radio_buttons_grouped($e);
	}
	
	public static function check_169($e, $content_dom)
	{
		$num_of_options = BasicChecks::count_children_by_tag($e, "option");
		$num_of_optgroups = BasicChecks::count_children_by_tag($e, "optgroup");
		
		return !($num_of_options > 3 && $num_of_optgroups < 2);
	}
	
	public static function check_173($e, $content_dom)
	{
		return !(stristr($e->plaintext, "click here") || stristr($e->plaintext, "more"));
	}
	
	public static function check_174($e, $content_dom)
	{
		// check if "a" has "img" child with attribute "alt" defined
		foreach ($e->children() as $child)
			if ($child->tag == "img") $len_of_img_alt = strlen(trim($child->attr["alt"]));
			
		return (strlen(trim($e->plaintext)) > 0 || strlen(trim($e->attr["title"])) > 0 || $len_of_img_alt > 0);
	}
	
	public static function check_175($e, $content_dom)
	{
		// check if "a" has "img" child with attribute "alt" defined
		foreach ($e->children() as $child)
			if ($child->tag == "img") $txt_img_alt = strtolower(trim($child->attr["alt"]));

		return !($txt_img_alt <> "" && $txt_img_alt == strtolower(trim($e->plaintext)));
	}
	
	public static function check_176($e, $content_dom)
	{
		return false;
	}
	
	public static function check_177($e, $content_dom)
	{
		return false;
	}
	
	public static function check_178($e, $content_dom)
	{
		return BasicChecks::has_parent($e, "a");
	}
	
	public static function check_179($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) <= 10);
	}
	
	public static function check_180($e, $content_dom)
	{
		$next_sibling = $e->next_sibling();
		
		return !(strtolower(trim($e->attr["href"])) == strtolower(trim($next_sibling->attr["href"])));
	}

	
	public static function check_181($e, $content_dom)
	{
		return (substr(strtolower(trim($e->attr["href"])), 0, 11) <> "javascript:");
	}
	
	public static function check_182($e, $content_dom)
	{
		return !isset($e->attr["background"]);
	}
	
	public static function check_183($e, $content_dom)
	{
		$num_of_embed = BasicChecks::count_children_by_tag($e, "embed");
		
		return ($num_of_embed > 0);
	}
	
	public static function check_184($e, $content_dom)
	{
		return false;
	}
	
	public static function check_185($e, $content_dom)
	{
		global $has_duplicate_attribute;

		$has_duplicate_attribute = false;
		$id_array = array();
		
		BasicChecks::has_duplicate_attribute($e, "id", $id_array);

		return !$has_duplicate_attribute;
	}
	
	public static function check_186($e, $content_dom)
	{
		return (BasicChecks::count_children_by_tag($e, "input") == 0);
	}
	
	public static function check_187($e, $content_dom)
	{
		global $has_duplicate_attribute;

		$has_duplicate_attribute = false;
		$id_array = array();
		
		BasicChecks::has_duplicate_attribute($e, "for", $id_array);

		return !$has_duplicate_attribute;
	}
	
	public static function check_188($e, $content_dom)
	{
		return BasicChecks::associated_label_has_text($e, $content_dom);
	}
	
	public static function check_189($e, $content_dom)
	{
		return false;
	}
	
	public static function check_190($e, $content_dom)
	{
		return isset($e->attr["title"]);
	}

	
	public static function check_191($e, $content_dom)
	{
		return !(!isset($e->attr["href"]) || (isset($e->attr["href"]) && isset($e->attr["title"])));
	}
	
	public static function check_192($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="image" && (strtolower(trim($e->attr["alt"]))=="submit" || strtolower(trim($e->attr["alt"]))=="button"));
	}
	
	public static function check_193($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="image");
	}
	
	public static function check_194($e, $content_dom)
	{
		return false;
	}
	
	public static function check_195($e, $content_dom)
	{
		return !($e->parent()->tag == "a" && (substr(strtolower(trim($e->attr["alt"])), 0, 7)=="link to" || substr(strtolower(trim($e->attr["alt"])), 0, 5)=="go to"));
	}
	
	public static function check_196($e, $content_dom)
	{
		return !(isset($e->attr["ismap"]));
	}
	
	public static function check_197($e, $content_dom)
	{
		return !(isset($e->attr["href"]));
	}
	
	public static function check_198($e, $content_dom)
	{
		return false;
	}
	
	public static function check_199($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) > 0);
	}
	
	public static function check_200($e, $content_dom)
	{
		return !(strtolower(trim($e->plaintext)) == "legend");
	}

	public static function check_201($e, $content_dom)
	{
		return !(strlen(trim($e->attr["title"])) == 0);
	}

	public static function check_202($e, $content_dom)
	{
		return (strtolower(trim($e->attr["title"])) <> "title" && strtolower(trim($e->attr["title"])) <> "the title" && strtolower(trim($e->attr["title"])) <> "frame title");
	}

	public static function check_203($e, $content_dom)
	{
		return !isset($e->attr["summary"]);
	}

	public static function check_204($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="radio" && !BasicChecks::associated_label_has_text($e, $content_dom));
	}

	public static function check_205($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "file");
	}

	public static function check_206($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="checkbox" && !BasicChecks::associated_label_has_text($e, $content_dom));
	}

	public static function check_207($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="password" && !BasicChecks::associated_label_has_text($e, $content_dom));
	}

	public static function check_208($e, $content_dom)
	{
		return BasicChecks::associated_label_has_text($e, $content_dom);
	}

	public static function check_209($e, $content_dom)
	{
		return BasicChecks::is_label_closed($e);
	}

	public static function check_210($e, $content_dom)
	{
		return false;
	}

	public static function check_211($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "text" && !BasicChecks::is_label_closed($e));
	}

	public static function check_212($e, $content_dom)
	{
		return BasicChecks::associated_label_has_text($e, $content_dom);
	}

	public static function check_213($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="text" && !BasicChecks::associated_label_has_text($e, $content_dom));
	}

	public static function check_214($e, $content_dom)
	{
		return false;
	}

	public static function check_216($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="file" && !BasicChecks::associated_label_has_text($e, $content_dom));
	}

	public static function check_217($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "password");
	}

	public static function check_218($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "text");
	}

	public static function check_219($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "checkbox");
	}

	public static function check_220($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) == "radio");
	}

	public static function check_221($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$textcolor = trim($e->attr["text"]);
		
		if ($bgcolor == "" && $textcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $textcolor) >= 4.99);
	}

	public static function check_222($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$linkcolor = trim($e->attr["link"]);
		
		if ($bgcolor == "" && $linkcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $linkcolor) >= 4.99);
	}

	public static function check_223($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$alinkcolor = trim($e->attr["alink"]);
		
		if ($bgcolor == "" && $alinkcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $alinkcolor) >= 4.99);
	}

	public static function check_224($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$vlinkcolor = trim($e->attr["vlink"]);
		
		if ($bgcolor == "" && $vlinkcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $vlinkcolor) >= 4.99);
	}

	public static function check_225($e, $content_dom)
	{
		$doctypes = $content_dom->find("doctype");

		if (count($doctypes) == 0) return false;
		
		foreach ($doctypes as $doctype)
		{
			foreach ($doctype->attr as $doctype_content => $garbage)
				if (stristr($doctype_content, "-//W3C//DTD HTML 4.01//EN") ||
						stristr($doctype_content, "-//W3C//DTD HTML 4.0//EN") ||
						stristr($doctype_content, "-//W3C//DTD XHTML 1.0 Strict//EN"))
					return true;
		}
		return false;
	}

	public static function check_226($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$textcolor = trim($e->attr["text"]);
		
		if ($bgcolor == "" && $textcolor == "")
			return true;
		else
			return BasicChecks::has_good_contrast_waiert($bgcolor, $textcolor);
	}

	public static function check_227($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$linkcolor = trim($e->attr["link"]);
		
		if ($bgcolor == "" && $linkcolor == "")
			return true;
		else
			return BasicChecks::has_good_contrast_waiert($bgcolor, $linkcolor);
	}

	public static function check_228($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$alinkcolor = trim($e->attr["alink"]);
		
		if ($bgcolor == "" && $alinkcolor == "")
			return true;
		else
			return BasicChecks::has_good_contrast_waiert($bgcolor, $alinkcolor);
	}

	public static function check_229($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$vlinkcolor = trim($e->attr["vlink"]);
		
		if ($bgcolor == "" && $vlinkcolor == "")
			return true;
		else
			return BasicChecks::has_good_contrast_waiert($bgcolor, $vlinkcolor);
	}

	public static function check_230($e, $content_dom)
	{
		global $is_data_table;

		BasicChecks::is_data_table($e);
		
		return !($is_data_table && 
						BasicChecks::count_children_by_tag($e, "thead") == 0 &&
						BasicChecks::count_children_by_tag($e, "tfoot") == 0 &&
						BasicChecks::count_children_by_tag($e, "tbody") == 0);
	}

	public static function check_231($e, $content_dom)
	{
		global $is_data_table;

		BasicChecks::is_data_table($e);
		
		return !($is_data_table && 
						BasicChecks::count_children_by_tag($e, "col") == 0 &&
						BasicChecks::count_children_by_tag($e, "colgroup") == 0);
	}

	public static function check_232($e, $content_dom)
	{
		global $htmlValidator;

		if (!isset($htmlValidator)) return false;
		
		return ($htmlValidator->getNumOfValidateError() == 0);
	}

	public static function check_233($e, $content_dom)
	{
		return false;
	}

	public static function check_234($e, $content_dom)
	{
		return false;
	}

	public static function check_235($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) < 11);
	}

	public static function check_236($e, $content_dom)
	{
		return !($e->next_sibling()->tag == "a" && trim($e->attr["href"]) == trim($e->next_sibling()->attr["href"]));
	}

	public static function check_237($e, $content_dom)
	{
		$submit_labels = array();
		
		foreach ($e->find("form") as $form)
		{
			foreach ($form->find("input") as $button)
			{
				$button_type = strtolower(trim($button->attr["type"]));

				if ($button_type == "submit" || $button_type == "image")
				{
					if ($button_type == "submit")
						$button_value = strtolower(trim($button->attr["value"]));
					
					if ($button_type == "image")
						$button_value = strtolower(trim($button->attr["alt"]));
					
					if (in_array($button_value, $submit_labels)) return false;
					else array_push($submit_labels, $button_value);
				}
			}
		}

		return true;
	}

	public static function check_238($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"])) <> "image" && isset($e->attr["alt"]));
	}

	public static function check_239($e, $content_dom)
	{
		return (trim($e->attr["title"]) == "");
	}

	public static function check_240($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["href"]), -4));
		
		return !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif");
	}

	public static function check_241($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) < 21);
	}

	public static function check_242($e, $content_dom)
	{
		return false;
	}

	public static function check_243($e, $content_dom)
	{
		if (trim($e->attr["summary"]) == "") return true;
		
		foreach($e->children() as $child)
			if ($child->tag == "caption") $caption = strtolower(trim($child->plaintext));
			
		return (strtolower(trim($e->attr["summary"])) <> $caption);
	}

	public static function check_244($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		if (!$is_data_table) return true;
		
		// check if the table contains both row and column headers
		list($num_of_header_rows, $num_of_header_cols) = BasicChecks::get_num_of_header_row_col($e);
		
		if ($num_of_header_rows > 0 && $num_of_header_cols > 0)
		{
			foreach ($e->find("th") as $th)
				if (!isset($th->attr["scope"])) return false;
		}
		
		return true;
	}

	public static function check_245($e, $content_dom)
	{
		global $is_data_table;
		
		$is_data_table = false;
		BasicChecks::is_data_table($e);
		
		if (!$is_data_table) return true;
		
		// check if the table contains both row and column headers
		list($num_of_header_rows, $num_of_header_cols) = BasicChecks::get_num_of_header_row_col($e);
		
		// if table has more than 1 header rows or has both header row and header column,
		// check if all "th" has "id" attribute defined and all "td" has "headers" defined
		if ($num_of_header_rows > 1 || ($num_of_header_rows > 0 && $num_of_header_cols > 0))
		{
			foreach ($e->find("th") as $th)
				if (!isset($th->attr["id"])) return false;

			foreach ($e->find("td") as $td)
				if (!isset($td->attr["headers"])) return false;
		}
		
		return true;
	}

	public static function check_246($e, $content_dom)
	{
		return false;
	}

	public static function check_247($e, $content_dom)
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
						if (BasicChecks::has_parent($e, "fieldset"))
							return BasicChecks::has_parent($e, "legend");
						else
							return false;
			}
			else
				return Checks::check_247($child, $content_dom);
		}
		
		return true;
	}

	public static function check_248($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) < 31);
	}

	public static function check_249($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) < 51);
	}

	public static function check_250($e, $content_dom)
	{
		return (strlen(trim($e->plaintext)) < 21);
	}

	public static function check_251($e, $content_dom)
	{
		return false;
	}

	public static function check_252($e, $content_dom)
	{
		$count_colors = 0;
		
		if (isset($e->attr["text"])) $count_colors++;
		if (isset($e->attr["link"])) $count_colors++;
		if (isset($e->attr["alink"])) $count_colors++;
		if (isset($e->attr["vlink"])) $count_colors++;
		if (isset($e->attr["bgcolor"])) $count_colors++;
		
		return ($count_colors == 0 || $count_colors == 5);
	}

	public static function check_253($e, $content_dom)
	{
		return false;
	}

	public static function check_254($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$textcolor = trim($e->attr["text"]);
		
		if ($bgcolor == "" && $textcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $textcolor) >= 9.99);
	}

	public static function check_255($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$linkcolor = trim($e->attr["link"]);
		
		if ($bgcolor == "" && $linkcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $linkcolor) >= 9.99);
	}

	public static function check_256($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$alinkcolor = trim($e->attr["alink"]);
		
		if ($bgcolor == "" && $alinkcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $alinkcolor) >= 9.99);
	}

	public static function check_257($e, $content_dom)
	{
		$bgcolor = trim($e->attr["bgcolor"]);
		$vlinkcolor = trim($e->attr["vlink"]);
		
		if ($bgcolor == "" && $vlinkcolor == "")
			return true;
		else
			return (BasicChecks::get_luminosity_contrast_ratio($bgcolor, $vlinkcolor) >= 9.99);
	}

	public static function check_258($e, $content_dom)
	{
		return false;
	}

	public static function check_259($e, $content_dom)
	{
		return false;
	}

	public static function check_260($e, $content_dom)
	{
		return false;
	}

	public static function check_261($e, $content_dom)
	{
		return false;
	}

	public static function check_262($e, $content_dom)
	{
		return false;
	}

	public static function check_263($e, $content_dom)
	{
		return false;
	}

	public static function check_264($e, $content_dom)
	{
		return !(strtolower(trim($e->attr["type"]))=="submit" && trim($e->attr["tabindex"]) == "");
	}

	public static function check_265($e, $content_dom)
	{
		return false;
	}

	public static function check_266($e, $content_dom)
	{
		return false;
	}

	public static function check_267($e, $content_dom)
	{
		return false;
	}

	public static function check_268($e, $content_dom)
	{
		return false;
	}

	public static function check_269($e, $content_dom)
	{
		return false;
	}

	public static function check_270($e, $content_dom)
	{
		return false;
	}

	public static function check_271($e, $content_dom)
	{
		return false;
	}

	public static function check_272($e, $content_dom)
	{
		return false;
	}

	public static function check_273($e, $content_dom)
	{
		if (isset($e->attr["lang"]))
			$lang_code = trim($e->attr["lang"]);
		else
			$lang_code = trim($e->attr["xml:lang"]);

		// return no error if language code is not specified
		if (!BasicChecks::valid_lang_code($lang_code)) return true;
		
		if ($lang_code == "heb" || $lang_code == "he" || $lang_code == "ara" || $lang_code == "ar")
			// When these 2 languages, "dir" attribute must be set and set to "rtl"
			return (strtolower(trim($e->attr["dir"])) == "rtl");
		else
			return (!isset($e->attr["dir"]) || strtolower(trim($e->attr["dir"])) == "ltr");
	}

	public static function check_274($e, $content_dom)
	{
		return !(isset($e->attr["onmouseover"]) || 
						isset($e->attr["onmouseout"]) || 
						isset($e->attr["onfocus"]) || 
						isset($e->attr["onblur"]) || 
						isset($e->attr["onchange"]));
	}

	public static function check_275($e, $content_dom)
	{
		return false;
	}

	public static function check_276($e, $content_dom)
	{
		return false;
	}
}
?>  
