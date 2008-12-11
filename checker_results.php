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

if (!defined("AT_INCLUDE_PATH")) die("Error: AT_INCLUDE_PATH is not defined in checker_input_form.php.");

if (!isset($aValidator) && !isset($htmlValidator)) die(_AC("no_instance"));

if (isset($aValidator))
{
	// find out selected guidelines
	foreach ($_POST["gid"] as $gid)
		$gids .= $gid . ",";
	
	$sql = "select title
					from ". TABLE_PREFIX ."guidelines
					where guideline_id in (" . substr($gids, 0, -1) . ")
					order by title";
	$result	= mysql_query($sql, $db) or die(mysql_error());
	
	while ($row = mysql_fetch_assoc($result))
	{
		$guidelines .= $row["title"]. ", ";
	}
	$guidelines = substr($guidelines, 0, -2); // remove ending space and ,
	
	$num_of_total_a_errors = $aValidator->getNumOfValidateError();

	if ($num_of_total_a_errors > 0)
	{
		include(AT_INCLUDE_PATH. "classes/AccessibilityRpt.class.php");

		$a_rpt = new AccessibilityRpt($aValidator->getValidationErrorRpt());
		
		$num_of_errors = $a_rpt->getNumOfErrors();
		$num_of_likely_problems = $a_rpt->getNumOfLikelyProblems();
		$num_of_potential_problems = $a_rpt->getNumOfPotentialProblems();

		$savant->assign('a_rpt', $a_rpt);
		$savant->assign('num_of_errors', $a_rpt->getNumOfErrors());
		$savant->assign('num_of_likely_problems', $a_rpt->getNumOfLikelyProblems());
		$savant->assign('num_of_potential_problems', $a_rpt->getNumOfPotentialProblems());
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

$savant->display('checker_results.tmpl.php');
?>
