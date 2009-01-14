<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

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
			<table align="center">
				<tr>
					<td colspan="2" align="left"><p><?php echo _AC('login_text'). _AC('required_field_text') ;?><br /><br /><br /></p></td>
				</tr>

				<tr>
					<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="login"><?php echo _AC('login_name_or_email'); ?></label></td>
					<td><input type="text" name="form_login" size="50" id="login" /><br /></td>
				</tr>
				
				<tr>
					<td align="left"><div class="required" align="right" title="<?php echo _AC('required_field'); ?>">*</div><label for="pass"><?php echo _AC('password'); ?></label></td>
					<td><input type="password" class="formfield" name="form_password" size="50" id="pass" /></td>
				</tr>

				<tr>
					<td colspan="2"><p class="submit_button">
						<input type="submit" name="submit" value="<?php echo _AC('login'); ?>" class="submit" onclick="return encrypt_password();" /> 
					</td>
				</tr>
			</table>
		</fieldset>			
	</div>
</form>

