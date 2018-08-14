<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id:

/**
* acheckerHTML
* Class to generate error report in HTML file
* for each of types: known, likely, potential, html, css and all selected 
* @access	public
* @author	Casian Olga
*/
if (!defined("AC_INCLUDE_PATH")) exit;
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/HTMLRpt.class.php');

//html_validation
//css_validation

class acheckerHTML {

	// all private
	// arrays that contain all data about errors of specific type
	var $known = array();
	var $likely = array();
	var $potential = array();
	var $html = '';
	var $css = '';
	
	// numbers of errors to display for each problem type
	var $error_nr_known = 0;
	var $error_nr_likely = 0;
	var $error_nr_potential = 0;
	var $error_nr_html = 0;
	var $error_nr_css = 0;
	
	// css and html error messages 
	// css validator is only available at validating url, not at validating a uploaded file or pasted html
	var $css_error = '';
	var $html_error = '';
	
	var $htmlRpt;					// instance of HTMLRpt. Generate error detail      
	var $numOfNoDecision;          	// number of problems with choice "no decision"             
	
	var $html_main =	
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Web Accessibility Checker report</title>
<meta name="author" content="AChecker - Web Accessibility Checker http://www.atutor.ca/achecker/" /> 
	
<style type="text/css">
ul {font-family: Arial; margin-bottom: 0px; margin-top: 0px; margin-right: 0px;}
li.msg_err, li.msg_info { font-family: Arial; margin-bottom: 20px;list-style: none;}
span.msg{font-family: Arial; line-height: 150%;}
code.input { margin-bottom: 2ex; background-color: #F8F8F8; line-height: 130%;}
span.err_type{ padding: .1em .5em; font-size: smaller;}
span.info_msg { line-height: 100%; color: blue; padding: 1em 1em; font-size: large; font-weight: bold;}
span.congrats_msg { line-height: 120%; color: green; padding: 1em 1em; font-size: large; font-weight: bold; white-space: nowrap;}
</style>
</head>
<body>
<p>
<strong>Result: </strong>
{SUMMARY}
<strong><br />
{GUIDELINE}
</p>
{DETAIL}
{VALIDATION}
</body></html>';

	var $html_summary = 
'<span style="background-color: {COLOR}; border: solid green; padding-right: 1em; padding-left: 1em">{SUMMARY}</span>&nbsp;&nbsp;
<span style="color:red">{SUMMARY_DETAIL}</span>';
	
	var $html_summary_validation = 
'<span style="color:red">{SUMMARY_DETAIL}</span>';
	
	var $html_a = 
'<a title="{TITLE}" target="_new" href="{HREF}">{TITLE}</a>';

	var $html_detail = 
'<h4>{DETAIL_TITLE}</h4>
<div id="{DIV_ID}" style="margin-top:1em">
	{DETAIL}
</div>';
		
	
	/**
	* public
	* error strings and numbers setter
	* @param
	* $known, $likely, $potential: strings that contain errors of specific type
	* $html, $css: strings of validation errors
	* $error_nr_known, $error_nr_likely, $error_nr_potential: nr of errors
	* $error_nr_html, $error_nr_css: nr of errors
	* $css_error: empty if css validation was required with URL input, otherwise string with error msg
	*/
	function __construct($known, $likely, $potential, $html, $css, 
		$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error, $html_error)
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
		$this->html_error = $html_error;
	}
	
	/**
	* public
	* main process of creating file
	* @param
	* $problem: problem type on which to create report (can be: known, likely, potential, html, css or all)
	* $_gids: array of guidelines that were used as testing criteria
	*/
	public function	getHTMLfile($problem, $_gids, $errors, $user_link_id) 
	{
		// set filename
		$date = AC_date('%Y-%m-%d');
		$time = AC_date('%H-%i-%s');
		$filename = 'achecker_'.$date.'_'.$time;

		$detail = '<h3>'._AC("accessibility_review").'</h3>'."\n";
	
		if ($problem != 'html' && $problem != 'css') {
			$this->htmlRpt = new HTMLRpt($errors, $user_link_id);
			if ($user_link_id != '') $this->htmlRpt->setAllowSetDecisions('true');
			else $this->htmlRpt->setAllowSetDecisions('false');
			$this->htmlRpt->generateRpt();
			$this->numOfNoDecision = $this->htmlRpt->getNumOfNoDecisions();
		}
		
		$validation = '';

		if ($problem == 'all') {
			$detail .= $this->getResultSection('known');
			$detail .= '<br/>'.$this->getResultSection('likely');
			$detail .= '<br/>'.$this->getResultSection('potential');
			if ($this->error_nr_html != -1) $validation .= '<br/>'.$this->getHTML();
			if ($this->error_nr_css != -1) $validation .= '<br/>'.$this->getCSS();
		} else if ($problem == 'html') {
			$validation .= $this->getHTML();
		} else if ($problem == 'css') {
			$validation .= $this->getCSS();
		} else {
			$detail .= $this->getResultSection($problem);
		}	

		$file_content = str_replace(array( '{SUMMARY}', 
		                                   '{GUIDELINE}',
		                                   '{DETAIL}', 
										   '{VALIDATION}'),
			                        array( $this->getSummaryStr($problem),
			                               $this->getGuidelineStr($_gids, $problem),
			                               $detail,
			                               $validation),
			                        $this->html_main);                        
			                        
		$path = AC_EXPORT_RPT_DIR.$filename.'.html';  
		$handle = fopen($path, 'w');	
		fwrite($handle, $file_content); 
		fclose($handle);
		
		return $path;		
	}
	
	/**
	* get summary string used to replace $html_main.{SUMMARY}
	*/
	private function getSummaryStr($problem)
	{
		// generate $summary and $color
		if ($this->error_nr_known > 0) {
			$summary = _AC('fail');
			$color = 'red';
		} else if ($this->error_nr_likely + $this->error_nr_potential > 0) {
			$summary = _AC('conditional_pass');
			$color = 'yellow';
		} else {
			$summary = _AC('pass');
			$color = 'green';
		}

		// generate $summary_detail
		$summary_detail = '<span style="font-weight: bold;">';
		if ($problem == 'known') $summary_detail .= $this->error_nr_known. ' ' ._AC('errors').'&nbsp;&nbsp;';
		else if ($problem == 'likely') $summary_detail .= $this->error_nr_likely.' '._AC('likely_problems').'&nbsp;&nbsp;';
		else if ($problem == 'potential') $summary_detail .= $this->error_nr_potential.' '._AC('potential_problems').'&nbsp;&nbsp;';
		else if ($problem == 'html') {
			if ($this->error_nr_html != -1) $summary_detail .= $this->error_nr_html.' '._AC('html_validation_result').'&nbsp;&nbsp;';
		}
		else if ($problem == 'css') {
			if ($this->error_nr_css != -1) $summary_detail .= $this->error_nr_css.' '._AC('css_validation_result').'&nbsp;&nbsp;';
		}
		else if ($problem == 'all'){
			$summary_detail .= $this->error_nr_known. ' ' ._AC('errors').'&nbsp;&nbsp;';
			$summary_detail .= $this->error_nr_likely.' '._AC('likely_problems').'&nbsp;&nbsp;';
			$summary_detail .= $this->error_nr_potential.' '._AC('potential_problems').'&nbsp;&nbsp;';
			if ($this->error_nr_html != -1) $summary_detail .= $this->error_nr_html.' '._AC('html_validation_result').'&nbsp;&nbsp;';
			if ($this->error_nr_css != -1) $summary_detail .= $this->error_nr_css.' '._AC('css_validation_result').'&nbsp;&nbsp;';
		}
		$summary_detail .= '</span>';
	
		if ($problem == 'html' || $problem == 'css') {
			return str_replace('{SUMMARY_DETAIL}', $summary_detail, $this->html_summary_validation);
		} else {
			return str_replace(array('{COLOR}', '{SUMMARY}', '{SUMMARY_DETAIL}'),
		                   array($color, $summary, $summary_detail),
		                   $this->html_summary);
		}
	}
	
	/**
	* get guideline string used to replace $html_main.{GUIDELINE}
	*/
	private function getGuidelineStr($_gids, $problem)
	{	
		$guidelineStr = '';	
		if ($problem == 'html' || $problem == 'css') {
			// do not show if validation data only required
			return $guidelineStr;
		} else {
			$guidelinesDAO = new GuidelinesDAO();
			$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_gids);
			if (is_array($guideline_rows)) {
				foreach ($guideline_rows as $id => $row) {
					$guidelineStr .= '<strong>Guides: </strong>'.str_replace(array('{TITLE}','{HREF}'),
					                             array($row['title']._AC('link_open_in_new'), AC_BASE_HREF.'guideline/view_guideline.php?id='.$row['guideline_id']),
					                             $this->html_a). "&nbsp;&nbsp;";
				}
			}
			return $guidelineStr;
		}
	}
	
	/**
	* private
	* writes result section for 1 problem type
	* @param
	* $problem_type: known, potential or likely; corresponding array in class should be set before calling
	* return result section as HTML string
	*/
	private function getResultSection($problem_type) 
	{
	    $congrats = "";
	    
		if ($problem_type == 'known') {
			if ($this->error_nr_known == 0) {
				$content = "<ul><li class='msg_info'><span id='AC_congrats_msg_for_errors' class='congrats_msg'>
						<img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_no_known")."
					</span></ul></li>";
				return str_replace(array('{DETAIL_TITLE}', '{DIV_ID}', '{DETAIL}'),
			              		array(_AC('errors'), 'errors', $content),
			           		  	$this->html_detail);
			} else {  // Since known problems cannot be passed, only grab its report at $this->error_nr_known > 0
				return str_replace(array('{DETAIL_TITLE}', '{DIV_ID}', '{DETAIL}'),
			              		array(_AC('errors'), 'errors', $this->htmlRpt->getErrorRpt()),
			           		  	$this->html_detail);
			}
			
		} else if ($problem_type == 'likely') {
		    if ($this->error_nr_likely == 0) {
				$congrats = "<ul><li class='msg_info'><span id='AC_congrats_msg_for_likely' class='congrats_msg'>
						<img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  "._AC("congrats_no_likely")."
					</span></ul></li>";
			}

			// Print out the likely problem report, which includes all the likely problems regardless of the user's decision
			$likely = $this->htmlRpt->getLikelyProblemRpt();
			$pat = '/\<input value="Reverse Decision" type="submit" name="reverse\[(.*)\]" \/\>/';
			if (preg_match($pat, $likely)) {
			    $likely = preg_replace($pat, "", $likely);
			}

			return str_replace(array('{DETAIL_TITLE}', '{DIV_ID}', '{DETAIL}'),
		            		array(_AC('likely_problems'), 'likely_problems', $congrats . $likely),
		               		$this->html_detail);
				
		} else if ($problem_type == 'potential') {
			if ($this->error_nr_potential == 0) {
				$congrats = "<ul><li class='msg_info'><span id='AC_congrats_msg_for_potential' class='congrats_msg'>
						<img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  "._AC("congrats_no_potential")."
					</span></ul></li>";
			}
			
			$potential = $this->htmlRpt->getPotentialProblemRpt();
			$pat = '/\<input value="Reverse Decision" type="submit" name="reverse\[(.*)\]" \/\>/';
			if (preg_match($pat, $potential)) {
			    $potential = preg_replace($pat, "", $potential);
			}
			
			return str_replace(array('{DETAIL_TITLE}', '{DIV_ID}', '{DETAIL}'),
		           			array(_AC('potential_problems'), 'potential_problems', $congrats . $potential),
		               		$this->html_detail);
		}
	}

	/**
	* private
	* writes report for HTML validation
	* return HTML validation result as HTML string
	*/
	private function getHTML() 
	{					
		$provided_by = '';
		
		// str with error type and nr of errors
		if ($this->error_nr_html == -1) {	
			$content = '<ol><li class="msg_err">
				<span class="info_msg">
					<img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("html_validator_disabled").'
				</span>
				</ol></li>';
		} else {				
			$provided_by = '<ol><li class="msg_err">'. _AC("html_validator_provided_by") .'</li></ol>'. "\n";
		
			// show congratulations if no errors found
			if ($this->error_nr_html == 0 && $this->html_error == '') {
				// no html validation errors, passed
				$content = "<ul><li class='msg_info'>
				<span class='congrats_msg'>
					<img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_html_validation") ."
				</span>
				</ul></li>";
			} else if($this->error_nr_html == 0 && $this->html_error != '') {
				// html validation errors
				$content = "<ul><li class='msg_info'>
				<span>
					". $this->html_error ."
				</span>
				</ul></li>";
			} else { // errors exist
				$content = preg_replace('/img src="([a-z]*)\//', 'img src="'.AC_BASE_HREF.'/images/', $this->html);
			}	
		}
		
		return str_replace(array('{DETAIL_TITLE}', '{DIV_ID}', '{DETAIL}'),
						array(_AC('html_validation_result'), 'html_validation', $provided_by.$content),
						$this->html_detail);
	}
	
	/**
	* private
	* writes report for CSS validation
	* return CSS validation result as HTML string
	*/
	private function getCSS() 
	{
		$provided_by = '';
		
		// str with error type and nr of errors
		if ($this->css_error == '' && $this->error_nr_css != -1) {
			$provided_by = '<ol><li class="msg_err">'. _AC("css_validator_provided_by") .'</li></ol>'. "\n";
		} else if ($this->css_error == '' && $this->error_nr_css == -1) {
			// css validator is disabled		
			$content = '<ol><li class="msg_err">
				<span class="info_msg">
					<img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("css_validator_disabled").'
				</span>
				</ol></li>';
		}
				
		if ($this->css_error != '') { // non url input
			$content = '<ol><li class="msg_err">
				<span class="info_msg">
					<img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '.$this->css_error.'
				</span>
				</ol></li>';
		} else { // ok -> show css validation result
			if ($this->error_nr_css == 0) { // no errors
				$content = "<ul><li class='msg_info'>
				<span class='congrats_msg'>
					<img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_css_validation") ."
				</span>
				</ul></li>";	
			} else { // errors exist
				$content = $this->css;
			}
		}
		
		return str_replace(array('{DETAIL_TITLE}', '{DIV_ID}', '{DETAIL}'),
						array(_AC('css_validation_result'), 'css_validation', $provided_by.$content),
						$this->html_detail);
	}
	
}
?>