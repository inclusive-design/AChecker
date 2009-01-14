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
* DAO for "themes" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class PrivilegesDAO extends DAO {

	/**
	* Return privileges that are open to public
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getPublicPrivileges()
	{
		$sql = 'SELECT *
						FROM '.TABLE_PREFIX.'privileges p
						WHERE open_to_public = 1
						ORDER BY p.menu_sequence';

    return $this->execute($sql);
  }

	/**
	* Return privileges of the given user
	* @access  public
	* @param   $userID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getUserPrivileges($userID)
	{
			$sql = 'SELECT *
							FROM '.TABLE_PREFIX.'users u, '.TABLE_PREFIX.'user_groups ug, '.TABLE_PREFIX.'user_group_privilege ugp, '.TABLE_PREFIX.'privileges p
							WHERE u.user_id = '.$userID.'
							AND u.user_group_id = ug.user_group_id
							AND ug.user_group_id = ugp.user_group_id
							AND ugp.privilege_id = p.privilege_id
							ORDER BY p.menu_sequence';

    return $this->execute($sql);
  }
}
?>