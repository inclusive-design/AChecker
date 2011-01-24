<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

global $onload;
$onload = "initial();";

include(AC_INCLUDE_PATH.'header.inc.php');
?>

<form name="input_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?><?php if (isset($_GET["id"])) echo '?id='.$_GET["id"]; ?>" >
<?php if (isset($this->user_group_row["user_group_id"])) {?>
<input type="hidden" name="user_group_id" value="<?php echo $this->user_group_row["user_group_id"]; ?>" />
<?php }?>

<div class="input-form">

<fieldset class="group_form"><legend class="group_form"><?php echo _AC('create_edit_user_group'); ?></legend>
	<table class="form-data">
		<tr>
			<td colspan="2" align="left"><?php echo _AC('required_field_text') ;?><br /><br /></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="title"><?php echo _AC('title'); ?></label></th>
			<td><input type="text" name="title" size="100" id="title" value="<?php if (isset($_POST['title'])) echo htmlspecialchars($_POST['title']); else echo htmlspecialchars($this->user_group_row["title"]); ?>" /></td>
		</tr>

		<tr>
			<th align="left"><label for="description"><?php echo _AC('description'); ?></label></th>
			<td><textarea rows="3" cols="30" name="description" id="description"><?php if (isset($_POST['description'])) echo htmlspecialchars($_POST['description']); else echo htmlspecialchars($this->user_group_row["description"]); ?></textarea></td>
		</tr>

		<?php if (isset($this->user_group_row['user_group_id'])) {?>
		<tr>
			<th align="left"><?php echo _AC('date_created'); ?></th>
			<td>
				<?php echo $this->user_group_row['create_date']; ?>
			</td>
		</tr>

		<tr>
			<th align="left"><?php echo _AC('last_update'); ?></th>
			<td>
				<?php echo $this->user_group_row['last_update']; ?>
			</td>
		</tr>
		<?php }?>
	</table>
	<br />
	
	<!-- section of displaying existing checks in current guideline -->
	<?php if (is_array($this->privs_rows)) { ?>
		<h2><?php echo _AC('privileges');?></h2>
		<table class="data" summary="" rules="rows" >
			<thead>
			<tr>
				<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all_del" title="<?php echo _AC('select_all'); ?>" name="selectall_delprivileges" onclick="CheckAll('del_privileges_id[]','selectall_delprivileges');" /></th>
				<th><?php echo _AC('privileges'); ?></th>
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
	<?php foreach ($this->privs_rows as $privs_row) { ?>
			<tr onmousedown="document.input_form['del_privileges_<?php echo $privs_row['privilege_id']; ?>'].checked = !document.input_form['del_privileges_<?php echo $privs_row['privilege_id']; ?>'].checked; togglerowhighlight(this, 'del_privileges_<?php echo $privs_row['privilege_id']; ?>');" 
			    onkeydown="document.input_form['del_privileges_<?php echo $privs_row['privilege_id']; ?>'].checked = !document.input_form['del_privileges_<?php echo $privs_row['privilege_id']; ?>'].checked; togglerowhighlight(this, 'del_privileges_<?php echo $privs_row['privilege_id']; ?>');"
			    id="rdel_privileges_<?php echo $privs_row['privilege_id']; ?>">
				<td><input type="checkbox" name="del_privileges_id[]" value="<?php echo $privs_row['privilege_id']; ?>" id="del_privileges_<?php echo $privs_row['privilege_id']; ?>" 
				           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" 
				           <?php if (is_array($_POST['del_privileges_id']) && in_array($privs_row['privilege_id'], $_POST['del_privileges_id'])) echo 'checked="checked"';?> /></td>
				<td><label for="del_privileges_<?php echo $privs_row['privilege_id']; ?>"><?php echo htmlspecialchars($privs_row['description']); ?></label></td>
			</tr>
	<?php } // end of foreach?>
			</tbody>
		</table>
	<?php } ?>

	<!-- section of displaying privileges to add -->
	<div class="row">
		<h2>
			<img src="images/arrow-closed.png" alt="<?php echo _AC("expand_add_privileges"); ?>" title="<?php echo _AC("expand_add_privileges"); ?>" id="toggle_image" border="0" />
			<a href="javascript:toggleToc('div_add_privs')"><?php echo _AC("add_privileges"); ?></a>
		</h2>
	</div>
	
	<div id="div_add_privs">
	<?php 
	if (!is_array($this->privs_to_add_rows)){ 
		echo _AC('none_found');
	} 
	else {?>
		<table class="data" summary="" rules="rows" >
			<thead>
			<tr>
				<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all_add" title="<?php echo _AC('select_all'); ?>" name="selectall_addprivileges" onclick="CheckAll('add_privileges_id[]','selectall_addprivileges');" /></th>
				<th><?php echo _AC('privileges'); ?></th>
			</tr>
			</thead>
			
			<tbody>
	<?php foreach ($this->privs_to_add_rows as $privileges_to_add_row) { ?>
			<tr onmousedown="document.input_form['add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>'].checked = !document.input_form['add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>'].checked; togglerowhighlight(this, 'add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>');" 
			    onkeydown="document.input_form['add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>'].checked = !document.input_form['add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>'].checked; togglerowhighlight(this, 'add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>');"
			    id="radd_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>">
				<td><input type="checkbox" name="add_privileges_id[]" value="<?php echo $privileges_to_add_row['privilege_id']; ?>" id="add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>" 
				           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" 
				           <?php if (is_array($_POST['add_privileges_id']) && in_array($privileges_to_add_row['privilege_id'], $_POST['add_privileges_id'])) echo 'checked="checked"';?> /></td>
				<td><label for="add_privileges_<?php echo $privileges_to_add_row['privilege_id']; ?>"><?php echo htmlspecialchars($privileges_to_add_row['description']); ?></label></td>
			</tr>
	<?php } // end of foreach?>
			</tbody>
		</table>
	<?php } // end of if?>
	</div>
	
	<div class="row">
		<input type="submit" name="save" value="<?php echo _AC('save'); ?>" />
		<input type="submit" name="cancel" value="<?php echo _AC('cancel'); ?>" />
	</div>
</fieldset>
</div>
</form>

<script type="text/JavaScript">
//<!--

function initial()
{
	// hide guideline div
	document.getElementById("div_add_privs").style.display = 'none';

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
