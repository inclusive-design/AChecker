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

require(dirname(__FILE__) . '/class.phpmailer.php');

/**
* ACheckerMailer is modified from ATutorMailer
*
* ACheckerMailer extends PHPMailer and sets all the default values
* that are common for AChecker.
* @access  public
* @see     include/classes/phpmailer/class.phpmailer.php
* @since   AChecker 0.2
* @author  Cindy Li
*/
class ACheckerMailer extends PHPMailer {

	/**
	* The constructor sets whether to use SMTP or Sendmail depending
	* on the value of MAIL_USE_SMTP defined in the config.inc.php file.
	* @access  public
	* @since   AChecker 0.2
	* @author  Joel Kronenberg
	*/
	function ACheckerMailer() {
		if (MAIL_USE_SMTP) {
			$this->IsSMTP(); // set mailer to use SMTP
			$this->Host = ini_get('SMTP');  // specify main and backup server
		} else {
			$this->IsSendmail(); // use sendmail
			$this->Sendmail = ini_get('sendmail_path');
		}

		$this->SMTPAuth = false;  // turn on SMTP authentication
		$this->IsHTML(false);

		// send the email in the current encoding:
		global $myLang;
		$this->CharSet = $myLang->getCharacterSet();
	}

	/**
	* Appends a custom AChecker footer to all outgoing email then sends the email.
	* If mail_queue is enabled then instead of sending the mail out right away, it 
	* places it in the database and waits for the cron to send it using SendQueue().
	* The mail queue does not support reply-to, or attachments, and converts all BCCs
	* to regular To emails.
	* @access  public
	* @return  boolean	whether or not the mail was sent (or queued) successfully.
	* @see     parent::send()
	* @since   AChecker 0.2
	* @author  Joel Kronenberg
	*/
	function Send() {
		global $_config;

		// attach the AChecker footer to the body first:
		$this->Body .= 	"\n\n".'----------------------------------------------'."\n";
		$this->Body .= _AC('sent_via_achecker', AC_BASE_HREF);

		$this->Body .= "\n"._AC('achecker_home').': http://achecker.ca';

		// if this email has been queued then don't send it. instead insert it in the db
		// for each bcc or to or cc
		if ($_config['enable_mail_queue'] && !$this->attachment) 
		{
			require_once(AC_INCLUDE_PATH.'classes/DAO/MailQueueDAO.class.php');
			$mailQueueDAO = new MailQueueDAO();
			
			for ($i = 0; $i < count($this->to); $i++) {
				$mailQueueDAO->Create(addslashes($this->to[$i][0]), addslashes($this->to[$i][1]), addslashes($this->From), addslashes($this->FromName), addslashes($this->Subject), addslashes($this->Body), addslashes($this->CharSet));
			}
			for($i = 0; $i < count($this->cc); $i++) {
				$mailQueueDAO->Create(addslashes($this->cc[$i][0]), addslashes($this->cc[$i][1]), addslashes($this->From), addslashes($this->FromName), addslashes($this->Subject), addslashes($this->Body), addslashes($this->CharSet));
			}
			for($i = 0; $i < count($this->bcc); $i++) {
				$mailQueueDAO->Create(addslashes($this->bcc[$i][0]), addslashes($this->bcc[$i][1]), addslashes($this->From), addslashes($this->FromName), addslashes($this->Subject), addslashes($this->Body), addslashes($this->CharSet));
			}
			return true;
		} else {
			return parent::Send();
		}
	}

	/**
	* Sends all the queued mail. Called by ./admin/cron.php.
	* @access public
	* @return void
	* @since AChecker 0.2
	* @author Joel Kronenberg
	*/
	function SendQueue() {
		global $db;

		require_once(AC_INCLUDE_PATH.'classes/DAO/MailQueueDAO.class.php');
		$mailQueueDAO = new MailQueueDAO();
		$rows = $mailQueueDAO->getAll();

		$mail_ids = array();
		
		if (is_array($rows))
		{
			foreach ($rows as $id => $row) 
			{
				$this->ClearAllRecipients();
	
				$this->AddAddress($row['to_email'], $row['to_name']);
				$this->From     = $row['from_email'];
				$this->FromName = $row['from_name'];
				$this->CharSet  = $row['char_set'];
				$this->Subject  = $row['subject'];
				$this->Body     = $row['body'];
	
				parent::Send();
	
				$mail_ids[] = $row['mail_id'];
			}
			if ($mail_ids) 
			{
				include(AC_INCLUDE_PATH.'classes/DAO/MailQueueDAO.class.php');
				$mailQueueDAO = new MailQueueDAO();
	
				$mailQueueDAO->DeleteByIDs($mail_ids);
			}
		}
	}

}

?>
