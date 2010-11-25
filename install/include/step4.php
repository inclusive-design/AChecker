<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

if (!defined('AC_INCLUDE_PATH')) { exit; }

if (isset($_POST['submit'])) {
	$_POST['content_dir'] = $stripslashes($_POST['content_dir']);

	unset($errors);

	if (!file_exists($_POST['content_dir']) || !realpath($_POST['content_dir'])) {
		$errors[] = '<strong>Temporary Directory</strong> entered does not exist.';
	} else if (!is_dir($_POST['content_dir'])) {
		$errors[] = '<strong>Temporary Directory</strong> is not a directory.';
	} else if (!is_writable($_POST['content_dir'])){
		$errors[] = 'The Temporary Directory is not writable.';
	} else {

		$_POST['content_dir'] = realpath(urldecode($_POST['content_dir']));

		if (!is_dir($_POST['content_dir'].'/import')) {
			if (!@mkdir($_POST['content_dir'].'/import')) {
				$errors[] = '<strong>'.$_POST['content_dir'].'/import</strong> directory does not exist and cannot be created.';  
			}
		} else if (!is_writable($_POST['content_dir'].'/import')){
			$errors[] = '<strong>'.$_POST['content_dir'].'/import</strong> directory is not writable.';
		} 

		if (!is_dir($_POST['content_dir'].'/updater')) {
			if (!@mkdir($_POST['content_dir'].'/updater')) {
				$errors[] = '<strong>'.$_POST['content_dir'].'/updater</strong> directory does not exist and cannot be created.';  
			}
		} else if (!is_writable($_POST['content_dir'].'/updater')){
			$errors[] = '<strong>'.$_POST['content_dir'].'/updater</strong> directory is not writable.';
		}

		// save blank index.html pages to those directories
		@copy('../images/index.html', $_POST['content_dir'] . '/import/index.html');
		@copy('../images/index.html', $_POST['content_dir'] . '/index.html');
	}

	if (!isset($errors)) {
		unset($errors);
		unset($_POST['submit']);
		unset($action);

		if (substr($_POST['content_dir'], -1) !='\\'){
			$_POST['content_dir'] .= DIRECTORY_SEPARATOR;
		}

		// kludge to fix the missing slashes when magic_quotes_gpc is On
		if ($addslashes != 'mysql_real_escape_string') {
			$_POST['content_dir'] = addslashes($_POST['content_dir']);
		}

		store_steps($step);
		$step++;
		return;
	} else {
		// kludge to fix the missing slashes when magic_quotes_gpc is On
		if ($addslashes != 'mysql_real_escape_string') {
			$_POST['content_dir'] = addslashes($_POST['content_dir']);
		}
	}
}	

print_progress($step);

if (isset($errors)) {
	print_errors($errors);
}

if (isset($_POST['step1']['old_version'])) 
{
	//get real path to old content

	$old_install   = realpath('../../' . DIRECTORY_SEPARATOR . $_POST['step1']['old_path']);
	$old_config_cd = urldecode($_POST['step1']['content_dir']); // this path may not exist
	$new_install   = realpath('../');

	$path_info = pathinfo($old_config_cd);
	$content_dir_name = $path_info['basename'];

	if ($new_install . DIRECTORY_SEPARATOR . $content_dir_name . DIRECTORY_SEPARATOR == $old_config_cd) {
		// case 2
		$copy_from     = $old_install . DIRECTORY_SEPARATOR . $content_dir_name;
	} else {
		// case 3 + 4
		// it's outside
		$copy_from = '';
	}

	$_defaults['content_dir'] = $old_config_cd;

}
else
{
	$defaults = $_defaults;
	$blurb = '';
	
	// the following code checks to see if get.php is being executed, then sets $_POST['get_file'] appropriately:
	$headers = array();
	$path  = substr($_SERVER['PHP_SELF'], 0, -strlen('install/install.php')) . 'get.php/?test';
	$port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
	
	$host = parse_url($_SERVER['HTTP_HOST']);
	
	if (isset($host['path'])) {
		$host = $host['path'];
	} else if (isset($host['host'])) {
		$host = $host['host'];
	} else {
		$_SERVER['HTTP_HOST'];
	}
	if ($port == 443) {
		// if we're using SSL, but don't know if support is compiled into PHP:
		$fd = @fopen('https://'.$host.$path, 'rb');
		print('https://'.$host.$path);
		if ($fd === false) {
			$content = false;
		} else {
			$content = @fread($fd, filesize($filename));
			@fclose($fd);
		}
	
		if (strlen($content) == 0) {
			$headers[] = 'AChecker-Get: OK';
		} else {
			$headers[] = '';
		}
	} else {
		$fp   = @fsockopen($host, $port, $errno, $errstr, 15);
		
		if($fp) {
			$head = 'HEAD '.@$path. " HTTP/1.0\r\nHost: ".@$host."\r\n\r\n";
			fputs($fp, $head);
			while(!feof($fp)) {
				if ($header = trim(fgets($fp, 1024))) {
					$headers[] = $header;
				}
			}
		}
	}
	
	if (in_array('AChecker-Get: OK', $headers)) {
		$get_file = 'TRUE';
	} else {
		$get_file = 'FALSE';
	}
}

?>
<br />
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="step" value="<?php echo $step; ?>" />
	<input type="hidden" name="copy_from" value="<?php echo $copy_from; ?>" />
	<input type="hidden" name="get_file" value="<?php echo $get_file; ?>" />
	<?php print_hidden($step); ?>

<?php if (isset($_POST['step1']['old_version'])) : ?>
	<input type="hidden" name="content_dir" value="<?php echo $_defaults['content_dir']; ?>" />
	<table width="80%" class="tableborder" cellspacing="0" cellpadding="1" align="center">	
	<tr>
		<td class="row1">The temporary directory at <strong><?php echo $_defaults['content_dir']; ?> </strong> will be used for this installation. Please create it if it does not already exist.</td>
	</tr>
	</table>
<?php elseif ($get_file == 'FALSE') : ?>
	<input type="hidden" name="content_dir" value="<?php if (!empty($_POST['content_dir'])) { echo $_POST['content_dir']; } else { echo $_defaults['content_dir']; } ?>" />

	<table width="80%" class="tableborder" cellspacing="0" cellpadding="1" align="center">	
	<tr>
		<td class="row1"><div class="required" title="Required Field">*</div><strong><label for="contentdir">Temporary Directory</label></strong>
		<p>It has been detected that your webserver does not support the protected temporary directory feature. The temporary directory stores all of the temporary files.</p>
		<p>Due to that restriction your temporary directory must exist within your AChecker installation directory and cannot be moved. Its path is specified below. Please create it if it does not already exist.</p>
		<br /><br />
		<input type="text" name="content_dir_disabled" id="contentdir" value="<?php if (!empty($_POST['content_dir'])) { echo $_POST['content_dir']; } else { echo $_defaults['content_dir']; } ?>" class="formfield" size="70" disabled="disabled" /></td>
	</tr>
	</table>
<?php else: ?>
	<table width="80%" class="tableborder" cellspacing="0" cellpadding="1" align="center">	
	<tr>
		<td class="row1"><div class="required" title="Required Field">*</div><strong><label for="contentdir">Temporary Directory</label></strong>
		<p>Please specify where the temporary directory should be. The temporary directory stores all of the temporary files. As a security measure, the temporary directory should be placed <em>outside</em> of your AChecker installation (for example, to a non-web-accessible location that is not publically available).</p>
		
		<p>On a Windows machine, the path should look like <kbd>C:\temp</kbd>, while on Unix it should look like <kbd>/var/temp</kbd>.</p>
		
		<p>The directory you specify must be created if it does not already exist and be writeable by the webserver. On Unix machines issue the command <kbd>chmod a+rwx temp</kbd>, additionally the path may not contain any symbolic links.</p>

		<input type="text" name="content_dir" id="contentdir" value="<?php if (!empty($_POST['content_dir'])) { echo $_POST['content_dir']; } else { echo $_defaults['content_dir']; } ?>" class="formfield" size="70" /></td>
	</tr>
	</table>
<?php endif; ?>
	<br /><br /><p align="center"><input type="submit" class="button" value=" Next &raquo;" name="submit" /></p>
</form>