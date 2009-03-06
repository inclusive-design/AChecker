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

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined in checker_input_form.php.");

if (!isset($aValidator) && !isset($htmlValidator)) die(_AC("no_instance"));

include_once(AC_INCLUDE_PATH. "classes/HTMLRpt.class.php");
include_once(AC_INCLUDE_PATH. "classes/DAO/UserLinksDAO.class.php");
include_once(AC_INCLUDE_PATH. "classes/DAO/UserDecisionsDAO.class.php");

if (isset($aValidator))
{
	// find out selected guidelines
	foreach ($_POST["gid"] as $gid)
		$gids .= $gid . ",";
	
	$gids = substr($gids, 0, -1);
	$guidelinesDAO = new GuidelinesDAO();
	$rows = $guidelinesDAO->getGuidelineByIDs($gids);
	
	unset($guidelines);
	if (is_array($rows))
	{
		foreach ($rows as $id => $row)
		{
			$guidelines .= $row["title"]. ", ";
		}
	}
	$guidelines = substr($guidelines, 0, -2); // remove ending space and ,

	$num_of_total_a_errors = $aValidator->getNumOfValidateError();

	if ($num_of_total_a_errors > 0)
	{
		$errors = $aValidator->getValidationErrorRpt();
		
		// if it's a LOGIN user validates URI, save into database for user to make decision.
		// Note that results of validating uploaded files are not saved
		$user_link_id = '';
		$show_decision = 'false';   // set default showDecision to 'false'
		
		if (isset($_SESSION['user_id']) && isset($_POST['uri']))
		{
			// save errors into user_links
			$userLinksDAO = new UserLinksDAO();
			$user_link_id = $userLinksDAO->getUserLinkID($_SESSION['user_id'], $_POST['uri'], $gids);
			
			// save errors into user_decisions 
			$userDecisionsDAO = new UserDecisionsDAO();
			$userDecisionsDAO->saveErrors($user_link_id, $errors);
			
			$show_decision = 'true';
		}

		$a_rpt = new HtmlRpt($errors, $user_link_id);
		$a_rpt->setShowDecisions($show_decision);
		$a_rpt->generateHTMLRpt();

		$savant->assign('a_rpt', $a_rpt);
		$savant->assign('num_of_errors', $a_rpt->getNumOfErrors());
		$savant->assign('num_of_likely_problems', $a_rpt->getNumOfLikelyProblems());
		$savant->assign('num_of_likely_problems_no_decision', $a_rpt->getNumOfLikelyWithFailDecisions());
		$savant->assign('num_of_potential_problems', $a_rpt->getNumOfPotentialProblems());
		$savant->assign('num_of_potential_problems_no_decision', $a_rpt->getNumOfPotentialWithFailDecisions());
	}

	$savant->assign('aValidator', $aValidator);
	$savant->assign('guidelines', $guidelines);
	$savant->assign('num_of_total_a_errors', $num_of_total_a_errors);
}

if (isset($htmlValidator))
{
	$num_of_html_errors = $htmlValidator->getNumOfValidateError();

	$savant->assign('htmlValidator', $htmlValidator);
	$savant->assign('num_of_html_errors', $num_of_html_errors);
}

$savant->display('checker/checker_results.tmpl.php');
?>
