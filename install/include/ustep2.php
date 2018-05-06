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

ignore_user_abort(true); 
@set_time_limit(0); 

if (!defined('AC_INCLUDE_PATH')) { exit; }

function update_one_ver($up_file) {
	global $progress;
	$update_file = implode('_',$up_file);
	queryFromFile('db/'.$update_file.'sql');
	//$progress[] = 'Successful update from version '.$up_file[2].' to '.$up_file[4];
	return $up_file[4];
}

$_POST['db_login'] = urldecode($_POST['db_login']);
$_POST['db_password'] = urldecode($_POST['db_password']);

	unset($errors);

	//check DB & table connection

	$db = mysqli_connect($_POST['db_host'], $_POST['db_login'], urldecode($_POST['db_password']), $_POST['db_name'], $_POST['db_port']);

	if (!$db) {
		$error_no = mysqli_errno($db);
		if ($error_no == 2005) {
			$errors[] = 'Unable to connect to database server. Database with hostname '.$_POST['db_host'].' not found.';
		} else {
			$errors[] = 'Unable to connect to database server. Wrong username/password combination.';
		}
	} else {
		if (!mysqli_select_db($db, $_POST['db_name'])) {
			$errors[] = 'Unable to connect to database <b>'.$_POST['db_name'].'</b>.';
		}

		$sql = "SELECT VERSION() AS version";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		if (version_compare($row['version'], '5.6', '>=') === FALSE) {
			$errors[] = 'MySQL version '.$row['version'].' was detected. AChecker requires version 5.6 or later.';
		}

		if (!$errors) {
			$progress[] = 'Connected to database <b>'.$_POST['db_name'].'</b> successfully.';
			unset($errors);

			//Save all the course primary language into session variables iff it has not been set. 
//			if (!isset($_SESSION['course_info'])){
//				$sql = "SELECT a.course_id, a.title, l.language_code, l.char_set FROM ".$_POST['tb_prefix']."courses a left join ".$_POST['tb_prefix']."languages l ON l.language_code = a.primary_language";
//				$result = mysql_query($sql, $db);
//				while ($row = mysql_fetch_assoc($result)){
//					$_SESSION['course_info'][$row['course_id']] = array('char_set'=>$row['char_set'], 'language_code'=>$row['language_code']);
//				}
//			}

//			$sql = "DELETE FROM ".$_POST['tb_prefix']."language_text";
//			@mysql_query($sql, $db);

			$sql = "DELETE FROM ".$_POST['tb_prefix']."languages WHERE language_code<>'eng'";
			mysqli_query($db, $sql);

			//get list of all update scripts minus sql extension
			$files = scandir('db'); 
			foreach ($files as $file) {
				if(count($file = explode('_',$file))==5) {
					$file[4] = substr($file[4],0,-3);
					$update_files[$file[2]] = $file;
				}
			}
			
			$curr_ver = $_POST['old_version'];
			ksort($update_files);
			foreach ($update_files as $up_file) {
				if(version_compare($curr_ver, $up_file[4], '<')) {
					update_one_ver($up_file);
				}
			}
			
			/* reset all the accounts to English */
//			$sql = "UPDATE ".$_POST['tb_prefix']."users SET language='eng', creation_date=creation_date, last_login=last_login";
//			@mysql_query($sql, $db);

			queryFromFile('db/language_text.sql');

			if (!$errors) {
				print_progress($step);

				unset($_POST['submit']);
				store_steps(1);
				print_feedback($progress);

				echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="form">
				<input type="hidden" name="step" value="3" />
				<input type="hidden" name="upgrade_action" value="true" />';
				echo '<input type="hidden" name="db_login" value="'.urlencode($_POST['db_login']).'" />';
				echo '<input type="hidden" name="db_password" value="'.urlencode($_POST['db_password']).'" />';
				echo '<input type="hidden" name="db_host" value="'.$_POST['db_host'].'" />';
				echo '<input type="hidden" name="db_name" value="'.$_POST['db_name'].'" />';
				echo '<input type="hidden" name="db_port" value="'.$_POST['db_port'].'" />';
				echo '<input type="hidden" name="tb_prefix" value="'.$_POST['tb_prefix'].'" />';
				echo '<input type="hidden" name="old_version" value="'.$_POST['old_version'].'" />';
				echo '<input type="hidden" name="new_version" value="'.$_POST['new_version'].'" />';
				print_hidden(2);
				echo '<p align="center"><input type="submit" class="button" value=" Next &raquo; " name="submit" /></p></form>';
				return;
			}
		}
	}

	print_progress($step);

	unset($_POST['submit']);
	if (isset($progress)) {
		print_feedback($progress);
	}

	if (isset($errors)) {
		print_errors($errors);
	}


	echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="form">
	<input type="hidden" name="step" value="2" />';
	store_steps(1);
	print_hidden(2);
	
	if ($found_lang) {
?>
<table width="60%" class="tableborder" cellspacing="0" cellpadding="1" border="0" align="center">
<tr>
	<td colspan="2" class="row1"><p><small>All installed language packs and changes made to the default English language will be deleted. You will have to re-install any language packs by downloading the latest versions from achecker.ca. Some language packs may not currently be available.</small></p></td>
</tr>
<tr>
	<td class="row1"><small><b><label for="dir">Continue with the upgrade?</label></b></small></td>
		<td class="row1" valign="middle" nowrap="nowrap"><input type="radio" name="override" value="1" id="c2" /><label for="c2">Yes, Continue</label>, <input type="radio" name="override" value="0" id="c1" checked="checked" /><label for="c1">No, Cancel</label></td>
</tr>
</table><br />
	<?php
	}

	echo '<input type="hidden" name="db_login" value="'.urlencode($_POST['db_login']).'" />';
	echo '<input type="hidden" name="db_password" value="'.urlencode($_POST['db_password']).'" />';
	echo '<input type="hidden" name="db_host" value="'.$_POST['db_host'].'" />';
	echo '<input type="hidden" name="db_name" value="'.$_POST['db_name'].'" />';
	echo '<input type="hidden" name="db_port" value="'.$_POST['db_port'].'" />';
	echo '<input type="hidden" name="tb_prefix" value="'.$_POST['tb_prefix'].'" />';
	echo '<input type="hidden" name="old_version" value="'.$_POST['old_version'].'" />';
	echo '<input type="hidden" name="new_version" value="'.$_POST['new_version'].'" />';

	echo '<p align="center"><input type="submit" class="button" value=" Retry " name="submit" /></p></form>';
	return;
?>