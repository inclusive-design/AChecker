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

include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');

/**
 * Display checks in the given $checks_array in html table with 'remove' button at the bottom
 * @param $checks_array : array of all checks to display
 * @param $prefix: indicates where the checks belong to: guideline, guideline group or guideline subgroup.
 *                 'g_[guidelineID] for guideline checks
 *                 'gg_[groupID] for guideline group checks
 *                 'gsg_[subgroupID] for guideline subgroup checks
 * @return a html table to display all checks in $checks_array 
 */
function dispaly_check_table($checks_array, $prefix)
{
	if (is_array($checks_array)){ 
?>
<form name="input_form_<?php echo $prefix; ?>" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?><?php if (isset($_GET["id"])) echo '?id='.$_GET["id"]; ?>" >
	<table class="data" rules="rows" >
		<thead>
		<tr>
			<th align="left" width="10%"><input type="checkbox" value="<?php echo _AC('select_all'); ?>" id="all_del_<?php echo $prefix;?>" title="<?php echo _AC('select_all'); ?>" name="selectall_delchecks_<?php echo $prefix;?>" onclick="CheckAll('del_checks_id_<?php echo $prefix; ?>[]','selectall_delchecks_<?php echo $prefix;?>');" /></th>
			<th align="left" width="20%"><?php echo _AC('html_tag'); ?></th>
			<th align="left" width="20%"><?php echo _AC('error_type'); ?></th>
			<th align="left" width="40%"><?php echo _AC('description'); ?></th>
			<th align="left" width="10%"><?php echo _AC('check_id'); ?></th>
		</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="5">
					<input type="submit" name="remove" value="<?php echo _AC('remove'); ?>" onclick="javascript: return get_confirm();" />
				</td>
			</tr>
		</tfoot>

		<tbody>
<?php foreach ($checks_array as $check_row) { ?>
		<tr onmousedown="document.getElementById('del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>').checked = !document.getElementById('del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>').checked; togglerowhighlight(this, 'del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>');" 
		    onkeydown="document.getElementById('del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>').checked = !document.getElementById('del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>').checked; togglerowhighlight(this, 'del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>');"
		    id="rdel_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>">
			<td><input type="checkbox" name="del_checks_id_<?php echo $prefix;?>[]" value="<?php echo $prefix.'_'.$check_row['check_id']; ?>" id="del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>" 
			           onmouseup="this.checked=!this.checked" onkeyup="this.checked=!this.checked" /></td>
			<td><?php echo htmlspecialchars($check_row['html_tag']); ?></td>
			<td><?php echo get_confidence_by_code($check_row['confidence']); ?></td>
			<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $check_row["check_id"]; ?>" onclick="AChecker.popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $check_row["check_id"]; ?>'); return false;"><label for="del_checks_<?php echo $prefix.'_'.$check_row['check_id']; ?>"><?php echo htmlspecialchars(_AC($check_row['name'])); ?></label></a></span></td>
			<td><?php echo $check_row['check_id']; ?></td>
		</tr>
<?php } // end of foreach?>
		</tbody>
	</table>
	<br/>
</form>
<?php } // end of if
}

global $onload;
$onload = "initial();";

$gid = $this->gid;

$guidelineGroupsDAO = new GuidelineGroupsDAO();
$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();

include(AC_INCLUDE_PATH.'header.inc.php');
?>
<script type='text/javascript' src='jscripts/calendar.js'></script>

<div class="input-form">

<fieldset class="group_form"><legend class="group_form"><?php echo _AC('create_edit_guideline'); ?></legend>

<form name="input_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?><?php if (isset($_GET["id"])) echo '?id='.$_GET["id"]; ?>" >
<?php if (isset($this->row["user_id"])) {?>
<input type="hidden" name="user_id" value="<?php echo $this->row["user_id"]; ?>" />
<?php }?>

<?php if (isset($this->row)) {?>
	<input type="hidden" name="title_orig" value="<?php echo htmlspecialchars($this->row['title']); ?>" />
	<input type="hidden" name="abbr_orig" value="<?php echo htmlspecialchars($this->row['abbr']); ?>" />
	<input type="hidden" name="long_name_orig" value="<?php echo htmlentities(_AC($this->row['long_name'])); ?>" />
	<input type="hidden" name="published_date_orig" value="<?php echo $this->row['published_date']; ?>" />
	<input type="hidden" name="earlid_orig" value="<?php echo htmlspecialchars($this->row['earlid']); ?>" />
	<input type="hidden" name="status_orig" value="<?php echo $this->row['status']; ?>" />
	<input type="hidden" name="open_to_public_orig" value="<?php echo $this->row['open_to_public']; ?>" />
<?php }?>

	<table class="form-data">
		<tr>
			<td colspan="2" align="left"><?php echo _AC('required_field_text') ;?><br /><br /></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="title"><?php echo _AC('title'); ?></label></th>
			<td><input type="text" name="title" size="70" id="title" value="<?php if (isset($_POST['title'])) echo htmlspecialchars($_POST['title']); else echo htmlspecialchars($this->row["title"]); ?>" /></td>
		</tr>

		<tr>
			<th align="left"><div class="required" title="<?php echo _AC('required_field'); ?>">*</div><label for="abbr"><?php echo _AC('abbr'); ?></label></th>
			<td><input type="text" name="abbr" size="70" id="abbr" value="<?php if (isset($_POST['abbr'])) echo htmlspecialchars($_POST['abbr']); else echo htmlspecialchars($this->row["abbr"]); ?>" /></td>
		</tr>

		<?php if ($this->is_admin) {?>
		<tr>
			<th align="left"><? echo _AC("author"); ?></th>
			<td><?php echo $this->author; ?></td>
		</tr>
		<?php } ?>

		<tr>
			<th align="left"><label for="long_name"><?php echo _AC('long_name'); ?></label></th>
			<td><textarea cols="10" rows="3" name="long_name" id="long_name"><?php if (isset($_POST['long_name'])) echo htmlspecialchars($_POST['long_name']); else echo htmlspecialchars(_AC($this->row["long_name"])); ?></textarea></td>
		</tr>

		<tr>
			<th align="left"><label for="published_date"><?php echo _AC('published_date').'<br />('._AC("yyyy-mm-dd").')'; ?></label></th>
			<td>
				<input type="text" name="published_date" id="published_date" value="<?php if (isset($_POST['published_date'])) echo $_POST['published_date']; else echo $this->row["published_date"]; ?>" />
				<img src="images/calendar.gif" alt="<?php echo _AC('calendar'); ?>" style="vertical-align: middle; cursor: pointer;" onclick="scwShow(scwID('published_date'),event);" />
			</td>
		</tr>

		<tr>
			<th align="left"><label for="earlid"><?php echo _AC('earlid'); ?></label></th>
			<td><input type="text" name="earlid" size="70" id="earlid" value="<?php if (isset($_POST['earlid'])) echo htmlspecialchars($_POST['earlid']); else echo htmlspecialchars($this->row["earlid"]); ?>" /></td>
		</tr>

		<tr>
			<th align="left"><?php echo _AC("status"); ?></th>
			<td>
				<input type="radio" name="status" id="statusD" value="0" <?php if ((isset($_POST['status']) && $_POST['status']==0) || (!isset($_POST['status']) && $this->row['status']==0)) echo 'checked="checked"'; ?> /><label for="statusD"><?php echo _AC('disabled'); ?></label> 
				<input type="radio" name="status" id="statusE" value="1" <?php if ((isset($_POST['status']) && $_POST['status']==1) || (!isset($_POST['status']) && $this->row['status']==1)) echo 'checked="checked"'; ?> /><label for="statusE"><?php echo _AC('enabled'); ?></label>
			</td>
		</tr>
			
		<?php if ($this->is_admin) {?>
		<tr>
			<th align="left"><?php echo _AC("open_to_public"); ?></th>
			<td>
				<input type="radio" name="open_to_public" id="open_to_publicN" value="0" <?php if ((isset($_POST['open_to_public']) && $_POST['open_to_public']==0) || (!isset($_POST['open_to_public']) && $this->row['open_to_public']==0)) echo 'checked="checked"'; ?> /><label for="open_to_publicN"><?php echo _AC('no'); ?></label> 
				<input type="radio" name="open_to_public" id="open_to_publicY" value="1" <?php if ((isset($_POST['open_to_public']) && $_POST['open_to_public']==1) || (!isset($_POST['open_to_public']) && $this->row['open_to_public']==1)) echo 'checked="checked"'; ?> /><label for="open_to_publicY"><?php echo _AC('yes'); ?></label>
			</td>
		</tr>
		<?php } else {?>
		<tr>
			<td><input type="hidden" name="open_to_public" value="<?php if (isset($this->row["open_to_public"])) echo $this->row["open_to_public"]; else echo "0"; ?>" /></td>
		</tr>
		<?php }?>
	</table>

	<div>
		<input type="submit" name="save_no_close" value="<?php echo _AC('save'); ?>" class="submit" /> 
		<input type="submit" name="save_and_close" value="<?php echo _AC('save_and_close'); ?>" class="submit" /> 
		<input type="submit" name="cancel" value="<?php echo _AC('cancel'); ?>" />
		<input type="hidden" name="javascript_submit" value="0" />
	</div>
	<br/>
</form>

<?php if (isset($this->row)) {?>
	<h2>
		<?php echo _AC('checks');?>
		<a href="<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=add&amp;gid=<?php echo $this->gid; ?>'); return false;" 
		       title="<?php echo _AC("add_group");?>"
		       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=add&amp;gid=<?php echo $this->gid; ?>'); return false;" >
		<img alt="<?php echo _AC("add_group");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/add_group.gif" />
		</a>
		<a href="<?php echo AC_BASE_HREF; ?>check/index.php?list=guideline&amp;gid=<?php echo $this->gid; ?>'); return false;" 
		       title="<?php echo _AC("add_checks_into_guideline");?>"
		       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=guideline&amp;gid=<?php echo $this->gid; ?>'); return false;" >
		<img alt="<?php echo _AC("add_checks_into_guideline");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/add.gif" />
		</a>
	</h2><br/>
<?php 
	// display guideline level checks
	$guidelineLevel_checks = $this->checksDAO->getGuidelineLevelChecks($gid);
	
	if (is_array($guidelineLevel_checks))
	{
		$num_of_checks += count($guidelineLevel_checks);
		dispaly_check_table($guidelineLevel_checks, 'g_'.$gid);
	}
	
	// display named guidelines and their checks 
	$named_groups = $guidelineGroupsDAO->getNamedGroupsByGuidelineID($gid);
	if (is_array($named_groups))
	{
		foreach ($named_groups as $group)
		{
	?>
		<h3>
			<?php echo _AC($group['name']);?>
			<a href="<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=add&amp;ggid=<?php echo $group['group_id']; ?>" 
			       title="<?php echo _AC('add_subgroup'); ?>" 
			       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=add&amp;ggid=<?php echo $group['group_id']; ?>'); return false;" >
			<img alt="<?php echo _AC("add_subgroup");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/add_group.gif" />
			</a>
			<a href="<?php echo AC_BASE_HREF; ?>check/index.php?list=group&amp;ggid=<?php echo $group['group_id']; ?>" 
			       title="<?php echo _AC('add_checks_into_group'); ?>" 
			       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=group&amp;ggid=<?php echo $group['group_id']; ?>'); return false;" >
			<img alt="<?php echo _AC("add_checks_into_group");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/add.gif" />
			</a>
			<a href="<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=edit&amp;ggid=<?php echo $group['group_id']; ?>" 
			       title="<?php echo _AC('edit_group_name'); ?>" 
			       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=edit&amp;ggid=<?php echo $group['group_id']; ?>'); return false;" >
			<img alt="<?php echo _AC("edit_group_name");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/edit.gif" />
			</a>
			<a href="<?php echo AC_BASE_HREF; ?>guideline/create_edit_guideline.php?id=<?php echo $gid?>" 
			       title="<?php echo _AC('del_this_group'); ?>" 
			       onclick="return del('gg', <?php echo $group['group_id']; ?>)" >
			<img alt="<?php echo _AC("del_this_group");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/delete.gif" />
			</a>
		</h3><br/>
	<?php
			// get group level checks: the checks in subgroups without subgroup names
			$groupLevel_checks = $this->checksDAO->getGroupLevelChecks($group['group_id']);
			if (is_array($groupLevel_checks))
			{
				$num_of_checks += count($groupLevel_checks);
				dispaly_check_table($groupLevel_checks, 'gg_'.$group['group_id']);
			}
			
			// display named subgroups and their checks
			$named_subgroups = $guidelineSubgroupsDAO->getNamedSubgroupByGroupID($group['group_id']);
			if (is_array($named_subgroups))
			{
				foreach ($named_subgroups as $subgroup)
				{
	?>
		<h4>
			<?php echo _AC($subgroup['name']);?>
			<a href="<?php echo AC_BASE_HREF; ?>check/index.php?list=subgroup&amp;gsgid=<?php echo $subgroup['subgroup_id']; ?>" 
			       title="<?php echo _AC('add_checks_into_subgroup'); ?>" 
			       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>check/index.php?list=subgroup&amp;gsgid=<?php echo $subgroup['subgroup_id']; ?>'); return false;" >
			<img alt="<?php echo _AC("add_checks_into_subgroup");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/add.gif" />
			</a>
			<a href="<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=edit&amp;gsgid=<?php echo $subgroup['subgroup_id']; ?>" 
			       title="<?php echo _AC('edit_subgroup_name'); ?>" 
			       onclick="check_unsaved_info(); AChecker.popup('<?php echo AC_BASE_HREF; ?>guideline/add_edit_group.php?action=edit&amp;gsgid=<?php echo $subgroup['subgroup_id']; ?>'); return false;" >
			<img alt="<?php echo _AC("edit_subgroup_name");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/edit.gif" />
			</a>
			<a href="<?php echo AC_BASE_HREF; ?>guideline/create_edit_guideline.php?id=<?php echo $gid?>" 
			       title="<?php echo _AC('del_this_subgroup'); ?>" 
			       onclick="return del('gsg', <?php echo $subgroup['subgroup_id']; ?>)" >
			<img alt="<?php echo _AC("del_this_subgroup");?>" src="themes/<?php echo $_SESSION['prefs']['PREF_THEME']; ?>/images/delete.gif" />
			</a>
		</h4><br/>
	<?php 
					$subgroup_checks = $this->checksDAO->getChecksBySubgroupID($subgroup['subgroup_id']);
					if (is_array($subgroup_checks))
					{
						$num_of_checks += count($subgroup_checks);
						dispaly_check_table($subgroup_checks, 'gsg_'.$subgroup['subgroup_id']);
					}
				} // end of foreach $named_subgroups
			} // end of if $named_subgroups
		} // end of foreach $named_groups 	
	} // end of if $named_groups
	
	// display "none found" if no check is defined in this guideline
	if ($num_of_checks == 0) echo _AC('none_found');
} // end of if (isset($this->row))
?>
</fieldset>
</div>

<script type="text/JavaScript">
//<!--

function initial()
{
	// set cursor focus
	document.input_form.title.focus();
}

function CheckAll(element_name, selectall_checkbox_name) {
	for (var j=0; j<document.forms.length;j++) {
		for (var i=0;i<document.forms[j].elements.length;i++)	{
			var e = document.forms[j].elements[i];
			if ((e.name == element_name) && (e.type=='checkbox')) {
				e.checked = document.forms[j][selectall_checkbox_name].checked;
				togglerowhighlight(document.getElementById("r" + e.id), e.id);
			}
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

	if (document.input_form.title.value != document.input_form.title_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.abbr.value != document.input_form.abbr_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.long_name.value != document.input_form.long_name_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.published_date.value != document.input_form.published_date_orig.value) {
		has_unsaved_info = true;
	}
	else if (document.input_form.earlid.value != document.input_form.earlid_orig.value) {
		has_unsaved_info = true;
	}
	else if (getValue(document.input_form.status) != document.input_form.status_orig.value) {
		has_unsaved_info = true;
	}
	else if (getValue(document.input_form.open_to_public) != document.input_form.open_to_public_orig.value) {
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

	if (Obj.type == 'hidden')
		return Obj.value;
	else {
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
	}
	return "";
}

function del(type, id) {
	check_unsaved_info();

	if (get_confirm()) {
		location.href="<?php echo AC_BASE_HREF; ?>guideline/create_edit_guideline.php?action=remove&"+type+"="+id+"&id="+"<?php echo $gid?>";
		return true;
	}
	else {
		return false;
	}
}

function get_confirm() {
	var answer = confirm("<?php echo _AC('confirm_delete'); ?>")
	if (answer){
		return true;
	}
	else {
		return false;
	}
}

//  End -->
//-->
</script>

<?php include(AC_INCLUDE_PATH.'footer.inc.php'); ?>
