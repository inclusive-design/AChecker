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
* LanguageEditor
* Class for adding/editing language.
* @access	public
* @author	Heidi Hazelton
* @author	Joel Kronenberg
* @package	Language
*/
include_once(AC_INCLUDE_PATH.'classes/DAO/LanguagesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');

class LanguageEditor extends Language {


	// array of missing terms
	var $missingTerms;

	// array of filters ['new', 'update']
	var $filters;
	
	/**
	* Constructor.
	* 
	* Initializes db and parent properties.
	*/
	function LanguageEditor($myLang) {
		global $db, $msg;
		
		global $savant;
		$this->msg = $msg;


		if (isset($myLang)) {
			$this->Language($myLang);
		}
		$this->missingTerms = array();
	}

	// public
	function updateTerm($variable, $term, $text) {

		$variable = $this->addSlashesLang($variable);
		$term     = $this->addSlashesLang($term);
		$text     = $this->addSlashesLang($text);
		$code     = $this->addSlashesLang($this->getCode());
		
		$sql	= "UPDATE ".TABLE_PREFIX."language_text SET text='$text', revised_date=NOW() WHERE language_code='$code' AND variable='$variable' AND term='$term'";

		/*
		if (mysql_query($sql, $this->db)) {
			return TRUE;
		} else {
			debug(mysql_error($this->db));
			return FALSE;
		}
		*/
	}

	// public
	function insertTerm($variable, $key, $text, $context) {

		$variable = $this->addSlashesLang($variable);
		$key      = $this->addSlashesLang($key);
		$text     = $this->addSlashesLang($text);
		$code     = $this->addSlashesLang($this->getCode());
		$context  = $this->addSlashesLang($context);

		$sql = "INSERT INTO ".TABLE_PREFIX."language_text VALUES('$code', '$variable', '$key', '$text', NOW(), '$context')";
	}

	// public
	function showMissingTermsFrame(){
		global $_base_path;
		//$terms = array_slice($this->missingTerms, 0, 20);
		$terms = $this->missingTerms;
		$terms = serialize($terms);
		$terms = urlencode($terms);

		echo '<div align="center"><iframe src="'.$_base_path.'admin/missing_language.php?terms='.$terms.SEP.'lang='.$_SESSION['lang'].'" width="99%" height="300"></div>';
	}

	// public
	// doesn't actually check if params is one of the possible ones.
	// possible params should be array ('new', 'update')
	function setFilter($params){
		if (!is_array($params)) {
			return;
		}

		foreach($params as $param => $garbage) {
			$this->filters[$param] = true;
		}
	}

	// private
	function checkFilter($param) {
		if ($this->filters[$param]) {
			return true;
		}
		return false;
	}

	// public
	function printTerms($terms){
		global $languageManager; // why won't $addslashes = $this->addslashes; work?

		$counter = 0;

		$terms = unserialize(stripslashes($terms));

		natcasesort($terms);

		if ($this->checkFilter('new')) {
			$new_check = ' checked="checked"';
		}
		if ($this->checkFilter('update')) {
			$update_check = ' checked="checked"';
		}

		$fromLanguage = $languageManager->getLanguage(DEFAULT_LANGUAGE_CODE);

		echo '<form method="post" action="'.htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES).'">';
		echo '<table border="0" cellpadding="0" cellspacing="2">';
		echo '<tr>';
		echo '<td>Show: ';
		echo '<input name="filter_new" id="n" value="1" type="checkbox" '.$new_check.' /><label for="n">New Language</label>, ';
		echo '<input name="filter_update" id="u" value="1" type="checkbox" '.$update_check.' /><label for="u">Updated Language</label> ';
		echo '</td>';
		echo '</tr>';

		foreach($terms as $term => $garbage) {
			$to_term   = $this->getTerm($term);
			$from_term = $fromLanguage->getTerm($term);

			$is_new = false;
			if ($to_term === false) {
				$is_new = true;
			}

			$is_old = false;
			if ($to_term['revised_date_unix'] < $from_term['revised_date_unix']) {
				$is_old = true;
			}


			if ($this->checkFilter('new') && !$is_new) {
				continue;
			}

			if ($this->checkFilter('update') && !$is_old) {
				continue;
			}

			if (($counter % 10) == 0) {
				echo '<tr>';
				echo '<td align="center"><input type="submit" name="submit" value="Save Changes" class="button" /></td>';
				echo '</tr>';
			}

			$style = '';
			if ($is_new) {
				$style = 'style="background-color: white; border: red 2px solid;"';
			} else {
				$style = 'style="background-color: white; border: yellow 1px solid;"';
			}

			echo '<tr>';
			echo '<td><strong>[ ' . $term . ' ] '.htmlspecialchars($from_term['text']).'</strong></td></tr>';
			echo '<tr><td><input type="text" name="'.$term.'" '.$style.' size="100" value="'.htmlspecialchars($to_term['text']).'" />';
			echo '<input type="hidden" name="old['.$term.']" '.$style.' size="100" value="'.htmlspecialchars($to_term['text']).'" /></td>';
			echo '</tr>';

			$counter++;
		}
		echo '</table>';
		echo '</form>';
	}

	// public
	function updateTerms($terms) {

		foreach($terms as $term => $text) {
			$text = $this->addSlashesLang($text);
			$term = $this->addSlashesLang($term);
		
			if (($text != '') && ($text != $_POST['old'][$term])) {
				$sql = "REPLACE INTO ".TABLE_PREFIX."language_text VALUES ('".$this->getCode()."', '_template', '$term', '$text', NOW(), '')";
				mysqli_query($this->db, $sql);
			}
		}
	}

	// public
	function addMissingTerm($term) {
		if (!isset($this->missingTerms[$term])) {
			$this->missingTerms[$term] = '';
		}
	}


	// this method should be called staticly: LanguageEditor::import()
	// public
	function import($language_sql_file) {
		// move sql import class from install/ to include/classes/
		// store the lang def'n in a .ini file and use insertLang 
		// after checking if it already exists

		// use the sql class to insert the language into the db

		// check if this language exists before calling this method

		require_once(AC_INCLUDE_PATH . 'classes/sqlutility.class.php');
		$sqlUtility = new SqlUtility();

		$sqlUtility->queryFromFile($language_sql_file, TABLE_PREFIX);
	}

	// sends the generated language pack to the browser
	// public
	function export($filename = '') {
//		$search  = array('"', "'", "\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required
//		$replace = array('\"', "\'", '\0', '\n', '\r', '\Z');

		// use a function to generate the ini file
		// use a diff fn to generate the sql dump
		// use the zipfile class to package the ini file and the sql dump
		
		
		$sql_dump = "INSERT INTO `languages` VALUES ('$this->code', '$this->characterSet', '$this->regularExpression', '$this->nativeName', '$this->englishName', $this->status);\r\n\r\n";

		$sql_dump .= "INSERT INTO `language_text` VALUES ";

		$languageTextDAO = new LanguageTextDAO();
		$rows = $languageTextDAO->getAllByLang($this->code);
		
		if (is_array($rows)) {
			foreach ($rows as $row)
			{
//				$row['text']    = str_replace($search, $replace, $row['text']);
//				$row['context'] = str_replace($search, $replace, $row['context']);
				$row['text']    = filter_var(trim($row['text']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$row['context'] = filter_var(trim($row['context']), FILTER_SANITIZE_STRING);
				
				$sql_dump .= "('$this->code', '$row[variable]', '$row[term]', '$row[text]', '$row[revised_date]', '$row[context]'),\r\n";
			}
		} else {
			$this->msg->addError('LANG_EMPTY');
			return;
		}
		$sql_dump = substr($sql_dump, 0, -3) . ";";

		$readme = 'This is an AChecker language pack. Use the administrator Language section to import this language pack or manually import the contents of the SQL file into your [table_prefix]language_text table, where `table_prefix` should be replaced with your correct AChecker table prefix as defined in ./include/config.inc.php .';

		require(AC_INCLUDE_PATH . 'classes/zipfile.class.php');
		$zipfile = new zipfile();

		$zipfile->add_file($sql_dump, 'language_text.sql');
		$zipfile->add_file($readme, 'readme.txt');
		$zipfile->add_file($this->getXML(), 'language.xml');  
	
		if ($filename) {
			$fp = fopen($filename, 'wb+');
			fwrite($fp, $zipfile->get_file(), $zipfile->get_size());
		} else {
			$version = str_replace('.','_',VERSION);

			$zipfile->send_file('achecker_' . $version . '_' . $this->code);
		}
		
	}

}
?>