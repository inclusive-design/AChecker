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
// $Id: index.php 495 2011-02-10 21:27:00Z cindy $

// Called by ajax request from guidelineline view report -> "make decision(s)" buttons
// @ see checker/js/checker.js
 
define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/UserLinksDAO.class.php');

// main process to save decisions
$guidelinesDAO = new GuidelinesDAO();
$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_POST['gids']);

if (!is_array($guideline_rows)) {
	echo _AC("AC_ERROR_EMPTY_GID");
	exit;
}
$utility = new Utility();
$seals = $utility->getSeals($guideline_rows);

if (is_array($seals)) {
	$userLinksDAO = new UserLinksDAO();
	$rows = $userLinksDAO->getByUserIDAndURIAndSession($_SESSION['user_id'], $_POST['uri'], $_POST['jsessionid']);

	$plate['user_link_id'] = $rows[0]['user_link_id'];
	$plate['seals'] = $seals;

	echo $plates->render('checker/seals.tmpl.php', $plate);
}

exit;
?>
