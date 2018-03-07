###############################################################
# Database upgrade SQL from AChecker 1.3 to AChecker 1.4
###############################################################

# ---------------------------------------------------------------------
# Altered Table Structure For Table 'users'
# For More details, Visit PR https://github.com/inclusive-design/AChecker/pull/71
# since 1.3

ALTER TABLE `users` MODIFY login VARCHAR(50);

# ---------------------------------------------------------------------
# Data Updated and Removed from Table 'language_text'
# For More details, Visit PR https://github.com/inclusive-design/AChecker/pull/71 
# since 1.3

UPDATE `language_text` SET term = '8_min_chars',text = '8 minimum characters' WHERE term='15_max_chars';

DELETE FROM `language_text` WHERE term = '20_max_chars';


