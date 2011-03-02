###############################################################
# Database upgrade SQL from AChecker 1.1 to AChecker 1.2
###############################################################

# --------------------------------------------------------
# Remove field user_decisions.sequence_id
# since 1.2

ALTER TABLE `user_decisions` DROP `sequence_id`;

# --------------------------------------------------------
# Modifications on some check functoins due to the removal of BasicFunctions::isFileExists()
# since 1.2

UPDATE `checks` SET `func` = 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(\'src\');\r\n\r\nif (!$width)\r\n   return BasicFunctions::hasAttribute(\'longdesc\');\r\nelse\r\n   return !($width > 50 && $height > 50 && !BasicFunctions::hasAttribute(\'longdesc\'));' WHERE `check_id`=8;
UPDATE `checks` SET `func` = 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(\'src\');\r\n\r\nif (!$width)\r\n   return  false;\r\nelse\r\n   return !($width > 50 && $height > 50);' WHERE `check_id`=11;
UPDATE `checks` SET `func` = 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(\'src\');\r\n\r\nif (!$width)\r\n   return  false;\r\nelse\r\n   return !($width > 100 && $height > 100);' WHERE `check_id`=14;

# --------------------------------------------------------
# Remove duplicate checks that appear more than once in each of the guidelines
# since 1.2
DELETE FROM `subgroup_checks` 
WHERE (`subgroup_id` = 1 AND `check_id` = 24)
OR (`subgroup_id` = 1 AND `check_id` = 34)
OR (`subgroup_id` = 1 AND `check_id` = 74)
OR (`subgroup_id` = 1 AND `check_id` = 75)
OR (`subgroup_id` = 1 AND `check_id` = 90)
OR (`subgroup_id` = 1 AND `check_id` = 127)
OR (`subgroup_id` = 62 AND `check_id` = 24)
OR (`subgroup_id` = 62 AND `check_id` = 90)
OR (`subgroup_id` = 80 AND `check_id` = 15)
OR (`subgroup_id` = 92 AND `check_id` = 77)
OR (`subgroup_id` = 100 AND `check_id` = 24)
OR (`subgroup_id` = 100 AND `check_id` = 74)
OR (`subgroup_id` = 100 AND `check_id` = 75)
OR (`subgroup_id` = 100 AND `check_id` = 127)
OR (`subgroup_id` = 117 AND `check_id` = 24)
OR (`subgroup_id` = 117 AND `check_id` = 34)
OR (`subgroup_id` = 117 AND `check_id` = 74)
OR (`subgroup_id` = 117 AND `check_id` = 75)
OR (`subgroup_id` = 117 AND `check_id` = 127)
OR (`subgroup_id` = 162 AND `check_id` = 24)
OR (`subgroup_id` = 162 AND `check_id` = 34)
OR (`subgroup_id` = 162 AND `check_id` = 74)
OR (`subgroup_id` = 162 AND `check_id` = 75)
OR (`subgroup_id` = 162 AND `check_id` = 127)
OR (`subgroup_id` = 163 AND `check_id` = 13)
OR (`subgroup_id` = 224 AND `check_id` = 11)
OR (`subgroup_id` = 234 AND `check_id` = 263)
OR (`subgroup_id` = 250 AND `check_id` = 133)
OR (`subgroup_id` = 278 AND `check_id` = 11)
OR (`subgroup_id` = 287 AND `check_id` = 145)
OR (`subgroup_id` = 349 AND `check_id` = 272)
OR (`subgroup_id` = 351 AND `check_id` = 269);
