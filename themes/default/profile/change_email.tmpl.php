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

global $onload;
$onload = 'document.form.form_password.focus();';
require(AC_INCLUDE_PATH.'header.inc.php'); 

?>

<script language="JavaScript" type="text/javascript" src="jscripts/sha-1factory.js"></script>

<script type="text/javascript">
function encrypt_password()
{
	document.form.form_password_hidden.value = hex_sha1(document.form.form_password.value);
	document.form.form_password.value = "";
}
</script>

<div class="center-input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC('change_email'); ?></legend>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
		<input type="hidden" name="form_password_hidden" value="" />
	
		<table align="center">
			<tr>
				<td align="left">
					<div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
					<label for="form_password"><?php echo _AC('password'); ?></label>
				</td>
				<td align="left">
					<input id="form_password" name="form_password" type="password" size="15" maxlength="15" value="" />
				</td>
			</tr>

			<tr>
				<td align="left">
					<div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
					<label for="email"><?php echo _AC('email_address'); ?></label>
				</td>
				<td align="left">
					<input id="email" name="email" type="text" size="50" maxlength="50" value="<?php echo stripslashes(htmlspecialchars($_POST['email'])); ?>" />
				</td>
			</tr>
		
			<tr>
				<td colspan="2">
					<p class="submit_button">
						<input type="submit" name="submit" value="<?php echo _AC('submit'); ?>" onClick="encrypt_password()" />
						<input type="submit" name="cancel" value=" <?php echo _AC('cancel'); ?> " />
					</p>
				</td>
			</tr>
		</table>
	</form>

</fieldset>
</div>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>