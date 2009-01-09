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
	function User()
	{
	  return true;
	}

	/**
	* Validate if the given login/pwd is valid
	* @access  public
	* @param   login: login id or email
	*          pwd: password
	* @return  user id, if login/pwd is valid
	*          false, if login/pwd is invalid
	* @author  Cindy Qi Li
	*/
	function Validate($login, $pwd)
	{
		global $db;
		
		$sql = "SELECT user_id FROM ".TABLE_PREFIX."users WHERE (login='".$login."' OR email='".$login."') AND SHA1(CONCAT(password, '".$_SESSION[token]."'))='".$pwd."'";
		$result = mysql_query($sql, $db);

		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_assoc($result);
			return $row['user_id'];
		}
		else
		{
			return false;
		}
	}

	/**
	* Create new user
	* @access  public
	* @param   user_group_id: user group ID (1 [admin] or 2 [user])
	*          login: login name
	*          pwd: password
	*          email: email
	*          first_name: first name
	*          last_name: last name
	* @return  user id, if successful
	*          false and add error into global var $msg, if unsuccessful
	* @author  Cindy Qi Li
	*/
	function Create($user_group_id, $login, $pwd, $email, $first_name, $last_name)
	{
		global $db, $addslashes, $msg;
		
		$missing_fields = array();
	
		/* email check */
		$login = $addslashes(strtolower(trim($login)));
		$email = $addslashes(trim($email));
		$first_name = $addslashes(str_replace('<', '', trim($first_name)));
		$last_name = $addslashes(str_replace('<', '', trim($last_name)));
	
		/* login name check */
		if ($login == '') 
		{
			$missing_fields[] = _AC('login_name');
		} 
		else 
		{
			/* check for special characters */
			if (!(eregi("^[a-zA-Z0-9_.-]([a-zA-Z0-9_.-])*$", $login))) 
			{
				$msg->addError('LOGIN_CHARS');
			} 
			else 
			{
				$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."users WHERE login='".$login."'",$db);
				if (mysql_num_rows($result) != 0) 
				{
					$msg->addError('LOGIN_EXISTS');
				}
			}
		}
	
		if ($email == '') 
		{
			$missing_fields[] = _AC('email');
		} 
		else if (!eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$", $email)) 
		{
			$msg->addError('EMAIL_INVALID');
		}
		$result = mysql_query("SELECT * FROM ".TABLE_PREFIX."users WHERE email='".$email."'",$db);
		
		if (mysql_num_rows($result) != 0) 
		{
			$msg->addError('EMAIL_EXISTS');
		}
	
		if (!$first_name) { 
			$missing_fields[] = _AC('first_name');
		}
	
		if (!$last_name) { 
			$missing_fields[] = _AC('last_name');
		}
	
		// check if first+last is unique
		if ($first_name && $last_name) 
		{
			$sql = "SELECT user_id FROM ".TABLE_PREFIX."users 
			        WHERE first_name='".$first_name."' 
			        AND last_name='".$last_name."' LIMIT 1";
			$result = mysql_query($sql, $db);
			if (mysql_fetch_assoc($result)) 
			{
				$msg->addError('FIRST_LAST_NAME_UNIQUE');
			}
		}
	
		if ($missing_fields) 
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}
	
		if (!$msg->containsErrors()) 
		{
			if (defined('AC_EMAIL_CONFIRMATION') && AC_EMAIL_CONFIRMATION) 
			{
				$status = AC_STATUS_UNCONFIRMED;
			} else 
			{
				$status = AC_STATUS_ENABLED;
			}
	
			/* insert into the db */
			$sql = "INSERT INTO ".TABLE_PREFIX."users 
			              (login,
			               password,
			               user_group_id,
			               first_name,
			               last_name,
			               email,
			               status,
			               create_date
			               )
			       VALUES ('".$login."',
			               '".$pwd."',
			               ".$user_group_id.",
			               '".$first_name."',
			               '".$last_name."', 
			               '".$email."',
			               ".$status.", 
			               now()
										)";

			$result = mysql_query($sql, $db) or die(mysql_error());
			$user_id	= mysql_insert_id($db);
			if (!$result) 
			{
				$msg->addError('DB_NOT_UPDATED');
				return false;
			}
			else
			{
				return $user_id;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	* Return given user's status
	* @access  public
	* @param   user id
	* @return  user's status
	* @author  Cindy Qi Li
	*/
	function getStatus($user_id)
	{
		global $db;
		
		$sql = "SELECT status FROM ".TABLE_PREFIX."users WHERE user_id='".$user_id."'";
		$result = mysql_query($sql, $db) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		return $row['status'];
	}

	/**
	* Set user's status
	* @access  public
	* @param   user id
	*          status
	* @return  true    if status is set successfully
	*          false   if unsuccessful
	* @author  Cindy Qi Li
	*/
	function setStatus($user_id, $status)
	{
		global $db;
		
		$sql = "Update ".TABLE_PREFIX."users SET status='".$status."' WHERE user_id='".$user_id."'";
		$result = mysql_query($sql, $db) or die(mysql_error());

		if (mysql_affected_rows() > 0) 
			return true;
		else 
			return false;
	}

	/**
	* Update user's last login time to now()
	* @access  public
	* @param   user id
	* @return  true    if update successfully
	*          false   if update unsuccessful
	* @author  Cindy Qi Li
	*/
	function updateLastLoginTime($user_id)
	{
		global $db;
		
		$sql = "Update ".TABLE_PREFIX."users SET last_login=now() WHERE user_id='".$user_id."'";
		$result = mysql_query($sql, $db) or die(mysql_error());

		if (mysql_affected_rows() > 0) 
			return true;
		else 
			return false;
	}

	/**
	* Set user id
	* @access  public
	* @param   user id
	* @return  true
	* @author  Cindy Qi Li
	*/
	function setUserID($user_id)
	{
		$this->userID = $user_id;
		
		return true;
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
		global $db;
		
    if (!$this->isSetUserID()) return '';
    
    $sql = 'SELECT * FROM '.TABLE_PREFIX.'users WHERE user_id='.$this->userID;
    $result = mysql_query($sql, $db) or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    
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

	/**
	* check if the user id has been set
	* @access  private
	* @param   none
	* @return  true     if user id has been set
	*          false    if user id has not been set
	* @author  Cindy Qi Li
	*/
	function isSetUserID()
	{
		if ($this->userID == '' || intval($this->userID) == 0) return false;
		else return true;
	}
	
}
?>