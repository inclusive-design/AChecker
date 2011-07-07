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
// $Id: 

/**
* acheckerCSV
* Class to generate error report in CSV file
* for each of types: known, likely, potential, html, css and all selected 
* @access	public
* @author	Casian Olga
*/
if (!defined("AC_INCLUDE_PATH")) exit;
include_once(AC_INCLUDE_PATH. "classes/DAO/GuidelinesDAO.class.php");

// end of line; system dependent
if (PHP_EOL == "\r\n") {
    define(EOL, "\r\n");
} else {
    define(EOL, "\n");
}

// delimiter
define(DELIM, ";");

	// strings of data to write in file and output with _AC();
//	achecker_file_title = 'AChecker - Web Accessibility Checker'
//	achecker_file_description = 'AChecker is an open source Web accessibility evaluation tool. It can be used to review the accessibility of Web pages based on a variety international accessibility guidelines.'
//	
//	achecker_file_passed = 'Passed'
//	achecker_file_failed = 'Failed'
//	achecker_file_no_decision = 'No Decision'
//
//	achecker_file_source_url = 'Source URL'
//	achecker_file_sourse_title = 'Source title'
//	
//	achecker_file_repair = 'Repair'
//	achecker_file_htm = 'HTML code'
//	achecker_file_css = 'CSS code'
//	achecker_file_img = 'Image source'
//	achecker_file_decision = 'Decision'
//	achecker_file_text = 'Error text'

class acheckerCSV {

	// all private
	// arrays that contain all data about errors of specific type
	var $known = array();
	var $likely = array();
	var $potential = array();
	var $html = array();
	var $css = array();
	
	// numbers of errors to display for each problem type
	var $error_nr_known = 0;
	var $error_nr_likely = 0;
	var $error_nr_potential = 0;
	var $error_nr_html = 0;
	var $error_nr_css = 0;
	
	// css error message 
	// css validator is only available at validating url, not at validating a uploaded file or pasted html
	var $css_error = 0;
	
	var $achecker_file_url = 'http://www.atutor.ca/achecker/';
		
	
	/**
	* public
	* error arrays and numbers setter
	* @param
	* $known, $likely, $potential: arrays that contain errors of specific type
	* $html, $css: arrays of validation errors
	* $error_nr_known, $error_nr_likely, $error_nr_potential: nr of errors
	* $error_nr_html, $error_nr_css: nr of errors
	* $css_error: empty if css validation was required with URL input, otherwise string with error msg
	*/
	function acheckerCSV($known, $likely, $potential, $html, $css, 
		$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error)
	{				
		$this->known = $known;
		$this->likely = $likely;
		$this->potential = $potential;
		$this->html = $html;	
		$this->css = $css;	
		
		$this->error_nr_known = $error_nr_known;
		$this->error_nr_likely = $error_nr_likely;
		$this->error_nr_potential = $error_nr_potential;
		$this->error_nr_html = $error_nr_html;
		$this->error_nr_css = $error_nr_css;
		
		$this->css_error = $css_error;
	}
	
	/**
	* public
	* main process of creating file
	* @param
	* $title: validated content title (fount in <title> tag); if empty title will not be displayed
	* $input_content_type: 'file', 'paste' or http://file_path
	* $problem: problem type on which to create report (can be: known, likely, potential, html, css or all)
	* $_gids: array of guidelines that were used as testing criteria
	*/
	public function	getCSV($problem, $input_content_type, $title, $_gids) 
	{	
		// set filename
		$date = AC_Date('%d-%m-%Y');
		$time = AC_Date('%H-%i-%s');
		$filename = 'achecker_'.$date.'_'.$time;
		
		$file_content = $this->getInfo($input_content_type, $title, $_gids, $date, $time);

		if ($problem == 'all') {
			$file_content .= $this->getResultSection('known');
			$file_content .= $this->getResultSection('likely');
			$file_content .= $this->getResultSection('potential');
			if ($this->error_nr_html != -1) $file_content .= $this->getHTML();
			if ($this->error_nr_css != -1) $file_content .= $this->getCSS();
		} else if ($problem == 'css') {
			$file_content .= $this->getCSS();
		} else if ($problem == 'html') {
			$file_content .= $this->getHTML();
		} else {
			$file_content .= $this->getResultSection($problem);
		}	

		$path = AC_TEMP_DIR.$filename.'.csv';  
		$handle = fopen($path, 'w');		
		fwrite($handle, $file_content); 
		fclose($handle);
		
		return $path;		
	}
	
	/**
	* private
	* writes AChecker info, date, time [, url] [, title] and guidelines
	* returns them as CSV string
	* @param
	* $input_content_type: 'file', 'paste' or http://file_path
	* $title: validated content title (fount in <title> tag); if empty title will not be displayed
	* $_gids: array of guidelines that were used as testing criteria
	* $date: date when function to create file called (showed in file title and inside document)
	* $time: time when function to create file called (showed in file title and inside document)
	*/
	private function getInfo($input_content_type, $title, $_gids, $date, $time)
	{		
		// achecker info
		$file_content =  chr(239).chr(187).chr(191)._AC('achecker_file_title').DELIM.'version '.VERSION.DELIM
			.$this->prepareStr(_AC('achecker_file_description')).EOL.$this->achecker_file_url.EOL.EOL;
	
		// date, time
		$file_content .= str_replace("-", ".", $date).' '.str_replace("-", ":", $time).EOL;
		
		// test info
		if ($input_content_type != 'file' && $input_content_type != 'paste') {
			$file_content .= _AC('achecker_file_source_url').DELIM.$input_content_type.EOL;
		} else {
			$file_content .= EOL;
		}
		
		if ($title != '') {
			$file_content .= _AC('achecker_file_source_title').DELIM.$title.EOL;
		} else {
			$file_content .= EOL;
		}		

		// guidelines
		$file_content .= EOL;
	
		$guidelinesDAO = new GuidelinesDAO();
		$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_gids);
		
		if (is_array($guideline_rows)) {
			foreach ($guideline_rows as $id => $row) {
				$file_content .= $row["abbr"].DELIM._AC($row["long_name"]).EOL;		
			}
		}			
		return $file_content;
	}

	/**
	* private
	* prepares given string to be written as single CSV cell (considers '"', ';', ',', '\n')
	* if $str has new lines it's better to replace them by '' or ' '
	* @param
	* $str: string that needs to be prepaired
	* return prepaired $str, one CSV cell
	*/
	private function prepareStr($str) 
	{
		// " => "" and "$str"
		if (strstr($str, '"')) {
			$a = explode('"', $str);
			$str = implode('""', $a);
			$str = '"'.$str.'"';
		}
		
		// \n | ; | , | " => "$str"
		if (($str[0] != '"' && $str[strlen($str)-1] != '"') && 
			((strstr($str, "\n") != false) || (strstr($str, ";") != false) || (strstr($str, ",") != false))) {
		
			$str = '"'.$str.'"';
		}		
		return $str;
	}
	
	/**
	* private
	* writes result section for 1 problem type
	* @param
	* $problem_type: known, potential or likely; corresponding array in class should be set before calling
	* return result section as CSV string
	*/
	private function getResultSection($problem_type) 
	{		
		if ($problem_type == 'known') {
			$array = $this->known;
			$nr = $this->error_nr_known;
			$file_content .= EOL._AC("known_problems").': '.$nr.EOL;
		} else if ($problem_type == 'likely') {
			$array = $this->likely;
			$nr = $this->error_nr_likely;
			$file_content .= EOL._AC("likely_problems").': '.$nr.EOL;
			if (isset($_SESSION['user_id'])) {
				$file_content .= DELIM._AC("achecker_file_decision");
			}
			$file_content .= EOL;
		} else if ($problem_type == 'potential') {
			$array = $this->potential;
			$nr = $this->error_nr_potential;
			$file_content .= EOL._AC("potential_problems").': '.$nr.EOL;
			if (isset($_SESSION['user_id'])) {
				$file_content .= DELIM._AC("achecker_file_decision");
			}
		}
		
		// show congratulations if no errors found
		if ($nr == 0) {
			// congrats message
			$file_content .= _AC("congrats_no_$problem_type").EOL;
		} else {		
			if ($problem_type == 'known') {
				$file_content .= _AC("achecker_file_repair").DELIM._AC("achecker_file_html").DELIM._AC("achecker_file_css").DELIM._AC("achecker_file_img").EOL;
				foreach ($array as $error) {
					// line and column + error text
					$file_content .= $error['line_text'].' '.$error['line_nr'].', '.$error['col_text'].' '.$error['col_nr']
						.': '.$this->prepareStr(strip_tags($error['error'])).EOL;
					
					// repair
					$str = str_replace(EOL, "", $error['repair']['detail']);
					$file_content .= $this->prepareStr(strip_tags($error['repair']['label'].': '.$str)).DELIM;					
						
					// html
					$file_content .= $this->prepareStr(html_entity_decode($error['html_code'], ENT_COMPAT, 'UTF-8')).DELIM;
												
					// css	
					if ($error['css_code']) {				
						$pattern = "/CSS.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s/";
						if (preg_match($pattern, strip_tags($error['css_code']), $matches)) {					
							$file_content .= $this->prepareStr($matches[0]).DELIM;	
						}			
					} else $file_content .= "".DELIM;
					
					// img
					if ($error['image']) {
						$file_content .= $this->prepareStr($error['image']['src']).EOL;
					} else $file_content .= "".DELIM.EOL;
					
				} // end foreach $error
			} 		
			// likely and potential. needed to show 'passed', 'failed' or 'no decision'		
			else { 
				$file_content .= _AC("achecker_file_html").DELIM._AC("achecker_file_css").DELIM._AC("achecker_file_img").EOL;
				foreach ($array as $category) { // with decision, no decision
					foreach ($category as $error) {
						// line and column + error text
						$file_content .= $error['line_text'].' '.$error['line_nr'].', '.$error['col_text'].' '.$error['col_nr']
							.': '.$this->prepareStr(strip_tags($error['error'])).EOL;
							
						// html
						$file_content .= $this->prepareStr(html_entity_decode($error['html_code'], ENT_COMPAT, 'UTF-8')).DELIM;
													
						// css	
						if ($error['css_code']) {				
							$pattern = "/CSS.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s/";
							if (preg_match($pattern, strip_tags($error['css_code']), $matches)) {					
								$file_content .= $this->prepareStr($matches[0]).DELIM;	
							}			
						} else $file_content .= "".DELIM;
						
						// img
						if ($error['image']) {
							$file_content .= $this->prepareStr($error['image']['src']).DELIM;
						} else $file_content .= "".DELIM;
						
						// if user is logged in display 'passed', 'failed' or 'no decision'
						if (isset($_SESSION['user_id'])) {	
							// decision
							if ($error['decision'] == 'true') $file_content .= _AC('achecker_file_passed');
							else if ($error['decision'] == false) $file_content .= _AC('achecker_file_failed');
							else if ($error['decision'] == 'none') $file_content .= _AC('achecker_file_no_decision');	
						} // end if user is logged in
				
						$file_content .= EOL;				
						
					} // end foreach $error
				} // end foreach $category
			}
		
		}		
		return $file_content;
	}

	/**
	* private
	* writes report for HTML validation; corresponding array in class should be set before calling
	* return HTML validation result as CSV string
	*/
	private function getHTML() 
	{		
		$file_content .= EOL._AC("html_validation_result").': '.$this->error_nr_html.DELIM.strip_tags(_AC("html_validator_provided_by")).EOL;		
		
		// show congratulations if no errors found
		if ($this->error_nr_html == 0) {
			// congrats message
			$file_content .= _AC("congrats_html_validation").EOL;
		} else {		
			$file_content .= _AC("achecker_file_html").DELIM._AC("achecker_file_text").EOL;
			foreach ($this->html as $error) {				
				// line and column + error text
				$file_content .= $this->prepareStr(_AC('line')." ".$error['line'].", "._AC('column')." ".$error['col'].":  ".html_entity_decode(strip_tags($error['err']))).EOL;
		
				// html
				$file_content .= $this->prepareStr(html_entity_decode($error['html_1'].$error['html_2'].$error['html_3'], ENT_COMPAT, 'UTF-8')).DELIM;
													
				// text
				if ($error['text']) {
					$str = str_replace("\n", "", strip_tags(html_entity_decode($error['text'])));
					$str = str_replace("\r", "", strip_tags(html_entity_decode($error['text'])));
					$str = preg_replace("/ {2,}/", " ", $str);
					$file_content .= $this->prepareStr($str).EOL;
				} else $file_content .= "".EOL;
					
			} // end foreach $error		
		}		
		return $file_content;
	}
	
	/**
	* private
	* writes report for CSS validation; corresponding array in class should be set before calling
	* return CSS validation result as CSV string
	*/
	private function getCSS() 
	{		
		$file_content .= EOL._AC("css_validation_result").': '.$this->error_nr_css.DELIM.strip_tags(_AC("css_validator_provided_by")).EOL;		
		
		// show congratulations if no errors found
		if ($this->error_nr_css == 0) {
			// congrats message
			$file_content .= _AC("congrats_css_validation").EOL;
		} else {	
			$file_content .= $this->prepareStr(_AC('line')).DELIM.$this->prepareStr(_AC('html_tag')).DELIM.$this->prepareStr(_AC('error')).EOL;
			foreach($this->css as $uri => $group) {
					// uri
					$file_content .= "URI: ".$uri.EOL;
					debug_to_log($uri);
					foreach($group as $error) {
						// line
						$file_content .= $error['line'];	

						// code
						if ($error['code'] != '') $file_content .= DELIM.$this->prepareStr($error['code']);
						else $file_content .= DELIM."";
						
						// parse
						if ($error['parse'] != '') {
							$str = str_replace("\n", "", strip_tags(html_entity_decode($error['parse'])));
							$str = str_replace("\r", "", strip_tags(html_entity_decode($error['parse'])));
							$str = preg_replace("/ {2,}/", " ", $str);
							$file_content .= DELIM.$this->prepareStr($str).EOL;
						}
						else $file_content .= DELIM."".EOL;
						
					} // end foreach error
				} // end foreach group	
		}	
		return $file_content;
	}
	
}
?>