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

global $handbook_pages, $_pages;

if (isset($_GET['p'])) {
	$this_page = htmlentities($_GET['p']);
} else {
	$this_page = 'index.php';
} 

function print_handbook($handbook_pages)
{
	global $_pages;
	
	foreach ($handbook_pages as $page_key => $page_value) 
	{
		if (is_array($page_value)) 
		{
			if (isset($_pages[$page_key]))
			{
				echo _AC($_pages[$page_key]['guide'])."<br /><br />";
				print_handbook($page_value);
			}
		} 
		else if (isset($_pages[$page_value]))
		{
			echo _AC($_pages[$page_value]['guide'])."<br /><br />";
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo _AC('doc_title'); ?></title>
	<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>

<a href="index.php?p=<?php echo $this_page; ?>"><?php echo _AC('back_to_chapters'); ?></a><br /><br />

<?php print_handbook($handbook_pages); ?>
</body>
</html>