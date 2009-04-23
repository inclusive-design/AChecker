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

/*
 * Called by "check/index.php" and "check/pre_next_checks_edit.php
 * 
 * Accept parameters:
 * 
 * check_row: only need when edit existing user.
 * all_html_tags: display selections in dropdown list box "HTML Tag"
 */

global $onload;
$onload = "initial();";

if (!isset($this->check_row))
{
	$onload .= "disableDiv('div_pre_next_checks');";
}

require(AC_INCLUDE_PATH.'header.inc.php'); 
?>

<h2 align="center"><?php echo _AC("create_edit_check"); ?></h2>
<form method="post" action="<?php $id_str = ''; if (isset($_GET['id'])) $id_str='?id='.$_GET['id']; echo $_SERVER['PHP_SELF'].$id_str; ?>" name="input_form">

<div class="input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AC('create_edit_check'); ?></legend>

<?php if (isset($this->check_row)) { // save raw information ?>
	<input type="hidden" name="html_tag_orig" value="<?php echo htmlspecialchars($this->check_row['html_tag']); ?>" />
	<input type="hidden" name="confidence_orig" value="<?php echo $this->check_row['confidence']; ?>" />
	<input type="hidden" name="open_to_public_orig" value="<?php echo $this->check_row['open_to_public']; ?>" />
	<input type="hidden" name="name_orig" value="<?php echo htmlspecialchars(_AC($this->check_row['name'])); ?>" />
	<input type="hidden" name="err_orig" value="<?php echo htmlspecialchars(_AC($this->check_row['err'])); ?>" />
	<input type="hidden" name="description_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["description"])); ?>" />
	<input type="hidden" name="search_str_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["search_str"])); ?>" />
	<input type="hidden" name="long_description_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["long_description"])); ?>" />
	<input type="hidden" name="rationale_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["rationale"])); ?>" />
	<input type="hidden" name="how_to_repair_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["how_to_repair"])); ?>" />
	<input type="hidden" name="repair_example_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["repair_example"])); ?>" />
	<input type="hidden" name="note_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["note"])); ?>" />
	<input type="hidden" name="question_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["question"])); ?>" />
	<input type="hidden" name="decision_pass_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["decision_pass"])); ?>" />
	<input type="hidden" name="decision_fail_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["decision_fail"])); ?>" />
	<input type="hidden" name="test_procedure_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["test_procedure"])); ?>" />
	<input type="hidden" name="test_expected_result_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["test_expected_result"])); ?>" />
	<input type="hidden" name="test_failed_result_orig" value="<?php echo htmlspecialchars(_AC($this->check_row["test_failed_result"])); ?>" />
	<input type="hidden" name="pass_example_desc_orig" value="<?php echo htmlspecialchars($this->check_example_row['pass_example_desc']); ?>" />
	<input type="hidden" name="pass_example_orig" value="<?php echo htmlspecialchars($this->check_example_row['pass_example']); ?>" />
	<input type="hidden" name="fail_example_desc_orig" value="<?php echo htmlspecialchars($this->check_example_row['fail_example_desc']); ?>" />
	<input type="hidden" name="fail_example_orig" value="<?php echo htmlspecialchars($this->check_example_row['fail_example']); ?>" />
<?php }?>
	
	<table class="form-data">
		<tr>
			<td colspan="2" align="left"><?php echo _AC('required_field_text') ;?><br /><br/></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="html_tag"><?php echo _AC('html_tag'); ?></label>:</th>
			<td align="left">
				<input name="html_tag" id="html_tag" value="<?php if (isset($_POST['html_tag'])) echo htmlspecialchars($_POST['html_tag']); else echo htmlspecialchars($this->check_row['html_tag']); ?>" />
				<a href="<?php echo AC_BASE_HREF; ?>check/html_tag_list.php" onclick="popup('<?php echo AC_BASE_HREF; ?>check/html_tag_list.php'); return false;" title="<?php echo _AC('select_from_tag_list'); ?>"><?php echo _AC('select_from_tag_list'); ?></a>
			</td>
		</tr>

		<tr>
			<td align="left" colspan="2">
				<small>&middot; <?php echo _AC('html_tag_text'); ?></small>
			</td>
		</tr>
		
		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="confidence"><?php echo _AC('error_type'); ?></label>:</th>
			<td align="left">
			<select name="confidence" id="confidence">
				<option value="-1">- <?php echo _AC('select'); ?> -</option>
				<option value="<?php echo KNOWN; ?>" <?php if ((isset($_POST['confidence']) && $_POST['confidence']==KNOWN) || (!isset($_POST['confidence']) && isset($this->check_row['confidence']) && $this->check_row['confidence'] == KNOWN)) echo 'selected="selected"'; ?>><?php echo _AC('known_problem'); ?></option>
				<option value="<?php echo LIKELY; ?>" <?php if ((isset($_POST['confidence']) && $_POST['confidence']==LIKELY) || (!isset($_POST['confidence']) && isset($this->check_row['confidence']) && $this->check_row['confidence'] == LIKELY)) echo 'selected="selected"'; ?>><?php echo _AC('likely_problem'); ?></option>
				<option value="<?php echo POTENTIAL; ?>" <?php if ((isset($_POST['confidence']) && $_POST['confidence']==POTENTIAL) || (!isset($_POST['confidence']) && isset($this->check_row['confidence']) && $this->check_row['confidence'] == POTENTIAL)) echo 'selected="selected"'; ?>><?php echo _AC('potential_problem'); ?></option>
			</select>
			</td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><?php echo _AC("open_to_public"); ?>:</th>
			<td align="left">
				<input type="radio" name="open_to_public" id="open_to_publicN" value="0" <?php if ((isset($_POST['open_to_public']) && $_POST['open_to_public']==0) || (!isset($_POST['open_to_public']) && $this->check_row['open_to_public']==0)) echo 'checked="checked"'; ?> /><label for="open_to_publicN"><?php echo _AC('no'); ?></label> 
				<input type="radio" name="open_to_public" id="open_to_publicY" value="1" <?php if ((isset($_POST['open_to_public']) && $_POST['open_to_public']==1) || (!isset($_POST['open_to_public']) && $this->check_row['open_to_public']==1)) echo 'checked="checked"'; ?> /><label for="open_to_publicY"><?php echo _AC('yes'); ?></label>
			</td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="name"><?php echo _AC('name'); ?></label>:</th>
			<td align="left"><input id="name" name="name" type="text" size="100" value="<?php if (isset($_POST['name'])) echo $_POST['name']; else echo htmlspecialchars(_AC($this->check_row['name'])); ?>" /></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="err"><?php echo _AC('error'); ?></label>:</th>
			<td align="left"><input id="err" name="err" type="text" size="100" value="<?php if (isset($_POST['err'])) echo $_POST['err']; else echo htmlspecialchars(_AC($this->check_row['err'])); ?>" /></td>
		</tr>

		<?php if (isset($this->check_row)) {?>
		<tr>
			<th align="left"><? echo _AC("guidelines"); ?></th>
			<td align="left">
			<?php if (is_array($this->guideline_rows)) {?> 
			<?php 	foreach ($this->guideline_rows as $guideline) {?>
					<a title="<?php echo $guideline['title']._AC('link_open_in_new'); ?>" target="_new" href="<?php echo AC_BASE_HREF; ?>guideline/view_guideline.php?id=<?php echo $guideline['guideline_id']; ?>"><?php echo $guideline["title"]; ?></a>&nbsp;&nbsp;
			<?php   } // end of foreach?>
			<?php } else { echo _AC('none_found'); }?>
			</td>
		</tr>
		<?php }?>
		
		<tr>
			<th align="left"><label for="short_desc"><?php echo _AC('short_desc'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="description" id="short_desc"><?php if (isset($_POST['description'])) echo htmlspecialchars($_POST['description']); else echo htmlspecialchars(_AC($this->check_row["description"])); ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="search_str"><?php echo _AC('search_str'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="search_str" id="search_str"><?php if (isset($_POST['search_str'])) echo htmlspecialchars($_POST['search_str']); else echo htmlspecialchars(_AC($this->check_row["search_str"])); ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="long_desc"><?php echo _AC('long_desc'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="long_description" id="long_desc"><?php if (isset($_POST['long_description'])) echo htmlspecialchars($_POST['long_description']); else echo htmlspecialchars(_AC($this->check_row["long_description"])); ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="rationale"><?php echo _AC('rationale'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="rationale" id="rationale"><?php if (isset($_POST['rationale'])) echo htmlspecialchars($_POST['rationale']); else echo htmlspecialchars(_AC($this->check_row["rationale"])); ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="how_to_repair"><?php echo _AC('how_to_repair'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="how_to_repair" id="how_to_repair"><?php if (isset($_POST['how_to_repair'])) echo htmlspecialchars($_POST['how_to_repair']); else echo htmlspecialchars(_AC($this->check_row["how_to_repair"])); ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="repair_example"><?php echo _AC('repair_example'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="repair_example" id="repair_example"><?php if (isset($_POST['repair_example'])) echo htmlspecialchars($_POST['repair_example']); else echo htmlspecialchars(_AC($this->check_row["repair_example"])); ?></textarea></td>
		</tr>
		
		<tr>
			<th align="left"><label for="note"><?php echo _AC('note'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="note" id="note"><?php if (isset($_POST['note'])) echo htmlspecialchars($_POST['note']); else echo htmlspecialchars(_AC($this->check_row["note"])); ?></textarea></td>
		</tr>
		
		<tr>
			<td align="left" colspan="2"><h3><?php echo _AC('how_to_determine');?></h3></td>
		</tr>

		<tr>
			<th align="left"><label for="question"><?php echo _AC('question'); ?></label>:</th>
			<td align="left">
				<input name="question" id="question" size="100" value="<?php if (isset($_POST['question'])) echo htmlspecialchars($_POST['question']); else echo htmlspecialchars(_AC($this->check_row['question'])); ?>" />
			</td>
		</tr>

		<tr>
			<th align="left"><label for="decision_pass"><?php echo _AC('decision_pass'); ?></label>:</th>
			<td align="left">
				<input name="decision_pass" id="decision_pass" size="100" value="<?php if (isset($_POST['decision_pass'])) echo htmlspecialchars($_POST['decision_pass']); else echo htmlspecialchars(_AC($this->check_row['decision_pass'])); ?>" />
			</td>
		</tr>

		<tr>
			<th align="left"><label for="decision_fail"><?php echo _AC('decision_fail'); ?></label>:</th>
			<td align="left">
				<input name="decision_fail" id="decision_fail" size="100" value="<?php if (isset($_POST['decision_fail'])) echo htmlspecialchars($_POST['decision_fail']); else echo htmlspecialchars(_AC($this->check_row['decision_fail'])); ?>" />
			</td>
		</tr>

		<tr>
			<td align="left" colspan="2"><h3><?php echo _AC('steps_to_check');?></h3></td>
		</tr>

		<tr>
			<th align="left"><label for="test_procedure"><?php echo _AC('procedure'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="test_procedure" id="test_procedure"><?php if (isset($_POST['test_procedure'])) echo htmlspecialchars($_POST['test_procedure']); else echo htmlspecialchars(_AC($this->check_row["test_procedure"])); ?></textarea></td>
		</tr>
		
		<tr>
			<th align="left"><label for="test_expected_result"><?php echo _AC('expected_result'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="test_expected_result" id="test_expected_result"><?php if (isset($_POST['test_expected_result'])) echo htmlspecialchars($_POST['test_expected_result']); else echo htmlspecialchars(_AC($this->check_row["test_expected_result"])); ?></textarea></td>
		</tr>
		
		<tr>
			<th align="left"><label for="test_failed_result"><?php echo _AC('failed_result'); ?></label>:</th>
			<td align="left"><textarea rows="3" cols="50" name="test_failed_result" id="test_failed_result"><?php if (isset($_POST['test_failed_result'])) echo htmlspecialchars($_POST['test_failed_result']); else echo htmlspecialchars(_AC($this->check_row["test_failed_result"])); ?></textarea></td>
		</tr>
		
		<tr>
			<th align="left"><label for="pass_example_desc"><?php echo _AC('pass_example_desc'); ?></label>:</th>
			<td align="left"><input id="pass_example_desc" name="pass_example_desc" type="text" size="100" value="<?php if (isset($_POST['pass_example_desc'])) echo htmlspecialchars($_POST['pass_example_desc']); else echo htmlspecialchars($this->check_example_row['pass_example_desc']); ?>" /></td>
		</tr>
		
		<tr>
			<th align="left"><label for="pass_example"><?php echo _AC('pass_example'); ?></label>:</th>
			<td align="left"><textarea rows="5" cols="50" name="pass_example" id="pass_example"><?php if (isset($_POST['pass_example'])) echo htmlspecialchars($_POST['pass_example']); else echo htmlspecialchars($this->check_example_row["pass_example"]); ?></textarea></td>
		</tr>
		
		<tr>
			<th align="left"><label for="fail_example_desc"><?php echo _AC('fail_example_desc'); ?></label>:</th>
			<td align="left"><input id="fail_example_desc" name="fail_example_desc" type="text" size="100" value="<?php if (isset($_POST['fail_example_desc'])) echo htmlspecialchars($_POST['fail_example_desc']); else echo htmlspecialchars($this->check_example_row['fail_example_desc']); ?>" /></td>
		</tr>
		
		<tr>
			<th align="left"><label for="fail_example"><?php echo _AC('fail_example'); ?></label>:</th>
			<td align="left"><textarea rows="5" cols="50" name="fail_example" id="fail_example"><?php if (isset($_POST['pass_example'])) echo htmlspecialchars($_POST['fail_example']); else echo htmlspecialchars($this->check_example_row["fail_example"]); ?></textarea></td>
		</tr>
		
		<?php if (isset($this->author)) {?>
		<tr>
			<th align="left"><?php echo _AC("author"); ?>:</th>
			<td align="left"><?php echo $this->author; ?></td>
		</tr>
		<tr>
			<th align="left"><?php echo _AC("date_created"); ?>:</th>
			<td align="left"><?php echo $this->check_row['create_date']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<br/>
	<span style="font-weight: bold">&middot; <?php echo _AC('note_edit_pre_next_checks'); ?></span><br/><br/>
	
	<div id="div_pre_next_checks">
	<h2>
		<?php echo _AC('pre_checks');?>
		<input type="button" name="add_pre_checks" value="<?php echo _AC('add_pre_checks'); ?>" 
		       onclick="check_unsaved_info(); popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=pre&amp;cid=<?php echo $_GET['id']; ?>'); return false;" />
	</h2><br/>
	<table class="data" summary="" rules="rows" >
		<thead>
		<tr>
			<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all_del_pre" title="<?php echo _AC('select_all'); ?>" name="selectall_delprechecks" onclick="CheckAll('del_pre_checks_id[]','selectall_delprechecks');" /></th>
			<th align="center"><?php echo _AC('html_tag'); ?></th>
			<th align="center"><?php echo _AC('error_type'); ?></th>
			<th align="center"><?php echo _AC('description'); ?></th>
			<th align="center"><?php echo _AC('check_id'); ?></th>
		</tr>
		</thead>
	<?php if (is_array($this->pre_rows)) { ?>
			
		<tfoot>
			<tr>
				<td colspan="5">
					<input type="submit" name="remove_pre" value="<?php echo _AC('remove'); ?>" />
				</td>
			</tr>
		</tfoot>

		<tbody>
	<?php foreach ($this->pre_rows as $pre_row) { ?>
		<tr onmousedown="document.input_form['del_pre_checks_<?php echo $pre_row['check_id']; ?>'].checked = !document.input_form['del_pre_checks_<?php echo $pre_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_pre_checks_<?php echo $pre_row['check_id']; ?>');" id="rdel_pre_checks_<?php echo $pre_row['check_id']; ?>"
		    onkeydown="document.input_form['del_pre_checks_<?php echo $pre_row['check_id']; ?>'].checked = !document.input_form['del_pre_checks_<?php echo $pre_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_pre_checks_<?php echo $pre_row['check_id']; ?>');">
			<td><input type="checkbox" name="del_pre_checks_id[]" value="<?php echo $pre_row['check_id']; ?>" id="del_pre_checks_<?php echo $pre_row['check_id']; ?>" 
			           title="del_pre_checks_<?php echo $pre_row['check_id']; ?>" onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" 
			           <?php if (is_array($_POST['del_pre_checks_id']) && in_array($pre_row['check_id'], $_POST['del_pre_checks_id'])) echo 'checked="checked"';?> /></td>
			<td><?php echo $pre_row['html_tag']; ?></td>
			<td><?php echo get_confidence_by_code($pre_row['confidence']); ?></td>
			<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $pre_row["check_id"]; ?>" onclick="popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $pre_row["check_id"]; ?>'); return false;"><?php echo _AC($pre_row['name']); ?></a></span></td>
			<td><?php echo $next_row['check_id']; ?></td>
		</tr>
	<?php } // end of foreach?>
	<?php } else {// end of if?>
		<tbody>
		<tr><td colspan="5"><?php echo _AC('none_found'); ?></td></tr>
	<?php }?>
		</tbody>
	</table>

	<!-- section of displaying existing next checks -->
	<br/>
	<h2>
		<?php echo _AC('next_checks');?>
		<input type="button" name="add_next_checks" value="<?php echo _AC('add_next_checks'); ?>" 
		       onclick="check_unsaved_info(); popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=next&amp;cid=<?php echo $_GET['id']; ?>'); return false;" />
	</h2><br/>
	<table class="data" summary="" rules="rows" >
		<thead>
		<tr>
			<th align="left"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all_del_next" title="<?php echo _AC('select_all'); ?>" name="selectall_delnextchecks" onclick="CheckAll('del_next_checks_id[]','selectall_delnextchecks');" /></th>
			<th align="center"><?php echo _AC('html_tag'); ?></th>
			<th align="center"><?php echo _AC('error_type'); ?></th>
			<th align="center"><?php echo _AC('description'); ?></th>
			<th align="center"><?php echo _AC('check_id'); ?></th>
		</tr>
		</thead>
	<?php if (is_array($this->next_rows)) { ?>
			
		<tfoot>
			<tr>
				<td colspan="4">
					<input type="submit" name="remove_next" value="<?php echo _AC('remove'); ?>" />
				</td>
			</tr>
		</tfoot>

		<tbody>
	<?php foreach ($this->next_rows as $next_row) { ?>
		<tr onmousedown="document.input_form['del_next_checks_<?php echo $next_row['check_id']; ?>'].checked = !document.input_form['del_next_checks_<?php echo $next_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_next_checks_<?php echo $next_row['check_id']; ?>');" id="rdel_next_checks_<?php echo $next_row['check_id']; ?>"
		    onkeydown="document.input_form['del_next_checks_<?php echo $next_row['check_id']; ?>'].checked = !document.input_form['del_next_checks_<?php echo $next_row['check_id']; ?>'].checked; togglerowhighlight(this, 'del_next_checks_<?php echo $next_row['check_id']; ?>');">
			<td><input type="checkbox" name="del_next_checks_id[]" value="<?php echo $next_row['check_id']; ?>" id="del_next_checks_<?php echo $next_row['check_id']; ?>" 
			           title="del_next_checks_<?php echo $next_row['check_id']; ?>" onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" 
			           <?php if (is_array($_POST['del_next_checks_id']) && in_array($next_row['check_id'], $_POST['del_next_checks_id'])) echo 'checked="checked"';?> /></td>
			<td><?php echo $next_row['html_tag']; ?></td>
			<td><?php echo get_confidence_by_code($next_row['confidence']); ?></td>
			<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $next_row["check_id"]; ?>" onclick="popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $next_row["check_id"]; ?>'); return false;"><?php echo _AC($next_row['name']); ?></a></span></td>
			<td><?php echo $next_row['check_id']; ?></td>
		</tr>
	<?php } // end of foreach?>
	<?php } else {// end of if?>
		<tbody>
		<tr><td colspan="4"><?php echo _AC('none_found'); ?></td></tr>
	<?php }?>
		</tbody>
	</table>
	</div>
	<br/>

	<div class="row">
		<input type="submit" name="save_no_close" value="<?php echo _AC('save'); ?>" class="submit" /> 
		<input type="submit" name="save_and_close" value="<?php echo _AC('save_and_close'); ?>" class="submit" /> 
		<input type="submit" name="cancel" value=" <?php echo _AC('cancel'); ?> "  class="submit" />
		<input type="hidden" name="javascript_submit" value="0" />
	</div>
</fieldset>

</div>
</form>

<script type="text/JavaScript">
//<!--
function initial()
{
	// set cursor focus
	document.input_form.html_tag.focus();
}

cDivs = new Array();

function disableDiv(divID)
{
	d = document.getElementsByTagName("BODY")[0];

	e = document.getElementById(divID);

    xPos = e.offsetLeft;
    yPos = e.offsetTop;
    oWidth = e.offsetWidth;    
    oHeight = e.offsetHeight;
    cDivs[cDivs.length] = document.createElement("DIV");
    cDivs[cDivs.length-1].style.width = oWidth+"px";
    cDivs[cDivs.length-1].style.height = oHeight+"px";
    cDivs[cDivs.length-1].style.position = "absolute";
    cDivs[cDivs.length-1].style.left = xPos+"px";
    cDivs[cDivs.length-1].style.top = yPos+"px";
    cDivs[cDivs.length-1].style.backgroundColor = "#999999";
    cDivs[cDivs.length-1].style.opacity = .6;
    cDivs[cDivs.length-1].style.filter = "alpha(opacity=60)";
    d.appendChild(cDivs[cDivs.length-1]);
}

function CheckAll(element_name, selectall_checkbox_name) {
	for (var i=0;i<document.input_form.elements.length;i++)	{
		var e = document.input_form.elements[i];
		if ((e.name == element_name) && (e.type=='checkbox')) {
			e.checked = document.input_form[selectall_checkbox_name].checked;
			togglerowhighlight(document.getElementById("r" + e.id), e.id);
		}
	}
}

function togglerowhighlight(obj, boxid) {
	if (document.getElementById(boxid).checked) {
		obj.className = 'selected';
	} else {
		obj.className = '';
	}
}

function check_unsaved_info() {
	var has_unsaved_info = false;
	
	if (document.input_form.html_tag.value != document.input_form.html_tag_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.confidence.value != document.input_form.confidence_orig.value) {
		has_unsaved_info = true;
	}
	else if (getValue(document.input_form.open_to_public) != document.input_form.open_to_public_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.name.value != document.input_form.name_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.err.value != document.input_form.err_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.description.value != document.input_form.description_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.search_str.value != document.input_form.search_str_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.long_description.value != document.input_form.long_description_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.rationale.value != document.input_form.rationale_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.how_to_repair.value != document.input_form.how_to_repair_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.repair_example.value != document.input_form.repair_example_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.note.value != document.input_form.note_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.question.value != document.input_form.question_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.decision_pass.value != document.input_form.decision_pass_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.decision_fail.value != document.input_form.decision_fail_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.test_procedure.value != document.input_form.test_procedure_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.test_expected_result.value != document.input_form.test_expected_result_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.test_failed_result.value != document.input_form.test_failed_result_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.pass_example_desc.value != document.input_form.pass_example_desc_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.pass_example.value != document.input_form.pass_example_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.fail_example_desc.value != document.input_form.fail_example_desc_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.fail_example.value != document.input_form.fail_example_orig.value) {
		has_unsaved_info = true;
	}

	if (has_unsaved_info)
	{
		var answer = confirm("<?php echo _AC('has_unsaved_info'); ?>")
		if (answer){
			document.input_form.javascript_submit.value = 1;
			document.input_form.submit();
		}
	}
}

function getValue(Obj) {
	if(!Obj)
		return "";

	var radioLength = Obj.length;
	if(radioLength == undefined)
		if(Obj.checked)
			return Obj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(Obj[i].checked) {
			return Obj[i].value;
		}
	}

	return "";
}

//  End -->
//-->
</script>

<?php require(AC_INCLUDE_PATH.'footer.inc.php'); ?>