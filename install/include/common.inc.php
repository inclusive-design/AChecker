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

if (!defined('AC_INCLUDE_PATH')) { exit; }
error_reporting(E_ALL ^ E_NOTICE);

/* achecker default configuration options */
/* used on: step3.php, step4.php, step5.php */
$_defaults['admin_username'] = 'admin';
$_defaults['admin_password'] = '';
$_defaults['admin_email'] = '';

$_defaults['site_name'] = 'Web Accessibility Checker';
$_defaults['header_img'] = '';
$_defaults['header_logo'] = '';
$_defaults['home_url'] = '';

$_defaults['email_notification'] = 'TRUE';
$_defaults['email_confirmation'] = 'TRUE';

$_defaults['max_file_size'] = '1048576';
$_defaults['ill_ext'] = 'exe, asp, php, php3, bat, cgi, pl, com, vbs, reg, pcd, pif, scr, bas, inf, vb, vbe, wsc, wsf, wsh';
$_defaults['cache_dir'] = '';

$_defaults['theme_categories'] = 'FALSE';
$_defaults['content_dir'] = realpath('../').DIRECTORY_SEPARATOR.'temp';

require('include/classes/sqlutility.php');

function my_null_slashes($string) {
	return $string;
}
 
if ( get_magic_quotes_gpc() == 1 ) {
	$stripslashes = 'stripslashes';
} else {
	$stripslashes = 'my_null_slashes';
}

function queryFromFile($sql_file_path)
{
	$sqlUtility = new SqlUtility;
	global $db, $progress, $errors;

	$tables = array();

	if (!file_exists($sql_file_path)) {
		$progress[] = $sql_file_path . ': file not exists.';
		return false;
	}

	$sql_query = trim(fread(fopen($sql_file_path, 'r'), filesize($sql_file_path)));
	$sqlUtility->splitSqlFile($pieces, $sql_query);
	
	foreach ($pieces as $piece) 
	{
		$piece = trim($piece);
		// [0] contains the prefixed query
		// [4] contains unprefixed table name

		if ($_POST['tb_prefix'] || ($_POST['tb_prefix'] == '')){ 
			$prefixed_query = $sqlUtility->prefixQuery($piece, $_POST['tb_prefix']);
		}else{
			$prefixed_query = $piece;
		}
	
		if ($prefixed_query != false ) 
		{
			$prefixed_query[1] = strtoupper($prefixed_query[1]);
			
			$table = $_POST['tb_prefix'].$prefixed_query[4];

			if($prefixed_query[1] == 'CREATE TABLE')
			{
				if (mysqli_query($db, $prefixed_query[0]) !== false)
					$progress[] = 'Table <strong>'.$table . '</strong> created successfully.';
				else 
					if (mysqli_errno($db) == 1050)
						$progress[] = 'Table <strong>'.$table . '</strong> already exists. Skipping.';
					else
						$errors[] = 'Table <strong>' . $table . '</strong> creation failed.';
			}elseif($prefixed_query[1] == 'INSERT INTO'){
				mysqli_query($db, $prefixed_query[0]);
			}elseif($prefixed_query[1] == 'DELETE FROM'){
				mysqli_query($db, $prefixed_query[0]);
			}elseif($prefixed_query[1] == 'REPLACE INTO'){
				mysqli_query($db, $prefixed_query[0]);
			}elseif($prefixed_query[1] == 'ALTER TABLE'){
				if (mysqli_query($db, $prefixed_query[0]) !== false)
					$progress[] = 'Table <strong>'.$table.'</strong> altered successfully.';
				else
					if (mysqli_errno($db) == 1060) 
						$progress[] = 'Table <strong>'.$table . '</strong> fields already exists. Skipping.';
					elseif (mysqli_errno($db) == 1091) 
						$progress[] = 'Table <strong>'.$table . '</strong> fields already dropped. Skipping.';
					else
						$errors[] = 'Table <strong>'.$table.'</strong> alteration failed.';
			}elseif($prefixed_query[1] == 'DROP TABLE'){
				mysqli_query($db, $prefixed_query[1] . ' ' .$table);
			}elseif($prefixed_query[1] == 'UPDATE'){
				mysqli_query($db, $prefixed_query[0]);
			}
		}
	}
	return true;
}

function print_errors( $errors ) {
	?>
	<br />
	<table border="0" class="errbox" cellpadding="3" cellspacing="2" width="90%" summary="" align="center">
	<tr class="errbox">
	<td>
		<h3 class="err"><img src="images/bad.gif" align="top" alt="" class="img" /> Error</h3>
		<?php
			echo '<ul>';
			foreach ($errors as $p) {
				echo '<li>'.$p.'</li>';
			}
			echo '</ul>';
		?>
		</td>
	</tr>
	</table>	<br />
<?php
}

function print_feedback( $feedback ) {
	?>
	<br />
	<table border="0" class="fbkbox" cellpadding="3" cellspacing="2" width="90%" summary="" align="center">
	<tr class="fbkbox">
	<td><h3 class="feedback2"><img src="images/feedback.gif" align="top" alt="" class="img" /> Feedback</h3>
		<?php
			echo '<ul>';
			foreach ($feedback as $p) {
				echo '<li>'.$p.'</li>';
			}
			echo '</ul>';
		?></td>
	</tr>
	</table>
	<br />
<?php
}

function store_steps($step) {

	global $stripslashes;

	foreach($_POST as $key => $value) {
		if (substr($key, 0, strlen('step')) == 'step') {
			continue;
		} else if ($key == 'step') {
			continue;
		} else if ($key == 'action') {
			continue;
		} else if ($key == 'submit') {
			continue;
		}

		$_POST['step'.$step][$key] = urlencode($stripslashes($value));
	}
}


function print_hidden($current_step) {
	for ($i=1; $i<$current_step; $i++) {
		if (is_array($_POST['step'.$i])) {
			foreach($_POST['step'.$i] as $key => $value) {
				echo '<input type="hidden" name="step'.$i.'['.$key.']" value="'.$value.'" />'."\n";
			}
		}
	}
}

function print_progress($step) {
	global $install_steps;
	
	echo '<div class="install"><h3>Installation Progress</h3><p>';

	$num_steps = count($install_steps);
	for ($i=0; $i<$num_steps; $i++) {
		if ($i == $step) {
			echo '<strong style="margin-left: 12px; color: #006699;">Step '.$i.': '.$install_steps[$i]['name'].'</strong>';
		} else {
			echo '<small style="margin-left: 10px; color: gray;">';
			if ($step > $i) {
				echo '<img src="../images/check.gif" height="9" width="9" alt="Step Done!" /> ';
			} else {
				echo '<img src="../images/clr.gif" height="9" width="9" alt="" /> ';
			}
			echo 'Step '.$i.': '.$install_steps[$i]['name'].'</small>';
		}
		if ($i+1 < $num_steps) {
			echo '<br />';
		}
	}
	echo '</p></div><br />';

	echo '<h3>'.$install_steps[$step]['name'].'</h3>';
}


if (version_compare(phpversion(), '5.2') < 0) {
	function scandir($dirstr) {
		$files = array();
		$fh = opendir($dirstr);
		while (false !== ($filename = readdir($fh))) {
			array_push($files, $filename);
		}
		closedir($fh);
		return $files;
	}
}

/**
 * This function is used for printing variables for debugging.
 * @access  public
 * @param   mixed $var	The variable to output
 * @param   string $title	The name of the variable, or some mark-up identifier.
 * @author  Joel Kronenberg
 */
function debug($var, $title='') {
	echo '<pre style="border: 1px black solid; padding: 0px; margin: 10px;" title="debugging box">';
	if ($title) {
		echo '<h4>'.$title.'</h4>';
	}
	
	ob_start();
	print_r($var);
	$str = ob_get_contents();
	ob_end_clean();

	$str = str_replace('<', '&lt;', $str);

	$str = str_replace('[', '<span style="color: red; font-weight: bold;">[', $str);
	$str = str_replace(']', ']</span>', $str);
	$str = str_replace('=>', '<span style="color: blue; font-weight: bold;">=></span>', $str);
	$str = str_replace('Array', '<span style="color: purple; font-weight: bold;">Array</span>', $str);
	echo $str;
	echo '</pre>';
}
?>