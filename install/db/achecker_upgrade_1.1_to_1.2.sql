###############################################################
# Database upgrade SQL from AChecker 1.1 to AChecker 1.2
###############################################################

# --------------------------------------------------------
# Remove field user_decisions.sequence_id
# since 1.2

ALTER TABLE `user_decisions` DROP `sequence_id`;
