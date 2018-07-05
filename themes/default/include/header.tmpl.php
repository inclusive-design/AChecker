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

if (!defined('AC_INCLUDE_PATH')) { exit; }
/* available header.tmpl.php variables:
 * $lang_code			the ISO language code
 * SITE_NAME				the site name from the config file
 * $page_title		the name of this page to use in the <title>
 * top_level_pages           array(array('url', 'title'))     the top level pages. AChecker default creates tabs.
 * current_top_level_page    string                           full url to the current top level page in "top_leve_pages"
 * path                      array(array('url', 'title'))     the breadcrumb path to the current page.
 * sub_menus                 array(array('url', 'title'))     the sub level menus.
 * current_page              string                           full url to the current sub level page in the "sub_level_pages"
 * section_title             string                           the name of the current section. either name of the course, administration, my start page, etc.
 * page_title                string                           the title of the current page.
 * user_name                 string                           name of login user
 * $lang_charset		the ISO language character set
 * $base_path			the absolute path to this achecker installation
 * $theme				the directory name of the current theme
 * $img				the theme image
 * $custom_head		the custom head script used in <head> section
 * $$onload			the html body onload event

 * $content_base_href	the <base href> to use for this page
 * $rtl_css			if set, the path to the RTL style sheet
 * $icon			the path to a course icon
 * $banner_style		-deprecated-
 * $base_href			the full url to this achecker installation
 * $onload			javascript onload() calls
 * $img				the absolute path to this theme's images/ directory
 * $sequence_links	associative array of 'previous', 'next', and/or 'resume' links
 * $path				associative array of path to this page: aka bread crumbs
 * $rel_url			the relative url from the installation root to this page
 * $nav_courses		associative array of this user's enrolled courses
 * $section_title		the title of this section (course, public, admin, my start page)
 * $top_level_pages	associative array of the top level navigation
 * $current_top_level_page	the full path to the current top level page with file name
 * $sub_level_pages			associate array of sub level navigation
 * $back_to_page				if set, the path and file name to the part of this page (if parent is not a top level nav)
 * $current_sub_level_page	the full path to the current sub level page with file name
 * $guide				the full path and file name to the guide page
 * ======================================
 * back_to_page              array('url', 'title')            the link back to the part of the current page, if needed.
 */

$lang_charset = "UTF-8";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>" lang="<?php echo DEFAULT_LANGUAGE_CODE; ?>"> 

<head>
	<title><?php echo SITE_NAME; ?> : <?php echo $page_title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang_charset; ?>" />
	<meta name="Generator" content="AChecker - Copyright 2009 by ATRC http://atrc.utoronto.ca/" />
	<meta name="keywords" content="achecker,free, open source, accessibility checker, accessibility reviewer, accessibility evaluator, accessibility evaluation, WCAG evaluation, 508 evaluation, BITV evaluation, evaluate accessibility, test accessibility, review accessibility, ATRC, WCAG 2, STANCA, BITV, Section 508." />
	<meta name="description" content="AChecker is a Web accessibility evalution tool designed to help Web content developers and Web application developers ensure their Web content is accessible to everyone regardless to the technology they may be using, or their abilities or disabilities." />
	<base href="<?php echo $base_path; ?>" />
	<link rel="shortcut icon" href="<?php echo $base_path; ?>images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php echo $base_path.'themes/'.$theme; ?>/forms.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $base_path.'themes/'.$theme; ?>/styles.css" type="text/css" />
	<!--[if IE]>
	  <link rel="stylesheet" href="<?php echo $base_path.'themes/'.$theme; ?>/ie_styles.css" type="text/css" />
	<![endif]-->
	<script src="<?php echo $base_path; ?>jscripts/lib/jquery.js" type="text/javascript"></script>
	<script src="<?php echo $base_path; ?>jscripts/lib/jquery-URLEncode.js" type="text/javascript"></script>
	<script src="<?php echo $base_path; ?>jscripts/AChecker.js" type="text/javascript"></script>   
	<?php echo $rtl_css; ?>
	<?php echo $custom_head; ?>
</head>

<body onload="<?php echo $onload; ?>">

<?php if (isset($show_jump_to_report)){ ?>
<a href="checker/index.php#output_div"><img src="images/clr.gif" height="1" width="1" alt="<?php echo _AC("jump_to_report"); ?>" border="0" /></a>
<?php } ?>
<div id="liquid-round"><div class="top"><span></span></div>
<div class="center-content" id="center-content">
		<div id="logo">
			<a href="http://www.atutor.ca/achecker/"><img src="<?php echo $base_path.'themes/'.$theme; ?>/images/checker_logo.png"  alt="AChecker" style="border:none;" /></a>
		</div>
	<div id="banner">

	<span id="logininfo"> 
        <?php
        if (isset($user_name))
        {
          echo _AC('welcome'). ' '.$user_name;
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
		
	</div>

	<div class="topnavlistcontainer">
	<!-- the main navigation. in our case, tabs -->
		<ul class="navigation">
			<?php foreach ($top_level_pages as $page): ?>
				<?php $is_submenu_accessed = false; ?>
				<?php foreach ($sub_menus as $sub_menu) {
					if ($page['url'] == $sub_menu['url'] || (!empty($back_to_page['url']) && $page['url'] == $back_to_page['url'])){
						$is_submenu_accessed = true;
					}
				} ?>
				<?php if ($page['url'] == $current_top_level_page || $is_submenu_accessed): ?>
					<li class="navigation"><a href="<?php echo $page['url']; ?>" title="<?php echo $page['title']; ?>" class="active"><span class="nav"><?php echo $page['title']; ?></span></a></li>
				<?php else: ?>
					<li class="navigation"><a href="<?php echo $page['url']; ?>"  title="<?php echo $page['title']; ?>"><span class="nav"><?php echo $page['title']; ?></span></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>

	<!-- the sub navigation and guide -->
	<div id="sub-menu">
		<!-- guide -->
		<?php if (isset($guide)) {?>
		<div>
			<a href="<?php echo $guide; ?>" onclick="AChecker.popup('<?php echo $guide; ?>'); return false;" id="guide" target="_new" title="<?php echo _AC('achecker_handbook').': '.$page_title; ?>"><em><?php echo $page_title; ?></em></a>
		</div>
		<?php }?>

		<!-- the sub navigation -->
		<div id="sub-navigation">
		<?php if ($sub_menus): ?>
				<?php echo _AC('back_to');  ?>	        
			<?php $num_pages = count($sub_menus); ?>
			<?php for ($i=0; $i<$num_pages; $i++): ?>
				<?php if ($sub_menus[$i]['url'] == $current_page): ?>
					<strong><?php echo $sub_menus[$i]['title']; ?></strong>
				<?php else: ?>
					<a href="<?php echo $sub_menus[$i]['url']; ?>"><?php echo $sub_menus[$i]['title']; ?></a>
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


<a name="content" title="<?php echo _AC("content_start"); ?>"></a>
<?php global $msg; $msg->printAll();?>
