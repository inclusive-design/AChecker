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

global $onload;
$onload = "initial();";

if (isset($javascript_run_now)) echo $javascript_run_now;

include(AC_INCLUDE_PATH.'header.inc.php');
?>

<form name="input_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=<?php echo $_GET["action"]; if (isset($_GET["gid"])) echo '&gid='.$_GET["gid"]; if (isset($_GET["ggid"])) echo '&ggid='.$_GET["ggid"]; if (isset($_GET["gsgid"])) echo '&gsgid='.$_GET["gsgid"]; ?>" >

<div class="input-form">

<fieldset class="group_form"><legend class="group_form"><?php echo _AC('add_group'); ?></legend>
	<table class="form-data">
		<tr>
			<td colspan="2" align="left"><?php echo _AC('required_field_text') ;?><br /><br /></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="name"><?php echo _AC('name'); ?></label></th>
			<td><textarea cols="80" rows="3" name="name" id="name"><?php if (isset($_POST['name'])) echo htmlspecialchars($_POST['name']); else echo htmlspecialchars(_AC($row['name'])); ?></textarea></td>
		</tr>
	</table>

	<div class="row">
		<input type="submit" name="submit" value="<?php echo _AC('submit'); ?>" class="submit" /> 
		<input type="button" name="cancel" value="<?php echo _AC('cancel'); ?>" onclick="javascript: self.close(); return false;" class="submit"/>
	</div>
</fieldset>
</div>
</form>

<script type="text/JavaScript">
//<!--

function initial()
{
	// set cursor focus
	document.input_form.name.focus();
}

//  End -->
//-->
</script>

<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>
