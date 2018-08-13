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

/**
 * Menu
 * 1. Generate main menu items based on login user
 * 2. Generate path in bread crumb
 * 3. Decide the page to display (redirect) based on login user's privilege.
 *    This page is set as current page
 * 4. Generate sub menus of current page
 * 5. Generate back to page of current page
 * @access	public
 * @author	Cindy Qi Li
 * @package	Menu
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/PrivilegesDAO.class.php');

class Menu {

	// all private
	var $pages;                               // top tab pages
	var $current_page;                        // current page
	var $root_page;                           // root page relative to current page
	var $breadcrumb_path = array();           // array of breadcrumb path
	var $sub_menus;                           // array of sub-menus of current page
	var $path;                                // array of all parent pages to current page, used for breadcrumb path and generating back to page
	var $back_to_page;                        // string of parent page to go back to

	/**
	 * Constructor: Initialize top pages (tab menu), all pages accessible by current user, current page.
	 * Generate top tab menu items based on session user_id. If no user login in (public view), use public menu
	 * @access  public
	 * @param   None
	 * @author  Cindy Qi Li
	 */
	function __construct()
	{
		$this->pages[AC_NAV_TOP] = array();        // top tab pages

		$this->init();           // Initialize $this->pages[AC_NAV_PUBLIC] & $this->pages
		$this->setTopPages();    // set top pages based on user id

		// decide current page.
		// if the page that user tries to access is from one of the public link
		// but not define in user's priviledge pages, re-direct to the first $this->pages[AC_NAV_TOP]
		$this->setCurrentPage();
		$this->sub_menus = $this->setSubMenus($this->current_page);   // loop recursively to set $this->submenus to the top parent of $this->current_page
		$this->root_page = $this->setRootPage($this->current_page);
		$this->path = $this->setPath($this->current_page);
		$this->back_to_page = $this->setBackToPage();
	}

	/**
	 * initialize: public accessible items ($this->pages[AC_NAV_PUBLIC]); all accessible pages ($this->pages)
	 * @access  private
	 * @param   user id
	 * @return  true
	 * @author  Cindy Qi Li
	 */
	private function init()
	{
		// $_pages is defined in include/constants.inc.php
		global $_pages, $_base_path;

		// initialize $this->pages
		$this->pages = $_pages;
		// end of initializing $this->pages

		$priviledgesDAO = new PrivilegesDAO();
		$rows = $priviledgesDAO->getPublicPrivileges();

		if (is_array($rows))
		{
			foreach ($rows as $id => $row)
			{
				$this->pages[AC_NAV_PUBLIC][] = array($row['link'] => array('title_var'=>$row['title_var'], 'parent'=>AC_NAV_TOP));
			}
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
	private function setTopPages()
	{
		global $_base_path;

		$priviledgesDAO = new PrivilegesDAO();

		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] <> 0)
		{
			$rows = $priviledgesDAO->getUserPrivileges($_SESSION['user_id']);
		}
		else // public pages
		{
			$rows = $priviledgesDAO->getPublicPrivileges();
		}

		if (is_array($rows))
		{
			foreach ($rows as $id => $row)
			{
				$this->pages[AC_NAV_TOP][] = array('url' => $_base_path.$row['link'], 'title' => _AC($row['title_var']));

				// add section pages if it has not been defined in $this->pages
				if (!isset($this->pages[$row['link']]))
				{
					$this->pages = array_merge($this->pages,
				                           array($row['link'] => array('title_var'=>$row['title_var'], 'parent'=>AC_NAV_TOP)));
				}
			}
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
	private function setCurrentPage()
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
	* Set sub-menus of current page by $_pages[$current_page]['children']
	* @access  private
	* @return  true
	* @author  Cindy Qi Li
	*/
	private function setSubMenus($page) {
		global $_base_path;

		if (isset($page) && defined($page))
		{
			// reached the top
			return array();
		}
		else if (isset($this->pages[$page]['children']))
		{
			$sub_menus[] = array('url' => $_base_path . $page, 'title' => $this->getPageTitle($page));

			foreach ($this->pages[$page]['children'] as $child)
			{
				$sub_menus[] = array('url' => $_base_path . $child,
				                    'title' => $this->getPageTitle($child),
				                    'has_children' => isset($this->pages[$child]['children']));
			}
		}
		else if (isset($this->pages[$page]['parent']))
		{
			// no children
			return $this->setSubMenus($this->pages[$page]['parent']);
		}

		return $sub_menus;
	}

	/**
	* Set the back to page of $this->current_page
	* @access  private
	* @return  true
	* @author  Cindy Qi Li
	*/
	private function setBackToPage()
	{
		$back_to_page = "";
		unset($this->path[0]);
		if (isset($this->path[2]['url'], $this->sub_menus[0]['url']) && $this->path[2]['url'] == $this->sub_menus[0]['url']) {
			$back_to_page = $this->path[3];
		} else if (isset($this->path[1]['url'], $this->sub_menus[0]['url']) && $this->path[1]['url'] == $this->sub_menus[0]['url']) {
			$back_to_page = isset($this->path[2]) ? $this->path[2] : null;
		} else if (isset($this->path[1])) {
			$back_to_page = $this->path[1];
		}

		return $back_to_page;
	}

	/**
	 * Check if the given link is a pre-defined public link
	 * @access  private
	 * @param   $page
	 * @return  true  if is a pre-defined public link
	 *          false if not a pre-defined public link
	 * @author  Cindy Qi Li
	 */
	private function isPublicLink($url)
	{
		foreach ($this->pages[AC_NAV_PUBLIC] as $page => $garbage)
		{
			if ($page == $url) return true;
		}

		return false;
	}

	/**
	 * Return the page title of given page
	 * @access  private
	 * @param   $page
	 * @return  page title
	 *          empty if page is not defined
	 * @author  Cindy Qi Li
	 */
	private function getPageTitle($page)
	{
		if (isset($this->pages[$page]['title']))
		{
			$page_title = $this->pages[$page]['title'];
		}
		else
		{
			$page_title = _AC($this->pages[$page]['title_var']);
		}

		return $page_title;
	}

	/**
	 * Return all pages array
	 * @access  public
	 * @return  all pages array
	 * @author  Cindy Qi Li
	 */
	public function getAllPages()
	{
		return $this->pages;
	}

	/**
	 * Return top tab menu item array
	 * @access  public
	 * @return  top tab menu item array
	 * @author  Cindy Qi Li
	 */
	public function getTopPages()
	{
		return $this->pages[AC_NAV_TOP];
	}

	/**
	 * Return top tab menu item array
	 * @access  public
	 * @return  top tab menu item array
	 * @author  Cindy Qi Li
	 */
	public function getCurrentPage()
	{
		return $this->current_page;
	}

	/**
	 * Return sub menus of current page
	 * @access  public
	 * @return  top tab menu item array
	 * @author  Cindy Qi Li
	 */
	public function getSubMenus()
	{
		return $this->sub_menus;
	}

	/**
	 * Return back to page of current page
	 * @access  public
	 * @return  back to page array
	 * @author  Cindy Qi Li
	 */
	public function getBackToPage()
	{
		return $this->back_to_page;
	}

	/**
	 * Set root page relative to the current page
	 * @access  public
	 * @return  root page
	 * @author  Cindy Qi Li
	 */
	private function setRootPage($page)
	{
		global $_base_path;

		$parent_page = $this->pages[$page]['parent'];

		if (isset($parent_page) && defined($parent_page)) // check if $parent_page is
		{
			return $_base_path . $page;
		}
		else if (isset($parent_page))
		{
			return $this->getRootPage();
		}
		else
		{
			return $_base_path . $page;
		}
	}

	/**
	 * Return root page relative to the current page
	 * @access  public
	 * @return  root page
	 * @author  Cindy Qi Li
	 */
	public function getRootPage()
	{
		return $this->root_page;
	}

	/**
	 * Return array of all parent items path to current page
	 * this array is used to determine back to page
	 * @access  private
	 * @return  array of breadcrumb path
	 * @author  Cindy Qi Li
	 */
	public function setPath($page)
	{
		global $_base_path;

		$parent_page = $this->pages[$page]['parent'];

		$page_title = $this->getPageTitle($page);

		if (isset($parent_page) && defined($parent_page))
		{
			$path[] = array('url' => $_base_path . $page, 'title' => $page_title);
		}
		else if (isset($parent_page))
		{
			$path[] = array('url' => $_base_path . $page, 'title' => $page_title);
			$path = array_merge((array) $path, $this->setPath($parent_page));
		} else {
			$path[] = array('url' => $_base_path . $page, 'title' => $page_title);
		}

		return $path;
	}

	/**
	 * Return breadcrumb path
	 * @access  public
	 * @return  root page
	 * @author  Cindy Qi Li
	 */
	public function getPath()
	{
		return $this->path;
	}

}
?>
