<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

/**
* AccessibilityValidator
* Class for accessibility validate
* This class checks the accessibility of the given html based on requested guidelines. 
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");

include (AC_INCLUDE_PATH . "lib/simple_html_dom.php");
include_once (AC_INCLUDE_PATH . "classes/BasicChecks.class.php");
include_once (AC_INCLUDE_PATH . "classes/BasicFunctions.class.php");
include_once (AC_INCLUDE_PATH . "classes/CheckFuncUtility.class.php");
include_once (AC_INCLUDE_PATH . "classes/DAO/ChecksDAO.class.php");

define("SUCCESS_RESULT", "success");
define("FAIL_RESULT", "fail");
define("DISPLAY_PREVIEW_HTML_LENGTH", 100);

class AccessibilityValidator {

	// all private
	var $num_of_errors = 0;              // number of errors
	
	var $validate_content;               // html content to check
	var $guidelines;                     // array, guidelines to check on
	var $uri;                            // the URI that $validate_content is from, used in check image size in BasicFunctions
	
	// structure: line_number, check_id, result (success, fail)
	var $result = array();               // all check results, including success ones and failed ones
	
	var $check_for_all_elements_array = array(); // array of the to-be-checked check_ids 
	var $check_for_tag_array = array();          // array of the to-be-checked check_ids 
	var $prerequisite_check_array = array();     // array of prerequisite check_ids of the to-be-checked check_ids 
//	var $next_check_array = array();             // array of the next check_ids of the to-be-checked check_ids
	var $check_func_array = array();         // array of all the check functions 
		
	var $content_dom;                    // dom of $validate_content

	var $line_offset;                    // 1. ignore the problems on the lines before the line of $line_offset
	                                     // 2. report line_number = real_line_number - $line_offset 
	/**
	 * public
	 * $content: string, html content to check
	 * $guidelines: array, guidelines to check on
	 */
	function AccessibilityValidator($content, $guidelines, $uri = '')
	{
		$this->validate_content = $content;
		$this->guidelines = $guidelines;
		$this->line_offset = 0;
		$this->uri = $uri;
	}
	
	/* public
	 * Validation
	 */
	public function validate()
	{
		// dom of the content to be validated
		$this->content_dom = $this->get_simple_html_dom($this->validate_content);

		// prepare gobal vars used in BasicFunctions.class.php to fasten the validation
		$this->prepare_global_vars();
		
		// set arrays of check_id, prerequisite check_id, next check_id
		$this->prepare_check_arrays($this->guidelines);

		$this->validate_element($this->content_dom->find('html'));
		
		$this->finalize();

		// end of validation process
	}
	
	/** private
	 * set global vars used in BasicChecks.class.php and BasicFunctions.class.php
	 * to fasten the validation process.
	 * return nothing.
	 */
	private function prepare_global_vars()
	{
		global $header_array, $base_href;

		// find all header tags which are used in BasicFunctions.class.php
		$header_array = $this->content_dom->find("h1, h2, h3, h4, h5, h6, h7");

		// find base href, used to check image size
		$all_base_elements = $this->content_dom->find("base");

		if (is_array($all_base_elements))
		{
			foreach ($all_base_elements as $base)
			{
				if (isset($base->attr['href']))
				{
					$base_href = $base->attr['href'];
					break;
				}
			}
		}

		// set all check functions
		$checksDAO = new ChecksDAO();
		$rows = $checksDAO->getAllOpenChecks();
		
		if (is_array($rows))
		{
			foreach ($rows as $row)
				$this->check_func_array[$row['check_id']] = CheckFuncUtility::convertCode($row['func']);
		}
	}
	
	/** private
	 * return a simple_html_dom on the given content.
	 * Because accessibility check is based on the root html element <html>,
	 * check if dom has html tag <html>, if no, add it and the end tag to the content
	 * and return the dom on modified content.
	 */
	private function get_simple_html_dom($content)
	{
		$dom = str_get_dom($content);
		
		if (count($dom->find('html')) == 0)
		{
			$dom = str_get_dom("<html>\n".$content."\n</html>");
			$this->line_offset += 1;
		}
			
		return $dom;
	}
	
	/**
	 * private
	 * generate arrays of check ids, prerequisite check ids, next check ids
	 * array structure:
	 check_array
	 (
	 [html_tag] => Array
	 (
	 [0] => check_id 1
	 [1] => check_id 2
	 ...
	 )
	 ...
	 )

	 prerequisite_check_array
	 (
	 [check_id] => Array
	 (
	 [0] => prerequisite_check_id 1
	 [1] => prerequisite_check_id 2
	 ...
	 )
	 ...
	 )

//	 next_check_array
//	 (
//	 [check_id] => Array
//	 (
//	 [0] => next_check_id 1
//	 [1] => next_check_id 2
//	 ...
//	 )
	 ...
	 )
	 */
	private function prepare_check_arrays($guidelines)
	{
		
		if (!($guideline_query = $this->convert_array_to_string($guidelines, ',')))
			return false;
		// validation process
		else  
		{
			$checksDAO = new ChecksDAO();
			
			// generate array of "all element"
			$rows = $checksDAO->getOpenChecksForAllByGuidelineIDs($guideline_query);
			
			$count = 0;
			if (is_array($rows))
			{
				foreach ($rows as $id => $row)
					$this->check_for_all_elements_array[$count++] = $row["check_id"];
			}
			
			// generate array of check_id
			$rows = $checksDAO->getOpenChecksNotForAllByGuidelineIDs($guideline_query);

			if (is_array($rows))
			{
				foreach ($rows as $id => $row)
				{
					if ($row["html_tag"] <> $prev_html_tag && $prev_html_tag <> "") $count = 0;
					
					$this->check_for_tag_array[$row["html_tag"]][$count++] = $row["check_id"];
					
					$prev_html_tag = $row["html_tag"];
				}
			}
			
			// generate array of prerequisite check_ids
			
			$rows = $checksDAO->getOpenPreChecksByGuidelineIDs($guideline_query);

			if (is_array($rows))
			{
				foreach ($rows as $id => $row)
				{
					if ($row["check_id"] <> $prev_check_id)  $prerequisite_check_array[$row["check_id"]] = array();
					
					array_push($prerequisite_check_array[$row["check_id"]], $row["prerequisite_check_id"]);
					
					$prev_check_id = $row["check_id"];
				}
			}
			$this->prerequisite_check_array = $prerequisite_check_array;

			// generate array of next check_ids
//			$rows = $checksDAO->getOpenNextChecksByGuidelineIDs($guideline_query);
//
//			if (is_array($rows))
//			{
//				foreach ($rows as $id => $row)
//				{
//					if ($row["check_id"] <> $prev_check_id)  $next_check_array[$row["check_id"]] = array();
//					
//					array_push($next_check_array[$row["check_id"]], $row["next_check_id"]);
//					
//					$prev_check_id = $row["check_id"];
//				}
//			}
//			$this->next_check_array = $next_check_array;
//			debug($this->next_check_array);
			return true;
		}
	}

	/**
	 * private
	 * Recursive function to validate html elements
	 */
	private function validate_element($element_array)
	{
		foreach($element_array as $e)
		{
			// generate array of checks for the html tag of this element
			if (is_array($this->check_for_tag_array[$e->tag]))
				$check_array[$e->tag] = array_merge($this->check_for_tag_array[$e->tag], $this->check_for_all_elements_array);
			else
				$check_array[$e->tag] = $this->check_for_all_elements_array;
				
			foreach ($check_array[$e->tag] as $check_id)
			{
				// check prerequisite ids first, if fails, report failure and don't need to proceed with $check_id
				$prerequisite_failed = false;
//debug($check_id);
//debug($this->prerequisite_check_array[$check_id]);
//debug($this->next_check_array[$check_id]);
				if (is_array($this->prerequisite_check_array[$check_id]))
				{
					foreach ($this->prerequisite_check_array[$check_id] as $prerequisite_check_id)
					{
						$check_result = $this->check($e, $prerequisite_check_id);
						
						if ($check_result == FAIL_RESULT)
						{
							$prerequisite_failed = true;
							break;
						}
					}
				}

				// if prerequisite check passes, proceed with current check_id
				if (!$prerequisite_failed)
				{
					$check_result = $this->check($e, $check_id);
					
					// if check_id passes, proceed with next checks
//					if ($check_result == SUCCESS_RESULT)
//					{
//						if (is_array($this->next_check_array[$check_id]))
//							foreach ($this->next_check_array[$check_id] as $next_check_id)
//							{
//								$this->check($e, $next_check_id);
//							}
//					}
				}
			}
			
			$this->validate_element($e->children());
		}
	}

	/**
	 * private
	 * check given html dom node for given check_id, save result into $this->result
	 * parameters:
	 * $e: simple html dom node
	 * $check_id: check id
	 *
	 * return "success" or "fail"
	 */
	private function check($e, $check_id)
	{
		global $msg, $base_href;
		
		// don't check the lines before $line_offset
		if ($e->linenumber <= $this->line_offset) return;
		
		$result = $this->get_check_result($e->linenumber-$this->line_offset, $e->colnumber, $check_id);

		// has not been checked
		if (!$result)
		{
			// run function for $check_id
			$check_result = eval($this->check_func_array[$check_id]);
			
			$checksDAO = new ChecksDAO();
			$row = $checksDAO->getCheckByID($check_id);
			if (is_null($check_result))
			{ // when $check_result is not true/false, must be something wrong with the check function.
			  // show warning message and skip this check
				$msg->addError(array('CHECK_FUNC', $row['html_tag'].': '._AC($row['name'])));
				
				// skip this check
				$check_result = true;
			}
			
			if ($check_result)  // success
			{
				$result = SUCCESS_RESULT;
			}
			else
			{
				$result = FAIL_RESULT;
			}

			// minus out the $line_offset from $linenumber 
			if ($result == FAIL_RESULT)
			{
				// find out checked html code
				// http://www.atutor.ca/atutor/mantis/view.php?id=3768
				// http://www.atutor.ca/atutor/mantis/view.php?id=3797
				// Display not only the start tag, but a substring from start tag to end tag.
				// Displaying checked html is in HTMLRpt.class.php
				if (strlen($e->outertext) > DISPLAY_PREVIEW_HTML_LENGTH) 
					$html_code = substr($e->outertext, 0, DISPLAY_PREVIEW_HTML_LENGTH) . " ...";
				else 
					$html_code = $e->outertext;
//				else
//					$html_code = substr($e->outertext, 0, strpos($e->outertext, '>')+1);

				// find out preview images for validation on <img>
				if (strtolower(trim($row['html_tag'])) == 'img')
				{
					$image = BasicChecks::getFile($e->attr['src'], $base_href, $this->uri);
				    $handle = @fopen($image, 'r');
				
				    if (!$handle) $image = '';
				    else @fclose($handle);
				    
				    // find out image alt text for preview image
				    if (!isset($e->attr['alt'])) $image_alt = '_NOT_DEFINED';
				    else if ($e->attr['alt'] == '') $image_alt = '_EMPTY';
				    else $image_alt = $e->attr['alt'];
				}
				
				$this->save_result($e->linenumber-$this->line_offset, $e->colnumber, $html_code, $check_id, $result, $image, $image_alt);
			}
		}
		
		return $result;
	}
	
	/**
	 * private
	 * get check result from $result. Return false if the result is not found.
	 * Parameters:
	 * $line_number: line number in the content for this check
	 * $check_id: check id
	 */
	private function get_check_result($line_number, $col_number, $check_id)
	{
		foreach($this->result as $one_result)
		{
			if ($one_result["line_number"] == $line_number && $one_result["col_number"] == $col_number && $one_result["check_id"] == $check_id)
				return $one_result["result"];
		}
		
		return false;
	}

	/**
	 * private
	 * save each check result
	 * Parameters:
	 * $line_number: line number in the content for this check
	 * $check_id: check id
	 * $result: result to save
	 */
	private function save_result($line_number, $col_number, $html_code, $check_id, $result, $image, $image_alt)
	{
		array_push($this->result, array("line_number"=>$line_number, "col_number"=>$col_number, "html_code"=>$html_code, "check_id"=>$check_id, "result"=>$result, "image"=>$image, "image_alt"=>$image_alt));
		
		return true;
	}
	
	/**
	 * private
	 * convert the given array to a string of the array elements separated by the given delimiter.
	 * For example:
	 * array ([0] => 7, [1] => 8)
	 * delimiter: ,
	 * is converted to string "7, 8"
	 */
	private function convert_array_to_string($in_array, $delimiter)
	{
		$count = 0;
		if (is_array($in_array))
		{
			foreach ($in_array as $element)
			{
				if ($count == 0) $str = $element;
				else $str .= $delimiter . $element;
				
				$count++;
			}
			return $str;
		}
		else
			return false;
	}
	
	/**
	 * private 
	 * generate class value: array of error results, number of errors
	 */
	private function finalize()
	{
		$this->num_of_errors = count($this->result);
	}
	
	/**
	 * public 
	 * set line offset
	 */
	public function setLineOffset($lineOffset)
	{
		$this->line_offset = $lineOffset;
	}
	
	/**
	 * public 
	 * return line offset
	 */
	public function getLineOffset()
	{
		return $this->line_offset;
	}
	
	/**
	 * public 
	 * return array of all checks that have been done, including successful and failed ones
	 */
	public function getValidationErrorRpt()
	{
		return $this->result;
	}
	

	/**
	 * public 
	 * return number of errors
	 */
	public function getNumOfValidateError()
	{
		return $this->num_of_errors;
	}

	/**
	 * public 
	 * return array of all checks that have been done by check id, including successful and failed ones
	 */
	public function getResultsByCheckID($check_id)
	{
		$rtn = array();
		foreach ($this->result as $oneResult)
			if ($oneResult["check_id"] == $check_id)
				array_push($rtn, array("line_number"=>$oneResult["line_number"], "col_number"=>$oneResult["col_number"], "check_id"=>$oneResult["check_id"], "result"=>$oneResult["result"]));
	
		return $rtn;
	}

	/**
	 * public 
	 * return array of all checks that have been done by line number, including successful and failed ones
	 */
	public function getResultsByLine($line_number)
	{
		$rtn = array();
		foreach ($this->result as $oneResult)
			if ($oneResult["line_number"] == $line_number)
				array_push($rtn, array("line_number"=>$oneResult["line_number"], "col_number"=>$oneResult["col_number"], "check_id"=>$oneResult["check_id"], "result"=>$oneResult["result"]));
	
		return $rtn;
	}

}
?>  
