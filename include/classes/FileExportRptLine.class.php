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
* FileExportRptLine
* Class to generate error report in form of 5 arrays: known, likely (with and without decision), potential (with and without decision)
* is based on HTMLRpt
* @access	public
* @author	Casian Olga
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/AccessibilityRpt.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');

class FileExportRptLine extends AccessibilityRpt {
	
	var $errors_by_checks = array();              				// Re-arranged errors table with the array key check_id

	var $group_known_problems = array();						// array of all info about known problems	
	var $group_likely_problems = array();						// array of all info about likely problems
	var $group_potential_problems = array();					// array of all info about potential problems
	
	var $group_likely_problems_no_decision = array();			// array of info about likely problems	no_decision
	var $group_potential_problems_no_decision = array();		// array of info about potential problems	no_decision	
	var $group_likely_problems_with_decision = array();			// array of info about likely problems	with_decision
	var $group_potential_problems_with_decision = array();		// array of info about potential problems	with_decision	
	
	var $nr_known_problems = 0;
	var $nr_likely_problems = 0;
	var $nr_potential_problems = 0;
	
	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: array
	*/
	function FileExportRptLine($errors, $user_link_id = '')
	{
		// run parent constructor
		parent::AccessibilityRpt($errors, $user_link_id);
	}

	/**
	* public
	* returns nr of errors - $nr_known_problems, $nr_likely_problems, $nr_potential_problems
	*/
	public function getErrorNr()
	{
		return array($this->nr_known_problems, $this->nr_likely_problems, $this->nr_potential_problems);
	}
	
	/**
	* public
	* main process to generate report and store result in 3 arrays
	*/
	public function generateRpt()
	{		
		$checksDAO = new ChecksDAO();
		
		// generate section details
		foreach ($this->errors as $error)
		{
			$row = $checksDAO->getCheckByID($error["check_id"]);
			if ($row["confidence"] == KNOWN )
			{ // no decision to make on known problems
				$this->nr_known_problems++;
				
				$this->group_known_problems[] = $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], 
					$error["html_code"], $error["image"], $error["image_alt"], $error["css_code"], _AC($row["err"]), _AC($row["how_to_repair"]), 
					'', IS_ERROR);
			}
			else if ($row["confidence"] == LIKELY )
			{
				if ($this->user_link_id == '') 
				{
					$this->group_likely_problems_no_decision[] = $this->generate_problem_section($error["check_id"], $error["line_number"], 
						$error["col_number"], $error["html_code"], $error["image"], $error["image_alt"], $error["css_code"],_AC($row["err"]), 
						_AC($row["how_to_repair"]), '', IS_WARNING);
					$this->nr_likely_problems++;
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"],$error['image'], 
						$error["image_alt"], IS_WARNING);
				}
			}
			else if ($row["confidence"] == POTENTIAL )
			{
				if ($this->user_link_id == '') 
				{
					$this->group_potential_problems_no_decision[] = $this->generate_problem_section($error["check_id"], $error["line_number"], 
						$error["col_number"], $error["html_code"], $error["image"], $error["image_alt"],$error["css_code"], _AC($row["err"]), 
						_AC($row["how_to_repair"]), '', IS_INFO);
					$this->nr_potential_problems++;
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"],$error['image'], 
						$error["image_alt"], IS_INFO);
				}
			}
		}
		
		$this->group_likely_problems['no_decision'] = $this->group_likely_problems_no_decision;
		$this->group_likely_problems['with_decision'] = $this->group_likely_problems_with_decision;
		
		$this->group_potential_problems['no_decision'] = $this->group_potential_problems_no_decision;
		$this->group_potential_problems['with_decision'] = $this->group_potential_problems_with_decision;
		
		return array($this->group_known_problems, $this->group_likely_problems, $this->group_potential_problems);
	}
	
	/** 
	* private
	* generate array with decision
	* parameters:
	* $check_row: table row of the check
	* $line_number: line number that the error happens
	* $col_number: column number that the error happens
	* $html_tag: html tag that the error happens
	* $error_type: IS_WARNING or IS_INFO
	*/
	private function generate_cell_with_decision($check_row, $line_number, $col_number, $html_code, $image, $image_alt, $error_type)
	{
		// generate decision section
		$userDecisionsDAO = new UserDecisionsDAO();		
		$row = $userDecisionsDAO->getByUserLinkIDAndLineNumAndColNumAndCheckID($this->user_link_id, $line_number, $col_number, $check_row['check_id']);
		
		if (!$row || $row['decision'] == AC_DECISION_FAIL) { // no decision or decision of fail
			if ($error_type == IS_WARNING) $this->nr_likely_problems++;
			if ($error_type == IS_INFO) $this->nr_potential_problems++;
		}
		
		if (!$row) {
			$decision_section = 'none';
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, 
				$css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->group_likely_problems_no_decision[] = $problem_section;
			if ($error_type == IS_INFO) $this->group_potential_problems_no_decision[] = $problem_section;
		}
		
		if ($row && $row['decision'] == AC_DECISION_PASS) { // pass decision has been made, display "congrats" icon				
			$decision_section = TRUE;
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, 
				$css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->group_likely_problems_with_decision[] = $problem_section;
			if ($error_type == IS_INFO) $this->group_potential_problems_with_decision[] = $problem_section;
		}
		
		if ($row && $row['decision'] == AC_DECISION_FAIL) { // pass decision has been made, display "congrats" icon				
			$decision_section = FALSE;
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, 
				$css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->group_likely_problems_with_decision[] = $problem_section;
			if ($error_type == IS_INFO) $this->group_potential_problems_with_decision[] = $problem_section;
		}
	}
	
	/** 
	* private
	* return problem array
	* parameters:
	* $line_number: line number that the error happens
	* $col_number: column number that the error happens
	* $html_tag: html tag that the error happens
	* $description: error description
	*/
	private function generate_problem_section($check_id, $line_number, $col_number, $html_code, $image, $image_alt, $css_code, $error, $repair, 
		$decision, $error_type)
	{
		if ($error_type == IS_ERROR) 		$img_src = "error.png";
		else if ($error_type == IS_WARNING)	$img_src = "warning.png";
		else if ($error_type == IS_INFO)	$img_src = "info.png";
		
		// only display first 100 chars of $html_code
		if (strlen($html_code) > 100)
		$array_code = substr($html_code, 0, 100) . " ...";
			
		// generate repair string
		if ($repair <> '') {
			$array_repair['label'] = _AC("repair");
			$array_repair['detail'] = $repair;
		}
		
		if ($image <> '') 
		{			
			$height = DISPLAY_PREVIEW_IMAGE_HEIGHT;
			
			if ($image_alt == '_NOT_DEFINED') $alt = '';
			else if ($image_alt == '_EMPTY') $alt = 'alt=""';
			else $alt = 'alt="'.$image_alt.'"';
			
			$array_image['src'] = $image;
			$array_image['height'] = $height;
			$array_image['alt'] = $alt;
		}
		
		$result['img_src'] = $img_src;
		$result['line_text'] = _AC('line');
		$result['line_nr'] = $line_number;
		$result['col_text'] = _AC('column');
		$result['col_nr'] = $col_number;
		$result['html_code'] = htmlentities($html_code, ENT_COMPAT, 'UTF-8');
		$result['css_code'] = $css_code;
		$result['error'] = $error;
		$result['base_href'] = AC_BASE_HREF;
		$result['image'] = $array_image;
		$result['repair'] = $array_repair;
		$result['decision'] = $decision;
		
		return $result;	
	}


	
}
?>