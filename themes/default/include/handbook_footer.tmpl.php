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
	<?php if (isset($prev_page)): ?>
		<?php echo _AC('previous_chapter'); ?>: <a href="frame_content.php?p=<?php echo $prev_page; ?>" accesskey="," title="<?php echo _AC($pages[$prev_page]['title_var']); ?> Alt+,"><?php echo _AC($pages[$prev_page]['title_var']); ?></a><br />
	<?php endif; ?>

	<?php if (isset($next_page)): ?>
		<?php echo _AC('next_chapter'); ?>: <a href="frame_content.php?p=<?php echo $next_page; ?>" accesskey="," title="<?php echo _AC($pages[$next_page]['title_var']); ?> Alt+,"><?php echo _AC($pages[$next_page]['title_var']); ?></a><br />
	<?php endif; ?>
</div>

<div class="tag">
	All text is available under the terms of the GNU Free Documentation License. 
</div>
</body>
</html>