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

/*
 * Called by "register.php" and "user/user_create_edit.php
 * 
 * Accept parameters:
 * 
 * show_user_group: true/false. Indicates whether show section "User Group"
 *                  Set to true when admin creates/edits user; set to false at new registration.
 *                  The new user registered via registration form is automatically set into group "User" 
 * show_password:  true/false. Indicates whether show section "Password" & "Password Again"
 *                 Set to true when admin creates new user or new user registration; 
 *                 Set to false when admin edits existing user.
 * show_status: true/false. Indicates whether show section "status"
 *              Set to true when admin creates/edits user; set to false at new registration.
 * user_row: only need when edit existing user.
 * all_user_groups: display selections in dropdown list box "User Group"
 * title: page title
 * submit_button_text: button text for submit button. "Register" at registration, "Save" at admin creating/editing user
 */
$default_user_group_id = AC_USER_GROUP_USER;

require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<script language="JavaScript" src="jscripts/sha-1factory.js" type="text/javascript"></script>

<script type="text/javascript">
function encrypt_password()
{
	document.form.password_error.value = "";

	err = verify_password(document.form.form_password1.value, document.form.form_password2.value);
	
	if (err.length > 0)
	{
		document.form.password_error.value = err;
	}
	else
	{
		document.form.form_password_hidden.value = hex_sha1(document.form.form_password1.value);
		document.form.form_password1.value = "";
		document.form.form_password2.value = "";
	}
}
</script>

<form method="post" action="<?php $id_str = ''; if (isset($_GET['id'])) $id_str='?id='.$_GET['id']; echo $_SERVER['PHP_SELF'].$id_str; ?>" name="form">
<input name="password_error" type="hidden" />
<input type="hidden" name="form_password_hidden" value="" />

<div class="center-input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo $this->title; ?></legend>

	<table class="form-data" align="center">
		<tr>
			<td colspan="2" align="left"><br/><?php echo _AC('required_field_text') ;?><br /><br /><br/></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="login"><?php echo _AC('login_name'); ?></label>:</td>
			<td align="left"><input id="login" name="login" type="text" maxlength="20" size="30" value="<?php if (isset($_POST['login'])) echo stripslashes(htmlspecialchars($_POST['login'])); else echo stripslashes(htmlspecialchars($this->user_row['login'])); ?>" /></td>
		</tr>

		<tr>
			<td align="left" colspan="2">
				<small>&middot; <?php echo _AC('contain_only'); ?><br />
					   &middot; <?php echo _AC('20_max_chars'); ?></small>
			</td>
		</tr>
		
		<?php if ($this->show_user_group) { ?>
		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="user_group_id"><?php echo _AC('user_group'); ?>:</label>:</td>
			<td align="left">
			<select name="user_group_id" id="user_group_id">
				<option value="-1">- <?php echo _AC('select'); ?> -</option>
				<?php foreach ($this->all_user_groups as $user_group) {?>
				<option value="<?php echo $user_group['user_group_id']; ?>" <?php if ((isset($_POST['user_group_id']) && $_POST['user_group_id']==$user_group['user_group_id']) || (!isset($_POST['user_group_id']) && !isset($this->user_row['user_group_id']) && $user_group['user_group_id'] == $default_user_group_id) || (!isset($_POST['user_group_id']) && isset($this->user_row['user_group_id']) && $this->user_row['user_group_id'] == $user_group['user_group_id'] )) echo 'selected="selected"'; ?>><?php echo $user_group['title']; ?></option>
				<?php } ?>
			</select>
			</td>
		</tr>
		<?php } ?>

		<?php if ($this->show_password) { ?>
		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="form_password1"><?php echo _AC('password'); ?></label>:</td>
			<td align="left"><input id="form_password1" name="form_password1" type="password" size="15" maxlength="15" /></td>
		</tr>

		<tr>
			<td colspan="2" align="left"><small>&middot; <?php echo _AC('combination'); ?><br />
				   &middot; <?php echo _AC('15_max_chars'); ?></small></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="form_password2"><?php echo _AC('password_again'); ?></label>:</td>
			<td align="left"><input id="form_password2" name="form_password2" type="password" size="15" maxlength="15" /></td>
		</tr>
		<?php } ?>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="email"><?php echo _AC('email_address'); ?></label>:</td>
			<td align="left"><input id="email" name="email" type="text" size="50" maxlength="50" value="<?php if (isset($_POST['email'])) echo stripslashes(htmlspecialchars($_POST['email'])); else echo stripslashes(htmlspecialchars($this->user_row['email'])); ?>" /></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="first_name"><?php echo _AC('first_name'); ?></label>:</td>
			<td align="left"><input id="first_name" name="first_name" type="text" value="<?php if (isset($_POST['first_name'])) echo stripslashes(htmlspecialchars($_POST['first_name'])); else echo stripslashes(htmlspecialchars($this->user_row['first_name'])); ?>" /></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="last_name"><?php echo _AC('last_name'); ?></label>:</td>
			<td align="left"><input id="last_name" name="last_name" type="text" value="<?php if (isset($_POST['last_name'])) echo stripslashes(htmlspecialchars($_POST['last_name'])); else echo stripslashes(htmlspecialchars($this->user_row['last_name'])); ?>" /></td>
		</tr>

		<?php if ($this->show_status) {?>
		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><?php echo _AC('status'); ?>:</td>
			<td align="left">
				<input type="radio" name="status" id="statusD" value="<?php echo AC_STATUS_DISABLED; ?>" <?php if ((isset($_POST['status']) && $_POST['status']==0) || (!isset($_POST['status']) && $this->user_row['status']==AC_STATUS_DISABLED)) echo 'checked="checked"'; ?> /><label for="statusD"><?php echo _AC('disabled'); ?></label> 
				<input type="radio" name="status" id="statusE" value="<?php echo AC_STATUS_ENABLED; ?>" <?php if ((isset($_POST['status']) && $_POST['status']==1) || (!isset($_POST['status']) && $this->user_row['status']==AC_STATUS_ENABLED)) echo 'checked="checked"'; ?> /><label for="statusE"><?php echo _AC('enabled'); ?></label>
				<?php if (defined('AC_EMAIL_CONFIRMATION') && AC_EMAIL_CONFIRMATION) {?>
				<input type="radio" name="status" id="statusU" value="<?php echo AC_STATUS_UNCONFIRMED; ?>" <?php if ((isset($_POST['status']) && $_POST['status']==1) || (!isset($_POST['status']) && $this->user_row['status']==AC_STATUS_UNCONFIRMED)) echo 'checked="checked"'; ?> /><label for="statusU"><?php echo _AC('unconfirmed'); ?></label>
				<?php }?>
			</td>
		</tr>
		<?php }?>
		
		<?php if (isset($this->user_row['web_service_id'])) {?>
		<tr>
			<td align="left"><?php echo _AC('web_service_id'); ?>:</td>
			<td align="left"><?php echo $this->user_row['web_service_id']; ?></td>
		</tr>
		<?php }?>

		<tr>
			<td colspan="2">
			<p class="submit_button">
				<input type="submit" name="submit" value="<?php echo $this->submit_button_text; ?>" class="submit" onclick="return encrypt_password();" /> 
				<input type="submit" name="cancel" value=" <?php echo _AC('cancel'); ?> "  class="submit" />
			</p>
			</td>
		</tr>
	</table>
</fieldset>

</div>
</form>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>