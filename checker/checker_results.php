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

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined in checker_input_form.php.");

//if (!isset($aValidator) && !isset($htmlValidator)) die(_AC("no_instance"));
//Simo: aggiunto isset risultati////////////////////////////////////////////////////
if (!isset($aValidator) && !isset($htmlValidator)  && !isset($_SESSION["risultati"])) die(_AC("no_instance"));

//include_once(AC_INCLUDE_PATH. "classes/HTMLRpt.class.php");
include_once(AC_INCLUDE_PATH. "classes/HTMLRptVamola.class.php");
include_once(AC_INCLUDE_PATH. "classes/Utility.class.php");
include_once(AC_INCLUDE_PATH. "classes/DAO/UserLinksDAO.class.php");
include_once(AC_INCLUDE_PATH. "classes/DAO/UserDecisionsDAO.class.php");


if (isset($htmlValidator))
{
	$num_of_html_errors = $htmlValidator->getNumOfValidateError();

	$savant->assign('htmlValidator', $htmlValidator);
	$savant->assign('num_of_html_errors', $num_of_html_errors);

	$_SESSION["risultati"]["num_of_html_errors"] = $num_of_html_errors;
}

////////////////////////////////////////////////////////////////////////////////////
//Simo: Validatore CSS
if (isset($cssValidator))
{
	$num_of_css_errors = $cssValidator->getNumOfValidateError();

	$savant->assign('cssValidator', $cssValidator);
	$savant->assign('num_of_css_errors', $num_of_css_errors);
	
	$_SESSION["risultati"]["num_of_css_errors"]=$num_of_css_errors;
}
////////////////////////////////////////////////////////////////////////////////////


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
			//MB tolgo il link $guidelines_text .= '<a title="'.$row["title"]._AC('link_open_in_new').'" target="_new" href="'.AC_BASE_HREF.'guideline/view_guideline.php?id='.$row["guideline_id"].'">'.$row["title"]. '</a>, ';
			$guidelines_text .= $row["title"]. ', ';
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
	
	
	////////////////////////////////////////////////////////////////////////////////
	//Simo: Errori di VaMoLà
	$num_of_errors_10 = $a_rpt->getNumOfErrors10();	
	$num_of_errors_11 = $a_rpt->getNumOfErrors11();
	$num_of_errors_12 = $a_rpt->getNumOfErrors12();
	$num_of_errors_13 = $a_rpt->getNumOfErrors13();
	////////////////////////////////////////////////////////////////////////////////
	
	// Simo: Aggiunto bollino Stanca
	// no any problems or all problems have pass decisions, display seals when no errors
//	if ($num_of_errors == 0 && 
//	    ($num_of_likely_problems == 0 && $num_of_potential_problems == 0 ||
//	     $num_of_likely_problems_no_decision == 0 && $num_of_potential_problems_no_decision == 0))
// Impossibile che tutti gli errori siano 0	
//if (($num_of_errors_10 + $num_of_html_errors + $num_of_css_errors + $num_of_errors_11 + $num_of_errors_12 + $num_of_errors_13) == 103)
	if (($num_of_errors_10 + $num_of_html_errors + $num_of_css_errors) == 0)
	{
		$utility = new Utility();
		$seals = $utility->getSeals($guideline_rows);
	}
	
	$savant->assign('a_rpt', $a_rpt);
	$savant->assign('num_of_errors', $num_of_errors);
	$savant->assign('num_of_likely_problems', $num_of_likely_problems);
	$savant->assign('num_of_likely_problems_no_decision', $num_of_likely_problems_no_decision);
	$savant->assign('num_of_potential_problems', $num_of_potential_problems);
	$savant->assign('num_of_potential_problems_no_decision', $num_of_potential_problems_no_decision);

	
	////////////////////////////////////////////////////////////////////////////////
	//Simo: Errori di VaMoLà e impostazione variabili di sessione
	$savant->assign('num_of_errors_10', $num_of_errors_10);
	$savant->assign('num_of_errors_11', $num_of_errors_11);
	$savant->assign('num_of_errors_12', $num_of_errors_12);
	$savant->assign('num_of_errors_13', $num_of_errors_13);
	
	$_SESSION["risultati"]["num_of_total_a_errors"] = $num_of_total_a_errors;
	$_SESSION["risultati"]["guidelines"] = $guidelines_text;
	
	$_SESSION["risultati"]["num_of_errors"] = $num_of_errors;	
	$_SESSION["risultati"]["num_of_likely_problems"] = $num_of_likely_problems;	
	$_SESSION["risultati"]["num_of_potential_problems"] = $num_of_potential_problems;	
	
	$_SESSION["risultati"]["num_of_errors_10"] = $num_of_errors_10;
	$_SESSION["risultati"]["num_of_errors_11"] = $num_of_errors_11;	
	$_SESSION["risultati"]["num_of_errors_12"] = $num_of_errors_12;
	$_SESSION["risultati"]["num_of_errors_13"] = $num_of_errors_13;
	////////////////////////////////////////////////////////////////////////////////

	
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




////////////////////////////////////////////////////////////////////////////////////
// Simo: Sezione per utilizzo variabili di sessione nei template
if(isset($_SESSION["risultati"]))
{
	$savant->assign('guidelines_text', $_SESSION["risultati"]["guidelines"]);
	
	$savant->assign('num_of_total_a_errors', $_SESSION["risultati"]["num_of_total_a_errors"]);
	
	$savant->assign('num_of_html_errors', $_SESSION["risultati"]["num_of_html_errors"]);
	$savant->assign('num_of_css_errors', $_SESSION["risultati"]["num_of_css_errors"]);
	
	$savant->assign('num_of_errors', $_SESSION["risultati"]["num_of_errors"]);
	$savant->assign('num_of_likely_problems', $_SESSION["risultati"]["num_of_likely_problems"]);
	$savant->assign('num_of_potential_problems', $_SESSION["risultati"]["num_of_potential_problems"]);

	$savant->assign('num_of_errors_10', $_SESSION["risultati"]["num_of_errors_10"]);
	$savant->assign('num_of_errors_11', $_SESSION["risultati"]["num_of_errors_11"]);
	$savant->assign('num_of_errors_12', $_SESSION["risultati"]["num_of_errors_12"]);
	$savant->assign('num_of_errors_13', $_SESSION["risultati"]["num_of_errors_13"]);
}
////////////////////////////////////////////////////////////////////////////////////



$savant->display('checker/checker_results.tmpl.php');
?>
