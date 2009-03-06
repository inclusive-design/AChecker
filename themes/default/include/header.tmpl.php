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

if (!defined('AC_INCLUDE_PATH')) { exit; }
/* available header.tmpl.php variables:
 * $this->lang_code			the ISO language code
 * SITE_NAME				the site name from the config file
 * $this->page_title		the name of this page to use in the <title>
 * top_level_pages           array(array('url', 'title'))     the top level pages. ATutor default creates tabs.
 * current_top_level_page    string                           full url to the current top level page in "top_leve_pages"
 * path                      array(array('url', 'title'))     the breadcrumb path to the current page.
 * sub_menus                 array(array('url', 'title'))     the sub level menus.
 * current_page              string                           full url to the current sub level page in the "sub_level_pages"
 * section_title             string                           the name of the current section. either name of the course, administration, my start page, etc.
 * page_title                string                           the title of the current page.
 * user_name                 string                           name of login user
 * $this->lang_charset		the ISO language character set
 * $this->base_path			the absolute path to this atutor installation
 * $this->theme				the directory name of the current theme
 * $this->img				the theme image
 * $this->custom_head		the custom head script used in <head> section
 * $this->$onload			the html body onload event

 * $this->content_base_href	the <base href> to use for this page
 * $this->rtl_css			if set, the path to the RTL style sheet
 * $this->icon			the path to a course icon
 * $this->banner_style		-deprecated-
 * $this->base_href			the full url to this atutor installation
 * $this->onload			javascript onload() calls
 * $this->img				the absolute path to this theme's images/ directory
 * $this->sequence_links	associative array of 'previous', 'next', and/or 'resume' links
 * $this->path				associative array of path to this page: aka bread crumbs
 * $this->rel_url			the relative url from the installation root to this page
 * $this->nav_courses		associative array of this user's enrolled courses
 * $this->section_title		the title of this section (course, public, admin, my start page)
 * $this->top_level_pages	associative array of the top level navigation
 * $this->current_top_level_page	the full path to the current top level page with file name
 * $this->sub_level_pages			associate array of sub level navigation
 * $this->back_to_page				if set, the path and file name to the part of this page (if parent is not a top level nav)
 * $this->current_sub_level_page	the full path to the current sub level page with file name
 * $this->guide				the full path and file name to the guide page
 * ======================================
 * back_to_page              array('url', 'title')            the link back to the part of the current page, if needed.
 */

$lang_charset = "UTF-8";

//Timer
$mtime = microtime(); 
$mtime = explode(' ', $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 
//Timer Ends

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 TRANSITIONAL//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>" lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>"> 

<head>
	<title><?php echo SITE_NAME; ?> : <?php echo $this->page_title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->lang_charset; ?>" />
	<meta name="Generator" content="Checker - Copyright 2008 by http://checker.atrc.utoronto.ca" />
	<base href="<?php echo $this->base_path; ?>" />
	<link rel="shortcut icon" href="<?php echo $this->base_path; ?>images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $this->base_path.'themes/'.$this->theme; ?>/forms.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->base_path.'themes/'.$this->theme; ?>/styles.css" type="text/css" />
	<?php echo $this->rtl_css; ?>
	<?php echo $this->custom_head; ?>

	<script type="text/javascript">
	//<!--
	var newwindow;
	function popup(url) 
	{
		newwindow=window.open(url,'popup','height=600,width=800,scrollbars=yes,resizable=yes');
		if (window.focus) {newwindow.focus()}
	}
	
	function toggleToc(objId) {
		var toc = document.getElementById(objId);
		if (toc == null) return;

		if (toc.style.display == 'none')
		{
			toc.style.display = '';
			document.getElementById("toggle_image").src = "images/arrow-open.png";
			document.getElementById("toggle_image").alt = "Collapse";
			document.getElementById("toggle_image").title = "Collapse";
		}
		else
		{
			toc.style.display = 'none';
			document.getElementById("toggle_image").src = "images/arrow-closed.png";
			document.getElementById("toggle_image").alt = "Expand";
			document.getElementById("toggle_image").title = "Expand";
		}
	}

	//-->
	</script>

</head>

<body onload="<?php echo $this->onload; ?>">

	<div id="banner">
		<a href="http://www.atutor.ca/achecker/"><img width="145" src="<?php echo $this->base_path.'themes/'.$this->theme; ?>/images/checker_logo.gif" height="43" alt="AChecker" style="border:none;" /></a>
		<h1 style="vertical-align:super;"><?php echo _AC("web_accessibility_checker"); ?>
			<span id="logininfo">
        <?php
        if (isset($this->user_name))
        {
          echo _AC('welcome'). ' '.$this->user_name;
        ?>
				&nbsp;&nbsp;
				<a href="<?php echo AC_BASE_HREF; ?>logout.php" ><?php echo _AC('logout'); ?></a>
        <?php
        }
        else
        {
        ?>
				<a href="<?php echo AC_BASE_HREF; ?>login.php" ><?php echo _AC('login'); ?></a>
				&nbsp;&nbsp;
				<a href="<?php echo AC_BASE_HREF; ?>register.php" ><?php echo _AC('register'); ?></a>
        <?php
        }
        ?>
			</span>
			<span id="versioninfo">
				<!--<a href="<?php echo AC_BASE_HREF; ?>translator.php" target="_blank"><?php echo _AC('help_with_translate'); ?></a>-->
				&nbsp;
				Version <?php echo VERSION; ?> Beta
			</span>
		</h1>
	</div>

	<div class="topnavlistcontainer">
	<!-- the main navigation. in our case, tabs -->
		<ul class="topnavlist">
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

	<!-- the sub navigation and guide -->
	<div id="sub-menu">
		<!-- guide -->
		<?php if (isset($this->guide)) {?>
		<div id="guide">
			<a href="<?php echo $this->guide; ?>" onclick="popup('<?php echo $this->guide; ?>'); return false;" target="_new"><em><?php echo $this->page_title; ?></em></a>
		</div>
		<?php }?>

		<!-- the sub navigation -->
		<div id="sub-navigation">
		<?php if ($this->sub_menus): ?>
			<?php if (isset($this->back_to_page)): ?>
				<a href="<?php echo $this->back_to_page['url']; ?>" id="back-to"><?php echo _AC('back_to').' '.$this->back_to_page['title']; ?></a> | 
			<?php endif; ?>
	
			<?php $num_pages = count($this->sub_menus); ?>
			<?php for ($i=0; $i<$num_pages; $i++): ?>
				<?php if ($this->sub_menus[$i]['url'] == $this->current_page): ?>
					<strong><?php echo $this->sub_menus[$i]['title']; ?></strong>
				<?php else: ?>
					<a href="<?php echo $this->sub_menus[$i]['url']; ?>"><?php echo $this->sub_menus[$i]['title']; ?></a>
				<?php endif; ?>
				<?php if ($i < $num_pages-1): ?>
					|
				<?php endif; ?>
			<?php endfor; ?>
		<?php else: ?>
			&nbsp;
		<?php endif; ?>
		</div>
	</div>

<!--<div>
	 the bread crumbs 
	<div id="breadcrumbs">
		<?php if (is_array($this->path)) {?>
		<?php foreach ($this->path as $page){ ?>
			<a href="<?php echo $page['url']; ?>"><?php echo $page['title']; ?></a> > 
		<?php }} echo $this->page_title; ?>
	</div>

	<?php if (isset($this->guide)) {?>
		<a href="<?php echo $this->guide; ?>" id="guide" onclick="poptastic('<?php echo $this->guide; ?>'); return false;" target="_new"><em><?php echo $this->page_title; ?></em></a>
	<?php } ?>
</div>
-->

<?php global $msg; $msg->printAll();?>
