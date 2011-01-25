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
 
global $_base_href;
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php if(isset($this->hidden_vars)): ?>
	<?php echo $this->hidden_vars; ?>
<?php endif; ?>

<div class="input-form">
	<div class="row">
		<?php if (is_array($this->item)) : ?>
			<?php foreach($this->item as $e) : ?>
				<p><?php echo $e; ?></p>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<div class="row buttons">
		<input type="submit" name="submit_yes" value="<?php echo $this->button_yes_text; ?>" /> 
<?php if(!$this->hide_button_no): ?>
		<input type="submit" name="submit_no" value="<?php echo $this->button_no_text; ?>" />
<?php endif; ?>
	</div>
</div>
</form>