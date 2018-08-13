<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
* Language
* Class for accessing information about a single language.
* @access	public
* @author	Joel Kronenberg
* @see		LanguageManager::getLanguage()
* @see		LanguageManager::getMyLanguage()
* @package	Language
*/
include_once(AC_INCLUDE_PATH.'classes/DAO/LangCodesDAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');
class Language extends DAO {
	// all private
	var $code;
	var $characterSet;
	var $direction;
	var $regularExpression;
	var $nativeName;
	var $englishName;
	var $status;
	var $achecker_version;


	// constructor

	function __construct($language_row) {

		if (is_array($language_row)) {
			$this->code              = $language_row['language_code'];
			$this->characterSet      = $language_row['charset'];
			$this->regularExpression = $language_row['reg_exp'];
			$this->nativeName        = $language_row['native_name'];
			$this->englishName       = $language_row['english_name'];
			$this->status            = $language_row['status'];
			$this->achecker_version    = isset($language_row['version']) ? $language_row['version'] : VERSION;

			$langCodesDAO = new LangCodesDAO();
			$row_langCodes = $langCodesDAO->GetLangCodeBy3LetterCode($this->getParentCode($language_row['language_code']));

			$this->direction = $row_langCodes['direction'];
			
		} else if (is_object($language_row)) {
			$this->cloneThis($language_row);
		}
	}

	// private
	// copies the properties from $from to $this Object
	function cloneThis($from) {
		$vars = get_object_vars($from);
		foreach ($vars as $key => $value) {
			$this->$key = $value;
		}
	}

	// returns whether or not the $search_string matches the regular expression
	function isMatchHttpAcceptLanguage($search_string) {
		return preg_match('/^(' . $this->regularExpression . ')(;q=[0-9]\\.[0-9])?$/', $search_string);
	}

	// returns boolean whether or not $search_string is in HTTP_USER_AGENT
	function isMatchHttpUserAgent($search_string) {
		return preg_match('/(\(|\[|;[\s])(' . $this->regularExpression . ')(;|\]|\))/', $search_string);

	}

	function getCode() {
		return $this->code;
	}

	function getCharacterSet() {
		return $this->characterSet;
	}

	function getDirection() {
		return $this->direction;
	}

	function getRegularExpression() {
		return $this->regularExpression;
	}

	function getACheckerVersion() {
		return $this->achecker_version;
	}

	function getTranslatedName() {
		if ($this->code == $_SESSION['lang']) {
			return $this->nativeName;
		}
		// this code has to be translated:
		return _AT('lang_' . str_replace('-', '_', $this->code));
	}
 
	function getNativeName() {
		return $this->nativeName;
	}

	function getEnglishName() {
		return $this->englishName;
	}

	function getStatus() {
		return $this->status;
	}


	// public
	function sendContentTypeHeader() {
		header('Content-Type: text/html; charset=' . $this->characterSet);
	}

	// public
	function saveToSession() {
		$_SESSION['lang'] = $this->code;
	}

	/* 
	 * public
	 * @param	member_id or login for members and admin respectively
	 * @param	1 for admin, 0 for members, all other integers are ignored. 
	 */
	function saveToPreferences($id, $is_admin) {
		if ($id) {
			if ($is_admin === 0) {
				$sql = "UPDATE ".TABLE_PREFIX."members SET language='".$this->code."', creation_date=creation_date, last_login=last_login WHERE member_id=$id";
			} elseif ($is_admin === 1) {
				$sql = "UPDATE ".TABLE_PREFIX."admins SET language='".$this->code."', last_login=last_login WHERE login='$id'";
			}
			mysqli_query($this->db, $sql);
		}
	}

	// public
	// returns whether or not this language is right-to-left
	// possible langues are: arabic, farsi, hebrew, urdo
	function isRTL() {
		if ($this->direction == 'rtl') {
			return true;
		}

		return false;
	}

	function getParentCode($code = '') {
		if (!$code && isset($this)) {
			$code = $this->code;
		}
		$peices = explode('AT_LANGUAGE_LOCALE_SEP', $code, 2);
		return $peices[0];
	}

	// public
	// can be called staticly
	function getLocale($code = '') {
		if (!$code && isset($this)) {
			$code = $this->code;
		}
		$peices = explode('AT_LANGUAGE_LOCALE_SEP', $code, 2);
		return $peices[1];
	}
	
	function getXML($part=FALSE) {
		if (!$part) {
			$xml = '<?xml version="1.0" encoding="iso-8859-1"?>
			<!-- This is an AChecker language pack -->

			<!DOCTYPE language [
			   <!ELEMENT achecker-version (#PCDATA)>
			   <!ELEMENT charset (#PCDATA)>
			   <!ELEMENT reg-exp (#PCDATA)>
			   <!ELEMENT native-name (#PCDATA)>
			   <!ELEMENT english-name (#PCDATA)>
			   <!ELEMENT status (#PCDATA)>

			   <!ATTLIST language code ID #REQUIRED>
			]>';
		} 

		$xml .= '<language code="'.$this->code.'">
			<achecker-version>'.VERSION.'</achecker-version>
			<charset>'.$this->characterSet.'</charset>
			<reg-exp>'.$this->regularExpression.'</reg-exp>
			<native-name>'.$this->nativeName.'</native-name>
			<english-name>'.$this->englishName.'</english-name>
			<status>'.$this->status.'</status>
		</language>';

		return $xml;
	}
}
?>
