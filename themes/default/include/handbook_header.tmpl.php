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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>" lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php _AC('achecker_documentation'); ?></title>
	<link rel="stylesheet" href="<?php echo $this->base_path.'themes/'.$this->theme; ?>/handbook_styles.css" type="text/css" />
</head>

<body onload="doparent();">
<script type="text/javascript">
// <!--
function doparent() {
	if (parent.toc && parent.toc.highlight) parent.toc.highlight('id<?php echo $this->this_page; ?>');
}
// -->
</script>

<div class="seq">
	<?php if (isset($this->prev_page)): ?>
		<?php echo _AC('previous_chapter'); ?>: <a href="frame_content.php?p=<?php echo $this->prev_page; ?>" accesskey="," title="<?php echo _AC($this->pages[$this->prev_page]['title_var']); ?> Alt+,"><?php echo _AC($this->pages[$this->prev_page]['title_var']); ?></a><br />
	<?php endif; ?>

	<?php if (isset($this->next_page)): ?>
		<?php echo _AC('next_chapter'); ?>: <a href="frame_content.php?p=<?php echo $this->next_page; ?>" accesskey="," title="<?php echo _AC($this->pages[$this->next_page]['title_var']); ?> Alt+,"><?php echo _AC($this->pages[$this->next_page]['title_var']); ?></a><br />
	<?php endif; ?>
</div>
