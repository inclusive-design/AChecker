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

if (!defined('AC_INCLUDE_PATH')) { exit; }

if(isset($_POST['submit']) && ($_POST['action'] == 'process')) {
	unset($errors);

	$_POST['admin_username'] = trim($_POST['admin_username']);
	$_POST['admin_email']    = trim($_POST['admin_email']);
	$_POST['site_name']      = trim($_POST['site_name']);
	$_POST['email']	         = trim($_POST['email']);

	/* Super Administrator Account checking: */
	if ($_POST['admin_username'] == ''){
		$errors[] = 'Administrator username cannot be empty.';
	} else {
		/* check for special characters */
		if (!(preg_match("/^[a-zA-Z0-9_]([a-zA-Z0-9_])*$/i", $_POST['admin_username']))){
			$errors[] = 'Administrator username is not valid.';
		}
	}
	if ($_POST['form_admin_password_hidden'] == '') {
		$errors[] = 'Administrator password cannot be empty.';
	}
	if ($_POST['admin_email'] == '') {
		$errors[] = 'Administrator email cannot be empty.';
	} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $_POST['admin_email'])) {
		$errors[] = 'Administrator email is not valid.';
	}

	/* System Preferences checking: */
	if ($_POST['site_name'] == '') {
		$errors[] = 'Site name cannot be empty.';
	}
	if ($_POST['email'] == '') {
		$errors[] = 'Contact email cannot be empty.';
	} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $_POST['email'])) {
		$errors[] = 'Contact email is not valid.';
	}

	if (!isset($errors)) {
		$db = mysqli_connect($_POST['step2']['db_host'], $_POST['step2']['db_login'], urldecode($_POST['step2']['db_password']), $_POST['step2']['db_name']);
		mysqli_select_db($db, $_POST['step2']['db_name']);

		$status = 3; // for instructor account

		$sql = "INSERT INTO ".$_POST['step2']['tb_prefix']."users (login, password, user_group_id, email, web_service_id, create_date)
		VALUES ('$_POST[admin_username]', '$_POST[form_admin_password_hidden]', 1, '$_POST[admin_email]', '".sha1(mt_rand() . microtime(TRUE))."', NOW())";
		$result= mysqli_query($db, $sql);

		$_POST['site_name'] = $addslashes($db, $_POST['site_name']);
		$sql = "INSERT INTO ".$_POST['step2']['tb_prefix']."config (name, value) VALUES ('site_name', '$_POST[site_name]')";
		$result = mysqli_query($db, $sql);

		$_POST['email'] = $addslashes($db, $_POST['email']);
		$sql = "INSERT INTO ".$_POST['step2']['tb_prefix']."config (name, value) VALUES ('contact_email', '$_POST[email]')";
		$result = mysqli_query($db, $sql);

		unset($_POST['admin_username']);
		unset($_POST['form_admin_password_hidden']);
		unset($_POST['admin_email']);
		unset($_POST['email']);
		unset($_POST['site_name']);

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

if (isset($_POST['step1']['old_version']) && $_POST['upgrade_action']) {
	$defaults['admin_username'] = urldecode($_POST['step1']['admin_username']);
	$defaults['admin_email']    = urldecode($_POST['step1']['admin_email']);

	$defaults['site_name']   = urldecode($_POST['step1']['site_name']);
	$defaults['header_img']  = urldecode($_POST['step1']['header_img']);
	$defaults['header_logo'] = urldecode($_POST['step1']['header_logo']);
	$defaults['home_url']    = urldecode($_POST['step1']['home_url']);
} else {
	$defaults = $_defaults;
}

?>
<script language="JavaScript" src="<?php echo AC_INCLUDE_PATH; ?>../../jscripts/sha-1factory.js" type="text/javascript"></script>

<script type="text/javascript">
function encrypt_password()
{
	document.form.form_admin_password_hidden.value = hex_sha1(document.form.admin_password.value);
	document.form.admin_password.value = "";
}
</script>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="action" value="process" />
	<input type="hidden" name="form_admin_password_hidden" value="" />
	<input type="hidden" name="form_account_password_hidden" value="" />
	<input type="hidden" name="step" value="<?php echo $step; ?>" />
	<?php print_hidden($step); ?>

	<?php
		/* detect mail settings. if sendmail_path is empty then use SMTP. */
		if (@ini_get('sendmail_path') == '') { 
			echo '<input type="hidden" name="smtp" value="true" />';
		} else {
			echo '<input type="hidden" name="smtp" value="false" />';
		}
	?>
	<br />
		<table width="70%" class="tableborder" cellspacing="0" cellpadding="1" align="center">
		<tr>
			<th colspan="2">Super Administrator Account</th>
		</tr>
		<tr>
			<td colspan="2" class="row1">The Super Administrator account is used for managing AChecker. The Super Administrator can also create additional Administrators each with their own privileges and roles. </td>
		</tr>
		<tr>
			<td class="row1"><div class="required" title="Required Field">*</div><b><label for="username">Administrator Username:</label></b><br />
			May contain only letters, numbers, or underscores.</td>
			<td class="row1"><input type="text" name="admin_username" id="username" maxlength="20" size="20" value="<?php if (!empty($_POST['admin_username'])) { echo stripslashes(htmlspecialchars($_POST['admin_username'])); } else { echo $defaults['admin_username']; } ?>" class="formfield" /></td>
		</tr>
		<tr>
			<td class="row1"><div class="required" title="Required Field">*</div><b><label for="password">Administrator Password:</label></b></td>
			<td class="row1"><input type="text" name="admin_password" id="password" maxlength="15" size="15" class="formfield" /></td>
		</tr>
		<tr>
			<td class="row1"><div class="required" title="Required Field">*</div><b><label for="email">Administrator Email:</label></b></td>
			<td class="row1"><input type="text" name="admin_email" id="email" size="40" value="<?php if (!empty($_POST['admin_email'])) { echo stripslashes(htmlspecialchars($_POST['admin_email'])); } else { echo $defaults['admin_email']; } ?>" class="formfield" /></td>
		</tr>
		</table>

	<br />

		<table width="70%" class="tableborder" cellspacing="0" cellpadding="1" align="center">
		<tr>
			<th colspan="2">System Preferences</th>
		</tr>
		<tr>
			<td class="row1"><div class="required" title="Required Field">*</div><b><label for="sitename">Site Name:</label></b><br />
			The name of your course server website.<br />Default: <kbd><?php echo $defaults['site_name']; ?></kbd></td>
			<td class="row1"><input type="text" name="site_name" size="28" maxlength="60" id="sitename" value="<?php if (!empty($_POST['site_name'])) { echo stripslashes(htmlspecialchars($_POST['site_name'])); } else { echo $defaults['site_name']; } ?>" class="formfield" /></td>
		</tr>
		<tr>
			<td class="row1"><div class="required" title="Required Field">*</div><b><label for="cemail">Contact Email:</label></b><br />
			The email that will be used as the return email when needed.</td>
			<td class="row1"><input type="text" name="email" id="cemail" size="40" value="<?php if (!empty($_POST['email'])) { echo stripslashes(htmlspecialchars($_POST['email'])); } else { echo $defaults['email']; } ?>" class="formfield" /></td>
		</tr>
		</table>

	<br />
	<br />
	<div align="center"><input type="submit" class="button" value=" Next &raquo;" name="submit" onclick="return encrypt_password();" /></div>
</form>