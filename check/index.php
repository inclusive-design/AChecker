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
include(AC_INCLUDE_PATH.'vitals.inc.php');
include(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');

// initialize constants
$results_per_page = 50;
$dao = new DAO();

// handle submit
if ( (isset($_GET['edit']) || isset($_GET['edit_function'])) && !isset($_GET['id']) ) {
	$msg->addError('SELECT_ONE_ITEM');
} else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location: check_create_edit.php?id='.$_GET['id']);
	exit;
} else if (isset($_GET['edit_function'], $_GET['id'])) {
	header('Location: check_function_edit.php?id='.$_GET['id']);
	exit;
} else if ( isset($_GET['delete'], $_GET['id'])) {
	header('Location: check_delete.php?id='.$_GET['id']);
	exit;
} else if (isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['edit_function'])) {
	$msg->addError('NO_ITEM_SELECTED');
}

// page initialize
if ($_GET['reset_filter']) {
	unset($_GET);
}

$page_string = '';
$orders = array('asc' => 'desc', 'desc' => 'asc');
$cols   = array('html_tag' => 1, 'public_field' => 1, 'confidence' => 1, 'description' => 1, 'open_to_public' => 1);

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = isset($cols[$_GET['asc']]) ? $_GET['asc'] : 'html_tag';
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = isset($cols[$_GET['desc']]) ? $_GET['desc'] : 'html_tag';
} else {
	// no order set
	$order = 'asc';
	$col   = 'html_tag';
}

// initialize default search values
if (!isset($_GET['html_tag']) || $_GET['html_tag'] == '') $_GET['html_tag'] = '-1';
if (!isset($_GET['confidence']) || $_GET['confidence'] == '') $_GET['confidence'] = '-1';
if (!isset($_GET['open_to_public']) || $_GET['open_to_public'] == '') $_GET['open_to_public'] = '-1';

if ($_GET['html_tag'] && $_GET['html_tag'] <> -1) {
	$condition = " html_tag = '".$_GET['html_tag']."'";
	$page_string .= SEP.'html_tag='.urlencode($_GET['html_tag']);
}

if (isset($_GET['confidence']) && $_GET['confidence'] <> -1) {
	$_GET['confidence'] = intval($_GET['confidence']);
	$page_string .= SEP.'confidence='.intval($_GET['confidence']);

	if ($_GET['confidence'] <> -1) 
	{
		if ($condition <> '') $condition .= ' AND';
		$condition .= ' confidence = ' . intval($_GET['confidence']);
	}
}

if (isset($_GET['open_to_public']) && $_GET['open_to_public'] <> -1) {
	$_GET['open_to_public'] = intval($_GET['open_to_public']);
	$page_string .= SEP.'open_to_public='.intval($_GET['open_to_public']);

	if ($_GET['open_to_public'] <> -1)
	{
		if ($condition <> '') $condition .= ' AND';
		$condition .= ' open_to_public = ' . intval($_GET['open_to_public']);
	}
}

if ($condition == '') $condition = '1';

$sql = "SELECT COUNT(check_id) AS cnt FROM ".TABLE_PREFIX."checks WHERE $condition";

$rows = $dao->execute($sql);
$num_results = $rows[0]['cnt'];

$num_pages = max(ceil($num_results / $results_per_page), 1);
$page = intval($_GET['p']);
if (!$page) {
	$page = 1;
}	
$count  = (($page-1) * $results_per_page) + 1;
$offset = ($page-1)*$results_per_page;

if ( isset($_GET['apply_all']) && $_GET['change_status'] >= -1) {
	$offset = 0;
	$results_per_page = 999999;
}

$sql = "SELECT * 
          FROM ".TABLE_PREFIX."checks
          WHERE $condition ORDER BY $col $order LIMIT $offset, $results_per_page";

$check_rows = $dao->execute($sql);

$checksDAO = new ChecksDAO();

$savant->assign('check_rows', $check_rows);
$savant->assign('all_html_tags', $checksDAO->getAllHtmlTags());
$savant->assign('results_per_page', $results_per_page);
$savant->assign('num_results', $num_results);
$savant->assign('col_counts', $col_counts);
$savant->assign('page',$page);
$savant->assign('page_string', $page_string);
$savant->assign('orders', $orders);
$savant->assign('order', $order);
$savant->assign('col', $col);

$savant->display('check/index.tmpl.php');

?>
