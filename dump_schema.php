<?php

// modify these constants to your settings
$host = 'localhost:3306';
$mysql_user = 'root';
$mysql_password = '';
$db = 'achecker';

define('TABLE_PREFIX', 'AC_');

// the script starts here
$lang_db = mysql_connect($host, $mysql_user, $mysql_password) or die(mysql_error());
mysql_select_db($db, $lang_db) or die(mysql_error());
echo "Database connected!<br /><br />";

$dump_tables = array('checks', 'check_examples', 'check_prerequisites', 'color_mapping',
                     'guidelines', 'guideline_groups', 'guideline_subgroups', 'languages',
                     'lang_codes', 'privileges', 'subgroup_checks', 'techniques',
                     'test_pass', 'themes', 'users', 'user_groups', 'user_group_privilege');

foreach ($dump_tables as $table_name) {
	$sql = "SELECT * FROM ".TABLE_PREFIX.$table_name;
	$result = mysql_query($sql, $lang_db) or die(mysql_error());

	// if the table contains data
	if (mysql_num_rows($result) > 0) {
		$field_types = array();
		
		$output .= "# Dumping data for table `".$table_name."`\n\n".
		          "INSERT INTO `".$table_name."` (";
		
		for ($i = 0; $i < mysql_num_fields($result); ++$i) {
		    $field_name = mysql_field_name($result, $i);
		    $field_type = mysql_field_type($result, $i);
		
		    $output .= "`".$field_name."`, ";
		    $field_types[$field_name] = $field_type;
		}
		$output = substr($output, 0, -2); // remove the last ", "
		$output .= ") VALUES\n";
		
		while($row = mysql_fetch_assoc($result)) {
			$output .= "(";
			foreach ($field_types as $field_name => $field_type) {
				if ($field_type == "int") {
					$output .= $row[$field_name].", ";
				} else if (is_null($row[$field_name])) {
					$output .= "NULL, ";
				} else {
					$output .= "'". mysql_real_escape_string($row[$field_name])."', ";
				}
			}
			$output = substr($output, 0, -2);  // remove the last ", "
			$output .= "),\n";
		}
		$output = substr($output, 0, -2);  // remove the last ",\n"
		$output .= ";\n\n";
	}
}
echo "Done with dumping insert SQL!<br /><br />";

$schema_file = 'install/db/achecker_schema.sql';
$location_to_replace = "############ DO NOT remove this line. Below are the required data insert ############";

// read the original content
$filesize = filesize($schema_file);
$fh = fopen($schema_file, 'r');
$file_content = fread($fh, $filesize);
fclose($fh);

$content_need = substr($file_content, 0, strpos($file_content, $location_to_replace) + strlen($location_to_replace));
$new_content = $content_need . $output;

// write the new content
$fh = fopen($schema_file, 'w');
fwrite($fh, $new_content);
fclose($fh);
echo "Done with writing into schema file!<br /><br />";
echo "Done!!!";
?>