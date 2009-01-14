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
* DAO for "mail_queue" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class MailQueueDAO extends DAO {

	/**
	* Create a record
	* @access  public
	* @param   infos
	* @return  mail_queue_id: if success
	*          false: if unsuccess
	* @author  Cindy Qi Li
	*/
	function Create($to_email, $to_name, $from_email, $from_name, $subject, $body, $charset)
	{
		$sql = "INSERT INTO ".TABLE_PREFIX."mail_queue 
						VALUES (NULL, '$to_email', '$to_name', '$from_email', '$from_name', '$charset', '$subject', '$body')";
		
		if ($this->execute($sql))
		{
			return mysql_insert_id($this->db);
		}
		else
		{
			return false;			
		}
	}

	/**
	* Create a record
	* @access  public
	* @param   $mids : mail IDs, for example: "1, 2, 3"
	* @return  true: if successful
	*          false: if unsuccessful
	* @author  Cindy Qi Li
	*/
	function DeleteByIDs($mids)
	{
		$sql = "DELETE FROM ".TABLE_PREFIX."mail_queue WHERE mail_id IN (".$mids.")";
		
		return $this->execute($sql);
	}

}
?>