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
* FileExportRptGuideline
* Class to generate error report in form of 3 arrays: known, likely, potential;
* is based on HTMLByGuidelineRpt
* @access	public
* @author	Casian Olga
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/AccessibilityRpt.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
//include_once(AC_INCLUDE_PATH.'fileExport/tcpdf/acheckerTCPDF.php');

class FileExportRptGuideline extends AccessibilityRpt {
	
	var $errors_by_checks = array();               // Re-arranged errors table with the array key check_id

	var $group_known_problems = array();			// array of all info about known problems
	var $group_likely_problems = array();			// array of all info about known likely
	var $group_potential_problems = array();		// array of all info about known potential	
	
	var $nr_known_problems = 0;
	var $nr_likely_problems = 0;
	var $nr_potential_problems = 0;
	
	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: html
	*/
	function FileExportRptGuideline($errors, $gid, $user_link_id = '')
	{
		// run parent constructor
		parent::AccessibilityRpt($errors, $user_link_id);
		
		$this->gid = $gid;
		
//		$this->num_of_no_decisions = 0;
//		$this->num_of_made_decisions = 0;
//		
//		$this->num_of_likely_problems_fail = 0;
//		$this->num_of_potential_problems_fail = 0;

		$this->checksDAO = new ChecksDAO();
		$this->guidelineGroupsDAO = new GuidelineGroupsDAO();
		$this->guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
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
		$this->errors_by_checks = $this->rearrange_errors_array($this->errors);
		
		// display guideline level checks
//		$guidelineLevel_checks = $this->checksDAO->getGuidelineLevelChecks($this->gid);
//
//		if (is_array($guidelineLevel_checks))
//		{
//			list($guideline_level_known_problems, $guideline_level_likely_problems, $guideline_level_potential_problems) =
//				$this->generateChecksTable($guidelineLevel_checks);
//		}
		
		// display all named guidelines and their checks 
		$named_groups = $this->guidelineGroupsDAO->getNamedGroupsByGuidelineID($this->gid);	
		
		if (is_array($named_groups))
		{
			foreach ($named_groups as $group)
			{
//				unset($group_level_known_problems);
//				unset($group_level_likely_problems);
//				unset($group_level_potential_problems);
				unset($subgroup_known_problems);
				unset($subgroup_likely_problems);
				unset($subgroup_potential_problems);
					
//				// get group level checks: the checks in subgroups without subgroup names
//				$groupLevel_checks = $this->checksDAO->getGroupLevelChecks($group['group_id']);				//null ?????
//		
//				if (is_array($groupLevel_checks))
//				{
//					list($group_level_known_problems, $group_level_likely_problems, $group_level_potential_problems) = 
//						$this->generateChecksTable($groupLevel_checks);
//				}
				
				// display named subgroups and their checks
				$named_subgroups = $this->guidelineSubgroupsDAO->getNamedSubgroupByGroupID($group['group_id']);
				
				if (is_array($named_subgroups))
				{
					foreach ($named_subgroups as $subgroup)
					{						
						$subgroup_checks = $this->checksDAO->getChecksBySubgroupID($subgroup['subgroup_id']);
						if (is_array($subgroup_checks))
						{
							// get html of all the problems in this subgroup
							list($known_problems, $likely_problems, $potential_problems) = 
								$this->generateChecksTable($subgroup_checks);
								
							$subgroup_title = _AC($subgroup['name']);
							
							if ($known_problems <> "") {
								$subgroup_known_problems[$subgroup_title] = $known_problems;
							} 
							if ($likely_problems <> "") {
								$subgroup_likely_problems[$subgroup_title] = $likely_problems;
							} 
							if ($potential_problems <> "") {
								$subgroup_potential_problems[$subgroup_title] = $potential_problems;
							}
						}
					} // end of foreach $named_subgroups
				} // end of if $named_subgroups
				
				$group_title = _AC($group['name']);
				
				if ($subgroup_known_problems <> '') {
					$this->group_known_problems[$group_title] = $subgroup_known_problems;
				} 				
//				if ($group_level_known_problems <> '') {
//					$group_known_problems[$group_title] = $group_level_known_problems;
//				}
				
				if ($subgroup_likely_problems <> '') {
					$this->group_likely_problems[$group_title] = $subgroup_likely_problems;
				} 				
//				if ($group_level_likely_problems <> '') {
//					$group_likely_problems[$group_title] = $group_level_likely_problems;
//				}
				
				if ($subgroup_potential_problems <> '') {
					$this->group_potential_problems[$group_title] = $subgroup_potential_problems;
				}
//				if ($group_level_potential_problems <> '') {
//					$group_potential_problems[$group_title] = $group_level_potential_problems;
//				}
			} // end of foreach $named_groups 	
		} // end of if $named_groups
		
//		if ($guideline_level_known_problems <> "") {
//			$this->rpt_errors[] = $guideline_level_known_problems;
//		}
//		if ($group_known_problems <> "") {
//			$this->rpt_errors[] = $group_known_problems;
//		} 
//		
//		if ($guideline_level_likely_problems <> "") {
//			$this->rpt_likely_problems[] = $guideline_level_likely_problems;
//		} 
//		if ($group_likely_problems <> "") {
//			$this->rpt_likely_problems[] = $group_likely_problems;
//		}
//		
//		if ($guideline_level_potential_problems <> "") {
//			$this->rpt_potential_problems[] = $guideline_level_potential_problems;
//		}
//		if ($group_potential_problems <> "") {
//			$this->rpt_potential_problems[] = $group_potential_problems;
//		}
		
//		debug_to_log('=================================================BY GUIDELINES===================================================');
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
		
		return array($this->group_known_problems, $this->group_likely_problems, $this->group_potential_problems);
	}
	
	/**
	 * Re-arrang check error array with check_id as the primary key
	 * @param: $errors - the error array
	 * @return: Re-arranged error array
	 */
	private function rearrange_errors_array($errors) {
		// return an empty array if the parameter is not an expected array
		if (!is_array($errors)) return array();
		
		$new_errors = array();
		foreach ($errors as $error) {
			$new_errors[$error["check_id"]][] = $error;
		}
		return $new_errors;
	}

	/**
	 * private
	 * Return html of the checks error table
	 * @param $checks_array
	 * @return an array of htmls of (known_problem, likely_problems, potential_problems)
	 */
	private function generateChecksTable($checks_array) // ($subgroup['subgroup_id'])
	{  
		if (!is_array($checks_array)) return NULL;
		
		foreach ($checks_array as $check) {
			unset($howto_repair);
			unset($question);
			
			$check_id = $check["check_id"];

			// continue with the next check if there is no errors for this check
			if (!is_array($this->errors_by_checks[$check_id])) continue;
			
			$row = $this->checksDAO->getCheckByID($check_id);       // row contains check info (by id)
			
			$repair = _AC($row['how_to_repair']);
			if ($repair <> '') 
			{				
				$howto_repair['label'] = _AC("repair");
				$howto_repair['detail'] = $repair;
			}
			
//	uncomment if Question/PASS/FAIL	needed
//			if (($row["confidence"] == LIKELY || $row["confidence"] == POTENTIAL)) 
//			{				
//				$question['question_label'] = _AC("question");				
//				$question['question'] = _AC($row['question']);
//				$question['pass_label'] = _AC("pass");				
//				$question['pass_answer'] = _AC($row['decision_pass']);
//				$question['fail_label'] = _AC("fail");				
//				$question['fail_answer'] = _AC($row['decision_fail']);

//				$one_problem['question'] = $question;
//			}
			
			$error_set = $this->get_table_rows_for_one_check($this->errors_by_checks[$check_id], $check_id, $row["confidence"]);
			
			$one_problem['check_label'] = _AC("check");			
			$one_problem['check_id'] = $check_id;			
			$one_problem['error'] = _AC($row["err"]);
			$one_problem['repair'] = $howto_repair;						
			$one_problem['subgroup_id'] = $check["subgroupID"];                 // as key one_problem => subgroup
			$one_problem['errors'] = $error_set;
			                            
			if ($row["confidence"] == KNOWN) { 
				$known[] = $one_problem;
			} else if ($row["confidence"] == LIKELY) { 
				$likely[] = $one_problem;
			} else if ($row["confidence"] == POTENTIAL) { 
				$potential[] = $one_problem;
			}
		}
		return array($known, $likely, $potential);
	}
	
	/** 
	* private
	* generate html table rows for all errors on one check 
	* @param
	* $errors_for_this_check: all errors
	* $check_id
	* $confidence: KNOWN, LIKELY, POTENTIAL  @ see include/constants.inc.php
	* @return html table rows
	*/
	private function get_table_rows_for_one_check($errors_for_this_check, $check_id, $confidence)
	{
		if (!is_array($errors_for_this_check)) {  // no problem found for this check
			return '';
		}
		
		foreach ($errors_for_this_check as $error) {			
			if ($confidence == KNOWN) {
				$this->nr_known_problems++;
//				$this->num_of_errors++;				
				$img_type = _AC('error');
				$img_src = "error.png";
			} else if ($confidence == LIKELY) {
//				$this->num_of_likely_problems++;				
				$img_type = _AC('warning');
				$img_src = "warning.png";
			} else if ($confidence == POTENTIAL) {
//				$this->num_of_potential_problems++;				
				$img_type = _AC('manual_check');
				$img_src = "info.png";
			}
			
			// only display first 100 chars of $html_code
			if (strlen($error["html_code"]) > 100)
			$html_code = substr($error["html_code"], 0, 100) . " ...";
				
			if ($error["image"] <> '') {
				$height = DISPLAY_PREVIEW_IMAGE_HEIGHT;
				
				if ($error["image_alt"] == '_NOT_DEFINED') $alt = '';
				else if ($error["image_alt"] == '_EMPTY') $alt = 'alt=""';
				else $alt = 'alt="'.$error["image_alt"].'"';
				
				$error_img['img_src'] = $error["image"];
				$error_img['height'] = $height;
			}			
		
			$userDecisionsDAO = new UserDecisionsDAO();
			$row = $userDecisionsDAO->getByUserLinkIDAndLineNumAndColNumAndCheckID($this->user_link_id, $error["line_number"], $error["col_number"], $error['check_id']);

			if (!$row || $row['decision'] == AC_DECISION_FAIL) { // no decision or decision of fail
				if ($confidence == LIKELY) {
					$this->nr_likely_problems++;
				}
				if ($confidence == POTENTIAL) {
					$this->nr_potential_problems++;
				}
			}
			
			$passed = FALSE;
			
			if (!$row) {
				$passed = 'none';
			}
			
			if ($row && $row['decision'] == AC_DECISION_PASS) { // pass decision has been made, display "congrats" icon
				$msg_type = "msg_info";
				$img_type = _AC('passed_decision');
				$img_src = "feedback.gif";
				
				$passed = TRUE;
			}		

		    $problem_cell['img_src'] = $img_src;
		    $problem_cell['line_text'] = _AC('line');
		    $problem_cell['line_nr'] = $error['line_number'];
		    $problem_cell['col_text'] = _AC('column');
		    $problem_cell['col_nr'] = $error["col_number"];
		    $problem_cell['check_id'] = $check_id;
		    $problem_cell['html_code'] = htmlentities($error["html_code"]);
		    $problem_cell['css_code'] = $error['css_code'];
		    $problem_cell['base_href'] = AC_BASE_HREF;
		    $problem_cell['error_img'] = $error_img;
		    $problem_cell['test_passed'] = $passed;
			
			$array[] = $problem_cell;
		}		
		return $array;
	}
	


	
}
?>