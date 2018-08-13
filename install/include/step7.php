<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

if (!defined('AC_INCLUDE_PATH')) { exit; }

print_progress($step);

?>
<p><strong>Congratulations on your installation of AChecker <?php echo $new_version; ?><i>!</i></strong></p>

<p>For security reasons once you have confirmed that AChecker has installed correctly, you should delete the <kbd>install/</kbd> directory,
and reset the permissions on the config.inc.php file to read only.</p>

<br />

<form method="get" action="../index.php">
	<div align="center">
		<input type="submit" name="submit" value="&raquo; Go To AChecker!" class="button" />
	</div>
</form>