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
$onload = "initial();";

include(AC_INCLUDE_PATH.'header.inc.php');
?>

<form name="input_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?><?php if (isset($_GET["id"])) echo '?id='.$_GET["id"]; ?>" >
<?php if (isset($this->row["language_code"])) {?>
<input type="hidden" name="language_code" value="<?php echo $this->row["language_code"]; ?>" />
<input type="hidden" name="charset" value="<?php echo $this->row["charset"]; ?>" />
<?php }?>

<div class="center-input-form">

<fieldset class="group_form"><legend class="group_form"><?php echo _AC('add_edit_language'); ?></legend>
	<table class="form-data" align="center">
		<tr>
			<td colspan="2" align="left"><?php echo _AC('required_field_text') ;?><br /><br /></td>
		</tr>

		<tr align="left">
			<th><div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
			<label for="lang_code"><?php echo _AC('lang_code'); ?></label></th>
			<td>
<?php if (isset($this->row['language_code'])) echo $this->row['lang_code']; else {?>
			<select name="lang_code" id="lang_code">
				<option value="-1">-- <?php echo _AC('select');?> --</option>
<?php 
	foreach ($this->rows_lang as $row_lang)
	{
?>
				<option value="<?php echo $row_lang['code_3letters']; ?>" <?php if ((isset($_POST["lang_code"]) && $_REQUEST["lang_code"] == $row_lang['code_3letters']) || (!isset($_REQUEST["lang_code"]) && $this->row["lang_code"] == $row_lang['code_3letters'])) echo 'selected="selected"'; ?>><?php echo $row_lang["description"]. ' - '. $row_lang['code_3letters']; ?></option>
<?php
	}
?>
			</select>
<?php }?>
			</td>
		</tr>

		<tr align="left">
			<th><label for="locale">&nbsp;&nbsp;&nbsp;<?php echo _AC('locale'); ?></label></th>
			<td>
<?php if (isset($this->row['language_code'])) if ($this->row['locale'] == '') echo _AC('na'); else echo $this->row['locale']; else {?>
				<input id="locale" name="locale" type="text" size="2" maxlength="2" value="<?php if (isset($_POST['locale'])) echo $_POST['locale']; else echo $this->row['locale']; ?>" />
<?php }?>
			</td>
		</tr>

		<tr align="left">
			<th><div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
			<label for="charset"><?php echo _AC('charset'); ?></label></th>
			<td>
<?php if (isset($this->row['language_code'])) echo $this->row['charset']; else {?>
				<input type="text" name="charset" id="charset" value="<?php if (isset($_POST['charset'])) echo $_POST['charset']; else if (isset($this->row["charset"])) echo $this->row["charset"]; else echo DEFAULT_CHARSET; ?>" />
<?php }?>
			</td>
		</tr>

		<tr align="left">
			<th><div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
			<label for="native_name"><?php echo _AC('name_in_language'); ?></label></th>
			<td><input type="text" name="native_name" id="native_name" value="<?php if (isset($_POST['native_name'])) echo $_POST['native_name']; else echo $this->row["native_name"]; ?>" /></td>
		</tr>

		<tr align="left">
			<th><div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
			<label for="english_name"><?php echo _AC('name_in_english'); ?></label></th>
			<td><input type="text" name="english_name" id="english_name" value="<?php if (isset($_POST['english_name'])) echo $_POST['english_name']; else echo $this->row["english_name"]; ?>" /></td>
		</tr>

		<tr align="left">
			<th>&nbsp;&nbsp;&nbsp;<?php echo _AC("status"); ?></th>
			<td>
				<input type="radio" name="status" id="statusD" value="0" <?php if ((isset($_POST['status']) && $_POST['status']==0) || (!isset($_POST['status']) && $this->row['status']==0)) echo 'checked="checked"'; ?> /><label for="statusD"><?php echo _AC('disabled'); ?></label> 
				<input type="radio" name="status" id="statusE" value="1" <?php if ((isset($_POST['status']) && $_POST['status']==1) || (!isset($_POST['status']) && $this->row['status']==1) || (!isset($_POST['status']) && !isset($this->row['status']))) echo 'checked="checked"'; ?> /><label for="statusE"><?php echo _AC('enabled'); ?></label>
			</td>
		</tr>

		<tr>
			<td colspan="2">
			<p class="submit_button">
			<input type="submit" name="save" value="<?php echo _AC('save'); ?>" />
			<input type="submit" name="cancel" value="<?php echo _AC('cancel'); ?>" />
			</p>
			</td>
		</tr>
	</table>
	
</fieldset>
</div>
</form>

<script type="text/JavaScript">
//<!--

function initial()
{
	// set cursor focus
	document.input_form.lang_code.focus();
}

function CheckAll(element_name, selectall_checkbox_name) {
	for (var i=0;i<document.input_form.elements.length;i++)	{
		var e = document.input_form.elements[i];
		if ((e.name == element_name) && (e.type=='checkbox')) {
			e.checked = document.input_form[selectall_checkbox_name].checked;
			togglerowhighlight(document.getElementById("r" + e.id), e.id);
		}
	}
}

function togglerowhighlight(obj, boxid) {
	if (document.getElementById(boxid).checked) {
		obj.className = 'selected';
	} else {
		obj.className = '';
	}
}
//  End -->
//-->
</script>

<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>
