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

global $addslashes, $congrats_msg_for_likely, $congrats_msg_for_potential;;

include_once(AC_INCLUDE_PATH.'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UserLinksDAO.class.php');
?>
<div id="seals_div" class="validator-output-form">

<?php 
// display seals
if (is_array($this->seals))
{
?>
<h3><?php echo _AC('valid_icons');?></h3>
<p><?php echo _AC('valid_icons_text');?></p>
<?php 
	$user_link_url = '';
	
	if (isset($this->user_link_id))
		$user_link_url = '&amp;id='.$this->user_link_id;
	
	foreach ($this->seals as $seal)
	{
?>
	<img class="inline-badge" src="<?php echo SEAL_ICON_FOLDER . $seal['seal_icon_name'];?>"
    alt="<?php echo $seal['title']; ?>" height="32" width="102"/>
    <pre class="badgeSnippet">
  &lt;p&gt;
    &lt;a href="<?php echo AC_BASE_HREF; ?>checker/index.php?uri=referer&amp;gid=<?php echo $seal['guideline'].$user_link_url;?>"&gt;
      &lt;img src="<?php echo AC_BASE_HREF.SEAL_ICON_FOLDER . $seal['seal_icon_name'];?>" alt="<?php echo $seal['title']; ?>" height="32" width="102" /&gt;
    &lt;/a&gt;
  &lt;/p&gt;
	</pre>

<?php 
	} // end of foreach (display seals)
} // end of if (display seals)
?>
</div>

<!-- ============================================================== -->
<form name="file_form" enctype="multipart/form-data" method="post" >

	<div class="left-col" style="float:left;clear:left;margin: 1em;padding: 1em; max-width:669px"><br />
	<fieldset class="group_form" style="min-height: 122px; margin-bottom:7px;"><legend class="group_form"><?php echo _AC("file_export"); ?></legend>
		<div style="text-align: center; padding: 0.5em;">
			<label for="file_type"><?php echo _AC('file_type'); ?>:</label>
			<select name="file_menu" id="fileselect">
				<option value="" selected="selected"><?php echo _AC('select_file'); ?></option>
				<option value="pdf" >PDF</option>
				<option value="earl">EARL</option>
				<option value="csv">CSV</option>
			</select>
			
			<label for="problem_type" style="margin-left: 2em;"><?php echo _AC('problem_type'); ?>:</label>
			<select name="problem_menu" id="problemselect">
				<option value="" selected="selected"><?php echo _AC('select_problem'); ?></option>
				<option value="known" ><?php echo _AC('known'); ?></option>
				<option value="likely"><?php echo _AC('likely'); ?></option>
				<option value="potential"><?php echo _AC('potential'); ?></option>
				<option value="all"><?php echo _AC('all'); ?></option>
			</select></br>
			
			<div id="progress" style="display:none">
				<div style="padding:0.5em 1em;">

				</div>
			</div>
			
			<div class="validation_submit_div">	
					<div class="spinner_div">
						<img class="spinner_img" id="spinner_export" style="display:none" src="<?php echo AC_BASE_HREF.'themes/'.$_SESSION['prefs']['PREF_THEME']; ?>/images/spinner.gif" alt="<?php echo _AC("in_progress"); ?>" />
						&nbsp;
					</div>
					<input class="validation_button" type="button" name="validate_export" id="validate_file" value="<?php echo 'Get File'; ?>" onclick="return AChecker.input.validateFile('spinner_export');" />
			</div>
		</div>
	</fieldset>
	</div>
</form>
<!-- ============================================================== -->

<div id="output_div" >

<?php
if (isset($this->aValidator) && $this->a_rpt->getAllowSetDecisions() == 'true')
{
	$sessionID = Utility::getSessionID();
	
	$userLinksDAO = new UserLinksDAO();
	$userLinksDAO->setLastSessionID($this->a_rpt->getUserLinkID(), $sessionID);
	
	echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">'."\n\r";
	echo '<input type="hidden" name="jsessionid" value="'.$sessionID.'" />'."\n\r";
	echo '<input type="hidden" name="uri" value="'.$addslashes($_POST["uri"]).'" />'."\n\r";
	echo '<input type="hidden" name="output" value="html" />'."\n\r";
	echo '<input type="hidden" name="validate_uri" value="1" />'."\n\r";
	echo '<input type="hidden" name="rpt_format" value="'.$addslashes($_POST['rpt_format']).'" />'."\n\r";

	// report for referer URI
	if (isset($this->referer_report))
	{
		echo '<input type="hidden" name="referer_report" value="'.$this->referer_report.'" />'."\n\r";
	} 

	// user_link_id for referer URI is sent in from request, don't need to retrieve
	if (isset($this->referer_user_link_id))
	{
		echo '<input type="hidden" name="referer_user_link_id" value="'.$this->referer_user_link_id.'" />'."\n\r";
	} 
	
	foreach ($_POST as $post_name => $value) {
		if (substr($post_name, -4) == "_gid") {
			foreach ($_POST[$post_name] as $gid_value) {
				echo '<input type="hidden" name="'.$post_name.'[]" value="'.$gid_value.'" />'."\n\r";
			}
		}
	}
}
?>
	<div class="center-input-form">
	<a name="report" title="<?php echo _AC("report_start"); ?>"></a>
	<fieldset class="group_form"><legend class="group_form"><?php echo _AC("accessibility_review"); ?></legend>
	<h3><?php echo _AC("accessibility_review") . ' ('. _AC("guidelines"). ': '.$this->guidelines_text. ')'; ?></h3>

	<div class="topnavlistcontainer"><br />
		<ul class="navigation">
			<li class="navigation"><a href="javascript:void(0);" accesskey="1" title="<?php echo _AC("known_problems"); ?> Alt+1" id="menu_AC_errors" onclick="AChecker.output.onClickTab('AC_errors');"><span class="nav"><?php echo _AC("known_problems"); ?>(<span id="AC_num_of_errors"><?php echo $this->num_of_errors; ?></span>)</span></a></li>

			<li class="navigation"><a href="javascript:void(0);" accesskey="2" title="<?php echo _AC("likely_problems"); ?> Alt+2" id="menu_AC_likely_problems" onclick="AChecker.output.onClickTab('AC_likely_problems');"><span class="nav"><?php echo _AC("likely_problems"); ?> (<span id="AC_num_of_likely"><?php echo $this->num_of_likely_problems_no_decision; ?></span>)</span></a></li>

			<li class="navigation"><a href="javascript:void(0);" accesskey="3" title="<?php echo _AC("potential_problems"); ?> Alt+3" id="menu_AC_potential_problems" onclick="AChecker.output.onClickTab('AC_potential_problems');"><span class="nav"><?php echo _AC("potential_problems"); ?> (<span id="AC_num_of_potential"><?php echo $this->num_of_potential_problems_no_decision; ?></span>)</span></a></li>

			<li class="navigation"><a href="javascript:void(0);" accesskey="4" title="<?php echo _AC("html_validation_result"); ?> Alt+4" id="menu_AC_html_validation_result" onclick="AChecker.output.onClickTab('AC_html_validation_result');"><span class="nav"><?php echo _AC("html_validation_result"); ?> <?php if (isset($_POST["enable_html_validation"])) echo '(<span id="AC_num_of_html_errors">'.$this->num_of_html_errors."</span>)"; ?></span></a></li>

			<li class="navigation"><a href="javascript:void(0);" accesskey="5" title="<?php echo _AC("css_validation_result"); ?> Alt+5" id="menu_AC_css_validation_result" onclick="AChecker.output.onClickTab('AC_css_validation_result');"><span class="nav"><?php echo _AC("css_validation_result"); ?> <?php if (isset($this->cssValidator)) echo '(<span id="AC_num_of_css_errors">'.$this->num_of_css_errors."</span>)"; ?></span></a></li>
		</ul>
	</div>

<?php 
$has_errors = false;
if (isset($this->aValidator) && $this->num_of_errors > 0) {
	$has_errors = true;
}
?>
	<div id="AC_errors">
	<br />
	<span id='AC_congrats_msg_for_errors' <?php if (!$has_errors) echo "class='congrats_msg'";?>>
<?php 
if (!$has_errors) {
	echo "<img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_no_known");
}
?>
	</span>
<?php
if ($has_errors) {
	echo $this->a_rpt->getErrorRpt();
}
?>
	</div>

<?php 
$resolved_all_likely = false;
if (isset($this->aValidator) && $this->num_of_likely_problems_no_decision == 0) {
	$resolved_all_likely = true;
}
?>
	<div id="AC_likely_problems" style="display:none;">
	<br />
	<span id="AC_congrats_msg_for_likely" <?php if ($resolved_all_likely) echo "class='congrats_msg'"; ?>>
<?php 
if ($resolved_all_likely) {
	echo $congrats_msg_for_likely;
}
?>
	</span>
<?php
if (isset($this->aValidator) && $this->num_of_likely_problems > 0) {
	echo $this->a_rpt->getLikelyProblemRpt();
}
?>
	</div>

<?php 
$resolved_all_potential = false;
if (isset($this->aValidator) && $this->num_of_potential_problems_no_decision == 0) {
	$resolved_all_potential = true;
}
?>
	<div id="AC_potential_problems" style="margin-top:1em; display:none;">
	<br />
	<span id="AC_congrats_msg_for_potential" <?php if ($resolved_all_potential) echo "class='congrats_msg'";?>>
<?php 
if ($resolved_all_potential) {
	echo $congrats_msg_for_potential;
}
?>
	</span>
<?php

if (isset($this->aValidator) && $this->num_of_potential_problems > 0) {
	echo $this->a_rpt->getPotentialProblemRpt();
}

?>
	</div>

	<div id="AC_html_validation_result" style="margin-top:1em; display:none;">
<?php
if (isset($this->htmlValidator))
{
	echo '		<br /><ol><li class="msg_err">'. _AC("html_validator_provided_by") .'</li></ol>'. "\n";
	
	if ($this->htmlValidator->containErrors())
		echo $this->htmlValidator->getErrorMsg();
	else
	{
		if ($this->num_of_html_errors > 0)
			echo $this->htmlValidator->getValidationRpt();
		else
			echo "<br /><span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_html_validation") ."</span>";
	}
}
else
	echo '<br /><span class="info_msg"><img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("html_validator_disabled").'</span>';
?>
	</div>
	
		<div id="AC_css_validation_result" style="margin-top:1em; display:none;">
<?php
if (isset($_POST['validate_file']) || isset($_POST['validate_paste'])) {
	// css validator is only available at validating url, not at validating a uploaded file or pasted html
	echo '<br /><span class="info_msg"><img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("css_validator_unavailable").'</span>';
} else if (isset($this->cssValidator)) {
	// validating url -> css validator option is turned ON
	echo '		<br /><ol><li class="msg_err">'. _AC("css_validator_provided_by") .'</li></ol>'. "\n";
	
	if ($this->cssValidator->containErrors())
		echo $this->cssValidator->getErrorMsg();
	else
	{
		if ($this->num_of_css_errors > 0)
			echo $this->cssValidator->getValidationRpt();
		else
			echo "<br /><span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_css_validation") ."</span>";
	}
} else {
	// validating url -> css validator option is turned OFF
	echo '<br /><span class="info_msg"><img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("css_validator_disabled").'</span>';
}
?>
	</div>
		
		
	</fieldset>

<?php 
if (isset($this->aValidator) && $this->a_rpt->getAllowSetDecisions() == 'true')
{
	if ($this->a_rpt->getNumOfNoDecisions() > 0)
	{
		echo '<div align="center"><input type="submit" name="make_decision" id="make_decision" value="'._AC('make_decision').'" style="align:center" /></div>';
	}
	echo '</form>';
}
?>
</div>

<?php if (isset($_POST['show_source']) && isset($this->aValidator)) {?>
<div id="source" class="validator-output-form">
<h3><?php echo _AC('source');?></h3>
<p><?php echo _AC('source_note');?></p>

<?php echo $this->a_rpt->getSourceRpt();?>
</div>
<?php }?>
</div><br />
