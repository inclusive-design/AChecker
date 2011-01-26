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

global $onload;
$onload="AChecker.initialize()";

include(AC_INCLUDE_PATH.'header.inc.php');

if (isset($this->error)) echo $this->error;
?>
<table style="width:100%">
<tr>
<td>
<div class="center-input-form">
<form name="input_form" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >

	<div class="left-col" style="float:left;clear:left;"><br />
	<fieldset class="group_form"><legend class="group_form"><?php echo _AC("input"); ?></legend>

		<div class="topnavlistcontainer"><br />
			<ul class="navigation">
				<li class="navigation"><a href="#" accesskey="a" title="<?php echo _AC("check_by_uri"); ?> Alt+1" id="menu_by_uri" onclick="return AChecker.input.onClickTab('by_uri');"><span><?php echo _AC("check_by_uri"); ?></span></a></li>
				<li class="navigation"><a href="#" accesskey="b" title="<?php echo _AC("check_by_upload"); ?> Alt+2" id="menu_by_upload" onclick="return AChecker.input.onClickTab('by_upload');"><span><?php echo _AC("check_by_upload"); ?></span></a></li>
				<li class="navigation"><a href="#" accesskey="c" title="<?php echo _AC("check_by_paste"); ?> Alt+3" id="menu_by_paste" onclick="return AChecker.input.onClickTab('by_paste');"><span><?php echo _AC("check_by_paste"); ?></span></a></li>
			</ul>
		</div>
		
		<div id="by_uri" style="display:none;">
			<div style="text-align:center;">
			<input type="text" name="uri" id="checkuri" value="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $this->default_uri_value; ?>" size="50"   />
			<p class="submit_button"  style="text-align:center;">
				<input type="submit" name="validate_uri" size="100" value="<?php echo _AC("check_it"); ?>" onclick="return AChecker.input.validateURI();" class="submit"/>
			</p>
			</div>
		</div>
		
		<div id="by_upload" style="display:none">
			<div  style="text-align:center;">
			<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
			<input type="file" id="checkfile" name="uploadfile" size="47" />
		
			<p class="submit_button">
				<input type="submit" name="validate_file" value="<?php echo _AC("check_it"); ?>" onclick="return AChecker.input.validateUpload();" class="submit" />
			</p>
			</div>
		</div>
		
		<div id="by_paste" style="display:none">
			<div  style="text-align:center;">
			<textarea rows="20" cols="75" name="pastehtml" id="checkpaste"><?php if (isset($_POST['pastehtml'])) echo htmlspecialchars($_POST['pastehtml']); ?></textarea>
		
			<p class="submit_button">
				<input type="submit" name="validate_paste" value="<?php echo _AC("check_it"); ?>" onclick="return AChecker.input.validatePaste();" class="submit" />
			</p>
			</div>
		</div>
		
		<div>
			<h2 align="left">
				<img src="images/arrow-closed.png" alt="<?php echo _AC("expand_guidelines"); ?>" title="<?php echo _AC("expand_guidelines"); ?>" id="toggle_image" border="0" />
				<a href="javascript:AChecker.toggleToc('div_options')"><?php echo _AC("options"); ?></a>
			</h2>
		</div>

		<div id="div_options" style="display:none">

		<table class="data static" style="background-colour:#eeeeee;">
			<tr>
				<td>
				<input type="checkbox" name="enable_html_validation" id="enable_html_validation" value="1" <?php if (isset($_POST["enable_html_validation"])) echo 'checked="checked"'; ?> />
				<label for='enable_html_validation'><?php echo _AC("enable_html_validator"); ?></label>
				</td>
				
				<td>
				<input type="checkbox" name="enable_css_validation" id="enable_css_validation" value="1" <?php if (isset($_POST["enable_css_validation"])) echo 'checked="checked"'; ?> />
				<label for='enable_css_validation'><?php echo _AC("enable_css_validation"); ?></label>
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
					<label for='gid_<?php echo $row["guideline_id"]; ?>'><?php echo htmlspecialchars($row["title"]); ?></label>
				</td>
<?php
		$count_guidelines_in_current_row++;
	
		if ($count_guidelines_in_current_row == $this->num_of_guidelines_per_row)
			echo "			</tr>\n";
	
	}
}
?>
		</table>
		</div>
	</fieldset>
	</div>
</form>
<div style="float:right;margin-right:2em;clear:right;width:250px;"><br />
<a href="checker/index.php#skipads"><img src="images/clr.gif" alt="<?php echo _AC("skip_over_ads"); ?>" border="0"/></a>	
	<script type="text/javascript">
	<!--
	google_ad_client = "pub-8538177464726172";
	/* 250x250, created 3/13/09 */
	google_ad_slot = "0783349774";
	google_ad_width = 250;
	google_ad_height = 250;
	//-->
	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
<a name="skipads" title="passed ads"></a>
</div>

</div>
</td>
</tr>
</table>

<script language="JavaScript" type="text/javascript">
(function() {
	AChecker.initialize = function () {
		// initialize input form
		<?php if (isset($_POST["validate_file"])) {?>
		AChecker.showDivOutof("by_upload", AChecker.input.inputDivIds);
		<?php } else if (isset($_POST["validate_paste"])) { ?>
		AChecker.showDivOutof("by_paste", AChecker.input.inputDivIds);
		<?php } else {?>
		AChecker.showDivOutof("by_uri", AChecker.input.inputDivIds);
		<?php }?>
		
		// initialize output form
		var div_errors = document.getElementById("errors");

		if (div_errors != null)
		{
			// show tab "errors", hide other tabs
			AChecker.showDivOutof("errors", AChecker.output.outputDivIds);			

			// hide button "make decision" as tab "errors" are selected
			AChecker.hideByID(AChecker.output.makeDecisionButtonId);
		} else { //if (div_errors == null) {
			document.input_form.uri.focus();
		}
	};
	
	/**
	 * Show the div with id == the given divId while hide all other divs in the array allDivIds
	 * @param divId: the id of the div to show
	 *        allDivIds: The array of div Ids that are in the same group of divId. divId must be in this array. 
	 */
	AChecker.input.onClickTab = function (divId) {
		AChecker.showDivOutof(divId, AChecker.input.inputDivIds);
		return false;
	};

	/**
	 * Validates if a uri is provided
	 */
	AChecker.input.validateURI = function () {
		// check uri
		var uri = document.input_form.uri.value;
		if (!uri || uri=="<?php echo $default_uri_value; ?>" ) {
			alert('Please provide a uri!');
			return false;
		}
	};
		
	/**
	 * Validates if a html file is provided
	 */
	AChecker.input.validateUpload = function () {
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
	};

	/**
	 * Validates if a html file is provided
	 */
	AChecker.input.validatePaste = function () {
		// check file type
		var paste_html = document.input_form.pastehtml.value;
		if (!paste_html || paste_html.trim()=='') {
			alert('Please provide a html input!');
			return false;
		}
	};

})();
</script>
