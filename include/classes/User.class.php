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
* User
* 1. Create new user, 
* 2. Existing user: return user name
* @access	public
* @author	Cindy Qi Li
* @package	User
*/

define('AC_INCLUDE_PATH', '../../include/');

class User {

	// all private
	var $userID;                               // set by setUserID

	/**
	* Constructor
	* doing nothing
	* @access  public
	* @param   None
	* @author  Cindy Qi Li
	*/
	function User($user_id)
	{
		$this->userID = $user_id;
	}

	/**
	* Based on this->userID, return (first name, last name), if first name, last name not exists, return login name
	* @access  public
	* @param   none
	* @return  first name, last name. if not exists, return login name
	* @author  Cindy Qi Li
	*/
	function getUserName()
	{
    require_once(AC_INCLUDE_PATH. 'classes/DAO/UsersDAO.class.php');
    $usersDAO = new UsersDAO();
    
    $row = $usersDAO->getUserByID($this->userID);
    
    if ($row['first_name'] <> '' && $row['last_name'] <> '')
    {
		  return $row['first_name']. ', '.$row['last_name'];
		}
		else if ($row['first_name'] <> '')
		{
		  return $row['first_name'];
		}
		else if ($row['last_name'] <> '')
		{
		  return $row['last_name'];
		}
		else
		{
		  return $row['login'];
		}
	}

}
?>