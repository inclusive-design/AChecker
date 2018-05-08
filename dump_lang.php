<?php

//define(AC_INCLUDE_PATH, 'include/');
//include(AC_INCLUDE_PATH.'vitals.inc.php');

$lang_db = mysqli_connect('localhost', 'root', '', 'achecker');
mysqli_select_db($lang_db, 'achecker');

$sql	= "SELECT * FROM AC_language_text WHERE language_code='eng' ORDER BY `variable`, `term`";
$result = mysqli_query($lang_db, $sql) or die(mysqli_error($lang_db));

$text = "# Table structure for table 'language_text'

CREATE TABLE `language_text` (
  `language_code` varchar(20) NOT NULL default '',
  `variable` varchar(30) NOT NULL default '',
  `term` varchar(50) NOT NULL default '',
  `text` blob NOT NULL,
  `revised_date` datetime default NULL,
  `context` text,
  PRIMARY KEY  (`language_code`,`variable`,`term`),
  UNIQUE KEY `idx_unique_lang_term` (`language_code`,`term`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table `language_text`

";

while($row = mysqli_fetch_assoc($result)) {
	$row['text'] = mysqli_real_escape_string($lang_db, $row['text']);
	$row['context'] = mysqli_real_escape_string($lang_db,$row['context']);
	$text .= "INSERT INTO `language_text` VALUES ('eng', '$row[variable]','$row[term]','$row[text]','$row[revised_date]','$row[context]');\n";
}

$fh = fopen('install/db/language_text.sql', 'w');
fwrite($fh, $text);
fclose($fh);
?>