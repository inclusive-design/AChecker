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

?>

<div class="seq">
	<?php if (isset($this->prev_page)): ?>
		<?php echo _AC('previous_chapter'); ?>: <a href="frame_content.php?p=<?php echo $this->prev_page; ?>" accesskey="," title="<?php echo _AC($this->pages[$this->prev_page]['title_var']); ?> Alt+,"><?php echo _AC($this->pages[$this->prev_page]['title_var']); ?></a><br />
	<?php endif; ?>

	<?php if (isset($this->next_page)): ?>
		<?php echo _AC('next_chapter'); ?>: <a href="frame_content.php?p=<?php echo $this->next_page; ?>" accesskey="," title="<?php echo _AC($this->pages[$this->next_page]['title_var']); ?> Alt+,"><?php echo _AC($this->pages[$this->next_page]['title_var']); ?></a><br />
	<?php endif; ?>
</div>

<div class="tag">
	All text is available under the terms of the GNU Free Documentation License. 
</div>
</body>
</html>