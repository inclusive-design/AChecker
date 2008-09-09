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
* AccessibilityValidator
* Class for accessibility validate
* This class checks the accessibility of the given html based on requested guidelines. 
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AT_INCLUDE_PATH")) die("Error: AT_INCLUDE_PATH is not defined.");

include (AT_INCLUDE_PATH . "lib/simple_html_dom.php");
include (AT_INCLUDE_PATH . "classes/Checks.class.php");

define("SUCCESS_RESULT", "success");
define("FAIL_RESULT", "fail");

class AccessibilityValidator {

	// all private
	var $num_of_errors = 0;              // number of errors
	
	var $validate_content;               // html content to check
	var $guidelines;                     // array, guidelines to check on
	
	// structure: line_number, check_id, result (success, fail)
	var $result = array();               // all check results, including success ones and failed ones
	var $error_result = array();         // failed check results
	
	var $check_array;                    // array of the to-be-checked check_ids 
	var $prerequisite_check_array;       // array of prerequisite check_ids of the to-be-checked check_ids 
	var $next_check_array;               // array of the next check_ids of the to-be-checked check_ids 
		
	var $content_dom;                    // dom of $validate_content

	
	/**
	* public
	* $content: string, html content to check
	* $guidelines: array, guidelines to check on
	*/
	function AccessibilityValidator($content, $guidelines)
	{
		$this->validate_content = $content;
		$this->guidelines = $guidelines;
		
		// validation process
		$this->validate();
	}
	
	// private
	function validate()
	{
		// set arrays of check_id, prerequisite check_id, next check_id
		$this->prepare_check_arrays($this->guidelines);
//		$this->check_array = array("input"=>array(211));
//		$this->prerequisite_check_array = array();
//		$this->next_check_array = array();
//		debug($this->check_array);
//		debug($this->prerequisite_check_array);
//		debug($this->next_check_array);

		// dom of the content to be validated
		$this->content_dom = $this->get_simple_html_dom($this->validate_content);

		$this->validate_element($this->content_dom->find('html'));
		
		$this->finalize();

		// end of validation process
	}
	
	/** private
	* return a simple_html_dom on the given content.
	* Because accessibility check is based on the root html element <html>,
	* check if dom has html tag <html>, if no, add it and the end tag to the content
	* and return the dom on modified content.
	*/
	function get_simple_html_dom($content)
	{
		$dom = str_get_dom($content);
		
		if (count($dom->find('html')) == 0)
			$dom = str_get_dom("<html>".$content."</html>");
			
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

	next_check_array
	(
    [check_id] => Array
        (
            [0] => next_check_id 1
            [1] => next_check_id 2
            ...
        )
    ...
  )
	*/
	function prepare_check_arrays($guidelines)
	{
		global $db;
		
		if (!($guideline_query = $this->convert_array_to_string($guidelines, ',')))
			return false;
		// validation process
		else  
		{
			// generate array of "all element"
			$sql = "select distinct gc.check_id, c.html_tag
							from ". TABLE_PREFIX ."guidelines g, 
									". TABLE_PREFIX ."guideline_groups gg, 
									". TABLE_PREFIX ."guideline_subgroups gs, 
									". TABLE_PREFIX ."subgroup_checks gc,
									". TABLE_PREFIX ."checks c
							where g.guideline_id in (".$guideline_query.")
								and g.guideline_id = gg.guideline_id
								and gg.group_id = gs.group_id
								and gs.subgroup_id = gc.subgroup_id
								and gc.check_id = c.check_id
								and c.html_tag = 'all elements'
							order by c.html_tag";
			$result	= mysql_query($sql, $db) or die(mysql_error());
			
			$check_for_all_elements_array = array();
			
			$count = 0;
			while ($row = mysql_fetch_assoc($result))
				$check_for_all_elements_array[$count++] = $row["check_id"];
			
			// generate array of check_id
			$sql = "select distinct gc.check_id, c.html_tag
							from ". TABLE_PREFIX ."guidelines g, 
									". TABLE_PREFIX ."guideline_groups gg, 
									". TABLE_PREFIX ."guideline_subgroups gs, 
									". TABLE_PREFIX ."subgroup_checks gc,
									". TABLE_PREFIX ."checks c
							where g.guideline_id in (".$guideline_query.")
								and g.guideline_id = gg.guideline_id
								and gg.group_id = gs.group_id
								and gs.subgroup_id = gc.subgroup_id
								and gc.check_id = c.check_id
								and c.html_tag <> 'all elements'
							order by c.html_tag";
			$result	= mysql_query($sql, $db) or die(mysql_error());
			
			$check_array = array();
			
			while ($row = mysql_fetch_assoc($result))
			{
				if ($row["html_tag"] <> $prev_html_tag && $prev_html_tag <> "")  
				{
					$count = 0;
					$check_array[$prev_html_tag] = array_merge($check_array[$prev_html_tag], $check_for_all_elements_array);
				}
				
				$check_array[$row["html_tag"]][$count++] = $row["check_id"];
				
				$prev_html_tag = $row["html_tag"];
			}
			// handle the last html_tag
			if ($prev_html_tag <> "")
				$check_array[$prev_html_tag] = array_merge($check_array[$prev_html_tag], $check_for_all_elements_array);
			
			$this->check_array = $check_array;
			
			// generate array of prerequisite check_ids
			$sql = "select distinct c.check_id, cp.prerequisite_check_id
						from ". TABLE_PREFIX ."guidelines g, 
						     ". TABLE_PREFIX ."guideline_groups gg, 
						     ". TABLE_PREFIX ."guideline_subgroups gs, 
						     ". TABLE_PREFIX ."subgroup_checks gc,
						     ". TABLE_PREFIX ."checks c,
						     ". TABLE_PREFIX ."check_prerequisites cp
						where g.guideline_id in (".$guideline_query.")
						  and g.guideline_id = gg.guideline_id
						  and gg.group_id = gs.group_id
						  and gs.subgroup_id = gc.subgroup_id
						  and gc.check_id = c.check_id
						  and c.check_id = cp.check_id
						order by c.check_id, cp.prerequisite_check_id";

			$result	= mysql_query($sql, $db) or die(mysql_error());
			
			$prerequisite_check_array = array();
			
			while ($row = mysql_fetch_assoc($result))
			{
				if ($row["check_id"] <> $prev_check_id)  $prerequisite_check_array[$row["check_id"]] = array();
				
				array_push($prerequisite_check_array[$row["check_id"]], $row["prerequisite_check_id"]);
				
				$prev_check_id = $row["check_id"];
			}
			$this->prerequisite_check_array = $prerequisite_check_array;
			
			// generate array of next check_ids
			$sql = "select distinct c.check_id, tp.next_check_id
								from ". TABLE_PREFIX ."guidelines g, 
								     ". TABLE_PREFIX ."guideline_groups gg, 
								     ". TABLE_PREFIX ."guideline_subgroups gs, 
								     ". TABLE_PREFIX ."subgroup_checks gc,
								     ". TABLE_PREFIX ."checks c,
								     ". TABLE_PREFIX ."test_pass tp
								where g.guideline_id in (".$guideline_query.")
								  and g.guideline_id = gg.guideline_id
								  and gg.group_id = gs.group_id
								  and gs.subgroup_id = gc.subgroup_id
								  and gc.check_id = c.check_id
								  and c.check_id = tp.check_id
								order by c.check_id, tp.next_check_id";

			$result	= mysql_query($sql, $db) or die(mysql_error());
			
			$next_check_array = array();
			
			while ($row = mysql_fetch_assoc($result))
			{
				if ($row["check_id"] <> $prev_check_id)  $next_check_array[$row["check_id"]] = array();
				
				array_push($next_check_array[$row["check_id"]], $row["next_check_id"]);
				
				$prev_check_id = $row["check_id"];
			}
			$this->next_check_array = $next_check_array;
			
			return true;
		}
	}

	/**
	* private
	* Recursive function to validate html elements
	*/
	function validate_element($element_array)
	{
		foreach($element_array as $e)
		{
			if (is_array($this->check_array[$e->tag]))
			{
				foreach ($this->check_array[$e->tag] as $check_id)
				{
					// check prerequisite ids first, if fails, report failure and don't need to proceed with $check_id
					$prerequisite_failed = false;

					if (is_array($this->prerequisite_check_array[$check_id]))
					{
						foreach ($this->prerequisite_check_array[$check_id] as $prerequisite_check_id)
						{
							$check_result = $this->check($e, $prerequisite_check_id);
							
							if ($check_result == "fail")
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
						if ($check_result == SUCCESS_RESULT)
						{
							if (is_array($this->next_check_array[$check_id]))
								foreach ($this->next_check_array[$check_id] as $next_check_id)
								{
									$this->check($e, $next_check_id);
								}
						}
					}
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
	function check($e, $check_id)
	{
		$result = $this->get_check_result($e->linenumber, $e->colnumber, $check_id);
		
		// has not been checked
		if (!$result)
		{
			// run function for $check_id
			eval("\$check_result = Checks::check_" . $check_id . "(\$e, \$this->content_dom);");
			
			if ($check_result)  // success
			{
				$result = SUCCESS_RESULT;
			}
			else
			{
				$result = FAIL_RESULT;
			}

			// find out checked html tag code
			$html_code = substr($e->outertext, 0, strpos($e->outertext, '>')+1);

			$this->save_result($e->linenumber, $e->colnumber, $html_code, $check_id, $result);
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
	function get_check_result($line_number, $col_number, $check_id)
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
	function save_result($line_number, $col_number, $html_code, $check_id, $result)
	{
		array_push($this->result, array("line_number"=>$line_number, "col_number"=>$col_number, "html_code"=>$html_code, "check_id"=>$check_id, "result"=>$result));
		
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
	function convert_array_to_string($in_array, $delimiter)
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
	* generate class value: array of error results, number of errors
	*/
	function finalize()
	{
		function errorRpt($one_result)
		{
			return ($one_result["result"] == FAIL_RESULT);
		}
	
		$this->error_result = array_filter($this->result, "errorRpt");
		$this->num_of_errors = count($this->error_result);
	}
	
	/**
	* public 
	* return validation report in html
	*/
	function getValidationFullRpt()
	{
		return $this->result;
	}

	/**
	* public 
	* return error validation report in html
	*/
	function getValidationErrorRpt()
	{
		return $this->error_result;
	}
	

	// public 
	function getNumOfValidateError()
	{
		return $this->num_of_errors;
	}

	// public 
	function getOneResult($line_number, $check_id)
	{
		$rtn = array();
		foreach ($this->result as $oneResult)
//			if ($oneResult["line_number"] == $line_number && $oneResult["check_id"] == $check_id)
//				return $oneResult["result"];
			if ($oneResult["check_id"] == $check_id)
				array_push($rtn, array("line_number"=>$oneResult["line_number"], "col_number"=>$oneResult["col_number"], "check_id"=>$oneResult["check_id"], "result"=>$oneResult["result"]));
	
		return $rtn;
	}

}
?>  
