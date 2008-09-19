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

?>
<div style="margin-left:auto; margin-right:auto; width:20em;">
	<small>Web site engine's code is copyright &copy; 2008</small><br />
	<div style="margin-left:auto; margin-right:auto; width:84px;"><a href="http://atrc.utoronto.ca/"><img width="84" src="images/atrclogo.gif" height="52" alt="Adaptive Technology Resource Centre" style="border:none;"/></a></div>
</div>
</body>
</html>

<?php
// Timer
$mtime = microtime(); 
$mtime = explode(" ", $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
debug ($totaltime. ' seconds.', "TIME USED"); 
// Timer Ends

?>
