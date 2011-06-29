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
* for each of types: known, likely, potential, all 
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

class acheckerCSV {

	// all private
	// arrays that contain all data about errors of specific type
	var $known = array();
	var $likely = array();
	var $potential = array();
	
	// numbers of errors to display for each problem type
	var $error_nr_known = 0;
	var $error_nr_likely = 0;
	var $error_nr_potential = 0;
	
	var $achecker_file_url = 'http://www.atutor.ca/achecker/';
		
	
	/**
	* public
	* error arrays and numbers setter
	*/
	function acheckerCSV($known, $likely, $potential, $error_nr_known, $error_nr_likely, $error_nr_potential)
	{				
		$this->known = $known;
		$this->likely = $likely;
		$this->potential = $potential;	
		
		$this->error_nr_known = $error_nr_known;
		$this->error_nr_likely = $error_nr_likely;
		$this->error_nr_potential = $error_nr_potential;
	}
	
	/**
	* public
	* main process of creating file
	*/
	public function	getCSV($problem, $input_content_type, $title, $_gids) 
	{	
		$file_content = $this->getInfo($input_content_type, $title, $_gids);
	
		if ($problem == 'all') {
			$file_content .= $this->getResultSection('known');
			$file_content .= $this->getResultSection('likely');
			$file_content .= $this->getResultSection('potential');
		} else {
			$file_content .= $this->getResultSection($problem);
		}	
		
		$path = AC_INCLUDE_PATH.'fileExport/csv.csv';
		$handle = fopen($path, 'w');		
		fwrite($handle, $file_content); 
		fclose($handle);
	}
	
	/**
	* private
	* computes AChecker info, date, time [, url] [, title] and guidelines
	* returns them as string
	*/
	private function getInfo($input_content_type, $title, $_gids)
	{		
		// achecker info
		$file_content =  chr(239).chr(187).chr(191)._AC('achecker_file_title').DELIM.'version '.VERSION.DELIM
			.$this->prepareStr(_AC('achecker_file_description')).EOL.$this->achecker_file_url.EOL.EOL;
	
		// date, time
		$file_content .= AC_Date('%d.%m.%Y').' '.AC_Date('%H:%i:%s').EOL;
		
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
	* prepares given string to be written as single csv cell
	* (considers '"', ';', ',', '\n')
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
	* computes Result Section for 1 problem type
	* returns it as string
	*/
	private function getResultSection($problem_type) 
	{		
		$this->error_id = 1;
		
		if ($problem_type == 'known') {
			$array = $this->known;
			$nr = $this->error_nr_known;
			$file_content .= EOL._AC("known_problems").': '.$nr.EOL;
			$file_content .= _AC("achecker_file_repair").DELIM._AC("achecker_file_html").DELIM._AC("achecker_file_css").DELIM._AC("achecker_file_img").DELIM.EOL;
		} else if ($problem_type == 'likely') {
			$array = $this->likely;
			$nr = $this->error_nr_likely;
			$file_content .= EOL._AC("likely_problems").': '.$nr.EOL;
			$file_content .= _AC("achecker_file_html").DELIM._AC("achecker_file_css").DELIM._AC("achecker_file_img");
			if (isset($_SESSION['user_id'])) {
				$file_content .= DELIM._AC("achecker_file_decision");
			}
			$file_content .= EOL;
		} else if ($problem_type == 'potential') {
			$array = $this->potential;
			$nr = $this->error_nr_potential;
			$file_content .= EOL._AC("potential_problems").': '.$nr.EOL;
			$file_content .= _AC("achecker_file_html").DELIM._AC("achecker_file_css").DELIM._AC("achecker_file_img");
			if (isset($_SESSION['user_id'])) {
				$file_content .= DELIM._AC("achecker_file_decision");
			}
			$file_content .= EOL;
		}
		
		// show congratulations if no errors found
		if ($nr == 0) {
			// congrats message
			$file_content .= _AC("congrats_no_$problem_type").EOL;
		} else {		
			if ($problem_type == 'known') {
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
		
	
}
?>