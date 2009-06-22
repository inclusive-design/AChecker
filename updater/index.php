<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('AC_INCLUDE_PATH', '../include/');

require (AC_INCLUDE_PATH.'vitals.inc.php');
require_once(AC_INCLUDE_PATH. 'classes/Updater/PatchListParser.class.php');
require_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
require_once(AC_INCLUDE_PATH. 'classes/DAO/PatchesDAO.class.php');
require_once('include/common.inc.php');

set_time_limit(0);

$patchesDAO = new PatchesDAO();

/**
 * Generate html of each patch row at main patch page
 */
function print_patch_row($patch_row, $row_id, $enable_radiotton)
{
	global $id, $patch_id;   // current selected patch
	global $dependent_patches;

	if ($dependent_patches =="")
		$description = $patch_row["description"];
	else
		$description = $patch_row["description"] . _AC('update_dependent_update_not_installed') . "<span style='color: red'>" . $dependent_patches . "</span>";
?>
	<tr <?php if ($enable_radiotton) echo 'onmousedown="document.form[\'m'. $row_id.'\'].checked = true; rowselect(this);" id="r_'. $row_id .'"'; ?>>
		<td><input type="radio" name="id" value="<?php echo $row_id; ?>"<?php if ($enable_radiotton) echo 'id="m'. $row_id.'"'; ?> <?php if (!$enable_radiotton) echo 'disabled="disabled" '; if (strcmp($row_id, $id) == 0 || strcmp($row_id, $patch_id) == 0) echo "checked "?> /></td>
		<td><label <?php if ($enable_radiotton) echo 'for="m'.$row_id.'"'; ?>><?php echo $patch_row["achecker_patch_id"]; ?></label></td>
		<td><?php echo $description; ?></td>
		<td><?php if (!isset($patch_row['status'])) echo _AC("not_installed"); else echo $patch_row["status"]; ?></td>
		<td><?php echo $patch_row["available_to"]; ?></td>
		<td><?php echo $patch_row["author"]; ?></td>
		<td><?php if (isset($patch_row['status'])) echo ($patch_row["installed_date"]=='0000-00-00 00:00:00')?_AC('na'):$patch_row["installed_date"]; ?></td>
		<td>
		<?php 
		if (preg_match('/Installed/', $patch_row["status"]) > 0 && ($patch_row["remove_permission_files"]<> "" || $patch_row["backup_files"]<>"" || $patch_row["patch_files"]<> ""))
			echo '
		  <div class="row buttons">
				<input type="button" align="middle" name="info" value="'._AC('view_message').'" onclick="location.href=\''. $_SERVER['PHP_SELF'] .'?patch_id='.$row_id.'\'" />
			</div>';
		?>
		</td>
	</tr>
<?php
}

// split a string by given delimiter and return an array
function get_array_by_delimiter($subject, $delimiter)
{
	return preg_split('/'.preg_quote($delimiter).'/', $subject, -1, PREG_SPLIT_NO_EMPTY);
}

$skipFilesModified = false;

if ($_POST['yes'])  $skipFilesModified = true;

if ($_POST['no'])
{
	unset($_SESSION['remove_permission']);
	$msg->addFeedback('CANCELLED');
	header('Location: index.php');
	exit;
}

require (AC_INCLUDE_PATH.'header.inc.php');

if (trim($_POST['who']) != '') $who = trim($_POST['who']);
elseif (trim($_REQUEST['who']) != '') $who = trim($_REQUEST['who']);
else $who = "public";

// check the connection to server update.atutor.ca
$update_server = "http://update.atutor.ca"; 
$connection_test_file = $update_server . '/index.php';
$connection = @file_get_contents($connection_test_file);

if (!$connection) 
{
	$infos = array('CANNOT_CONNECT_PATCH_SERVER', $update_server);
	$msg->addInfo($infos);
	$server_connected = false;
}
else
	$server_connected = true;

// get patch list if successfully connect to patch server
if ($server_connected)
{
	$patch_folder = $update_server . '/achecker/patch/' . str_replace('.', '_', VERSION) . '/';
	$patch_list_xml = @file_get_contents($patch_folder . 'patch_list.xml');
	
	if ($patch_list_xml) 
	{
		$patchListParser =& new PatchListParser();
		$patchListParser->parse($patch_list_xml);
		$patch_list_array = $patchListParser->getMyParsedArrayForVersion(VERSION);
	}
}
// end of get patch list

$module_content_folder = AC_TEMP_DIR . "updater/temp";
if (!is_dir($module_content_folder)) mkdir($module_content_folder);

if ($_POST['install_upload'] && $_POST['uploading'])
{
	include_once(AC_INCLUDE_PATH . 'lib/pclzip.lib.php');
	
	// clean up module content folder
	Utility::clearDir($module_content_folder);
	
	// 1. unzip uploaded file to module's content directory
	$archive = new PclZip($_FILES['patchfile']['tmp_name']);

	if ($archive->extract(PCLZIP_OPT_PATH, $module_content_folder) == 0)
	{
	    Utility::clearDir($module_content_folder);
	    $msg->addError('CANNOT_UNZIP');
	}
}

// Installation process
if ($_POST['install'] || $_POST['install_upload'] && !isset($_POST["not_ignore_version"]))
{
	
	if (isset($_POST['id'])) $id=$_POST['id'];
	else $id = $_REQUEST['id'];

	if ($_POST['install'] && $id == "")
	{
		$msg->addError('CHOOSE_UNINSTALLED_PATCH');
	}
	else
	{
		if ($_POST['install'])
		{
			$patchURL = $patch_folder . $patch_list_array[$id][patch_folder] . "/";
		}
		else if ($_POST['install_upload'])
		{
			$patchURL = $module_content_folder . "/";
		}
			
		$patch_xml = @file_get_contents($patchURL . 'patch.xml');
		
		if ($patch_xml === FALSE) 
		{
			$msg->addError('PATCH_XML_NOT_FOUND');
		}
		else
		{
			require_once(AC_INCLUDE_PATH.'classes/Updater/PatchParser.class.php');
			require_once(AC_INCLUDE_PATH.'classes/Updater/Patch.class.php');
			
			$patchParser =& new PatchParser();
			$patchParser->parse($patch_xml);
			
			$patch_array = $patchParser->getParsedArray();

			if ($_POST["ignore_version"]) $patch_array["applied_version"] = VERSION;
			
			if ($_POST["install_upload"])
			{
				$current_patch_list = array('achecker_patch_id' => $patch_array['achecker_patch_id'],
																		'applied_version' => $patch_array['applied_version'],
																		'patch_folder' => $patchURL,
																		'available_to' => 'private',
																		'author' => $patch_array['author'],
																		'sql' => $patch_array['sql'],
																		'description' => $patch_array['description'],
																		'dependent_patches' => $patch_array['dependent_patches']);
			}

			if ($_POST["install"])
			{
				$current_patch_list = $patch_list_array[$id];
				$current_patch_list["sql"] = $patch_array["sql"];
			}

			if ($_POST["install_upload"] && is_patch_installed($patch_array["achecker_patch_id"]))
				$msg->addError('UPDATE_ALREADY_INSTALLED');
			else
			{
				$patch = & new Patch($patch_array, $current_patch_list, $skipFilesModified, $patchURL);
			
				if ($patch->applyPatch())  $patch_id = $patch->getPatchID();
			}
		}
	}
}
// end of patch installation

// display permission and backup files message
if (isSet($_REQUEST['patch_id']))  $patch_id = $_REQUEST['patch_id'];
elseif ($_POST['patch_id']) $patch_id=$_POST['patch_id'];

if ($patch_id > 0)
{
	// clicking on button "Done" at displaying remove permission info page
	if ($_POST['done'])
	{
		$permission_files = array();
		
		if (is_array($_SESSION['remove_permission']))
		{
			foreach ($_SESSION['remove_permission'] as $file)
			{
				if (is_writable($file))  $permission_files[] = $file;
			}
		}
		
		if (count($permission_files) == 0)
		{
			$updateInfo = array("remove_permission_files"=>"", "status"=>"Installed");
		
			$patchesDAO->UpdateByArray($patch_id, $updateInfo);
		}
		else
		{
			foreach($permission_files as $permission_file)
				$remove_permission_files .= $permission_file. '|';
		
			$updateInfo = array("remove_permission_files"=>preg_quote($remove_permission_files), "status"=>"Partly Installed");
			
			$patchesDAO->UpdateByArray($patch_id, $updateInfo);
		}
	
	}
	
	// display remove permission info
	unset($_SESSION['remove_permission']);

	$row = $patchesDAO->getByID($patch_id);
	
	if ($row["remove_permission_files"]<> "")
	{
		$remove_permission_files = $_SESSION['remove_permission'] = get_array_by_delimiter($row["remove_permission_files"], "|");

		if (count($_SESSION['remove_permission']) > 0)
		{
			if ($_POST['done']) $msg->printErrors('REMOVE_WRITE_PERMISSION');
			else $msg->printInfos('PATCH_INSTALLED_AND_REMOVE_PERMISSION');
			
			$feedbacks[] = _AC('remove_write_permission');
			
			foreach($remove_permission_files as $remove_permission_file)
				if ($remove_permission_file <> "") $feedbacks[count($feedbacks)-1] .= "<strong>" . $remove_permission_file . "</strong><br />";

			$notes = '<form action="'. $_SERVER['PHP_SELF'].'?patch_id='.$patch_id.'" method="post" name="remove_permission">
		  <div class="row buttons">
				<input type="hidden" name="patch_id" value="'.$patch_id.'" />
				<input type="submit" name="done" value="'._AC('done').'" accesskey="d" />
			</div>
			</form>';
		}

		print_errors($feedbacks, $notes);
	}

	// display backup file info after remove permission step
	if ($row["remove_permission_files"] == "")
	{
		$msg->printFeedbacks('PATCH_INSTALLED_SUCCESSFULLY');
		
		if ($row["backup_files"]<> "")
		{
			$backup_files = get_array_by_delimiter($row["backup_files"], "|");
	
			if (count($backup_files) > 0)
			{
				$feedbacks[] = _AC('updater_show_backup_files');
				
				foreach($backup_files as $backup_file)
					if ($backup_file <> "") $feedbacks[count($feedbacks)-1] .= "<strong>" . $backup_file . "</strong><br />";
			}
		}

		if ($row["patch_files"]<> "")
		{
			$patch_files = get_array_by_delimiter($row["patch_files"], "|");
	
			if (count($patch_files) > 0)
			{
				$feedbacks[] = _AC('updater_show_patch_files');
				
				foreach($patch_files as $patch_file)
					if ($patch_file <> "") $feedbacks[count($feedbacks)-1] .= "<strong>" . $patch_file . "</strong><br />";
					
			}
		}
		
		if (count($feedbacks)> 0)
			print_feedback($feedbacks);
		else
			print_feedback(array());
	}
}

$msg->printAll();

// display installed patches
$rows = $patchesDAO->getPatchByVersion(VERSION);

if (is_array($rows)) $num_of_patches_in_db = count($rows);
else $num_of_patches_in_db = 0;

$num_of_patches = $num_of_patches_in_db + count($patch_list_array);

$savant->assign('num_of_patches', $num_of_patches);
$savant->assign('patches_in_db', $rows);
$savant->assign('patch_list_array', $patch_list_array);
$savant->assign('patches_in_db', $rows);
$savant->assign('patches_in_db', $rows);

$savant->display('updater/index.tmpl.php');
?>
