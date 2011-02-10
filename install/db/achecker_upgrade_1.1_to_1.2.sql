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
