<?php

//define(AC_INCLUDE_PATH, 'include/');
//include(AC_INCLUDE_PATH.'vitals.inc.php');

$lang_db = mysql_connect('localhost:3306', 'root', '');
mysql_select_db('achecker', $lang_db);

$term=trim('AC_HELP_TRANSLATION');

$sql = "DELETE FROM `AC_language_text` WHERE term='".$term."'";
$result = mysql_query($sql, $lang_db) or die(mysql_error());

// NOTE: MODIFY THIS 2 VARS

$text = "<h2>Translation</h2>

	<p>All fields are self-explanatory. Note that language drop down box lists all the languages defined in AChecker, no matter the language status (enabled or disabled).</p>

<p>You can contribute to the AChecker community by exporting a language pack from your AChecker installation, and attaching it to a message in the atutor.ca <a href=\"http://atutor.ca/forum/4/1.html\">Translation Forum</a>. Also see the <a href=\"http://atutor.ca/atutor/docs/translate.php\">Translator Documentation</a> for further details about translating AChecker.</p>
";

$sql = "INSERT INTO `AC_language_text` VALUES ('eng', '_msgs','".$term."','".mysql_real_escape_string($text)."',now(),'')";
print $sql;
$result = mysql_query($sql, $lang_db) or die(mysql_error());

?>