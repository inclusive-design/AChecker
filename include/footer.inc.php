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

global $plates;
if (!defined('AC_INCLUDE_PATH')) { exit; }

$plate['base_path'] = AC_BASE_HREF;
$plate['theme'] = $_SESSION['prefs']['PREF_THEME'];

echo $plates->render('include/footer.tmpl.php', $plate);
?>
