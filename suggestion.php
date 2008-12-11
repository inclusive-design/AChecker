<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

define('AT_INCLUDE_PATH', 'include/');

include(AT_INCLUDE_PATH.'vitals.inc.php');
include(AT_INCLUDE_PATH.'header.inc.php');

$check_id = intval($_GET["id"]);

$sql = "SELECT name, err, description, rationale, how_to_repair, repair_example, question, decision_pass, decision_fail
				FROM ". TABLE_PREFIX ."checks WHERE check_id=". $check_id;
$result	= mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);

?>
<div class="output-form">
	
<h2><? echo _AC("requirement"); ?></h2>
<span class="msg"><?php echo _AC($row["name"]); ?></span>

<h2><? echo _AC("error"); ?></h2>
<span class="msg"><?php echo _AC($row["err"]); ?></span>

<?php
if ($row["description"] <> "")
{
?>

<h2><? echo _AC("description"); ?></h2>
<span class="msg"><?php echo _AC($row["description"]); ?></span>

<?php
}
?>

<?php
if ($row["rationale"] <> "")
{
?>

<h2><? echo _AC("rationale"); ?></h2>
<span class="msg"><?php echo _AC($row["rationale"]); ?></span>

<?php
}
?>

<?php
if ($row["how_to_repair"] <> "")
{
?>

<h2><? echo _AC("how_to_repair"); ?></h2>
<span class="msg"><?php echo _AC($row["how_to_repair"]); ?></span>

<?php
}
?>

<?php
if ($row["repair_example"] <> "")
{
?>

<h2><? echo _AC("repair_example"); ?></h2>
<span class="msg"><pre><?php echo htmlentities(_AC($row["repair_example"])); ?></pre></span>

<?php
}
?>

<?php
if ($row["question"] <> "")
{
?>

<h2><? echo _AC("how_to_determine"); ?></h2>
<table>
	<tr>
		<th align="left"><? echo _AC("question"); ?></th>
		<td><span class="msg"><?php echo _AC($row["question"]); ?></span></td>
	</tr>
	<tr>
		<th align="left"><? echo _AC("pass"); ?></th>
		<td><span class="msg"><?php echo _AC($row["decision_pass"]); ?></span></td>
	</tr>
	<tr>
		<th align="left"><? echo _AC("fail"); ?></th>
		<td><span class="msg"><?php echo _AC($row["decision_fail"]); ?></span></td>
	</tr>
</table>

<?php
}

$sql = "SELECT step_id, step
				FROM ". TABLE_PREFIX ."test_procedure 
				WHERE check_id=". $check_id ."
				ORDER BY step_id";
$result	= mysql_query($sql, $db) or die(mysql_error());

if (mysql_num_rows($result) > 0)
{
?>

<h2><? echo _AC("steps_to_check"); ?></h2>
	<h3><? echo _AC("procedure"); ?></h3>
<?php
}

while ($row = mysql_fetch_assoc($result))
{
	echo '<span class="msg">'.intval($row["step_id"] + 1)  . ". " . _AC($row["step"]). "</span><br />";
}

$sql = "SELECT step_id, step
				FROM ". TABLE_PREFIX ."test_expected 
				WHERE check_id=". $check_id ."
				ORDER BY step_id";
$result	= mysql_query($sql, $db) or die(mysql_error());

if (mysql_num_rows($result) > 0)
{
?>

	<h3><? echo _AC("expected_result"); ?></h3>
<?php
}

while ($row = mysql_fetch_assoc($result))
{
	echo '<span class="msg">'.intval($row["step_id"]+1) . ". " . _AC($row["step"]). "</span><br />";
}

$sql = "SELECT step_id, step
				FROM ". TABLE_PREFIX ."test_fail 
				WHERE check_id=". $check_id ."
				ORDER BY step_id";
$result	= mysql_query($sql, $db) or die(mysql_error());

if (mysql_num_rows($result) > 0)
{
?>

	<h3><? echo _AC("failed_result"); ?></h3>
<?php
}

while ($row = mysql_fetch_assoc($result))
{
	echo '<span class="msg">'.intval($row["step_id"]+1) . ". " . _AC($row["step"]). "</span><br />";
}
?>
</div>
<?php
// display footer
include(AT_INCLUDE_PATH.'footer.inc.php');

?>
