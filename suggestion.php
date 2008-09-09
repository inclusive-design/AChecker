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
include(AT_INCLUDE_PATH.'header.php');

$check_id = intval($_GET["id"]);

$sql = "SELECT name, err, description, rationale, how_to_repair, repair_example, question, decision_pass, decision_fail
				FROM ". TABLE_PREFIX ."checks WHERE check_id=". $check_id;
$result	= mysql_query($sql, $db) or die(mysql_error());
$row = mysql_fetch_assoc($result);

?>
<div class="output-form">
	
<h4>Requirement</h4>
<span class="msg"><?php echo $row["name"]; ?></span>

<h4>Error</h4>
<span class="msg"><?php echo $row["err"]; ?></span>

<?php
if ($row["description"] <> "")
{
?>

<h4>Description</h4>
<span class="msg"><?php echo $row["description"]; ?></span>

<?php
}
?>

<?php
if ($row["rationale"] <> "")
{
?>

<h4>Rationale</h4>
<span class="msg"><?php echo $row["rationale"]; ?></span>

<?php
}
?>

<?php
if ($row["how_to_repair"] <> "")
{
?>

<h4>How to Repair</h4>
<span class="msg"><?php echo $row["how_to_repair"]; ?></span>

<?php
}
?>

<?php
if ($row["repair_example"] <> "")
{
?>

<h4>Repair Example</h4>
<span class="msg"><pre><?php echo htmlentities($row["repair_example"]); ?></pre></span>

<?php
}
?>

<?php
if ($row["question"] <> "")
{
?>

<h4>How to Determine</h4>
<table>
	<tr>
		<th align="left">Question</th>
		<td><span class="msg"><?php echo $row["question"]; ?></span></td>
	</tr>
	<tr>
		<th align="left">Pass</th>
		<td><span class="msg"><?php echo $row["decision_pass"]; ?></span></td>
	</tr>
	<tr>
		<th align="left">Fail</th>
		<td><span class="msg"><?php echo $row["decision_fail"]; ?></span></td>
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

<h4>Steps to Check</h4>
	<h5>Procedure</h5>
<?php
}

while ($row = mysql_fetch_assoc($result))
{
	echo '<span class="msg">'.intval($row["step_id"] + 1)  . ". " . $row["step"]. "</span><br>";
}

$sql = "SELECT step_id, step
				FROM ". TABLE_PREFIX ."test_expected 
				WHERE check_id=". $check_id ."
				ORDER BY step_id";
$result	= mysql_query($sql, $db) or die(mysql_error());

if (mysql_num_rows($result) > 0)
{
?>

	<h5>Expected Result</h5>
<?php
}

while ($row = mysql_fetch_assoc($result))
{
	echo '<span class="msg">'.intval($row["step_id"]+1) . ". " . $row["step"]. "</span><br>";
}

$sql = "SELECT step_id, step
				FROM ". TABLE_PREFIX ."test_fail 
				WHERE check_id=". $check_id ."
				ORDER BY step_id";
$result	= mysql_query($sql, $db) or die(mysql_error());

if (mysql_num_rows($result) > 0)
{
?>

	<h5>Failed Result</h5>
<?php
}

while ($row = mysql_fetch_assoc($result))
{
	echo '<span class="msg">'.intval($row["step_id"]+1) . ". " . $row["step"]. "</span><br>";
}
?>
</div>
<?php
// display footer
include(AT_INCLUDE_PATH.'footer.php');

?>
