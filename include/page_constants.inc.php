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

/* constants to map privileges.privilege_id, used to load constant pages */
define('AC_PRIV_CHECKER', 1);
define('AC_PRIV_USER_MANAGEMENT', 2);
define('AC_PRIV_GUIDELINE_MANAGEMENT', 3);
define('AC_PRIV_CHECK_MANAGEMENT', 4);
define('AC_PRIV_LANGUAGE_MANAGEMENT', 5);
define('AC_PRIV_TRANSLATION', 6);
define('AC_PRIV_PROFILE', 7);
define('AC_PRIV_UPDATER', 8);

/* constants used for menu item generation. Used in class Menu (include/classes/Menu.class.php) */
define('AC_NAV_PUBLIC', 'AC_NAV_PUBLIC');  // public menus, when no user login
define('AC_NAV_TOP', 'AC_NAV_TOP');        // top tab menus

include_once('classes/DAO/PrivilegesDAO.class.php');
$priviledgesDAO = new PrivilegesDAO();

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] <> 0)
{
	$rows = $priviledgesDAO->getUserPrivileges($_SESSION['user_id']);
}
else
{
	$rows = $priviledgesDAO->getPublicPrivileges();
}

foreach ($rows as $row)
	$privs[] = $row['privilege_id'];

/* initialize pages accessed by public */
//$_pages[AC_NAV_PUBLIC] = array('index.php' => array('parent'=>AC_NAV_PUBLIC));

/* define all accessible pages */
// 1. public pages
$_pages['translator.php']['title_var'] = 'translator';
$_pages['translator.php']['parent']    = AC_NAV_PUBLIC;
$_pages['translator.php']['guide']    = 'AC_HELP_TRANSLATOR';

$_pages['register.php']['title_var'] = 'registration';
$_pages['register.php']['parent']    = AC_NAV_PUBLIC;
$_pages['register.php']['guide']    = 'AC_HELP_REGISTRATION';

$_pages['confirm.php']['title_var'] = 'confirm';
$_pages['confirm.php']['parent']    = AC_NAV_PUBLIC;

$_pages['login.php']['title_var'] = 'login';
$_pages['login.php']['parent']    = AC_NAV_PUBLIC;
$_pages['login.php']['guide']    = 'AC_HELP_LOGIN';
$_pages['login.php']['children']  = array_merge(array('password_reminder.php'), isset($_pages['login.php']['children']) ? $_pages['login.php']['children'] : array());

$_pages['logout.php']['title_var'] = 'logout';
$_pages['logout.php']['parent']    = AC_NAV_PUBLIC;

$_pages['password_reminder.php']['title_var'] = 'password_reminder';
$_pages['password_reminder.php']['parent']    = 'login.php';
$_pages['password_reminder.php']['guide']    = 'AC_HELP_PASSWORD_REMINDER';

// The scripts below need to be accessible by public. 
$_pages['guideline/view_guideline.php']['title_var'] = 'view_guideline';   // used in web service validation response
$_pages['checker/suggestion.php']['title_var'] = 'details';
$_pages['documentation/web_service_api.php']['title_var'] = 'web_service_api';

// 1. checker pages
if (in_array(AC_PRIV_CHECKER, $privs))
{
	$_pages['checker/index.php']['title_var'] = 'web_accessibility_checker';
	$_pages['checker/index.php']['parent']    = AC_NAV_PUBLIC;
	$_pages['checker/index.php']['guide']    = 'AC_HELP_INDEX';
	
	$_pages['checker/suggestion.php']['parent']    = AC_NAV_PUBLIC;
	$_pages['checker/suggestion.php']['guide']    = 'AC_HELP_SUGGESTION';
}

// 2. user pages
if (in_array(AC_PRIV_USER_MANAGEMENT, $privs))
{
	$_pages['user/index.php']['title_var'] = 'users';
	$_pages['user/index.php']['parent']    = AC_NAV_TOP;
	$_pages['user/index.php']['children']  = array_merge(array('user/user_create_edit.php',
	                                                           'user/user_group.php'), 
	                                                     isset($_pages['user/index.php']['children']) ? $_pages['user/index.php']['children'] : array());
	$_pages['user/index.php']['guide']    = 'AC_HELP_USER';

	$_pages['user/user_create_edit.php']['title_var'] = 'create_user';
	$_pages['user/user_create_edit.php']['parent']    = 'user/index.php';
	$_pages['user/user_create_edit.php']['guide']    = 'AC_HELP_CREATE_EDIT_USER';
	
	$_pages['user/user_password.php']['title_var'] = 'change_password';
	$_pages['user/user_password.php']['parent']    = 'user/index.php';
	$_pages['user/user_password.php']['guide']    = 'AC_HELP_USER_PASSWORD';

	$_pages['user/user_delete.php']['title_var'] = 'delete_user';
	$_pages['user/user_delete.php']['parent']    = 'user/index.php';

	$_pages['user/user_group.php']['title_var'] = 'user_group';
	$_pages['user/user_group.php']['parent']    = 'user/index.php';
	$_pages['user/user_group.php']['children']  = array_merge(array('user/user_group_create_edit.php'), 
	                                                     isset($_pages['user/user_group.php']['children']) ? $_pages['user/user_group.php']['children'] : array());
	$_pages['user/user_group.php']['guide']    = 'AC_HELP_USER_GROUP';
	
	$_pages['user/user_group_create_edit.php']['title_var'] = 'create_edit_user_group';
	$_pages['user/user_group_create_edit.php']['parent']    = 'user/user_group.php';
	$_pages['user/user_group_create_edit.php']['guide']    = 'AC_HELP_CREATE_EDIT_USER_GROUP';
	
	$_pages['user/user_group_delete.php']['title_var'] = 'delete_user_group';
	$_pages['user/user_group_delete.php']['parent']    = 'user/user_group.php';
}

// 3. guideline pages
if (in_array(AC_PRIV_GUIDELINE_MANAGEMENT, $privs))
{
	$_pages['guideline/index.php']['title_var'] = 'guidelines';
	$_pages['guideline/index.php']['parent']    = AC_NAV_TOP;
	$_pages['guideline/index.php']['children']  = array_merge(array('guideline/create_edit_guideline.php'), 
	                                                        isset($_pages['guideline/index.php']['children']) ? $_pages['guideline/index.php']['children'] : array());
	$_pages['guideline/index.php']['guide']    = 'AC_HELP_GUIDELINE';
	                                                        
	$_pages['guideline/create_edit_guideline.php']['title_var'] = 'create_guideline';
	$_pages['guideline/create_edit_guideline.php']['parent']    = 'guideline/index.php';
	$_pages['guideline/create_edit_guideline.php']['guide']    = 'AC_HELP_CREATE_EDIT_GUIDELINE';
	
	$_pages['guideline/add_edit_group.php']['title_var'] = 'add_group';
	$_pages['guideline/add_edit_group.php']['parent']    = 'guideline/index.php';
	$_pages['guideline/add_edit_group.php']['guide']    = 'AC_HELP_ADD_GROUP';
	
	// $_pages['guideline/view_guideline.php']['title_var'] is defined outside to open to public
	$_pages['guideline/view_guideline.php']['parent']    = 'guideline/index.php';
	$_pages['guideline/view_guideline.php']['guide']    = 'AC_HELP_VIEW_GUIDELINE';
	
	$_pages['guideline/delete_guideline.php']['title_var'] = 'delete_guideline';
	$_pages['guideline/delete_guideline.php']['parent']    = 'guideline/index.php';
}

// 3. check pages
if (in_array(AC_PRIV_CHECK_MANAGEMENT, $privs))
{
	$_pages['check/index.php']['title_var'] = 'checks';
	$_pages['check/index.php']['parent']    = AC_NAV_TOP;
	$_pages['check/index.php']['children']  = array_merge(array('check/check_create_edit.php'), 
	                                                        isset($_pages['check/index.php']['children']) ? $_pages['check/index.php']['children'] : array());
	$_pages['check/index.php']['guide']    = 'AC_HELP_CHECK';
	                                                        
	$_pages['check/html_tag_list.php']['title_var'] = 'html_tag_list';
	$_pages['check/html_tag_list.php']['parent']    = 'check/index.php';
	$_pages['check/html_tag_list.php']['guide']    = 'AC_HELP_HTML_TAG_LIST';
	
	$_pages['check/check_create_edit.php']['title_var'] = 'create_check';
	$_pages['check/check_create_edit.php']['parent']    = 'check/index.php';
	$_pages['check/check_create_edit.php']['guide']    = 'AC_HELP_CREATE_EDIT_CHECK';
	
	$_pages['check/check_function_edit.php']['title_var'] = 'edit_check_function';
	$_pages['check/check_function_edit.php']['parent']    = 'check/index.php';
	$_pages['check/check_function_edit.php']['guide']    = 'AC_HELP_EDIT_CHECK_FUNCTION';
	
	$_pages['check/check_delete.php']['title_var'] = 'delete_check';
	$_pages['check/check_delete.php']['parent']    = 'check/index.php';
}

// 5. language pages
if (in_array(AC_PRIV_LANGUAGE_MANAGEMENT, $privs))
{
	$_pages['language/index.php']['title_var'] = 'language';
	$_pages['language/index.php']['parent']    = AC_NAV_TOP;
	$_pages['language/index.php']['children']  = array_merge(array('language/language_add_edit.php'), 
	                                                     isset($_pages['language/index.php']['children']) ? $_pages['language/index.php']['children'] : array());
	$_pages['language/index.php']['guide']    = 'AC_HELP_LANGUAGE';

	$_pages['language/language_add_edit.php']['title_var'] = 'add_language';
	$_pages['language/language_add_edit.php']['parent']    = 'language/index.php';
	$_pages['language/language_add_edit.php']['guide']    = 'AC_HELP_ADD_EDIT_LANGUAGE';
	
	$_pages['language/language_delete.php']['title_var'] = 'delete_language';
	$_pages['language/language_delete.php']['parent'] = 'language/index.php';

	$_pages['language/language_import_mismatched_version.php']['title_var'] = 'import_language';
	$_pages['language/language_import_mismatched_version.php']['parent'] = 'language/index.php';
}

// 6. translation
if (in_array(AC_PRIV_TRANSLATION, $privs))
{
	$_pages['translation/index.php']['title_var'] = 'translation';
	$_pages['translation/index.php']['parent']    = AC_NAV_TOP;
	$_pages['translation/index.php']['guide']    = 'AC_HELP_TRANSLATION';
}

// 7. profile pages
if (in_array(AC_PRIV_PROFILE, $privs))
{
	$_pages['profile/index.php']['title_var'] = 'profile';
	$_pages['profile/index.php']['parent']    = AC_NAV_TOP;
	$_pages['profile/index.php']['guide']    = 'AC_HELP_PROFILE';
	$_pages['profile/index.php']['children']  = array_merge(array('profile/change_password.php', 
	                                                              'profile/change_email.php'), 
	                                                        isset($_pages['profile/index.php']['children']) ? $_pages['profile/index.php']['children'] : array());
	
	$_pages['profile/change_password.php']['title_var'] = 'change_password';
	$_pages['profile/change_password.php']['parent']    = 'profile/index.php';
	$_pages['profile/change_password.php']['guide']    = 'AC_HELP_CHANGE_PASSWORD';
	
	$_pages['profile/change_email.php']['title_var'] = 'change_email';
	$_pages['profile/change_email.php']['parent']    = 'profile/index.php';
	$_pages['profile/change_email.php']['guide']    = 'AC_HELP_CHANGE_EMAIL';
}

// 8. updater pages
if (in_array(AC_PRIV_UPDATER, $privs))
{
	$_pages['updater/index.php']['title_var'] = 'updater';
	$_pages['updater/index.php']['parent']    = AC_NAV_TOP;
	$_pages['updater/index.php']['guide']    = 'AC_HELP_UPDATER';
	$_pages['updater/index.php']['children']  = array_merge(array('updater/myown_patches.php', 
	                                                              'updater/patch_create.php'), 
	                                                        isset($_pages['updater/index.php']['children']) ? $_pages['updater/index.php']['children'] : array());
	
	$_pages['updater/myown_patches.php']['title_var'] = 'myown_updates';
	$_pages['updater/myown_patches.php']['parent']    = 'updater/index.php';
	$_pages['updater/myown_patches.php']['children']    = array('updater/patch_create.php');
	
	$_pages['updater/patch_create.php']['title_var'] = 'create_update';
	$_pages['updater/patch_create.php']['parent']    = 'updater/index.php';
	$_pages['updater/patch_create.php']['guide']    = 'AC_HELP_CREATE_UPDATE';

	$_pages['updater/patch_edit.php']['title_var'] = 'edit_update';
	$_pages['updater/patch_edit.php']['parent']    = 'updater/index.php';

	$_pages['updater/patch_delete.php']['title_var'] = 'delete_update';
	$_pages['updater/patch_delete.php']['parent']    = 'updater/index.php';
}
?>