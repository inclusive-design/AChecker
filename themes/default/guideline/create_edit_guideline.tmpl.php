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

<form name="input_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?><?php if (isset($_GET["id"])) echo '?id='.$_GET["id"]; ?>" >

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
			<td><textarea cols="3" name="long_name" id="long_name"><?php if (isset($_POST['long_name'])) echo $_POST['long_name']; else echo _AC($this->row["long_name"]); ?></textarea></td>
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
				<input type="radio" name="status" id="statusD" value="0" <?php if ((isset($_POST['status']) && $_POST['status']==0) || (!isset($_POST['status']) && $this->row['status']==0)) echo 'checked="checked"'; ?> /><label for="statusD"><?php echo _AC('disabled'); ?></label> 
				<input type="radio" name="status" id="statusE" value="1" <?php if ((isset($_POST['status']) && $_POST['status']==1) || (!isset($_POST['status']) && $this->row['status']==1)) echo 'checked="checked"'; ?> /><label for="statusE"><?php echo _AC('enabled'); ?></label>
			</td>
		</tr>
			
		<?php if ($this->is_admin) {?>
		<tr>
			<th align="left"><? echo _AC("open_to_public"); ?></th>
			<td>
				<input type="radio" name="open_to_public" id="open_to_publicN" value="0" <?php if ((isset($_POST['open_to_public']) && $_POST['open_to_public']==0) || (!isset($_POST['open_to_public']) && $this->row['open_to_public']==0)) echo 'checked="checked"'; ?> /><label for="open_to_publicN"><?php echo _AC('no'); ?></label> 
				<input type="radio" name="open_to_public" id="open_to_publicY" value="1" <?php if ((isset($_POST['open_to_public']) && $_POST['open_to_public']==1) || (!isset($_POST['open_to_public']) && $this->row['open_to_public']==1)) echo 'checked="checked"'; ?> /><label for="open_to_publicY"><?php echo _AC('yes'); ?></label>
			</td>
		</tr>
		<?php } else {?>
		<tr>
			<td><input type="hidden" name="open_to_public" value="<?php if (isset($this->row["open_to_public"])) echo $this->row["open_to_public"]; else echo "0"; ?>" /></td>
		</tr>
		<?php }?>
	</table>
		
	
	<!-- section of displaying existing checks in current guideline -->
	<?php if (is_array($this->checks_rows)) { ?>
		<h2><?php echo _AC('checks');?></h2>
		<table class="data" summary="" rules="rows" >
			<thead>
			<tr>
				<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all" title="<?php echo _AC('select_all'); ?>" name="selectall_delchecks" onclick="CheckAll('del_checks_id[]','selectall_delchecks');" /></th>
				<th align="center"><?php echo _AC('html_tag'); ?></th>
				<th align="center"><?php echo _AC('error_type'); ?></th>
				<th align="center"><?php echo _AC('description'); ?></th>
			</tr>
			</thead>
			
			<tfoot>
				<tr>
					<td colspan="4">
						<input type="submit" name="remove" value="<?php echo _AC('remove'); ?>" />
					</td>
				</tr>
			</tfoot>

			<tbody>
	<?php foreach ($this->checks_rows as $checks_row) { ?>
			<tr onmousedown="document.input_form['del_checks_<?php echo $checks_row['check_id']; ?>'].checked = !document.input_form['del_checks_<?php echo $checks_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_checks_<?php echo $checks_row['check_id']; ?>');" id="rdel_checks_<?php echo $checks_row['check_id']; ?>">
				<td><input type="checkbox" name="del_checks_id[]" value="<?php echo $checks_row['check_id']; ?>" id="del_checks_<?php echo $checks_row['check_id']; ?>" onmouseup="this.checked=!this.checked" <?php if (is_array($_POST['del_checks_id']) && in_array($checks_row['check_id'], $_POST['del_checks_id'])) echo 'checked="checked"';?> /></td>
				<td><?php echo $checks_row['html_tag']; ?></td>
				<td><?php echo get_confidence_by_code($checks_row['confidence']); ?></td>
				<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $checks_row["check_id"]; ?>" onclick="popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $checks_row["check_id"]; ?>'); return false;"><?php echo _AC($checks_row['name']); ?></a></span></td>
			</tr>
	<?php } // end of foreach?>
			</tbody>
		</table>
	<?php } ?>

	<!-- section of displaying checks to add -->
	<div class="row">
		<h2>
			<img src="images/arrow-closed.png" alt="<?php echo _AC("expand_add_checks"); ?>" title="<?php echo _AC("expand_add_checks"); ?>" id="toggle_image" border="0" />
			<a href="javascript:toggleToc('div_add_checks')"><?php echo _AC("add_checks"); ?></a>
		</h2>
	</div>
	
	<div id="div_add_checks">
	<?php 
	if (!is_array($this->checks_to_add_rows)){ 
		echo _AC('none_found');
	} 
	else {?>
		<table class="data" summary="" rules="rows" >
			<thead>
			<tr>
				<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all" title="<?php echo _AC('select_all'); ?>" name="selectall_addchecks" onclick="CheckAll('add_checks_id[]','selectall_addchecks');" /></th>
				<th align="center"><?php echo _AC('html_tag'); ?></th>
				<th align="center"><?php echo _AC('error_type'); ?></th>
				<th align="center"><?php echo _AC('description'); ?></th>
			</tr>
			</thead>
			
			<tbody>
	<?php foreach ($this->checks_to_add_rows as $checks_to_add_row) { ?>
			<tr onmousedown="document.input_form['add_checks_<?php echo $checks_to_add_row['check_id']; ?>'].checked = !document.input_form['add_checks_<?php echo $checks_to_add_row['check_id']; ?>'].checked; togglerowhighlight(this, 'add_checks_<?php echo $checks_to_add_row['check_id']; ?>');" id="radd_checks_<?php echo $checks_to_add_row['check_id']; ?>">
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
//<!--

function initial()
{
	// hide guideline div
	document.getElementById("div_add_checks").style.display = 'none';

	// set cursor focus
	document.input_form.title.focus();
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
