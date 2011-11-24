###############################################################
# Database upgrade SQL from AChecker 1.2 to AChecker 1.3
###############################################################

# ---------------------------------------------------------------------
# Increase the size of the images, to 100 * 100, that triggers check 8
# More details at http://atutor.ca/atutor/mantis/view.php?id=4802
# since 1.3

UPDATE `checks` SET `func` = 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(\'src\');\r\n\r\nif (!$width)\r\n   return BasicFunctions::hasAttribute(\'longdesc\');\r\nelse\r\n   return !($width > 100 && $height > 100 && !BasicFunctions::hasAttribute(\'longdesc\'));' WHERE `check_id`=8;

# ---------------------------------------------------------------------
# Replaced check 63 with 213 in the guideline "Section 508"
# More details at http://atutor.ca/atutor/mantis/view.php?id=4816
# since 1.3

UPDATE `subgroup_checks` SET `check_id` = 213 WHERE `subgroup_id` = 75 AND `check_id` = 63;
