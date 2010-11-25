<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

if (!defined('AC_INCLUDE_PATH')) { exit; }

$_POST['db_login']    = urldecode($_POST['db_login']);
$_POST['db_password'] = urldecode($_POST['db_password']);
/* Destory session */
session_unset();
$_SESSION= array();
if(isset($_POST['submit']) && ($_POST['action'] == 'process')) {
	unset($errors);
//	$db = @mysql_connect($_POST['step1']['db_host'] . ':' . $_POST['step1']['db_port'], $_POST['step1']['db_login'], urldecode($_POST['step1']['db_password']));
//	@mysql_select_db($_POST['step1']['db_name'], $db);

	if (!isset($errors)) {
		unset($errors);
		unset($_POST['submit']);
		unset($action);
		store_steps($step);
		$step++;
		return;
	}
}

print_progress($step);

if (isset($errors)) {
	print_errors($errors);
}


?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="action" value="process" />
	<input type="hidden" name="step" value="<?php echo $step; ?>" />
	<?php print_hidden($step); ?>
	<p>There are no new configuration options for this version.</p>
	<br />
	<br />
	<div align="center"><input type="submit" class="button" value=" Next &raquo;" name="submit" /></div>
</form>