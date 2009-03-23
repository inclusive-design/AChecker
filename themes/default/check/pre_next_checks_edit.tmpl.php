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
 * Called by "register.php" and "user/user_create_edit.php
 * 
 * Accept parameters:
 * 
 * check_row: only need when edit existing user.
 * all_html_tags: display selections in dropdown list box "HTML Tag"
 */

require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<form method="post" action="<?php $id_str = ''; if (isset($_GET['id'])) $id_str='?id='.$_GET['id']; echo $_SERVER['PHP_SELF'].$id_str; ?>" name="input_form">

<div class="input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC('edit_pre_next_checks'); ?></legend>

	<h2 id="#pre_checks">
		<?php echo _AC('pre_checks');?>
		<input type="button" name="add_pre_checks" value="<?php echo _AC('add_pre_checks'); ?>" 
		       onclick="popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=pre&cid=<?php echo $_GET['id']; ?>'); return false;" />
	</h2>
	<table class="data" summary="" rules="rows" >
		<thead>
		<tr>
			<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all" title="<?php echo _AC('select_all'); ?>" name="selectall_delprechecks" onclick="CheckAll('del_pre_checks_id[]','selectall_delprechecks');" /></th>
			<th align="center"><?php echo _AC('html_tag'); ?></th>
			<th align="center"><?php echo _AC('error_type'); ?></th>
			<th align="center"><?php echo _AC('description'); ?></th>
		</tr>
		</thead>
	<?php if (is_array($this->pre_rows)) { ?>
			
		<tfoot>
			<tr>
				<td colspan="4">
					<input type="submit" name="remove_pre" value="<?php echo _AC('remove'); ?>" />
				</td>
			</tr>
		</tfoot>

		<tbody>
	<?php foreach ($this->pre_rows as $pre_row) { ?>
		<tr onmousedown="document.input_form['del_pre_checks_<?php echo $pre_row['check_id']; ?>'].checked = !document.input_form['del_pre_checks_<?php echo $pre_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_pre_checks_<?php echo $pre_row['check_id']; ?>');" id="rdel_pre_checks_<?php echo $pre_row['check_id']; ?>">
			<td><input type="checkbox" name="del_pre_checks_id[]" value="<?php echo $pre_row['check_id']; ?>" id="del_pre_checks_<?php echo $pre_row['check_id']; ?>" onmouseup="this.checked=!this.checked" <?php if (is_array($_POST['del_pre_checks_id']) && in_array($pre_row['check_id'], $_POST['del_pre_checks_id'])) echo 'checked="checked"';?> /></td>
			<td><?php echo $pre_row['html_tag']; ?></td>
			<td><?php echo get_confidence_by_code($pre_row['confidence']); ?></td>
			<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $pre_row["check_id"]; ?>" onclick="popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $pre_row["check_id"]; ?>'); return false;"><?php echo _AC($pre_row['name']); ?></a></span></td>
		</tr>
	<?php } // end of foreach?>
	<?php } else {// end of if?>
		<tr><td colspan="4"><?php echo _AC('none_found'); ?></td></tr>
	<?php }?>
		</tbody>
	</table>

	<!-- section of displaying existing next checks -->
	<br/>
	<h2 id="#next_checks">
		<?php echo _AC('next_checks');?>
		<input type="button" name="add_next_checks" value="<?php echo _AC('add_next_checks'); ?>" 
		       onclick="popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=next&cid=<?php echo $_GET['id']; ?>'); return false;" />
	</h2>
	<table class="data" summary="" rules="rows" >
		<thead>
		<tr>
			<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all" title="<?php echo _AC('select_all'); ?>" name="selectall_delnextchecks" onclick="CheckAll('del_next_checks_id[]','selectall_delnextchecks');" /></th>
			<th align="center"><?php echo _AC('html_tag'); ?></th>
			<th align="center"><?php echo _AC('error_type'); ?></th>
			<th align="center"><?php echo _AC('description'); ?></th>
		</tr>
		</thead>
	<?php if (is_array($this->next_rows)) { ?>
			
		<tfoot>
			<tr>
				<td colspan="4">
					<input type="submit" name="remove_next" value="<?php echo _AC('remove'); ?>" />
				</td>
			</tr>
		</tfoot>

		<tbody>
	<?php foreach ($this->next_rows as $next_row) { ?>
		<tr onmousedown="document.input_form['del_next_checks_<?php echo $next_row['check_id']; ?>'].checked = !document.input_form['del_next_checks_<?php echo $next_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_next_checks_<?php echo $next_row['check_id']; ?>');" id="rdel_next_checks_<?php echo $next_row['check_id']; ?>">
			<td><input type="checkbox" name="del_next_checks_id[]" value="<?php echo $next_row['check_id']; ?>" id="del_next_checks_<?php echo $next_row['check_id']; ?>" onmouseup="this.checked=!this.checked" <?php if (is_array($_POST['del_next_checks_id']) && in_array($next_row['check_id'], $_POST['del_next_checks_id'])) echo 'checked="checked"';?> /></td>
			<td><?php echo $next_row['html_tag']; ?></td>
			<td><?php echo get_confidence_by_code($next_row['confidence']); ?></td>
			<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $next_row["check_id"]; ?>" onclick="popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $next_row["check_id"]; ?>'); return false;"><?php echo _AC($next_row['name']); ?></a></span></td>
		</tr>
	<?php } // end of foreach?>
	<?php } else {// end of if?>
		<tr><td colspan="4"><?php echo _AC('none_found'); ?></td></tr>
	<?php }?>
		</tbody>
	</table>
</fieldset>

</div>
</form>

<script type="text/JavaScript">
//<!--
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

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>