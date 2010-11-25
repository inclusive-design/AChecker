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

include(AC_INCLUDE_PATH.'header.inc.php');
?>

<script language="JavaScript" src="jscripts/sha-1factory.js" type="text/javascript"></script>

<script type="text/javascript">
/* 
 * Encrypt login password with sha1
 */
function encrypt_password() {
	document.form.form_password_hidden.value = hex_sha1(hex_sha1(document.form.form_password.value) + "<?php echo $_SESSION['token']; ?>");
	document.form.form_password.value = "";
	return true;
}
</script>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
<input type="hidden" name="form_login_action" value="true" />
<input type="hidden" name="form_course_id" value="<?php echo $this->course_id; ?>" />
<input type="hidden" name="form_password_hidden" value="" />

	<div class="center-input-form">
		<fieldset class="group_form"><legend class="group_form"><?php echo _AC('login') ;?></legend>
			<table  align="center" width="90%">
				<tr>
					<td colspan="2" align="left"><br /><?php echo _AC('login_text'). _AC('required_field_text') ;?><br /><br /><br /><br /></td>
				</tr>

				<tr>
					<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="login"><?php echo _AC('login_name_or_email'); ?></label></td>
					<td><input type="text" name="form_login" size="50" id="login"  class="formfield" style="max-width:70%;width:70%;"/><br /></td>
				</tr>
				
				<tr>
					<td align="left"><div class="required" align="right" title="<?php echo _AC('required_field'); ?>">*</div><label for="pass"><?php echo _AC('password'); ?></label></td>
					<td><input type="password" class="formfield" name="form_password" size="50" id="pass" style="max-width:70%;width:70%;"/></td>
				</tr>

				<tr>
					<td colspan="2">
					<p class="submit_button">
						<input type="submit" name="submit" value="<?php echo _AC('login'); ?>" class="submit" onclick="return encrypt_password();" /> 
					</p>
					</td>
				</tr>
			</table>
		</fieldset>			
	</div>
</form>

<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>