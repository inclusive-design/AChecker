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
* Class to generate error report in form of 5 arrays: known, likely (with and without decision), 
* potential (with and without decision);
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
	
	var $group_likely_problems_no_decision = array();			// array of info about known likely 	no_decision
	var $group_potential_problems_no_decision = array();		// array of info about known potential 	no_decision	
	var $group_likely_problems_with_decision = array();			// array of info about known likely 	with_decision
	var $group_potential_problems_with_decision = array();		// array of info about known potential 	with_decision	
	
	var $nr_known_problems = 0;
	var $nr_likely_problems = 0;
	var $nr_potential_problems = 0;
	
	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: html
	*/
	function FileExportRptLine($errors, $user_link_id = '')
	{
		// run parent constructor
		parent::AccessibilityRpt($errors, $user_link_id);
		
		$this->gid = $gid;
		
//		$this->num_of_no_decisions = 0;
//		$this->num_of_made_decisions = 0;
//		
//		$this->num_of_likely_problems_fail = 0;
//		$this->num_of_potential_problems_fail = 0;
	}

	/**
	* public
	* returns nr of errors to display - $nr_known_problems, $nr_likely_problems, $nr_potential_problems
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
//				$this->num_of_errors++;
				$this->nr_known_problems++;
				
				$this->group_known_problems[] = $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $error["image"], $error["image_alt"], $error["css_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_ERROR);
			}
			else if ($row["confidence"] == LIKELY )
			{
//				$this->num_of_likely_problems++;
				if ($this->user_link_id == '') //($this->allow_set_decision == 'false' && !($this->from_referer == 'true' && $this->user_link_id > 0))
				{
					$this->group_likely_problems_no_decision[] = $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $error["image"], $error["image_alt"], $error["css_code"],_AC($row["err"]), _AC($row["how_to_repair"]), '', IS_WARNING);
//					$this->num_of_likely_problems_fail++;
					$this->nr_likely_problems++;
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"],$error['image'], $error["image_alt"], IS_WARNING);
				}
			}
			else if ($row["confidence"] == POTENTIAL )
			{
//				$this->num_of_potential_problems++;
				if ($this->user_link_id == '') //($this->allow_set_decision == 'false' && !($this->from_referer == 'true' && $this->user_link_id > 0))
				{
					$this->group_potential_problems_no_decision[] = $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $error["image"], $error["image_alt"],$error["css_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_INFO);
//					$this->num_of_potential_problems_fail++;
					$this->nr_potential_problems++;
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"],$error['image'], $error["image_alt"], IS_INFO);
				}
			}
		}
		
//		if ($this->allow_set_decision == 'true' || 
//		    ($this->allow_set_decision == 'false' && $this->from_referer == 'true' && $this->user_link_id > 0))
//		{
//			$this->rpt_likely_problems .= $this->rpt_likely_decision_not_made.$this->rpt_likely_decision_made;
//			$this->rpt_potential_problems .= $this->rpt_potential_decision_not_made.$this->rpt_potential_decision_made;
//		}		
		
		debug_to_log('=================================================BY LINES===================================================');
//		debug_to_log($this->nr_known_problems);
//		debug_to_log($this->nr_likely_problems);
//		debug_to_log($this->nr_potential_problems);
//		
////		debug_to_log(count($this->group_known_problems));
//		debug_to_log($this->group_known_problems);
//		debug_to_log('----------------------------------------------likely----------------');
////		debug_to_log(count($this->group_likely_problems));
//		debug_to_log($this->group_likely_problems);
//		debug_to_log('----------------------------------------------potential----------------');
////		debug_to_log(count($this->group_potential_problems));
//		debug_to_log($this->group_potential_problems);
		
		$this->group_likely_problems['no_decision'] = $this->group_likely_problems_no_decision;
		$this->group_likely_problems['with_decision'] = $this->group_likely_problems_with_decision;
		
		$this->group_potential_problems['no_decision'] = $this->group_potential_problems_no_decision;
		$this->group_potential_problems['with_decision'] = $this->group_potential_problems_with_decision;
		
		return array($this->group_known_problems, $this->group_likely_problems, $this->group_potential_problems);
	}
	
	/** 
	* private
	* generate html output with decision. In html output, the errors with no decision made are display at the top,
	* followed by errors that decisions have been made. This method also calculates number of errors based on made decisions.
	* If a decision is made as pass, the error is ignored without adding into number of errors.
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
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, $css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->group_likely_problems_no_decision[] = $problem_section;
			if ($error_type == IS_INFO) $this->group_potential_problems_no_decision[] = $problem_section;
		}
		
		if ($row && $row['decision'] == AC_DECISION_PASS) { // pass decision has been made, display "congrats" icon				
			$decision_section = TRUE;
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, $css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->group_likely_problems_with_decision[] = $problem_section;
			if ($error_type == IS_INFO) $this->group_potential_problems_with_decision[] = $problem_section;
		}
		
		if ($row && $row['decision'] == AC_DECISION_FAIL) { // pass decision has been made, display "congrats" icon				
			$decision_section = FALSE;
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, $css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->group_likely_problems_with_decision[] = $problem_section;
			if ($error_type == IS_INFO) $this->group_potential_problems_with_decision[] = $problem_section;
		}
//			$this->num_of_made_decisions++;
	}
	
	/** 
	* private
	* return problem section
	* parameters:
	* $line_number: line number that the error happens
	* $col_number: column number that the error happens
	* $html_tag: html tag that the error happens
	* $description: error description
	*/
	private function generate_problem_section($check_id, $line_number, $col_number, $html_code, $image, $image_alt, $css_code, $error, $repair, $decision, $error_type)
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
			// COMMENTTED OUT the way to determine the image display size by measuring the actual image size
			// since the fetch of the remote images slows down the process a lot. 
//			$dimensions = getimagesize($image);
//			if ($dimensions[1] > DISPLAY_PREVIEW_IMAGE_HEIGHT) $height = DISPLAY_PREVIEW_IMAGE_HEIGHT;
//			else $height = $dimensions[1];
			
			$height = DISPLAY_PREVIEW_IMAGE_HEIGHT;
			
			if ($image_alt == '_NOT_DEFINED') $alt = '';
			else if ($image_alt == '_EMPTY') $alt = 'alt=""';
			else $alt = 'alt="'.$image_alt.'"';
			
			$array_image['src'] = $image;
			$array_image['height'] = $height;
			$array_image['alt'] = $alt;
		}
		
//		$result['msg_type'] = $msg_type;
		$result['img_src'] = $img_src;
		$result['line_text'] = _AC('line');
		$result['line_nr'] = $line_number;
		$result['col_text'] = _AC('column');
		$result['col_nr'] = $col_number;
		$result['html_code'] = htmlentities($html_code);
		$result['css_code'] = $css_code;
		$result['error'] = $error;
		$result['base_href'] = AC_BASE_HREF;
//		$result['title'] = _AC("suggest_improvements");
		$result['image'] = $array_image;
		$result['repair'] = $array_repair;
		$result['decision'] = $decision;
		
		return $result;	
	}


	
}
?>