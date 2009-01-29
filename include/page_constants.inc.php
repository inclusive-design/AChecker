<?php
/* constants to map privileges.privilege_id, used to load constant pages */
define('AC_PRIV_CHECKER', 1);
define('AC_PRIV_USER_MANAGEMENT', 2);
define('AC_PRIV_GUIDELINE_MANAGEMENT', 3);
define('AC_PRIV_CHECK_MANAGEMENT', 4);
define('AC_PRIV_LANGUAGE_MANAGEMENT', 5);
define('AC_PRIV_PROFILE', 6);

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

// 1. checker pages
if (in_array(AC_PRIV_CHECKER, $privs))
{
	$_pages['checker/index.php']['title_var'] = 'web_accessibility_checker';
	$_pages['checker/index.php']['parent']    = AC_NAV_PUBLIC;
	$_pages['checker/index.php']['guide']    = 'AC_HELP_INDEX';
	
	$_pages['checker/suggestion.php']['title_var'] = 'suggestion';
	$_pages['checker/suggestion.php']['parent']    = AC_NAV_PUBLIC;
	$_pages['checker/suggestion.php']['guide']    = 'AC_HELP_SUGGESTION';
}

// 2. profile pages
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

// 3. guideline pages
if (in_array(AC_PRIV_GUIDELINE_MANAGEMENT, $privs))
{
	$_pages['guideline/index.php']['title_var'] = 'guideline_manage';
	$_pages['guideline/index.php']['parent']    = AC_NAV_TOP;
	$_pages['guideline/index.php']['children']  = array_merge(array('guideline/create_edit_guideline.php'), 
	                                                        isset($_pages['guideline/index.php']['children']) ? $_pages['guideline/index.php']['children'] : array());
	$_pages['guideline/index.php']['guide']    = 'AC_HELP_GUIDELINE';
	                                                        
	$_pages['guideline/create_edit_guideline.php']['title_var'] = 'create_guideline';
	$_pages['guideline/create_edit_guideline.php']['parent']    = 'guideline/index.php';
	$_pages['guideline/create_edit_guideline.php']['guide']    = 'AC_HELP_CREATE_EDIT_GUIDELINE';
	
	$_pages['guideline/view_guideline.php']['title_var'] = 'view_guideline';
	$_pages['guideline/view_guideline.php']['parent']    = 'guideline/index.php';
	$_pages['guideline/view_guideline.php']['guide']    = 'AC_HELP_VIEW_GUIDELINE';
	
	$_pages['guideline/delete_guideline.php']['title_var'] = 'delete_guideline';
	$_pages['guideline/delete_guideline.php']['parent']    = 'guideline/index.php';
}
// 4. user pages
if (in_array(AC_PRIV_USER_MANAGEMENT, $privs))
{
}
?>