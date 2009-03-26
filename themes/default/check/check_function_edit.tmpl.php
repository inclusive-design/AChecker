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
			<th align="left"><label for="func"><?php echo _AC('function'); ?></label>:</th>
		</tr>
		
		<tr>
			<td align="left"><textarea rows="25" cols="135" name="func" id="func"><?php if (isset($_POST['func'])) echo $_POST['func']; else echo $this->check_row["func"]; ?></textarea></td>
		</tr>

	</table>

	<div class="row">
		<input type="submit" name="submit" value="<?php echo _AC('submit'); ?>" class="submit" /> 
		<input type="submit" name="submit_and_close" value="<?php echo _AC('submit_and_close'); ?>" class="submit" /> 
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