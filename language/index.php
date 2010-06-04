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
include_once(AC_INCLUDE_PATH.'classes/DAO/LanguagesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/Language/LanguageEditor.class.php');

$languagesDAO = new LanguagesDAO();

if (isset($_POST['id']))
{
	$pieces = explode('_', $_POST['id'], 2);
	$lang_code = $pieces[0];
}

if ( (isset($_POST['delete']) || isset($_POST['export']) || isset($_POST['edit'])) && !isset($_POST['id']))
{
	$msg->addError('NO_ITEM_SELECTED');
} 
else if ($_POST['delete'])
{
	global $msg;

	if ($lang_code == DEFAULT_LANGUAGE_CODE)
	{
		$msg->addError(array('CANNOT_DEL_DEFAULT_LANG', DEFAULT_LANGUAGE_CODE));
	}
	
	if (!$msg->containsErrors())
	{
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		header('Location: language_delete.php?id='.$_POST['id']);
		exit;
	}
}
else if ($_POST['edit'])
{
	header('Location: language_add_edit.php?id='.$_POST['id']);
	exit;
}
else if (isset($_POST['export'])) 
{
	$language =& $languageManager->getLanguage($lang_code);
	if ($language === FALSE) {
		$msg->addError('ITEM_NOT_FOUND');
	} else {
		$languageEditor =& new LanguageEditor($language);
		$languageEditor->export();
	}
}

if (isset($_POST['import']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
	$languageManager->import($_FILES['file']['tmp_name']);

	header('Location: index.php');
	exit;
}

// interface
$savant->assign('rows', $languagesDAO->getAll());
$savant->display('language/index.tmpl.php');

?>
