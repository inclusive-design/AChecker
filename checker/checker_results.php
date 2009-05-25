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
	$guideline_rows = $guidelinesDAO->getGuidelineByIDs($gids);
	
	unset($guidelines_text);
	if (is_array($guideline_rows))
	{
		foreach ($guideline_rows as $id => $row)
		{
			$guidelines_text .= '<a title="'.$row["title"]._AC('link_open_in_new').'" target="_new" href="'.AC_BASE_HREF.'guideline/view_guideline.php?id='.$row["guideline_id"].'">'.$row["title"]. '</a>, ';
		}
	}
	$guidelines_text = substr($guidelines_text, 0, -2); // remove ending space and ,

	$num_of_total_a_errors = $aValidator->getNumOfValidateError();

	$errors = $aValidator->getValidationErrorRpt();
	
	// if it's a LOGIN user validates URI, save into database for user to make decision.
	// Note that results of validating uploaded files are not saved
	$user_link_id = '';
	$allow_set_decision = 'false';   // set default allowSetDecision to 'false'
	$from_referer = 'false';
	
	// initial request to validate referer URL
	if ($_GET['uri'] == 'referer')
	{
		$from_referer = 'true';
		
		// if id (id is user_link_id) is given
		if (isset($_GET['id']) && intval($_GET['id']) > 0) 
		{
			$user_link_id = $_GET['id'];
			
			// same user associated in user_link_id is login, set user_link_id
			// the validation of ($_SESSION['user_id'] == user_id defined in $_GET['id']) is done in checker/index.php 
			if ($_SESSION['user_id'] > 0) $allow_set_decision = 'true';
		}
	}
	else if (isset($_POST['referer_report']))
	{
		$from_referer = 'true';
		if (isset($_POST['referer_user_link_id'])) 
		{
			$user_link_id = $_POST['referer_user_link_id'];
			if ($_SESSION['user_id'] > 0) $allow_set_decision = 'true';
		}
	}
	else if (isset($_SESSION['user_id']) && $_POST["validate_uri"])
	{
		// save errors into user_links
		$userLinksDAO = new UserLinksDAO();
		$user_link_id = $userLinksDAO->getUserLinkID($_SESSION['user_id'], $_POST['uri'], $gids);
		
		// save errors into user_decisions 
		$userDecisionsDAO = new UserDecisionsDAO();
		$userDecisionsDAO->saveErrors($user_link_id, $errors);
		
		$allow_set_decision = 'true';
	}

	$a_rpt = new HtmlRpt($errors, $user_link_id);
	$a_rpt->setAllowSetDecisions($allow_set_decision);
	$a_rpt->setFromReferer($from_referer);
	if (isset($_POST['show_source'])) $a_rpt->setShowSource('true', $source_array);
	
	$a_rpt->generateHTMLRpt();

	$num_of_errors = $a_rpt->getNumOfErrors();
	$num_of_likely_problems = $a_rpt->getNumOfLikelyProblems();
	$num_of_likely_problems_no_decision = $a_rpt->getNumOfLikelyWithFailDecisions();
	$num_of_potential_problems = $a_rpt->getNumOfPotentialProblems();
	$num_of_potential_problems_no_decision = $a_rpt->getNumOfPotentialWithFailDecisions();
	
//	$num_of_errors = 0;
//	$num_of_likely_problems = 0;
//	$num_of_likely_problems_no_decision = 0;
//	$num_of_potential_problems = 0;
//	$num_of_potential_problems_no_decision = 0;
	
	// no any problems or all problems have pass decisions, display seals when no errors
	if ($num_of_errors == 0 && 
	    ($num_of_likely_problems == 0 && $num_of_potential_problems == 0 ||
	     $num_of_likely_problems_no_decision == 0 && $num_of_potential_problems_no_decision == 0))
	{
		unset($highest_subset_guideline);
		foreach ($guideline_rows as $row)
		{
			if ($row['subset'] == 0)
			{
				$seals[] = array('title' => $row['title'],
				                 'guideline' => $row['abbr'], 
				                 'seal_icon_name' => $row['seal_icon_name']);
			}
			else
			{
				if (!isset($highest_subset_guideline) || $highest_subset_guideline['subset'] < $row['subset'])
				{
					$highest_subset_guideline = $row;
				}
			}// end of outer if
		} // end of foreach
		$seals[] = array('title' => $highest_subset_guideline['title'], 
				         'guideline' => $highest_subset_guideline['abbr'], 
		                 'seal_icon_name' => $highest_subset_guideline['seal_icon_name']);
	}
	
	$savant->assign('a_rpt', $a_rpt);
	$savant->assign('num_of_errors', $num_of_errors);
	$savant->assign('num_of_likely_problems', $num_of_likely_problems);
	$savant->assign('num_of_likely_problems_no_decision', $num_of_likely_problems_no_decision);
	$savant->assign('num_of_potential_problems', $num_of_potential_problems);
	$savant->assign('num_of_potential_problems_no_decision', $num_of_potential_problems_no_decision);

	$savant->assign('aValidator', $aValidator);
	$savant->assign('guidelines_text', $guidelines_text);
	$savant->assign('num_of_total_a_errors', $num_of_total_a_errors);
	
	// vars for displaying seals
	if (is_array($seals)) $savant->assign('seals', $seals);
	if ($user_link_id <> '') $savant->assign('user_link_id', $user_link_id);
	
	// vars for displaying report from referer URI
	if ($_REQUEST['uri'] == 'referer')
	{
		$savant->assign('referer_report', 1);
		if (intval($user_link_id) > 0) $savant->assign('referer_user_link_id', $user_link_id);
	}
}

if (isset($htmlValidator))
{
	$num_of_html_errors = $htmlValidator->getNumOfValidateError();

	$savant->assign('htmlValidator', $htmlValidator);
	$savant->assign('num_of_html_errors', $num_of_html_errors);
	$savant->assign('num_of_html_errors', $num_of_html_errors);

}

$savant->display('checker/checker_results.tmpl.php');
?>
