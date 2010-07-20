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

// Simo: aggiunto header

global $onload;
$onload="initial()"; 
include_once(AC_INCLUDE_PATH.'header.inc.php');

if (isset($this->error)) echo $this->error;


	$link_home = "
			<div style='margin-left:60px; margin-right:60px; margin-bottom:10px; padding-bottom:1px; text-align:left; '>
				<a   title='Esegui una nuova validazione' style='text-decoration:none;border:0px solid #e0e0e0; padding:4px; align:left; font:Verdana, Helvetica, Arial, sans-serif; font-weight: bold; font-size: small; margin-left: 4px;' 
				href='".$_SERVER['PHP_SELF']."'><img style='border:none; height: 10px; width: 10px; padding-right:3px' src='themes/vamola/images/arrow-left.png' alt='' />Esegui una nuova validazione</a>
			</div>
		";

	echo $link_home;

	
	
// display seals
/*
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
*/

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
	<!--
	<fieldset class="group_form"><legend class="group_form"><?php echo _AC("accessibility_review"); ?></legend>
	-->
	<div class="result">
	
	<h3><?php echo _AC("accessibility_review") /*. ' ('. _AC("guidelines"). ': '.$this->guidelines_text. ')'*/; ?></h3>
	
	
	
		<?php 
		if ($_SESSION["show"]==="all")
		{
			?>
			<div class="topnavlistcontainer"><br />
				<ul class="navigation" id="topnavlist_up">
					<li class="navigation"><a href="checker/index.php?tab_ris=1" accesskey="1" title="<?php echo _AC("errors_10"); ?> Alt+1" id="menu_errors_10" ><span> <?php echo _AC("errors_10"); ?> (<?php echo $this->num_of_errors_10 + $this->num_of_html_errors + $this->num_of_css_errors + 0 ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=2" accesskey="2" title="<?php echo _AC("errors_11"); ?> Alt+2" id="menu_errors_11" ><span> <?php echo _AC("errors_11"); ?> (<?php echo $this->num_of_errors_11 + 0 ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=3" accesskey="3" title="<?php echo _AC("errors_12"); ?> Alt+3" id="menu_errors_12" ><span> <?php echo _AC("errors_12"); ?> (<?php echo $this->num_of_errors_12 + 0 ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=4" accesskey="4" title="<?php echo _AC("errors_13"); ?> Alt+4" id="menu_errors_13" ><span> <?php echo _AC("errors_13"); ?> (<?php echo $this->num_of_errors_13 + 0 ; ?>)</span></a></li>
				</ul>
				<br/>
				<ul class="navigation" id="topnavlist_bottom" style="margin-top:3px">
					<li class="navigation"><a href="checker/index.php?tab_ris=5" accesskey="5" title="<?php echo _AC("known_problems"); ?> Alt+5" id="menu_errors" ><span> <?php echo _AC("known_problems"); ?> (<?php echo $this->num_of_errors ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=6" accesskey="6" title="<?php echo _AC("likely_problems"); ?> Alt+6" id="menu_likely_problems" ><span> <?php echo _AC("likely_problems"); ?> (<?php echo $this->num_of_likely_problems ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=7" accesskey="7" title="<?php echo _AC("potential_problems"); ?> Alt+7" id="menu_potential_problems" ><span> <?php echo _AC("potential_problems"); ?> (<?php echo $this->num_of_potential_problems ; ?>)</span></a></li>
				</ul>
			</div>
		<?php		
		}
		else if ($_SESSION["show"]==="wcag")
		{	
			?>
			<div class="topnavlistcontainer"><br />
				<ul class="navigation" id="topnavlist_bottom">
					<li class="navigation"><a href="checker/index.php?tab_ris=5" accesskey="5" title="<?php echo _AC("known_problems"); ?> Alt+5" id="menu_errors" ><span> <?php echo _AC("known_problems"); ?> (<?php echo $this->num_of_errors ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=6" accesskey="6" title="<?php echo _AC("likely_problems"); ?> Alt+6" id="menu_likely_problems" ><span> <?php echo _AC("likely_problems"); ?> (<?php echo $this->num_of_likely_problems ; ?>)</span></a></li>
					<li class="navigation"><a href="checker/index.php?tab_ris=7" accesskey="7" title="<?php echo _AC("potential_problems"); ?> Alt+7" id="menu_potential_problems" ><span> <?php echo _AC("potential_problems"); ?> (<?php echo $this->num_of_potential_problems ; ?>)</span></a></li>
				</ul>
			</div>
		<?php
		}
		else if ($_SESSION["show"]==="stanca")
		{
			?>
			<div class="topnavlistcontainer"><br />
				<ul class="navigation" id="topnavlist_up">
					<li class="navigation"><a href="checker/index.php?tab_ris=1" accesskey="1" title="<?php echo _AC("errors_10"); ?> Alt+1" id="menu_errors_10" ><span> <?php echo _AC("errors_10"); ?> (<?php echo $this->num_of_errors_10 + $this->num_of_html_errors + $this->num_of_css_errors + 0 ; ?>)</span></a></li>
<!--//					<li class="navigation"><a href="checker/index.php?tab_ris=2" accesskey="2" title="<?php echo _AC("errors_11"); ?> Alt+2" id="menu_errors_11" ><span> <?php echo _AC("errors_11"); ?> (<?php echo $this->num_of_errors_11 + 0 ; ?>)</span></a></li>
    //					<li class="navigation"><a href="checker/index.php?tab_ris=3" accesskey="3" title="<?php echo _AC("errors_12"); ?> Alt+3" id="menu_errors_12" ><span> <?php echo _AC("errors_12"); ?> (<?php echo $this->num_of_errors_12 + 0 ; ?>)</span></a></li>
    //					<li class="navigation"><a href="checker/index.php?tab_ris=4" accesskey="4" title="<?php echo _AC("errors_13"); ?> Alt+4" id="menu_errors_13" ><span> <?php echo _AC("errors_13"); ?> (<?php echo $this->num_of_errors_13 + 0 ; ?>)</span></a></li>
-->
				</ul>
				</div>
		<?php
		}
		else if ($_SESSION["show"]==="alternative")
		{
			echo ('
				<ul class="topnavlist" id="topnavlist_up">
					<li><a href="/checker/index.php?tab_ris=8" accesskey="1" title="'. _AC("errors_10"). 'Alt+1" id="menu_errors_10" >'. _AC("errors_10").'<span class="small_font">('. ($this->num_of_errors + $this->num_of_errors_10 + $_SESSION[risultati]["num_of_html_errors"] + $_SESSION[risultati]["num_of_css_errors"]) .')</span></a></li>
					<li><a href="/checker/index.php?tab_ris=9" accesskey="2" title="'. _AC("errors_11").' Alt+2" id="menu_errors_11" >'. _AC("errors_11").'<span class="small_font">('. ($this->num_of_likely_problems+$this->num_of_errors_11 + 0) . ')</span></a></li>
					<li><a href="/checker/index.php?tab_ris=10" accesskey="3" title="'. _AC("errors_12").' Alt+3" id="menu_errors_12" >'. _AC("errors_12").'<span class="small_font">('. ($this->num_of_potential_problems+$this->num_of_errors_12 + 0) . ')</span></a></li>
					<li><a href="/checker/index.php?tab_ris=11" accesskey="4" title="'. _AC("errors_13").' Alt+4" id="menu_errors_13" >'. _AC("errors_13").'<span class="small_font">('. ($this->num_of_errors_13 + 0) . ')</span></a></li>		
				</ul>
			');
		}
	?>
	
<?php
	if (isset($this->aValidator))
	{	
		$_SESSION["risultati"]["errors"] = $this->a_rpt->getErrorRpt();
		$_SESSION["risultati"]["likely_problems"] = $this->a_rpt->getLikelyProblemRpt();
		$_SESSION["risultati"]["potential_problems"] = $this->a_rpt->getPotentialProblemRpt();
		
		$_SESSION["risultati"]["errors_10"] = $this->a_rpt->getErrors10Rpt();
		$_SESSION["risultati"]["errors_11"] = $this->a_rpt->getErrors11Rpt();
		$_SESSION["risultati"]["errors_12"] = $this->a_rpt->getErrors12Rpt();
		$_SESSION["risultati"]["errors_13"] = $this->a_rpt->getErrors13Rpt();
	}
	if (isset($this->htmlValidator))
	{
		$_SESSION["risultati"]["htmlValidator"] = 1;
		
		if ($this->htmlValidator->containErrors())
		{
			// Se � settata la chiamata al validatore ha dato errori
			$_SESSION["risultati"]["html_validator_errors"] = $this->htmlValidator->getErrorMsg();
		}
		else
		{	// Simo: Se la chiamata al validatore markup non ha restituito false, prendo il doctype
			if (trim($this->htmlValidator->getDoctype()) != "")
				$_SESSION["risultati"]["doctype"] = $this->htmlValidator->getDoctype();
			
			$_SESSION["risultati"]["html_errors"] = $this->htmlValidator->getValidationRpt();			
		}		
	}
	
	if (isset($this->cssValidator))
	{
		$_SESSION["risultati"]["cssValidator"] = 1;
		
		if ($this->cssValidator->containErrors())
		{
			$_SESSION["risultati"]["css_validator_errors"] = $this->cssValidator->getErrorMsg();
		}
		else
		{
			$_SESSION["risultati"]["css_errors"]=$this->cssValidator->getValidationRpt();
		}
			
	}
?>	
	
	
		

<?php
	//Simo: Genero i vari tab con gli errori
	if ((isset($_SESSION["risultati"]["errors_10"]) && !isset($_SESSION["tab_ris"]))||
		(isset($_SESSION["risultati"]["errors_10"]) && $_SESSION["tab_ris"] == 1))
	{
		// Prende in input l'id per il div, il testo da inserire nell'h3, il numero degli errori, la sezione di errori, il testo da stampare nel caso di nessun errrore)
		echo generateErrorSection("errors_10", "Errori rilevati da VaMoL&agrave;", $this->num_of_errors_10, $_SESSION["risultati"]["errors_10"], _AC("congrats_no_errors_10"));
	
		// Simo: Risultati validatore W3C Markup	
		if (isset($_SESSION["risultati"]["htmlValidator"]))
		{
			echo generateW3CErrorSection("markup_validator", "Errori riportati dal validatore HTML", $this->num_of_html_errors, $_SESSION["risultati"]["html_errors"], _AC("congrats_html_validation"), $_SESSION["risultati"]["html_validator_errors"], _AC("html_validator_provided_by"), $_SESSION["risultati"]["doctype"]);
		}	
		/*
		//MB rimuovo il messaggio di avvertimento del validatore html disabilitato
		else
			echo '<span class="info_msg"><img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("html_validator_disabled").'</span>';
		*/
		// Simo: Risultati validatore W3C CSS	
		
		
		if (isset($_SESSION["risultati"]["cssValidator"]))
		{
			echo generateW3CErrorSection("css_validator", "Errori riportati dal validatore CSS", $this->num_of_css_errors, $_SESSION["risultati"]["css_errors"], "Congratulazioni! Nessun errore trovato nei CSS.", $_SESSION["risultati"]["css_validator_errors"], "<strong>Nota: i seguenti risultati sono generati dal validatore CSS disponibile al sito http://jigsaw.w3.org/css-validator</strong>", "");
		}
		
		elseif (isset($_SESSION["req"][0]) && $_SESSION["req"][0]==100)
		{
			/// mostro questo messaggio solo per upload con req 1
			echo '<div id="css_validator">
					<h3 class="msg_err"">  Validatore CSS disabilitato</h3><br/>
					
					<ul class="msg_err">
      				<li class="msg_err">
         			<span class="err_type"><img src="images/info.png" alt="Info" title="Info" width="15" height="15" /></span>

					<span class="msg">
						<em>Il controllo sui CSS e\' disponibile solamente per la validazione tramite URL</em>
					</span>
					</li>
					</ul>
					</div>';
		}
		
			
	}	

	if (isset($_SESSION["risultati"]["errors_11"]) && $_SESSION["tab_ris"] == 2)
	{
		echo generateErrorSection("errors_11", _AC("errors_11"), $this->num_of_errors_11, $_SESSION["risultati"]["errors_11"], _AC("congrats_no_errors_11"));
	}

	
	if (isset($_SESSION["risultati"]["errors_12"]) && $_SESSION["tab_ris"] == 3)
	{
		echo generateErrorSection("errors_12", _AC("errors_12"), $this->num_of_errors_12, $_SESSION["risultati"]["errors_12"], _AC("congrats_no_errors_12"));
	}

	
	if (isset($_SESSION["risultati"]["errors_13"]) && $_SESSION["tab_ris"] == 4)
	{
		echo generateErrorSection("errors_13", _AC("errors_13"), $this->num_of_errors_13, $_SESSION["risultati"]["errors_13"], _AC("congrats_no_errors_13"));
	}

	
	if (isset($_SESSION["risultati"]["errors"]) && $_SESSION["tab_ris"] == 5)
	{
		echo generateErrorSection("errors", _AC("errors"), $this->num_of_errors, $_SESSION["risultati"]["errors"], _AC("congrats_no_known"));
	}

	
	if (isset($_SESSION["risultati"]["likely_problems"]) && $_SESSION["tab_ris"] == 6)
	{
		echo generateErrorSection("likely_problems", _AC("likely_problems"), $this->num_of_likely_problems, $_SESSION["risultati"]["likely_problems"], _AC("congrats_no_likely"));
	}

	
	if (isset($_SESSION["risultati"]["potential_problems"]) && $_SESSION["tab_ris"] == 7)
	{
		echo generateErrorSection("potential_problems", _AC("potential_problems"), $this->num_of_potential_problems, $_SESSION["risultati"]["potential_problems"], _AC("congrats_no_potential"));
	}
?>
	
<?php
	if (isset($_SESSION["risultati"]["errors_10"]) && isset($_SESSION["risultati"]["errors"]) && $_SESSION["tab_ris"] == 8)
	{
		echo '<ul id="mixed_errors" style="margin-top:1em">';
		
		echo '<li>';
		$num_of_errors = $this->num_of_errors_10 + $this->num_of_html_errors + $this->num_of_css_errors;
		$error_string = " (". $num_of_errors . " errori in totale)";
		if ($num_of_errors == 1)
				$error_string = str_replace("errori", "errore", $error_string);	
		echo ("<h3 class='msg_err'>Legge 04/2004". $error_string ."</h3>");	

		echo generateErrorSection("errors_10", "Errori rilevati da VaMoL&agrave;", $this->num_of_errors_10, $_SESSION["risultati"]["errors_10"], _AC("congrats_no_errors_10"));
		// Simo: Risultati validatore W3C Markup	
		if (isset($_SESSION["risultati"]["htmlValidator"]))
		{
			echo generateW3CErrorSection("markup_validator", "Errori riportati dal validatore HTML", $this->num_of_html_errors, $_SESSION["risultati"]["html_errors"], _AC("congrats_html_validation"), $_SESSION["risultati"]["html_validator_errors"], _AC("html_validator_provided_by"), $_SESSION["risultati"]["doctype"]);
		}	
		/*
		//MB rimuovo il messaggio di avvertimento del validatore html disabilitato
		else
			echo '<span class="info_msg"><img src="'.AC_BASE_HREF.'images/info.png" width="15" height="15" alt="'._AC("info").'"/>  '._AC("html_validator_disabled").'</span>';
		*/
		// Simo: Risultati validatore W3C CSS	
		
		
		
		if (isset($_SESSION["risultati"]["cssValidator"]) )
		{
			echo generateW3CErrorSection("css_validator", "Errori riportati dal validatore CSS", $this->num_of_css_errors, $_SESSION["risultati"]["css_errors"], "Congratulazioni! Nessun errore trovato nei CSS.", $_SESSION["risultati"]["css_validator_errors"], "<strong>Nota: i seguenti risultati sono generati dal validatore CSS disponibile al sito http://jigsaw.w3.org/css-validator</strong>", "");
		}
		elseif (isset($_SESSION["req"][0]) && $_SESSION["req"][0]==100)
		{
			/// mostro questo messaggio solo per upload con req 1
			echo '<div id="css_validator">
					<h3 class="msg_err"">  Validatore CSS disabilitato</h3><br/>
					
					<ul class="msg_err">
      				<li class="msg_err">
         			<span class="err_type"><img src="images/info.png" alt="Info" title="Info" width="15" height="15" /></span>

					<span class="msg">
						<em>Il controllo sui CSS e\' disponibile solamente per la validazione tramite URL</em>
					</span>
					</li>
					</ul>
					
					</div>';
			
		}
		
		
		
		echo '</li>';
			
		echo '<li>';
		$num_of_errors = $this->num_of_errors;
		$error_string = " (". $num_of_errors . " errori in totale)";
		if ($num_of_errors == 1)
				$error_string = str_replace("errori", "errore", $error_string);	
		echo ("<h3 class='msg_err'>Linee guida WCAG 2.0". $error_string ."</h3>");
		echo generateErrorSection("errors", _AC("errors"), $this->num_of_errors, $_SESSION["risultati"]["errors"], _AC("congrats_no_known"));	
		echo '</li>';
		
		echo '</ul>';
	}
	
	
	if (isset($_SESSION["risultati"]["errors_11"]) && isset($_SESSION["risultati"]["likely_problems"]) && $_SESSION["tab_ris"] == 9)
	{
		echo '<ul id="mixed_errors" style="margin-top:1em">';
		
		echo '<li>';
		echo ("<h3 class='msg_err'>Legge 04/2004 (Legge Stanca)</h3>");	
		echo generateErrorSection("errors_11", _AC("errors_11"), $this->num_of_errors_11, $_SESSION["risultati"]["errors_11"], _AC("congrats_no_errors_11"));
		echo '</li>';
			
		echo '<li>';
		echo ("<h3 class='msg_err'>Linee guida WCAG 2.0</h3>");
		echo generateErrorSection("likely_problems", _AC("likely_problems"), $this->num_of_likely_problems, $_SESSION["risultati"]["likely_problems"], _AC("congrats_no_likely"));
		echo '</li>';
		
		echo '</ul>';
	}
	
		
	if (isset($_SESSION["risultati"]["errors_12"]) && isset($_SESSION["risultati"]["potential_problems"]) && $_SESSION["tab_ris"] == 10)
	{
		echo '<ul id="mixed_errors" style="margin-top:1em">';

		echo '<li>';
		echo ("<h3 class='msg_err'>Legge 04/2004 (Legge Stanca)</h3>");
		echo generateErrorSection("errors_12", _AC("errors_12"), $this->num_of_errors_12, $_SESSION["risultati"]["errors_12"], _AC("congrats_no_errors_12"));
		echo '</li>';	
		
		echo '<li>';
		echo ("<h3 class='msg_err'>Linee guida WCAG 2.0</h3>");
		echo generateErrorSection("potential_problems", _AC("potential_problems"), $this->num_of_potential_problems, $_SESSION["risultati"]["potential_problems"], _AC("congrats_no_potential"));
		echo '</li>';
		
		echo '</ul>';
	}
	
	if (isset($_SESSION["risultati"]["errors_13"]) && $_SESSION["tab_ris"] == 11)
	{
		echo '<ul id="mixed_errors" style="margin-top:1em">';

		echo '<li>';
		echo ("<h3 class='msg_err'>Legge 04/2004 (Legge Stanca)</h3>");
		echo generateErrorSection("errors_13", _AC("errors_13"), $this->num_of_errors_13, $_SESSION["risultati"]["errors_13"], _AC("congrats_no_errors_13"));
		echo '</li>';	
		
		echo '<li>';
		echo ("<h3 class='msg_err'>Linee guida WCAG 2.0 (tipologia di errore non disponibile)</h3>");
		echo '</li>';
		
		echo '</ul>';
	
	}
	
	
?>
<!--	
</fieldset>
-->
</div>	
	
	
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

<?php
		$link_home = "
			<div style='margin-left:60px; margin-right:60px; margin-bottom:45px; padding-bottom:1px; text-align:left; '>
				<a   title='Esegui una nuova validazione' style='text-decoration:none;border:0px solid #e0e0e0; padding:4px; align:left; font:Verdana, Helvetica, Arial, sans-serif; font-weight: bold; font-size: small; margin-left: 4px;' 
				href='".$_SERVER['PHP_SELF']."'><img style='border:none; height: 10px; width: 10px; padding-right:3px' src='themes/vamola/images/arrow-left.png' alt='' />Esegui una nuova validazione</a>
			</div>";

	echo $link_home;
?>

<?php
	function generateErrorSection($id, $h3, $num_of_errors, $errors, $no_errors)
	{
		
		$div = '<div id="'.$id.'">';
		$h3 = '<h3 class="msg_err">'. $h3;
		
		$h3 .= ' ('.$num_of_errors.' errori)';
			
		if ($num_of_errors == 1)
			$h3 = str_replace("errori", "errore",$h3);		
			$h3 .= '</h3>';
		// Simo: se ho errori
		
		if ($num_of_errors > 0)
			$errors_output = $errors;
		// Simo: nessun errore
		else 
			$errors_output = "<span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". $no_errors ."</span>";
		
		return $div ."\n".$h3 ."\n".$errors_output . "\n</div>\n";
	}
	
	
	function generateW3CErrorSection($id, $h3_in, $num_of_errors, $errors, $no_errors, $validator_errors, $validator_provided_by, $doctype)
	{
		$div = '<div id="'.$id.'">';
		$provided_by = '<ol><li class="msg_err">'. $validator_provided_by .'</li></ol>';
	
		$h3 = '<h3 class="msg_err">'. $h3_in . ' ('.$num_of_errors.' errori';
		// Simo: Doctype. Se non ha restituito false, visualizzo il doctype
		if ($doctype <> "")
			$h3 .= ", Doctype: " . $doctype;		
				
		$h3 .= ')';
			
		if ($num_of_errors == 1)
			$h3 = str_replace("errori", "errore",$h3);		

		$h3 .= '</h3>';
		
		if (isset($validator_errors))
		{
			$h3 = '<h3 class="msg_err">'. $h3_in. ' (n/a)</h3>';
			$errors_output = $validator_errors;
		}		
		else if ($num_of_errors > 0)
			$errors_output =  $errors;

		// Simo: nessun errore
		else 
		{
			$errors_output = "<span class='congrats_msg'><img src='".AC_BASE_HREF."images/feedback.gif' alt='"._AC("feedback")."' />  ". $no_errors ."</span>";	
		}
				
		return $div ."\n" .  $h3 ."\n" . $provided_by . "\n" . $errors_output . "\n</div>\n";
	}

?>	