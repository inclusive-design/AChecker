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

include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');
include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');

$gid = $gid;

$guidelineGroupsDAO = new GuidelineGroupsDAO();
$guidelineSubgroupsDAO = new GuidelineSubgroupsDAO();
$checksDAO = new ChecksDAO();

$num_of_checks = 0;

function dispaly_check_table($checks_array)
{
	if (is_array($checks_array)){ 
?>
	<table class="data" rules="rows" >
		<thead>
		<tr>
			<th align="center"><?php echo _AC('html_tag'); ?></th>
			<th align="center"><?php echo _AC('error_type'); ?></th>
			<th align="center"><?php echo _AC('description'); ?></th>
			<th align="center"><?php echo _AC('check_id'); ?></th>
		</tr>
		</thead>
		
		<tbody>
	<?php foreach ($checks_array as $check_row) { ?>
		<tr>
			<td><?php echo htmlspecialchars($check_row['html_tag']); ?></td>
			<td><?php echo get_confidence_by_code($check_row['confidence']); ?></td>
			<td><span class="msg"><a target="_new" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $check_row["check_id"]; ?>" onclick="AChecker.popup('<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $check_row["check_id"]; ?>'); return false;"><?php echo htmlspecialchars(_AC($check_row['name'])); ?></a></span></td>
			<td><?php echo $check_row['check_id']; ?></td>
		</tr>
	<?php } // end of foreach?>
		</tbody>
	</table>
	<?php } // end of if
}

include(AC_INCLUDE_PATH.'header.inc.php');
?>
<div class="output-form">
	<h2><?php echo $row["title"]; ?></h2>
	
	<table class="form-data">
	<?php if ($row["abbr"] <> "") { ?>
		<tr>
			<th align="left"><?php echo _AC("abbr"); ?></th>
			<td><span class="msg"><?php echo $row["abbr"]; ?></span></td>
		</tr>
	<?php } ?>
	
	<?php if ($row["long_name"] <> "") { ?>
		<tr>
			<th align="left"><?php echo _AC("long_name"); ?></th>
			<td><span class="msg"><?php echo _AC($row["long_name"]); ?></span></td>
		</tr>
	<?php } ?>
			
	<?php if ($row["published_date"] <> "") { ?>
		<tr>
			<th align="left"><?php echo _AC("published_date"); ?></th>
			<td><span class="msg"><?php echo $row["published_date"]; ?></span></td>
		</tr>
	<?php } ?>

	<?php if ($row["earlid"] <> "") { ?>
		<tr>
			<th align="left"><?php echo _AC("earlid"); ?></th>
			<td><span class="msg"><a href="<?php echo $row["earlid"]; ?>"><?php echo $row["earlid"]; ?></a></span></td>
		</tr>
	<?php } ?>
			
	<?php $status = get_status_by_code($row['status']);
	if ($status <> "") { ?>
		<tr>
			<th align="left"><?php echo _AC("status"); ?></th>
			<td><span class="msg"><?php echo $status; ?></span></td>
		</tr>
	<?php } ?>
			
		<tr>
			<th align="left"><?php echo _AC("open_to_public"); ?></th>
			<td><span class="msg"><?php if ($row['open_to_public']) echo _AC('yes'); else echo _AC('no'); ?></span></td>
		</tr>
	</table>
	
	<h2><br /><?php echo _AC('checks'); ?></h2><br />
<?php 
// display guideline level checks
$guidelineLevel_checks = $checksDAO->getGuidelineLevelChecks($gid);

if (is_array($guidelineLevel_checks))
{
	$num_of_checks += count($guidelineLevel_checks);
	dispaly_check_table($guidelineLevel_checks);
}

// display named guidelines and their checks 
$named_groups = $guidelineGroupsDAO->getNamedGroupsByGuidelineID($gid);
if (is_array($named_groups))
{
	foreach ($named_groups as $group)
	{
?>
	<h3><?php echo _AC($group['name']);?></h3><br/>
<?php
		// get group level checks: the checks in subgroups without subgroup names
		$groupLevel_checks = $checksDAO->getGroupLevelChecks($group['group_id']);
		if (is_array($groupLevel_checks))
		{
			$num_of_checks += count($groupLevel_checks);
			dispaly_check_table($groupLevel_checks);
		}
		
		// display named subgroups and their checks
		$named_subgroups = $guidelineSubgroupsDAO->getNamedSubgroupByGroupID($group['group_id']);
		if (is_array($named_subgroups))
		{
			foreach ($named_subgroups as $subgroup)
			{
?>
	<h4><?php echo _AC($subgroup['name']);?></h4><br/>
<?php 
				$subgroup_checks = $checksDAO->getChecksBySubgroupID($subgroup['subgroup_id']);
				if (is_array($subgroup_checks))
				{
					$num_of_checks += count($subgroup_checks);
					dispaly_check_table($subgroup_checks);
				}
				else
					echo '		<p class="subgroup">'._AC('none_found').'<br/><br/></p>';
			} // end of foreach $named_subgroups
		} // end of if $named_subgroups
	} // end of foreach $named_groups 	
} // end of if $named_groups

// display "none found" if no check is defined in this guideline
if ($num_of_checks == 0) echo _AC('none_found');
?>
</div>
<?php
// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>
