<?php 
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2011                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id: $
//
// Noe: This file is essentially a javascript file, but needs to be terminated with
// a .php extension so that php calls can be used within it. Please put pure javascript
// in checker.js

// This file is currently used to define all the language translations that are used 
// in checker.js

// Used in themes/default/checker/checker_results.tmpl.php & include/classes/HTMLByGuidelineRpt.class.php
global $congrats_msg_for_likely, $congrats_msg_for_potential;
$congrats_msg_for_likely = '<img src="'.AC_BASE_HREF.'images/feedback.gif" alt="'._AC("feedback").'" />  '. _AC("congrats_no_likely");
$congrats_msg_for_potential = '<img src="'.AC_BASE_HREF.'images/feedback.gif" alt="'._AC("feedback").'" />  '. _AC("congrats_no_potential");
?>

var AChecker = AChecker || {};
AChecker.lang = AChecker.lang || {};

(function () {

    // Define langauge translations that are used in checker.js
    // @ see checker/js/checker.js
    AChecker.lang.provide_uri = "<?php echo _AC('provide_uri'); ?>";
    AChecker.lang.provide_html_file = "<?php echo _AC('provide_html_file'); ?>";
    AChecker.lang.provide_upload_file = "<?php echo _AC('provide_upload_file'); ?>";
    AChecker.lang.provide_html_input = "<?php echo _AC('provide_html_input'); ?>";
    AChecker.lang.wait = "<?php echo _AC('wait'); ?>";
    AChecker.lang.get_file = "<?php echo _AC('get_file'); ?>";
    AChecker.lang.error_occur = "<?php echo _AC('error_occur'); ?>";
    AChecker.lang.pass_decision = "<?php echo _AC('passed_decision'); ?>";
    AChecker.lang.warning = "<?php echo _AC('warning'); ?>";
    AChecker.lang.manual_check = "<?php echo _AC('manual_check'); ?>";
    AChecker.lang.get_seal = '<?php echo _AC('get_seal'); ?>';
    AChecker.lang.congrats_likely = '<?php echo $congrats_msg_for_likely; ?>';
    AChecker.lang.congrats_potential = '<?php echo $congrats_msg_for_potential; ?>';

})();
