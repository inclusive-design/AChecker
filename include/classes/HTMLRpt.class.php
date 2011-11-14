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
* HTMLRpt
* Class to generate error report in html format 
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/AccessibilityRpt.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');

class HTMLRpt extends AccessibilityRpt {

	// all private
	var $num_of_no_decisions;                // Number of likely/potential errors that decisions have not been made
	var $num_of_made_decisions;              // Number of likely/potential errors that decisions have been made
	
	var $num_of_likely_problems_fail;        // Number of likely errors that decisions have not been made
	var $num_of_potential_problems_fail;     // Number of potential errors that decisions have not been made
	
	var $rpt_likely_decision_made;           // The output report of the likely problems which are the ones that decisions have been made on 
	var $rpt_potential_decision_made;        // The output report of the likely problems which are the ones that decisions have been made on
	
	var $rpt_likely_decision_not_made;       // The output report of the likely problems which are the ones that no decisions have been made on
	var $rpt_potential_decision_not_made;    // The output report of the likely problems which are the ones that no decisions have been made on
	
	var $html_problem_achecker =
'      <li class="{MSG_TYPE}">
         <span class="err_type"><img src="{BASE_HREF}images/{IMG_SRC}" alt="{IMG_TYPE}" title="{IMG_TYPE}" width="15" height="15" /></span>
         <em>{LINE_TEXT} {LINE_NUMBER}, {COL_TEXT} {COL_NUMBER}</em>:
         <span class="msg">
            <a href="{BASE_HREF}checker/suggestion.php?id={CHECK_ID}"
               onclick="AChecker.popup(\'{BASE_HREF}checker/suggestion.php?id={CHECK_ID}\'); return false;" 
               target="_new">{ERROR}</a>
         </span>
         <pre><code class="input">{HTML_CODE}</code></pre>
         {IMAGE}
         <p class="helpwanted">
         </p>
         {REPAIR}
         {DECISION}
         {CSS_CODE}
       </li>
';

	var $html_image = 
'<img src="{SRC}" height="{HEIGHT}" border="1" {ALT} />
';

	var $html_repair = 
'         <span style="font-weight:bold">{REPAIR_LABEL}: </span>{REPAIR_DETAIL}
';
	
	var $html_decision_not_made = 
'<table>
  <tr>
    <td>
      {QUESTION}
   </td>
  </tr>
  <tr>
    <td>
      <input value="P" type="radio" name="d[{LINE_NUM}_{COL_NUM}_{CHECK_ID}]" id="pass{LINE_NUM}_{COL_NUM}_{CHECK_ID}" {PASS_CHECKED} />
      <label for="pass{LINE_NUM}_{COL_NUM}_{CHECK_ID}">{DECISION_PASS}</label>
   </td>
  </tr>
  <tr>
    <td>
	  <input value="F" type="radio" name="d[{LINE_NUM}_{COL_NUM}_{CHECK_ID}]" id="fail{LINE_NUM}_{COL_NUM}_{CHECK_ID}" {FAIL_CHECKED} />
      <label for="fail{LINE_NUM}_{COL_NUM}_{CHECK_ID}">{DECISION_FAIL}</label>
    </td>
  </tr>
  <tr>
    <td>
	  <input value="N" type="radio" name="d[{LINE_NUM}_{COL_NUM}_{CHECK_ID}]" id="nodecision{LINE_NUM}_{COL_NUM}_{CHECK_ID}" {NODECISION_CHECKED} />
      <label for="nodecision{LINE_NUM}_{COL_NUM}_{CHECK_ID}">{DECISION_NO}</label>
    </td>
  </tr>
</table>
';

	var $html_decision_made = 
'<table class="form-data">
  <tr>
    <th align="left">{LABEL_QUESTION}:</th>
    <td>{QUESTION}</td>
  </tr>
  <tr>
    <th align="left">{LABEL_DECISION}:</th>
    <td>{DECISION}</td>
  </tr>
  <tr>
    <th align="left">{LABEL_DATE}:</th>
    <td>{DATE}</td>
  </tr>
  {REVERSE_DECISION}
</table>
';

	var $html_reverse_decision = 
'  <tr>
    <td colspan="2">
	  <input value="{LABEL_REVERSE_DECISION}" type="submit" name="reverse[{LINE_NUM}_{COL_NUM}_{CHECK_ID}]" />
    </td>
  </tr>
';
	
	var $html_source = 
'	<ol class="source">
{SOURCE_CONTENT}
	</ol>
';
	
	var $html_source_line =
'		<li id="line-{LINE_ID}">{LINE}</li>
';
	
	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: html
	*/
	function HTMLRpt($errors, $user_link_id = '')
	{
		// run parent constructor
		parent::AccessibilityRpt($errors, $user_link_id);
		
		$this->num_of_no_decisions = 0;
		$this->num_of_made_decisions = 0;
		
		$this->num_of_likely_problems_fail = 0;
		$this->num_of_potential_problems_fail = 0;
	}
	
	/**
	* public
	* main process to generate report in html format
	*/
	public function generateRpt()
	{
		global $msg;

		// user_link_id must be given to show decision section
		if ((!isset($this->user_link_id) || $this->user_link_id == '') && $this->allow_set_decision == 'true')
		{
			$msg->addError('NONE_USER_LINK');
			return false;
		}
		
		// initialize each section
		$this->rpt_errors = "<ul>\n";
		$this->rpt_likely_problems = "<ul>\n";
		$this->rpt_potential_problems = "<ul>\n";
		
		$checksDAO = new ChecksDAO();
		// generate section details
		foreach ($this->errors as $error)
		{
			$row = $checksDAO->getCheckByID($error["check_id"]);
			if ($row["confidence"] == KNOWN )
			{ // no decision to make on known problems
				$this->num_of_errors++;
				
				$this->rpt_errors .= $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $error["image"], $error["image_alt"], $error["css_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_ERROR);
			}
			else if ($row["confidence"] == LIKELY )
			{
				$this->num_of_likely_problems++;
				if ($this->allow_set_decision == 'false' && !($this->from_referer == 'true' && $this->user_link_id > 0))
				{
					$this->rpt_likely_problems .= $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $error["image"], $error["image_alt"], $error["css_code"],_AC($row["err"]), _AC($row["how_to_repair"]), '', IS_WARNING);
					$this->num_of_likely_problems_fail++;
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"],$error['image'], $error["image_alt"], IS_WARNING);
				}
			}
			else if ($row["confidence"] == POTENTIAL )
			{
				$this->num_of_potential_problems++;
				if ($this->allow_set_decision == 'false' && !($this->from_referer == 'true' && $this->user_link_id > 0))
				{
					$this->rpt_potential_problems .= $this->generate_problem_section($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $error["image"], $error["image_alt"],$error["css_code"], _AC($row["err"]), _AC($row["how_to_repair"]), '', IS_INFO);
					$this->num_of_potential_problems_fail++;
				}
				else
				{
					$this->generate_cell_with_decision($row, $error["line_number"], $error["col_number"], $error["html_code"],$error['image'], $error["image_alt"], IS_INFO);
				}
			}
		}
		
		if ($this->allow_set_decision == 'true' || 
		    ($this->allow_set_decision == 'false' && $this->from_referer == 'true' && $this->user_link_id > 0))
		{
			$this->rpt_likely_problems .= $this->rpt_likely_decision_not_made.$this->rpt_likely_decision_made;
			$this->rpt_potential_problems .= $this->rpt_potential_decision_not_made.$this->rpt_potential_decision_made;
		}
		
		$this->rpt_errors .= "</ul>";
		$this->rpt_likely_problems .= "</ul>";
		$this->rpt_potential_problems .= "</ul>";
		
		if ($this->show_source == 'true')
		{
			$this->generateSourceRpt();
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
	private function generate_cell_with_decision($check_row, $line_number, $col_number, $html_code, $image, $image_alt, $error_type)
	{
		$css_code = "";
		
		// generate decision section
		$userDecisionsDAO = new UserDecisionsDAO();
		$row = $userDecisionsDAO->getByUserLinkIDAndLineNumAndColNumAndCheckID($this->user_link_id, $line_number, $col_number, $check_row['check_id']);
		
		if (!$row || $row['decision'] == AC_DECISION_FAIL) { // no decision or decision of fail
			if ($error_type == IS_WARNING) $this->num_of_likely_problems_fail++;
			if ($error_type == IS_INFO) $this->num_of_potential_problems_fail++;
		}
		
		if (!$row) // no decision
		{
			if ($this->allow_set_decision == 'true')
			{
				$decision_section = str_replace(array("{LINE_NUM}",
				                                      "{COL_NUM}",
				                                      "{CHECK_ID}",
				                                      "{PASS_CHECKED}", 
				                                      "{FAIL_CHECKED}", 
				                                      "{NODECISION_CHECKED}", 
				                                      "{QUESTION}", 
				                                      "{DECISION_PASS}", 
				                                      "{DECISION_FAIL}", 
				                                      "{DECISION_NO}"),
				                                array($line_number,
				                                      $col_number,
				                                      $check_row['check_id'],
				                                      "",
				                                      "",
				                                      'checked="checked"',
				                                      _AC($check_row['question']),
				                                      _AC($check_row['decision_pass']),
				                                      _AC($check_row['decision_fail']),
				                                      _AC('no_decision')),
				                                $this->html_decision_not_made);
			}                                
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, $css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			if ($error_type == IS_WARNING) $this->rpt_likely_decision_not_made .= $problem_section;
			if ($error_type == IS_INFO) $this->rpt_potential_decision_not_made .= $problem_section;
			
			$this->num_of_no_decisions++;
		}
		else
		{
			if ($row['decision'] == AC_DECISION_PASS) $decision = $check_row['decision_pass'];
			if ($row['decision'] == AC_DECISION_FAIL) $decision = $check_row['decision_fail'];
			
			if ($this->allow_set_decision == 'true')
			{
				$reverse_decision = str_replace(array("{LABEL_REVERSE_DECISION}", 
				                                      "{LINE_NUM}",
				                                      "{COL_NUM}",
				                                      "{CHECK_ID}"),
				                                array(_AC('reverse_decision'), 
				                                      $line_number,
				                                      $col_number,
				                                      $check_row['check_id']),
				                                $this->html_reverse_decision);
			}
			                           
			$decision_section = str_replace(array("{LABEL_DECISION}", 
			                                      "{QUESTION}", 
			                                      "{DECISION}", 
			                                      "{LABEL_QUESTION}",
			                                      "{LABEL_USER}", 
			                                      "{LABEL_DATE}", 
			                                      "{DATE}",
			                                      "{REVERSE_DECISION}"),
			                                 array(_AC('decision'),
			                                       _AC($check_row['question']),
			                                       _AC($decision),
			                                       _AC('question'),
			                                       _AC('user'),
			                                       _AC('date'),
			                                       $row['last_update'],
			                                       $reverse_decision),
			                                 $this->html_decision_made);
			
			// generate problem section
			$problem_section = $this->generate_problem_section($check_row['check_id'], $line_number, $col_number, $html_code, $image, $image_alt, $css_code, _AC($check_row['err']), _AC($check_row['how_to_repair']), $decision_section, $error_type);
			
			if ($error_type == IS_WARNING) $this->rpt_likely_decision_made .= $problem_section;
			if ($error_type == IS_INFO) $this->rpt_potential_decision_made .= $problem_section;
			
			$this->num_of_made_decisions++;
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
	private function generate_problem_section($check_id, $line_number, $col_number, $html_code, $image, $image_alt, $css_code, $error, $repair, $decision, $error_type)
	{
		$html_image = "";
		$html_repair = "";
		
		if ($this->show_source == 'true')
		{
			$line_number = '<a href="checker/index.php#line-'.$line_number.'">'.$line_number.'</a>';
		}
		
		if ($error_type == IS_ERROR)
		{
			$msg_type = "msg_err";
			$img_type = _AC('error');
			$img_src = "error.png";
		}
		else if ($error_type == IS_WARNING)
		{
			$msg_type = "msg_info";
			$img_type = _AC('warning');
			$img_src = "warning.png";
		}
		else if ($error_type == IS_INFO)
		{
			$msg_type = "msg_info";
			$img_type = _AC('manual_check');
			$img_src = "info.png";
		}
		
		
		// only display first 100 chars of $html_code
		if (strlen($html_code) > 100)
		$html_code = substr($html_code, 0, 100) . " ...";
			
		// generate repair string
		if ($repair <> '') {
			$html_repair = str_replace(array('{REPAIR_LABEL}', '{REPAIR_DETAIL}'), 
			                           array(_AC("repair"), $repair), $this->html_repair);
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
			
			$html_image = str_replace(array("{SRC}", "{HEIGHT}", "{ALT}"), array($image, $height, $alt), $this->html_image);
		}
		
		return str_replace(array("{MSG_TYPE}", 
		                         "{IMG_SRC}", 
		                         "{IMG_TYPE}", 
		                         "{LINE_TEXT}", 
		                         "{LINE_NUMBER}", 
		                         "{COL_TEXT}", 
		                         "{COL_NUMBER}", 
		                         "{HTML_CODE}",
		                         "{CSS_CODE}", 
		                         "{ERROR}", 
		                         "{BASE_HREF}", 
		                         "{CHECK_ID}", 
		                         "{TITLE}",
		                         "{IMAGE}",
		                         "{REPAIR}",
		                         "{DECISION}"),
		                   array($msg_type, 
		                         $img_src, 
		                         $img_type,
		                         _AC('line'), 
		                         $line_number, 
		                         _AC('column'),
		                         $col_number, 
		                         htmlentities($html_code, ENT_COMPAT, 'UTF-8'),
		                         $css_code, 
		                         $error, 
		                         AC_BASE_HREF, 
		                         $check_id, 
		                         _AC("suggest_improvements"),
		                         $html_image,
		                         $html_repair,
		                         $decision),
		                   $this->html_problem_achecker);

                   
	}
	
	
	// generate $this->rpt_source
	public function generateSourceRpt()
	{
		if (count($this->source_array) == 0) return;
		
		$line_num = 1;
		foreach ($this->source_array as $line)
		{
			$source_content .= str_replace(array("{LINE_ID}","{LINE}"), 
			                               array($line_num, htmlspecialchars($line)), 
			                               $this->html_source_line);
			$line_num++;
		}
		
		$this->rpt_source = str_replace("{SOURCE_CONTENT}", $source_content, $this->html_source);
	}
	
	/**
	* public 
	* return number of likely/potential errors that decision have not been made
	*/
	public function getNumOfNoDecisions()
	{
		return $this->num_of_no_decisions;
	}

	/**
	* public 
	* return number of likely errors that decision have not been made or have fail decision
	*/
	public function getNumOfLikelyWithFailDecisions()
	{
		return $this->num_of_likely_problems_fail;
	}
	
	/**
	* public 
	* return number of potential errors that decision have not been made or have fail decision
	*/
	public function getNumOfPotentialWithFailDecisions()
	{
		return $this->num_of_potential_problems_fail;
	}
	
	/** 
	* public
	* return error report in html
	* parameters: $errors: errors array
	* author: Cindy Qi Li
	*/
	public static function generateErrorRpt($errors)
	{
		// html error template
		$html_error = 
'<div id="error">
	<h4>{ERROR_MSG_TITLE}</h4>
	{ERROR_DETAIL}
</div>';
	
		$html_error_detail = 
'		<ul>
			<li>{ERROR}</li>
		</ul>
';
		if (!is_array($errors)) return false;
		
		foreach ($errors as $err)
		{
			$error_detail .= str_replace("{ERROR}", _AC($err), $html_error_detail);
		}
			
		return str_replace(array('{ERROR_MSG_TITLE}', '{ERROR_DETAIL}'), 
		                   array(_AC('the_follow_errors_occurred'), $error_detail),
		                   $html_error);
	}
	
	/** 
	* public
	* return success in html
	* parameters: none
	* author: Cindy Qi Li
	*/
	public static function generateSuccessRpt()
	{
		$html_success = 
'<div id="success">Success</div>';
		
		return $html_success;
	}
}
?>
