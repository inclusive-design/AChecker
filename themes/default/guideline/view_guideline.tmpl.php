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

include(AC_INCLUDE_PATH.'header.inc.php');
?>
<div class="output-form">
	<h2><?php echo $this->row["title"]; ?></h2>
	
	<table>
	<?php if ($this->row["abbr"] <> "") { ?>
		<tr>
			<th align="left"><? echo _AC("abbr"); ?></th>
			<td><span class="msg"><?php echo $this->row["abbr"]; ?></span></td>
		</tr>
	<?php } ?>
	
	<?php if ($this->row["long_name"] <> "") { ?>
		<tr>
			<th align="left"><? echo _AC("long_name"); ?></th>
			<td><span class="msg"><?php echo _AC($this->row["long_name"]); ?></span></td>
		</tr>
	<?php } ?>
			
	<?php if ($this->row["published_date"] <> "") { ?>
		<tr>
			<th align="left"><? echo _AC("published_date"); ?></th>
			<td><span class="msg"><?php echo $this->row["published_date"]; ?></span></td>
		</tr>
	<?php } ?>

	<?php if ($this->row["earlid"] <> "") { ?>
		<tr>
			<th align="left"><? echo _AC("earlid"); ?></th>
			<td><span class="msg"><a href="<?php echo $this->row["earlid"]; ?>"><?php echo $this->row["earlid"]; ?></a></span></td>
		</tr>
	<?php } ?>
			
	<?php $status = get_status_by_code($this->row['status']);
	if ($status <> "") { ?>
		<tr>
			<th align="left"><? echo _AC("status"); ?></th>
			<td><span class="msg"><?php echo $status; ?></span></td>
		</tr>
	<?php } ?>
			
		<tr>
			<th align="left"><? echo _AC("open_to_public"); ?></th>
			<td><span class="msg"><?php if ($this->row['open_to_public']) echo _AC('yes'); else echo _AC('no'); ?></span></td>
		</tr>
	</table>
	
	<h2><br /><?php echo _AC('checks'); ?></h2><br />
	<?php 
	if (!is_array($this->checks_rows)){ 
		echo _AC('none_found');
	} 
	else {?>
	<table class="data" summary="" rules="rows" >
		<thead>
		<tr>
			<th align="center"><?php echo _AC('html_tag'); ?></th>
			<th align="center"><?php echo _AC('error_type'); ?></th>
			<th align="center"><?php echo _AC('description'); ?></th>
		</tr>
		</thead>
		
		<tbody>
	<?php foreach ($this->checks_rows as $checks_row) { ?>
		<tr>
			<td><?php echo $checks_row['html_tag']; ?></td>
			<td><?php echo get_confidence_by_code($checks_row['confidence']); ?></td>
			<td><span class="msg"><a target="_blank" href="<?php echo AC_BASE_HREF; ?>checker/suggestion.php?id=<?php echo $checks_row["check_id"]; ?>"><?php echo _AC($checks_row['name']); ?></a></span></td>
		</tr>
	<?php } // end of foreach?>
		</tbody>
	</table>
	<?php } // end of if?>
</div>
<?php
// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>
