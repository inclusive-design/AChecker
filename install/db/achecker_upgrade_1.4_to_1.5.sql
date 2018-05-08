
###############################################################
# Database upgrade SQL from AChecker 1.4 to AChecker 1.5
# Change all "0000-00-00 00:00:00" values to NULL
# Bug Tracker :https://issues.fluidproject.org/projects/ACHECKER/issues/ACHECKER-3?filter=allopenissuess
###############################################################



ALTER TABLE `checks` MODIFY `create_date` datetime DEFAULT NULL;

ALTER TABLE `language_text` MODIFY `revised_date` datetime default NULL;

ALTER TABLE `themes` MODIFY `last_updated` date NOT NULL DEFAULT CURRENT_TIMESTAMP;

UPDATE `checks` SET create_date = NULL WHERE create_date = '0000-00-00 00:00:00';

UPDATE `language_text` SET revised_date = NULL WHERE revised_date = '0000-00-00 00:00:00';


