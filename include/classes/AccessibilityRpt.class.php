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
// $Id$

/**
* AccessibilityRpt
* Base Class for outputing accessibility validation report
* This class returns accessibility validation report
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");

define(IS_ERROR, 1);
define(IS_WARNING, 2);
define(IS_INFO, 3);

class AccessibilityRpt {

	// all private
	var $errors;                         // an array, output of AccessibilityValidator -> getValidationErrorRpt
	var $user_link_id;                   // user_links.user_link_id; default to ''
	var $allow_set_decision;                  // 'true' or 'false'. default to 'false'. show decision choices or not.
	var $from_referer;                   // 'true' or 'false'. default to 'false'. indicate the request is from referer or not.
	                                     // if from referer and user_link_id is set but user is not login, only display the choice and not allow to make decision
	var $show_source;                    // 'true' or 'false'. default to 'false'. if 'true', wrap line number in <a> to jump to the source line.
	var $source_array;                   // the array that source content. Each element of the array corresponds to a line in the file

	var $num_of_errors;                  // Number of known errors. (db: checks.confidence = "Known")
	var $num_of_likely_problems;         // Number of likely errors. (db: checks.confidence = "Likely")
	var $num_of_potential_problems;      // Number of potential errors. (db: checks.confidence = "Potential")

	var $num_of_no_decisions;            // Number of likely/potential errors that decisions have not been made
	var $num_of_made_decisions;            // Number of likely/potential errors that decisions have been made

	var $rpt_errors;                     // <DIV> section of errors
	var $rpt_likely_problems;            // <DIV> section of likely problems
	var $rpt_potential_problems;         // <DIV> section of potential problems
	var $rpt_source;                     // <DIV> section of source code used for validation

	/**
	* public
	* $errors: an array, output of AccessibilityValidator -> getValidationErrorRpt
	* $type: html
	*/
	function __construct($errors, $user_link_id = '')
	{
		$this->errors = $errors;
		$this->user_link_id = $user_link_id;
		$this->allow_set_decision = 'false';           // set default "show decision choices" to false
		$this->from_referer = 'false';           // set default "from referer" to false
		$this->show_source = 'false';             // set default "show source" to false
		$this->source_array = array();

		$this->num_of_errors = 0;
		$this->num_of_likely_problems = 0;
		$this->num_of_potential_problems = 0;

		$this->rpt_errors = "";
		$this->rpt_likely_problems = "";
		$this->rpt_potential_problems = "";
	}

	/**
	* public
	* set flag "show decision"
	*/
	public function setAllowSetDecisions($allowSetDecisions)
	{
		// set default to 'false'
		if ($allowSetDecisions <> 'true' && $allowSetDecisions <> 'false')
			$allowSetDecisions = 'false';

		$this->allow_set_decision = $allowSetDecisions;
	}

	/**
	* public
	* set flag "from referer"
	*/
	public function setFromReferer($fromReferer)
	{
		// set default to 'false'
		if ($fromReferer <> 'true' && $fromReferer <> 'false')
			$fromReferer = 'false';

		$this->from_referer = $fromReferer;
	}

	/**
	* public
	* set flag "show source"
	*/
	public function setShowSource($showSource, $sourceArray)
	{
		// set default to 'false'
		if ($showSource <> 'true' && $showSource <> 'false')
			$showSource = 'false';

		$this->show_source = $showSource;
		$this->source_array = $sourceArray;
	}

	/**
	* public
	* return user link id
	*/
	public function getUserLinkID()
	{
		return $this->user_link_id;
	}

	/**
	* public
	* return flag "show decisions"
	*/
	public function getAllowSetDecisions()
	{
		return $this->allow_set_decision;
	}

	/**
	* public
	* return flag "from referer"
	*/
	public function getFromReferer()
	{
		return $this->from_referer;
	}

	/**
	* public
	* return flag "show source"
	*/
	public function getShowSource()
	{
		return $this->show_source;
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

	/**
	* public
	* return number of known errors
	*/
	public function getSourceRpt()
	{
		return $this->rpt_source;
	}
}
?>
