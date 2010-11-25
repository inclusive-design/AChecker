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

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="<?php echo $this->formName; ?>">
<h2 align="center"><?php echo $this->title ;?></h2>
<div class="output-form">
<fieldset class="group_form"><legend class="group_form"><?php echo $this->title; ?></legend>
<table class="data" summary="a list of accessibility standards available" rules="rows" style="margin-top: 1em;">

<thead>
	<tr>
		<th scope="col">&nbsp;</th>
		<th scope="col"><?php echo _AC('title');?></th>
		<th scope="col"><?php echo _AC('description');?></th>
		<?php if ($this->showStatus) {?>
		<th scope="col"><?php echo _AC('status');?></th>
		<?php }?>
		<?php if ($this->isAdmin) {?>
		<th scope="col"><?php echo _AC('open_to_public');?></th>
		<?php }?>
	</tr>
</thead>

<tfoot>
	<tr>
		<td colspan="5">
			<?php foreach ($this->buttons as $button_text) {?>
			<input type="submit" name="<?php echo $button_text; ?>" value="<?php echo _AC($button_text); ?>" />
			<?php }?>
		</td>
	</tr>
</tfoot>

<tbody>
<?php foreach ($this->rows as $row) {?>
	<tr onmousedown="document.<?php echo $this->formName; ?>['m<?php echo $row["guideline_id"]; ?>'].checked = true; rowselect(this);" 
	    onkeydown="document.<?php echo $this->formName; ?>['m<?php echo $row["guideline_id"]; ?>'].checked = true; rowselect(this);"
	    id="r_<?php echo $row["guideline_id"]; ?>">
		<td><input type="radio" name="id" value="<?php echo $row["guideline_id"]; ?>" id="m<?php echo $row['guideline_id']; ?>" 
		           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
		<td><label for="m<?php echo $row["guideline_id"]; ?>"><?php echo $row["title"]; ?></label></td>
		<td><?php echo _AC($row['long_name']); ?></td>
		<?php if ($this->showStatus) {?>
		<td><?php if ($row['status']) echo _AC('enabled'); else echo _AC('disabled'); ?></td>
		<?php }?>
		<?php if ($this->isAdmin) {?>
		<td><?php if ($row['open_to_public']) echo _AC('yes'); else echo _AC('no'); ?></td>
		<?php }?>
	</tr>
<?php }?>
</tbody>

</table>
</fieldset>
</div>
</form>