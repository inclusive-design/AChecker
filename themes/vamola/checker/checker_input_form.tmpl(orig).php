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

global $onload;
$onload="initial()";

include_once(AC_INCLUDE_PATH.'header.inc.php');

//if (isset($this->error)) echo $this->error;

?>
<div class="center-input-form">
<form name="input_form" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >

	<div class="left-col" style="float:left;clear:left;"><br />
	<fieldset class="group_form"><legend class="group_form"><?php echo _AC("input"); ?></legend>
		<div style="width:80%; margin-left:auto;margin-right:auto;">
			<div><h2 style="width:60%;margin-left:auto;margin-right:auto;"><label for="checkuri"><?php echo _AC("check_by_uri"); ?></label></h2></div>
			<div>
			<input type="text" name="uri" id="checkuri" value="www.google.it" size="50" />
<!--			<input type="text" name="uri" id="checkuri" value="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $this->default_uri_value; ?>" size="50" />-->
			<p class="submit_button">
			<!--	Simo: Levato l'onclick javascript
				<input type="submit" name="validate_uri" size="100" value="<?php echo _AC("check_it"); ?>" onclick="return validate_this_uri();" class="submit"/>-->
				<input type="submit" name="validate_uri" size="100" value="<?php echo _AC("check_it"); ?>" class="submit"/>
			</p>
			</div>

			<div><h2 style="width:60%;margin-left:auto;margin-right:auto;"><label for="checkfile"><?php echo _AC("check_by_upload"); ?></label></h2></div>

			<div>
			<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
			<input type="file" id="checkfile" name="uploadfile" size="47" />
		
			<p class="submit_button">
<!--			Simo: Levato l'onclick javascript
				<input type="submit" name="validate_file" value="<?php echo _AC("check_it"); ?>" onclick="return validate_filename();" class="submit" />-->
				<input type="submit" name="validate_file" value="<?php echo _AC("check_it"); ?>" class="submit" />
			</p>
			</div>
		</div>
		<div>
			<h2 align="left">
				<img src="images/arrow-closed.png" alt="<?php echo _AC("expand_guidelines"); ?>" title="<?php echo _AC("expand_guidelines"); ?>" id="toggle_image" border="0" />
				<a href="javascript:toggleToc('div_options')"><?php echo _AC("options"); ?></a>
			</h2>
		</div>

		<div id="div_options" style="display:none">

		<table class="data static" style="background-colour:#eeeeee;">
			<tr>
				<td>
				<input type="checkbox" name="enable_html_validation" id="enable_html_validation" value="1" <?php if (isset($_POST["enable_html_validation"])) echo 'checked="checked"'; ?> />
				<label for='enable_html_validation'><?php echo _AC("enable_html_validator"); ?></label>
				</td>

				<td colspan="2">
				<input type="checkbox" name="show_source" id="show_source" value="1" <?php if (isset($_POST["show_source"])) echo 'checked="checked"'; ?> />
				<label for='show_source'><?php echo _AC("show_source"); ?></label>
				</td>
			</tr>
			
			<tr>
				<td colspan="3"><h3><?php echo _AC("guidelins_to_check"); ?></h3></td>
			</tr>
<?php
$count_guidelines_in_current_row = 0;

if (is_array($this->rows))
{
	foreach ($this->rows as $id => $row)
	{
		if ($count_guidelines_in_current_row == 0 || $count_guidelines_in_current_row == $this->num_of_guidelines_per_row)
		{
			$count_guidelines_in_current_row = 0;
			echo "			<tr>\n";
		}
?>
				<td>
					<input type="checkbox" name="gid[]" id='gid_<?php echo $row["guideline_id"]; ?>' value='<?php echo $row["guideline_id"]; ?>' <?php foreach($_POST["gid"] as $gid) {if ($gid == $row["guideline_id"]) echo 'checked="checked"';} ?> />
					<label for='gid_<?php echo $row["guideline_id"]; ?>'><?php echo $row["title"]; ?></label>
				</td>
<?php
		$count_guidelines_in_current_row++;
	
		if ($count_guidelines_in_current_row == $this->num_of_guidelines_per_row)
			echo "			</tr>\n";
	
	}
	// Simo aggiungo il tr
	echo "			</tr>\n";
}
?>
		</table>
		</div>
	</fieldset>
	</div>
</form>


</div>

<?php
	// Simo: Eliminate le funzioni per validare uri e file con javascript, sostituite da funzioni php
?>
