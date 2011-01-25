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

require(AC_INCLUDE_PATH.'header.inc.php');
?>
<div class="output-form">
<form name="form" action="<?php echo $_SERVER['PHP_SELF']?>">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC("html_tag_list"); ?></legend>
<table summary="" class="data" rules="rows" style="width:60%;">
<thead>
	<tr>
		<th scope="col">&nbsp;</th>
		<th scope="col"><?php echo _AC('html_tag');?></th>
	</tr>
</thead>

<tfoot>
	<tr>
		<td colspan="2">
			<input type="submit" name="<?php echo _AC('select'); ?>" value="<?php echo _AC('select'); ?>" onclick="insertIntoParentWindow();"/>
		</td>
	</tr>
</tfoot>

<tbody>
<?php foreach ($this->all_html_tags as $row) { $html_tag_no_space = str_replace(' ', '', $row['html_tag']); ?>
	<tr onmousedown="document.form['m<?php echo $html_tag_no_space; ?>'].checked = true; rowselect(this);" 
	    onkeydown="document.form['m<?php echo $html_tag_no_space; ?>'].checked = true; rowselect(this);"
	    id="r_<?php echo $html_tag_no_space; ?>">
		<td><input type="radio" name="html_tag" value="<?php echo $row["html_tag"]; ?>" id="m<?php echo $html_tag_no_space; ?>" 
		           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
		<td><label for="m<?php echo $html_tag_no_space; ?>"><?php echo htmlspecialchars($row["html_tag"]); ?></label></td>
	</tr>
<?php } ?>
</tbody>
</table>
</fieldset>
</form>
</div>

<script type="text/javascript">
//<!--
function insertIntoParentWindow()
{
	var htmltagObj = document.form.html_tag;

	for(var i = 0; i < htmltagObj.length; i++) {
		if(htmltagObj[i].checked) {
			window.opener.document.getElementById('html_tag').value = htmltagObj[i].value;
			self.close();
			return true;
		}
	}
}
//-->
</script>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>