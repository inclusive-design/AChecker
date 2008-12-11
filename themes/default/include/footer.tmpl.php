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

if (!defined('AT_INCLUDE_PATH')) { exit; }

global $languageManager, $_my_uri;

if($languageManager->getNumLanguages() > 1)
{
?>

<div align="center" id="lang" style="clear: left"><br />
<?php

	if ($languageManager->getNumLanguages() > 5) {
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

<div style="margin-left:auto; margin-right:auto; width:20em;">
	<small>Web site engine's code is copyright &copy; 2008</small><br />
	<div style="margin-left:auto; margin-right:auto; width:84px;">
		<a href="http://atrc.utoronto.ca/"><img width="84" src="<?php echo $this->base_path.'themes/'.$this->theme; ?>/images/atrclogo.gif" height="52" alt="Adaptive Technology Resource Centre" style="border:none;"/></a>
	</div>
</div>
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
debug($totaltime. ' seconds.', "TIME USED"); 
debug($_SESSION);
// Timer Ends

?>
