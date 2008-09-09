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
* AccessibilityRpt
* Class for accessibility validate
* This class returns accessibility validation report
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AT_INCLUDE_PATH")) die("Error: AT_INCLUDE_PATH is not defined.");

define(IS_ERROR, 1);
define(IS_WARNING, 2);
define(IS_INFO, 3);

class AccessibilityRpt {

	// all private
	var $errors;                         // an array, output of AccessibilityValidator -> getValidationErrorRpt
	var $display_type;                   // how to display. defaul to "html"
	
	var $num_of_errors;                  // Number of known errors. (db: checks.confidence = "Known")
	var $num_of_likely_problems;         // Number of likely errors. (db: checks.confidence = "Likely")
	var $num_of_potential_problems;      // Number of potential errors. (db: checks.confidence = "Potential")
	
	var $rpt_errors;                     // <DIV> section of errors
	var $rpt_likely_problems;            // <DIV> section of likely problems
	var $rpt_potential_problems;         // <DIV> section of potential problems
	
	var $cell_html =
'      <li class="{MSG_TYPE}">
         <span class="err_type"><img src="images/{IMG_SRC}" alt="{IMG_TYPE}" title="{IMG_TYPE}" width="15" height="15" /></span>
         <em>Line {LINE_NUMBER}, Column {COL_NUMBER}</em>:
         <span class="msg">{ERROR}</span>
         <pre><code class="input">{HTML_CODE}</code></pre>
         <p class="helpwanted">
            <a href="javascript: popup(\'{BASE_HREF}suggestion.php?id={CHECK_ID}\')" 
               title="Suggest improvements on this error message">&#x2709;</a>
         </p>
       </li>
';

	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: html
	*/
	function AccessibilityRpt($errors, $type = "html")
	{
		$this->errors = $errors;
		$this->display_type = $type;
		
		$this->num_of_errors = 0;
		$this->num_of_likely_problems = 0;
		$this->num_of_potential_problems = 0;
		
		$this->rpt_errors = "";
		$this->rpt_likely_problems = "";
		$this->rpt_potential_problems = "";

		// Generate display based on $this->display_type
		$this->generateDisplay();
	}
	
	/**
	* private
	*/
	function generateDisplay()
	{
		global $db;
		
		// generate section details
		foreach ($this->errors as $error)
		{
			$sql = "SELECT confidence, err FROM ". TABLE_PREFIX ."checks WHERE check_id=". $error["check_id"];
			$result	= mysql_query($sql, $db) or die(mysql_error());

			while ($row = mysql_fetch_assoc($result))
			{
				if ($row["confidence"] == "Known")
				{
					$this->num_of_errors++;
					
					$this->rpt_errors .= $this->generate_cell($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $row["err"], IS_ERROR);
				}
				else if ($row["confidence"] == "Likely")
				{
					$this->num_of_likely_problems++;
					
					$this->rpt_likely_problems .= $this->generate_cell($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $row["err"], IS_WARNING);
				}
				else if ($row["confidence"] == "Potential")
				{
					$this->num_of_potential_problems++;
					
					$this->rpt_potential_problems .= $this->generate_cell($error["check_id"], $error["line_number"], $error["col_number"], $error["html_code"], $row["err"], IS_INFO);
				}
			}
		}
	}
	
	/** 
	* private
	* return check detail in html
	* parameters:
	* $line_number: line number that the error happens
	* $col_number: column number that the error happens
	* $html_tag: html tag that the error happens
	* $description: error description
	*/
	function generate_cell($check_id, $line_number, $col_number, $html_code, $error, $error_type)
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
			$html_code = substr($html_code, 0, 100) . "...";
			
		return str_replace(array("{MSG_TYPE}", "{IMG_SRC}", "{IMG_TYPE}", "{LINE_NUMBER}", "{COL_NUMBER}", "{HTML_CODE}", "{ERROR}", "{BASE_HREF}", "{CHECK_ID}"),
		                   array($msg_type, $img_src, $img_type, $line_number, $col_number, htmlentities($html_code), $error, AT_BASE_HREF, $check_id),
		                   $this->cell_html);
	}
	
	/**
	* public 
	* return validation error report in html
	*/
	function getErrorRpt()
	{
		return $this->rpt_errors;
	}

	/**
	* public 
	* return validation likely problem report in html
	*/
	function getLikelyProblemRpt()
	{
		return $this->rpt_likely_problems;
	}

	/**
	* public 
	* return validation error report in html
	*/
	function getPotentialProblemRpt()
	{
		return $this->rpt_potential_problems;
	}

	/**
	* public 
	* return number of known errors
	*/
	function getNumOfErrors()
	{
		return $this->num_of_errors;
	}
	
	/**
	* public 
	* return number of known errors
	*/
	function getNumOfLikelyProblems()
	{
		return $this->num_of_likely_problems;
	}
	
	/**
	* public 
	* return number of known errors
	*/
	function getNumOfPotentialProblems()
	{
		return $this->num_of_potential_problems;
	}
}
?>  
