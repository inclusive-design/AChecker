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
 * Decision
 * make or reverse user decisons
 * @access	public
 * @author	Cindy Qi Li
 * @package	Decision
 */

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");

require_once(AC_INCLUDE_PATH. 'classes/DAO/UserLinksDAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/DAO/UserDecisionsDAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/HTMLRpt.class.php');

class Decision {

	// all private
	var $userID;
	var $URI;                              // URI to make/reverse decisions on
	var $output;                           // output format: html or rest
	var $sessionID;                        // session ID sent by our server in question list form

	var $userLinkID;                       // generated in validateFields()
	var $userLinksDAO;
	var $userDecisionsDAO;

	var $errors;                           // error msg array

	/**
	 * Constructor
	 * doing nothing
	 * @access  public
	 * @param   None
	 * @author  Cindy Qi Li
	 */
	function Decision($userID, $URI, $output, $sessionID)
	{
		global $msg;

		$this->userID = $userID;
		$this->URI = urldecode($URI);
		$this->output = $output;
		$this->sessionID = $sessionID;

		$this->userLinksDAO = new UserLinksDAO();
		$this->userDecisionsDAO = new UserDecisionsDAO();

		if (!$this->validateFields()) return false;
	}

	/**
	 * Make decisions
	 * @access  public
	 * @param   $decisions: decisions array
	 * @return  true/false
	 * @author  Cindy Qi Li
	 */
	public function makeDecisions($decisions)
	{
		if (!is_array($decisions)) return false;

		foreach ($decisions as $sequenceID => $decision)
			$this->userDecisionsDAO->setDecision($this->userLinkID, $sequenceID, $decision);
	}

	/**
	 * Reverse decision by given sequence ID
	 * @access  public
	 * @param   $sequences : sequenceID array
	 * @return  true/false
	 * @author  Cindy Qi Li
	 */
	public function reverseDecisions($sequences)
	{
		if (!is_array($sequences)) return false;

		foreach ($sequences as $sequenceID)
			$this->userDecisionsDAO->setDecision($this->userLinkID, $sequenceID, AC_NO_DECISION);
	}

	/**
	 * check if error happens
	 * @access  public
	 * @param   none
	 * @return  true: has error; false: no error
	 * @author  Cindy Qi Li
	 */
	public function hasError()
	{
		return (count($this->errors) > 0);
	}

	/**
	 * return error report
	 * @access  public
	 * @param   none
	 * @return  error report
	 * @author  Cindy Qi Li
	 */
	public function getErrorRpt()
	{
		if ($this->output <> 'rest')
		{
			$errorRpt = HTMLRpt::generateErrorRpt($this->errors);
		}
		return $errorRpt;
	}

	/**
	 * Validate fields
	 * @access  private
	 * @param   none
	 * @return  true/false; if false, save errors into array $this->errors
	 * @author  Cindy Qi Li
	 */
	private function validateFields()
	{
		if ($this->sessionID == '')
		{
			$this->errors[] = 'AC_ERROR_EMPTY_SESSIONID';
		}
		if ($this->userID == '')
		{
			$this->errors[] = 'AC_ERROR_EMPTY_USER';
		}
		if ($this->URI == '')
		{
			$this->errors[] = 'AC_ERROR_EMPTY_URI';
		}
		if ($this->output <> 'html' && $this->output <> 'rest')
		{
			$this->errors[] = 'AC_ERROR_INVALID_FORMAT';
		}

		if (count($this->errors) > 0) return false;

		$rows = $this->userLinksDAO->getByUserIDAndURIAndSession($this->userID, $this->URI, $this->sessionID);

		if (!is_array($rows))
		{
			$this->errors[] = 'AC_ERROR_INVALID_SESSION';
			return false;
		}
		else
		{
			$this->userLinkID = $rows[0]['user_link_id'];
		}

		return true;
	}

}
?>