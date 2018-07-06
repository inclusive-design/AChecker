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
include_once(AC_INCLUDE_PATH.'classes/DAO/LangCodesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/Language/LanguageUtility.class.php');

if (isset($_GET["id"])) 
{
	$pieces = explode('_', $_GET['id'], 2);
	$lang_code = $pieces[0];
	$charset = $pieces[1];
}

$languagesDAO = new LanguagesDAO();
$langCodesDAO = new LangCodesDAO();

// handle submits
if (isset($_POST['cancel'])) 
{
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
} 
else if (isset($_POST['save']))
{
	if (isset($_GET["id"]))  // edit existing guideline
	{
		if ($languagesDAO->Update($lang_code, 
		                      $charset,
		                      '',
		                      trim($_POST['native_name']),
		                      trim($_POST['english_name']),
		                      $_POST['status']))
		{
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: index.php');
			exit;
		}
	}
	else  // create a new guideline
	{
		if (isset($_POST['locale']) && $_POST['locale'] <> '')
			$language_code = $_POST['lang_code'] . AC_LANGUAGE_LOCALE_SEP. $_POST['locale'];
		else
			$language_code = $_POST['lang_code'];

		if ($languagesDAO->Create($language_code, 
		                      trim($_POST['charset']),
		                      '',
		                      trim($_POST['native_name']),
		                      trim($_POST['english_name']),
		                      $_POST['status']))
		{
			$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
			header('Location: index.php');
			exit;
		}
	}
}

// interface display
if (isset($lang_code) && isset($charset))
{
	// edit existing guideline
	$row = $languagesDAO->getByLangCodeAndCharset($lang_code, $charset);
	$row['lang_code'] = LanguageUtility::getParentCode($row['language_code']);
	$row['locale'] = LanguageUtility::getLocale($row['language_code']);

	$plate['row'] = $row;
}


$plate['rows_lang'] = $langCodesDAO->GetAll();

echo $plates->render('language/language_add_edit.tmpl.php', $plate);
?>
