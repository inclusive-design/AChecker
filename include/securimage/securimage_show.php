<?php
define('AC_INCLUDE_PATH', '../');
require (AC_INCLUDE_PATH.'vitals.inc.php');
session_start();

include 'securimage.php';

$img = new securimage();

$img->show(); // alternate use:  $img->show('/path/to/background.jpg');
?>
