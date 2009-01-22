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

define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');

$guidelineDAO = new GuidelinesDAO();

if ($_POST['view'])
{
	header('Location: view_guideline.php?id='.$_POST['id']);
	exit;
}

include(AC_INCLUDE_PATH.'header.inc.php');

// generate section of "my guidelines" 
$my_guidelines = $guidelineDAO->getGuidelineByUserID($_SESSION['user_id']);

if (is_array($my_guidelines))
{
	$savant->assign('title', _AC('my_guidelines'));
	$savant->assign('buttons', array('edit', 'enable', 'disable', 'delete'));
	$savant->assign('rows', $my_guidelines);
	$savant->assign('showStatus', true);
	$savant->display('guideline/index.tmpl.php');
}

// generate section of "standard guidelines" 
$savant->assign('title', _AC('standard_guidelines'));
$savant->assign('buttons', array('view'));
$savant->assign('rows', $guidelineDAO->getStandardGuidelines());
$savant->assign('showStatus', false);
$savant->display('guideline/index.tmpl.php');


// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>
