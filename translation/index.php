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

include_once(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/DAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/LanguagesDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');

global $msg, $addslashes;

$dao = new DAO();
$languagesDAO = new LanguagesDAO();
$languageTextDAO = new LanguageTextDAO();

if (isset($_REQUEST['reset_filter'])) unset($_REQUEST);

if (isset($_REQUEST['submit']) || isset($_REQUEST['search']))
{
	if (isset($_REQUEST['submit']))
	{
		if (isset($_REQUEST['term_type']) && $_REQUEST['term_type'] <> '') $term_type = $_REQUEST['term_type'];
		
		$sql = "SELECT * FROM ".TABLE_PREFIX."language_text 
						WHERE language_code='".DEFAULT_LANGUAGE_CODE."'";
		
		if ($term_type <> '') $sql .= " AND variable = '".$term_type."'";
		
		if (isset($_REQUEST['new_or_translated']) && ($_REQUEST['new_or_translated'] == 1 || $_REQUEST['new_or_translated'] == 2))
		{
			$subquery = "(SELECT term FROM ".TABLE_PREFIX."language_text
										WHERE language_code='".$_REQUEST['lang_code']."'
										  AND text <> '')";
			
			if ($_REQUEST['new_or_translated'] == 1) $sql .= " AND term NOT IN ".$subquery;
			if ($_REQUEST['new_or_translated'] == 2) $sql .= " AND term IN ".$subquery;
		}
		
		if (isset($_REQUEST['new_or_translated']) && $_REQUEST['new_or_translated'] == 3)
		{
			$sql = "select * from ".TABLE_PREFIX."language_text a 
							where language_code='".DEFAULT_LANGUAGE_CODE."' 
								and exists (select 1 from ".TABLE_PREFIX."language_text b 
														where language_code = '".$_REQUEST['lang_code']."' 
															and a.term = b.term 
															and a.revised_date > b.revised_date)";
		}
	}
	
	if (isset($_REQUEST['search']))
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."language_text 
						WHERE language_code='".DEFAULT_LANGUAGE_CODE."'
						  AND lower(term) like '%".$addslashes(strtolower(trim($_REQUEST['search_phase'])))."%'";
	}
	
	$rows = $dao->execute($sql);
	
	if (is_array($rows)) $num_results = count($rows);
	else $num_results = 0;
}

if (isset($_REQUEST["save"]))
{
	$sql_save	= "REPLACE INTO ".TABLE_PREFIX."language_text VALUES ('".$_POST["lang_code"]."', '".$_POST["variable"]."', '".$_POST["term"]."', '".$addslashes($_POST["translated_text"])."', NOW(), '')";

	if (!$dao->execute($sql_save)) {
		$success_error = '<div class="error">Error: changes not saved!</div>';
	}
	else {
		$success_error = '<div class="feedback2"">Success: changes saved.</div>';
	}
}

$rows_lang = $languagesDAO->getAll();				

include(AC_INCLUDE_PATH.'header.inc.php');

?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div class="input-form">
		<div class="row">
			<div class="required" title="<?php echo _AC('required_field'); ?>">*</div>
			<label for="lang_code"><?php echo _AC('choose_lang'); ?></label>:
			<select name="lang_code" id="lang_code"> 
<?php 
	foreach ($rows_lang as $row_lang)
	{
?>
				<option value="<?php echo $row_lang['language_code']; ?>" <?php if ($_REQUEST["lang_code"] == $row_lang['language_code'] || $row_lang['language_code'] == $_SESSION['lang']) echo 'selected="selected"'; ?>><?php echo htmlspecialchars($row_lang["english_name"]); ?></option>
<?php
	}
?>
			</select>
		</div>

<?php  ?>
	<fieldset class="group_form"><legend class="group_form"><?php echo _AC('filter'); ?></legend>

<?php if (isset($num_results)) { ?>
		<div class="row">
			<h2><?php echo _AC('results_found', $num_results); ?></h2>
		</div>
<?php } ?>

		<div>
			<?php echo _AC('new_or_translated'); ?><br />
			<input type="radio" name="new_or_translated" value="0" id="u0" <?php if (!isset($_REQUEST['new_or_translated']) || $_REQUEST['new_or_translated'] == 0) { echo 'checked="checked"'; } ?> /><label for="u0"><?php echo _AC('all'); ?></label> 
			<input type="radio" name="new_or_translated" value="1" id="u1" <?php if ($_REQUEST['new_or_translated'] == 1) { echo 'checked="checked"'; } ?> /><label for="u1"><?php echo _AC('new_terms'); ?></label> 
			<input type="radio" name="new_or_translated" value="2" id="u2" <?php if ($_REQUEST['new_or_translated'] == 2) { echo 'checked="checked"'; } ?> /><label for="u2"><?php echo _AC('translated_terms'); ?></label> 
			<input type="radio" name="new_or_translated" value="3" id="u3" <?php if ($_REQUEST['new_or_translated'] == 3) { echo 'checked="checked"'; } ?> /><label for="u3"><?php echo _AC('updated_terms'); ?></label> 
		</div>

		<div>
			<?php echo _AC('term_type'); ?><br />
			<input type="radio" name="term_type" value="" id="t0" <?php if (!isset($_REQUEST['term_type']) || $_REQUEST['term_type'] == "") { echo 'checked="checked"'; } ?> /><label for="t0"><?php echo _AC('all'); ?></label> 
			<input type="radio" name="term_type" value="_template" id="t1" <?php if ($_REQUEST['term_type'] == "_template") { echo 'checked="checked"'; } ?> /><label for="t1"><?php echo _AC('interface_terms'); ?></label> 
			<input type="radio" name="term_type" value="_msgs" id="t2" <?php if ($_REQUEST['term_type'] == "_msgs") { echo 'checked="checked"'; } ?> /><label for="t2"><?php echo _AC('msg_terms'); ?></label> 
			<input type="radio" name="term_type" value="_check" id="t3" <?php if ($_REQUEST['term_type'] == "_check") { echo 'checked="checked"'; } ?> /><label for="t3"><?php echo _AC('check_terms'); ?></label> 
			<input type="radio" name="term_type" value="_guideline" id="t4" <?php if ($_REQUEST['term_type'] == "_guideline") { echo 'checked="checked"'; } ?> /><label for="t4"><?php echo _AC('guideline_terms'); ?></label> 
			<input type="radio" name="term_type" value="_test" id="t5" <?php if ($_REQUEST['term_type'] == "_test") { echo 'checked="checked"'; } ?> /><label for="t5"><?php echo _AC('test_terms'); ?></label> 
		</div>

		<div>
			<input type="submit" name="search" value="<?php echo _AC('search_phase'); ?>" class="submit" style="display:none;" />
			<input type="submit" name="submit" value="<?php echo _AC('submit'); ?>" class="submit" />
			<input type="submit" name="reset_filter" value="<?php echo _AC('reset_filter'); ?>" class="submit" />
		</div>

		<div>
			<label for="search_phase"><?php echo _AC('or'). ",<br /><br />" . _AC('search_text'); ?></label>
		</div>

		<div>
			<input size="60" type="text" name="search_phase" id="search_phase" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['search_phase'])); ?>" /> 
		</div>

		<div>
			<input type="submit" name="search" value="<?php echo _AC('search_phase'); ?>" class="submit" /> 
		</div>
	</fieldset>
	
	</div>
</form>

<?php 
if (isset($_REQUEST['selected_term'])) 
{
	$sql_english	= "SELECT * FROM ".TABLE_PREFIX."language_text WHERE language_code='".DEFAULT_LANGUAGE_CODE."' AND term='".$_REQUEST["selected_term"]."'";
	if ($_REQUEST["term_type"] <> "") $sql_english .= " AND variable='".$_REQUEST["term_type"]."' ";

	$rows_english = $dao->execute($sql_english);
	$row_english = $rows_english[0];

	$rows_selected = $languageTextDAO->getByTermAndLang($_REQUEST["selected_term"], $_REQUEST["lang_code"]);
	
function trans_form() {
	global $row_english, $rows_selected;
	global $langs;
	global $success_error;
	global $db;
	global $addslashes;

	if (!is_array($rows_selected)) // add new term
		$add_new = true;
	else // update existing one
	{
		$row_selected = $rows_selected[0];
		$add_new = false;
	}
?>
<br />
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#anchor">
	<input type="hidden" name="selected_term" value="<?php echo $_REQUEST['selected_term']; ?>" />
	<input type="hidden" name="lang_code" value="<?php echo $_REQUEST['lang_code']; ?>" />
	<input type="hidden" name="new_or_translated" value="<?php echo $_REQUEST['new_or_translated']; ?>" />
	<input type="hidden" name="term_type" value="<?php echo $_REQUEST['term_type']; ?>" />
	<input type="hidden" name="search_phase" value="<?php echo htmlspecialchars(stripslashes($_REQUEST['search_phase'])); ?>" />
	<input type="hidden" name="variable" value="<?php echo $row_english['variable']; ?>" />
	<input type="hidden" name="term" value="<?php echo $row_english['term']; ?>" />
<?php if (isset($_REQUEST["submit"])) { ?>
	<input type="hidden" name="submit" value="1" />
<?php } ?>
<?php if (isset($_REQUEST["search"])) { ?>
	<input type="hidden" name="search" value="1" />
<?php } ?>

	<table border="0" cellspacing="0" cellpadding="2" width="100%" align="left" class="box">
	<tr>
		<th class="box" colspan="2">Edit</th>
	</tr>

	<?php if ($row_english['context'] <> "") { ?>
	<tr>
		<td align="right"><b><?php echo _AC('english_context'); ?>:</b></td>
		<td><?php echo htmlspecialchars(stripslashes($row_english['context'])); ?></td>
	</tr>
	<tr>
		<td align="right"><b><?php echo _AC('translated_context'); ?>:</b></td>
		<td><input type="text" name="translated_context" class="input" value="<?php echo htmlspecialchars(stripslashes($row_selected['context'])); ?>" size="45" /></td>
	</tr>
	<?php } ?>

	<tr>
		<td valign="top" align="right" nowrap="nowrap"><b><?php echo _AC('english_text'); ?>:</b></td>
		<td><?php echo nl2br(htmlspecialchars(stripslashes($row_english['text']))); ?></td>
	</tr>
	<tr>
		<td valign="top" align="right" nowrap="nowrap"><b><label for="translated_text"><?php echo _AC('translated_text'); ?></label>:</b></td>
		<td><textarea rows="4" cols="75" name="translated_text" id="translated_text" class="input2"><?php echo htmlspecialchars(stripslashes($row_selected['text']));?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="save" value="Save ALT-S" class="submit" accesskey="s" />
		</td>
	</tr>
	</table>
	</form>

	<?php
		echo $success_error;
	}
}
//displaying templates
if ($num_results > 0)
{
	echo '<h3 class="indent">'. _AC("result") .'</h3>'."\n";
	echo '<div class="input-form">'."\n";
	echo '<br /><ul>'."\n";
	if (is_array($rows))
	{
		if (isset($_REQUEST["submit"]))
			$submits = htmlspecialchars(SEP)."submit=1";
		if (isset($_REQUEST["search"]))
			$submits .= htmlspecialchars(SEP)."search=1";

		foreach ($rows as $row) 
		{
			if ($row['term'] == $_REQUEST["selected_term"])
				echo '<li>'."\n".'<a name="anchor" title="anchor"></a>'."\n";
			else
				echo '<li>'."\n";
	
//			if ($row['term'] == $_REQUEST["search_phase"]) {
				echo '<a href="'.$_SERVER['PHP_SELF'].'?selected_term='.$row['term'].htmlspecialchars(SEP).'lang_code='.$_REQUEST['lang_code'].htmlspecialchars(SEP).'new_or_translated='.$_REQUEST["new_or_translated"].htmlspecialchars(SEP).'term_type='.$_REQUEST["term_type"].htmlspecialchars(SEP).'search_phase='.$_REQUEST["search_phase"].$submits.'#anchor" ';
				if ($row['term'] == $_REQUEST["selected_term"]) echo 'class="selected"';
				echo '>';
				echo $row['term'];
				echo '</a>'."\n";
//			} 
	
			// display if the term is new or translated
			$rows_check = $languageTextDAO->getByTermAndLang($row['term'], $_REQUEST['lang_code']);
			$row_check = $rows_check[0];
			
			// check if the term is new
			if ($row_check['text'] == '')
				echo '&nbsp;<small>*New*</small>'."\n";
			
			// compare revised_date to see if the term is updated since last translation
			if ($row_check['revised_date'] <> '' && $row['revised_date'] > $row_check['revised_date'])
				echo '&nbsp;<small>*Updated*</small>'."\n";
				
			echo '<br /><br/>';
			// display translation form
			if ($row['term'] == $_REQUEST["selected_term"]) trans_form();
			
			echo '</li>'."\n";
		}
	}
	echo '</ul>'."\n";
	echo '</div>'."\n";
}

include(AC_INCLUDE_PATH.'footer.inc.php'); 
?>
