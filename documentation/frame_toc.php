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

define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');
include(AC_INCLUDE_PATH.'handbook_pages.inc.php');

/**
 * handbook toc printer
 * prints an unordered html list representation of the multidimensional array.
 * $handbook_pages    the array of items to print.
 * $section  the directory name of the files.
 */
function hb_print_toc($handbook_pages) {
	global $_pages;
	echo '<ul id="handbook-toc">';
	foreach ($handbook_pages as $page_key => $page_value) {
		echo '<li>';
		if (is_array($page_value)) 
		{
			if (isset($_pages[$page_key]))
			{
				echo '<a href="frame_content.php?p='.$page_key.'" id="id'.$page_key.'" class="tree">'._AC($_pages[$page_key]['title_var']).'</a>';
				hb_print_toc($page_value);
			}
		} else if (isset($_pages[$page_value])){
			echo '<a href="frame_content.php?p='.$page_value.'" id="id'.$page_value.'" class="leaf">'._AC($_pages[$page_value]['title_var']).'</a>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="<?php if ($missing_lang) { echo DEFAULT_LANGUAGE_CODE; } else { echo $req_lang; } ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php _AC('handbook_toc'); ?></title>
	<base target="body" />
<style type="text/css">
body { font-family: Verdana,Arial,sans-serif; font-size: x-small; margin: 0px; padding: 0px; background: #f4f4f4; margin-left: -5px; }
ul { list-style: none; padding-left: 0px; margin-left: -15px; }
li { margin-left: 19pt; padding-top: 2px; }
a { background-repeat: no-repeat; background-position: 0px 1px; padding-left: 12px; text-decoration: none; }
a.tree { background-image: url('../images/folder.gif'); }
a.leaf { background-image: url('../images/paper.gif'); }
a:link, a:visited { color: #006699; }
a:hover { color: #66AECC; }
</style>

<script type="text/javascript">
// <!--
function highlight(page) {
	if (page == false) {
		alert(parent.header.currentPage);
		if (parent.header.currentPage) {
			var toc = parent.toc.document.getElementById(parent.header.currentPage);
			toc.style.color = 'blue';
			toc.style.fontWeight = 'bold';
		}
	} else {
		if (parent.header.currentPage) {
			var toc = parent.toc.document.getElementById(parent.header.currentPage);
			toc.style.color = '';
			toc.style.fontWeight = '';
		}
	
		var toc = parent.toc.document.getElementById(page);
		toc.style.color = 'blue';
		toc.style.fontWeight = 'bold';
		parent.header.currentPage = page;
	}
}
// -->
</script>
</head>
<body onload="">
<?php
global $handbook_pages;

hb_print_toc($handbook_pages);
?>

</body>
</html>
