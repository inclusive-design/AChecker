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


	
	if (!$msg->containsErrors())
	{
		
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
	$language = $languageManager->getLanguage($lang_code);
	if ($language === FALSE) {
		$msg->addError('ITEM_NOT_FOUND');
	} else {
		$languageEditor = new LanguageEditor($language);
		$languageEditor->export();
	}
}

if (isset($_POST['import']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
	$allowed_file_extensions = ["zip"];

	if (!Utility::is_extension_in_list($_FILES['file']['name'], $allowed_file_extensions)) {
		$msg->addError(array('ALLOWED_FILE_TYPES', implode(", ", $allowed_file_extensions)));
	} else {
		$rtn = $languageManager->import($_FILES['file']['tmp_name']);

		// the achecker version from the imported language pack does not match with the current version
		// the array of ("imported version", "import path") is returned
		if (is_array($rtn)) {
			header('Location: language_import_mismatched_version.php?version='.urlencode($rtn["version"]).SEP.'path='.urlencode($rtn["import_path"]));
			exit;
		}
	}

	header('Location: index.php');
	exit;
}

// interface
// $savant->assign('rows', $languagesDAO->getAll());
$plate['rows'] = $languagesDAO->getAll();

// $savant->display('language/index.tmpl.php');

echo $plates->render('language/index.tmpl.php', $plate);

?>
