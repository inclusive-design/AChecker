<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
 * DAO for "users" table
 * @access	public
 * @author	Cindy Qi Li
 * @package	DAO
 */

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');

class UsersDAO extends DAO {

	/**
	 * Validate if the given login/pwd is valid
	 * @access  public
	 * @param   login: login id or email
	 *          pwd: password
	 * @return  user id, if login/pwd is valid
	 *          false, if login/pwd is invalid
	 * @author  Cindy Qi Li
	 */
	public function Validate($login, $pwd)
	{
		global $addslashes;
		$login = $addslashes($login);
		$pwd = $addslashes($pwd);
		
		$sql = "SELECT user_id FROM ".TABLE_PREFIX."users 
		         WHERE (login='".$login."' OR email='".$login."') 
		           AND SHA1(CONCAT(password, '".$_SESSION[token]."'))='".$pwd."'";

		$rows = $this->execute($sql);
		if (is_array($rows))
		{
			return $rows[0]['user_id'];
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
	public function Create($user_group_id, $login, $pwd, $email, $first_name, $last_name, $status)
	{
		global $addslashes;

		/* email check */
		$user_group_id = intval($user_group_id);
		$login = $addslashes(strtolower(trim($login)));
		$email = $addslashes(trim($email));
		$first_name = $addslashes(str_replace('<', '', trim($first_name)));
		$last_name = $addslashes(str_replace('<', '', trim($last_name)));
		$status = intval($status);

		if ($this->isFieldsValid('new', $user_group_id,$login, $email,$first_name, $last_name))
		{
			if ($status == "")
			{
				if (defined('AC_EMAIL_CONFIRMATION') && AC_EMAIL_CONFIRMATION)
				{
					$status = AC_STATUS_UNCONFIRMED;
				} else
				{
					$status = AC_STATUS_ENABLED;
				}
			}

			/* insert into the db */
			$sql = "INSERT INTO ".TABLE_PREFIX."users
			              (login,
			               password,
			               user_group_id,
			               first_name,
			               last_name,
			               email,
			               web_service_id,
			               status,
			               create_date
			               )
			       VALUES ('".$login."',
			               '".$pwd."',
			               ".$user_group_id.",
			               '".$first_name."',
			               '".$last_name."', 
			               '".$email."',
			               '".Utility::getSessionID()."',
			               ".$status.", 
			               now()
										)";

			if (!$this->execute($sql))
			{
				$msg->addError('DB_NOT_UPDATED');
				return false;
			}
			else
			{
				return mysql_insert_id();
			}
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
	public function Update($userID, $user_group_id, $login, $email, $first_name, $last_name, $status)
	{
		global $addslashes, $msg;

		/* email check */
		$login = $addslashes(strtolower(trim($login)));
		$email = $addslashes(trim($email));
		$first_name = $addslashes(str_replace('<', '', trim($first_name)));
		$last_name = $addslashes(str_replace('<', '', trim($last_name)));

		if ($this->isFieldsValid('update', $user_group_id,$login, $email,$first_name, $last_name))
		{
			/* insert into the db */
			$sql = "UPDATE ".TABLE_PREFIX."users
			           SET login = '".$login."',
			               user_group_id = '".$user_group_id."',
			               first_name = '".$first_name."',
			               last_name = '".$last_name."',
			               email = '".$email."',
			               status = '".$status."'
			         WHERE user_id = ".$userID;

			return $this->execute($sql);
		}
	}

	/**
	 * Delete user
	 * @access  public
	 * @param   user_id
	 * @return  true, if successful
	 *          false and add error into global var $msg, if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function Delete($userID)
	{
		// delete customized guidelines created by user but yet open to public
		include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');
		$guidelinesDAO = new GuidelinesDAO();
		$guidelines = $guidelinesDAO->getGuidelineByUserID($userID);
		
		if (is_array($guidelines))
		{
			foreach($guidelines as $guideline)
			{
				if ($guideline['open_to_pulic'] == 0)
					$guidelinesDAO->Delete($guideline['guideline_id']);
			}
		}

		// delete customized checks created by user but yet open to public
		include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
		$checksDAO = new ChecksDAO();
		$checks = $checksDAO->getCheckByUserID($userID);
		
		if (is_array($checks))
		{
			foreach($checks as $check)
			{
				if ($check['open_to_pulic'] == 0)
					$checksDAO->Delete($check['check_id']);
			}
		}
		
		// delete user links and decisions generated by this user
		include_once(AC_INCLUDE_PATH.'classes/DAO/UserLinksDAO.class.php');
		$userLinksDAO = new UserLinksDAO();
		$userLinks = $userLinksDAO->DeleteByUserID($userID);
		
		$sql = "DELETE FROM ".TABLE_PREFIX."users
		         WHERE user_id = ".$userID;

		return $this->execute($sql);
	}

	/**
	 * Return all users' information
	 * @access  public
	 * @param   none
	 * @return  user rows
	 * @author  Cindy Qi Li
	 */
	public function getAll()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'users ORDER BY user_id';
		return $this->execute($sql);
	}

	/**
	 * Return user information by given user id
	 * @access  public
	 * @param   user id
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getUserByID($userID)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'users WHERE user_id='.$userID;
		if ($rows = $this->execute($sql))
		{
			return $rows[0];
		}
		else return false;
	}

	/**
	 * Return user information by given web service ID
	 * @access  public
	 * @param   web service ID
	 * @return  user row
	 * @author  Cindy Qi Li
	 */
	public function getUserByWebServiceID($webServiceID)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."users WHERE web_service_id='".$webServiceID."'";
		if ($rows = $this->execute($sql))
		{
			return $rows[0];
		}
		else return false;
	}

	/**
	 * Return user information by given email
	 * @access  public
	 * @param   email
	 * @return  user row : if successful
	 *          false : if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function getUserByEmail($email)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."users WHERE email='".$email."'";

		$rows = $this->execute($sql);
		if (is_array($rows))
		{
			return $rows[0];
		}
		else
		return false;
	}

	/**
	 * Return user information by given first, last name
	 * @access  public
	 * @param   $firstName : first name
	 *          $lastName : last name
	 * @return  user row : if successful
	 *          false   if unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function getUserByName($firstName, $lastName)
	{
		$sql = "SELECT user_id FROM ".TABLE_PREFIX."users
			        WHERE first_name='".$firstName."' 
			        AND last_name='".$lastName."'";

		$rows = $this->execute($sql);
		if (is_array($rows))
		{
			return $rows[0];
		}
		else
			return false;
	}

	/**
	 * Based on this->userID, return (first name, last name), if first name, last name not exists, return login name
	 * @access  public
	 * @param   $userID
	 * @return  first name, last name. if not exists, return login name
	 * @author  Cindy Qi Li
	 */
	public function getUserName($userID)
	{
		$row = $this->getUserByID($userID);
		
		if (!$row) return false;
		
		if ($row['first_name'] <> '' && $row['last_name'] <> '')
		{
			return $row['first_name']. ' '.$row['last_name'];
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
	 * Return given user's status
	 * @access  public
	 * @param   user id
	 * @return  user's status
	 * @author  Cindy Qi Li
	 */
	public function getStatus($userID)
	{
		$sql = "SELECT status FROM ".TABLE_PREFIX."users WHERE user_id='".$userID."'";
		$rows = $this->execute($sql);

		if ($rows)
		return $rows[0]['status'];
		else
		return false;
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
	public function setStatus($userID, $status)
	{
		$sql = "Update ".TABLE_PREFIX."users SET status='".$status."' WHERE user_id='".$userID."'";
		return $this->execute($sql);
	}

	/**
	 * Update user's last login time to now()
	 * @access  public
	 * @param   user id
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function setLastLogin($userID)
	{
		$sql = "Update ".TABLE_PREFIX."users SET last_login=now() WHERE user_id='".$userID."'";
		return $this->execute($sql);
	}

	/**
	 * Update user's first, last name
	 * @access  public
	 * @param   $userID : user ID
	 *          $firstName : first name
	 *          $lastName : last name
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function setName($userID, $firstName, $lastName)
	{
		$sql = "Update ".TABLE_PREFIX."users SET first_name='".$firstName."', last_name='".$lastName."' WHERE user_id='".$userID."'";
		return $this->execute($sql);
	}

	/**
	 * Update user's password
	 * @access  public
	 * @param   $userID : user ID
	 *          $password : password
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function setPassword($userID, $password)
	{
		$sql = "Update ".TABLE_PREFIX."users SET password='".$password."' WHERE user_id='".$userID."'";
		return $this->execute($sql);
	}

	/**
	 * Update user's email
	 * @access  public
	 * @param   $userID : user ID
	 *          $email : email
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	public function setEmail($userID, $email)
	{
		$sql = "Update ".TABLE_PREFIX."users SET email='".$email."' WHERE user_id='".$userID."'";
		return $this->execute($sql);
	}

	/**
	 * Validate fields preparing for insert and update
	 * @access  private
	 * @param   $validate_type : new/update. When validating for update, don't check if the login, email, name are unique  
	 *          $user_group_id : user ID
	 *          $login
	 *          $email
	 *          $first_name
	 *          $last_name
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	private function isFieldsValid($validate_type, $user_group_id, $login, $email, $first_name, $last_name)
	{
		global $msg;
		
		$missing_fields = array();
		/* login name check */
		if ($login == '')
		{
			$missing_fields[] = _AC('login_name');
		}
		else
		{
			/* check for special characters */
			if (!(preg_match("/^[a-zA-Z0-9_.-]([a-zA-Z0-9_.-])*$/i", $login)))
			{
				$msg->addError('LOGIN_CHARS');
			}
			else
			{
				if ($validate_type == 'new')
				{
					$sql = "SELECT * FROM ".TABLE_PREFIX."users WHERE login='".$login."'";
	
					if (is_array($this->execute($sql)))
					{
						$msg->addError('LOGIN_EXISTS');
					}
				}
			}
		}

		if ($user_group_id == '' || $user_group_id <= 0)
		{
			$missing_fields[] = _AC('user_group');
		}
		if ($email == '')
		{
			$missing_fields[] = _AC('email');
		}
		else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $email))
		{
			$msg->addError('EMAIL_INVALID');
		}

		if ($validate_type == 'new')
		{
			$sql = "SELECT * FROM ".TABLE_PREFIX."users WHERE email='".$email."'";
	
			if (is_array($this->execute($sql)))
			{
				$msg->addError('EMAIL_EXISTS');
			}
		}

		if (!$first_name) {
			$missing_fields[] = _AC('first_name');
		}

		if (!$last_name) {
			$missing_fields[] = _AC('last_name');
		}

		// check if first+last is unique
//		if (($first_name || $last_name) && $validate_type == 'new')
//		{
//			if ($this->getUserByName($first_name, $last_name))
//			{
//				$msg->addError('FIRST_LAST_NAME_UNIQUE');
//			}
//		}

		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}
		
		if (!$msg->containsErrors())
			return true;
		else
			return false;
	}
}
?>