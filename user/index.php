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

define('AC_INCLUDE_PATH', '../include/');
include(AC_INCLUDE_PATH.'vitals.inc.php');
include(AC_INCLUDE_PATH.'classes/DAO/UserGroupsDAO.class.php');

// initialize constants
$results_per_page = 50;
$dao = new DAO();

// handle submit
if ( (isset($_GET['edit']) || isset($_GET['password'])) && (isset($_GET['id']) && count($_GET['id']) > 1) ) {
	$msg->addError('SELECT_ONE_ITEM');
} else if (isset($_GET['edit'], $_GET['id'])) {
	header('Location: user_create_edit.php?id='.$_GET['id'][0]);
	exit;
} else if (isset($_GET['password'], $_GET['id'])) {
	header('Location: user_password.php?id='.$_GET['id'][0]);
	exit;
} else if ( isset($_GET['delete'], $_GET['id'])) {
	$ids = implode(',', $_GET['id']);
	header('Location: user_delete.php?id='.$ids);
	exit;
} else if (isset($_GET['edit']) || isset($_GET['delete']) || isset($_GET['password'])) {
	$msg->addError('NO_ITEM_SELECTED');
}

// page initialize
if ($_GET['reset_filter']) {
	unset($_GET);
}

$page_string = '';
$orders = array('asc' => 'desc', 'desc' => 'asc');
$cols   = array('login' => 1, 'public_field' => 1, 'first_name' => 1, 'last_name' => 1, 'user_group' => 1, 'email' => 1, 'status' => 1);

if (isset($_GET['asc'])) {
	$order = 'asc';
	$col   = isset($cols[$_GET['asc']]) ? $_GET['asc'] : 'login';
} else if (isset($_GET['desc'])) {
	$order = 'desc';
	$col   = isset($cols[$_GET['desc']]) ? $_GET['desc'] : 'login';
} else {
	// no order set
	$order = 'desc';
	$col   = 'last_login';
}
if (isset($_GET['status']) && ($_GET['status'] != '')) {
	$_GET['status'] = intval($_GET['status']);
	$status = '=' . intval($_GET['status']);
	$page_string .= htmlspecialchars(SEP).'status'.$status;
} else {
	$status = '<>-1';
	$_GET['status'] = '';
}

if (isset($_GET['include']) && $_GET['include'] == 'one') {
	$checked_include_one = ' checked="checked"';
	$page_string .= htmlspecialchars(SEP).'include=one';
} else {
	$_GET['include'] = 'all';
	$checked_include_all = ' checked="checked"';
	$page_string .= htmlspecialchars(SEP).'include=all';
}
 
if ($_GET['search']) {
	$page_string .= htmlspecialchars(SEP).'search='.urlencode($stripslashes($_GET['search']));
	
	$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
	$search = explode(' ', $search);
	
	if ($_GET['include'] == 'all') {
		$predicate = 'AND ';
	} else {
		$predicate = 'OR ';
	}

	$sql = '';
	foreach ($search as $term) {
		$term = trim($term);
		$term = str_replace(array('%','_'), array('\%', '\_'), $term);
		if ($term) {
			$term = '%'.$term.'%';
			$sql .= "((U.first_name LIKE '$term') OR (U.last_name LIKE '$term') OR (U.email LIKE '$term') OR (U.login LIKE '$term')) $predicate";
		}
	}
	$sql = '('.substr($sql, 0, -strlen($predicate)).')';
	$search = $sql;
} else {
	$search = '1';
}

if ($_GET['user_group_id'] && $_GET['user_group_id'] <> -1) {
	$user_group_sql = "U.user_group_id = ".$_GET['user_group_id'];
	$page_string .= htmlspecialchars(SEP).'user_group_id='.urlencode($_GET['user_group_id']);
}
else
{
	$user_group_sql = '1';
}

$sql	= "SELECT COUNT(user_id) AS cnt FROM ".TABLE_PREFIX."users U WHERE status $status AND $search AND $user_group_sql";

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

$sql = "SELECT U.user_id, U.login, U.first_name, U.last_name, UG.title user_group, U.email, U.status, U.last_login AS last_login 
          FROM ".TABLE_PREFIX."users U, ".TABLE_PREFIX."user_groups UG
          WHERE U.user_group_id = UG.user_group_id
          AND U.status $status AND $search AND $user_group_sql ORDER BY $col $order LIMIT $offset, $results_per_page";

$user_rows = $dao->execute($sql);

if ( isset($_GET['apply_all']) && $_GET['change_status'] >= -1) {
	$ids = '';
	while ($row = mysqli_fetch_assoc($result)) {
		$ids .= $row['user_id'].','; 
	}
	$ids = substr($ids,0,-1);
	$status = intval($_GET['change_status']);

	if ($status==-1) {
		header('Location: user_delete.php?id='.$ids);
		exit;
	} else {
		header('Location: user_status.php?ids='.$ids.'&status='.$status);
		exit;
	}
}

$userGroupsDAO = new UserGroupsDAO();

$savant->assign('user_rows', $user_rows);
$savant->assign('all_user_groups', $userGroupsDAO->getAll());
$savant->assign('results_per_page', $results_per_page);
$savant->assign('num_results', $num_results);
$savant->assign('checked_include_all', $checked_include_all);
$savant->assign('col_counts', $col_counts);
$savant->assign('page',$page);
$savant->assign('page_string', $page_string);
$savant->assign('orders', $orders);
$savant->assign('order', $order);
$savant->assign('col', $col);

$savant->display('user/index.tmpl.php');

?>
