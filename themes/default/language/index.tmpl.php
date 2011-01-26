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

include(AC_INCLUDE_PATH.'header.inc.php');
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form1">
<h2 align="center"><?php echo $this->title ;?></h2>

<table class="data" summary="" rules="rows">

<thead>
	<tr>
		<th scope="col">&nbsp;</th>
		<th scope="col"><?php echo _AC('name_in_translated');?></th>
		<th scope="col"><?php echo _AC('name_in_english');?></th>
		<th scope="col"><?php echo _AC('lang_code');?></th>
		<th scope="col"><?php echo _AC('charset');?></th>
		<th scope="col"><?php echo _AC('status');?></th>
	</tr>
</thead>

<tfoot>
	<tr>
		<td colspan="6">
			<input type="submit" name="edit" value="<?php echo _AC('edit'); ?>" />
			<input type="submit" name="export" value="<?php echo _AC('export'); ?>" />
			<input type="submit" name="delete" value="<?php echo _AC('delete'); ?>" />
		</td>
	</tr>
</tfoot>

<tbody>
<?php foreach ($this->rows as $row) {?>
	<tr onmousedown="document.form1['m<?php echo $row["language_code"]."_".$row["charset"]; ?>'].checked = true; rowselect(this);" 
	    onkeydown="document.form1['m<?php echo $row["language_code"]."_".$row["charset"]; ?>'].checked = true; rowselect(this);"
	    id="r_<?php echo $row["language_code"]."_".$row["charset"]; ?>">
		<td><input type="radio" name="id" value="<?php echo $row["language_code"]."_".$row["charset"]; ?>" id="m<?php echo $row['language_code']."_".$row["charset"]; ?>" 
		           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
		<td><label for="m<?php echo $row["language_code"]."_".$row["charset"]; ?>"><?php echo htmlspecialchars($row["native_name"]); ?></label></td>
		<td><?php echo htmlspecialchars($row['english_name']); ?></td>
		<td><?php echo htmlspecialchars($row['language_code']); ?></td>
		<td><?php echo htmlspecialchars($row['charset']); ?></td>
		<td><?php if ($row['status']) echo _AC('enabled'); else echo _AC('disabled'); ?></td>
	</tr>
<?php }?>
</tbody>

</table>
</form>
<br /><br />

<form name="import_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
<div class="input-form">
	<div class="row">
		<h2><label for="file"><?php echo _AC('import_a_new_lang') ;?></label></h2>
	</div>
	
	<div class="row">
		<input type="file" name="file" id="file" size="50"/>
		<input type="submit" name="import" value="<?php echo _AC('import'); ?>" onclick="javascript: return validate_filename(); " />
	</div>
</div>
</form>

<script type="text/javascript">
<!--
// This function validates if and only if a zip file is given
function validate_filename() {
  // check file type
  var file = document.import_form.file.value;
  if (!file || file.trim()=='') {
    alert('Please select a language pack zip file.');
    return false;
  }
  
  if(file.slice(file.lastIndexOf(".")).toLowerCase() != '.zip') {
    alert('Please upload ZIP file only!');
    return false;
  }
}

//  End -->
//-->
</script>

<?php 
// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');
?>