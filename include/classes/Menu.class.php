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

/**
* Menu
* 1. Generate main menu items based on login user
* 2. Generate path in bread crumb
* @access	public
* @author	Cindy Qi Li
* @package	Menu
*/

define('AC_INCLUDE_PATH', '../../include/');

class Menu {

	// all private
	var $pages;                               // top tab pages
	var $current_page;                        // current page
	var $root_page;                           // root page relative to current page
	var $breadcrumb_path = array();           // array of breadcrumb path

	/**
	* Constructor: Initialize top pages (tab menu), all pages accessible by current user, current page.
	* Generate top tab menu items based on session user_id. If no user login in (public view), use public menu
	* @access  public
	* @param   None
	* @author  Cindy Qi Li
	*/
	function Menu()
	{
		$this->pages[AC_NAV_TOP] = array();        // top tab pages
//		unset($_SESSION['user_id']);
//		$_SESSION['user_id'] = 2;
		
		$this->init();           // Initialize $this->pages[AC_NAV_PUBLIC] & $this->pages
		$this->setTopPages();    // set top pages based on user id
		
		// decide current page.
		// if the page that user tries to access is from one of the public link 
		// but not define in user's priviledge pages, re-direct to the first $this->pages[AC_NAV_TOP]
		$this->setCurrentPage();
	}

	/**
	* initialize: public accessible items ($this->pages[AC_NAV_PUBLIC]); all accessible pages ($this->pages)
	* @access  private
	* @param   user id
	* @return  true
	* @author  Cindy Qi Li
	*/
	function init()
	{
		// $_pages is defined in include/constants.inc.php
		global $_pages, $db, $_base_path;
		
		// initialize $this->pages
		$this->pages = $_pages;
		// end of initializing $this->pages
		
		// initialize $this->pages[AC_NAV_PUBLIC]
		$this->pages[AC_NAV_PUBLIC] = $_pages[AC_NAV_PUBLIC];
		
		$sql = 'SELECT privilege_id, title_var, link 
						FROM '.TABLE_PREFIX.'privileges p
						WHERE open_to_public = 1
						ORDER BY p.menu_sequence';
		$result	= mysql_query($sql, $db) or die(mysql_error());
	
		while ($row = mysql_fetch_assoc($result))
		{
			$this->pages[AC_NAV_PUBLIC] = array_merge($this->pages[AC_NAV_PUBLIC], array($row['link'] => array('title_var'=>$row['title_var'], 'parent'=>AC_NAV_TOP)));
		}
		// end of initializing $this->pages[AC_NAV_PUBLIC]
		
		return true;
	}

	/**
	* Set top pages array based on login user's priviledge. If there's no login user, use priviledges that are open to public.
	* @access  private
	* @param   none
	* @return  true
	* @author  Cindy Qi Li
	*/
	function setTopPages()
	{
		global $db, $_base_path;
		
		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] <> 0)
		{
			$sql = 'SELECT p.privilege_id, p.title_var, p.link 
							FROM '.TABLE_PREFIX.'users u, '.TABLE_PREFIX.'user_groups ug, '.TABLE_PREFIX.'user_group_privilege ugp, '.TABLE_PREFIX.'privileges p
							WHERE u.user_id = '.$_SESSION['user_id'].'
							AND u.user_group_id = ug.user_group_id
							AND ug.user_group_id = ugp.user_group_id
							AND ugp.privilege_id = p.privilege_id
							ORDER BY p.menu_sequence';
		}
		else // public pages
		{
			$sql = 'SELECT privilege_id, title_var, link 
							FROM '.TABLE_PREFIX.'privileges p
							WHERE open_to_public = 1
							ORDER BY p.menu_sequence';
		}
	
		$result	= mysql_query($sql, $db) or die(mysql_error());
	
		while ($row = mysql_fetch_assoc($result))
		{
			$this->pages[AC_NAV_TOP][] = array('url' => $_base_path.$row['link'], 'title' => _AC($row['title_var']));
	
			// add section pages
			$this->pages = array_merge($this->pages, array($row['link'] => array('title_var'=>$row['title_var'], 'parent'=>AC_NAV_TOP)));
		}

		return true;
	}

	/**
	* Decide current page.
	* if the page that user tries to access is from one of the public link 
	* but not define in user's priviledge pages, re-direct to the first $this->pages[AC_NAV_TOP]
	* @access  private
	* @return  true
	* @author  Cindy Qi Li
	*/
	function setCurrentPage()
	{
		global $_base_path, $_base_href, $msg;
		
		$this->current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));
		
		if (!isset($this->pages[$this->current_page])) 
		{
			if (!$this->isPublicLink($this->current_page))  // report error if the link is not from a public link
			{
				$msg->addError(array('PAGE_NOT_FOUND', $_base_href.$this->current_page));
			}

			// re-direct to first $_pages URL 
			foreach ($this->pages[AC_NAV_TOP] as $page)
			{
				if ($_base_path.$this->current_page != $page['url'])
				{
					header('location: '.$page['url']);
					
					// reset current_page after re-direction
					$this->current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));
					
					// Note: must exit. otherwise, the rest of includeheader.inc.php proceeds and prints out all messages
					// which is not going to be displayed at re-directed page.
					exit;      
				}
			}
		}
	}

	/**
	* Check if the given link is a pre-defined public link
	* @access  private
	* @param   $page
	* @return  true  if is a pre-defined public link
	*          false if not a pre-defined public link
	* @author  Cindy Qi Li
	*/
	function isPublicLink($url)
	{
		foreach ($this->pages[AC_NAV_PUBLIC] as $page => $garbage)
		{
			if ($page == $url) return true;
		}

		return false;
	}
	
	/**
	* Return all pages array
	* @access  public
	* @return  all pages array
	* @author  Cindy Qi Li
	*/
	function getAllPages()
	{
		return $this->pages;
	}

	/**
	* Return top tab menu item array
	* @access  public
	* @return  top tab menu item array
	* @author  Cindy Qi Li
	*/
	function getTopPages()
	{
		return $this->pages[AC_NAV_TOP];
	}

	/**
	* Return top tab menu item array
	* @access  public
	* @return  top tab menu item array
	* @author  Cindy Qi Li
	*/
	function getCurrentPage()
	{
		return $this->current_page;
	}

	/**
	* Return root page relative to the current page
	* @access  public
	* @return  root page
	* @author  Cindy Qi Li
	*/
	function getRootPage()
	{
		global $_base_path;
	
		$parent_page = $this->pages[$this->current_page]['parent'];

		if (isset($parent_page) && defined($parent_page)) // check if $parent_page is 
		{
			return $_base_path . $this->current_page;
		} 
		else if (isset($parent_page)) 
		{
			return $this->getRootPage($parent_page);
		}
		else
		{
			return $this->current_page;
		}
	}

	/**
	* Return array of breadcrumb path
	* @access  public
	* @return  array of breadcrumb path
	* @author  Cindy Qi Li
	*/
	function getBreadcrumbPath()
	{
		global $_base_path;

		$parent_page = $this->pages[$this->current_page]['parent'];
	
		if (isset($this->pages[$this->current_page]['title']))
			$page_title = $this->pages[$this->current_page]['title'];
		else
			$page_title = _AC($this->pages[$this->current_page]['title_var']);
	
		if (isset($parent_page) && defined($parent_page)) 
		{
			$path[] = array('url' => $_base_path . $this->current_page, 'title' => $page_title);
		} 
		else if (isset($parent_page)) 
		{
			$path[] = array('url' => $_base_path . $this->current_page, 'title' => $page_title);
			$path = array_merge((array) $path, get_path($parent_page));
		} else {
			$path[] = array('url' => $_base_path . $this->current_page, 'title' => $page_title);
		}
		
		return $path;
	}

}
?>