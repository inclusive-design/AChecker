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

require_once(dirname(__FILE__) . '/Language.class.php');



/**
* LanguageManager
* Class for managing available languages as Language Objects.
* @access	public
* @author	Joel Kronenberg
* @see		Language.class.php
* @package	Language
*/
class LanguageManager {

	/**
	* This array stores references to all the Language Objects
	* that are available in this installation.
	* @access private
	* @var array
	*/
	var $allLanguages;
	
	/**
	* This array stores references to the Language Objects
	* that are available in this installation.
	* @access private
	* @var array
	*/
	var $availableLanguages;

	/**
	* The number of languages that are available. Does not include
	* character set variations.
	* @access private
	* @var integer
	*/
	var $numEnabledLanguages;

	/**
	* Constructor.
	* 
	* Initializes availableLanguages and numLanguages.
	*/
	function LanguageManager() {
		require_once(AC_INCLUDE_PATH. 'classes/DAO/LanguagesDAO.class.php');
		$languagesDAO = new LanguagesDAO();
		
		// initialize available lanuguages. Available languages are the ones with status "enabled"
		$rows = $languagesDAO->getAllEnabled();
		
		// if there's no enabled language, set to default language and default charset
		if (!is_array($rows))
		{
			$rows = array($languagesDAO->getByLangCodeAndCharset(DEFAULT_LANGUAGE_CODE, DEFAULT_CHARSET));
		}
		foreach ($rows as $i => $row) {
			$this->availableLanguages[$row['language_code']][$row['charset']] = new Language($row);
		}
		$this->numEnabledLanguages = count($this->availableLanguages);

			// initialize available lanuguages. Available languages are the ones with status "enabled"
		$rows = $languagesDAO->getAll();
		
		foreach ($rows as $i => $row) {
			$this->allLanguages[$row['language_code']][$row['charset']] = new Language($row);
		}
	}


	/**
	* Returns a valid Language Object based on the given language $code and optional
	* $charset, FALSE if it can't be found.
	* @access	public
	* @param	string $code		The language code of the language to return.
	* @param	string $charset		Optionally, the character set of the language to find.
	* @return	boolean|Language	Returns FALSE if the requested language code and
	*								character set cannot be found. Returns a Language Object for the
	*								specified language code and character set.
	* @see		getMyLanguage()
	*/
	function getLanguage($code, $charset = '') {
		if (!$charset) {
			if (isset($this->allLanguages[$code])) {
				return current($this->allLanguages[$code]);
			} else {
				return FALSE;
			}
		}

		foreach ($this->allLanguages[$code] as $language) {
			if ($language->getCharacterSet() == $charset) {
				return $language;
			}
		}
		return FALSE;
	}

	/**
	* Tries to detect the user's current language preference/setting from (in order):
	* _GET, _POST, _SESSION, HTTP_ACCEPT_LANGUAGE, HTTP_USER_AGENT. If no match can be made
	* then it tries to detect a default setting (defined in config.inc.php) or a fallback
	* setting, false if all else fails.
	* @access	public
	* @return	boolean|Language	Returns a Language Object matching the user's current session.
	*								Returns FALSE if a valid Language Object cannot be found
	*								to match the request
	* @see		getLanguage()
	*/
	function getMyLanguage() {

		if (isset($_GET) && !empty($_GET['lang']) && isset($this->availableLanguages[$_GET['lang']])) {
			$language = $this->getLanguage($_GET['lang']);

			if ($language) {
				return $language;
			}

		} 

		if (isset($_POST) && !empty($_POST['lang']) && isset($this->availableLanguages[$_POST['lang']])) {
			$language = $this->getLanguage($_POST['lang']);

			if ($language) {
				return $language;
			}

		} 
		if (isset($_SESSION) && isset($_SESSION['lang']) && !empty($_SESSION['lang']) && isset($this->availableLanguages[$_SESSION['lang']])) {
			$language = $this->getLanguage($_SESSION['lang']);

			if ($language) {
				return $language;
			}
		}

		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

			// Language is not defined yet :
			// try to find out user's language by checking its HTTP_ACCEPT_LANGUAGE
			$accepted    = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$acceptedCnt = count($accepted);
			reset($accepted);
			for ($i = 0; $i < $acceptedCnt; $i++) {
				foreach ($this->availableLanguages as $codes) {
					foreach ($codes as $language) {
						if ($language->isMatchHttpAcceptLanguage($accepted[$i])) {
							return $language;
						}
					}
				}
			}
		}
		
		if (!empty($_SERVER['HTTP_USER_AGENT'])) {

			// Language is not defined yet :
			// try to find out user's language by checking its HTTP_USER_AGENT
			foreach ($this->availableLanguages as $codes) {
				foreach ($codes as $language) {
					if ($language->isMatchHttpUserAgent($_SERVER['HTTP_USER_AGENT'])) {
						return $language;
					}
				}
			}
		}

		// Didn't catch any valid lang : we use the default settings
		if (isset($this->availableLanguages[DEFAULT_LANGUAGE_CODE])) {
			$language = $this->getLanguage(DEFAULT_LANGUAGE_CODE, DEFAULT_CHARSET);

			if ($language) {
				return $language;
			}
		}
		
		// else pick one at random:
		reset($this->availableLanguages);
		$uknown_language = current($this->availableLanguages);
		if ($unknown_language) {
			return FALSE;
		}
		
		return current($uknown_language);
	}

	function getAvailableLanguages() {
		return $this->availableLanguages;
	}

	// public
	function printDropdown($current_language, $name, $id) {
		echo '<select name="'.$name.'" id="'.$id.'">';

		foreach ($this->availableLanguages as $codes) {
			$language = current($codes);
			if ($language->getStatus() == AC_STATUS_ENABLED) {
				echo '<option value="'.$language->getCode().'"';
				if ($language->getCode() == $current_language) {
					echo ' selected="selected"';
				}
				echo '>'.htmlspecialchars($language->getNativeName()).'</option>';
			}
		}
		echo '</select>';
	}

	// public
	function printList($current_language, $name, $id, $url) {

		$delim = false;
		foreach ($this->availableLanguages as $codes) {
			$language = current($codes);

			if ($language->getStatus() == AC_STATUS_ENABLED) {

				if ($delim){
					echo ' | ';
				}

				if ($language->getCode() == $current_language) {
					echo '<strong>'.$language->getNativeName().'</strong>';
				} else {
					echo '<a href="'.$url.'lang='.$language->getCode().'">'.$language->getNativeName().'</a> ';
				}

				$delim = true;
			}
		}
	}

	// public
	function getNumEnabledLanguages() {
		return $this->numEnabledLanguages;
	}

	// public
	// checks whether or not the language exists
	function exists($code) {
		return isset($this->allLanguages[$code]);
	}

	// public
	// import language pack from specified file
	// return imported AChecker version if it does not match with the current version
	function import($filename, $ignore_version = false) {
		global $msg;
		$import_path = AC_TEMP_DIR . 'import/';

		$zip = new ZipArchive();
	
		if ($zip->open($filename) === TRUE) {
			$zip->extractTo($import_path);
			$zip->close();			
		} else {
			$msg->addError('CANNOT_UNZIP');
			return false;
		}
		
		// import
		$rtn = $this->import_from_path($import_path, $ignore_version);
		
		// the achecker version from the imported language pack does not match with the current version
		// the array of ("imported version", "import path") is returned
		if (is_array($rtn)) return $rtn;
		
		// remove uploaded zip file
		@unlink($filename);
		
		return true;
	}

	// public
	// import the languages from the files that are in the given folder 
	public function import_from_path($import_path, $ignore_version=false) {
		require_once(AC_INCLUDE_PATH . 'classes/Language/LanguageParser.class.php');

		global $languageManager, $msg;

		$language_xml = @file_get_contents($import_path.'language.xml');

		$languageParser = new LanguageParser();
		$languageParser->parse($language_xml);
		$languageEditor = $languageParser->getLanguageEditor(0);

		$import_version = $languageEditor->getACheckerVersion();
		if ($import_version != VERSION && !$ignore_version) {
			return array('version'=>$import_version, "import_path"=>$import_path);
		}

		if ($languageManager->exists($languageEditor->getCode())) {
			$msg->addError('LANG_EXISTS');
		}

		if (!$msg->containsErrors()) {
			$languageEditor->import($import_path . 'language_text.sql');
			$msg->addFeedback('IMPORT_LANG_SUCCESS');
		}
		$this->cleanup_language_files($import_path);
		return true;
	}
	
	// public
	// remove language files in the given folder
	public function cleanup_language_files($import_path) {
		// remove the files:
		@unlink($import_path . 'language.xml');
		@unlink($import_path . 'language_text.sql');
		@unlink($import_path . 'readme.txt');
	}
	
	// public
	// imports LIVE language from the achecker language database
	function liveImport($language_code) {

		require_once(AC_INCLUDE_PATH. 'classes/DAO/LanguagesDAO.class.php');
		$languagesDAO = new LanguagesDAO();
		$tmp_lang_db = mysqli_connect(AC_LANG_DB_HOST, AC_LANG_DB_USER, AC_LANG_DB_PASS, AC_LANG_DB_NAME);
		// set database connection using utf8
		mysqli_query($tmp_lang_db, "SET NAMES 'utf8'");
		
		if (!$tmp_lang_db) {
			/* AC_ERROR_NO_DB_CONNECT */
			echo 'Unable to connect to db.';
			exit;
		}
		if (!mysqli_select_db($tmp_lang_db, 'dev_achecker_langs')) {
			echo 'DB connection established, but database "dev_achecker_langs" cannot be selected.';
			exit;
		}

		$sql = "SELECT * FROM languages_SVN WHERE language_code='$language_code'";
		$result = mysqli_query($tmp_lang_db, $sql);

		if ($row = mysqli_fetch_assoc($result)) {
			$row['reg_exp'] = $languagesDAO->addSlashes($row['reg_exp']);
			$row['native_name'] = $languagesDAO->addSlashes($row['native_name']);
			$row['english_name'] = $languagesDAO->addSlashes($row['english_name']);

			$sql = "REPLACE INTO ".TABLE_PREFIX."languages VALUES ('{$row['language_code']}', '{$row['charset']}', '{$row['reg_exp']}', '{$row['native_name']}', '{$row['english_name']}', 3)";
			$result = mysqli_query($languagesDAO->db, $sql);

			$sql = "SELECT * FROM language_text_SVN WHERE language_code='$language_code'";
			$result = mysqli_query($tmp_lang_db, $sql);

			$sql = "REPLACE INTO ".TABLE_PREFIX."language_text VALUES ";
			while ($row = mysqli_fetch_assoc($result)) {
				$row['text'] = $languagesDAO->addSlashes($row['text']);
				$row['context'] = $languagesDAO->addSlashes($row['context']);
				$sql .= "('{$row['language_code']}', '{$row['variable']}', '{$row['term']}', '{$row['text']}', '{$row['revised_date']}', '{$row['context']}'),";
			}
			$sql = substr($sql, 0, -1);
			mysqli_query($languagesDAO->db, $sql);
		}
	}
	
}
?>
