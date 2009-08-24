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

global $addslashes;

include_once(AC_INCLUDE_PATH.'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/UserLinksDAO.class.php');

// display seals
if (is_array($this->seals))
{
?>

<div id="seals_div" class="validator-output-form">

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
?>
</div>
<?php 
} // end of if (display seals)
?>

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
	
	foreach ($_POST['gid'] as $gid)
		echo '<input type="hidden" name="gid[]" value="'.$gid.'" />'."\n\r";
}
?>
	<div class="center-input-form">
	<a name="report" title="<?php echo _AC("report_start"); ?>"></a>
	<fieldset class="group_form"><legend class="group_form"><?php echo _AC("accessibility_review"); ?></legend>
	<h3><?php echo _AC("accessibility_review") . ' ('. _AC("guidelines"). ': '.$this->guidelines_text. ')'; ?></h3>

	<div class="topnavlistcontainer"><br />
		<ul class="navigation">
			<li class="navigation"><a href="checker/index.php#output_div" accesskey="1" title="<?php echo _AC("known_problems"); ?> Alt+1" id="menu_errors" onclick="showDiv('errors');return false;"><span><?php echo _AC("known_problems"); ?>(<?php echo $this->num_of_errors; ?>)</span></a></li>
			<li class="navigation"><a href="checker/index.php#output_div" accesskey="2" title="<?php echo _AC("likely_problems"); ?> Alt+2" id="menu_likely_problems" onclick="showDiv('likely_problems');return false;"><span><?php echo _AC("likely_problems"); ?> (<?php echo $this->num_of_likely_problems_no_decision; ?>)</span></a></li>
			<li class="navigation"><a href="checker/index.php#output_div" accesskey="3" title="<?php echo _AC("potential_problems"); ?> Alt+3" id="menu_potential_problems" onclick="showDiv('potential_problems');return false;"><span><?php echo _AC("potential_problems"); ?> (<?php echo $this->num_of_potential_problems_no_decision; ?>)</span></a></li>
			<li class="navigation"><a href="checker/index.php#output_div" accesskey="4" title="<?php echo _AC("html_validation_result"); ?> Alt+4" id="menu_html_validation_result" onclick="showDiv('html_validation_result');return false;"><span><?php echo _AC("html_validation_result"); ?> <?php if (isset($_POST["enable_html_validation"])) echo "(".$this->num_of_html_errors.")"; ?></span></a></li>
		</ul>
	</div>

	<div id="errors">
<?php

if (isset($this->aValidator))
{
	if ($this->num_of_errors > 0)
		echo $this->a_rpt->getErrorRpt();
	else
		echo "<span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_no_known") ."</span>";
}

?>
	</div>

	<div id="likely_problems">
<?php

if (isset($this->aValidator))
{
	if ($this->num_of_likely_problems > 0)
		echo $this->a_rpt->getLikelyProblemRpt();
	else
		echo "<span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_no_likely") ."</span>";
}

?>
	</div>

	<div id="potential_problems" style="margin-top:1em">
<?php

if (isset($this->aValidator))
{
	if ($this->num_of_potential_problems > 0)
		echo $this->a_rpt->getPotentialProblemRpt();
	else
		echo "<span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_no_potential") ."</span>";
}

?>
	</div>

	<div id="html_validation_result" style="margin-top:1em">
<?php
if (isset($this->htmlValidator))
{
	echo '		<ol><li class="msg_err">'. _AC("html_validator_provided_by") .'</li></ol>'. "\n";
	
	if ($this->htmlValidator->containErrors())
		echo $this->htmlValidator->getErrorMsg();
	else
	{
		if ($this->num_of_html_errors > 0)
			echo $this->htmlValidator->getValidationRpt();
		else
			echo "<span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". _AC("congrats_html_validation") ."</span>";
	}
}
else
	echo '<span class="info_msg"><img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("html_validator_disabled").'</span>';
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
<script language="JavaScript" type="text/javascript">
<!--

// show and highlight the given div, hide other output divs. 
function showDiv(divName)
{
	window.location.hash = 'output_div';
	// all ids of dives to hide/show
	var allDivIDs = new Array("errors", "likely_problems", "potential_problems", "html_validation_result");
	var i;
	
	for (i in allDivIDs)
	{
		if (allDivIDs[i] == divName)
		{
			document.getElementById(allDivIDs[i]).style.display = 'block';
			eval('document.getElementById("menu_'+ allDivIDs[i] +'").className = "active"');
		}
		else
		{
			document.getElementById(allDivIDs[i]).style.display = 'none';
			eval('document.getElementById("menu_'+ allDivIDs[i] +'").className = ""');
		}
	}

	// hide button "make decision" when "known problems" tab is selected
	if (divName == "errors")
	{
		document.getElementById('make_decision').style.display = 'none';
	}
	else	
	{
		document.getElementById('make_decision').style.display = 'block';
	}
}
//-->
</script>
