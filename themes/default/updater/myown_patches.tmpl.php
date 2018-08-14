<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<div class="center-input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC("myown_updates"); ?></legend>

<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table summary="" class="data" rules="rows">

<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><?php echo _AC('achecker_update_id'); ?></th>
	<th scope="col"><?php echo _AC('achecker_version_to_apply'); ?></th>
	<th scope="col"><?php echo _AC('description'); ?></th>
	<th scope="col"><?php echo _AC('last_modified'); ?></th>
</tr>
</thead>
<?php if (!is_array($patch_rows)) { ?>
<tbody>
	<tr>
		<td colspan="5"><?php echo _AC('none_found'); ?></td>
	</tr>
</tbody>
<?php } else { ?>
<tfoot>
<tr>
	<td colspan="5">
		<div class="row buttons">
		<input type="submit" name="edit" value="<?php echo _AC('edit'); ?>" /> 
		<input type="submit" name="remove" value="<?php echo _AC('remove'); ?>" /> 
		</div>
	</td>
</tr>
</tfoot>
<tbody>
<?php foreach ($patch_rows as $row) { ?>
		<tr onmousedown="document.form['m<?php echo $row['myown_patch_id']; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row['myown_patch_id']; ?>">
			<td width="10"><input type="radio" name="myown_patch_id" value="<?php echo $row['myown_patch_id']; ?>" id="m<?php echo $row['myown_patch_id']; ?>" <?php if ($row['myown_patch_id']==$_POST['myown_patch_id']) echo 'checked'; ?> /></td>
			<td><label for="m<?php echo $row['myown_patch_id']; ?>"><?php echo htmlspecialchars($row['achecker_patch_id']); ?></label></td>
			<td><?php echo htmlspecialchars($row['applied_version']); ?></td>
			<td><?php echo htmlspecialchars($row['description']); ?></td>
			<td><?php echo htmlspecialchars($row['last_modified']); ?></td>
		</tr>
<?php } // end of foreach ?>
</tbody>
<?php } // end of else ?>

</table>

</form>

</fieldset>
</div>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>
