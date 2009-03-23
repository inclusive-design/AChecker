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

require(AC_INCLUDE_PATH.'header.inc.php');
?>
<form name="form">
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
<?php foreach ($this->all_html_tags as $row) {?>
	<tr onmousedown="document.form['m<?php echo $row["html_tag"]; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row["html_tag"]; ?>">
		<td><input type="radio" name="html_tag" value="<?php echo $row["html_tag"]; ?>" id="m<?php echo $row['html_tag']; ?>" onmouseup="this.checked=!this.checked" /></td>
		<td><label for="m<?php echo $row["html_tag"]; ?>"><?php echo $row["html_tag"]; ?></label></td>
	</tr>
<?php } ?>
</tbody>
</table>
</form>

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