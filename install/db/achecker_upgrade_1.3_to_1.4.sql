###############################################################
# Database upgrade SQL from AChecker 1.3 to AChecker 1.4
###############################################################

# ---------------------------------------------------------------------
# Altered Table Structure For Table 'users'
# For More details, Visit http://www.atutor.ca/atutor/mantis/view.php?id=5846
# since 1.3

ALTER TABLE `users` MODIFY `login` VARCHAR(50);

# ---------------------------------------------------------------------
# Data Updated and Removed from Table 'language_text'
# For More details, Visit http://www.atutor.ca/atutor/mantis/view.php?id=5846
# since 1.3

UPDATE `language_text` SET `term` = '8_min_chars',`text` = '8 minimum characters',`revised_date`='2018-03-08 02:00:00' WHERE `term`='15_max_chars';

DELETE FROM `language_text` WHERE `term` = '20_max_chars';


