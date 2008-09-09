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

if (!isset($_POST["gid"])) $_POST["gid"] = array($default_guideline);
?>

<FORM NAME="input_form" ENCTYPE="multipart/form-data" METHOD="POST" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>" >
	
<div class="input-form">
	<fieldset class="group_form"><legend class="group_form">Input</legend>
		<div class="row"><h4>Validate by URI </h4></div>

		<div class="row">
			<INPUT TYPE="text" NAME="uri" VALUE="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $default_uri_value; ?>" SIZE="50" />
			<p class="submit_button">
				<INPUT TYPE="submit" name="validate_uri" size="100" value="Validate" onClick="return validate_this_uri();" class="submit" />
			</p>
		</div>

		<div class="row"><h4>Validate by File Upload <h4></div>

		<div class="row">
			<INPUT TYPE="hidden" name="MAX_FILE_SIZE" VALUE="52428800">
			<INPUT TYPE="file" NAME="uploadfile"  SIZE="50">
		
			<p class="submit_button">
				<INPUT TYPE="submit" name="validate_file" value="Validate" onClick="return validate_filename();" class="submit" />
			</p>
		</div>

		<div class="row"><h5>Guidelines to Validate Against </h5></div>

		<table class="data static">
			<tr>
				<td><input type="checkbox" name="gid[]" value="4" <?php foreach($_POST["gid"] as $gid) if ($gid == 4) echo "checked"; ?> />
				WCAG 1.0 (Level A)</td>

				<td><input type="checkbox" name="gid[]" value="5" <?php foreach($_POST["gid"] as $gid) if ($gid == 5) echo "checked"; ?> />
				WCAG 1.0 (Level AA)</td>

				<td><input type="checkbox" name="gid[]" value="6" <?php foreach($_POST["gid"] as $gid) if ($gid == 6) echo "checked"; ?> />
				WCAG 1.0 (Level AAA)</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="gid[]" value="7" <?php foreach($_POST["gid"] as $gid) if ($gid == 7) echo "checked"; ?> />
				WCAG 2.0 (Level A)</td>

				<td><input type="checkbox" name="gid[]" value="8" <?php foreach($_POST["gid"] as $gid) if ($gid == 8) echo "checked"; ?> />
				WCAG 2.0 (Level AA)</td>

				<td><input type="checkbox" name="gid[]" value="9" <?php foreach($_POST["gid"] as $gid) if ($gid == 9) echo "checked"; ?> />
				WCAG 2.0 (Level AAA)</td>
			</tr>

			<tr>
				<td><input type="checkbox" name="gid[]" value="1" <?php foreach($_POST["gid"] as $gid) if ($gid == 1) echo "checked"; ?> />
				BITV 1.0 (Level 2)</td>

				<td><input type="checkbox" name="gid[]" value="2" <?php foreach($_POST["gid"] as $gid) if ($gid == 2) echo "checked"; ?> />
				Section 508</td>

				<td><input type="checkbox" name="gid[]" value="3" <?php foreach($_POST["gid"] as $gid) if ($gid == 3) echo "checked"; ?> />
				Stanca Act</td>
			</tr>
		</table>
	</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
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
