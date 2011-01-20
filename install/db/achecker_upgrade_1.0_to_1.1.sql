###############################################################
# Database upgrade SQL from AChecker 1.0 to AChecker 1.1
###############################################################

# --------------------------------------------------------
# Table structure for table `patches`
# since 1.1

CREATE TABLE `patches` (
  `patches_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `achecker_patch_id` VARCHAR(20) NOT NULL default '',
  `applied_version` VARCHAR(10) NOT NULL default '',
  `patch_folder` VARCHAR(250) NOT NULL default '',
  `description` TEXT,
  `available_to` VARCHAR(250) NOT NULL default '',
  `sql_statement` text,
  `status` varchar(20) NOT NULL default '',
  `remove_permission_files` text,
  `backup_files` text,
  `patch_files` text,
  `author` VARCHAR(255) NOT NULL,
  `installed_date` datetime NOT NULL,
  PRIMARY KEY  (`patches_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


# --------------------------------------------------------
# Table structure for table `patches_files`
# since 1.1

CREATE TABLE `patches_files` (
  `patches_files_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patches_id` MEDIUMINT UNSIGNED NOT NULL default 0,
  `action` VARCHAR(20) NOT NULL default '',
  `name` TEXT,
  `location` VARCHAR(250) NOT NULL default '',
  PRIMARY KEY  (`patches_files_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
# Table structure for table `patches_files_actions`
# since 1.1

CREATE TABLE `patches_files_actions` (
  `patches_files_actions_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `patches_files_id` MEDIUMINT UNSIGNED NOT NULL default 0,
  `action` VARCHAR(20) NOT NULL default '',
  `code_from` TEXT,
  `code_to` TEXT,
  PRIMARY KEY  (`patches_files_actions_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
# Table structure for table `myown_patches`
# since 1.1

CREATE TABLE `myown_patches` (
  `myown_patch_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `achecker_patch_id` VARCHAR(20) NOT NULL default '',
  `applied_version` VARCHAR(10) NOT NULL default '',
  `description` TEXT,
  `sql_statement` text,
  `status` varchar(20) NOT NULL default '',
  `last_modified` datetime NOT NULL,
  PRIMARY KEY  (`myown_patch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
# Table structure for table `myown_patches_dependent`
# since 1.1

CREATE TABLE `myown_patches_dependent` (
  `myown_patches_dependent_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `myown_patch_id` MEDIUMINT UNSIGNED NOT NULL,
  `dependent_patch_id` VARCHAR(50) NOT NULL default '',
  PRIMARY KEY  (`myown_patches_dependent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
# Table structure for table `myown_patches_files`
# since 1.1

CREATE TABLE `myown_patches_files` (
  `myown_patches_files_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `myown_patch_id` MEDIUMINT UNSIGNED NOT NULL,
  `action` VARCHAR(20) NOT NULL default '',
  `name` VARCHAR(250) NOT NULL,
  `location` VARCHAR(250) NOT NULL default '',
  `code_from` TEXT,
  `code_to` TEXT,
  `uploaded_file` TEXT,
  PRIMARY KEY  (`myown_patches_files_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# --------------------------------------------------------
# Updates on table `checks`

UPDATE `checks` SET func = 'return ((BasicFunctions::getInnerTextLength() > 0 || BasicFunctions::getAttributeTrimedValueLength(''title'') > 0 || BasicFunctions::getLengthOfAttributeValueWithGivenTagInChildren(''img'', ''alt'') > 0) || !BasicFunctions::hasAttribute(''href''));' WHERE check_id = 174;
UPDATE `checks` SET func = 'return (BasicFunctions::getPlainTextLength() < 21 || !BasicFunctions::hasTabularInfo());' WHERE check_id = 241;

# --------------------------------------------------------
# Altered unnecessary "NOT NULL" fields

ALTER TABLE `guidelines` MODIFY `published_date` date;
ALTER TABLE `language_text` MODIFY `context` text;
ALTER TABLE `privileges` MODIFY `description` text, MODIFY `last_update` datetime DEFAULT NULL;
ALTER TABLE `themes` MODIFY `extra_info` text;
ALTER TABLE `user_links` MODIFY `URI` text, MODIFY `last_sessionID` varchar(40);

# --------------------------------------------------------
# Data update

INSERT INTO `checks` (`check_id`, `user_id`, `html_tag`, `confidence`, `note`, `name`, `err`, `description`, `search_str`, `long_description`, `rationale`, `how_to_repair`, `repair_example`, `question`, `decision_pass`, `decision_fail`, `test_procedure`, `test_expected_result`, `test_failed_result`, `func`, `open_to_public`, `create_date`) VALUES
(301, 0, 'all elements', 0, '_NOTE_301', '_CNAME_301', '_ERR_301', '_DESC_301', '', NULL, '_RATIONALE_301', '_HOWTOREPAIR_301', '_REPAIREXAMPLE_301', '_QUESTION_301', '_DECISIONPASS_301', '_DECISIONFAIL_301', '_PROCEDURE_301', '_EXPECTEDRESULT_301', '_FAILEDRESULT_301', 'return BasicFunctions::checkColorContrastForGeneralElementWCAG2AA();', 1, '0000-00-00 00:00:00'),
(302, 0, 'a', 0, '_NOTE_302', '_CNAME_302', '_ERR_302', '_DESC_302', '', NULL, '_RATIONALE_302', '_HOWTOREPAIR_302', '_REPAIREXAMPLE_302', '_QUESTION_302', '_DECISIONPASS_302', '_DECISIONFAIL_302', '_PROCEDURE_302', '_EXPECTEDRESULT_302', '_FAILEDRESULT_302', 'return BasicFunctions::checkColorContrastForVisitedLinkWCAG2AA();', 1, '0000-00-00 00:00:00'),
(303, 0, 'a', 0, '_NOTE_303', '_CNAME_303', '_ERR_303', '_DESC_303', '', NULL, '_RATIONALE_303', '_HOWTOREPAIR_303', '_REPAIREXAMPLE_303', '_QUESTION_303', '_DECISIONPASS_303', '_DECISIONFAIL_303', '_PROCEDURE_303', '_EXPECTEDRESULT_303', '_FAILEDRESULT_303', 'return BasicFunctions::checkColorContrastForActiveLinkWCAG2AA();', 1, '0000-00-00 00:00:00'),
(304, 0, 'a', 0, '_NOTE_304', '_CNAME_304', '_ERR_304', '_DESC_304', '', NULL, '_RATIONALE_304', '_HOWTOREPAIR_304', '_REPAIREXAMPLE_304', '_QUESTION_304', '_DECISIONPASS_304', '_DECISIONFAIL_304', '_PROCEDURE_304', '_EXPECTEDRESULT_304', '_FAILEDRESULT_304', 'return BasicFunctions::checkColorContrastForHoverLinkWCAG2AA();', 1, '0000-00-00 00:00:00'),
(305, 0, 'a', 0, '_NOTE_305', '_CNAME_305', '_ERR_305', '_DESC_305', '', NULL, '_RATIONALE_305', '_HOWTOREPAIR_305', '_REPAIREXAMPLE_305', '_QUESTION_305', '_DECISIONPASS_305', '_DECISIONFAIL_305', '_PROCEDURE_305', '_EXPECTEDRESULT_305', '_FAILEDRESULT_305', 'return BasicFunctions::checkColorContrastForNotVisitedLinkWCAG2AA();', 1, '0000-00-00 00:00:00'),
(306, 0, 'all elements', 0, '_NOTE_306', '_CNAME_306', '_ERR_306', '_DESC_306', '', NULL, '_RATIONALE_306', '_HOWTOREPAIR_306', '_REPAIREXAMPLE_306', '_QUESTION_306', '_DECISIONPASS_306', '_DECISIONFAIL_306', '_PROCEDURE_306', '_EXPECTEDRESULT_306', '_FAILEDRESULT_306', 'return BasicFunctions::checkColorContrastForGeneralElementWCAG2AAA();', 1, '0000-00-00 00:00:00'),
(307, 0, 'a', 0, '_NOTE_307', '_CNAME_307', '_ERR_307', '_DESC_307', '', NULL, '_RATIONALE_307', '_HOWTOREPAIR_307', '_REPAIREXAMPLE_307', '_QUESTION_307', '_DECISIONPASS_307', '_DECISIONFAIL_307', '_PROCEDURE_307', '_EXPECTEDRESULT_307', '_FAILEDRESULT_307', 'return BasicFunctions::checkColorContrastForVisitedLinkWCAG2AAA();', 1, '0000-00-00 00:00:00'),
(308, 0, 'a', 0, '_NOTE_308', '_CNAME_308', '_ERR_308', '_DESC_308', '', NULL, '_RATIONALE_308', '_HOWTOREPAIR_308', '_REPAIREXAMPLE_308', '_QUESTION_308', '_DECISIONPASS_308', '_DECISIONFAIL_308', '_PROCEDURE_308', '_EXPECTEDRESULT_308', '_FAILEDRESULT_308', 'return BasicFunctions::checkColorContrastForActiveLinkWCAG2AAA();', 1, '0000-00-00 00:00:00'),
(309, 0, 'a', 0, '_NOTE_309', '_CNAME_309', '_ERR_309', '_DESC_309', '', NULL, '_RATIONALE_309', '_HOWTOREPAIR_309', '_REPAIREXAMPLE_309', '_QUESTION_309', '_DECISIONPASS_309', '_DECISIONFAIL_309', '_PROCEDURE_309', '_EXPECTEDRESULT_309', '_FAILEDRESULT_309', 'return BasicFunctions::checkColorContrastForHoverLinkWCAG2AAA();', 1, '0000-00-00 00:00:00'),
(310, 0, 'a', 0, '_NOTE_310', '_CNAME_310', '_ERR_310', '_DESC_310', '', NULL, '_RATIONALE_310', '_HOWTOREPAIR_310', '_REPAIREXAMPLE_310', '_QUESTION_310', '_DECISIONPASS_310', '_DECISIONFAIL_310', '_PROCEDURE_310', '_EXPECTEDRESULT_310', '_FAILEDRESULT_310', 'return BasicFunctions::checkColorContrastForNotVisitedLinkWCAG2AAA();', 1, '0000-00-00 00:00:00');

INSERT INTO `check_examples` (`check_example_id`, `check_id`, `type`, `description`, `content`) VALUES
(563, 301, '0', 'Low contrast black text on a blue background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #301 - Negative</title>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#0000ff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(562, 301, '1', 'High contrast black text on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #301 - Positive</title>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(556, 302, '1', 'Style assigns higher contrast blue as visited link colour on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #302 - Positive</title>\r\n<style type=\"text/css\">\r\na:visited{color:blue;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(557, 302, '0', 'Style assigns low contrast yellow to visited link colour on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #302 - Negative</title>\r\n<style type=\"text/css\">\r\na:visited{color:yellow;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(559, 303, '0', 'Style assigns low contrast yellow to active link colour on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #302 - Negative</title>\r\n<style type=\"text/css\">\r\na:active{color:yellow;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(558, 303, '1', 'Style assigns higher contrast blue as active link colour on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #302 - Positive</title>\r\n<style type=\"text/css\">\r\na:active{color:blue;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(528, 304, '1', 'Style assigns higher contrast red as selected link colour on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #304 - Positive</title>\r\n<style type=\"text/css\">\r\na:hover{color:red;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(529, 304, '0', 'Style assigns low contrast yellow to selected link colour on a white background', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #304 - Negative</title>\r\n<style type=\"text/css\">\r\na:hover{color:yellow;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(561, 305, '0', 'Style assigns low contrast yellow to visited link colour on a white background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #305 - Negative</title>\r\n<style type=\"text/css\">\r\na:link{color:yellow;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(560, 305, '1', 'Style assigns higher contrast blue as link colour on a white background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #305 - Positive</title>\r\n<style type=\"text/css\">\r\na:link{color:blue;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(537, 306, '0', 'Style assigns lower contrast green to text colour on a red background 2.7:1', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #306 - Negative</title>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#59b500;color:#b70006;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(536, 306, '1', 'Style assigns high contrast black as text colour on a white background 21:1', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #306 - Positive</title>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;color:#000000;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(541, 307, '0', 'Style assigns low contrast green to visited link colour on a red background 2.7:1', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #307 - Negative</title>\r\n<style type=\"text/css\">\r\na:visited{color:#b70006;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#59b500;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(540, 307, '1', 'Style assigns higher contrast black as visited link colour on a white background. 21:1', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #307 - Positive</title>\r\n<style type=\"text/css\">\r\na:visited{color:black;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(542, 308, '1', 'Style assigns higher contrast black as active link colour on a white background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #308 - Positive</title>\r\n<style type=\"text/css\">\r\na:active{color:black;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(543, 308, '0', 'Style assigns low contrast green to active link colour on a red background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #308 - Negative</title>\r\n<style type=\"text/css\">\r\na:active{color:#b70006;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#59b500;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(547, 309, '0', 'Style assigns low contrast green to selected link colour on a red background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #307 - Negative</title>\r\n<style type=\"text/css\">\r\na:hover{color:#b70006;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#59b500;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(546, 309, '1', 'Style assigns high contrast black as the selected link colour on a white background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #309 - Positive</title>\r\n<style type=\"text/css\">\r\na:hover{color:black;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(565, 310, '0', 'Style assigns low contrast green to visited link colour on a red background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #310 - Negative</title>\r\n<style type=\"text/css\">\r\na:link{color:#b70006;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#59b500;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>'),
(564, 310, '1', 'Style assigns high contrast black as link colour on a white background.', '<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\"\"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n<html lang=\"en\">\r\n<head>\r\n<title>OAC Testfile - Check #310 - Positive</title>\r\n<style type=\"text/css\">\r\na:link{color:black;}\r\n</style>\r\n</head>\r\n<body>\r\n<p style=\"background-color:#ffffff;\">\r\nRead the <a href=\"carol-text-dogs.txt\">text transcript of Carol\'s talk about dogs</a>.\r\n</p>\r\n</body>\r\n</html>');

DELETE FROM `subgroup_checks` WHERE subgroup_id = 253 AND check_id in (221, 222, 223, 224);
DELETE FROM `subgroup_checks` WHERE subgroup_id = 295 AND check_id in (221, 222, 223, 224);
DELETE FROM `subgroup_checks` WHERE subgroup_id = 333 AND check_id in (254, 255, 256, 257);
DELETE FROM `subgroup_checks` WHERE subgroup_id = 360 AND check_id in (221, 222, 223, 224);

INSERT INTO `subgroup_checks` (subgroup_id, check_id) VALUES
(333, 306),
(333, 307),
(333, 308),
(333, 309),
(333, 310),
(360, 301),
(360, 302),
(360, 303),
(360, 304),
(360, 305);

UPDATE `privileges` SET `menu_sequence`=8 WHERE `privilege_id`=7;

INSERT INTO `privileges` (`privilege_id`, `title_var`, `description`, `create_date`, `last_update`, `link`, `menu_sequence`, `open_to_public`) VALUES
(8, 'updater', 'Updater: Install, create, edit updates.', '2011-01-11 13:48:03', NULL, 'updater/index.php', 7, 0);

INSERT INTO `user_group_privilege` (`user_group_id`, `privilege_id`) VALUES
(1, 6),
(1, 8);
