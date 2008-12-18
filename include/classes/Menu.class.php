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

define('AT_INCLUDE_PATH', '../../include/');

class Menu {

	// all private
	var $pages;                               // top tab pages
	var $current_page;                        // current page
	var $root_page;                           // root page relative to current page
	var $breadcrumb_path = array();           // array of breadcrumb path

	/**
	* Constructor: Initialization 
	* Generate top tab menu items based on session user_id. If no user login in (public view), use public menu
	* @access  public
	* @param   None
	* @author  Cindy Qi Li
	*/
	function Menu()
	{
		$this->pages[AC_NAV_TOP] = array();        // top tab pages
//		unset($_SESSION['user_id']);
		$_SESSION['user_id'] = 2;
		
		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] <> 0) 
		{
			$this->set_top_pages($_SESSION['user_id']);    // set top pages based on user id
		}
		else
		{
			$this->set_top_pages_to_public();
		}
		
		// decide current page.
		// if the page that user tries to access is from one of the public link 
		// but not define in user's priviledge pages, re-direct to the first $this->pages[AC_NAV_TOP]
		$this->set_current_page();
	}

	/**
	* Set top pages array based on user's priviledge
	* @access  private
	* @param   user id
	* @return  true
	* @author  Cindy Qi Li
	*/
	function set_top_pages($user_id)
	{
		global $db, $_section_pages, $_base_path;
		
		$sql = 'SELECT p.privilege_id, p.title_var, p.link 
						FROM '.TABLE_PREFIX.'users u, '.TABLE_PREFIX.'user_groups ug, '.TABLE_PREFIX.'user_group_privilege ugp, '.TABLE_PREFIX.'privileges p
						WHERE u.user_id = '.$user_id.'
						AND u.user_group_id = ug.user_group_id
						AND ug.user_group_id = ugp.user_group_id
						AND ugp.privilege_id = p.privilege_id
						ORDER BY p.menu_sequence';
	
		$result	= mysql_query($sql, $db) or die(mysql_error());
	
		while ($row = mysql_fetch_assoc($result))
		{
			$this->pages[AC_NAV_TOP][] = array('url' => $_base_path.$row['link'], 'title' => _AC($row['title_var']));
	
			// add section pages
			$this->pages = array_merge($this->pages, $this->set_parent($_section_pages[$row['privilege_id']], AC_NAV_TOP));
		}
		
		return true;
	}

	/**
	* Set top pages array to pre-defined public menu array
	* @access  private
	* @return  true
	* @author  Cindy Qi Li
	*/
	function set_top_pages_to_public()
	{
		global $_pages_constant, $_base_path;
		
		if (is_array($_pages_constant[AC_NAV_PUBLIC]))
		{
			foreach ($_pages_constant[AC_NAV_PUBLIC] as $url => $title_var)
				$this->pages[AC_NAV_TOP][] = array('url' => $_base_path.$url, 'title' => _AC($title_var));
			
			$this->pages = array_merge($this->pages, $this->set_parent($_pages_constant[AC_NAV_PUBLIC], AC_NAV_TOP));
		}
		
		return true;
	}

	/**
	* Set parent of the 1st item in section page array to
	* @access  private
	* @param   $page_array   page array
	*          $parent       parent to set
	* @return  true
	* @author  Cindy Qi Li
	*/
	function set_parent($page_array, $parent)
	{
		$cnt = 0;
		foreach ($page_array as &$page)
		{
			$page['parent'] = $parent;
			
			if (++$cnt == 1) break;
		}

		return $page_array;
	}

	/**
	* Decide current page.
	* if the page that user tries to access is from one of the public link 
	* but not define in user's priviledge pages, re-direct to the first $this->pages[AC_NAV_TOP]
	* @access  private
	* @return  true
	* @author  Cindy Qi Li
	*/
	function set_current_page()
	{
		global $_base_path, $msg;
		
		$this->current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));
		
		if (!isset($this->pages[$this->current_page])) 
		{
			if (!$this->is_public_link($this->current_page))  // report error if the link is not from a public link
			{
				$msg->addError('PAGE_NOT_FOUND'); 
			}
			
			// re-direct to first $_pages URL 
			$cnt = 0;
			
			foreach ($this->pages[AC_NAV_TOP] as $page)
			{
				if ($_base_path.$this->current_page != $page['url'])
				{
					header('location: '.$page['url']);
					
					// reset current_page after re-direction
					$this->current_page = substr($_SERVER['PHP_SELF'], strlen($_base_path));
				}
				
				if (++$cnt == 1) break;
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
	function is_public_link($url)
	{
		global $_pages_constant;
		
		foreach ($_pages_constant[AC_NAV_PUBLIC] as $page => $garbage)
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
	function get_all_pages()
	{
		return $this->pages;
	}

	/**
	* Return top tab menu item array
	* @access  public
	* @return  top tab menu item array
	* @author  Cindy Qi Li
	*/
	function get_top_pages()
	{
		return $this->pages[AC_NAV_TOP];
	}

	/**
	* Return top tab menu item array
	* @access  public
	* @return  top tab menu item array
	* @author  Cindy Qi Li
	*/
	function get_current_page()
	{
		return $this->current_page;
	}

	/**
	* Return root page relative to the current page
	* @access  public
	* @return  root page
	* @author  Cindy Qi Li
	*/
	function get_root_page()
	{
		global $_base_path;
	
		$parent_page = $this->pages[$this->current_page]['parent'];
	
		if (isset($parent_page) && defined($parent_page)) 
		{
			return $_base_path . $this->current_page;
		} 
		else if (isset($parent_page)) 
		{
			return $this->get_root_page($parent_page);
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
	function get_breadcrumb_path()
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