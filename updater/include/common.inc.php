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

function print_errors( $errors, $notes='' ) {
	?>
	<div class="input-form">
	<table border="0" class="errbox" cellpadding="3" cellspacing="2" width="100%" summary="" align="center">
	<tr class="errbox">
		<td>
		<h3 class="err"><img src="images/bad.gif" align="top" alt="" class="img" /> Warning</h3>
		<?php
			echo '<ul>';
			foreach ($errors as $p) {
				echo '<li>'.$p.'</li>';
			}
			echo '</ul>';
		?>
		</td>
	</tr>
	<tr>
		<td>
		<?php echo $notes; ?>
		</td>
	</tr>
	</table>
	</div>
<?php
}

function print_feedback( $feedback, $notes='' ) {
	?>
	<div class="input-form">
	<table border="0" class="fbkbox" cellpadding="3" cellspacing="2" width="100%" summary="" align="center">
	<tr class="fbkbox">
	<td><h3 class="feedback2"><img src="images/feedback.gif" align="top" alt="" class="img" /> <?php echo _AC('AC_FEEDBACK_UPDATE_INSTALLED_SUCCESSFULLY')?></h3>
		<?php
			echo '<ul>';
			foreach ($feedback as $p) {
				echo '<li>'.$p.'</li>';
			}
			echo '</ul>';
		?></td>
	</tr>
	<tr>
		<td>
		<?php echo $notes; ?>
		</td>
	</tr>
	</table>
	</div>
<?php
}

/**
 * Check if the patch has been installed
 */
function is_patch_installed($patch_id)
{
	$patchesDAO = new PatchesDAO();
	$rows = $patchesDAO->getInstalledPatchByIDAndVersion($patch_id, VERSION);

	if (is_array($rows)) return true;
	else return false;
}

?>
