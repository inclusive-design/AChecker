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
<script type='text/javascript' src='jscripts/calendar.js'></script>

<form name="input_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >

<div class="input-form">
	<table>
		<tr>
			<td colspan="2" align="left"><p><?php echo _AC('required_field_text') ;?><br /><br /></p></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="title"><?php echo _AC('title'); ?></label></th>
			<td><input type="text" name="title" size="100" id="title" value="<?php if (isset($_POST['title'])) echo $_POST['title']; else echo $this->row["title"]; ?>" /></td>
		</tr>

		<tr>
			<th align="left"><label for="abbr"><?php echo _AC('abbr'); ?></label></th>
			<td><input type="text" name="abbr" size="100" id="abbr" value="<?php if (isset($_POST['abbr'])) echo $_POST['abbr']; else echo $this->row["abbr"]; ?>" /></td>
		</tr>

		<tr>
			<th align="left"><label for="long_name"><?php echo _AC('long_name'); ?></label></th>
			<td><textarea cols="3" name="long_name" id="long_name"><?php if (isset($_POST['long_name'])) echo $_POST['long_name']; else echo $this->row["long_name"]; ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="published_date"><?php echo _AC('published_date').'<br />('._AC("yyyy-mm-dd").')'; ?></label></th>
			<td>
				<input type="text" name="published_date" id="published_date" value="<?php if (isset($_POST['published_date'])) echo $_POST['published_date']; else echo $this->row["published_date"]; ?>" />
				<img src="images/calendar.gif" style="vertical-align: middle; cursor: pointer;" onclick="scwShow(scwID('published_date'),event);" />
			</td>
		</tr>

		<tr>
			<th align="left"><label for="earlid"><?php echo _AC('earlid'); ?></label></th>
			<td><input type="text" name="earlid" size="100" id="earlid" value="<?php if (isset($_POST['earlid'])) echo $_POST['earlid']; else echo $this->row["earlid"]; ?>" /></td>
		</tr>

		<tr>
			<th align="left"><? echo _AC("status"); ?></th>
			<td>
				<input type="radio" name="status" id="statusD" value="0" <?php if ((isset($_POST['status']) && $_POST['status']==0) || (!isset($_POST['status']) && $row['status']==0)) echo 'checked="checked"'; ?> /><label for="statusD"><?php echo _AC('disabled'); ?></label> 
				<input type="radio" name="status" id="statusE" value="1" <?php if ((isset($_POST['status']) && $_POST['status']==1) || (!isset($_POST['status']) && $row['status']==1)) echo 'checked="checked"'; ?> /><label for="statusE"><?php echo _AC('enabled'); ?></label>
			</td>
		</tr>
			
	</table>
	
	<div class="row">
		<h2>
			<img src="images/arrow-closed.png" alt="<?php echo _AC("expand_add_checks"); ?>" title="<?php echo _AC("expand_add_checks"); ?>" id="toggle_image" border="0" />
			<a href="javascript:toggleToc('div_add_checks')"><?php echo _AC("add_checks"); ?></a>
		</h2>
	</div>
	
	<!-- section of adding checks -->
	<div id="div_add_checks">
	<?php 
	if (!is_array($this->checks_to_add_rows)){ 
		echo _AC('none_found');
	} 
	else {?>
		<table class="data" summary="" rules="rows" >
			<thead>
			<tr>
				<th>&nbsp;</th>
				<th align="center"><?php echo _AC('html_tag'); ?></th>
				<th align="center"><?php echo _AC('error_type'); ?></th>
				<th align="center"><?php echo _AC('description'); ?></th>
			</tr>
			</thead>
			
			<tbody>
	<?php foreach ($this->checks_to_add_rows as $checks_to_add_row) { ?>
			<tr onmousedown="document.input_form['add_checks_<?php echo $checks_to_add_row['check_id']; ?>'].checked = !document.input_form['add_checks_<?php echo $checks_to_add_row['check_id']; ?>'].checked; togglerowhighlight(this, 'add_checks_<?php echo $checks_to_add_row['check_id']; ?>');" id="c_add_checks_<?php echo $checks_to_add_row['check_id']; ?>">
				<td><input type="checkbox" name="add_checks_id[]" value="<?php echo $checks_to_add_row['check_id']; ?>" id="add_checks_<?php echo $checks_to_add_row['check_id']; ?>" onmouseup="this.checked=!this.checked" <?php if (is_array($_POST['add_checks_id']) && in_array($checks_to_add_row['check_id'], $_POST['add_checks_id'])) echo 'checked="checked"';?> /></td>
				<td><?php echo $checks_to_add_row['html_tag']; ?></td>
				<td><?php echo get_confidence_by_code($checks_to_add_row['confidence']); ?></td>
				<td><span class="msg"><a target="_blank" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $checks_to_add_row["check_id"]; ?>"><?php echo _AC($checks_to_add_row['name']); ?></a></span></td>
			</tr>
	<?php } // end of foreach?>
			</tbody>
		</table>
	<?php } // end of if?>
	</div>
	
	<div class="row">
		<input type="submit" name="save" value="<?php echo _AC('save'); ?>" />
	</div>
</div>
</form>
	<script type="text/JavaScript">
<!--

function initial()
{
	// hide guideline div
	document.getElementById("div_add_checks").style.display = 'none';
	
	document.input_form.title.focus();
}

//  End -->
//-->
</script>

<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>
