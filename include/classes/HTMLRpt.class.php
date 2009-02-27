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
* HTMLRpt
* Class to generate error report in html format 
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');

class HTMLRpt {

	// all private
	var $errors;                         // an array, output of AccessibilityValidator -> getValidationErrorRpt
	var $user_link_id;                   // user_links.user_link_id; default to ''
	var $show_decision;                  // true or false. default to "false". show decision choices or not.
	
	var $num_of_errors;                  // Number of known errors. (db: checks.confidence = "Known")
	var $num_of_likely_problems;         // Number of likely errors. (db: checks.confidence = "Likely")
	var $num_of_potential_problems;      // Number of potential errors. (db: checks.confidence = "Potential")
	
	var $rpt_errors;                     // <DIV> section of errors
	var $rpt_likely_problems;            // <DIV> section of likely problems
	var $rpt_potential_problems;         // <DIV> section of potential problems
	
	// temp vars
	var $rpt_likely_decision_made;       // html string for checks that decisions have been made
	var $rpt_likely_decision_not_made;   // html string for checks that decisions have NOT been made
	var $rpt_potential_decision_made;       // html string for checks that decisions have been made
	var $rpt_potential_decision_not_made;   // html string for checks that decisions have NOT been made
	
	// HTML templates
	var $html_problem =
'      <li class="{MSG_TYPE}">
         <span class="err_type"><img src="{BASE_HREF}images/{IMG_SRC}" alt="{IMG_TYPE}" title="{IMG_TYPE}" width="15" height="15" /></span>
         <em>Line {LINE_NUMBER}, Column {COL_NUMBER}</em>:
         <span class="msg">
            <a href="{BASE_HREF}checker/suggestion.php?id={CHECK_ID}"
               onclick="popup(\'{BASE_HREF}checker/suggestion.php?id={CHECK_ID}\'); return false;" 
               title="{TITLE}" target="_new">{ERROR}</a>
         </span>
         <pre><code class="input">{HTML_CODE}</code></pre>
         <p class="helpwanted">
         </p>
         {REPAIR}
         {DECISION}
       </li>
';

	var $html_repair = 
'         <span style="font-weight:bold">Repair: </span>{REPAIR_DETAIL}
';
	
	var $html_decision_not_made = 
'<table>
  <tr>
    <td>
      <input value="P" type="radio" name="d[{SEQUENCE_ID}]" id="pass{SEQUENCE_ID}" {PASS_CHECKED} />
      <label for="pass{SEQUENCE_ID}">{DECISION_PASS}</label>
   </td>
  <tr>
  <tr>
    <td>
	  <input value="F" type="radio" name="d[{SEQUENCE_ID}]" id="fail{SEQUENCE_ID}" {FAIL_CHECKED} />
      <label for="fail{SEQUENCE_ID}">{DECISION_FAIL}</label>
    </td>
  <tr>
  <tr>
    <td>
	  <input value="N" type="radio" name="d[{SEQUENCE_ID}]" id="nodecision{SEQUENCE_ID}" {NODECISION_CHECKED} />
      <label for="nodecision{SEQUENCE_ID}">{DECISION_NO}</label>
    </td>
  <tr>
</table>
';

	var $html_decision_made = 
'<table>
  <tr>
    <td>{LABEL_DECISION}</td>
    <td>{DECISION}</td>
  <tr>
  <tr>
    <td>{LABEL_USER}</td>
    <td>{USER}</td>
  <tr>
  <tr>
    <td>{LABEL_DATE}</td>
    <td>{DATE}</td>
  <tr>
</table>
';

	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: html
	*/
	function HTMLRpt($errors, $user_link_id = '', $show_decision)
	{
		global $msg;

		// user_link_id must be given to show decision section
		if ((!isset($user_link_id) || $user_link_id == '') && $show_decision == 'true')
		{
			$msg->addError('NONE_USER_LINK');
			return false;
		}
		
		$this->errors = $errors;
		$this->user_link_id = $user_link_id;
		$this->show_decision = $show_decision;           // set default "show decision choices" to false
		
		$this->num_of_errors = 0;
		$this->num_of_likely_problems = 0;
		$this->num_of_potential_problems = 0;
		
		$this->rpt_errors = "";
		$this->rpt_likely_problems = "";
		$this->rpt_potential_problems = "";
		
		$this->generateHTMLRpt();
	}
	
	/**
	* private
	* generate report in html format
	*/
	private function generateHTMLRpt()
	{
		// initialize each section
		$this->rpt_errors = "<h2>". _AC("known_problems") ."</h2><br />";
		$this->rpt_likely_problems = "<h2>". _AC("likely_problems") ."</h2><br />";
		$this->rpt_potential_problems = "<h2>". _AC("potential_problems") ."</h2><br />";

		// generate section details
		foreach ($this->errors as $error)
		{
			$checksDAO = new ChecksDAO();
			$row = $checksDAO->getCheckByID($error["check_id"]);

			if ($row["confidence"] == KNOWN)
			{ // no decision to make on known problems
				$this->num_of_errors++;
				
				$this->rpt_errors .= $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_ERROR);
			}
			else if ($row["confidence"] == LIKELY)
			{
				if ($this->show_decision == 'false')
				{
					$this->num_of_likely_problems++;
					$this->rpt_likely_problems .= $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_WARNING);
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"], IS_WARNING);
				}
			}
			else if ($row["confidence"] == POTENTIAL)
			{
				if ($this->show_decision == 'false')
				{
					$this->num_of_potential_problems++;
					$this->rpt_potential_problems .= $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_INFO);
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"], IS_INFO);
				}
			}
		}
		
		if ($this->show_decision == 'true')
		{
			$this->rpt_likely_problems = $this->rpt_likely_decision_not_made.$this->rpt_likely_decision_made;
			$this->rpt_potential_problems = $this->rpt_potential_decision_not_made.$this->rpt_potential_decision_made;
		}
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
	private function generate_cell_with_decision($check_row, $line_number, $col_number, $html_code, $error_type)
	{
		// generate decision section
		$userDecisionsDAO = new UserDecisionsDAO();
		$row = $userDecisionsDAO->getByUserLinkIDAndLineNumAndColNumAndCheckID($this->user_link_id, $line_number, $col_number, $check_row['check_id']);
		
		if ($row['decision'] == AC_NO_DECISION || $row['decision'] == AC_DECISION_FAIL)
		{
			if ($error_type == IS_WARNING) $this->num_of_likely_problems++;
			if ($error_type == IS_INFO) $this->num_of_potential_problems++;
		}
		
		if ($row['decision'] == AC_NO_DECISION)
		{
			$decision_section = str_replace(array("{SEQUENCE_ID}", 
			                                      "{PASS_CHECKED}", 
			                                      "{FAIL_CHECKED}", 
			                                      "{NODECISION_CHECKED}", 
			                                      "{DECISION_PASS}", 
			                                      "{DECISION_FAIL}", 
			                                      "{DECISION_NO}"),
			                                array($row['sequence_id'],
			                                      "",
			                                      "",
			                                      'checked="checked"',
			                                      _AC($check_row['decision_pass']),
			                                      _AC($check_row['decision_fail']),
			                                      _AC('no_decision')),
			                                $this->html_decision_not_made);
			                                
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->rpt_likely_decision_not_made .= $problem_section;
			if ($error_type == IS_INFO) $this->rpt_potential_decision_not_made .= $problem_section;
		}
		else
		{
			if ($row['decision'] == AC_DECISION_PASS) $decision = $check_row['decision_pass'];
			if ($row['decision'] == AC_DECISION_FAIL) $decision = $check_row['decision_fail'];
			
			$decision_section = str_replace(array("{LABEL_DECISION}", 
			                                      "{DECISION}", 
			                                      "{LABEL_USER}", 
			                                      "{USER}", 
			                                      "{LABEL_DATE}", 
			                                      "{DATE}"),
			                                 array(_AC('decision'),
			                                       $decision,
			                                       _AC('user'),
			                                       $row['user_name'],
			                                       _AC('date'),
			                                       $row['last_update']),
			                                 $this->html_decision_made);
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->rpt_likely_decision_made .= $problem_section;
			if ($error_type == IS_INFO) $this->rpt_potential_decision_made .= $problem_section;
		}
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
	private function generate_problem_section($check_id, $line_number, $col_number, $html_code, $error, $repair, $decision, $error_type)
	{
		if ($error_type == IS_ERROR)
		{
			$msg_type = "msg_err";
			$img_type = "Error";
			$img_src = "error.png";
		}
		else if ($error_type == IS_WARNING)
		{
			$msg_type = "msg_info";
			$img_type = "Info";
			$img_src = "warning.png";
		}
		else if ($error_type == IS_INFO)
		{
			$msg_type = "msg_info";
			$img_type = "Info";
			$img_src = "info.png";
		}
		
		// only display first 100 chars of $html_code
		if (strlen($html_code) > 100)
			$html_code = substr($html_code, 0, 100) . " ...";
			
		// generate repair string
		if ($repair <> '') $html_repair = str_replace('{REPAIR_DETAIL}', $repair, $this->html_repair);
		
		return str_replace(array("{MSG_TYPE}", 
		                         "{IMG_SRC}", 
		                         "{IMG_TYPE}", 
		                         "{LINE_NUMBER}", 
		                         "{COL_NUMBER}", 
		                         "{HTML_CODE}", 
		                         "{ERROR}", 
		                         "{BASE_HREF}", 
		                         "{CHECK_ID}", 
		                         "{TITLE}",
		                         "{REPAIR}",
		                         "{DECISION}"),
		                   array($msg_type, 
		                         $img_src, 
		                         $img_type, 
		                         $line_number, 
		                         $col_number, 
		                         htmlentities($html_code), 
		                         $error, 
		                         AC_BASE_HREF, 
		                         $check_id, 
		                         _AC("suggest_improvements"),
		                         $html_repair,
		                         $decision),
		                   $this->html_problem);
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
	private function generate_section_decision($check_id, $line_number, $col_number, $html_code, $error, $error_type)
	{
		
		// if pass decision has been made, 
	}
	
	/**
	* public 
	* return validation error report in html
	*/
	public function getErrorRpt()
	{
		return $this->rpt_errors;
	}

	/**
	* public 
	* return validation likely problem report in html
	*/
	public function getLikelyProblemRpt()
	{
		return $this->rpt_likely_problems;
	}

	/**
	* public 
	* return validation error report in html
	*/
	public function getPotentialProblemRpt()
	{
		return $this->rpt_potential_problems;
	}

	/**
	* public 
	* return number of known errors
	*/
	public function getNumOfErrors()
	{
		return $this->num_of_errors;
	}
	
	/**
	* public 
	* return number of known errors
	*/
	public function getNumOfLikelyProblems()
	{
		return $this->num_of_likely_problems;
	}
	
	/**
	* public 
	* return number of known errors
	*/
	public function getNumOfPotentialProblems()
	{
		return $this->num_of_potential_problems;
	}
}
?>  
