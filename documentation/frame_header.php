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

if (isset($_GET['p'])) {
	$this_page = htmlentities($_GET['p']);
} else {
	exit;
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo _AC('achecker_documentation'); ?></title>
<style type="text/css">
body { font-family: Verdana,Arial,sans-serif; font-size: x-small; margin: 0px; padding: 3px; background: #f4f4f4; color: #afafaf; }
ul, ol { list-style: none; padding-left: 0px; margin-left: -15px; }
li { margin-left: 19pt; padding-top: 2px; }
a { text-decoration: none; }
a:link, a:visited { color: #006699; }
a:hover { color: #66AECC; }
input { border: 0px; padding: 2px 5px 2px 5px; }
input[type=submit] { background-color: #dfdfdf; padding: 1px; border:  #AAA  solid 1px; }
input[type=submit]:hover { color: blue; background-color: #eee; padding: 1px; }
form { padding: 0px; margin: 0px; display: inline; }
</style>
<script type="text/javascript">
// <!--
var currentPage;

function showTocToggle(show, hide) {
	if(document.getElementById) {
		document.writeln('<a href="javascript:toggleToc(false)">' +
		'<span id="showlink" style="display:none;">' + show + '</span>' +
		'<span id="hidelink">' + hide + '</span>'	+ '</a>');
	}
}
function toggleToc(override) {
	var showlink=document.getElementById('showlink');
	var hidelink=document.getElementById('hidelink');

	if (override && (hidelink.style.display == 'none')) {
		top.show();
		hidelink.style.display='';
		showlink.style.display='none';
	} else if (!override && (hidelink.style.display == 'none')) {
		top.show();
		hidelink.style.display='';
		showlink.style.display='none';
	} else if (!override) {
		top.hide(); //('0, *');
		hidelink.style.display='none';
		showlink.style.display='';
	}
}
// -->
</script>
</head>
<body><form method="get" action="search.php" target="toc" onsubmit='toggleToc(true);false;'>
<input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">

<script type="text/javascript">
// <!--
if (top.name == 'popup') {
	document.write('<a href="javascript:top.close();"><?php echo _AC('close_popup'); ?></a> | ');
}
// -->
</script>

<input type="text" name="query" /> <input type="submit" name="search" value="<?php echo _AC('search'); ?>" /> |  
<a href="print.php?p=<?php echo $this_page; ?>" target="_top"><?php echo _AC('print_version'); ?></a>

<script type="text/javascript">
//<!--
document.writeln(' | ');
showTocToggle('<?php echo _AC('show_contents'); ?>' ,'<?php echo _AC('hide_contents'); ?>');
if (top.name == 'popup') {
	toggleToc(true);
}
//-->
</script>

</form>
</body>
</html>