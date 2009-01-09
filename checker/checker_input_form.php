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

$default_uri_value = "http://";
$default_guideline = 8;      // default guideline to check html accessibility if the guidelines are not given in $_POST
$num_of_guidelines_per_row = 3;  // default number of guidelines to display in a row on the page

if (!isset($_POST["gid"])) $_POST["gid"] = array($default_guideline);

$sql = "select guideline_id, title
				from ". TABLE_PREFIX ."guidelines
				order by title";
$result	= mysql_query($sql, $db) or die(mysql_error());

$savant->assign('default_uri_value', $default_uri_value);
$savant->assign('default_guideline', $default_guideline);
$savant->assign('num_of_guidelines_per_row', $num_of_guidelines_per_row);
$savant->assign('result', $result);

$savant->display('checker/checker_input_form.tmpl.php');
?>
