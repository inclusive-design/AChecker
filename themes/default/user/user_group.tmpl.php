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

include(AC_INCLUDE_PATH.'header.inc.php');
?>

<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<table summary="" class="data" rules="rows">
	<thead>
	<tr>
		<th scope="col" align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all" title="<?php echo _AC('select_all'); ?>" name="selectall" onclick="CheckAll();" /></th>
	
		<th scope="col"><?php echo _AC('title'); ?></th>
		<th scope="col"><?php echo _AC('description'); ?></th>
		<th scope="col"><?php echo _AC('privileges'); ?></th>
	</tr>
	
	</thead>
<?php if (is_array($this->user_group_rows)): ?>
	<tfoot>
	<tr>
		<td colspan="4">
			<input type="submit" name="edit" value="<?php echo _AC('edit'); ?>" /> 
			<input type="submit" name="delete" value="<?php echo _AC('delete'); ?>" />
		</td>
	</tr>
	</tfoot>
	<tbody>
		<?php foreach ($this->user_group_rows as $row) 
			{
			// get privileges
			$privileges = $this->privilegesDAO->getUserGroupPrivileges($row['user_group_id']);
			
			if (is_array($privileges))
			{
				$priv_str = '<ul>';
				foreach ($privileges as $priv)	$priv_str .= '<li>'. $priv['privilege_desc'].'</li>';
				$priv_str .= '</ul>';
			}
		?>
			<tr onmousedown="document.form['m<?php echo $row['user_group_id']; ?>'].checked = !document.form['m<?php echo $row['user_group_id']; ?>'].checked; togglerowhighlight(this, 'm<?php echo $row['user_group_id']; ?>');" 
			    onkeydown="document.form['m<?php echo $row['user_group_id']; ?>'].checked = !document.form['m<?php echo $row['user_group_id']; ?>'].checked; togglerowhighlight(this, 'm<?php echo $row['user_group_id']; ?>');"
			    id="rm<?php echo $row['user_group_id']; ?>">
				<td><input type="checkbox" name="id[]" value="<?php echo $row['user_group_id']; ?>" id="m<?php echo $row['user_group_id']; ?>" 
				           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
				<td width='20%'><label for="m<?php echo $row['user_group_id']; ?>"><?php echo $row['title']; ?></label></td>
				<td width='30%'><?php echo $row['description']; ?></td>
				<td><?php echo $priv_str; ?></td>
			</tr>
		<?php } ?>
	</tbody>
<?php else: ?>
	<tr>
		<td colspan="4"><?php echo _AC('none_found'); ?></td>
	</tr>
<?php endif; ?>
</table>
</form>

<script language="JavaScript" type="text/javascript">
//<!--
function CheckAll() {
	for (var i=0;i<document.form.elements.length;i++)	{
		var e = document.form.elements[i];
		if ((e.name == 'id[]') && (e.type=='checkbox')) {
			e.checked = document.form.selectall.checked;
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
//-->
</script>
<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>