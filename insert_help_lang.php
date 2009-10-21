<?php

//define(AC_INCLUDE_PATH, 'include/');
//include(AC_INCLUDE_PATH.'vitals.inc.php');

$lang_db = mysql_connect('localhost:3306', 'root', '');
mysql_select_db('achecker1', $lang_db);

$term=trim('AC_HELP_UPDATER');

$sql = "DELETE FROM `AC_language_text` WHERE term='".$term."'";
$result = mysql_query($sql, $lang_db) or die(mysql_error());

// NOTE: MODIFY THIS 2 VARS

$text = '<h2>Updater</h2>
	<p>The Updater was introduce in AChecker 1.6 to allow administrators to update their systems with feature adjustments, security fixes, and other AChecker code changes in between AChecker releases. The Updater is included as a standard module with AChecker 1.6.1+, and installs as an extra module for 1.6.</p>
<dl>
	<dt>The Update List</dt>
	<dd>On the opening screen of the Updater will appear a list of updates available for the version of AChecker you are using, along with a description of each update.  This list is retrieved from update.atutor.ca, as are the updates themselves, so you must be connected to the Internet. Updates are retrieved from update.atutor.ca by AChecker and applied as necessary. </dd>
	<dt>File Permissions</dt>
	<dd>In most cases you will be asked to temporarily grant write permission to the files that need to be updated or replaced, then once the update has been applied, you will be asked to change the permissions back to read only. It is important that you follow the instructions after updates have been applied, otherwise you run the risk of opening a security hole. 
	</dd>
	<dt>Types of Updates</dt>
	<dd>Updates come in various forms. Some updates replace code in a file with new code. Others replace a file with a new file.  Others may do both on multiple files and multiple code changes. Other updates delete files that are no longer required.</dd>
	<dt>Required and Non-Required Updates</dt>
	<dd>In most cases you will want to install updates in the order they appear in the update list, but not all updates are required updates.  Some feature updates can be ignored if you do not need the features they would add or modify on your system. Other updates will have dependencies, requiring the administrator to install earlier updates before installing a later one. You will be prompted to install previous updates if there are dependencies.</dd>
	<dt>Checks and File Backups</dt>
	<dd>If you have made changes to a file the Updater wishes to change,  you will be prompted to continue or not. The updater compares your local file with the same file in the AChecker code repository, and if they differ the prompt will display. In many cases  the Updater can apply updates without changing the code you have modified, but if the code to be replaced was modified, the update will fail, or if the update replaces a file, your changes will be lost. In all cases the updater will create a backup of the files that were modified, identified by the filename plus the update number added as a suffix. Rename the file to its original name to restore that file back to its original state. You can list these files by clicking the view messages button next to the update listing after the update is installed. After you have confirmed that the updates were applied and are working properly, it is safe to delete the backup files, though it does not hurt to keep them around. </dd>
	<dt>Private Updates</dt>
	<dd>In some cases private updates can be applied by uploading a update file through the upload form below the update list.  Private updates are often those used to apply changes that are not being applied to the AChecker default source code, or to apply custom features, or to share updates between users, etc. When uploading a update, be sure the update id, defined in the patch.xml file, is unique . </dd>
</dl>
';

$sql = "INSERT INTO `AC_language_text` VALUES ('eng', '_msgs','".$term."','".mysql_real_escape_string($text)."',now(),'')";
print $sql;
$result = mysql_query($sql, $lang_db) or die(mysql_error());

?>