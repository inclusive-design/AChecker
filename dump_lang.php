<?php

//define(AC_INCLUDE_PATH, 'include/');
//include(AC_INCLUDE_PATH.'vitals.inc.php');

$lang_db = mysql_connect('localhost:3306', 'root', '');
mysql_select_db('achecker', $lang_db);

$sql	= "SELECT * FROM AC_language_text WHERE language_code='eng' ORDER BY `variable`, `term`";
$result = mysql_query($sql, $lang_db) or die(mysql_error());

$text = "# Table structure for table 'language_text'

CREATE TABLE `language_text` (
  `language_code` varchar(20) NOT NULL default '',
  `variable` varchar(30) NOT NULL default '',
  `term` varchar(50) NOT NULL default '',
  `text` blob NOT NULL,
  `revised_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `context` text,
  PRIMARY KEY  (`language_code`,`variable`,`term`),
  UNIQUE KEY `idx_unique_lang_term` (`language_code`,`term`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table `language_text`

";

while($row = mysql_fetch_assoc($result)) {
	$row['text'] = mysql_real_escape_string($row['text']);
	$row['context'] = mysql_real_escape_string($row['context']);
	$text .= "INSERT INTO `language_text` VALUES ('eng', '$row[variable]','$row[term]','$row[text]','$row[revised_date]','$row[context]');\n";
}

$fh = fopen('install/db/language_text.sql', 'w');
fwrite($fh, $text);
fclose($fh);
?>