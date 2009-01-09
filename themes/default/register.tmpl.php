<?php 
require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<script language="JavaScript" src="sha-1factory.js" type="text/javascript"></script>

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

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
<input name="password_error" type="hidden" />
<input type="hidden" name="form_password_hidden" value="" />

<div class="center-input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC('registration'); ?></legend>

	<table align="center">
		<tr>
			<td colspan="2" align="left"><p><?php echo _AC('required_field_text') ;?><br /><br /><br /></p></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="login"><?php echo _AC('login_name'); ?></label>:</td>
			<td align="left"><input id="login" name="login" type="text" maxlength="20" size="30" value="<?php echo stripslashes(htmlspecialchars($_POST['login'])); ?>" /></td>
		</tr>

		<tr>
			<td align="left" colspan="2">
				<small>&middot; <?php echo _AC('contain_only'); ?><br />
					   &middot; <?php echo _AC('20_max_chars'); ?></small>
			</td>
		</tr>
		
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

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="email"><?php echo _AC('email_address'); ?></label>:</td>
			<td align="left"><input id="email" name="email" type="text" size="50" maxlength="50" value="<?php echo stripslashes(htmlspecialchars($_POST['email'])); ?>" /></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="first_name"><?php echo _AC('first_name'); ?></label>:</td>
			<td align="left"><input id="first_name" name="first_name" type="text" value="<?php echo stripslashes(htmlspecialchars($_POST['first_name'])); ?>" /></td>
		</tr>

		<tr>
			<td align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="last_name"><?php echo _AC('last_name'); ?></label>:</td>
			<td align="left"><input id="last_name" name="last_name" type="text" value="<?php echo stripslashes(htmlspecialchars($_POST['last_name'])); ?>" /></td>
		</tr>

		<tr>
			<td colspan="2"><p class="submit_button">
				<input type="submit" name="submit" align="center" value="<?php echo _AC('register'); ?>" class="submit" onclick="return encrypt_password();" /> 
				<input type="submit" name="cancel" value=" <?php echo _AC('cancel'); ?> "  class="submit" />
			</td>
		</tr>
	</table>
</fieldset>

</div>
</form>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>