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
include_once (AC_INCLUDE_PATH . "classes/VamolaBasicChecks.class.php");

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
		if (!@file_get_contents($e->attr["src"])) return isset($e->attr['longdesc']);
		
		$dimensions = getimagesize($e->attr["src"]);
		
		return !($dimensions[0] > 50 && $dimensions[1] > 50 && !isset($e->attr['longdesc']));
	}

	public static function check_9($e, $content_dom)
	{
		return !(isset($e->attr['longdesc']) && (trim($e->next_sibling()->tag) <> "a" || trim($e->next_sibling()->innertext) <> "[d]"));
	}

	public static function check_10($e, $content_dom)
	{
		if (substr(trim($e->attr['src']), -4) == ".gif" && !file_exists($e->attr['src'])) return false;
		
		return !(substr(trim($e->attr['src']), -4) == ".gif" && intval($e->attr['width']) > 25 && intval($e->attr['height']) > 25);
	}

	public static function check_11($e, $content_dom)
	{
		if (!@file_get_contents($e->attr["src"])) return false;
		
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
		if (!@file_get_contents($e->attr["src"])) return false;

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
			if ($child->tag == "noframes") $num_of_noframe++;
		
		return ($num_of_noframe>=1);
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
		return true;
		// commentato perche' da errore nel nuovo achecker
//		$lang_code = trim($e->attr["lang"]);
//		$xml_lang_code = trim($e->attr["xml:lang"]);
//		
//		// set default
//		$is_lang_code_valid = true;
//		$is_xml_lang_code_valid = true;
//
//		if ($lang_code <> "") $is_lang_code_valid = BasicChecks::valid_lang_code($lang_code);
//		if ($xml_lang_code <> "") $is_xml_lang_code_valid = BasicChecks::valid_lang_code($xml_lang_code);
//
//		return ($is_lang_code_valid && $is_xml_lang_code_valid);
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
		return true;
//		if (isset($e->attr["lang"]))
//			$lang_code = trim($e->attr["lang"]);
//		else
//			$lang_code = trim($e->attr["xml:lang"]);
//
//		// return no error if language code is not specified
//		if (!BasicChecks::valid_lang_code($lang_code)) return true;
//		
//		$rtl_lang_codes = BasicChecks::get_rtl_lang_codes();
//
//		if (in_array($lang_code, $rtl_lang_codes))
//			// When these 2 languages, "dir" attribute must be set and set to "rtl"
//			return (strtolower(trim($e->attr["dir"])) == "rtl");
//		else
//			return (!isset($e->attr["dir"]) || strtolower(trim($e->attr["dir"])) == "ltr");
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
	
	
	
	
	
	
	// CHECK UNIBO
	//Matteo Battistelli
	//h1-h6: pseudo 21
	
	// controllo che h2-h6 non siano in prima posizione
	//h2
	public static function check_995($e, $content_dom)
	{
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		if($headers_array[0]->linenumber==$e->linenumber && $headers_array[0]->colnumber==$e->colnumber)
			return false;
		else
			return true;
		
	}
	//h3
	public static function check_996($e, $content_dom)
	{
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		if($headers_array[0]->linenumber==$e->linenumber && $headers_array[0]->colnumber==$e->colnumber)
			return false;
		else
			return true;		
	}
	//h4
	public static function check_997($e, $content_dom)
	{
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		if($headers_array[0]->linenumber==$e->linenumber && $headers_array[0]->colnumber==$e->colnumber)
			return false;
		else
			return true;		
	}
	//h5
	public static function check_998($e, $content_dom)
	{
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		if($headers_array[0]->linenumber==$e->linenumber && $headers_array[0]->colnumber==$e->colnumber)
			return false;
		else
			return true;		
	}
	//h6
	public static function check_999($e, $content_dom)
	{
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		if($headers_array[0]->linenumber==$e->linenumber && $headers_array[0]->colnumber==$e->colnumber)
			return false;
		else
			return true;		
	}
	
	public static function check_1000($e, $content_dom)
	{// controllo che non si ripetano gli h1
		
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=0; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		
		$headers_array_2 = $content_dom->find('h1');
		if(sizeof($headers_array_2)>1 && ($headers_array_2[0]->linenumber!=$e->linenumber ||
 		$headers_array_2[0]->colnumber!=$e->colnumber))
		return false;
		else
		return true;
	}
	
	public static function check_1001($e, $content_dom)
	{ //h3
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' )
		return false;
		else
		return true;
	}

	public static function check_1002($e, $content_dom)
	{   //h4
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' || $headers_array[$i-1]->tag == 'h2')
		return false;
		else
		return true;
	}	

	public static function check_1003($e, $content_dom)
	{   //h5
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' || $headers_array[$i-1]->tag == 'h2' || $headers_array[$i-1]->tag == 'h3')
		return false;
		else
		return true;
	}	

	public static function check_1004($e, $content_dom)
	{   //h6
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' || $headers_array[$i-1]->tag == 'h2' || $headers_array[$i-1]->tag == 'h3' || $headers_array[$i-1]->tag == 'h4')
		return false;
		else
		return true;
	}		
	
//b, basefont, big, center, font, s, small, strike, tt, u: pseudo 2
	//b
	public static function check_1005($e, $content_dom)
	{	
		
		return false;
	}	
	//basefont
	public static function check_1006($e, $content_dom)
	{
		
		return false;
	}
	//big
	public static function check_1007($e, $content_dom)
	{
		
		return false;
	}	
	//center
	public static function check_1008($e, $content_dom)
	{
		return false;
	
	}	
	//font
	public static function check_1009($e, $content_dom)
	{
	
		return false;
	
	}
	//s
	public static function check_1010($e, $content_dom)
	{
	
		return false;
	
	}	
	//small
	public static function check_1011($e, $content_dom)
	{
	
		return false;
	
	}	
	//strike
	public static function check_1012($e, $content_dom)
	{
	
		return false;
	
	}
	//tt
	public static function check_1013($e, $content_dom)
	{
	
		return false;
	}
	//u
	public static function check_1014($e, $content_dom)
	{
	
		return false;
	}	
	
	//body: pseudo 3
	public static function check_1015($e, $content_dom)
	{

		return !( isset($e->attr["text"]) || isset($e->attr["link"]) || isset($e->attr["vlink"]) || isset($e->attr["bgcolor"]) || isset($e->attr["background"]) || isset($e->attr["alink"])  );

	}
	
	//p div: pseudo 4
	//controlla che p non contenga align
	public static function check_1016($e, $content_dom)
	{

		return !( isset($e->attr["align"]) );

	}
	//controlla che div non contenga align
	public static function check_1017($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	//controlla che caption non contenga align
	//da eliminare
	public static function check_1018($e, $content_dom)
	{

		//return !( isset($e->attr["align"])  );
		return true;

	}
	//h1 - h6 pseudo 5	
	public static function check_1019($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	public static function check_1020($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	public static function check_1021($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	public static function check_1022($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	public static function check_1023($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	public static function check_1024($e, $content_dom)
	{

		return !( isset($e->attr["align"])  );

	}
	//hr: pseudo 6
	public static function check_1025($e, $content_dom)
	{

		return !( isset($e->attr["noshade"]) || isset($e->attr["align"])  || isset($e->attr["width"]) || isset($e->attr["size"]));

	}
	
	//  applet: pseudo 7
	public static function check_1026($e, $content_dom)
	{

		return !( isset($e->attr["align"]) || isset($e->attr["hspace"])  || isset($e->attr["vspace"]) || isset($e->attr["width"]) || isset($e->attr["height"]));

	}
	
	// img object: pseudo 8
	public static function check_1027($e, $content_dom)
	{
		// Simo: levati i controlli su height e width
		/*return !( isset($e->attr["align"]) || isset($e->attr["hspace"])  || isset($e->attr["vspace"]) || isset($e->attr["width"]) || isset($e->attr["height"]) || isset($e->attr["border"]));*/

		return !( isset($e->attr["align"]) || isset($e->attr["hspace"])  || isset($e->attr["vspace"]) || isset($e->attr["border"]));
	}
	
	public static function check_1028($e, $content_dom)
	{
		// Simo: levati i controlli su height e width
		/*return !( isset($e->attr["align"]) || isset($e->attr["hspace"])  || isset($e->attr["vspace"]) || isset($e->attr["width"]) || isset($e->attr["height"]) || isset($e->attr["border"]));*/
		return !( isset($e->attr["align"]) || isset($e->attr["hspace"])  || isset($e->attr["vspace"]) || isset($e->attr["border"]));

	}


	//table: pseudo 9
	public static function check_1029($e, $content_dom)
	{

		return !( isset($e->attr["align"]) || isset($e->attr["width"])  || isset($e->attr["bgcolor"]) || isset($e->attr["frame"]) || isset($e->attr["rules"]) || isset($e->attr["border"]) || isset($e->attr["cellspacing"]) || isset($e->attr["cellpadding"]));

	}
	
	//caption: pseudo 10
	//conrolla che <caption> in <table> non contenga 'align'
	public static function check_1030($e, $content_dom)
	{

		return !( isset($e->attr["align"]));
		
	}	
	//thead tfoot tbody: pseudo 11
	public static function check_1031($e, $content_dom)
	{

		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]));

	}
	public static function check_1032($e, $content_dom)
	{

		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]));

	}		
	public static function check_1033($e, $content_dom)
	{

		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]));

	}
	//colgroup col : pseudo 12
	public static function check_1034($e, $content_dom)
	{

		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]) || isset($e->attr["width"]));

	}
	
	public static function check_1035($e, $content_dom)
	{
		
		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]) || isset($e->attr["width"]));

	}	
	//tr: pseudo 13
	public static function check_1036($e, $content_dom)
	{
		
		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]) || isset($e->attr["bgcolor"]));

	}
	//th td: pseudo 14

	public static function check_1037($e, $content_dom)
	{
		
		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]) || isset($e->attr["bgcolor"]) || isset($e->attr["height"]) || isset($e->attr["width"]) || isset($e->attr["nowrap"]));

	}
	public static function check_1038($e, $content_dom)
	{
		
 		return !( isset($e->attr["align"]) || isset($e->attr["char"]) || isset($e->attr["charoff"]) || isset($e->attr["valign"]) || isset($e->attr["bgcolor"]) || isset($e->attr["height"]) || isset($e->attr["width"]) || isset($e->attr["nowrap"]));

	}
	//input : pseudo 15
	public static function check_1039($e, $content_dom)
	{
		
 		return !( isset($e->attr["align"]) /*|| isset($e->attr["size"])*/);

	}		
	//legend : pseudo 16
	public static function check_1040($e, $content_dom)
	{
		
 		return !( isset($e->attr["align"]));

	}	
	//select: pseudo 17
	public static function check_1041($e, $content_dom)
	{
		
 		//return !( isset($e->attr["size"]));
		return true;
	}	

	//pre: pseudo 18
	public static function check_1042($e, $content_dom)
	{
		
 		return !( isset($e->attr["width"]));

	}		
	
	//style: pseudo 19
	public static function check_1043($e, $content_dom)
	{
		
 		return !( isset($e->attr["style"]));

	}	
	
	//form a: pseudo 20
	public static function check_1044($e, $content_dom)
	{
		if (isset($e->attr["target"]) && $e->attr["target"]!="_self" && $e->attr["target"]!="")
 			return ( isset($e->attr["title"]));
 		else
 			return true;

	}
	public static function check_1045($e, $content_dom)
	{
		if (isset($e->attr["target"]) && $e->attr["target"]!="_self" && $e->attr["target"]!="")
 			return ( isset($e->attr["title"]));
		else
 			return true;
	}		
	public static function check_1046($e, $content_dom)
	{
		if (isset($e->attr["target"]) && $e->attr["target"]!="_self" && $e->attr["target"]!="")
 			return !( isset($e->attr["title"]));
 		else
 			return true;

	}
	public static function check_1047($e, $content_dom)
	{
		
 		if (isset($e->attr["target"]) && $e->attr["target"]!="_self" && $e->attr["target"]!="")
 			return !( isset($e->attr["title"]));
 		else
 			return true;

	}
	//blockquote q: pseudo 22
	public static function check_1048($e, $content_dom)
	{
		
 		return ( isset($e->attr["cite"]));

	}
	public static function check_1049($e, $content_dom)
	{
		
 		return ( isset($e->attr["cite"]));

	}
	public static function check_1050($e, $content_dom)
	{
		
 		return false;

	}
	public static function check_1051($e, $content_dom)
	{
		
 		return false;

	}
	//cite: pseudo 23
	public static function check_1052($e, $content_dom)
	{
		
 		return false;

	}	
	//code: pseudo 24
	public static function check_1053($e, $content_dom)
	{
		
 		return false;

	}	
	//pre: pseudo 25
	public static function check_1054($e, $content_dom)
	{
		
 		return false;

	}		
	//div: pseudo 26
	public static function check_1055($e, $content_dom)
	{
		
		$div_array = $content_dom->find("div");
		$i=0;
		for ($i=0; $i < sizeof($div_array); $i++)
		{
			if($div_array[$i]->linenumber==$e->linenumber && $div_array[$i]->colnumber==$e->colnumber)
				break;
		}
		
		if($i < sizeof($div_array)-1) //$e isn't the last <div>
		{
			$c1=$div_array[$i]->children();
			$c2=$div_array[$i+1]->children();
			//is the same image?
			if($c1[0]->tag=="img" && $c2[0]->tag=="img" && $c1[0]->attr['src'] == $c2[0]->attr['src']/*$c1[0]->src == $c2[0]->src*/)
			{	
					return false;
			}
	 
		}
		
		return true;
	}
	
	//p: pseudo 27
	public static function check_1056($e, $content_dom)
	{
		
		$p_array = $content_dom->find("p");
		$i=0;
		for ($i=0; $i < sizeof($p_array); $i++)
		{
			if($p_array[$i]->linenumber==$e->linenumber && $p_array[$i]->colnumber==$e->colnumber)
				break;
		}
		
		if($i < sizeof($p_array)-1) //$e isn't the last <p>
		{
			$c1=$p_array[$i]->children();
			$c2=$p_array[$i+1]->children();
			//is the same image?
			if($c1[0]->tag=="img" && $c2[0]->tag=="img" && $c1[0]->attr['src'] == $c2[0]->attr['src'])
			{	
					return false;
			}
	 
		}
		
		return true;
	}	
	//~Matteo Battistelli	
	
	
	
	
	
	
	
	
	
	// Simone Spagnoli

	// Pseudocodice 28
	// Br usati per implementare le liste
	public static function check_1057($e, $content_dom)
	{
	
		$padre = $e->parent();
		// Se dopo un br c'e' una immagine faccio partire il check		
		$fratello = $e->next_sibling();
		if ($fratello->tag == "img")
		{
			$src_img = $fratello->attr["src"];
			//echo "cerco:". $src_img . " ";
			$num_repeated_img = 0;
			$figli = $padre->children();
			foreach ($figli as $child)
			{
				if ($child->tag == "img" && $child->attr["src"] == $src_img)
				{
					// Se tra i fratelli trovo un'altra immagine con lo stesso src allora l'immagine e' ripetuta
					$num_repeated_img = $num_repeated_img+1;				
				}
			}

			if ($num_repeated_img > 1)
			{
				return false;				
			}
		}	

		return true;	
	
	}
	
	// Pseudocodice 29
	// Tr (tabelle)  usate per implementare le liste
	public static function check_1058($e, $content_dom)
	{

		$table = $e->parent();
	
		$img = $e->find('img', 0);
		
		// Se dentro un tr c'e' una immagine faccio partire il check
		if ($img != null)
		{
			$src_img = $img->attr["src"]; 
		
			$num_repeated_img = 0;
			$table_row = $table->children();
			
			foreach ($table_row as $child)
			{
				if ($child->tag == "tr")
				{
					$child_img = $child->find('img',0);
					if ($child_img != null)
					{
						$src_img_child = $child_img->attr["src"];
						if ($src_img_child == $src_img)
						{
							// Se tra le altre righe trovo un'altra immagine con lo stesso src allora l'immagine e' ripetuta
							$num_repeated_img = $num_repeated_img+1;
						}					
					}				
				}	
			}
			if ($num_repeated_img > 1)
			{
				return false;				
			}	
		
		
		}

		return true;
	
	}
	
	
	// Pseudocodice 30
	// Dl deve avere come figli solo dt e dd, e dt deve per forza essere seguito da un dd
	public static function check_1059($e, $content_dom)
	{
	
		$dl_child = $e->children();
		foreach ($dl_child as $child)
			{
				if ($child->tag != "dt" && $child->tag != "dd")
				{
					return false;			
				}
				elseif ($child->tag == "dt")
				{
					$dt_broth = $child->next_sibling();
					if ($dt_broth->tag != "dd")
					{
						return false;			
					}
				}
		}
		return true;
	
	}
	

	
	// Pseudocodice 32
	// Frame deve avere title o name
	public static function check_1060($e, $content_dom)
	{

		return ( isset($e->attr["title"]) || isset($e->attr["name"]) );
	
	}

	// Iframe deve avere title o name
	public static function check_1061($e, $content_dom)
	{

		return ( isset($e->attr["title"]) || isset($e->attr["name"]) );
	
	}
	

	// Frame deve avere longdesc
	public static function check_1062($e, $content_dom)
	{

		return isset($e->attr["longdesc"]);
	
	}
	
	// Iframe deve avere longdesc
	public static function check_1063($e, $content_dom)
	{

		return isset($e->attr["longdesc"]);
	
	}
	
	// Pseudocodice 33
	// Frame non deve avere frameborder, marginwidth e marginheight
	public static function check_1064($e, $content_dom)
	{

		return !( isset($e->attr["frameborder"]) || isset($e->attr["marginwidth"]) || isset($e->attr["marginheight"]) );
	
	}
	
	// Pseudocodice 34
	// Iframe non deve avere width, height, align, frameborder, marginwidth e marginheight
	public static function check_1065($e, $content_dom)
	{

		return !( isset($e->attr["width"]) || isset($e->attr["height"]) || isset($e->attr["align"]) || isset($e->attr["frameborder"]) || isset($e->attr["marginwidth"]) || isset($e->attr["marginheight"]) );
		
	}

	// Pseudocodice 35
	// Ogni frameset deve contenere noframes
	public static function check_1066($e, $content_dom)
	{
		
		foreach ($e->children() as $child)
			if ($child->tag == "noframes")
				return true;

	}
	
	// Pseudocodice 32 
	// Frame: verifica esistenza file longdesc remoto
	public static function check_1067($e, $content_dom)
	{
		
		if (($_POST["uri"]) != "http://")
		{
			if (isset($e->attr["longdesc"]))
			{	

				$ld_uri = explode("/",$_POST["uri"]);
				$ld_uri = array_slice($ld_uri, 0, sizeof($ld_uri)-1);
				$ur = implode("/", $ld_uri);

				$ld_path = $ur . "/" . $e->attr["longdesc"];
		
				$AgetHeaders = @get_headers($ld_path);
				if (preg_match("|200|", $AgetHeaders[0])) {
					return true;
				} else {
					return false;
				}
			}
		}
		return true;
	
	}
	
	// Pseudocodice 32 
	// Iframe: verifica esistenza file longdesc remoto
	public static function check_1068($e, $content_dom)
	{

		if (($_POST["uri"]) != "http://")
		{
			if (isset($e->attr["longdesc"]))
			{	

				$ld_uri = explode("/",$_POST["uri"]);
				$ld_uri = array_slice($ld_uri, 0, sizeof($ld_uri)-1);
				$ur = implode("/", $ld_uri);

				$ld_path = $ur . "/" . $e->attr["longdesc"];
		
				$AgetHeaders = @get_headers($ld_path);
				if (preg_match("|200|", $AgetHeaders[0])) {
					return true;
				} else {
					return false;
				}
			}
		}
		return true;
	
	}
	
	// Check 225
	// HTML se il doctype non e' strict ritorna false
	public static function check_1069($e, $content_dom)
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

	// Check 232
	// HTML: se il validatore markup e' attivo e trova almeno un errore ritorna false
	public static function check_1070($e, $content_dom)
	{
		global $htmlValidator;

		if (!isset($htmlValidator)) return true;
		
		return ($htmlValidator->getNumOfValidateError() == 0);
	}
	
	// Check 233
	// Frameset: se esiste l'elemento frameset nel documento ritorna falso
	public static function check_1071($e, $content_dom)
	{
		return false;
	}

	// Check 234
	// Frame: se esiste l'elemento frame nel documento ritorna falso	
	public static function check_1072($e, $content_dom)
	{
		return false;
	}

	//CHECK FILO
	
	//REQUISITO 11
	// 
	//Pseudocodice 5.19
	//Controllo presenza tag link che legano fogli di stile o style che definiscono stili interni nell'header
	// Nota di Simo: si possono unire questo e il seguente, basta che dopo il foreach si controlli la presenza di almeno un elemento di tipo Style
	
	public static function check_1073($e, $content_dom)
	{
		foreach ($e->children() as $child)
		{
			if ($child->tag == "link")
			{
				$rel_val = strtolower(trim($child->attr["rel"]));
				
				if ($rel_val == "stylesheet" && isset($child->attr["href"]))
					return false;
			}
			if($child->tag =="style"){
				return false;
			}
		}	
		return true;
	}
	
	//MB: eliminato dalla tabella ac_subgroups_checks. Era associato ai subgroup_id 1010 e 2010
	//nota MB: non è sufficiente restituire un messaggio per pagina invece che per ogni elemento con attributo style?
	//Controllo la presenza di attributi style che indicano la definizione di stili inline nei tag del body
	public static function check_1074($e, $content_dom)
	{
		// Nota di Simo: Non c'e' bisogno di questo controllo, dato che e' manuale basta segnalarlo una volta sola per pagina, basta quello sopra, commento questo e levo l'all elements dalla tabella senno' rallenta inutilmente tutto.
		return true;
 		//return !( isset($e->attr["style"]));
	}	
	
	
	// REQUISITO 3
	// Simone Spagnoli
	// Pseudocodice 36
	// Input di tipo button e image: se non esiste l'attributo alt ritorna falso	
	public static function check_3000($e, $content_dom)
	{
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			if (!isset($e->attr["alt"]))
			{
				return false;
			}
		}	
		return true;
	}

	// Input: se l'attributo alt e' di lunghezza zero ritorna falso	(alt="")
	public static function check_3001($e, $content_dom)
	{
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim == "") 
				{
					return false;
				}
			}
		}	
		return true;
	}
	
	// Input: se l'attributo alt e' di lunghezza maggiore di LUNGHEZZA_MASSIMA ritorna falso	
	public static function check_3002($e, $content_dom)
	{
		$MAX_LENGTH = 80;
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim != "" && strlen($alt) > $MAX_LENGTH ) 
				{
					return false;
				}
			}
		}	
		return true;		
	}
	
	
	// Input: se nel contenuto dell'attributo alt trovo l'estensione di una immagine ritorna falso	
	public static function check_3003($e, $content_dom)
	{
		$MAX_LENGTH = 80;
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
				{
					$pos_gif = stripos($alt,".gif");
					$pos_jpg = stripos($alt,".jpg");
					$pos_jpeg = stripos($alt,".jpeg");
					$pos_png = stripos($alt,".png");
					$pos_bmp = stripos($alt,".bmp");
					$pos_tga = stripos($alt,".tga");
					
					if($pos_gif !== FALSE || $pos_jpg !== FALSE || $pos_jpeg !== FALSE || 
					   $pos_png !== FALSE || $pos_bmp !== FALSE || $pos_tga !== FALSE ) 					
					{
						return false;
					}
				}
			}
		}	
		return true;	
	}
	
	// Input: se l'attributo alt c'e', se non e' di dimensione zero, se non e' troppo lungo, se non contiene l'estensione di una immagine, ritorna falso lo stesso per segnalare di controllare che l'alt abbia un contenuto adeguato 	
	public static function check_3004($e, $content_dom)
	{
		$MAX_LENGTH = 80;
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
				{
					$pos_gif = stripos($alt,".gif");
					$pos_jpg = stripos($alt,".jpg");
					$pos_jpeg = stripos($alt,".jpeg");
					$pos_png = stripos($alt,".png");
					$pos_bmp = stripos($alt,".bmp");
					$pos_tga = stripos($alt,".tga");
					
					if($pos_gif === FALSE && $pos_jpg === FALSE && $pos_jpeg === FALSE && 
					   $pos_png === FALSE && $pos_bmp === FALSE && $pos_tga === FALSE ) 					
					{
						return false;
					}
				}
			}
		}	
		return true;	
	}
	
	
	// Area: se non esiste l'attributo alt ritorna falso	
	public static function check_3005($e, $content_dom)
	{
		if (!isset($e->attr["alt"]))
		{
			return false;
		}	
		return true;
	}

	// Area: se l'attributo alt e' di lunghezza zero ritorna falso	(alt="")
	public static function check_3006($e, $content_dom)
	{
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim == "") 
			{
				return false;
			}
		}
		return true;
	}
	
	// Area: se l'attributo alt e' di lunghezza maggiore di LUNGHEZZA_MASSIMA ritorna falso	
	public static function check_3007($e, $content_dom)
	{
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) > $MAX_LENGTH ) 
			{
				return false;
			}
		}
	
		return true;		
	}
	
	
	// Area: se nel contenuto dell'attributo alt trovo l'estensione di una immagine ritorna falso	
	public static function check_3008($e, $content_dom)
	{
		$MAX_LENGTH = 80;
				
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif !== FALSE || $pos_jpg !== FALSE || $pos_jpeg !== FALSE || 
				   $pos_png !== FALSE || $pos_bmp !== FALSE || $pos_tga !== FALSE ) 					
				{
					return false;
				}
			}
		}

		return true;	
	}
	
	// Area: se l'attributo alt c'e', se non e' di dimensione zero, se non e' troppo lungo, se non contiene l'estensione di una immagine, ritorna falso lo stesso per segnalare di controllare che l'alt abbia un contenuto adeguato 	
	public static function check_3009($e, $content_dom)
	{
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif === FALSE && $pos_jpg === FALSE && $pos_jpeg === FALSE && 
				   $pos_png === FALSE && $pos_bmp === FALSE && $pos_tga === FALSE ) 					
				{
					return false;
				}
			}
		}
	
		return true;	
	}

	
	// Img: se non esiste l'attributo alt ritorna falso	
	public static function check_3010($e, $content_dom)
	{
		if (!isset($e->attr["alt"]))
		{
			return false;
		}	
		return true;
	}

	// Img: se l'attributo alt e' di lunghezza zero ritorna falso	(alt="")
	public static function check_3011($e, $content_dom)
	{
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim == "") 
			{
				return false;
			}
		}
		return true;
	}
	
	// Img: se l'attributo alt e' di lunghezza maggiore di LUNGHEZZA_MASSIMA ritorna falso	
	public static function check_3012($e, $content_dom)
	{
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) > $MAX_LENGTH ) 
			{
				return false;
			}
		}
	
		return true;		
	}
	
	
	// Img: se nel contenuto dell'attributo alt trovo l'estensione di una immagine ritorna falso	
	public static function check_3013($e, $content_dom)
	{
		$MAX_LENGTH = 80;
				
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif !== FALSE || $pos_jpg !== FALSE || $pos_jpeg !== FALSE || 
				   $pos_png !== FALSE || $pos_bmp !== FALSE || $pos_tga !== FALSE ) 					
				{
					return false;
				}
			}
		}

		return true;	
	}
	
	// Img: se l'attributo alt c'e', se non e' di dimensione zero, se non e' troppo lungo, se non contiene l'estensione di una immagine, ritorna falso lo stesso per segnalare di controllare che l'alt abbia un contenuto adeguato 	
	public static function check_3014($e, $content_dom)
	{
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif === FALSE && $pos_jpg === FALSE && $pos_jpeg === FALSE && 
				   $pos_png === FALSE && $pos_bmp === FALSE && $pos_tga === FALSE ) 					
				{
					return false;
				}
			}
		}
	
		return true;	
	}
	

	// Pseudocodice 37
	// Img: se validazione tramite uri e se trovo longdesc controllo se il file esiste
	public static function check_3015($e, $content_dom)
	{
		if (($_POST["uri"]) != "http://")
		{
			if (isset($e->attr["longdesc"]))
			{	

				$ld_uri = explode("/",$_POST["uri"]);
				$ld_uri = array_slice($ld_uri, 0, sizeof($ld_uri)-1);
				$ur = implode("/", $ld_uri);

				$ld_path = $ur . "/" . $e->attr["longdesc"];
		
				$AgetHeaders = @get_headers($ld_path);
				if (preg_match("|200|", $AgetHeaders[0])) {
					return true;
				} else {
					return false;
				}
			}
		}
		return true;

	}

	// Img: se esiste l'attributo longdesc avviso di controllare il contenuto
	public static function check_3016($e, $content_dom)
	{
		if (isset($e->attr["longdesc"]))
		{	
			return false;
		}
		return true;
	}
	
	//pseudocodice 2a 2.3 
	// object: verifico ricorsivamente la presenza di alternativi testuali
	public static function check_3017($e, $content_dom)
	{
		if ($e->parent()->tag!='object')
		return VamolaBasicChecks::check_obj($e,$content_dom);
		else
		return true;
	}
	
	//ritorna false se l'alternativo testuale di object contiene il nome di un file
	public static function check_3018($e, $content_dom)
	{
		$testo=VamolaBasicChecks::remove_obj($e);
		
		$estensioni= array(".jpg",".jpeg", ".gif", ".png", ".bmp", ".tga", ".mpeg", ".avi", ".mpg");
		if (isset($testo) && trim($testo)!='')// l'elemento contiene del testo
		{
			//echo($e->plaintext);
			
			foreach($estensioni as $est)
			{
			if(stripos($testo,$est) !== false)
			return false;
			
			}
		}
		return true;
	}
	
	//ritorna false per tutti gli alternativi testualti di object 
	// (eccetto per quelli che contengono il nome di un file, 
	//  per cui c'� gi� il controllo 3018)
	public static function check_3019($e, $content_dom)
	{
		if ($e->parent()->tag!='object')
		return !VamolaBasicChecks::check_obj($e,$content_dom);
		else
		return true;
	}	
	
	//REQUISITO 4
	//Pseudocodice 2.1 (Doc 3a)
	//Controllo manuale: un messaggio per ogni pagina. Controlla che il colore non sia l'unico mezzo per veicolare info.
	public static function check_4000($e, $content_dom)
	{
		return false;	
	}
	
	//REQUISITO 5
	
	//Pseudocodice 3.1 (Doc 3a)
	//Controllo la presenza della regola text-decoration : blink
	// negli elementi: p span a strong em q cite blockquote li ol dd dd dt td tr th h1 h2 h3 h4 h5 h6 label acronym abbr code pre
	//p
	public static function check_5000($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}	
	
	//span
	public static function check_5001($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}
	//a
	public static function check_5002($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//strong
	public static function check_5003($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//em
	public static function check_5004($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//q
	public static function check_5005($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//cite
	public static function check_5006($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//blockquote
	public static function check_5007($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//li
	public static function check_5008($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//ol
	public static function check_5009($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//dd
	public static function check_5010($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}
 
	//dt
	public static function check_5011($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//td
	public static function check_5012($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//tr
	public static function check_5013($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//th
	public static function check_5014($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//h1
	public static function check_5015($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//h2
	public static function check_5016($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//h3
	public static function check_5017($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//h4
	public static function check_5018($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//h5
	public static function check_5019($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//h6
	public static function check_5020($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//label
	public static function check_5021($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//acronym
	public static function check_5022($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//abbr
	public static function check_5023($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//code
	public static function check_5024($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}

	//pre
	public static function check_5025($e, $content_dom)
	{
			VamolaBasicChecks::setCssSelectors($content_dom);
			return VamolaBasicChecks::check_blink($e, $content_dom);
	}
	
	//Pseudocodice 3.2 (Doc 3a)
	//verifico presenza dell'elemento <blink>
	public static function check_5026($e, $content_dom)
	{
		
		return false;	
	}
	
	//Pseudocodice 3.3 (Doc 3a)
	//Richiede di verificare se i .gif sono animati (su img)
	public static function check_5027($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["src"]), -4));
		
		return !($ext == ".gif" );	
	}	

	//Pseudocodice 3.4 (Doc 3a)
	//Richiede di verificare se i .png sono animati (su img)
	public static function check_5028($e, $content_dom)
	{
		$ext = strtolower(substr(trim($e->attr["src"]), -4));
		
		return !($ext == ".png" );
			
	}		
	

	/**************
	* Requisito 6 *
	**************/
	
	//Pseudocodice 4.2
	public static function check_6000($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//controllo solo gli elementi testuali
		if($e->tag=="p" || $e->tag=="span" || $e->tag=="strong" || $e->tag=="em" || 
		   $e->tag=="q" || $e->tag=="cite" || $e->tag=="blockquote" || $e->tag=="li" || 
		   $e->tag=="dd" ||  $e->tag=="dt" || $e->tag=="td" ||  $e->tag=="th" || 
		   $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6" || 
		   $e->tag=="label" || $e->tag=="acronym" || $e->tag=="abbr" || $e->tag=="code" || $e->tag=="pre")
		{
			
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

			
				$background=VamolaBasicChecks::getBackground($e);
				$foreground=VamolaBasicChecks::getForeground($e);
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		}
		return 2;
		//return true;
	}
	
	public static function check_6001($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
		if($e->tag=="p" || $e->tag=="span" || $e->tag=="strong" || $e->tag=="em" || 
		   $e->tag=="q" || $e->tag=="cite" || $e->tag=="blockquote" || $e->tag=="li" || 
		   $e->tag=="dd" ||  $e->tag=="dt" || $e->tag=="td" ||  $e->tag=="th" || 
		   $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6" || 
		   $e->tag=="label" || $e->tag=="acronym" || $e->tag=="abbr" || $e->tag=="code" || $e->tag=="pre")		
		{
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$background=VamolaBasicChecks::getBackground($e);
				$foreground=VamolaBasicChecks::getForeground($e);	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		}
		return 2;
		//return true;
	}	
	
	//Pseudocodice 4.3
	//su <body>, restituisce un messaggio solo se c'è almeno un'immagine
	public static function check_6002($e, $content_dom){
	
		if (BasicChecks::count_children_by_tag($e, "img") > 0)
			return false;
		else
			return true;
	}
	
	//Pseudocodice 4.4
	

	
	public static function check_6003($e, $content_dom)
	{
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$bg=VamolaBasicChecks::get_p_css($e,"background-image");
		
		if($bg!=""){
			return false;
		}
		return true;
	}
	

	//link visitati
	public static function check_6004($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "visited");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["vlink"]))
						$foreground=$app->attr["vlink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"visited");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link visitati
	public static function check_6005($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "visited");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["vlink"]))
						$foreground=$app->attr["vlink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"visited");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);;	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}
	

	//link attivati
	public static function check_6006($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "active");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["alink"]))
						$foreground=$app->attr["alink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link attivati
	public static function check_6007($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "active");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["alink"]))
						$foreground=$app->attr["alink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);;	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}
		

	//link hover
	public static function check_6008($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "hover");
				//if($foreground=="" || $foreground==null)

				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"hover");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link hover
	public static function check_6009($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "hover");
				//if($foreground=="" || $foreground==null)

				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"hover");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);;	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}	
	
	
	//link non visitati
	public static function check_6010($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "link");
				if($foreground=="" || $foreground==null)
					$foreground=VamolaBasicChecks::getForeground($e);
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["link"]))
						$foreground=$app->attr["link"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link non visitati
	public static function check_6011($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "link");
				if($foreground=="" || $foreground==null)
					$foreground=VamolaBasicChecks::getForeground($e);
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["link"]))
						$foreground=$app->attr["link"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
				{
					$background=VamolaBasicChecks::getBackground($e);
				}	
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}	
	
	// Requisito 7 e 8
	// Pseudocodice 39
	// Img: se c'e' l'attributo ismap e' una mappa lato server, allora restituisco errore
	public static function check_7000($e, $content_dom)
	{
		if (isset($e->attr["ismap"]))
		{	
			return false;
		}
		return true;
	}	
	
	
	
	/***************
	*REQUISITO 9  *
	*			   *
	****************/	
	//Pseudocodice 2.1
	// Elemento TABLE, tipologia 2
	//
	public static function check_9000($e, $content_dom)
	{
		// Se summary non c'e' (null), oppure � vuoto (""):
		//L'elemento <table> non contiene l'attributo summary. Tale attributo � necessario per fornire una descrizione sul contenuto e sull�organizzazione della tabella.
		
		if (!isset($e->attr["summary"]) || $e->attr["summary"]=='')
			return false;
		else
			return true;
	}

	// Elemento TABLE, tipologia 1
	//
	public static function check_9001($e, $content_dom)
	{
		
		// Se summary c'e', e non � vuoto:
		//Verificare che l�attributo summary dell�elemento <table> descriva in maniera adeguata il contenuto e l�organizzazione della tabella.

		if (isset($e->attr["summary"]) && $e->attr["summary"]!='')
			return false; 
		else
			return true;
	}	
	//Pseudocodice 2.2
	// Elemento TABLE, tipologia 2
	//	
	public static function check_9002($e, $content_dom)
	{
		
		// Se non c'e' caption ne' title:
		// 
		//L'elemento <table> non contiene l'attributo title n� l'elemento <caption>, necessari per descrivere la natura della tabella.

				
		$th =$e->find("caption");
		if( ($th== null || sizeof($th)==0) && !isset($e->attr["title"]))
			return false;
		else
			return true;
	}		
	
	// Elemento TABLE, tipologia 1
	//	

	public static function check_9003($e, $content_dom)
	{
		// Se caption c'e':
		//Verificare che l'elemento <caption> descriva in maniera adeguata la natura della tabella.
		$th =$e->find("caption");
		if( ($th== null || sizeof($th)==0))
			return true;
		else
			return false;
	}			


	// Elemento TABLE, tipologia 1
	//
	public static function check_9004($e, $content_dom)
	{
		// Se title c'e':
		//Verificare che l�attributo title dell�elemento <table> descriva in maniera adeguata la natura della tabella.

		if( !isset($e->attr["title"]))
			return true;
		else
			return false;
	}

	//Pseudocodice 2.3
	// Elemento TABLE, tipologia 1
	//	
	public static function check_9005($e, $content_dom)
	{
		// Se non c'e' nessun th:	
		// Nessun elemento <th> presente all�interno della tabella. Se si tratta di una tabella dati � necessario specificarne le intestazioni tramite questo elemento.

		$th =$e->find("th");
		if( ($th== null || sizeof($th)==0))
			return false;
		else
			return true;
	}	
	// Elemento TABLE, tipologia 1
	//
	public static function check_9006($e, $content_dom)
	{
		// Se c'e' th:	
		// Verificare che gli elementi <th> della tabella siano utilizzati per specificare una intestazione e non a scopo decorativo

		$th =$e->find("th");
		if( ($th != null && sizeof($th)!=0))
			return false;
		else
			return true;
	}
	
	//Pseudocodice 2.4
	// Elemento TABLE, tipologia 2
	//
	public static function check_9007($e, $content_dom)
	{		
		// Se c'e' almeno un th e nessun abbr:	
		// Non � stato individuato alcun attributo abbr per gli elementi <th> presenti nella tabella. Nel caso di etichette di intestazione lunghe pu� essere utile fornirne abbreviazioni.


		$th =$e->find("th");
		
		if( ($th == null || sizeof($th)==0))
			return true;
		else
		{
	
			for($i=0; $i<sizeof($th); $i++)
			{
				
				if(isset($th[$i]->attr['abbr']) && $th[$i]->attr['abbr']!="")
					return true; //c'� almeno un abbr
					
			}
			//se esco dal for non ho trovato nessun abbr
			return false;
		}
	}
		
	
	
	/**************
	* Requisito 10 *
	**************/
	
	//Pseudocodice 3.1
	// Elemento TD, tipologia 0
	//
	public static function check_10000($e, $content_dom){
		
		
		//Uno degli id indicati nell�attributo headers dell�elemento <td> non esiste, cio� tale id non � associato a nessuna intestazione.
		
		
		if (!isset ($e->attr["headers"]))
			return true; //non c'� l'attributo headers
		else 
		{
			$headers=$e->attr["headers"];
			$ids=explode(' ',$headers);
			$t=VamolaBasicChecks::getTable($e);
			if($t==null)
				return false;
			return VamolaBasicChecks::checkIdInTable($t,$ids);

		}
		
		
	}
	
	// Elemento TD, tipologia 0
	//
	public static function check_10001($e, $content_dom)
	{
	
		//A questo elemento <td> non � associata nessuna intestazione. Nell�elemento l�attributo headers non � definito o � vuoto ed, inoltre, l�elemento non rientra nello scope di nessuna cella di intestazione.
		
		 if (isset ($e->attr["headers"]) && trim($e->attr["headers"])!='')
			return true; // c'� l'attributo headers
		else
		{
			$t=VamolaBasicChecks::getTable($e);
			if($t==null)
				return true;
			elseif($t->find("th")!=null)//se c'e' almeno un th e' una tabella dati
			{
			if(VamolaBasicChecks::getRowHeader($e)==null && VamolaBasicChecks::getColHeader($e)==null)
				return false;
			else 
				return true;
			}
		}
		return true;
	}	
	
	
	
	/***************
	*REQUISITO 12  *
	*			   *
	****************/
	
	//12000 - 12031: conrolli sulle misure relative e contenuto di px per tutti gli elementi
	//sulle proprietà: font-size, line-height, padding-top, padding-bottom, padding-left, 
	//padding-right, margin-top, margin -bottom, margin -left, margin –right, top, 
	//bottom, left, right, width e height
	
	public static function check_12000($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'font-size');
		
	}
	
	public static function check_12001($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'font-size');
		
	}

	public static function check_12002($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'line-height');
		
	}
	
	public static function check_12003($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'line-height');
		
	}	

	
	public static function check_12004($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-top');
	}
	
	
	public static function check_12005($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-top');

	}		
	
	
	public static function check_12006($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-bottom');

	}
	
	public static function check_12007($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-bottom');

	}	
	
	public static function check_12008($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-right');

	}
	
	public static function check_12009($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-right');
		
	}	


	public static function check_12010($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-left');

	}
	
	public static function check_12011($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-left');
		
	}	
	

	public static function check_12012($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-top');
	}
	
	
	public static function check_12013($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-top');

	}		
	
	
	public static function check_12014($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-bottom');

	}
	
	public static function check_12015($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-bottom');

	}	
	
	public static function check_12016($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-right');

	}
	
	public static function check_12017($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-right');
		
	}	


	public static function check_12018($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-left');

	}
	
	public static function check_12019($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-left');
		
	}
	
	
	public static function check_12020($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'top');

	}
	
	public static function check_12021($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'top');
		
	}	
	
	public static function check_12022($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'bottom');

	}
	
	public static function check_12023($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'bottom');
		
	}	
	
	
	public static function check_12024($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'left');

	}
	
	public static function check_12025($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'left');
		
	}	
	
	public static function check_12026($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'right');

	}
	
	public static function check_12027($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'right');
		
	}	
	
	public static function check_12028($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'width');

	}
	
	public static function check_12029($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'width');
		
	}		
	
	
	public static function check_12030($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'height');

	}
	
	public static function check_12031($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'height');
		
	}
	
	//messaggio che richiede di verificare la sovrapposizione tra elementi 
	//nel caso di ridimensionamento delle finestre
	
	public static function check_12032($e, $content_dom){
		
		return false;
	}
	

	/**************
	* Requisito 13 *
	**************/
	
	//Pseudocodice 4.1
	// Elemento TABLE, tipologia 1
	//
	public static function check_13000($e, $content_dom){
		
		// Le tabelle di layout andrebbero evitate, preferendo l�uso dei fogli di stile CSS. Se questa � una tabella di layout  si ricorda che � necessario verificare manualmente che essa sia comprensibile se letta in maniera linearizzata. 
		return false;
		
	}
	
	
	//Pseudocodice 4.2
	// Elemento TABLE, tipologia 1
	//
	public static function check_13001($e, $content_dom){
		// Se la TABLE ha th | thead | tbody | tfoot | caption:
		// Se questa � una tabella di layout, si ricorda che va evitato l�utilizzo degli elementi marcatori di struttura quali th, thead, tbody, tfoot e caption.
		
		if($e->find("th")!=null || $e->find("th")!=null || $e->find("thead")!=null || $e->find("tbody")!=null || $e->find("tfoot")!=null || $e->find("caption")!=null)
			return false;
		else 
			return true;
		
	}	
	
	
	/**************
	* Requisito 14 *
	**************/
	// Pseudocodice 2.1
	// Etichetta implicita in elemento input
	public static function check_14000($e, $content_dom)
	{
		if ($e->attr["type"] != "button" && $e->attr["type"] != "submit" &&
			$e->attr["type"] != "reset" && $e->attr["type"] != "image" && 
			$e->attr["type"] != "hidden")
		{
			return Checks::check_14002($e, $content_dom);
		}
		else
			return true;
	}
	
	// Pseudocodice 2.1
	// Etichetta implicita in elemento textarea
	public static function check_14001($e, $content_dom)
	{
		
		return Checks::check_14002($e, $content_dom);
	}
	
	// Pseudocodice 2.1
	// Etichetta implicita in elemento select
	public static function check_14002($e, $content_dom)
	{
		
		$foundLabel = FALSE;
 		$isImplicit = FALSE;
 
		$parentFormElement = $e->parent();
		$brotherFormElement = $e->prev_sibling();
		
		$idFormElement = $e->attr["id"];
		
		$labelFormElements = $content_dom->find("label");  
		foreach ($labelFormElements as $label)
		{
			if (strtolower(trim($label->attr["for"])) == strtolower(trim($idFormElement)) && $idFormElement!="")		
				$labelFormElement = $label;
		}
		
		// Etichetta esplicita
		if ( $labelFormElement != NULL || $labelFormElement != "")
		{
			$foundLabel = TRUE;
			$isImplicit = FALSE;
		}	

		// etichetta implicita come padre o fratello
	    else if ( ($parentFormElement->tag == "label" && 
		 		   $parentFormElement->attr["for"] == NULL) || 
		 		  ($brotherFormElement->tag == "label" && 
 				   $brotherFormElement->attr["for"] == NULL))
		{ 
     		 $foundLabel = TRUE;
     		 $isImplicit = TRUE;
		}
		
		if ( $foundLabel == TRUE && $isImplicit == TRUE )
		{		/*
		//MB tolgo eventuali nomi di proprietà
		
		$m_top = str_ireplace(":","",$m_top);
		$p_top = str_ireplace(":","",$p_top);
		$m_top = str_ireplace("margin-top","",$m_top);
		$p_top = str_ireplace("padding-top","",$p_top);
		$m_top = str_ireplace("margin","",$m_top);
		$p_top = str_ireplace("padding","",$p_top);					
		*/
			return false;
		}
		else
		    	return true;
	}
	
	
	// Pseudocodice 2.1
	// Nessuna etichetta esplicita in elemento input
	public static function check_14003($e, $content_dom)
	{
		$e->attr["type"]=strtolower(trim($e->attr["type"]));
		if ($e->attr["type"] != "button" && $e->attr["type"] != "submit" &&
			$e->attr["type"] != "reset" && $e->attr["type"] != "image" && 
			$e->attr["type"] != "hidden")
		{
			return Checks::check_14005($e, $content_dom);
		}
		else
			return true;
	}
	
	// Pseudocodice 2.1
	// Nessuna etichetta esplicita in elemento textarea
	public static function check_14004($e, $content_dom)
	{
		
		return Checks::check_14005($e, $content_dom);;
	}
	
	// Pseudocodice 2.1
	// Nessuna etichetta esplicita in elemento select
	public static function check_14005($e, $content_dom)
	{
		$foundLabel = FALSE;
 		$isImplicit = FALSE;
 
		$parentFormElement = $e->parent();
		$brotherFormElement = $e->prev_sibling();
		
		$idFormElement = $e->attr["id"];
		
		$labelFormElements = $content_dom->find("label");  
		foreach ($labelFormElements as $label)
		{
			if (strtolower(trim($label->attr["for"])) == strtolower(trim($idFormElement)) && $idFormElement!="")		
				$labelFormElement = $label;
		}
		
		// Etichetta esplicita
		if ( $labelFormElement != NULL || $labelFormElement != "")
		{
			$foundLabel = TRUE;
			$isImplicit = FALSE;
		}	

		// etichetta implicita come padre o fratello
	    else if ( ($parentFormElement->tag == "label" && 
		 		   $parentFormElement->attr["for"] == NULL) || 
		 		  ($brotherFormElement->tag == "label" && 
 				   $brotherFormElement->attr["for"] == NULL))
		{
     		 $foundLabel = TRUE;
     		 $isImplicit = TRUE;
		}
		
		if ( $foundLabel == TRUE && $isImplicit == TRUE )
		{
			return true;
		}
		else if ( $foundLabel == FALSE)
		{
			return false;
		}
		else
		    return true;
	}
	
	
	
	

	/***************
	* Requisito 15 *
	***************/
	
	//Pseudocodice 2.1
	// Elemento BODY, tipologia 0
	public static function check_15000($e, $content_dom){
		
		// Non � presente alcun elemento <noscript> che fornisca una versione alternativa per gli elementi <script> presenti nella pagina.
		$script=$e->find('script');
		
		if(!isset($script) || sizeof($script)==0)//c'� almeno un elemento <script>
			return true;
		else //verifico che sia presente almeno un elemento <noscript>
			{
				$noscript=$e->find('noscript');
				if(!isset($noscript) || sizeof($noscript)==0)
				return false;		
			}

		
	}
	
	//Pseudocodice 2.2
	// Elemento NOSCRIPT, tipologia 1
	public static function check_15001($e, $content_dom){
		
		// Verificare che l�elemento <noscript> fornisca le stesse funzionalit� e informazioni offerte dagli oggetti di programmazione presenti nella pagina.
		return true;
		
	}
	
	
	/* per questi richiamo i controlli 3017, 3018, 3019
	//Pseudocodice 2.3
	// Elemento OBJECT, tipologia 0
	public static function check_15002($e, $content_dom){
	
		// Vedi controlli documento 2a
	}
	
	// Elemento OBJECT, tipologia 2
	public static function check_15003($e, $content_dom){
	
		// Vedi controlli documento 2a
		return true;		
	}
	
		// Elemento OBJECT, tipologia 1
	public static function check_15004($e, $content_dom){
	
		// Vedi controlli documento 2a
		return true;		
	}
	*/
	
	//Pseudocodice 2.4
	// Elemento APPLET, tipologia 0
	public static function check_15002($e, $content_dom){
	
		
		$testo=trim($e->plaintext)=="";
		if (isset($testo) && $testo!='')
			return false;
		return true;		
	}
	
	// Elemento APPLET, tipologia 1
	public static function check_15003($e, $content_dom){
	
		//restituisce un messaggio per gli elementi che non fanno scattare il controllo sulle estensioni di file
		return !Checks::check_15002($e,$content_dom) || !Checks::check_15004($e,$content_dom);		
		
	}
	
	// Elemento APPLET, tipologia 2
	public static function check_15004($e, $content_dom){
	
		$testo=trim($e->plaintext);
		$estensioni= array(".class");
		if (isset($testo) && $testo!='')// l'elemento contiene del testo
		{
			//echo($e->plaintext);
			
			foreach($estensioni as $est)
			{
			if(stripos($testo,$est) !== false)
			return false;
			
			}
		}
		return true;
		
		

		
	}
	
	//Pseudocodice 2.5 
	// Elemento HTML, tipologia 1
	public static function check_15005($e, $content_dom){
	
		//Assicurarsi che le pagine siano utilizzabili quando script, applet, o altri oggetti di programmazione sono disabilitati o non supportati. Se ci� non fosse possibile fornire una spiegazione testuale della funzionalit� svolta e garantire un�alternativa testuale equivalente.
		return VamolaBasicChecks::rec_check_15005($e);
		
		
		return true;		
		
	}
	
	
	/***************
	* Requisito 16 *  
	***************/
	//Pseudocodice 3.1
	//vengono richiamati i check 21001 - 21007
	
	//Pseudocodice 3.2
	//body, tipologia 1
	//nota: farlo anche per applet oltre che per object?
	public static function check_16000($e, $content_dom){
		$o=$e->find('object');
		if(isset($o) && sizeof($o)>0)
			return false;
		else 
			return true;
		//Verificare che eventuali applet o oggetti di programmazione dotati di una propria specifica	interfaccia, siano indipendenti da uno specifico dispositivo di input."
		
		
	}

	
	//Controllo che alla presenza di onmouseover corrisponda la presenza di onfocus
	public static function check_16001($e, $content_dom){
	
		if(isset($e->attr["onmouseover"])){
			if(!isset($e->attr["onfocus"]))
				return false;
		}		
		return true;
	}
	
	//Controllo che alla presenza di onmouseout corrisponda la presenza di onblur
	public static function check_16002($e, $content_dom){
	
		if(isset($e->attr["onmouseout"])){
			if(!isset($e->attr["onblur"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onmousedown corrisponda la presenza di onkeydown
	public static function check_16003($e, $content_dom){
	
		if(isset($e->attr["onmousedown"])){
			if(!isset($e->attr["onkeydown"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onmouseup corrisponda la presenza di onkeyup
	public static function check_16004($e, $content_dom){
	
		if(isset($e->attr["onmouseup"])){
			if(!isset($e->attr["onkeyup"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onclick corrisponda la presenza di onkeypress
	public static function check_16005($e, $content_dom){
	
		
			if($e->tag !== "input" && ($e->attr["type"]!=="button" || $e->attr["type"]!=="submit" || $e->attr["type"]!=="reset"))
			{
				if(isset($e->attr["onclick"])){
					if(!isset($e->attr["onkeypress"]))
						return false;
				}
			}
		return true;		
	}
	
	//Controllo che non sia presente ondblclick
	public static function check_16006($e, $content_dom){
	
		if(isset($e->attr["ondblclick"])) 
				return false;
		else
			return true;		
	}
	
	//Controllo che non sia presente onmousemove
	public static function check_16007($e, $content_dom){
	
		if(isset($e->attr["onmousemove"]))
				return false;
		else
			return true;		
	}	
	
	
	

	/***************
	* Requisito 17 *
	***************/	
	//Pseudocodice 4.1 tipologia 1
	//� stato rilevato un oggetto di programmazione. Assicurarsi che le funzionalit� e le informazioni veicolate per mezzo di tale oggetto siano direttamente accessibili.
	
	//object
	public static function check_17000($e, $content_dom){
		return false;
	}
	
	//script
	public static function check_17001($e, $content_dom){
		return false;
	}	
	
	//applet
	public static function check_17002($e, $content_dom){
		return false;
	}
	
	//all elements
	public static function check_17003($e, $content_dom){
		
		if(isset($e->attr['onload']) || isset($e->attr['onunload']) || isset($e->attr['onclick']) || isset($e->attr['ondblclick'])
		   || isset($e->attr['onmousedown'])|| isset($e->attr['onmouseup'])|| isset($e->attr['onmouseover']) || isset($e->attr['onmousemove'])|| isset($e->attr['onmouse'])|| isset($e->attr['onblur'])
		   || isset($e->attr['onkeypress'])|| isset($e->attr['onkeydown'])|| isset($e->attr['onkeyup'])|| isset($e->attr['onsubmit'])|| isset($e->attr['onreset'])|| isset($e->attr['onselect'])
		   || isset($e->attr['onchange']))
		   
			return false;
		else 
			return true; 
	}
	
	
	/***************
	* Requisito 18 *
	***************/
	//consiglia di verificare la trascrizione di un filmato puntato da un link
	public static function check_18000($e, $content_dom)
	{	// come il check_20 con l'aggiunta di ".avi"
		// il check_20 esegue lo stesso controllo del check_145
		//return Checks::check_20($e, $content_dom);		
		$ext = strtolower(substr(trim($e->attr["href"]), -4));
		
		return !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif" || $ext == ".avi");

		

	}
	//consiglia di verificare la trascrizione di un filmato contenuto in un <object>
	public static function check_18001($e, $content_dom)
	{
		// il check_77 esegue lo stesso controllo del check_146
		return Checks::check_77($e, $content_dom);
	}
	/*
	public static function check_true($e, $content_dom)
	{
		return true;
	}
	*/
	
	
	
	/***************
	* Requisito 19 *
	***************/
	//pseudocodice 5.1
	//elemento a, tipologia 0
	public static function check_19000($e, $content_dom)
	{
		$t=$e->innertext();
		if(stripos($t,"click here")!==false || stripos($t,"clicca")!==false)
			return false;
		else
			return true;
		//Evitare di utilizzare frasi come "Clicca qui" o "Click here" come testo di un link. Il testo dovrebbe fornire informazioni sulla natura della destinazione del collegamento ipertestuale.
	}
	
	//elemento a, tipologia 1
	public static function check_19001($e, $content_dom)
	{
		
		return !Checks::check_19000($e, $content_dom);
		
		//Assicurarsi che il testo del link sia significativo e che sia chiara la destinazione del collegamento ipertestuale.
	}	
	
	//pseudocodice 5.2
	//elemento body, tipologia 1
	public static function check_19002($e, $content_dom)
	{
		return false;
		//Verificare che siano presenti meccanismi che consentano di evitare la lettura ripetitiva di sequenze di collegamenti comuni a pi� pagine.
		
	}
	
	/***************
	*REQUISITO 20  *
	*			   *
	****************/
	
	//controlla la presenza di <meta http-equiv=”refresh” contet=”x”>
	//E’ stato rilevato un elemento <meta http-equiv=”refresh” contet=”x”> che causa il refresh automatico della pagina entro x seconi. E’ necessario avvertire l’utente della presenza di questa funzionalità e del tempo entro il quale avverà il refresh. Inoltre deve essere fornito un meccanismo che consenta all’utente di controllare tale funzionalità o, in alternativa, un collegamento ad una versione equivalente. 
	////check sul tag <meta>
	public static function check_20000($e, $content_dom)
	{
		if(isset($e->attr["http-equiv"]) && isset($e->attr["content"]))
		{
			if(strtolower($e->attr["http-equiv"])=="refresh" && is_numeric($e->attr["content"]))
				return false;
		}
			return true;		
	}
	
	//controlla la presenza di <meta http-equiv=”refresh” contet=”x;url”>
	//E’ stato rilevato un elemento <meta http-equiv=”refresh” content=”x;url”> che causa il reindirizzamento automatico della pagina all’indirizzo url entro x secondi. E’ necessario avvertire l’utente della presenza di questa funzionalità e del tempo entro il quale avverà il reindirizzamento. Inoltre deve essere fornito un meccanismo che consenta all’utente di controllare tale funzionalità o, in alternativa, un collegamento ad una versione equivalente. 
	//check sul tag <meta>
	public static function check_20001($e, $content_dom)
	{
		if(isset($e->attr["http-equiv"]) && isset($e->attr["content"]))
		{
			if(strtolower($e->attr["http-equiv"])=="refresh" && stripos($e->attr["content"],';')!==false && is_numeric(substr($e->attr["content"],0,stripos($e->attr["content"],';'))))
				return false;
		}
			return true;		
	}
	
	
	/***************
	*REQUISITO 21  *
	*			   *
	****************/
	
	/* Pseudocodice 7.1*/
	
	//Controllo presenza href nei tag a
	public static function check_21000($e, $content_dom){
	
	if(!isset($e->attr["href"]) && !isset($e->attr["name"]) && !isset($e->attr["id"]))
			return false;
		else
			return true;
	}
	
	/* Pseudocodice 7.2 */
	
	//Controllo che alla presenza di onmouseover corrisponda la presenza di onfocus
	public static function check_21001($e, $content_dom){
	
		if(isset($e->attr["onmouseover"])){
			if(!isset($e->attr["onfocus"]))
				return false;
		}		
		return true;
	}
	
	//Controllo che alla presenza di onmouseout corrisponda la presenza di onblur
	public static function check_21002($e, $content_dom){
	
		if(isset($e->attr["onmouseout"])){
			if(!isset($e->attr["onblur"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onmousedown corrisponda la presenza di onkeydown
	public static function check_21003($e, $content_dom){
	
		if(isset($e->attr["onmousedown"])){
			if(!isset($e->attr["onkeydown"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onmouseup corrisponda la presenza di onkeyup
	public static function check_21004($e, $content_dom){
	
		if(isset($e->attr["onmouseup"])){
			if(!isset($e->attr["onkeyup"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onclick corrisponda la presenza di onkeypress
	/* MB: check rimosso
	public static function check_21005($e, $content_dom){
	
		
			if($e->tag !== "input" && ($e->attr["type"]!=="button" || $e->attr["type"]!=="submit" || $e->attr["type"]!=="reset"))
			{
				if(isset($e->attr["onclick"])){
					if(!isset($e->attr["onkeypress"]))
						return false;
				}
			}
		return true;		
	}
	*/
	//Controllo che alla presenza di onmouseout corrisponda la presenza di onblur
	public static function check_21006($e, $content_dom){
	
		if(isset($e->attr["ondblclick"])) 
				return false;
		else
			return true;		
	}
	
	//Controllo che alla presenza di onmouseout corrisponda la presenza di onblur
	public static function check_21007($e, $content_dom){
	
		if(isset($e->attr["onmousemove"]))
				return false;
		else
			return true;		
	}
	
	/*Pseudocodice 7.3 */
	
	//controllo dello spazio verticale tra link consecutivi.
	//funzione richiamata su un elemento li
	public static function check_21008($e, $content_dom){

		VamolaBasicChecks::setCssSelectors($content_dom);
		
			//relativi al fratello precedente di $e
			global $m_bottom;
			global $p_bottom;
			//relativi a $e
			global $m_top;
			global $p_top;
			
			$prev=$e->prev_sibling();
			
			if($prev== null || $prev->tag!="li")//li è il primo elemento della lista
				return true;
			
			$a =$e->find("a");
			$a_prev =$e->prev_sibling()->find("a");
			if(($a== null || sizeof($a)==0) &&($a_prev== null || sizeof($a_prev)==0))//ne' li ne' il suo prev sono link
				return true;
			
			
			//verifico che non siano inline
			$inlinea=VamolaBasicChecks::get_p_css($e, "display");
			$inlinea2=VamolaBasicChecks::get_p_css($e->prev_sibling(), "display");
			

			if(($inlinea!="" && stripos($inlinea,"inline")!==null) && ($inlinea2!=="" || stripos($inlinea2,"inline")!==null))
				return true;		
				
				
				
			VamolaBasicChecks::GetVerticalDistance($e);
			//se non sono in em ritorno false
			if($m_bottom!="" && substr($m_bottom,-2, 2)!="em" || $p_bottom!="" && substr($p_bottom,-2, 2)!="em" || $m_top!="" && substr($m_top,-2, 2)!="em" || $p_top!="" &&substr($p_top,-2, 2)!="em")
			{
				return false; 
			}
			 
			
			$m_bottom = str_ireplace("em","",$m_bottom);
			$m_top = str_ireplace("em","",$m_top);
			$p_bottom = str_ireplace("em","",$p_bottom);
			$p_top = str_ireplace("em","",$p_top);
			
		
			if($p_top=="")
				$p_top=0;
			if($p_bottom=="")
				$p_bottom=0;
			if($m_top=="")
				$m_top=0;		
			if($m_bottom=="")
				$m_bottom=0;
			
				$dist= $p_top + $p_bottom + max( $m_bottom, $m_top );
				
				if($dist<0.5){
					return false;
				}
				
			return true;	
	}
			
	/* Pseudocodice 7.4*/
	//Controllo della distanza minima orizzontale di un li in caso di liste disposte inline
	public static function check_21009($e, $content_dom){
		
			VamolaBasicChecks::setCssSelectors($content_dom);

			//relativi al fratello precedente di $e
			global $m_right;
			global $p_right;
			//relativi a $e
			global $m_left;
			global $p_left;
			
			$prev=$e->prev_sibling();
			
			if($prev== null || $prev->tag!="li")//li è il primo elemento della lista
				return true;
			
			$a =$e->find("a");
			$a_prev =$e->prev_sibling()->find("a");
			if(($a== null || sizeof($a)==0) &&($a_prev== null || sizeof($a_prev)==0))//ne' li ne' il suo prev sono link
				return true;
				
			//verifico che siano inline
			$inlinea=VamolaBasicChecks::get_p_css($e, "display");
			$inlinea2=VamolaBasicChecks::get_p_css($e->prev_sibling(), "display");
			

			if(($inlinea=="" || stripos($inlinea,"inline")===null) && ($inlinea2=="" || stripos($inlinea2,"inline")===null))
				return true;	
				
			
			VamolaBasicChecks::GetHorizontalDistance($e);
			//se non sono in em ritorno false
			if($m_right!="" && substr($m_right,-2, 2)!="em" || $p_right!="" && substr($p_right,-2, 2)!="em" || $m_left!="" && substr($m_left,-2, 2)!="em" || $p_left!="" &&substr($p_left,-2, 2)!="em")
			{
				return false; 
			}
			 
			
			$m_right = str_ireplace("em","",$m_right);
			$m_left = str_ireplace("em","",$m_left);
			$p_right = str_ireplace("em","",$p_right);
			$p_left = str_ireplace("em","",$p_left);
			
		
			if($p_right=="")
					$p_right=0;
				if($p_left=="")
					$p_left=0;
				if($m_right=="")
					$m_right=0;		
				if($m_left=="")
					$m_left=0;
				
			$dist= $p_right + $p_left + $m_right + $m_left;
			if($dist<0.5){
				return false;	
			}
				
			return true;	
	}	

	
	
//Pseudocodice 7.5
  
	//Controllo lo spazio verticale tra liste di link
	//ol
	public static function check_21010($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
	    global $m_bottom;
		global $p_bottom;	

		$a =$e->find("a");
		if($a== null || sizeof($a)==0)//ol non contiene link
			return true;
		
		
		VamolaBasicChecks::GetVerticalListBottomDistance($e);
		
		
		
		
		if(($m_bottom!="" && substr($m_bottom,-2,2)!="em") || ($p_bottom!="" && substr($p_bottom,-2,2)!="em")){
				return false;
			}
			
		
			
		$m_bottom = substr($m_bottom,0,strlen($m_bottom)-2);
		$p_bottom = substr($p_bottom,0,strlen($p_bottom)-2);
		
		
		if($p_bottom=="")  
				$p_bottom=0;
		if($m_bottom=="")
				$m_bottom=0;
						
		
		
		$dist_bottom= $p_bottom + $m_bottom;
				
			
			
			if($dist_bottom<0.5){
				return false;
			}
		
			return true;
	}
	//ol
	public static function check_21011($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_top;	
		global $p_top;
	
		$a =$e->find("a");
		if($a== null || sizeof($a)==0)//ol non contiene link
			return true;
	
		VamolaBasicChecks::GetVerticalListTopDistance($e);
		
		
		if(($m_top!="" && substr($m_top,-2,2)!="em") || ($p_top!="" && substr($p_top,-2,2)!="em")){					
			return false;
		}

				
		$m_top = substr($m_top,0,strlen($m_top)-2);
		$p_top = substr($p_top,0,strlen($p_top)-2);
		
		
		if($p_top=="")  
				$p_top=0;
		if($m_top=="")
				$m_top=0;


			$dist_top= $p_top + $m_top;
						
			if($dist_top<0.5){
				return false;
			}
		
			return true;	
	}
	//ul
	public static function check_21012($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		//richiamo check_21010
		return Checks::check_21010($e, $content_dom);

	}
	//ul
	public static function check_21013($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		// richiamo check_21011
		return Checks::check_21011($e, $content_dom);
	}
	
	//Pseudocodice 7.6
	
	//Verifica delle corrette dimensioni ridefinite in un "input" con type "button"
	public static function check_21014($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")){
			
			$h =VamolaBasicChecks::get_p_css($e, "height");
			$pl = VamolaBasicChecks::get_p_css($e, "padding-left");
			$pr = VamolaBasicChecks::get_p_css($e, "padding-right");
			$pt = VamolaBasicChecks::get_p_css($e, "padding-top");
			$pb = VamolaBasicChecks::get_p_css($e, "padding-buttom");
			
			
			
			if($h !="" || $pl!="" || $pr!="" || $pt!="" || $pb!=""){
				return false;
			}	
		}
		return true;
	}
		
	public static function check_21015($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_top;	
	
		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")) {
		
			VamolaBasicChecks::GetVerticalListTopDistance($e);
			//echo("<p>BOTTONE= ".$m_top."");
			if(($m_top!="" && substr($m_top,-2,2)!="em")){
				return false;
			}
							
			$m_top = substr($m_top,0,strlen($m_top)-2);
			
			
			//echo("<p>BOTTONE= ".$m_top."</p>");
			//MB
			if($m_top =="")
				$m_top=0;
			//MBif($m_top !="" && $m_top<0.5){
			
			if($m_top<0.5){
				return false;
			}	
		}
		return true;
	}
	
	public static function check_21016($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_bottom;	

		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")){
		
			VamolaBasicChecks::GetVerticalListBottomDistance($e);
			//echo("<p>m_bottom =".$m_bottom."");
			if(($m_bottom!="" && substr($m_bottom,-2,2)!="em")){
				return false;
			}
							
			$m_bottom = substr($m_bottom,0,strlen($m_bottom)-2);
		
			//MB
			if($m_bottom =="")
				$m_bottom=0;			
			
			//MBif($m_bottom !="" && $m_bottom<0.5){
			//echo("<p>m_bottom =".$m_bottom."");
			if($m_bottom<0.5){
				return false;
			}	
		}
		return true;
	}
	
	public static function check_21017($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
	
		global $m_left;	

		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")) {
		
			VamolaBasicChecks::GetHorizontalListLeftDistance($e);
			
			if(($m_left!="" && substr($m_left,-2,2)!="em")){
				return false;
			}
							
			$m_left = substr($m_left,0,strlen($m_left)-2);
			
			
			//MB
			if($m_left =="")
				$m_left=0;			
			
			//MB if($m_left !="" && $m_left<0.5){
			if($m_left<0.5){
				return false;
			}	
		}
		return true;
	}
	
	public static function check_21018($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_right;	

		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")){
		
			VamolaBasicChecks::GetHorizontalListRightDistance($e);
			
			if(($m_right!="" && substr($m_right,-2,2)!="em")){
				return false;
			}
							
			$m_right = substr($m_right,0,strlen($m_right)-2);
		
			//MB
			if($m_right =="")
				$m_right=0;
			
			//MB if($m_right !="" && $m_right<0.5){
			if($m_right<0.5){
				return false;
			}	
		}
		return true;
	}
		

	//Verifica delle corrette dimensioni ridefinite in un "button"
	public static function check_21019($e, $content_dom){
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
			
			$h =VamolaBasicChecks::get_p_css($e, "height");
			$pl = VamolaBasicChecks::get_p_css($e, "padding-left");
			$pr = VamolaBasicChecks::get_p_css($e, "padding-right");
			$pt = VamolaBasicChecks::get_p_css($e, "padding-top");
			$pb = VamolaBasicChecks::get_p_css($e, "padding-buttom");
			
			
			
			if($h !="" || $pl!="" || $pr!="" || $pt!="" || $pb!=""){
				return false;
			}	
		//}
		return true;
	}
		
	public static function check_21020($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
	
		global $m_top;	
	
		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetVerticalListTopDistance($e);
			//echo("<p>BOTTONE= ".$m_top."");
			if(($m_top!="" && substr($m_top,-2,2)!="em")){
				return false;
			}
							
			$m_top = substr($m_top,0,strlen($m_top)-2);
			
			
			//echo("<p>BOTTONE= ".$m_top."</p>");
			//MB
			if($m_top =="")
				$m_top=0;
			//MBif($m_top !="" && $m_top<0.5){
			
			if($m_top<0.5){
				return false;
			}	
		//}
		return true;
	}
	
	public static function check_21021($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_bottom;	

		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetVerticalListBottomDistance($e);
			//echo("<p>m_bottom =".$m_bottom."");
			if(($m_bottom!="" && substr($m_bottom,-2,2)!="em")){
				return false;
			}
							
			$m_bottom = substr($m_bottom,0,strlen($m_bottom)-2);
		
			//MB
			if($m_bottom =="")
				$m_bottom=0;			
			
			//MBif($m_bottom !="" && $m_bottom<0.5){
			//echo("<p>m_bottom =".$m_bottom."");
			if($m_bottom<0.5){
				return false;
			}	
		//}
		return true;
	}
	
	public static function check_21022($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);

		global $m_left;	

		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetHorizontalListLeftDistance($e);
			
			if(($m_left!="" && substr($m_left,-2,2)!="em")){
				return false;
			}
							
			$m_left = substr($m_left,0,strlen($m_left)-2);
			
			
			//MB
			if($m_left =="")
				$m_left=0;			
			
			//MB if($m_left !="" && $m_left<0.5){
			if($m_left<0.5){
				return false;
			}	
		//}
		return true;
	}
	
	public static function check_21023($e, $content_dom){
	
		VamolaBasicChecks::setCssSelectors($content_dom);

		global $m_right;	

		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetHorizontalListRightDistance($e);
			
			if(($m_right!="" && substr($m_right,-2,2)!="em")){
				return false;
			}
							
			$m_right = substr($m_right,0,strlen($m_right)-2);
		
			//MB
			if($m_right =="")
				$m_right=0;
			
			//MB if($m_right !="" && $m_right<0.5){
			if($m_right<0.5){
				return false;
			}	
		//}
		return true;
	}	
	
	
	/***************
	*REQUISITO 22  *
	*			   *
	****************/
	
	//Nel caso in cui la pagina validata non sia accessibile è necessario fornire in essa un link ad una pagina dal contenuto equivalente, che sia aggiornata con la stessa frequenza della pagina originale. 	
	//check sul tag <html>
	public static function check_22000($e, $content_dom){
		return false;
	}
	
	
	public static function check_23023($e, $content_dom){
		return false;
	}
	public static function check_23024($e, $content_dom){
		return true;
	}
	public static function check_23025($e, $content_dom){
		return false;
	}
	public static function check_23026($e, $content_dom){
		return false;
	}
	public static function check_23027($e, $content_dom){
		return false;
	}
	public static function check_23028($e, $content_dom){
		return false;
	}
	public static function check_23029($e, $content_dom){
		return false;
	}	
	public static function check_23030($e, $content_dom){
		return false;
	}	
	public static function check_23031($e, $content_dom){
		return false;
	}
	public static function check_23032($e, $content_dom){
		return false;
	}

	
	//attributi deprecati
	/*
	public static function check_23040($e, $content_dom){
		
		if(isset($e->attr["align"]) && ($e->tag=="caption" || $e->tag=="iframe" || $e->tag=="img" || $e->tag=="input" || $e->tag=="object" || $e->tag=="legend" || $e->tag=="table" || $e->tag=="hr" || $e->tag=="div" 
		|| $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6"  || $e->tag=="p"))
		return false;
		else
		return true;
	}
	
	
	public static function check_23041($e, $content_dom){
		
		if(isset($e->attr["alink"]) && $e->tag=="body" )
		return false;
		else
		return true;
	}
	
	public static function check_23042($e, $content_dom){
		
		if(isset($e->attr["background"]) && $e->tag=="body" )
		return false;
		else
		return true;
	}	
	
	public static function check_23043($e, $content_dom){
		
		if(isset($e->attr["bgcolor"]) && ($e->tag=="table" || $e->tag=="tr" || $e->tag=="td" || $e->tag=="th" || $e->tag=="body"))
		return false;
		
		else
		return true;
	}
	
	public static function check_23044($e, $content_dom){
		
		if(($e->tag=="img" || $e->tag=="object") && isset($e->attr['border']))
		{
						//echo("<p>".print_r($e->attr)."</p>");
		return false;
		}
		else
		return true;
	}		

	public static function check_23045($e, $content_dom){
		
		if(isset($e->attr["color"]) && ($e->tag=="br" ))
		return false;
		else
		return true;
	}		
	
	public static function check_23046($e, $content_dom){
		
		if(isset($e->attr["compact"]) && ($e->tag=="dl" || $e->tag=="ol" || $e->tag=="ul"))
		return false;
		else
		return true;
	}
	
	public static function check_23047($e, $content_dom){
		
		if(isset($e->attr["height"]) && ($e->tag=="th" || $e->tag=="td" ))
		return false;
		else
		return true;
	}		
	
	public static function check_23048($e, $content_dom){
		
		if(isset($e->attr["hspace"]) && ($e->tag=="img" || $e->tag=="object"))
		return false;
		else
		return true;
	}

	public static function check_23049($e, $content_dom){
		
		if(isset($e->attr["language"]) && ($e->tag=="script"))
		return false;
		else
		return true;
	}		

	public static function check_23050($e, $content_dom){
		
		if(isset($e->attr["link"]) && ($e->tag=="body"))
		return false;
		else
		return true;
	}	
	
	
	public static function check_23051($e, $content_dom){
		
		if(isset($e->attr["noshade"]) && ($e->tag=="hr"))
		return false;
		else
		return true;
	}
	
	public static function check_23052($e, $content_dom){
		
		if(isset($e->attr["nowrap"]) && ($e->tag=="td" || $e->tag=="th"))
		return false;
		else
		return true;
	}
	
	public static function check_23053($e, $content_dom){
		
		if(isset($e->attr["size"]) && ($e->tag=="hr"))
		return false;
		else
		return true;
	}	
	

	public static function check_23054($e, $content_dom){
		
		if(isset($e->attr["start"]) && ($e->tag=="ol"))
		return false;
		else
		return true;
	}		
	

	public static function check_23055($e, $content_dom){
		
		if(isset($e->attr["text"]) && ($e->tag=="body"))
		return false;
		else
		return true;
	}			
	

	public static function check_23056($e, $content_dom){
		
		if(isset($e->attr["type"]) && ($e->tag=="li" || $e->tag=="ul" || $e->tag=="ol"))
		return false;
		else
		return true;
	}		
	
	public static function check_23057($e, $content_dom){
		
		if(isset($e->attr["value"]) && ($e->tag=="li"))
		return false;
		else
		return true;
	}	

	public static function check_23058($e, $content_dom){
		
		if(isset($e->attr["version"]) && ($e->tag=="html"))
		return false;
		else
		return true;
	}
	

	public static function check_23059($e, $content_dom){
		
		if(isset($e->attr["vlink"]) && ($e->tag=="body"))
		return false;
		else
		return true;
	}
	
	public static function check_23060($e, $content_dom){
		
		if(isset($e->attr["vspace"]) && ($e->tag=="img" || $e->tag=="object"))
		return false;
		else
		return true;
	}	
	

	public static function check_23061($e, $content_dom){
		
		if(isset($e->attr["width"]) && ($e->tag=="hr" || $e->tag=="td" || $e->tag=="th" || $e->tag=="pre"))
		return false;
		else
		return true;
	}
	*/

	//body
	public static function check_24000($e, $content_dom){
		
		if(isset($e->attr["background"]) )
		return false;
		else
		return true;
	}	


	public static function check_24001($e, $content_dom){
		
		if(isset($e->attr["bgcolor"]) )
		return false;
		else
		return true;
	}	



	public static function check_24002($e, $content_dom){
		
		if(isset($e->attr["link"]) )
		return false;
		else
		return true;
	}		


	public static function check_24003($e, $content_dom){
		
		if(isset($e->attr["text"]) )
		return false;
		else
		return true;
	}		


	public static function check_24004($e, $content_dom){
		
		if(isset($e->attr["vlink"]) )
		return false;
		else
		return true;
	}	


	public static function check_24005($e, $content_dom){
		
		if(isset($e->attr["alink"]) )
		return false;
		else
		return true;
	}	


//table

	public static function check_24006($e, $content_dom){
		
		if(isset($e->attr["bgcolor"]) )
		return false;
		else
		return true;
	}	
	
	
	public static function check_24007($e, $content_dom){
		
		if(isset($e->attr["alink"]) )
		return false;
		else
		return true;
	}	
	

//tr

	public static function check_24008($e, $content_dom){
		
		if(isset($e->attr["bgcolor"]) )
		return false;
		else
		return true;
	}	
	
	
//td

	public static function check_24009($e, $content_dom){
		
		if(isset($e->attr["bgcolor"]) )
		return false;
		else
		return true;
	}	
	
	public static function check_24010($e, $content_dom){
		
		if(isset($e->attr["height"]) )
		return false;
		else
		return true;
	}	
	
	
	public static function check_24011($e, $content_dom){
		
		if(isset($e->attr["nowrap"]) )
		return false;
		else
		return true;
	}		
	
	
//th

	public static function check_24012($e, $content_dom){
		
		if(isset($e->attr["bgcolor"]) )
		return false;
		else
		return true;
	}	
	
	public static function check_24013($e, $content_dom){
		
		if(isset($e->attr["height"]) )
		return false;
		else
		return true;
	}	
	
	
	public static function check_24014($e, $content_dom){
		
		if(isset($e->attr["nowrap"]) )
		return false;
		else
		return true;
	}			
	
//img
	public static function check_24015($e, $content_dom){
		
		if(isset($e->attr["border"]) )
		return false;
		else
		return true;
	}	
	
	
	public static function check_24016($e, $content_dom){
		
		if(isset($e->attr["hspace"]) )
		return false;
		else
		return true;
	}
	
	
	public static function check_24017($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}	
	
	
//object
	public static function check_24018($e, $content_dom){
		
		if(isset($e->attr["border"]) )
		return false;
		else
		return true;
	}	
	
	
	public static function check_24019($e, $content_dom){
		
		if(isset($e->attr["hspace"]) )
		return false;
		else
		return true;
	}
	
	
	public static function check_24020($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		
	
//br

	public static function check_24021($e, $content_dom){
		
		if(isset($e->attr["clear"]) )
		return false;
		else
		return true;
	}
	
	
//dl

	public static function check_24022($e, $content_dom){
		
		if(isset($e->attr["compact"]) )
		return false;
		else
		return true;
	}	
	
//ol

	public static function check_24023($e, $content_dom){
		
		if(isset($e->attr["compact"]) )
		return false;
		else
		return true;
	}		
	
	public static function check_24024($e, $content_dom){
		
		if(isset($e->attr["type"]) )
		return false;
		else
		return true;
	}		

	
	public static function check_24025($e, $content_dom){
		
		if(isset($e->attr["start"]) )
		return false;
		else
		return true;
	}		
	
	
//ul

	public static function check_24026($e, $content_dom){
		
		if(isset($e->attr["compact"]) )
		return false;
		else
		return true;
	}		
	
	public static function check_24027($e, $content_dom){
		
		if(isset($e->attr["type"]) )
		return false;
		else
		return true;
	}		

	
//script
	
	public static function check_24028($e, $content_dom){
		
		if(isset($e->attr["language"]) )
		return false;
		else
		return true;
	}		
	
	
//hr

	public static function check_24029($e, $content_dom){
		
		if(isset($e->attr["noshade"]) )
		return false;
		else
		return true;
	}	

	public static function check_24030($e, $content_dom){
		
		if(isset($e->attr["size"]) )
		return false;
		else
		return true;
	}		

	public static function check_24031($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}	
	
//li

	public static function check_24032($e, $content_dom){
		
		if(isset($e->attr["type"]) )
		return false;
		else
		return true;
	}		

	public static function check_24033($e, $content_dom){
		
		if(isset($e->attr["value"]) )
		return false;
		else
		return true;
	}		
	
//caption

	public static function check_24034($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		

	
	
//iframe	
	public static function check_24035($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		
	
//input	
	public static function check_24036($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		

//legend	
	public static function check_24037($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		
	
//div	
	public static function check_24038($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}

//h1	
	public static function check_24039($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		

//h2	
	public static function check_24040($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		
//h3	
	public static function check_24041($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		

	
//h4	
	public static function check_24042($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		
	
//h5	
	public static function check_24043($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}	

//h6
	public static function check_24044($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}

//p	
	public static function check_24045($e, $content_dom){
		
		if(isset($e->attr["align"]) )
		return false;
		else
		return true;
	}		
	
		
	
	
}
?>  
