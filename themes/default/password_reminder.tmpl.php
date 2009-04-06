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
$onload = 'document.form.form_email.focus();';

require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<div class="center-input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC('password_reminder'); ?></legend>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
		<input type="hidden" name="form_password_reminder" value="true" />

		<table class="form-data" align="center" width="60%">
			<tr>
				<td colspan="2" align="left"><?php echo _AC('password_blurb'); ?></td>
			</tr>
			
			<tr><td><br /></td></tr>

			<tr>
				<td align="left">
					<div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
					<label for="email"><?php echo _AC('email_address'); ?></label>:
				</td>
				<td align="left">
					<input type="text" name="form_email" id="email" size="60" />
				</td>
			</tr>
		
			<tr>
				<td colspan="2">
					<p class="submit_button">
						<input type="submit" name="submit" value="<?php echo _AC('submit'); ?>" onclick="encrypt_password()" />
						<input type="submit" name="cancel" value=" <?php echo _AC('cancel'); ?> " />
					</p>
				</td>
			</tr>
		</table>
	</form>

</fieldset>
</div>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>