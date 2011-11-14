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

if (!defined('AC_INCLUDE_PATH')) { exit; }
require(AC_INCLUDE_PATH . 'classes/DAO/LanguageTextDAO.class.php');

/**********************************************************************************/
/* Output functions found in this file, in order:
/*
/*	- AC(term)
/*
/**********************************************************************************/

/**
* Converts language code to actual language message, caches them according to page url
* @access	public
* @param	args				unlimited number of arguments allowed but first arg MUST be name of the language variable/term
*								i.e		$args[0] = the term to the format string $_template[term]
*										$args[1..x] = optional arguments to the formatting string 
* @return	string|array		full resulting message
* @see		$db			        in include/vitals.inc.php
* @see		cache()				in include/phpCache/phpCache.inc.php
* @see		cache_variable()	in include/phpCache/phpCache.inc.php
* @author	Joel Kronenberg
*/
function _AC() {
	global $_cache_template, $lang_et, $_rel_url, $stripslashes;
	static $_template;

	$args = func_get_args();
	
	if ($args[0] == "") return "";
	
	$languageTextDAO = new LanguageTextDAO();
	
	// a feedback msg
	if (!is_array($args[0])) {
		/**
		 * Added functionality for translating language code String (AC_ERROR|AC_INFOS|AC_WARNING|AC_FEEDBACK).*
		 * to its text and returning the result. No caching needed.
		 * @author Jacek Materna
		 */

		// Check for specific language prefix, extendible as needed
		// 0002767:  a substring+in_array test should be faster than a preg_match test.
		// replaced the preg_match with a test of the substring.
		$sub_arg = substr($args[0], 0, 7); // 7 is the shortest type of msg (AC_INFO)
		if (in_array($sub_arg, array('AC_ERRO','AC_INFO','AC_WARN','AC_FEED','AC_CONF'))) {
			global $_base_path, $addslashes;

			$args[0] = $addslashes($args[0]);
					
			/* get $_msgs_new from the DB */
			$rows = $languageTextDAO->getMsgByTermAndLang($args[0], $_SESSION['lang']);
			$msgs = '';
					
			if (is_array($rows)) 
			{
				$row = $rows[0];
				// do not cache key as a digit (no contstant(), use string)
				$msgs = str_replace('SITE_URL/', $_base_path, $row['text']);
				if (defined('AC_DEVEL') && AC_DEVEL) {
					$msgs .= ' <small><small>('. $args[0] .')</small></small>';
				}
			}

			return $msgs;
		}
	}
	
	// a template variable
	if (!isset($_template)) {
		$url_parts = parse_url(AC_BASE_HREF);
		$name = substr($_SERVER['PHP_SELF'], strlen($url_parts['path'])-1);

		if ( !($lang_et = cache(120, 'lang', $_SESSION['lang'].'_'.$name)) ) {
			/* get $_template from the DB */
			$rows = $languageTextDAO->getAllTemplateByLang($_SESSION['lang']);
			
			if (is_array($rows))
			{
				foreach ($rows as $id => $row) 
				{
					//Do not overwrite the variable that existed in the cache_template already.
					//The edited terms (_c_template) will always be at the top of the resultset
					//0003279
					if (isset($_cache_template[$row['term']])){
						continue;
					}
	
					// saves us from doing an ORDER BY
					if ($row['language_code'] == $_SESSION['lang']) {
						$_cache_template[$row['term']] = $stripslashes($row['text']);
					} else if (!isset($_cache_template[$row['term']])) {
						$_cache_template[$row['term']] = $stripslashes($row['text']);
					}
				}
			}
		
			cache_variable('_cache_template');
			endcache(true, false);
		}
		$_template = $_cache_template;
	}

	$num_args = func_num_args();
	if (is_array($args[0])) {
		$args = $args[0];
		$num_args = count($args);
	}
	$format	  = array_shift($args);

	if (isset($_template[$format]) && count($args) > 0) {
		$outString	= vsprintf($_template[$format], $args);
		$str = ob_get_contents();
	} else {
		$outString = '';
	}

	if ($outString === false) {
		return ('[Error parsing language. Variable: <code>'.$format.'</code>. Language: <code>'.$_SESSION['lang'].'</code> ]');
	}

	if (empty($outString)) {

		$rows = $languageTextDAO->getByTermAndLang($format, $_SESSION['lang']);
		if (is_array($rows) && isset($rows[0]))
		{
			$row = $rows[0];
			$_template[$row['term']] = $stripslashes($row['text']);
			$outString = $_template[$row['term']];
		}

		if (empty($outString)) {
			return ('[ '.$format.' ]');
		}
	}

	return $outString;
}

/* 
	The following options were added as language dependant:
	%D: A textual representation of a week, three letters Mon through Sun
	%F: A full textual representation of a month, such as January or March January through December
	%l (lowercase 'L'): A full textual representation of the day of the week Sunday through Saturday
	%M: A short textual representation of a month, three letters Jan through Dec

	Support for the following maybe added later:
	?? %S: English ordinal suffix for the day of the month, 2 characters st, nd, rd or th. Works well with j
	?? %a: Lowercase Ante meridiem and Post meridiem am or pm 
	?? %A: Uppercase Ante meridiem and Post meridiem AM or PM 

	valid format types:
	AC_DATE_MYSQL_DATETIME:		YYYY-MM-DD HH:MM:SS
	AC_DATE_MYSQL_TIMESTAMP_14:	YYYYMMDDHHMMSS
	AC_DATE_UNIX_TIMESTAMP:		seconds since epoch
	AC_DATE_INDEX_VALUE:		0-x, index into a date array
*/
function AC_date($format='%Y-%M-%d', $timestamp = '', $format_type=AC_DATE_MYSQL_DATETIME) {	
	static $day_name_ext, $day_name_con, $month_name_ext, $month_name_con;
	global $_config;

	if (!isset($day_name_ext)) {
		$day_name_ext = array(	'date_sunday', 
								'date_monday', 
								'date_tuesday', 
								'date_wednesday', 
								'date_thursday', 
								'date_friday',
								'date_saturday');

		$day_name_con = array(	'date_sun', 
								'date_mon', 
								'date_tue', 
								'date_wed',
								'date_thu', 
								'date_fri', 
								'date_sat');

		$month_name_ext = array('date_january', 
								'date_february', 
								'date_march', 
								'date_april', 
								'date_may',
								'date_june', 
								'date_july', 
								'date_august', 
								'date_september', 
								'date_october', 
								'date_november',
								'date_december');

		$month_name_con = array('date_jan', 
								'date_feb', 
								'date_mar', 
								'date_apr', 
								'date_may_short',
								'date_jun', 
								'date_jul', 
								'date_aug', 
								'date_sep', 
								'date_oct', 
								'date_nov',
								'date_dec');
	}

	if ($format_type == AC_DATE_INDEX_VALUE) {
		if ($format == '%D') {
			return _AC($day_name_con[$timestamp-1]);
		} else if ($format == '%l') {
			return _AC($day_name_ext[$timestamp-1]);
		} else if ($format == '%F') {
			return _AC($month_name_ext[$timestamp-1]);
		} else if ($format == '%M') {
			return _AC($month_name_con[$timestamp-1]);
		}
	}

	if ($timestamp == '') {
		$timestamp = time();
		$format_type = AC_DATE_UNIX_TIMESTAMP;
	}

	/* convert the date to a Unix timestamp before we do anything with it */
	if ($format_type == AC_DATE_MYSQL_DATETIME) {
		$year	= substr($timestamp,0,4);
		$month	= substr($timestamp,5,2);
		$day	= substr($timestamp,8,2);
		$hour	= substr($timestamp,11,2);
		$min	= substr($timestamp,14,2);
		$sec	= substr($timestamp,17,2);
	    $timestamp	= mktime($hour, $min, $sec, $month, $day, $year);

	} else if ($format_type == AC_DATE_MYSQL_TIMESTAMP_14) {
	    $year		= substr($timestamp,0,4);
	    $month		= substr($timestamp,4,2);
	    $day		= substr($timestamp,6,2);
		$hour		= substr($timestamp,8,2);
	    $minute		= substr($timestamp,10,2);
	    $second		= substr($timestamp,12,2);
	    $timestamp	= mktime($hour, $minute, $second, $month, $day, $year);  
	}

	/* pull out all the %X items from $format */
	$first_token = strpos($format, '%');
	if ($first_token === false) {
		/* no tokens found */
		return $timestamp;
	} else {
		$tokened_format = substr($format, $first_token);
	}
	$tokens = explode('%', $tokened_format);
	array_shift($tokens);
	$num_tokens = count($tokens);

	$output = $format;
	for ($i=0; $i<$num_tokens; $i++) {
		$tokens[$i] = substr($tokens[$i],0,1);

		if ($tokens[$i] == 'D') {
			$output = str_replace('%D', _AC($day_name_con[date('w', $timestamp)]),$output);
		
		} else if ($tokens[$i] == 'l') {
			$output = str_replace('%l', _AC($day_name_ext[date('w', $timestamp)]),$output);
		
		} else if ($tokens[$i] == 'F') {
			$output = str_replace('%F', _AC($month_name_ext[date('n', $timestamp)-1]),$output);		
		
		} else if ($tokens[$i] == 'M') {
			$output = str_replace('%M', _AC($month_name_con[date('n', $timestamp)-1]),$output);

		} else {

			/* this token doesn't need translating */
			$value = date($tokens[$i], $timestamp);
			if ($value != $tokens[$i]) {
				$output = str_replace('%'.$tokens[$i], $value, $output);
			} /* else: this token isn't valid. so don't replace it. Eg. try %q */
		}
	}

	return $output;
}

/**********************************************************************************************************/
/**
* 	Transforms text based on formatting preferences.  Original $input is also changed (passed by reference).
*	Can be called as:
*	1) $output = AC_print($input, $name);
*	   echo $output;
*
*	2) echo AC_print($input, $name); // prefered method
*
* @access	public
* @param	string $input			text being transformed
* @param	string $name			the unique name of this field (convension: table_name.field_name)
* @param	boolean $runtime_html	forcefully disables html formatting for $input (only used by fields that 
*									have the 'formatting' option
* @return	string					transformed $input
* @see		AC_FORMAT constants		in include/lib/constants.inc.php
* @see		query_bit()				in include/vitals.inc.php
* @author	Joel Kronenberg
*/
function AC_print($input, $name, $runtime_html = true) {
	global $_field_formatting;

	if (!isset($_field_formatting[$name])) {
		/* field not set, check if there's a global setting */
		$parts = explode('.', $name);
		
		/* check if wildcard is set: */
		if (isset($_field_formatting[$parts[0].'.*'])) {
			$name = $parts[0].'.*';
		} else {
			/* field not set, and there's no global setting */
			/* same as AC_FORMAT_NONE */
			return $input;
		}
	}

	if (query_bit($_field_formatting[$name], AC_FORMAT_QUOTES)) {
		$input = str_replace('"', '&quot;', $input);
	}

	if (query_bit($_field_formatting[$name], AC_FORMAT_HTML) && $runtime_html) {
		/* what special things do we have to do if this is HTML ? remove unwanted HTML? validate? */
	} else {
		$input = str_replace('<', '&lt;', $input);
		$input = nl2br($input);
	}

	/* this has to be here, only because AC_FORMAT_HTML is the only check that has an else-block */
	if ($_field_formatting[$name] === AC_FORMAT_NONE) {
		return $input;
	}

	if (query_bit($_field_formatting[$name], AC_FORMAT_EMOTICONS)) {
		$input = smile_replace($input);
	}

	if (query_bit($_field_formatting[$name], AC_FORMAT_ATCODES)) {
		$input = trim(myCodes(' ' . $input . ' '));
	}

	if (query_bit($_field_formatting[$name], AC_FORMAT_LINKS)) {
		$input = trim(make_clickable(' ' . $input . ' '));
	}

	if (query_bit($_field_formatting[$name], AC_FORMAT_IMAGES)) {
		$input = trim(image_replace(' ' . $input . ' '));
	}


	return $input;
}

/********************************************************************************************/
// Global variables for emoticons
 
global $smile_pics;
global $smile_codes;
if (!isset($smile_pics)) {
	$smile_pics[0] = $_base_path.'images/forum/smile.gif';
	$smile_pics[1] = $_base_path.'images/forum/wink.gif';
	$smile_pics[2] = $_base_path.'images/forum/frown.gif';
	$smile_pics[3] = $_base_path.'images/forum/ohwell.gif';
	$smile_pics[4] = $_base_path.'images/forum/tongue.gif';
	$smile_pics[5] = $_base_path.'images/forum/51.gif';
	$smile_pics[6] = $_base_path.'images/forum/52.gif';
	$smile_pics[7] = $_base_path.'images/forum/54.gif';
	$smile_pics[8] = $_base_path.'images/forum/27.gif';
	$smile_pics[9] = $_base_path.'images/forum/19.gif';
	$smile_pics[10] = $_base_path.'images/forum/3.gif';
	$smile_pics[11] = $_base_path.'images/forum/56.gif';
}

if (!isset($smile_codes)) {
	$smile_codes[0] = ':)';
	$smile_codes[1] = ';)';
	$smile_codes[2] = ':(';
	$smile_codes[3] = '::ohwell::';
	$smile_codes[4] = ':P';
	$smile_codes[5] = '::evil::';
	$smile_codes[6] = '::angry::';
	$smile_codes[7] = '::lol::';
	$smile_codes[8] = '::crazy::';
	$smile_codes[9] = '::tired::';
	$smile_codes[10] = '::confused::';
	$smile_codes[11] = '::muah::';
}

/**
* Replaces smile-code text into smilie image.
* @access	public
* @param	string $text		smile text to be transformed
* @return	string				transformed $text
* @see		$smile_pics			in include/lib/output.inc.php (above)
* @see		$smile_codes		in include/lib/output.inc.php (above)
* @author	Joel Kronenberg
*/
function smile_replace($text) {
	global $smile_pics;
	global $smile_codes;
	static $smiles;

	$smiles[0] = '<img src="'.$smile_pics[0].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_smile').'" />';
	$smiles[1] = '<img src="'.$smile_pics[1].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_wink').'" />';
	$smiles[2] = '<img src="'.$smile_pics[2].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_frown').'" />';
	$smiles[3]= '<img src="'.$smile_pics[3].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_oh_well').'" />';
	$smiles[4]= '<img src="'.$smile_pics[4].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_tongue').'" />';
	$smiles[5]= '<img src="'.$smile_pics[5].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_evil').'" />';
	$smiles[6]= '<img src="'.$smile_pics[6].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_angry').'" />';
	$smiles[7]= '<img src="'.$smile_pics[7].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_lol').'" />';
	$smiles[8]= '<img src="'.$smile_pics[8].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_crazy').'" />';
	$smiles[9]= '<img src="'.$smile_pics[9].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_tired').'" />';
	$smiles[10]= '<img src="'.$smile_pics[10].'" border="0" height="17" width="19" align="bottom" alt="'._AT('smile_confused').'" />';
	$smiles[11]= '<img src="'.$smile_pics[11].'" border="0" height="15" width="15" align="bottom" alt="'._AT('smile_muah').'" />';

	$text = str_replace($smile_codes[0],$smiles[0],$text);
	$text = str_replace($smile_codes[1],$smiles[1],$text);
	$text = str_replace($smile_codes[2],$smiles[2],$text);
	$text = str_replace($smile_codes[3],$smiles[3],$text);
	$text = str_replace($smile_codes[4],$smiles[4],$text);
	$text = str_replace($smile_codes[5],$smiles[5],$text);
	$text = str_replace($smile_codes[6],$smiles[6],$text);
	$text = str_replace($smile_codes[7],$smiles[7],$text);
	$text = str_replace($smile_codes[8],$smiles[8],$text);
	$text = str_replace($smile_codes[9],$smiles[9],$text);
	$text = str_replace($smile_codes[10],$smiles[10],$text);
	$text = str_replace($smile_codes[11],$smiles[11],$text);

	return $text;
}


/* Used specifically for the visual editor
*/
function smile_javascript () {
	global $_base_path;
	global $smile_pics;
	global $smile_codes;

	static $i = 0;

	while ($smile_pics [$i]) {
		echo 'case "'.$smile_codes[$i].'":'."\n";
		echo 'pic = "'.$smile_pics[$i].'";'."\n";
		echo 'break;'."\n";
		$i++;
	}
}

function myCodes($text, $html = false) {
	global $_base_path;
	global $HTTP_USER_AGENT;

	if (substr($HTTP_USER_AGENT,0,11) == 'Mozilla/4.7') {
		$text = str_replace('[quote]','</p><p class="block">',$text);
		$text = str_replace('[/quote]','</p><p>',$text);

		$text = str_replace('[reply]','</p><p class="block">',$text);
		$text = str_replace('[/reply]','</p><p>',$text);
	} else {
		$text = str_replace('[quote]','<blockquote>',$text);
		$text = str_replace('[/quote]','</blockquote><p>',$text);

		$text = str_replace('[reply]','</p><blockquote class="block"><p>',$text);
		$text = str_replace('[/reply]','</p></blockquote><p>',$text);
	}

	$text = str_replace('[b]','<strong>',$text);
	$text = str_replace('[/b]','</strong>',$text);

	$text = str_replace('[i]','<em>',$text);
	$text = str_replace('[/i]','</em>',$text);

	$text = str_replace('[u]','<u>',$text);
	$text = str_replace('[/u]','</u>',$text);

	$text = str_replace('[center]','<center>',$text);
	$text = str_replace('[/center]','</center><p>',$text);

	/* colours */
	$text = str_replace('[blue]','<span style="color: blue;">',$text);
	$text = str_replace('[/blue]','</span>',$text);

	$text = str_replace('[orange]','<span style="color: orange;">',$text);
	$text = str_replace('[/orange]','</span>',$text);

	$text = str_replace('[red]','<span style="color: red;">',$text);
	$text = str_replace('[/red]','</span>',$text);

	$text = str_replace('[purple]','<span style="color: purple;">',$text);
	$text = str_replace('[/purple]','</span>',$text);

	$text = str_replace('[green]','<span style="color: green;">',$text);
	$text = str_replace('[/green]','</span>',$text);

	$text = str_replace('[gray]','<span style="color: gray;">',$text);
	$text = str_replace('[/gray]','</span>',$text);

	$text = str_replace('[op]','<span class="bigspacer"></span> <a href="',$text);
	$text = str_replace('[/op]','">'._AT('view_entire_post').'</a>',$text);

	$text = str_replace('[head1]','<h2>',$text);
	$text = str_replace('[/head1]','</h2>',$text);

	$text = str_replace('[head2]','<h3>',$text);
	$text = str_replace('[/head2]','</h3>',$text);

	$text = str_replace('[cid]',$_base_path.'content.php?cid='.$_SESSION['s_cid'],$text);

	global $sequence_links;
	if (isset($sequence_links['previous']) && $sequence_links['previous']['url']) {
		$text = str_replace('[pid]', $sequence_links['previous']['url'], $text);
	}
	if (isset($sequence_links['next']) && $sequence_links['next']['url']) {
		$text = str_replace('[nid]', $sequence_links['next']['url'], $text);
	}
	if (isset($sequence_links['resume']) && $sequence_links['resume']['url']) {
		$text = str_replace('[nid]', $sequence_links['resume']['url'], $text);
	}
	if (isset($sequence_links['first']) && $sequence_links['first']['url']) {
		$text = str_replace('[fid]', $sequence_links['first']['url'], $text);
	}

	/* contributed by Thomas M. Duffey <tduffey at homeboyz.com> */
	$html = !$html ? 0 : 1;
	$text = preg_replace("/\[code\]\s*(.*)\s*\[\\/code\]/Usei", "highlight_code(fix_quotes('\\1'), $html)", $text);

	return $text;
}

/* contributed by Thomas M. Duffey <tduffey at homeboyz.com> */
function highlight_code($code, $html) {
	// XHTMLize PHP highlight_string output until it gets fixed in PHP
	static $search = array(
		'<br>',
		'<font',
		'</font>',
		'color="');

	static $replace = array(
		'<br />',
		'<span',
		'</span>',
		'style="color:');
	if (!$html) {
		$code = str_replace('&lt;', '<', $code);
		$code = str_replace("\r", '', $code);
	}

	return str_replace($search, $replace, highlight_string($code, true));
}

/* contributed by Thomas M. Duffey <tduffey at homeboyz.com> */
function fix_quotes($text){
	return str_replace('\\"', '"', $text);
}

function embed_media($text) {
	if (preg_match("/\[media(\|[0-9]+\|[0-9]+)?\]*/", $text)==0){
		return $text;
	}

	$media_matches = Array();
	
	/*
		First, we search though the text for all different kinds of media defined by media tags and store the results in $media_matches.
		
		Then the different replacements for the different media tags are stored in $media_replace.
		
		Lastly, we loop through all $media_matches / $media_replaces. (We choose $media_replace as index because $media_matches is multi-dimensioned.) It is important that for each $media_matches there is a $media_replace with the same index. For each media match we check the width/height, or we use the default value of 425x350. We then replace the height/width/media1/media2 parameter placeholders in $media_replace with the correct ones, before running a str_replace on $text, replacing the given media with its correct replacement.
		
	*/
	
	// youtube videos
	preg_match_all("#\[media[0-9a-z\|]*\]http://([a-z0-9\.]*)?youtube.com/watch\?v=([a-z0-9_-]+)\[/media\]#i",$text,$media_matches[1],PREG_SET_ORDER);
	$media_replace[1] = '<object width="##WIDTH##" height="##HEIGHT##"><param name="movie" value="http://##MEDIA1##youtube.com/v/##MEDIA2##"></param><embed src="http://##MEDIA1##youtube.com/v/##MEDIA2##" type="application/x-shockwave-flash" width="##WIDTH##" height="##HEIGHT##"></embed></object>';
		
	// .mpg
	preg_match_all("#\[media[0-9a-z\|]*\]([.\w\d]+[^\s\"]+).mpg\[/media\]#i",$text,$media_matches[2],PREG_SET_ORDER);
	$media_replace[2] = "<object data=\"##MEDIA1##.mpg\" type=\"video/mpeg\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.mpg\"><param name=\"autoplay\" value=\"false\"><param name=\"autoStart\" value=\"0\"><a href=\"##MEDIA1##.mpg\">##MEDIA1##.mpg</a></object>";
	
	// .avi
	preg_match_all("#\[media[0-9a-z\|]*\]([.\w\d]+[^\s\"]+).avi\[/media\]#i",$text,$media_matches[3],PREG_SET_ORDER);
	$media_replace[3] = "<object data=\"##MEDIA1##.avi\" type=\"video/x-msvideo\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.avi\"><param name=\"autoplay\" value=\"false\"><param name=\"autoStart\" value=\"0\"><a href=\"##MEDIA1##.avi\">##MEDIA1##.avi</a></object>";
	
	// .wmv
	preg_match_all("#\[media[0-9a-z\|]*\]([.\w\d]+[^\s\"]+).wmv\[/media\]#i",$text,$media_matches[4],PREG_SET_ORDER);
	$media_replace[4] = "<object data=\"##MEDIA1##.wmv\" type=\"video/x-ms-wmv\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.wmv\"><param name=\"autoplay\" value=\"false\"><param name=\"autoStart\" value=\"0\"><a href=\"##MEDIA1##.wmv\">##MEDIA1##.wmv</a></object>";
	
	// .mov
	preg_match_all("#\[media[0-9a-z\|]*\]([.\w\d]+[^\s\"]+).mov\[/media\]#i",$text,$media_matches[5],PREG_SET_ORDER);
	$media_replace[5] = "<object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.mov\"><param name=\"controller\" value=\"true\"><param name=\"autoplay\" value=\"false\"><!--[if gte IE 7]> <!--><object type=\"video/quicktime\" data=\"##MEDIA1##.mov\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"controller\" value=\"true\"><param name=\"autoplay\" value=\"false\"><a href=\"##MEDIA1##.mov\">##MEDIA1##.mov</a></object><!--<![endif]--><!--[if lt IE 7]><a href=\"##MEDIA1##.mov\">##MEDIA1##.mov</a><![endif]--></object>";
	
	// .swf
	preg_match_all("#\[media[0-9a-z\|]*\]([.\w\d]+[^\s\"]+).swf\[/media\]#i",$text,$media_matches[6],PREG_SET_ORDER);
	$media_replace[6] = "<object type=\"application/x-shockwave-flash\" data=\"##MEDIA1##.swf\" width=\"##WIDTH##\" height=\"##HEIGHT##\">  <param name=\"movie\" value=\"##MEDIA1##.swf\"><param name=\"loop\" value=\"false\"><a href=\"##MEDIA1##.swf\">##MEDIA1##.swf</a></object>";
	
	// .mp3
	preg_match_all("#\[media[0-9a-z\|]*\](.+[^\s\"]+).mp3\[/media\]#i",$text,$media_matches[7],PREG_SET_ORDER);
	$media_replace[7] = "<object type=\"audio/mpeg\" data=\"##MEDIA1##.mp3\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.mp3\"><param name=\"autoplay\" value=\"false\"><param name=\"autoStart\" value=\"0\"><a href=\"##MEDIA1##.mp3\">##MEDIA1##.mp3</a></object>";
	
	// .wav
	preg_match_all("#\[media[0-9a-z\|]*\](.+[^\s\"]+).wav\[/media\]#i",$text,$media_matches[8],PREG_SET_ORDER);
	$media_replace[8] ="<object type=\"audio/x-wav\" data=\"##MEDIA1##.wav\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.wav\"><param name=\"autoplay\" value=\"false\"><param name=\"autoStart\" value=\"0\"><a href=\"##MEDIA1##.wav\">##MEDIA1##.wav</a></object>";
	
	// .ogg
	preg_match_all("#\[media[0-9a-z\|]*\](.+[^\s\"]+).ogg\[/media\]#i",$text,$media_matches[9],PREG_SET_ORDER);
	$media_replace[9] ="<object type=\"application/ogg\" data=\"##MEDIA1##.ogg\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.ogg\"><a href=\"##MEDIA1##.ogg\">##MEDIA1##.ogg</a></object>";
	
	// .mid
	preg_match_all("#\[media[0-9a-z\|]*\](.+[^\s\"]+).mid\[/media\]#i",$text,$media_matches[10],PREG_SET_ORDER);
	$media_replace[10] ="<object type=\"application/x-midi\" data=\"##MEDIA1##.mid\" width=\"##WIDTH##\" height=\"##HEIGHT##\"><param name=\"src\" value=\"##MEDIA1##.mid\"><a href=\"##MEDIA1##.mid\">##MEDIA1##.mid</a></object>";
	
	$text = preg_replace("#\[media[0-9a-z\|]*\](.+[^\s\"]+).mid\[/media\]#i", "<object type=\"application/x-midi\" data=\"\\1.mid\" width=\"".$width."\" height=\"".$height."\"><param name=\"src\" value=\"\\1.mid\"><a href=\"\\1.mid\">\\1.mid</a></object>", $text);

	// Executing the replace
	for ($i=1;$i<=count($media_replace);$i++){
		foreach($media_matches[$i] as $media)
		{
			
			//find width and height for each matched media
			if (preg_match("/\[media\|([0-9]*)\|([0-9]*)\]*/", $media[0], $matches)) 
			{
				$width = $matches[1];
				$height = $matches[2];
			}
			else
			{
				$width = 425;
				$height = 350;
			}
			
			//replace media tags with embedded media for each media tag
			$media_input = $media_replace[$i];
			$media_input = str_replace("##WIDTH##","$width",$media_input);
			$media_input = str_replace("##HEIGHT##","$height",$media_input);
			$media_input = str_replace("##MEDIA1##","$media[1]",$media_input);
			$media_input = str_replace("##MEDIA2##","$media[2]",$media_input);
			$text = str_replace($media[0],$media_input,$text);
		}
	}
		
	return $text;
}

function make_clickable($text) {
	$text = embed_media($text);

	// convert plain text URL to clickable URL.
	// Limited conversion: It doesn't cover the case when the stuff in front of the URL is not a word. For example:
	// <p>http://google.ca</p>
	// "http://google.ca" 
	$text = preg_replace('/(^|[\n ])([\w]*?)((?<!(\[media\]))http(s)?:\/\/[\w]+[^ \,\"\n\r\t\)<]*)/is', 
	                     '$1$2<a href="$3">$3</a>', $text);
	
	// convert email address to clickable URL that pops up "send email" interface with the address filled in
	$text = preg_replace('/(?|<a href="mailto[\s]*:[\s]*([_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)*'.'\@'
                            .'[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)*'.'(\.[a-zA-Z]{1,6})+)">(.*)<\/a>'
                            .'|((((([_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)*'.'\@'
                            .'[_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9\-]+)*'.'(\.[a-zA-Z]{1,6})+))))))/i',
						"<a href=\"mailto:\\1\">\\5</a>",
						$text);
	
	return $text;
}

function image_replace($text) {
	/* image urls do not require http:// */
	
	$text = preg_replace("/\[image(\|)?([a-zA-Z0-9\s]*)\]".
	                     "[\s]*".
	                     "([a-zA-Z0-9\#\?\/\&\=\:\\\"\'\_\.\-]+)[\s]*".
	                     "((\[\/image\])|(.*\[\/image\]))/i",
				  "<img src=\"\\3\" alt=\"\\2\" />",
				  $text);
		 
	return $text;
}

function format_final_output($text, $nl2br = true) {
	global $_base_path;

	$text = str_replace('CONTENT_DIR/', '', $text);

	if ($nl2br) {
		return nl2br(image_replace(make_clickable(myCodes(' '.$text, false))));
	}
	return image_replace(make_clickable(myCodes(' '.$text, true)));
}

/****************************************************************************************/
/* @See: ./user/search.php & ./index.php */
function highlight($input, $var) {//$input is the string, $var is the text to be highlighted
	if ($var != "") {
		$xtemp = "";
		$i=0;
		/*
			The following 'if' statement is a check to ensure that the search term is not part of the tag, '<strong class="highlight">'.  Words within this string are avoided in case a previously highlighted string is used for the haystack, $input.  To avoid any html breaks in the highlighted string, the search word is avoided completely.
		*/
		if (strpos('<strong class="highlight">', $var) !== false) {
			return $input;
		}
		while($i<strlen($input)){
			if((($i + strlen($var)) <= strlen($input)) && (strcasecmp($var, substr($input, $i, strlen($var))) == 0)) {
				$xtemp .= '<strong class="highlight">' . substr($input, $i , strlen($var)) . '</strong>';
				$i += strlen($var);
			}
			else {
				$xtemp .= $input{$i};
				$i++;
			}
		}
		$input = $xtemp;
	}
	return $input;
}


/* @See: ./index.php */
function format_content($input, $html = 0, $glossary, $simple = false) {
	global $_base_path, $_config_defaults;

	if (!$html) {
		$input = str_replace('<', '&lt;', $input);
		$input = str_replace('&lt;?php', '<?php', $input); // for bug #2087
	}

	/* do the glossary search and replace: */
	if (is_array($glossary)) {
		foreach ($glossary as $k => $v) {
			$k = urldecode($k);
			$v = str_replace("\n", '<br />', $v);
			$v = str_replace("\r", '', $v);

			/* escape special characters */
			$k = preg_quote($k);

			$k = str_replace('&lt;', '<', $k);
			$k = str_replace('/', '\/', $k);

			$original_term = $k;
			$term = $original_term;

	 		$term = '(\s*'.$term.'\s*)';
			$term = str_replace(' ','((<br \/>)*\s*)', $term); 

			$def = htmlspecialchars($v);		
			if ($simple) {
				$input = preg_replace
						("/(\[\?\])$term(\[\/\?\])/i",
						'<a href="'.$simple.'glossary.html#'.urlencode($original_term).'" target="body" class="at-term">\\2</a>',
						$input);
			} else {
				$input = preg_replace
						("/(\[\?\])$term(\[\/\?\])/i",
						'\\2<sup><a href="'.$_base_path.'glossary/index.php?g_cid='.$_SESSION['s_cid'].SEP.'w='.urlencode($original_term).'#term" onmouseover="return overlib(\''.$def.'\', CAPTION, \''.addslashes($original_term).'\', AUTOSTATUS);" onmouseout="return nd();" onfocus="return overlib(\''.$def.'\', CAPTION, \''.addslashes($original_term).'\', AUTOSTATUS);" onblur="return nd();"><span style="color: blue; text-decoration: none;font-size:small; font-weight:bolder;">?</span></a></sup>',
						$input);
			}
		}
	} else if (!$user_glossary) {
		$input = str_replace(array('[?]','[/?]'), '', $input);
	}

	$input = str_replace('CONTENT_DIR', '', $input);

	if (isset($_config_defaults['latex_server']) && $_config_defaults['latex_server']) {
		// see: http://www.forkosh.com/mimetex.html
		$input = preg_replace('/\[tex\](.*?)\[\/tex\]/sie', "'<img src=\"'.\$_config_defaults['latex_server'].rawurlencode('$1').'\" align=\"middle\">'", $input);
	}

	if ($html) {
		$x = format_final_output($input, false);
		return $x;
	}

	$output = format_final_output($input);

	$output = '<p>'.$output.'</p>';

	return $output;
}

/***********************************************************************
	@See /include/Classes/Message/Message.class.php
	Jacek Materna
*/

/**
* Take a code as input and grab its language specific message. Also cache the resulting 
* message. Return the message. Same as get_message but key value in cache is string
* @access  public
* @param   string $codes 	Message Code to translate - > 'term' field in DB
* @return  string 			The translated language specific message for code $code
* @author  Jacek Materna
*/
function getTranslatedCodeStr($codes) {
	
	/* this is where we want to get the msgs from the database inside a static variable */
	global $_cache_msgs_new;
	static $_msgs_new;

	if (!isset($_msgs_new)) {
		if ( !($lang_et = cache(120, 'msgs_new', $_SESSION['lang'])) ) {
			global $db, $_base_path;

			$parent = Language::getParentCode($_SESSION['lang']);

			/* get $_msgs_new from the DB */
			$sql	= 'SELECT * FROM '.TABLE_PREFIX.'language_text WHERE variable="_msgs" AND (language_code="'.$_SESSION['lang'].'" OR language_code="'.$parent.'")';
			$result	= @mysql_query($sql, $db);
			$i = 1;
			while ($row = @mysql_fetch_assoc($result)) {
				// do not cache key as a digit (no contstant(), use string)
				$_cache_msgs_new[$row['term']] = str_replace('SITE_URL/', $_base_path, $row['text']);
				if (AC_DEVEL) {
					$_cache_msgs_new[$row['term']] .= ' <small><small>('.$row['term'].')</small></small>';
				}
			}

			cache_variable('_cache_msgs_new');
			endcache(true, false);
		}
		$_msgs_new = $_cache_msgs_new;
	}

	if (is_array($codes)) {
		/* this is an array with terms to replace */		
		$code		= array_shift($codes);

		$message	= $_msgs_new[$code];
		$terms		= $codes;

		/* replace the tokens with the terms */
		$message	= vsprintf($message, $terms);

	} else {
		$message = $_msgs_new[$codes];

		if ($message == '') {
			/* the language for this msg is missing: */
		
			$sql	= 'SELECT * FROM '.TABLE_PREFIX.'language_text WHERE variable="_msgs"';
			$result	= @mysql_query($sql, $db);
			$i = 1;
			while ($row = @mysql_fetch_assoc($result)) {
				if (($row['term']) === $codes) {
					$message = '['.$row['term'].']';
					break;
				}
			}
		}
		$code = $codes;
	}
	return $message;
}

function html_get_list($array) {
	$list = '';
	foreach ($array as $value) {
		$list .= '<li>'.$value.'</li>';
	}
	return $list;
}

/**
 * print_paginator
 *
 * print out list of page links
 */
function print_paginator($current_page, $num_rows, $request_args, $rows_per_page = 50, $window = 5) {
	$num_pages = ceil($num_rows / $rows_per_page);
	$request_args = '?'.$request_args;

    if ($num_rows) {
		echo '<div class="paging">';
	    echo '<ul>';
		
		$i=max($current_page-$window - max($window-$num_pages+$current_page,0), 1);

		if ($i > 1) {
			echo '<li><a href="'.$_SERVER['PHP_SELF'].$request_args.htmlspecialchars(SEP).'p=1">1</a></li>';
			if ($i > 2) {
		        echo '<li>&hellip;</li>';
			}
		}

		for ($i; $i<= min($current_page+$window -min($current_page-$window,0),$num_pages); $i++) {
			if ($current_page == $i) {
				echo '<li><a href="'.$_SERVER['PHP_SELF'].$request_args.htmlspecialchars(SEP).'p='.$i.'" class="current"><em>'.$current_page.'</em></a></li>';
			} else {
				echo '<li><a href="'.$_SERVER['PHP_SELF'].$request_args.htmlspecialchars(SEP).'p='.$i.'">'.$i.'</a></li>';
			}
		}
        if ($i <= $num_pages) {
			if ($i < $num_pages) {
		        echo '<li>&hellip;</li>';
	        }
			echo '<li><a href="'.$_SERVER['PHP_SELF'].$request_args.htmlspecialchars(SEP).'p='.$num_pages.'">'.$num_pages.'</a></li>';
		}
		echo '</ul>';
		echo '</div>';
	}
}
?>
