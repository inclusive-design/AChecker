<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

if (!defined('AT_INCLUDE_PATH')) { exit; }

$lang_charset = "UTF-8";

//Timer
$mtime = microtime(); 
$mtime = explode(' ', $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 
//Timer Ends

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 TRANSITIONAL//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 

<head>
	<title><?php echo SITE_NAME; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->lang_charset; ?>" />
	<meta name="Generator" content="Checker - Copyright 2008 by http://checker.atrc.utoronto.ca" />
	<base href="<?php echo $this->base_path; ?>" />
	<link rel="shortcut icon" href="<?php echo $this->base_path; ?>images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $this->base_path.'themes/'.$this->theme; ?>/forms.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->base_path.'themes/'.$this->theme; ?>/styles.css" type="text/css" />
	<?php echo $this->custom_head; ?>
	<script type="text/javascript">
	//<!--
	var newwindow;
	function popup(url) 
	{
		newwindow=window.open(url,'popup','height=600,width=800,scrollbars=yes,resizable=yes');
		if (window.focus) {newwindow.focus()}
	}
	
	function initial()
	{
		// hide guideline div
		document.getElementById("div_options").style.display = 'none';
		
		var div_error = document.getElementById("errors");
		
		if (div_error != null)
		{
			// show tab "errors", hide other tabs
			div_error.style.display = 'block';
			document.getElementById("likely_problems").style.display = 'none';
			document.getElementById("potential_problems").style.display = 'none';
			document.getElementById("html_validation_result").style.display = 'none';

			// highlight tab "errors"
			document.getElementById("menu_errors").className = 'active';
		}
		else
			document.input_form.uri.focus();
	}
	
	function toggleToc(objId) {
		var toc = document.getElementById(objId);
		if (toc == null) return;

		if (toc.style.display == 'none')
		{
			toc.style.display = '';
			document.getElementById("toggle_image").src = "images/arrow-open.png";
			document.getElementById("toggle_image").alt = "Collapse Guidelines";
			document.getElementById("toggle_image").title = "Collapse Guidelines Getting Started";
		}
		else
		{
			toc.style.display = 'none';
			document.getElementById("toggle_image").src = "images/arrow-closed.png";
			document.getElementById("toggle_image").alt = "Expand Guidelines";
			document.getElementById("toggle_image").title = "Expand Guidelines Getting Started";
		}
	}
	//-->
	</script>

</head>

<body onload="initial(); <?php echo $this->onload; ?>">

	<div id="banner">
		<a href="http://www.atutor.ca/achecker/"><img width="145" src="<?php echo $this->base_path.'themes/'.$this->theme; ?>/images/checker_logo.gif" height="43" alt="AChecker" style="border:none;" /></a>
		<h1 style="vertical-align:super;"><?php echo _AC("web_accessibility_checker"); ?>
			<span id="versioninfo">
				<a href="<?php echo AT_BASE_HREF; ?>translator.php" target="_blank"><?php echo _AC('help_with_translate'); ?></a>
				&nbsp;
				Version 0.1 Beta
			</span>
		</h1>
	</div>

<?php 
if (count($this->top_level_pages) > 1) 
{
?>
	<div id="topnavlistcontainer">
	<!-- the main navigation. in our case, tabs -->
		<ul id="topnavlist">
			<?php foreach ($this->top_level_pages as $page): ?>
				<?php $accesscounter = 0; //initialize ?>
				<?php ++$accesscounter; $accesscounter = ($accesscounter == 10 ? 0 : $accesscounter); ?>
				<?php $accesskey_text = ($accesscounter < 10 ? 'accesskey="'.$accesscounter.'"' : ''); ?>
				<?php $accesskey_title = ($accesscounter < 10 ? ' Alt+'.$accesscounter : ''); ?>
				<?php if ($page['url'] == $this->current_top_level_page): ?>
					<li><a href="<?php echo $page['url']; ?>" <?php echo $accesskey_text; ?> title="<?php echo $page['title'] . $accesskey_title; ?>" class="active"><?php echo $page['title']; ?></a></li>
				<?php else: ?>
					<li><a href="<?php echo $page['url']; ?>" <?php echo $accesskey_text; ?> title="<?php echo $page['title'] . $accesskey_title; ?>"><?php echo $page['title']; ?></a></li>
				<?php endif; ?>
				<?php $accesscounter = ($accesscounter == 0 ? 11 : $accesscounter); ?>
			<?php endforeach; ?>
		</ul>
	</div>
<?php 
}
?>

<div>
	<!-- the bread crumbs -->
	<div id="breadcrumbs">
		<?php foreach ($this->path as $page){ ?>
			<a href="<?php echo $page['url']; ?>"><?php echo $page['title']; ?></a> > 
		<?php } echo $this->page_title; ?>
	</div>

	<?php if (isset($this->guide)) {?>
		<a href="<?php echo $this->guide; ?>" id="guide" onclick="poptastic('<?php echo $this->guide; ?>'); return false;" target="_new"><em><?php echo $this->page_title; ?></em></a>
	<?php } ?>
</div>

<?php global $msg; $msg->printAll(); ?>
