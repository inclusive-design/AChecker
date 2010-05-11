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

if (!defined('AC_INCLUDE_PATH')) { exit; }

global $languageManager, $_my_uri;

if($languageManager->getNumEnabledLanguages() > 1)
{
?>

<div align="center" id="lang" style="clear: left;"><br />
<?php

	if ($languageManager->getNumEnabledLanguages() > 5) {
		echo '<form method="get" action="'.htmlspecialchars($_my_uri, ENT_QUOTES).'">';
		echo '<label for="lang" style="display:none;">'._AC('translate_to').' </label>';
		$languageManager->printDropdown($_SESSION['lang'], 'lang', 'lang');
		echo ' <input type="submit" name="submit_language" class="button" value="'._AC('translate').'" />';
		echo '</form>';
	} else {
		echo '<small><label for="lang">'._AC('translate_to').' </label></small>';
		$languageManager->printList($_SESSION['lang'], 'lang', 'lang', htmlspecialchars($_my_uri));
	}
?>
</div>
<?php } ?>


</div> <!--  end center-content div -->
<div class="bottom"><span></span></div><!--  bottom for liquid-round theme -->
</div> <!-- end liquid-round div -->



<ul id="linklist">
	<li class="linkbar">
		<a class="bordo_toolbar" style="font-size: 12px" href="<?php echo $this->base_path ?>documentation_vamola/credits.php"><span lang="en" xml:lang="en">Credits</span></a> 
	</li>
	<li class="linkbar">
		<a class="bordo_toolbar" style="font-size: 12px" href="<?php echo $this->base_path ?>documentation_vamola/ringraziamenti.php">Ringraziamenti</a>
	</li>
	<li class="linkbar last">
		<a class="bordo_toolbar" style="font-size: 12px" href="<?php echo $this->base_path ?>documentation_vamola/faq.php">Faq</a>
	</li>
</ul>       



<!--
<div style="align:center;clear:both;margin-left:auto; width:15em;margin-right:auto;">
	<a href="documentation/web_service_api.php" title="<?php echo _AC("web_service_api"); ?>" target="_new"><?php echo _AC('web_service_api'); ?></a>
</div>
-->

</body>
</html>

<?php
// Timer, calculate how much time to load the page
// starttime is in include/header.inc.php
$mtime = microtime(); 
$mtime = explode(" ", $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 

if (defined('AC_DEVEL') && AC_DEVEL) 
{
	debug(TABLE_PREFIX, 'TABLE_PREFIX');
	debug(DB_NAME, 'DB_NAME');
	debug($totaltime. ' seconds.', "TIME USED"); 
	debug($_SESSION);
}
// Timer Ends

?>
