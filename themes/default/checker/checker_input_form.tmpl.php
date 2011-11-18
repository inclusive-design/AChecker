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

global $onload, $_custom_head;

if (isset($_POST["validate_file"])){
	$init_tab = "AC_by_upload";
} else if (isset($_POST["validate_paste"])){
	$init_tab = "AC_by_paste";
} else {
	$init_tab = "AC_by_uri";
}

if ($_POST["rpt_format"] == REPORT_FORMAT_GUIDELINE) {
	$rpt_format = "by_guideline";
} else if ($_POST["rpt_format"] == REPORT_FORMAT_LINE) {
	$rpt_format = "by_line";
}

$onload="AChecker.input.initialize('".$init_tab."', '".$rpt_format."');";
$_custom_head .= '	<script language="javascript" type="text/javascript">'."\n".
                 '	//<!--'."\n";

ob_start();
require_once(AC_INCLUDE_PATH.'../checker/js/checker_js.php');
$_custom_head .= ob_get_contents();
ob_end_clean();

$_custom_head .= '	//-->'."\n".
                 '	</script>'."\n".
                 '	<script src="'.AC_BASE_HREF.'checker/js/checker.js" type="text/javascript"></script>'."\n";

include(AC_INCLUDE_PATH.'header.inc.php');

if (isset($this->error)) echo $this->error;

/** return the string of a div html to display all the available guidelines
 * 2 formats: checkbox or radio button in front of the guideline
 * @param: $guideline_rows - array of available guidelines
 *         $num_of_guidelines_per_row
 *         $format: "checkbox" or "radio"
 */ 
function get_guideline_div($guideline_rows, $num_of_guidelines_per_row, $format = "checkbox") {
	$output = '				<div id="guideline_in_'.$format .'"';
	if ($format == "checkbox") $output .= ' style="display:none"';
	$output .= '>'."\n";
	$output .= '				<table width="100%">'."\n";
	
	$count_guidelines_in_current_row = 0;
	
	if (is_array($guideline_rows))
	{
		foreach ($guideline_rows as $id => $row)
		{
			if ($count_guidelines_in_current_row == 0 || $count_guidelines_in_current_row == $num_of_guidelines_per_row)
			{
				$count_guidelines_in_current_row = 0;
				$output .= "					<tr>\n";
			}

			$output .= '						<td class="one_third_width">'."\n";
			$output .= '							<input type="';
			
			if ($format == "checkbox") $output .= "checkbox";
			else $output .= "radio";
			
			$output .= '" name="'.$format.'_gid[]" id="'.$format.'_gid_'.$row["guideline_id"].'" value="'. $row["guideline_id"].'"';
			
			// the name of the array for the selected guidelines in the post value are different.
			// "radio_gids" at guideline view and "checkbox_gids" at line view. 
			$gid_name = $format."_gid";
			foreach($_POST[$gid_name] as $gid) {
				if ($gid == $row["guideline_id"]) $output .= ' checked="checked"';
			} 
			$output .= ' />'."\n";
			
			$output .= '							<label for="'.$format.'_gid_'. $row["guideline_id"].'">'. htmlspecialchars($row["title"]).'</label>'."\n";
			$output .= "						</td>\n";
			$count_guidelines_in_current_row++;
		
			if ($count_guidelines_in_current_row == $num_of_guidelines_per_row)
				$output .= "					</tr>\n";
		
		}
	}
	$output .= "				</table>\n";
	$output .= "			</div>\n";
	
	return $output;
}
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
				<li class="navigation"><a href="javascript:void(0)" accesskey="a" title="<?php echo _AC("check_by_uri"); ?> Alt+1" id="AC_menu_by_uri" onclick="return AChecker.input.onClickTab('AC_by_uri');" <?php if (!isset($_POST["validate_paste"]) && !isset($_POST["validate_file"])) echo 'class="active"'; ?>><span class="nav"><?php echo _AC("check_by_uri"); ?></span></a></li>
				<li class="navigation"><a href="javascript:void(0)" accesskey="b" title="<?php echo _AC("check_by_upload"); ?> Alt+2" id="AC_menu_by_upload" onclick="return AChecker.input.onClickTab('AC_by_upload');" <?php if (isset($_POST["validate_file"])) echo 'class="active"'; ?>><span class="nav"><?php echo _AC("check_by_upload"); ?></span></a></li>
				<li class="navigation"><a href="javascript:void(0)" accesskey="c" title="<?php echo _AC("check_by_paste"); ?> Alt+3" id="AC_menu_by_paste" onclick="return AChecker.input.onClickTab('AC_by_paste');" <?php if (isset($_POST["validate_paste"])) echo 'class="active"'; ?>><span class="nav"><?php echo _AC("check_by_paste"); ?></span></a></li>
			</ul>
		</div>
		
		<div id="AC_by_uri" class="input_tab" style="<?php if (!isset($_POST["validate_file"]) && !isset($_POST["validate_paste"])) echo "display:block"; else echo "display:none"; ?>">
			<div style="text-align:center;">
				<label for="checkuri"><?php echo _AC('URL'); ?>:</label>
				<input type="text" name="uri" id="checkuri" value="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $this->default_uri_value; ?>" size="50"   />
				<div class="validation_submit_div">
					<div class="spinner_div">
						<img class="spinner_img" id="AC_spinner_by_uri" style="display:none" src="<?php echo AC_BASE_HREF.'themes/'.$_SESSION['prefs']['PREF_THEME']; ?>/images/spinner.gif" alt="<?php echo _AC("in_progress"); ?>" />
						&nbsp;
					</div>
					<input class="validation_button" type="submit" name="validate_uri" id="validate_uri" size="100" value="<?php echo _AC("check_it"); ?>" onclick="return AChecker.input.validateURI();" />
				</div>
			</div>
		</div>
		
		<div id="AC_by_upload" class="input_tab" style="<?php if (isset($_POST["validate_file"])) echo "display:block"; else echo "display:none"; ?>">
			<div style="text-align:center;">
				<label for="checkfile"><?php echo _AC('file'); ?>:</label>
				<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
				<input type="file" id="checkfile" name="uploadfile" size="47" />
			
				<div class="validation_submit_div">
					<div class="spinner_div">
						<img class="spinner_img" id="AC_spinner_by_upload" style="display:none" src="<?php echo AC_BASE_HREF.'themes/'.$_SESSION['prefs']['PREF_THEME']; ?>/images/spinner.gif" alt="<?php echo _AC("in_progress"); ?>" />
						&nbsp;
					</div>
					<input class="validation_button" type="submit" name="validate_file" id="validate_file" value="<?php echo _AC("check_it"); ?>" onclick="return AChecker.input.validateUpload();"  />
				</div>
			</div>
		</div>
		
		<div id="AC_by_paste" class="input_tab" style="<?php if (isset($_POST["validate_paste"])) echo "display:block"; else echo "display:none"; ?>">
			<label for="checkpaste"><?php echo _AC('enter'); ?>:</label>
			<div style="text-align:center;">
				<textarea rows="20" cols="75" name="pastehtml" id="checkpaste"><?php if (isset($_POST['pastehtml'])) echo htmlspecialchars($_POST['pastehtml']); ?></textarea>
		
				<div class="validation_submit_div">
					<div class="spinner_div">
						<img class="spinner_img" id="AC_spinner_by_paste" style="display:none" src="<?php echo AC_BASE_HREF.'themes/'.$_SESSION['prefs']['PREF_THEME']; ?>/images/spinner.gif" alt="<?php echo _AC("in_progress"); ?>" />
						&nbsp;
					</div>
					<input class="validation_button" type="submit" name="validate_paste" id="validate_paste" value="<?php echo _AC("check_it"); ?>" onclick="return AChecker.input.validatePaste();" />
				</div>
			</div>
		</div>
		
		<div>
			<h2 align="left">
				<img src="images/arrow-closed.png" alt="<?php echo _AC("expand_guidelines"); ?>" title="<?php echo _AC("expand_guidelines"); ?>" id="toggle_image" border="0" />
				<a href="javascript:AChecker.toggleDiv('div_options', 'toggle_image');"><?php echo _AC("options"); ?></a>
			</h2>
		</div>

		<div id="div_options" style="display:none">

		<table class="data static" style="background-colour:#eeeeee;">
			<tr>
				<td class="one_third_width">
				<input type="checkbox" name="enable_html_validation" id="enable_html_validation" value="1" <?php if (isset($_POST["enable_html_validation"])) echo 'checked="checked"'; ?> />
				<label for='enable_html_validation'><?php echo _AC("enable_html_validator"); ?></label>
				</td>
				
				<td class="one_third_width">
				<input type="checkbox" name="enable_css_validation" id="enable_css_validation" value="1" <?php if (isset($_POST["enable_css_validation"])) echo 'checked="checked"'; ?> />
				<label for='enable_css_validation'><?php echo _AC("enable_css_validation"); ?></label>
				</td>
				
				<td class="one_third_width">
				<input type="checkbox" name="show_source" id="show_source" value="1" <?php if (isset($_POST["show_source"])) echo 'checked="checked"'; ?> />
				<label for='show_source'><?php echo _AC("show_source"); ?></label>
				</td>
				
			</tr>
			
			<tr>
				<td colspan="3"><h3><?php echo _AC("guidelins_to_check"); ?></h3></td>
			</tr>
<!-- 
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
					<input type="checkbox" name="gid[]" id='gid_<?php echo $row["guideline_id"]; ?>' value='<?php echo $row["guideline_id"]; ?>' <?php 
					if (isset($_POST["gid"]) && is_array($_POST["gid"])) {	
						foreach($_POST["gid"] as $gid) {
							if (intval($gid) == $row["guideline_id"]) echo 'checked="checked"';
						}
					} 
					?> />
					<label for='gid_<?php echo $row["guideline_id"]; ?>'><?php echo htmlspecialchars($row["title"]); ?></label>
				</td>
<?php
		$count_guidelines_in_current_row++;
	
		if ($count_guidelines_in_current_row == $this->num_of_guidelines_per_row)
			echo "			</tr>\n";
	
	}
}
?>
 -->
			<tr>
			<td colspan="3">
<?php 
echo get_guideline_div($this->rows, $this->num_of_guidelines_per_row, "radio");  // used at "view by guideline"
echo get_guideline_div($this->rows, $this->num_of_guidelines_per_row, "checkbox");  // used at "view by line"
?>
			</td>
			</tr>
			
			<tr>
				<td colspan="3"><h3><?php echo _AC("report_format"); ?></h3></td>
			</tr>
			<tr>
				<td class="one_third_width"><input type="radio" name="rpt_format" value="<?php echo REPORT_FORMAT_GUIDELINE; ?>" id="option_rpt_gdl" <?php if ($_POST["rpt_format"] == REPORT_FORMAT_GUIDELINE) echo 'checked="checked"'; ?> /><label for="option_rpt_gdl"><?php echo _AC("view_by_guideline"); ?></label></td>
				<td class="one_third_width"><input type="radio" name="rpt_format" value="<?php echo REPORT_FORMAT_LINE; ?>" id="option_rpt_line" <?php if ($_POST["rpt_format"] == REPORT_FORMAT_LINE) echo 'checked="checked"'; ?> /><label for="option_rpt_line"><?php echo _AC("view_by_line"); ?></label></td>
			</tr>
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
