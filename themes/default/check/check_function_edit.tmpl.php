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

/*
 * Called by "check/index.php" and "check/pre_next_checks_edit.php
 * 
 * Accept parameters:
 * 
 * check_row: only need when edit existing user.
 * all_html_tags: display selections in dropdown list box "HTML Tag"
 */

global $onload;
$onload = "initial();";

require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<form method="post" action="<?php $id_str = ''; if (isset($_GET['id'])) $id_str='?id='.$_GET['id']; echo $_SERVER['PHP_SELF'].$id_str; ?>" name="input_form">

<div class="input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC('edit_function'); ?></legend>

	<table class="form-data">
		<tr>
			<th align="left" width="20%"><?php echo _AC('html_tag'); ?>:</th>
			<td align="left"><?php echo $this->check_row['html_tag']; ?></td>
		</tr>

		<tr>
			<th align="left"><?php echo _AC('error_type'); ?>:</th>
			<td align="left"><?php echo get_confidence_by_code($this->check_row['error_type']); ?></td>
		</tr>

		<tr>
			<th align="left"><?php echo _AC('name'); ?>:</th>
			<td align="left"><?php echo _AC($this->check_row['name']); ?></td>
		</tr>

		<tr>
			<th align="left" colspan="2"><label for="func"><?php echo _AC('function'); ?></label>:</th>
		</tr>
		
		<tr>
			<td align="left" colspan="2"><textarea rows="15" cols="120" name="func" id="func"><?php if (isset($_POST['func'])) echo $_POST['func']; else echo $this->check_row["func"]; ?></textarea></td>
		</tr>

	</table>

	<div class="row">
		<input type="submit" name="save" value="<?php echo _AC('save'); ?>" class="submit" /> 
		<input type="submit" name="save_and_close" value="<?php echo _AC('save_and_close'); ?>" class="submit" /> 
		<input type="submit" name="cancel" value=" <?php echo _AC('cancel'); ?> "  class="submit" />
	</div>
</fieldset>

</div>
</form>

<script type="text/JavaScript">
//<!--
function initial()
{
	// set cursor focus
	document.input_form.func.focus();
}

//  End -->
//-->
</script>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>