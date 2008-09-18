<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

if (!defined("AT_INCLUDE_PATH")) die("Error: AT_INCLUDE_PATH is not defined in checker_input_form.php.");

$default_uri_value = "http://";
$default_guideline = 8;      // default guideline to check html accessibility if the guidelines are not given in $_POST
$num_of_guidelines_per_row = 3;  // default number of guidelines to display in a row on the page

if (!isset($_POST["gid"])) $_POST["gid"] = array($default_guideline);

$sql = "select guideline_id, title
				from ". TABLE_PREFIX ."guidelines
				order by title";
$result	= mysql_query($sql, $db) or die(mysql_error());
?>

<form name="input_form" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	
<div class="input-form">
	<fieldset class="group_form"><legend class="group_form">Input</legend>
		<div class="row"><h2><label for="checkuri">Check Accessibility by URI </label></h2></div>

		<div class="row">
			<input type="text" name="uri" id="checkuri" value="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $default_uri_value; ?>" size="50" />
			<p class="submit_button">
				<input type="submit" name="validate_uri" size="100" value="Check It" onclick="return validate_this_uri();" class="submit" />
			</p>
		</div>

		<div class="row"><h2><label for="checkfile">Check Accessibility by File Upload</label></h2></div>

		<div class="row">
			<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
			<input type="file" id="checkfile" name="uploadfile" size="50" />
		
			<p class="submit_button">
				<input type="submit" name="validate_file" value="Check It" onclick="return validate_filename();" class="submit" />
			</p>
		</div>

		<div class="row"><h3>Guidelines to Validate Against </h3></div>

		<table class="data static">
<?php
$count_guidelines_in_current_row = 0;

while ($row = mysql_fetch_assoc($result))
{
	if ($count_guidelines_in_current_row == 0 || $count_guidelines_in_current_row == $num_of_guidelines_per_row)
	{
		$count_guidelines_in_current_row = 0;
		echo "			<tr>\n";
	}
?>
				<td><input type="checkbox" name="gid[]" id='gid_<?php echo $row["guideline_id"]; ?>' value='<?php echo $row["guideline_id"]; ?>' <?php foreach($_POST["gid"] as $gid) if ($gid == $row["guideline_id"]) echo 'checked="checked"'; ?> />
				<label for='gid_<?php echo $row["guideline_id"]; ?>'><?php echo $row["title"]; ?></label></td>
<?php
	$count_guidelines_in_current_row++;

	if ($count_guidelines_in_current_row == $num_of_guidelines_per_row)
		echo "			</tr>\n";

}
?>
		</table>
	</fieldset>
	</div>

</form>

<script type="text/JavaScript">
<!--

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}

// This function validates if and only if a zip file is given
function validate_filename() {
  // check file type
  var upload_file = document.input_form.uploadfile.value;
  if (!upload_file || upload_file.trim()=='') {
    alert('Please provide a html file!');
    return false;
  }
  
  file_extension = upload_file.slice(upload_file.lastIndexOf(".")).toLowerCase();
  if(file_extension != '.html' && file_extension != '.htm') {
    alert('Please upload html (or htm) file only!');
    return false;
  }
}

// This function validates if and only if a uri is given
function validate_this_uri() {
  // check uri
  var uri = document.input_form.uri.value;
  if (!uri || uri=="<?php echo $default_uri_value; ?>" ) {
    alert('Please provide a uri!');
    return false;
  }
}  
//  End -->
//-->
</script>
