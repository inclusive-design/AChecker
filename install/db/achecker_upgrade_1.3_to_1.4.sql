###############################################################
# Database upgrade SQL from AChecker 1.3 to AChecker 1.4
###############################################################

# ---------------------------------------------------------------------
######
# EDITS ADDED BY UNIBO, added to AChecker 1.4
#
######
/*
delete from subgroup_checks where subgroup_id = "224" and check_id = "60"; 
delete from subgroup_checks where subgroup_id = "224" and check_id = "66"; 
delete from subgroup_checks where subgroup_id = "224" and check_id = "238"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "57"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "91"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "95"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "118"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "119"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "120"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "121"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "204"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "206"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "207"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "208"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "212"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "213"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "216"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "270"; 
delete from subgroup_checks where subgroup_id = "227" and check_id = "271"; 
delete from subgroup_checks where subgroup_id = "229" and check_id = "133"; 
delete from subgroup_checks where subgroup_id = "238" and check_id = "274"; 
delete from subgroup_checks where subgroup_id = "242" and check_id = "60"; 
delete from subgroup_checks where subgroup_id = "242" and check_id = "66"; 
delete from subgroup_checks where subgroup_id = "242" and check_id = "238"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "57"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "91"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "95"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "118"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "119"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "120"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "121"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "133"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "204"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "206"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "207"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "208"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "212"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "213"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "216"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "270"; 
delete from subgroup_checks where subgroup_id = "248" and check_id = "271"; 
delete from subgroup_checks where subgroup_id = "264" and check_id = "173"; 
delete from subgroup_checks where subgroup_id = "271" and check_id = "274"; 
delete from subgroup_checks where subgroup_id = "278" and check_id = "60"; 
delete from subgroup_checks where subgroup_id = "278" and check_id = "238"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "57"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "91"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "95"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "118"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "119"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "120"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "121"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "204"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "206"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "207"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "208"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "212"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "213"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "216"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "270"; 
delete from subgroup_checks where subgroup_id = "288" and check_id = "271"; 
delete from subgroup_checks where subgroup_id = "290" and check_id = "133"; 
delete from subgroup_checks where subgroup_id = "293" and check_id = "221"; 
delete from subgroup_checks where subgroup_id = "293" and check_id = "222"; 
delete from subgroup_checks where subgroup_id = "293" and check_id = "223"; 
delete from subgroup_checks where subgroup_id = "293" and check_id = "224"; 
delete from subgroup_checks where subgroup_id = "322" and check_id = "263"; 
delete from subgroup_checks where subgroup_id = "322" and check_id = "265"; 
delete from subgroup_checks where subgroup_id = "323" and check_id = "81"; 
delete from subgroup_checks where subgroup_id = "323" and check_id = "100"; 
delete from subgroup_checks where subgroup_id = "323" and check_id = "131"; 
delete from subgroup_checks where subgroup_id = "324" and check_id = "274"; 
delete from subgroup_checks where subgroup_id = "367" and check_id = "173"; 
delete from subgroup_checks where subgroup_id = "372" and check_id = "263"; 
delete from subgroup_checks where subgroup_id = "372" and check_id = "265"; 
delete from subgroup_checks where subgroup_id = "373" and check_id = "81"; 
delete from subgroup_checks where subgroup_id = "373" and check_id = "100"; 
delete from subgroup_checks where subgroup_id = "373" and check_id = "131"; 


INSERT INTO `subgroup_checks` (`subgroup_id`, `check_id`) VALUES
(224, 11),
(224, 14),
(224, 24),
(224, 55),
(224, 73),
(224, 236),
(224, 286),
(226, 313),
(226, 317),
(227, 37),
(227, 38),
(227, 39),
(227, 40),
(227, 41),
#(227, 42),
#(227, 43),
#(227, 44),
#(227, 45),
#(227, 46),
#(227, 47),
(227, 249),
(227, 323),
(227, 324),
(227, 325),
(227, 326),
(227, 327),
(227, 328),
(227, 329),
(227, 330),
(227, 331),
(227, 332),
(227, 333),
(227, 334),
(227, 335),
(227, 336),
(227, 337),
(227, 338),
(227, 339),
(227, 340),
(227, 342),
(227, 343),
(227, 344),
(227, 345),
(227, 346),
(227, 347),
(227, 348),
(227, 349),
(227, 350),
(227, 351),
(227, 352),
(227, 353),
(227, 354),
(227, 355),
(227, 356),
(227, 357),
(227, 401),
(228, 133),
(228, 154),
(228, 270),
(228, 271),
(228, 322),
(231, 380),
(231, 381),
(231, 382),
(231, 383),
(231, 384),
(231, 385),
(231, 386),
(231, 387),
(232, 71),
(232, 394),
(233, 395),
(234, 399),
(234, 400),
(237, 274),
(237, 381),
(237, 383),
(237, 385),
(237, 407),
(237, 411),
(237, 412),
(237, 413),
(237, 414),
(237, 415),
(237, 419),
(238, 416),
(238, 417),
(239, 423),
(239, 424),
(240, 425),
(240, 426),
(242, 14),
(242, 55),
(242, 73),
(242, 236),
(242, 286),
(245, 313),
(245, 317),
(246, 17),
(246, 20),
(246, 145),
#(246, 311),
#(246, 312),
(246, 313),
(246, 314),
(246, 316),
(246, 318),
(248, 37),
(248, 38),
(248, 39),
(248, 40),
(248, 41),
#(248, 42),
#(248, 43),
#(248, 44),
#(248, 45),
#(248, 46),
#(248, 47),
(248, 136),
(248, 249),
(248, 323),
(248, 324),
(248, 325),
(248, 326),
(248, 327),
(248, 328),
(248, 329),
(248, 330),
(248, 331),
(248, 332),
(248, 333),
(248, 334),
(248, 335),
(248, 336),
(248, 337),
(248, 338),
(248, 339),
(248, 340),
(248, 342),
(248, 343),
(248, 344),
(248, 345),
(248, 346),
(248, 347),
(248, 348),
(248, 349),
(248, 350),
(248, 351),
(248, 352),
(248, 353),
(248, 354),
(248, 355),
(248, 356),
(248, 357),
(248, 401),
(249, 133),
(249, 154),
(249, 270),
(249, 271),
(249, 322),
(253, 358),
(253, 359),
(253, 360),
(253, 364),
# (253, 365), removed
(253, 388),
(253, 389),
(253, 390),
(253, 396),
(254, 371),
(254, 374),
(255, 380),
(255, 381),
(255, 382),
(255, 383),
(255, 384),
(255, 385),
(255, 386),
(255, 387),
(256, 379),
(257, 71),
(257, 394),
(258, 391),
(258, 392),
(258, 393),
(259, 395),
(261, 399),
(261, 400),
(263, 397),
(264, 7),
#(264, 64),
#(264, 236),
(264, 398),
(270, 274),
(270, 381),
(270, 383),
(270, 385),
(270, 407),
(270, 411),
(270, 412),
(270, 413),
(270, 414),
(270, 415),
(270, 419),
(271, 416),
(271, 417),
(274, 423),
(274, 424),
(275, 425),
(275, 426),
(278, 11),
(278, 14),
(278, 24),
(278, 55),
(278, 73),
(278, 194),
(278, 236),
(278, 286),
(282, 313),
(282, 317),
(283, 17),
(283, 20),
(283, 145),
#(283, 311),
#(283, 312),
(283, 313),
(283, 314),
(283, 315),
(283, 316),
(283, 318),
(284, 311),
(284, 320),
(285, 314),
(285, 318),
(286, 315),
(286, 319),
(287, 145),
(287, 314),
(287, 318),
(288, 37),
(288, 38),
(288, 39),
(288, 40),
(288, 41),
#(288, 42),
#(288, 43),
#(288, 44),
#(288, 45),
#(288, 46),
#(288, 47),
(288, 249),
(288, 323),
(288, 324),
(288, 325),
(288, 326),
(288, 327),
(288, 328),
(288, 329),
(288, 330),
(288, 331),
(288, 332),
(288, 333),
(288, 334),
(288, 335),
(288, 336),
(288, 337),
(288, 338),
(288, 339),
(288, 340),
(288, 342),
(288, 343),
(288, 344),
(288, 345),
(288, 346),
(288, 347),
(288, 348),
(288, 349),
(288, 350),
(288, 351),
(288, 352),
(288, 353),
(288, 354),
(288, 355),
(288, 356),
(288, 357),
(288, 401),
(289, 133),
(289, 154),
(289, 270),
(289, 271),
(289, 322),
(293, 14),
(293, 21),
(293, 358),
(293, 359),
(293, 360),
(293, 364),
# (293, 365), removed
(293, 388),
(293, 389),
(293, 390),
(293, 396),
(295, 362),
(295, 363),
(295, 378),
(296, 380),
(296, 381),
(296, 382),
(296, 383),
(296, 384),
(296, 385),
(296, 386),
(296, 387),
(297, 379),
(299, 71),
(299, 394),
(300, 391),
(300, 392),
(300, 393),
(302, 395),
(305, 399),
(305, 400),
(307, 397),
#(308, 7),
#(308, 64),
#(308, 236),
(308, 398),
(310, 344),
(310, 345),
(310, 346),
(310, 347),
(310, 348),
(310, 349),
(310, 350),
(310, 351),
(310, 352),
(310, 353),
(310, 354),
(310, 355),
(310, 356),
(310, 357),
(310, 401),
(311, 364),
(311, 365),
(311, 381),
(311, 383),
(311, 385),
(311, 388),
(311, 389),
(311, 390),
(311, 397),
(319, 402),
(319, 403),
(320, 274),
(320, 381),
(320, 383),
(320, 385),
(320, 407),
(320, 411),
(320, 412),
(320, 413),
(320, 414),
(320, 415),
(320, 419),
(321, 416),
(321, 417),
(323, 408),
(324, 409),
(324, 410),
(324, 418),
(324, 420),
(324, 421),
(325, 423),
(325, 424),
(326, 425),
(326, 426),
(330, 146),
(330, 160),
(330, 316),
(331, 312),
(331, 321),
(332, 361),
(335, 372),
(335, 375),
(336, 366),
(336, 367),
(336, 368),
(336, 369),
(336, 370),
(336, 376),
(336, 377),
(337, 373),
(338, 71),
(338, 72),
(340, 173),
(343, 404),
(344, 405),
(345, 406),
(346, 422),
(353, 422),
(355, 17),
(355, 20),
(355, 145),
#(355, 311),
#(355, 312),
(355, 313),
(355, 314),
(355, 316),
(355, 318),
(356, 311),
(356, 320),
(357, 314),
(357, 318),
(358, 358),
(358, 359),
(358, 360),
(358, 364),
#(358, 365), removed
(358, 388),
(358, 389),
(358, 390),
(358, 396),
(360, 362),
(360, 378),
(361, 361),
(363, 379),
(364, 391),
(364, 392),
(364, 393),
(366, 397),
#(367, 7),
#(367, 64),
#(367, 236),
(367, 398),
(369, 344),
(369, 345),
(369, 346),
(369, 347),
(369, 348),
(369, 349),
(369, 350),
(369, 351),
(369, 352),
(369, 353),
(369, 354),
(369, 355),
(369, 356),
(369, 357),
(369, 401),
(370, 364),
(370, 365),
(370, 381),
(370, 383),
(370, 385),
(370, 388),
(370, 389),
(370, 390),
(370, 397),
(373, 408),
(374, 422);

*/
/*
REPLACE INTO `checks` (`check_id`, `user_id`, `html_tag`, `confidence`, `note`, `name`, `err`, `description`, `search_str`, `long_description`, `rationale`, `how_to_repair`, `repair_example`, `question`, `decision_pass`, `decision_fail`, `test_procedure`, `test_expected_result`, `test_failed_result`, `func`, `open_to_public`, `create_date`) VALUES
(6, 0, 'img', 1, '_NOTE_6', '_CNAME_6', '_ERR_6', '_DESC_6', '_SEARCHSTR_6', NULL, '_RATIONALE_6', '', '', '_QUESTION_6', '_DECISIONPASS_6', '_DECISIONFAIL_6', '_PROCEDURE_6', '_EXPECTEDRESULT_6', '_FAILEDRESULT_6', '$SearchString=BasicFunctions::isAttributeValueInSearchString(''alt'');\r\nreturn !(BasicFunctions::getAttributeValue(''alt'') == ''''|| BasicFunctions::getAttributeValue(''alt'') == $SearchString);', 1, '0000-00-00 00:00:00'),
(7, 0, 'img', 0, '_NOTE_7', '_CNAME_7', '_ERR_7', '_DESC_7', NULL, NULL, '_RATIONALE_7', '_HOWTOREPAIR_7', '', '', '', '', '_PROCEDURE_7', '_EXPECTEDRESULT_7', '_FAILEDRESULT_7', 'return !(BasicFunctions::getParentHTMLTag() == "a" && BasicFunctions::getParentPlainTextLength() == 0 && (!BasicFunctions::hasAttribute(''alt'') || BasicFunctions::getAttributeValue(''alt'') == ""));', 1, '0000-00-00 00:00:00'),
(8, 0, 'img', 2, '', '_CNAME_8', '_ERR_8', '_DESC_8', NULL, NULL, '_RATIONALE_8', '', '', '_QUESTION_8', '_DECISIONPASS_8', '_DECISIONFAIL_8', '_PROCEDURE_8', '_EXPECTEDRESULT_8', '_FAILEDRESULT_8', 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(''src'');\r\n\r\nif (!$width)\r\n   return BasicFunctions::hasAttribute(''longdesc'');\r\nelse\r\n   return !($width > 50 && $height > 50 && !BasicFunctions::hasAttribute(''longdesc''));', 1, '0000-00-00 00:00:00'),
(10, 0, 'img', 2, '_NOTE_10', '_CNAME_10', '_ERR_10', '_DESC_10', NULL, NULL, '_RATIONALE_10', '', '', '_QUESTION_10', '_DECISIONPASS_10', '_DECISIONFAIL_10', '_PROCEDURE_10', '_EXPECTEDRESULT_10', '_FAILEDRESULT_10', 'return !(BasicFunctions::getLast4CharsFromAttributeValue(''src'') == ".gif");', 1, '0000-00-00 00:00:00'),
(17, 0, 'a', 2, '', '_CNAME_17', '_ERR_17', '_DESC_17', NULL, NULL, '', '', '', '_QUESTION_17', '_DECISIONPASS_17', '_DECISIONFAIL_17', '_PROCEDURE_17', '_EXPECTEDRESULT_17', '_FAILEDRESULT_17', '$ext = BasicFunctions::getLast4CharsFromAttributeValue(''href'');\r\n		\r\nreturn !($ext == ".wav" || $ext == ".snd" || $ext == ".mp3" || $ext == ".iff" || $ext == ".svx" || $ext == ".sam" || \r\n         $ext == ".vce" || $ext == ".vox" || $ext == ".pcm" || $ext == ".aif" || $ext == ".smp"); \r\n\r\n\r\n \r\n$extensions = array(".wav", ".snd", ".mp3", ".iff", ".svx", ".sam", ".smp", ".vce", ".vox", ".pcm", ".aif", ".ra");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\nreturn !BasicFunctions::searchElementInArray($ext, $extensions);', 1, '0000-00-00 00:00:00'),
(18, 0, 'a', 1, '', '_CNAME_18', '_ERR_18', '_DESC_18', NULL, NULL, '', '', '', '_QUESTION_18', '_DECISIONPASS_18', '_DECISIONFAIL_18', '_PROCEDURE_18', '_EXPECTEDRESULT_18', '_FAILEDRESULT_18', '$target_val = BasicFunctions::getAttributeValueInLowerCase(''target'');\r\n\r\nreturn (!(BasicFunctions::hasAttribute("onclick") || BasicFunctions::hasAttribute("onkeypress")) || BasicFunctions::hasAttribute(''target'') && ($target_val == "_self" || $target_val == "_top" || $target_val == "_parent"));', 1, '0000-00-00 00:00:00'),
(20, 0, 'a', 1, '', '_CNAME_20', '_ERR_20', '_DESC_20', NULL, NULL, '', '', '', '_QUESTION_20', '_DECISIONPASS_20', '_DECISIONFAIL_20', '_PROCEDURE_20', '_EXPECTEDRESULT_20', '_FAILEDRESULT_20', '$ext = BasicFunctions::getLast4CharsFromAttributeValue(''href'');\r\n\r\nreturn !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif");\r\n\r\n \r\n$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''href''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '0000-00-00 00:00:00'),
(23, 0, 'applet', 0, '', '_CNAME_23', '_ERR_23', '_DESC_23', NULL, NULL, '', '', '', '_QUESTION_23', '_DECISIONPASS_23', '_DECISIONFAIL_23', '_PROCEDURE_23', '_EXPECTEDRESULT_23', '_FAILEDRESULT_23', 'return (BasicFunctions::getAttributeValue(''alt'')<>'''');', 1, '0000-00-00 00:00:00'),
(25, 0, 'applet', 0, '', '_CNAME_25', '_ERR_25', '_DESC_25', NULL, NULL, '', '', '', '_QUESTION_25', '_DECISIONPASS_25', '_DECISIONFAIL_25', '_PROCEDURE_25', '_EXPECTEDRESULT_25', '_FAILEDRESULT_25', 'return (BasicFunctions::getInnerText()<>'''');', 1, '0000-00-00 00:00:00'),
(28, 0, 'body', 1, '', '_CNAME_28', '_ERR_28', '_DESC_28', NULL, NULL, '', '', '', '_QUESTION_28', '_DECISIONPASS_28', '_DECISIONFAIL_28', '_PROCEDURE_28', '_EXPECTEDRESULT_28', '_FAILEDRESULT_28', 'return (BasicFunctions::hasLinkChildWithText(array("%jump%","%go to%","%skip%","%navigation%","%content%")) || BasicFunctions::hasLinkChildWithHref(array("#%")));', 1, '0000-00-00 00:00:00'),
(37, 0, 'h1', 0, '', '_CNAME_37', '_ERR_37', '_DESC_37', NULL, NULL, '', '_HOWTOREPAIR_37', '', '', '', '', '_PROCEDURE_37', '_EXPECTEDRESULT_37', '_FAILEDRESULT_37', 'return BasicFunctions::isPrevTagNotIn(array("h1","h2","h3","h4","h5","h6"));', 1, '0000-00-00 00:00:00'),
(38, 0, 'h2', 0, '', '_CNAME_38', '_ERR_38', '_DESC_38', NULL, NULL, '', '_HOWTOREPAIR_38', '', '', '', '', '_PROCEDURE_38', '_EXPECTEDRESULT_38', '_FAILEDRESULT_38', 'return BasicFunctions::isPrevTagNotIn(array());', 1, '0000-00-00 00:00:00'),
(39, 0, 'h3', 0, '', '_CNAME_39', '_ERR_39', '_DESC_39', NULL, NULL, '', '_HOWTOREPAIR_39', '', '', '', '', '_PROCEDURE_39', '_EXPECTEDRESULT_39', '_FAILEDRESULT_39', 'return BasicFunctions::isPrevTagNotIn(array("h1"));', 1, '0000-00-00 00:00:00'),
(40, 0, 'h4', 0, '', '_CNAME_40', '_ERR_40', '_DESC_40', NULL, NULL, '', '_HOWTOREPAIR_40', '', '', '', '', '_PROCEDURE_40', '_EXPECTEDRESULT_40', '_FAILEDRESULT_40', 'return BasicFunctions::isPrevTagNotIn(array("h1", "h2"));', 1, '0000-00-00 00:00:00'),
(41, 0, 'h5', 0, '', '_CNAME_41', '_ERR_41', '_DESC_41', NULL, NULL, '', '_HOWTOREPAIR_41', '', '', '', '', '_PROCEDURE_41', '_EXPECTEDRESULT_41', '_FAILEDRESULT_41', 'return BasicFunctions::isPrevTagNotIn(array("h1", "h2", "h3"));', 1, '0000-00-00 00:00:00'),
(48, 0, 'html', 0, '', '_CNAME_48', '_ERR_48', '_DESC_48', NULL, NULL, '_RATIONALE_48', '_HOWTOREPAIR_48', '_REPAIREXAMPLE_48', '', '', '', '_PROCEDURE_48', '_EXPECTEDRESULT_48', '_FAILEDRESULT_48', 'return BasicFunctions::isDocumentLanguageDefined();', 1, '0000-00-00 00:00:00'),
(58, 0, 'input', 0, '', '_CNAME_58', '_ERR_58', '_DESC_58', NULL, NULL, '', '_HOWTOREPAIR_58', '', '', '', '', '_PROCEDURE_58', '_EXPECTEDRESULT_58', '_FAILEDRESULT_58', 'return (BasicFunctions::getAttributeValue(''type'') <> ''image'' ||(BasicFunctions::getAttributeValue(''type'')\r\n == ''image'' && BasicFunctions::getAttributeValue(''alt'')<>''''));', 1, '0000-00-00 00:00:00'),
(64, 0, 'area', 0, '', '_CNAME_64', '_ERR_64', '_DESC_64', NULL, NULL, '', '_HOWTOREPAIR_64', '', '', '', '', '_PROCEDURE_64', '_EXPECTEDRESULT_64', '_FAILEDRESULT_64', 'return BasicFunctions::hasNotEmptyAttribute(''alt'');', 1, '0000-00-00 00:00:00'),
(68, 0, 'area', 1, '', '_CNAME_68', '_ERR_68', '_DESC_68', NULL, NULL, '', '', '', '_QUESTION_68', '_DECISIONPASS_68', '_DECISIONFAIL_68', '_PROCEDURE_68', '_EXPECTEDRESULT_68', '_FAILEDRESULT_68', '$target_val = BasicFunctions::getAttributeValueInLowerCase(''target'');\r\n\r\nreturn (!(BasicFunctions::hasAttribute("onclick") || BasicFunctions::hasAttribute("onkeypress")) || BasicFunctions::hasAttribute(''target'') && ($target_val == "_self" || $target_val == "_top" || $target_val == "_parent"));', 1, '0000-00-00 00:00:00'),
(80, 0, 'object', 0, '', '_CNAME_80', '_ERR_80', '_DESC_80', NULL, NULL, '_RATIONALE_80', '', '', '_QUESTION_80', '_DECISIONPASS_80', '_DECISIONFAIL_80', '_PROCEDURE_80', '_EXPECTEDRESULT_80', '_FAILEDRESULT_80', 'return BasicFunctions::objectAltText();', 1, '0000-00-00 00:00:00'),
(92, 0, 'select', 2, '', '_CNAME_92', '_ERR_92', '_DESC_92', NULL, NULL, '', '', '', '_QUESTION_92', '_DECISIONPASS_92', '_DECISIONFAIL_92', '_PROCEDURE_92', '_EXPECTEDRESULT_92', '_FAILEDRESULT_92', 'return !BasicFunctions::hasAttribute(''onchange'');', 1, '0000-00-00 00:00:00'),
(145, 0, 'a', 1, '', '_CNAME_145', '_ERR_145', '_DESC_145', NULL, NULL, '', '', '', '_QUESTION_145', '_DECISIONPASS_145', '_DECISIONFAIL_145', '_PROCEDURE_145', '_EXPECTEDRESULT_145', '_FAILEDRESULT_145', '$ext = BasicFunctions::getLast4CharsFromAttributeValue(''href'');\r\n\r\nreturn !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif");\r\n\r\n\r\n \r\n$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''href''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '0000-00-00 00:00:00'),
(146, 0, 'object', 2, '', '_CNAME_146', '_ERR_146', '_DESC_146', NULL, NULL, '', '', '', '_QUESTION_146', '_DECISIONPASS_146', '_DECISIONFAIL_146', '_PROCEDURE_146', '_EXPECTEDRESULT_146', '_FAILEDRESULT_146', ' return !BasicFunctions::getAttributeValueInLowerCase(''type'') == "video"); \r\n\r\n \r\n$type = BasicFunctions::getAttributeValueInLowerCase(''type'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''data''));\r\n\r\n$notallowed = array("video%", "application%");\r\n\r\nreturn !($youtube || BasicChecks::inSearchString($type, $notallowed));', 1, '0000-00-00 00:00:00'),
(160, 0, 'object', 2, '', '_CNAME_160', '_ERR_160', '_DESC_160', NULL, NULL, '', '', '', '_QUESTION_160', '_DECISIONPASS_160', '_DECISIONFAIL_160', '_PROCEDURE_160', '_EXPECTEDRESULT_160', '_FAILEDRESULT_160', 'return false;\r\n\r\n \r\nreturn BasicFunctions::getInnerTextLength() == 0 ? false : true;', 1, '0000-00-00 00:00:00'),
(168, 0, 'form', 0, '', '_CNAME_168', '_ERR_168', '_DESC_168', NULL, NULL, '', '_HOWTOREPAIR_168', '', '', '', '', '_PROCEDURE_168', '_EXPECTEDRESULT_168', '_FAILEDRESULT_168', 'return BasicFunctions::isRadioButtonsGrouped(); \r\n\r\n \r\nreturn BasicFunctions::areButtonsGrouped("radio");', 1, '0000-00-00 00:00:00'),
(236, 0, 'a', 0, '', '_CNAME_236', '_ERR_236', '_DESC_236', NULL, NULL, '', '_HOWTOREPAIR_236', '_REPAIREXAMPLE_236', '', '', '', '_PROCEDURE_236', '_EXPECTEDRESULT_236', '_FAILEDRESULT_236', 'return BasicFunctions::adjacentDestination();', 1, '0000-00-00 00:00:00'),
(239, 0, 'img', 0, '', '_CNAME_239', '_ERR_239', '_DESC_239', NULL, NULL, '', '', '', '_QUESTION_239', '_DECISIONPASS_239', '_DECISIONFAIL_239', '_PROCEDURE_239', '_EXPECTEDRESULT_239', '_FAILEDRESULT_239', 'if(BasicFunctions::getAttributeValue(''alt'') == "" && BasicFunctions::hasAttribute(''title'') && BasicFunctions::getAttributeValue(''title'') <> "")\r\nreturn false;\r\nelse\r\nreturn true;\r\n', 1, '0000-00-00 00:00:00'),
(241, 0, 'body', 2, '', '_CNAME_241', '_ERR_241', '_DESC_241', NULL, NULL, '', '', '', '_QUESTION_241', '_DECISIONPASS_241', '_DECISIONFAIL_241', '_PROCEDURE_241', '_EXPECTEDRESULT_241', '_FAILEDRESULT_241', ' return (BasicFunctions::getPlainTextLength() 21 || !BasicFunctions::hasTabularInfo()); \r\n\r\n \r\nreturn !BasicFunctions::hasTabularInfo();', 1, '0000-00-00 00:00:00'),
(247, 0, 'form', 0, '', '_CNAME_247', '_ERR_247', '_DESC_247', NULL, NULL, '', '_HOWTOREPAIR_247', '', '', '', '', '_PROCEDURE_247', '_EXPECTEDRESULT_247', '_FAILEDRESULT_247', ' return BasicFunctions::hasFieldsetOnMultiCheckbox(); \r\n\r\n \r\nreturn BasicFunctions::areButtonsGrouped("checkbox");', 1, '0000-00-00 00:00:00'),
(248, 0, 'body', 2, '', '_CNAME_248', '_ERR_248', '_DESC_248', NULL, NULL, '', '', '', '_QUESTION_248', '_DECISIONPASS_248', '_DECISIONFAIL_248', '_PROCEDURE_248', '_EXPECTEDRESULT_248', '_FAILEDRESULT_248', ' return (BasicFunctions::getPlainTextLength() 31); \r\n\r\n \r\nreturn false;', 1, '0000-00-00 00:00:00'),
(249, 0, 'body', 2, '', '_CNAME_249', '_ERR_249', '_DESC_249', NULL, NULL, '', '', '', '_QUESTION_249', '_DECISIONPASS_249', '_DECISIONFAIL_249', '_PROCEDURE_249', '_EXPECTEDRESULT_249', '_FAILEDRESULT_249', ' return (BasicFunctions::getPlainTextLength() 51); \r\n\r\n \r\nreturn false;', 1, '0000-00-00 00:00:00'),
(250, 0, 'body', 2, '', '_CNAME_250', '_ERR_250', '_DESC_250', NULL, NULL, '', '', '', '_QUESTION_250', '_DECISIONPASS_250', '_DECISIONFAIL_250', '_PROCEDURE_250', '_EXPECTEDRESULT_250', '_FAILEDRESULT_250', ' return (BasicFunctions::getPlainTextLength() 21); \r\n\r\n \r\nreturn false;', 1, '0000-00-00 00:00:00'),
(274, 0, 'a', 2, '', '_CNAME_274', '_ERR_274', '_DESC_274', NULL, NULL, '', '', '', '_QUESTION_274', '_DECISIONPASS_274', '_DECISIONFAIL_274', '_PROCEDURE_274', '_EXPECTEDRESULT_274', '_FAILEDRESULT_274', 'return !(BasicFunctions::hasAttribute("onmouseover") || \r\n         BasicFunctions::hasAttribute("onmouseout") || \r\n         BasicFunctions::hasAttribute("onfocus") || \r\n         BasicFunctions::hasAttribute("onblur") || \r\n         BasicFunctions::hasAttribute("onchange"));', 1, '0000-00-00 00:00:00'),
(66, 0, 'area', 1, '', '_CNAME_66', '_ERR_66', '_DESC_66', NULL, NULL, '', '', '', '_QUESTION_66', '_DECISIONPASS_66', '_DECISIONFAIL_66', '_PROCEDURE_66', '_EXPECTEDRESULT_66', '_FAILEDRESULT_66', '$ext = BasicFunctions::getLast4CharsFromAttributeValue(''href'');\r\n		\r\nreturn !($ext == ".wav" || $ext == ".snd" || $ext == ".mp3" || $ext == ".iff" || $ext == ".svx" || $ext == ".sam" || \r\n         $ext == ".vce" || $ext == ".vox" || $ext == ".pcm" || $ext == ".aif" || $ext == ".smp"); \r\n\r\n\r\n \r\n$extensions = array(".wav", ".snd", ".mp3", ".iff", ".svx", ".sam", ".smp", ".vce", ".vox", ".pcm", ".aif", ".ra");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\nreturn !BasicFunctions::searchElementInArray($ext, $extensions);', 1, '0000-00-00 00:00:00'),
(11, 0, 'img', 2, '', '_CNAME_11', '_ERR_11', '_DESC_11', NULL, NULL, '', '', '', '_QUESTION_11', '_DECISIONPASS_11', '_DECISIONFAIL_11', '_PROCEDURE_11', '_EXPECTEDRESULT_11', '_FAILEDRESULT_11', 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(''src'');\r\n\r\nif (!$width)\r\n   return  false;\r\nelse\r\n   return !($width > 50 || $height > 50);', 1, '0000-00-00 00:00:00'),
(76, 0, 'object', 2, '', '_CNAME_76', '_ERR_76', '_DESC_76', NULL, NULL, '', '', '', '_QUESTION_76', '_DECISIONPASS_76', '_DECISIONFAIL_76', '_PROCEDURE_76', '_EXPECTEDRESULT_76', '_FAILEDRESULT_76', '\r\nreturn (!BasicFunctions::hasAttribute(''codebase'') || BasicFunctions::getAttributeValue(''codebase'') == "");\r\n\r\nreturn false;', 1, '0000-00-00 00:00:00');

*/

#####
# New checks added by UNIBO in AChecker 1.4
#####
INSERT INTO `checks` VALUES (286, 0, 'pre', 2, NULL, '_CNAME_286', '_ERR_286', '_DESC_286', NULL, NULL, NULL, NULL, NULL, '_QUESTION_286', '_DECISIONPASS_286', '_DECISIONFAIL_286', '_PROCEDURE_286', '_EXPECTEDRESULT_286', '_FAILEDRESULT_286', 'return false;', 1, '2010-10-05 23:10:32'); 
INSERT INTO `checks` VALUES (311, 0, 'a', 2, NULL, '_CNAME_311', '_ERR_311', '_DESC_311', NULL, NULL, NULL, NULL, NULL, '_QUESTION_311', '_DECISIONPASS_311', '_DECISIONFAIL_311', '_PROCEDURE_311', '_EXPECTEDRESULT_311', '_FAILEDRESULT_311', 'return false;', 1, '2011-02-10 14:50:44');
INSERT INTO `checks` VALUES (312, 0, 'a', 2, NULL, '_CNAME_312', '_ERR_312', '_DESC_312', NULL, NULL, NULL, NULL, NULL, '_QUESTION_312', '_DECISIONPASS_312', '_DECISIONFAIL_312', '_PROCEDURE_312', '_EXPECTEDRESULT_312', '_FAILEDRESULT_312', 'return false;', 1, '2011-02-10 15:03:04');
INSERT INTO `checks` VALUES (313, 0, 'a', 2, NULL, '_CNAME_313', '_ERR_313', '_DESC_313', NULL, NULL, NULL, NULL, NULL, '_QUESTION_313', '_DECISIONPASS_313', '_DECISIONFAIL_313', '_PROCEDURE_313', '_EXPECTEDRESULT_313', '_FAILEDRESULT_313', '$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx", ".wav", ".snd", ".mp3", ".iff", ".svx", ".sam", ".smp", ".vce", ".vox", ".pcm", ".ra");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''href''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '2011-02-10 15:11:53');
INSERT INTO `checks` VALUES (314, 0, 'a', 2, NULL, '_CNAME_314', '_ERR_314', '_DESC_314', NULL, NULL, NULL, NULL, NULL, '_QUESTION_314', '_DECISIONPASS_314', '_DECISIONFAIL_314', '_PROCEDURE_314', '_EXPECTEDRESULT_314', '_FAILEDRESULT_314', '$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''href''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '2011-02-10 15:17:05');
INSERT INTO `checks` VALUES (315, 0, 'a', 2, NULL, '_CNAME_315', '_ERR_315', '_DESC_315', NULL, NULL, NULL, NULL, NULL, '_QUESTION_315', '_DECISIONPASS_315', '_DECISIONFAIL_315', '_PROCEDURE_315', '_EXPECTEDRESULT_315', '_FAILEDRESULT_315', '$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx", ".wav", ".snd", ".mp3", ".iff", ".svx", ".sam", ".smp", ".vce", ".vox", ".pcm", ".ra");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''href'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''href''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '2011-02-10 15:23:26');
INSERT INTO `checks` VALUES (316, 0, 'object', 2, NULL, '_CNAME_316', '_ERR_316', '_DESC_316', NULL, NULL, NULL, NULL, NULL, '_QUESTION_316', '_DECISIONPASS_316', '_DECISIONFAIL_316', '_PROCEDURE_316', '_EXPECTEDRESULT_316', '_FAILEDRESULT_316', 'return BasicFunctions::getInnerTextLength() > 0 ? false : true;', 1, '2011-02-10 16:49:02');
INSERT INTO `checks` VALUES (317, 0, 'object', 2, NULL, '_CNAME_317', '_ERR_317', '_DESC_317', NULL, NULL, NULL, NULL, NULL, '_QUESTION_317', '_DECISIONPASS_317', '_DECISIONFAIL_317', '_PROCEDURE_317', '_EXPECTEDRESULT_317', '_FAILEDRESULT_317', '$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx", ".wav", ".snd", ".mp3", ".iff", ".svx", ".sam", ".smp", ".vce", ".vox", ".pcm", ".ra");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''data'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''data''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '2011-02-10 16:57:27');
INSERT INTO `checks` VALUES (318, 0, 'object', 2, NULL, '_CNAME_318', '_ERR_318', '_DESC_318', NULL, NULL, NULL, NULL, NULL, '_QUESTION_318', '_DECISIONPASS_318', '_DECISIONFAIL_318', '_PROCEDURE_318', '_EXPECTEDRESULT_318', '_FAILEDRESULT_318', '$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''data'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''data''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '2011-02-10 17:03:44');
INSERT INTO `checks` VALUES (319, 0, 'object', 2, NULL, '_CNAME_319', '_ERR_319', '_DESC_319', NULL, NULL, NULL, NULL, NULL, '_QUESTION_319', '_DECISIONPASS_319', '_DECISIONFAIL_319', '_PROCEDURE_319', '_EXPECTEDRESULT_319', '_FAILEDRESULT_319', '$extensions = array(".wmv", ".mpg", ".mov", ".ram", ".aif", ".avi", ".mp4", ".flv", ".smil", ".mpeg", ".divx", ".wav", ".snd", ".mp3", ".iff", ".svx", ".sam", ".smp", ".vce", ".vox", ".pcm", ".ra");\r\n\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''data'');\r\n\r\n$youtube = BasicFunctions::searchWordInHostOfGivenUrl("youtube", BasicFunctions::getAttributeValueInLowerCase(''data''));\r\n\r\nreturn !(BasicFunctions::searchElementInArray($ext, $extensions) || $youtube);', 1, '2011-02-10 17:17:19');
INSERT INTO `checks` VALUES (320, 0, 'object', 2, NULL, '_CNAME_320', '_ERR_320', '_DESC_320', NULL, NULL, NULL, NULL, NULL, '_QUESTION_320', '_DECISIONPASS_320', '_DECISIONFAIL_320', '_PROCEDURE_320', '_EXPECTEDRESULT_320', '_FAILEDRESULT_320', 'return false;', 1, '2011-02-10 17:21:25');
INSERT INTO `checks` VALUES (321, 0, 'object', 2, NULL, '_CNAME_321', '_ERR_321', '_DESC_321', NULL, NULL, NULL, NULL, NULL, '_QUESTION_321', '_DECISIONPASS_321', '_DECISIONFAIL_321', '_PROCEDURE_321', '_EXPECTEDRESULT_321', '_FAILEDRESULT_321', 'return false;', 1, '2011-02-10 17:26:55');
INSERT INTO `checks` VALUES (322, 0, 'body', 2, NULL, '_CNAME_322', '_ERR_322', '_DESC_322', NULL, NULL, NULL, NULL, NULL, '_QUESTION_322', '_DECISIONPASS_322', '_DECISIONFAIL_322', '_PROCEDURE_322', '_EXPECTEDRESULT_322', '_FAILEDRESULT_322', 'return false;', 1, '2011-02-14 09:10:28');
INSERT INTO `checks` VALUES (323, 0, 'body', 2, NULL, '_CNAME_323', '_ERR_323', '_DESC_323', NULL, NULL, NULL, NULL, NULL, '_QUESTION_323', '_DECISIONPASS_323', '_DECISIONFAIL_323', '_PROCEDURE_323', '_EXPECTEDRESULT_323', '_FAILEDRESULT_323', 'return false;', 1, '2011-02-14 09:48:48');
INSERT INTO `checks` VALUES (324, 0, 'body', 2, NULL, '_CNAME_324', '_ERR_324', '_DESC_324', NULL, NULL, NULL, NULL, NULL, '_QUESTION_324', '_DECISIONPASS_324', '_DECISIONFAIL_324', '_PROCEDURE_324', '_EXPECTEDRESULT_324', '_FAILEDRESULT_324', 'return !(BasicFunctions::hasTagInChildren(''code''));', 1, '2011-02-14 10:13:48');
INSERT INTO `checks` VALUES (325, 0, 'body', 2, NULL, '_CNAME_325', '_ERR_325', '_DESC_325', NULL, NULL, NULL, NULL, NULL, '_QUESTION_325', '_DECISIONPASS_325', '_DECISIONFAIL_325', '_PROCEDURE_325', '_EXPECTEDRESULT_325', '_FAILEDRESULT_325', 'return !(BasicFunctions::hasTagInChildren(''del''));', 1, '2011-02-14 10:14:00');
INSERT INTO `checks` VALUES (326, 0, 'body', 2, NULL, '_CNAME_326', '_ERR_326', '_DESC_326', NULL, NULL, NULL, NULL, NULL, '_QUESTION_326', '_DECISIONPASS_326', '_DECISIONFAIL_326', '_PROCEDURE_326', '_EXPECTEDRESULT_326', '_FAILEDRESULT_326', 'return !(BasicFunctions::hasTagInChildren(''ins''));', 1, '2011-02-14 10:20:11');
INSERT INTO `checks` VALUES (327, 0, 'body', 2, NULL, '_CNAME_327', '_ERR_327', '_DESC_327', NULL, NULL, NULL, NULL, NULL, '_QUESTION_327', '_DECISIONPASS_327', '_DECISIONFAIL_327', '_PROCEDURE_327', '_EXPECTEDRESULT_327', '_FAILEDRESULT_327', 'return !(BasicFunctions::hasTagInChildren(''dfn''));', 1, '2011-02-14 10:27:21');
INSERT INTO `checks` VALUES (328, 0, 'body', 2, NULL, '_CNAME_328', '_ERR_328', '_DESC_328', NULL, NULL, NULL, NULL, NULL, '_QUESTION_328', '_DECISIONPASS_328', '_DECISIONFAIL_328', '_PROCEDURE_328', '_EXPECTEDRESULT_328', '_FAILEDRESULT_328', 'return !(BasicFunctions::hasTagInChildren(''kbd''));', 1, '2011-02-14 10:31:17');
INSERT INTO `checks` VALUES (329, 0, 'body', 2, NULL, '_CNAME_329', '_ERR_329', '_DESC_329', NULL, NULL, NULL, NULL, NULL, '_QUESTION_329', '_DECISIONPASS_329', '_DECISIONFAIL_329', '_PROCEDURE_329', '_EXPECTEDRESULT_329', '_FAILEDRESULT_329', 'return !(BasicFunctions::hasTagInChildren(''s''));', 1, '2011-02-14 10:37:18');
INSERT INTO `checks` VALUES (330, 0, 'body', 2, NULL, '_CNAME_330', '_ERR_330', '_DESC_330', NULL, NULL, NULL, NULL, NULL, '_QUESTION_330', '_DECISIONPASS_330', '_DECISIONFAIL_330', '_PROCEDURE_330', '_EXPECTEDRESULT_330', '_FAILEDRESULT_330', 'return !(BasicFunctions::hasTagInChildren(''sub''));', 1, '2011-02-14 10:40:57');
INSERT INTO `checks` VALUES (331, 0, 'body', 2, NULL, '_CNAME_331', '_ERR_331', '_DESC_331', NULL, NULL, NULL, NULL, NULL, '_QUESTION_331', '_DECISIONPASS_331', '_DECISIONFAIL_331', '_PROCEDURE_331', '_EXPECTEDRESULT_331', '_FAILEDRESULT_331', 'return !(BasicFunctions::hasTagInChildren(''sup''));', 1, '2011-02-14 10:44:40');
INSERT INTO `checks` VALUES (332, 0, 'body', 2, NULL, '_CNAME_332', '_ERR_332', '_DESC_332', NULL, NULL, NULL, NULL, NULL, '_QUESTION_332', '_DECISIONPASS_332', '_DECISIONFAIL_332', '_PROCEDURE_332', '_EXPECTEDRESULT_332', '_FAILEDRESULT_332', 'return !(BasicFunctions::hasTagInChildren(''tt''));', 1, '2011-02-14 10:52:51');
INSERT INTO `checks` VALUES (333, 0, 'body', 2, NULL, '_CNAME_333', '_ERR_333', '_DESC_333', NULL, NULL, NULL, NULL, NULL, '_QUESTION_333', '_DECISIONPASS_333', '_DECISIONFAIL_333', '_PROCEDURE_333', '_EXPECTEDRESULT_333', '_FAILEDRESULT_333', 'return !(BasicFunctions::hasTagInChildren(''q''));', 1, '2011-02-14 10:55:41');
INSERT INTO `checks` VALUES (334, 0, 'body', 2, NULL, '_CNAME_334', '_ERR_334', '_DESC_334', NULL, NULL, NULL, NULL, NULL, '_QUESTION_334', '_DECISIONPASS_334', '_DECISIONFAIL_334', '_PROCEDURE_334', '_EXPECTEDRESULT_334', '_FAILEDRESULT_334', 'return !(BasicFunctions::hasTagInChildren(''em''));', 1, '2011-02-14 10:59:42');
INSERT INTO `checks` VALUES (335, 0, 'body', 2, NULL, '_CNAME_335', '_ERR_335', '_DESC_335', NULL, NULL, NULL, NULL, NULL, '_QUESTION_335', '_DECISIONPASS_335', '_DECISIONFAIL_335', '_PROCEDURE_335', '_EXPECTEDRESULT_335', '_FAILEDRESULT_335', 'return !(BasicFunctions::hasTagInChildren(''strong''));', 1, '2011-02-14 11:05:04');
INSERT INTO `checks` VALUES (336, 0, 'body', 2, NULL, '_CNAME_336', '_ERR_336', '_DESC_336', NULL, NULL, NULL, NULL, NULL, '_QUESTION_336', '_DECISIONPASS_336', '_DECISIONFAIL_336', '_PROCEDURE_336', '_EXPECTEDRESULT_336', '_FAILEDRESULT_336', 'return !(BasicFunctions::hasTagInChildren(''b''));', 1, '2011-02-14 11:07:44');
INSERT INTO `checks` VALUES (337, 0, 'body', 2, NULL, '_CNAME_337', '_ERR_337', '_DESC_337', NULL, NULL, NULL, NULL, NULL, '_QUESTION_337', '_DECISIONPASS_337', '_DECISIONFAIL_337', '_PROCEDURE_337', '_EXPECTEDRESULT_337', '_FAILEDRESULT_337', 'return !(BasicFunctions::hasTagInChildren(''i''));', 1, '2011-02-14 11:10:38');
INSERT INTO `checks` VALUES (338, 0, 'body', 2, NULL, '_CNAME_338', '_ERR_338', '_DESC_338', NULL, NULL, NULL, NULL, NULL, '_QUESTION_338', '_DECISIONPASS_338', '_DECISIONFAIL_338', '_PROCEDURE_338', '_EXPECTEDRESULT_338', '_FAILEDRESULT_338', 'return !(BasicFunctions::hasTagInChildren(''cite''));', 1, '2011-02-14 11:14:07');
INSERT INTO `checks` VALUES (339, 0, 'body', 2, NULL, '_CNAME_339', '_ERR_339', '_DESC_339', NULL, NULL, NULL, NULL, NULL, '_QUESTION_339', '_DECISIONPASS_339', '_DECISIONFAIL_339', '_PROCEDURE_339', '_EXPECTEDRESULT_339', '_FAILEDRESULT_339', 'return !(BasicFunctions::hasTagInChildren(''blockquote''));', 1, '2011-02-14 11:18:32');
INSERT INTO `checks` VALUES (340, 0, 'body', 2, NULL, '_CNAME_340', '_ERR_340', '_DESC_340', NULL, NULL, NULL, NULL, NULL, '_QUESTION_340', '_DECISIONPASS_340', '_DECISIONFAIL_340', '_PROCEDURE_340', '_EXPECTEDRESULT_340', '_FAILEDRESULT_340', 'return false;', 1, '2011-02-14 13:16:50');
INSERT INTO `checks` VALUES (342, 0, 'select', 2, NULL, '_CNAME_342', '_ERR_342', '_DESC_342', NULL, NULL, NULL, NULL, NULL, '_QUESTION_342', '_DECISIONPASS_342', '_DECISIONFAIL_342', '_PROCEDURE_342', '_EXPECTEDRESULT_342', '_FAILEDRESULT_342', 'return BasicFunctions::hasTagInChildren(''optgroup'');', 1, '2011-02-16 12:20:59');
INSERT INTO `checks` VALUES (343, 0, 'label', 0, NULL, '_CNAME_343', '_ERR_343', '_DESC_343', NULL, NULL, NULL, '_HOWTOREPAIR_343', NULL, NULL, NULL, NULL, '_PROCEDURE_343', '_EXPECTEDRESULT_343', '_FAILEDRESULT_343', 'return BasicFunctions::isLabelAssociated();', 1, '2011-02-17 18:16:24');
INSERT INTO `checks` VALUES (344, 0, 'input', 0, NULL, '_CNAME_344', '_ERR_344', '_DESC_344', NULL, NULL, NULL, '_HOWTOREPAIR_344', NULL, NULL, NULL, NULL, '_PROCEDURE_344', '_EXPECTEDRESULT_344', '_FAILEDRESULT_344', 'if (BasicFunctions::getAttributeValue(''type'') != "text")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:07');
INSERT INTO `checks` VALUES (345, 0, 'input', 0, NULL, '_CNAME_345', '_ERR_345', '_DESC_345', NULL, NULL, NULL, '_HOWTOREPAIR_345', NULL, NULL, NULL, NULL, '_PROCEDURE_345', '_EXPECTEDRESULT_345', '_FAILEDRESULT_345', 'if (BasicFunctions::getAttributeValue(''type'') != "password")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:11');
INSERT INTO `checks` VALUES (346, 0, 'input', 0, NULL, '_CNAME_346', '_ERR_346', '_DESC_346', NULL, NULL, NULL, '_HOWTOREPAIR_346', NULL, NULL, NULL, NULL, '_PROCEDURE_346', '_EXPECTEDRESULT_346', '_FAILEDRESULT_346', 'if (BasicFunctions::getAttributeValue(''type'') != "checkbox")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:15');
INSERT INTO `checks` VALUES (347, 0, 'input', 0, NULL, '_CNAME_347', '_ERR_347', '_DESC_347', NULL, NULL, NULL, '_HOWTOREPAIR_347', NULL, NULL, NULL, NULL, '_PROCEDURE_347', '_EXPECTEDRESULT_347', '_FAILEDRESULT_347', 'if (BasicFunctions::getAttributeValue(''type'') != "radio")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:18');
INSERT INTO `checks` VALUES (348, 0, 'input', 0, NULL, '_CNAME_348', '_ERR_348', '_DESC_348', NULL, NULL, NULL, '_HOWTOREPAIR_348', NULL, NULL, NULL, NULL, '_PROCEDURE_348', '_EXPECTEDRESULT_348', '_FAILEDRESULT_348', 'if (BasicFunctions::getAttributeValue(''type'') != "file")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:30');
INSERT INTO `checks` VALUES (349, 0, 'select', 0, NULL, '_CNAME_349', '_ERR_349', '_DESC_349', NULL, NULL, NULL, '_HOWTOREPAIR_349', NULL, NULL, NULL, NULL, '_PROCEDURE_349', '_EXPECTEDRESULT_349', '_FAILEDRESULT_349', '\r\nif(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:33');
INSERT INTO `checks` VALUES (350, 0, 'textarea', 0, NULL, '_CNAME_350', '_ERR_350', '_DESC_350', NULL, NULL, NULL, '_HOWTOREPAIR_350', NULL, NULL, NULL, NULL, '_PROCEDURE_350', '_EXPECTEDRESULT_350', '_FAILEDRESULT_350', '\r\nif(!BasicFunctions::hasExplicitlyAssociatedLabel() && !BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 09:55:37');
INSERT INTO `checks` VALUES (351, 0, 'input', 2, NULL, '_CNAME_351', '_ERR_351', '_DESC_351', NULL, NULL, NULL, NULL, NULL, '_QUESTION_351', '_DECISIONPASS_351', '_DECISIONFAIL_351', '_PROCEDURE_351', '_EXPECTEDRESULT_351', '_FAILEDRESULT_351', 'if (BasicFunctions::getAttributeValue(''type'') != "text")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 11:35:11');
INSERT INTO `checks` VALUES (352, 0, 'input', 2, NULL, '_CNAME_352', '_ERR_352', '_DESC_352', NULL, NULL, NULL, NULL, NULL, '_QUESTION_352', '_DECISIONPASS_352', '_DECISIONFAIL_352', '_PROCEDURE_352', '_EXPECTEDRESULT_352', '_FAILEDRESULT_352', 'if (BasicFunctions::getAttributeValue(''type'') != "password")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 11:55:03');
INSERT INTO `checks` VALUES (353, 0, 'input', 2, NULL, '_CNAME_353', '_ERR_353', '_DESC_353', NULL, NULL, NULL, NULL, NULL, '_QUESTION_353', '_DECISIONPASS_353', '_DECISIONFAIL_353', '_PROCEDURE_353', '_EXPECTEDRESULT_353', '_FAILEDRESULT_353', 'if (BasicFunctions::getAttributeValue(''type'') != "checkbox")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 12:00:40');
INSERT INTO `checks` VALUES (354, 0, 'input', 2, NULL, '_CNAME_354', '_ERR_354', '_DESC_354', NULL, NULL, NULL, NULL, NULL, '_QUESTION_354', '_DECISIONPASS_354', '_DECISIONFAIL_354', '_PROCEDURE_354', '_EXPECTEDRESULT_354', '_FAILEDRESULT_354', 'if (BasicFunctions::getAttributeValue(''type'') != "radio")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 12:02:07');
INSERT INTO `checks` VALUES (355, 0, 'input', 2, NULL, '_CNAME_355', '_ERR_355', '_DESC_355', NULL, NULL, NULL, NULL, NULL, '_QUESTION_355', '_DECISIONPASS_355', '_DECISIONFAIL_355', '_PROCEDURE_355', '_EXPECTEDRESULT_355', '_FAILEDRESULT_355', 'if (BasicFunctions::getAttributeValue(''type'') != "file")\r\n   return true;\r\nelse if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 12:06:07');
INSERT INTO `checks` VALUES (356, 0, 'select', 2, NULL, '_CNAME_356', '_ERR_356', '_DESC_356', NULL, NULL, NULL, NULL, NULL, '_QUESTION_356', '_DECISIONPASS_356', '_DECISIONFAIL_356', '_PROCEDURE_356', '_EXPECTEDRESULT_356', '_FAILEDRESULT_356', 'if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 12:08:24');
INSERT INTO `checks` VALUES (357, 0, 'textarea', 2, NULL, '_CNAME_357', '_ERR_357', '_DESC_357', NULL, NULL, NULL, NULL, NULL, '_QUESTION_357', '_DECISIONPASS_357', '_DECISIONFAIL_357', '_PROCEDURE_357', '_EXPECTEDRESULT_357', '_FAILEDRESULT_357', 'if(!BasicFunctions::hasExplicitlyAssociatedLabel() && BasicFunctions::hasAttribute("title"))\r\n   return false;\r\nelse\r\n   return true;', 1, '2011-02-18 12:10:43');
INSERT INTO `checks` VALUES (358, 0, 'a', 0, NULL, '_CNAME_358', '_ERR_358', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkSurroundingLinkTextColorDifference(''link'');', 1, '2011-04-07 17:48:16');
INSERT INTO `checks` VALUES (359, 0, 'a', 0, NULL, '_CNAME_359', '_ERR_359', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkSurroundingLinkTextColorDifference(''visited'');', 1, '2011-04-12 18:08:15');
INSERT INTO `checks` VALUES (360, 0, 'a', 0, NULL, '_CNAME_360', '_ERR_360', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return(BasicFunctions::checkSurroundedLinkPseudoClassEffectWCAG2A(''focus'') && BasicFunctions::checkSurroundedLinkPseudoClassEffectWCAG2A(''focus''));', 1, '2011-04-12 18:14:21');
INSERT INTO `checks` VALUES (361, 0, 'all elements', 2, NULL, '_CNAME_361', '_ERR_361', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkResizable();', 1, '2011-05-10 00:00:00');
INSERT INTO `checks` VALUES (362, 0, 'all elements', 2, NULL, '_CNAME_362', '_ERR_362', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkBackgroundImageConctrastWCAG2();', 1, '2011-04-12 22:08:06');
INSERT INTO `checks` VALUES (363, 0, 'all elements', 2, NULL, '_CNAME_363', '_ERR_363', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkBackgroundImageConctrastWCAG2();', 1, '2011-04-12 22:25:11');
INSERT INTO `checks` VALUES (364,0, 'a',1, NULL, '_CNAME_364', '_ERR_364', NULL, NULL, NULL, NULL, NULL, NULL, '_QUESTION_364', '_DECISIONPASS_364', '_DECISIONFAIL_364', NULL, NULL, NULL, 'return BasicFunctions::checkLinkPseudoClassEffectWCAG2A(''focus'');',1, '2011-04-19 17:19:49');
INSERT INTO `checks` VALUES (365,0, 'input',1, NULL, '_CNAME_365', '_ERR_365', NULL, NULL, NULL, NULL, NULL, NULL, '_QUESTION_365', '_DECISIONPASS_365', '_DECISIONFAIL_365', NULL, NULL, NULL, 'return BasicFunctions::checkInputPseudoClassEffectWCAG2A(''focus'');',1, '2011-04-19 23:44:24');
INSERT INTO `checks` VALUES (366, 0, 'all elements', 2, NULL, '_CNAME_366', '_ERR_366', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkColorSelection();', 1, '2011-05-03 20:07:37');
INSERT INTO `checks` VALUES (367, 0, 'all elements', 2, NULL, '_CNAME_367', '_ERR_367', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkRelativeWidth();', 1, '2011-05-03 23:34:06');
INSERT INTO `checks` VALUES (368, 0, 'all elements', 0, NULL, '_CNAME_368', '_ERR_368', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkJustified();', 1, '2011-05-04 01:03:05');
INSERT INTO `checks` VALUES (369, 0, 'p', 2, NULL, '_CNAME_369', '_ERR_369', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkLineHeight();', 1, '2011-05-04 01:30:24');
INSERT INTO `checks` VALUES (370, 0, 'all elements', 2, NULL, '_CNAME_370', '_ERR_370', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::checkResizable();', 1, '2011-05-10 22:58:22');
INSERT INTO `checks` VALUES (371, 0, 'applet', 2, NULL, '_CNAME_371', '_ERR_371', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-10 23:34:58');
INSERT INTO `checks` VALUES (372, 0, 'applet', 2, NULL, '_CNAME_372', '_ERR_372', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-10 23:38:26');
INSERT INTO `checks` VALUES (373, 0, 'img', 2, NULL, '_CNAME_373', '_ERR_373', '_DESC_373', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'list($width, $height) = BasicFunctions::getImageWidthAndHeight(''src'');\r\n\r\nif (!$width)\r\n   return  false;\r\nelse\r\n   return !($width > 50 && $height > 50);', 1, '2011-05-11 01:34:50');
INSERT INTO `checks` VALUES (374, 0, 'object', 2, NULL, '_CNAME_374', '_ERR_374', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-11 01:40:58');
INSERT INTO `checks` VALUES (375, 0, 'object', 2, NULL, '_CNAME_375', '_ERR_375', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-11 01:42:09');
INSERT INTO `checks` VALUES (376, 0, 'body', 2, NULL, '_CNAME_376', '_ERR_376', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-24 12:07:33');
INSERT INTO `checks` VALUES (377, 0, 'body', 2, NULL, '_CNAME_377', '_ERR_377', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-24 12:18:50');
INSERT INTO `checks` VALUES (378, 0, 'body', 2, NULL, '_CNAME_378', '_ERR_378', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-24 12:24:21');
INSERT INTO `checks` VALUES (379, 0, 'script', 2, NULL, '_CNAME_379', '_ERR_379', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-24 12:29:30');
INSERT INTO `checks` VALUES (380, 0, 'a', 2, NULL, '_CNAME_380', '_ERR_380', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::onClickHandler();', 1, '2011-05-24 12:43:17');
INSERT INTO `checks` VALUES (381, 0, 'a', 0, NULL, '_CNAME_381', '_ERR_381', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::onFocusHandler();', 1, '2011-05-25 01:16:40');
INSERT INTO `checks` VALUES (382, 0, 'input', 2, NULL, '_CNAME_382', '_ERR_382', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::onClickHandler();', 1, '2011-05-25 01:19:06');
INSERT INTO `checks` VALUES (383, 0, 'input', 0, NULL, '_CNAME_383', '_ERR_383', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::onFocusHandler();', 1, '2011-05-25 01:20:25');
INSERT INTO `checks` VALUES (384, 0, 'button', 2, NULL, '_CNAME_384', '_ERR_384', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::onClickHandler();', 1, '2011-05-25 01:21:24');
INSERT INTO `checks` VALUES (385, 0, 'button', 0, NULL, '_CNAME_385', '_ERR_385', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::onFocusHandler();', 1, '2011-05-25 01:22:34');
INSERT INTO `checks` VALUES (386, 0, 'all elements', 0, NULL, '_CNAME_386', '_ERR_386', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::falseLink();', 1, '2011-05-25 01:30:36');
INSERT INTO `checks` VALUES (387, 0, 'body', 2, NULL, '_CNAME_387', '_ERR_387', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-05-25 01:33:19');
INSERT INTO `checks` VALUES (388,0, 'select',1, NULL, '_CNAME_388', '_ERR_388', NULL, NULL, NULL, NULL, NULL, NULL, '_QUESTION_388', '_DECISIONPASS_388', '_DECISIONFAIL_388', NULL, NULL, NULL, 'return BasicFunctions::checkInputPseudoClassEffectWCAG2A(''focus'');',1, '2011-04-19 23:44:24');
INSERT INTO `checks` VALUES (389,0, 'textarea',1, NULL, '_CNAME_389', '_ERR_389', NULL, NULL, NULL, NULL, NULL, NULL, '_QUESTION_389', '_DECISIONPASS_389', '_DECISIONFAIL_389', NULL, NULL, NULL , 'return BasicFunctions::checkInputPseudoClassEffectWCAG2A(''focus'');',1, '2011-04-19 23:44:24');
INSERT INTO `checks` VALUES (390,0, 'button',1, NULL, '_CNAME_390', '_ERR_390', NULL, NULL, NULL, NULL, NULL, NULL, '_QUESTION_390', '_DECISIONPASS_390', '_DECISIONFAIL_390', NULL, NULL, NULL, 'return BasicFunctions::checkInputPseudoClassEffectWCAG2A(''focus'');',1, '2011-04-19 23:44:24');
INSERT INTO `checks` VALUES (391, 0, 'all elements', 0, NULL, '_CNAME_391', '_ERR_391', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !BasicFunctions::doesElementBlink();', 1, '2011-11-28 17:50:01');
INSERT INTO `checks` VALUES (392, 0, 'img', 2, NULL, '_CNAME_392', '_ERR_392', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$extensions = array(".gif");\r\n$ext = BasicFunctions::getLastCharsStartingFromLastDot(''src'');\r\n\r\nreturn !BasicFunctions::searchElementInArray($ext, $extensions);', 1, '2011-11-28 17:50:55');
INSERT INTO `checks` VALUES (393, 0, 'input', 2, NULL, '_CNAME_393', '_ERR_393', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'if(BasicFunctions::getAttributeValueInLowerCase("type") == "image")\r\n{\r\n	$extensions = array(".gif");\r\n	$ext = BasicFunctions::getLastCharsStartingFromLastDot(''src'');\r\n	return !BasicFunctions::searchElementInArray($ext, $extensions);\r\n}\r\nreturn true;', 1, '2011-11-29 09:42:17');
INSERT INTO `checks` VALUES (394, 0, 'form', 2, NULL, '_CNAME_394', '_ERR_394', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-11-29 09:58:06');
INSERT INTO `checks` VALUES (395, 0, 'input', 2, NULL, '_CNAME_395', '_ERR_395', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'if(BasicFunctions::getAttributeValueInLowerCase("type") == "image")\r\n{\r\n	$extensions = array(".gif");\r\n	$ext = BasicFunctions::getLastCharsStartingFromLastDot(''src'');\r\n	return !BasicFunctions::searchElementInArray($ext, $extensions);\r\n}\r\nreturn true;', 1, '2011-11-29 10:42:48');
INSERT INTO `checks` VALUES (396,0, 'a',1, NULL, '_CNAME_396', '_ERR_396', NULL, NULL, NULL, NULL, NULL, NULL, '_QUESTION_396', '_DECISIONPASS_396', '_DECISIONFAIL_396', NULL, NULL, NULL, 'return BasicFunctions::checkLinkPseudoClassEffectWCAG2A(''hover'');',1, '2011-11-29 12:32:50');
INSERT INTO `checks` VALUES (397, 0, 'body', 2, NULL, '_CNAME_397', '_ERR_397', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-11-29 15:18:31');
INSERT INTO `checks` VALUES (398, 0, 'body', 2, NULL, '_CNAME_398', '_ERR_398', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-11-30 10:18:52');
INSERT INTO `checks` VALUES (399, 0, 'iframe', 0, NULL, '_CNAME_399', '_ERR_399', '_DESC_399', NULL, NULL, NULL, '_HOWTOREPAIR_399', '_REPAIREXAMPLE_399', NULL, NULL, NULL, '_PROCEDURE_399', '_EXPECTEDRESULT_399', '_FAILEDRESULT_399', 'return BasicFunctions::hasAttribute(''title'');', 1, '2011-11-30 10:25:03');
INSERT INTO `checks` VALUES (400, 0, 'iframe', 2, NULL, '_CNAME_400', '_ERR_400', '_DESC_400', NULL, NULL, NULL, NULL, NULL, '_QUESTION_400', '_DECISIONPASS_400', '_DECISIONFAIL_400', '_PROCEDURE_400', '_EXPECTEDRESULT_400', '_FAILEDRESULT_400', 'return false;', 1, '2011-11-30 10:31:56');
INSERT INTO `checks` VALUES (401, 0, 'h6', 0, NULL, '_CNAME_401', '_ERR_401', '_DESC_401', NULL, NULL, NULL, '_HOWTOREPAIR_401', NULL, NULL, NULL, NULL, '_PROCEDURE_401', '_EXPECTEDRESULT_401', '_FAILEDRESULT_401', 'return BasicFunctions::isPrevTagNotIn(array("h1", "h2", "h3", "h4"));', 1, '2011-12-01 10:47:35');
INSERT INTO `checks` VALUES (402, 0, 'body', 2, NULL, '_CNAME_402', '_ERR_402', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-01 15:37:17');
INSERT INTO `checks` VALUES (403, 0, 'body', 2, NULL, '_CNAME_403', '_ERR_403', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-01 15:38:22');
INSERT INTO `checks` VALUES (404, 0, 'body', 2, NULL, '_CNAME_404', '_ERR_404', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-01 15:42:29');
INSERT INTO `checks` VALUES (405, 0, 'body', 2, NULL, '_CNAME_405', '_ERR_405', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-01 15:44:10');
INSERT INTO `checks` VALUES (406, 0, 'body', 2, NULL, '_CNAME_406', '_ERR_406', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-01 15:44:43');
INSERT INTO `checks` VALUES (407, 0, 'body', 2, NULL, '_CNAME_407', '_ERR_407', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-05 15:12:29');
INSERT INTO `checks` VALUES (408, 0, 'body', 2, NULL, '_CNAME_408', '_ERR_408', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-05 15:18:38');
INSERT INTO `checks` VALUES (409, 0, 'body', 2, NULL, '_CNAME_409', '_ERR_409', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-05 15:24:06');
INSERT INTO `checks` VALUES (410, 0, 'body', 2, NULL, '_CNAME_410', '_ERR_410', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-05 15:34:44');
INSERT INTO `checks` VALUES (411, 0, 'area', 2, NULL, '_CNAME_411', '_ERR_411', '_DESC_411', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::hasAttribute("onmouseover") || \r\n         BasicFunctions::hasAttribute("onmouseout") || \r\n         BasicFunctions::hasAttribute("onfocus") || \r\n         BasicFunctions::hasAttribute("onblur") || \r\n         BasicFunctions::hasAttribute("onchange"));', 1, '2011-12-05 17:07:39');
INSERT INTO `checks` VALUES (412, 0, 'button', 2, NULL, '_CNAME_412', '_ERR_412', '_DESC_412', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::hasAttribute("onmouseover") || \r\n         BasicFunctions::hasAttribute("onmouseout") || \r\n         BasicFunctions::hasAttribute("onfocus") || \r\n         BasicFunctions::hasAttribute("onblur") || \r\n         BasicFunctions::hasAttribute("onchange"));', 1, '2011-12-05 17:08:31');
INSERT INTO `checks` VALUES (413, 0, 'input', 2, NULL, '_CNAME_413', '_ERR_413', '_DESC_413', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::hasAttribute("onmouseover") || \r\n         BasicFunctions::hasAttribute("onmouseout") || \r\n         BasicFunctions::hasAttribute("onfocus") || \r\n         BasicFunctions::hasAttribute("onblur") || \r\n         BasicFunctions::hasAttribute("onchange"));', 1, '2011-12-05 17:09:29');
INSERT INTO `checks` VALUES (414, 0, 'textarea', 2, NULL, '_CNAME_414', '_ERR_414', '_DESC_414', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::hasAttribute("onmouseover") || \r\n         BasicFunctions::hasAttribute("onmouseout") || \r\n         BasicFunctions::hasAttribute("onfocus") || \r\n         BasicFunctions::hasAttribute("onblur") || \r\n         BasicFunctions::hasAttribute("onchange"));', 1, '2011-12-05 17:10:24');
INSERT INTO `checks` VALUES (415, 0, 'body', 2, NULL, '_CNAME_415', '_ERR_415', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-05 17:23:37');
INSERT INTO `checks` VALUES (416, 0, 'form', 2, NULL, '_CNAME_416', '_ERR_416', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (417, 0, 'form', 2, NULL, '_CNAME_417', '_ERR_417', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-14 10:01:28');
INSERT INTO `checks` VALUES (418, 0, 'form', 2, NULL, '_CNAME_418', '_ERR_418', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-14 10:07:39');
INSERT INTO `checks` VALUES (419, 0, 'form', 2, NULL, '_CNAME_419', '_ERR_419', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-14 10:08:58');
INSERT INTO `checks` VALUES (420, 0, 'input', 2, NULL, '_CNAME_420', '_ERR_420', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::hasAttribute("onkeypress"));', 1, '2011-12-14 11:16:35');
INSERT INTO `checks` VALUES (421, 0, 'textarea', 2, NULL, '_CNAME_421', '_ERR_421', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::hasAttribute("onkeypress"));', 1, '2011-12-14 14:27:29');
INSERT INTO `checks` VALUES (422, 0, 'form', 2, NULL, '_CNAME_422', '_ERR_422', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2012-01-24 11:48:59');
INSERT INTO `checks` VALUES (423, 0, 'html', 0, NULL, '_CNAME_423', '_ERR_423', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::isDocumentWellFormed();', 1, '2012-01-26 11:43:21');
INSERT INTO `checks` VALUES (424, 0, 'html', 0, NULL, '_CNAME_424', '_ERR_424', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return BasicFunctions::doesDocumentElementsContainDuplicateAttribute();', 1, '2012-01-29 22:57:47');
INSERT INTO `checks` VALUES (425, 0, 'img', 2, NULL, '_CNAME_425', '_ERR_425', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'if (BasicFunctions::hasAttribute(''alt'') && BasicFunctions::getAttributeTrimedValueLength(''alt'') != 0) return false;\r\nreturn true;', 1, '2012-01-29 23:19:54');
INSERT INTO `checks` VALUES (426, 0, 'object', 2, NULL, '_CNAME_426', '_ERR_426', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2012-01-29 23:20:40');
INSERT INTO `checks` VALUES (427, 2, 'html', 2, NULL, '_CNAME_427', '_ERR_427', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (428, 2, 'form', 2, NULL, '_CNAME_428', '_ERR_428', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return false;', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (429, 0, 'h1', 0, NULL, '_CNAME_429', '_ERR_429', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (430, 0, 'h2', 0, NULL, '_CNAME_430', '_ERR_430', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (431, 0, 'h3', 0, NULL, '_CNAME_431', '_ERR_431', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (432, 0, 'h4', 0, NULL, '_CNAME_432', '_ERR_432', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (433, 0, 'h5', 0, NULL, '_CNAME_433', '_ERR_433', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (434, 0, 'h6', 0, NULL, '_CNAME_434', '_ERR_434', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (435, 0, 'abbr', 0, NULL, '_CNAME_435', '_ERR_435', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (436, 0, 'acronym', 0, NULL, '_CNAME_436', '_ERR_436', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (437, 0, 'address', 0, NULL, '_CNAME_437', '_ERR_437', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (438, 0, 'b', 0, NULL, '_CNAME_438', '_ERR_438', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (439, 0, 'bdo', 0, NULL, '_CNAME_439', '_ERR_439', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (440, 0, 'big', 0, NULL, '_CNAME_440', '_ERR_440', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (441, 0, 'blockquote', 0, NULL, '_CNAME_441', '_ERR_441', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (442, 0, 'caption', 0, NULL, '_CNAME_442', '_ERR_442', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (443, 0, 'center', 0, NULL, '_CNAME_443', '_ERR_443', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (444, 0, 'cite', 0, NULL, '_CNAME_444', '_ERR_444', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (445, 0, 'code', 0, NULL, '_CNAME_445', '_ERR_445', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (446, 0, 'dd', 0, NULL, '_CNAME_446', '_ERR_446', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (447, 0, 'del', 0, NULL, '_CNAME_447', '_ERR_447', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (448, 0, 'dfn', 0, NULL, '_CNAME_448', '_ERR_448', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (449, 0, 'dl', 0, NULL, '_CNAME_449', '_ERR_449', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (450, 0, 'dt', 0, NULL, '_CNAME_450', '_ERR_450', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (451, 0, 'em', 0, NULL, '_CNAME_451', '_ERR_451', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (452, 0, 'font', 0, NULL, '_CNAME_452', '_ERR_452', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (453, 0, 'i', 0, NULL, '_CNAME_453', '_ERR_453', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (454, 0, 'ins', 0, NULL, '_CNAME_454', '_ERR_454', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (455, 0, 'kbd', 0, NULL, '_CNAME_455', '_ERR_455', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (456, 0, 'label', 0, NULL, '_CNAME_456', '_ERR_456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (457, 0, 'legend', 0, NULL, '_CNAME_457', '_ERR_457', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (458, 0, 'li', 0, NULL, '_CNAME_458', '_ERR_458', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (459, 0, 'noframes', 0, NULL, '_CNAME_459', '_ERR_459', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (460, 0, 'noscript', 0, NULL, '_CNAME_460', '_ERR_460', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (461, 0, 'option', 0, NULL, '_CNAME_461', '_ERR_461', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (462, 0, 'p', 0, NULL, '_CNAME_462', '_ERR_462', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (463, 0, 'param', 0, NULL, '_CNAME_463', '_ERR_463', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (464, 0, 'pre', 0, NULL, '_CNAME_464', '_ERR_464', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (465, 0, 'q', 0, NULL, '_CNAME_465', '_ERR_465', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (466, 0, 's', 0, NULL, '_CNAME_466', '_ERR_466', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (467, 0, 'samp', 0, NULL, '_CNAME_467', '_ERR_467', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (468, 0, 'small', 0, NULL, '_CNAME_468', '_ERR_468', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (469, 0, 'span', 0, NULL, '_CNAME_469', '_ERR_469', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (470, 0, 'strike', 0, NULL, '_CNAME_470', '_ERR_470', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (471, 0, 'strong', 0, NULL, '_CNAME_471', '_ERR_471', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (472, 0, 'sub', 0, NULL, '_CNAME_472', '_ERR_472', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (473, 0, 'sup', 0, NULL, '_CNAME_473', '_ERR_473', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (474, 0, 'td', 0, NULL, '_CNAME_474', '_ERR_474', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (475, 0, 'th', 0, NULL, '_CNAME_475', '_ERR_475', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (477, 0, 'tt', 0, NULL, '_CNAME_477', '_ERR_477', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');
INSERT INTO `checks` VALUES (478, 0, 'u', 0, NULL, '_CNAME_478', '_ERR_478', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'return !(BasicFunctions::isEmpty());', 1, '2011-12-14 10:00:00');


/*
# update associations
insert INTO `subgroup_checks` (`subgroup_id`, `check_id`) VALUES
(225, 66),
(244, 66),
(281, 66),
(294, 371),
(294, 374),
(359, 371),
(359, 374),
(360, 251),
(295, 251),
(350, 427),
(350, 428);

DELETE FROM `subgroup_checks` WHERE `subgroup_id` = 295 AND `check_id` = 362;



INSERT INTO `subgroup_checks` (`subgroup_id`, `check_id`) VALUES
(275,429),
(275,430),
(275,431),
(275,432),
(275,433),
(275,434),
(275,435),
(275,436),
(275,437),
(275,438),
(275,439),
(275,440),
(275,441),
(275,442),
(275,443),
(275,444),
(275,445),
(275,446),
(275,447),
(275,448),
(275,449),
(275,450),
(275,451),
(275,452),
(275,453),
(275,454),
(275,455),
(275,456),
(275,457),
(275,458),
(275,459),
(275,460),
(275,461),
(275,462),
(275,463),
(275,464),
(275,465),
(275,466),
(275,467),
(275,468),
(275,469),
(275,470),
(275,471),
(275,472),
(275,473),
(275,474),
(275,475),
(275,51),
(275,477),
(275,478),
(326,429),
(326,430),
(326,431),
(326,432),
(326,433),
(326,434),
(326,435),
(326,436),
(326,437),
(326,438),
(326,439),
(326,440),
(326,441),
(326,442),
(326,443),
(326,444),
(326,445),
(326,446),
(326,447),
(326,448),
(326,449),
(326,450),
(326,451),
(326,452),
(326,453),
(326,454),
(326,455),
(326,456),
(326,457),
(326,458),
(326,459),
(326,460),
(326,461),
(326,462),
(326,463),
(326,464),
(326,465),
(326,466),
(326,467),
(326,468),
(326,469),
(326,470),
(326,471),
(326,472),
(326,473),
(326,474),
(326,475),
(326,51),
(326,477),
(326,478),
(240,429),
(240,430),
(240,431),
(240,432),
(240,433),
(240,434),
(240,435),
(240,436),
(240,437),
(240,438),
(240,439),
(240,440),
(240,441),
(240,442),
(240,443),
(240,444),
(240,445),
(240,446),
(240,447),
(240,448),
(240,449),
(240,450),
(240,451),
(240,452),
(240,453),
(240,454),
(240,455),
(240,456),
(240,457),
(240,458),
(240,459),
(240,460),
(240,461),
(240,462),
(240,463),
(240,464),
(240,465),
(240,466),
(240,467),
(240,468),
(240,469),
(240,470),
(240,471),
(240,472),
(240,473),
(240,474),
(240,475),
(240,51),
(240,477),
(240,478);





# "remove" unnecessary checks
update checks set open_to_public = 0 where
check_id = 116 or check_id = 176 or check_id = 221 or check_id = 222 or check_id = 223 or check_id = 224 or check_id = 252 or check_id = 177 or check_id = 117;
*/
