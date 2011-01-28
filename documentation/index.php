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

define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');
include(AC_INCLUDE_PATH.'handbook_pages.inc.php');

global $handbook_pages;

if (isset($_GET['p'])) {
	$p = htmlentities($_GET['p']);
} else {
	// go to first handbook page defined in $handbook_pages
	foreach ($handbook_pages as $page_key => $page_value)
	{
		if (is_array($page_key))
		{
			if (isset($_pages[$page_key])) $display_page = $page_key;
		}
		else
		{
			if (isset($_pages[$page_value])) $display_page = $page_value;
		}
		if (isset($display_page))
		{
			header('Location: index.php?p='.htmlentities($page_key));
			exit;
		}
	}
} 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 TRANSITIONAL//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>" lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo _AC('achecker_handbook'); ?></title>

<script type="text/javascript">

var i = 0;

function show2() {
	var fs = document.getElementById('frameset1');
	if (fs) {
		i += 5;
		if (i > 28) {
			i = 28;
		}
		fs.cols = i + '%, *';
	}
	if (i < 28) {
		window.setTimeout('show2()', 1);
	}
	return true;
}
function show() {
	i = 0;
	window.setTimeout('show2()', 1);
	return true;
}

function hide2() {
	var fs = document.getElementById('frameset1');
	if (fs) {
		i -= 5;
		if (i < 0) {
			i =0;
		}
		fs.cols = i + '%, *';
	}
	if (i > 0) {
		window.setTimeout('hide2()', 1);
	}
	return false;
}

function hide() {
	i= 28;
	window.setTimeout('hide2()', 1);
	return false;
}
</script>
</head>
<frameset rows="24,*">
	<frame src="frame_header.php?p=<?php echo $p; ?>" frameborder="0" name="header" title="header" scrolling="no" noresize="noresize">
	<frameset cols="22%, *" id="frameset1">
		<frame frameborder="0" scrolling="auto" marginwidth="0" marginheight="0" src="frame_toc.php" name="toc" id="toc" title="Table of Contents">
		<frame frameborder="0" src="frame_content.php?p=<?php echo $p; ?>" name="body" id="body" title="Content">
	</frameset>

	<noframes>
		<h1><?php echo _AC('achecker_handbook'); ?></h1>
		<p><a href="frame_toc.html">Table of Contents</a></p>
	 </noframes>
</frameset>

</html>
