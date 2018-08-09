/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2018                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id: checker_results.tmpl.php 460 2011-01-25 18:26:41Z cindy $

// Declare dependencies
/*global window, alert, jQuery*/

var AChecker = AChecker || {};
AChecker.utility = AChecker.utility || {};
AChecker.input = AChecker.input || {};
AChecker.output = AChecker.output || {};

(function ($) {
    // The mapping between the tab IDs and corresponding menu & spinner IDs on the validator input form
    var inputDivMapping = {
        "AC_by_uri": {
            menuID: "AC_menu_by_uri",
            spinnerID: "AC_spinner_by_uri"
        },
        "AC_by_upload": {
            menuID: "AC_menu_by_upload",
            spinnerID: "AC_spinner_by_upload"
        },
        "AC_by_paste": {
            menuID: "AC_menu_by_paste",
            spinnerID: "AC_spinner_by_paste"
        }
    };

    // The mapping between the tab IDs and their corresponding menu IDs on the validator output form
    var outputDivMapping = {
        "AC_errors": {
            menuID: "AC_menu_errors"
        },
        "AC_likely_problems": {
            menuID: "AC_menu_likely_problems" 
        },
        "AC_potential_problems": {
            menuID: "AC_menu_potential_problems"
        },
        "AC_html_validation_result": {
            menuID: "AC_menu_html_validation_result"
        },
        "AC_css_validation_result": {
            menuID: "AC_menu_css_validation_result"
        }
    };

    AChecker.output.makeDecisionButtonId = "AC_btn_make_decision_lineNumRpt";
    AChecker.output.sealDivID = "AC_seals_div";

    // Private variables that are only available in this script
    var disableClass = "AC_disabled";
    
    var clickOptionRptGDL = function () {
        $("#guideline_in_checkbox").hide();
        $("#guideline_in_radio").show();
    };
    
    var clickOptionRptLine = function () {
        $("#guideline_in_checkbox").show();
        $("#guideline_in_radio").hide();
    };
    
    /**
     * Display the clicked tab and show/hide "made decision" button according to the displayed tab.
     * @param tab: "validate_uri", "validate_file", "validate_paste"
     *        rptFormat: "by_guideline", "by_line"
     */
    AChecker.input.initialize = function (tab, rptFormat) {
        // initialize input form
        $("#" + inputDivMapping[tab].spinnerID).hide();
        
        AChecker.showDivOutof(tab, inputDivMapping);
        
        // initialize output form
        var div_errors_id = "AC_errors";
        var div_errors = document.getElementById(div_errors_id);

        if (div_errors) {
            // show tab "errors", hide other tabs
            AChecker.showDivOutof(div_errors_id, outputDivMapping);            

            // hide button "make decision" as tab "errors" are selected
            $("#" + AChecker.output.makeDecisionButtonId).hide();
        } else { // no output yet, set focus on "check by uri" input box
            document.getElementById("checkuri").focus();
        }
        
        // link click event on radio buttons on "options" => "report format"
        $("#option_rpt_gdl").click(clickOptionRptGDL);
        $("#option_rpt_line").click(clickOptionRptLine);
        
        // initialized the "options" => "guidelines" section, based on the selected "report format"
        if (rptFormat === "by_guideline") {
            $("#option_rpt_gdl").trigger("click");
        } else if (rptFormat === "by_line") {
            $("#option_rpt_line").trigger("click");
        }
    };
    
    /**
     * Display and activate the selected input div
     * @param divId: the id of the selected input div
     */
    AChecker.input.onClickTab = function (divId) {
        // check if the div is disabled
        if (!$('#' + inputDivMapping[divId].menuID).hasClass(disableClass)) {
            AChecker.showDivOutof(divId, inputDivMapping);
        }
        return false;
    };

    var disableClickablesAndShowSpinner = function (spinnerID) {
        // disable the tabs on the input form by adding css class "AC_disabled"
        // which is detected and processed in AChecker.input.onClickTab()
        for (var key in inputDivMapping) {
            $('#' + inputDivMapping[key].menuID).addClass(disableClass);
        }
        
        $("#" + spinnerID).show();
        document.getElementById(spinnerID).focus();
    };
    
    var enableClickablesAndHideSpinner = function (spinnerID) {
        for (var key in inputDivMapping) {
            $('#' + inputDivMapping[key].menuID).removeClass(disableClass);
        }
        
        $("#" + spinnerID).hide();
    };
    
    /**
     * Validates if a uri is provided
     */
    AChecker.input.validateURI = function () {
        // check uri
        var uri = document.getElementById("checkuri").value;
        if (!uri) {
            alert(AChecker.lang.provide_uri);
            return false;
        }
        disableClickablesAndShowSpinner(inputDivMapping.AC_by_uri.spinnerID);
    };
        
    /**
     * Validates if a html file is provided
     */
    AChecker.input.validateUpload = function () {
        // check file type
        var upload_file = document.getElementById("checkfile").value;
        if (!upload_file || upload_file.trim() === '') {
            alert(AChecker.lang.provide_html_file);
            return false;
        }
        
        var file_extension = upload_file.slice(upload_file.lastIndexOf(".")).toLowerCase();
        if (file_extension !== '.html' && file_extension !== '.htm') {
            alert(AChecker.lang.provide_upload_file);
            return false;
        }
        disableClickablesAndShowSpinner(inputDivMapping.AC_by_upload.spinnerID);
    };

    /**
     * Validates if a html file (paste) is provided
     */
    AChecker.input.validatePaste = function () {
        // check file type
        var paste_html = document.getElementById("checkpaste").value;
        if (!paste_html || paste_html.trim() === '') {
            alert(AChecker.lang.provide_html_input);
            return false;
        }
        disableClickablesAndShowSpinner(inputDivMapping.AC_by_paste.spinnerID);
    };
    
    /**
     * Validates file select menu, sends file & problem type to start_export.php,
     * receives file's path and starts downloading
     */
    AChecker.input.validateFile = function (exportSpinnerID) {
        // check selected items
        var file = document.getElementById("fileselect").value;
        var problem = document.getElementById("problemselect").value;
        
        $("#validate_file_button").val(AChecker.lang.wait);
        
        // show spinner        
        disableClickablesAndShowSpinner(exportSpinnerID);             
        
        // make dataString and send it
        var dataString = 'file=' + file + '&problem=' + problem;
        
        $.ajax({
            type: "POST",
            url: "checker/start_export.php",
            data: dataString,
            cache: false,
            success: function (returned_data) {
                // change button label
                $("#validate_file_button").val(AChecker.lang.get_file);
            
                // enable the clickable tabs/buttons and hide the spinner
                enableClickablesAndHideSpinner(exportSpinnerID);
            
                // change src and start downloading
                var ifrm = document.getElementById("downloadFrame");
                ifrm.src = "checker/download.php?path=" + returned_data;
            },
        
            error: function (xhr, errorType, exception) {
                alert(AChecker.lang.error_occur + exception);

                // enable the clickable tabs/buttons and hide the spinner
                enableClickablesAndHideSpinner(exportSpinnerID);
            }
        });
    };

    /**
     * Display and activate the selected output div
     * @param divId: the id of the selected output div
     */
    AChecker.output.onClickTab = function (divId) {
        window.location.hash = 'output_div';
        AChecker.showDivOutof(divId, outputDivMapping);

        if (divId === "AC_errors" || divId === "AC_html_validation_result" || divId === "AC_css_validation_result") {
            $("#" + AChecker.output.makeDecisionButtonId).hide();
        } else {
            $("#" + AChecker.output.makeDecisionButtonId).show();
        }
        
        return false;
    };
    
    /**
     * private
     * clicking the last unchecked or checked child checkbox should check or uncheck the parent "select all" checkbox
     */
    var undoSelectAll = function (this_child) {
        if ($(this_child).parents('table:eq(0)').find('.AC_selectAllCheckBox').attr('checked') === true && this_child.checked === false) {
            $(this_child).parents('table:eq(0)').find('.AC_selectAllCheckBox').attr('checked', false);
        }
        
        if (this_child.checked) {
            var flag = true;
            $(this_child).parents('table:eq(0)').find('.AC_childCheckBox').each(
                function () {
                    if (!this.checked) {
                        flag = false;
                    }
                }
            );
            $(this_child).parents('table:eq(0)').find('.AC_selectAllCheckBox').attr('checked', flag);
            $(this_child).parent().parent().addClass('selected');
        } else {
            $(this_child).parent().parent().removeClass('selected');
        }
    };
    
    /**
     * private
     * Display server response message - success
     * Called by makeDecisions()
     */
    var displaySuccessMsg = function (btn_make_decision, message) {
        var serverMsgSpan = $(btn_make_decision).parents('tr:eq(0)').find('span[id^="server_response"]');
        serverMsgSpan.addClass("gd_success");
        serverMsgSpan.html(message);
    };
    
    /**
     * private
     * Display server response message - error.
     * Called by makeDecisions()
     */
    var displayErrorMsg = function (btn_make_decision, message) {
        var serverMsgSpan = $(btn_make_decision).parents('tr:eq(0)').find('span[id^="server_response"]');
        serverMsgSpan.addClass("gd_error");
        serverMsgSpan.html(message);
    };
    
    /**
     * private
     * When the pass decision is made, flip the likely/potential icons to green congrats icons;
     * When the pass decision is cancelled, flip green congrats icons to the likely/potential icons.
     * Called by makeDecisions() 
     */
    var flipMsgIcon = function (btn_make_decision) {
        $(btn_make_decision).parents('table:eq(0)').find('.AC_childCheckBox').each(function () {
            // find out the id of message icon
            var checkboxName = $(this).attr('name');
            var msgIconID = checkboxName.replace('d[', '');
            msgIconID = msgIconID.replace(']', '');
            
            var msgIconIDValue = '#msg_icon_' + msgIconID;

            var msgIcon = $(msgIconIDValue);
            if (this.checked) {
                msgIcon.attr('src', 'images/feedback.gif');
                msgIcon.attr('title', AChecker.lang.pass_decision);
                msgIcon.attr('alt', AChecker.lang.pass_decision);
            } else {
                // find out the problem is a likely or a potential
                var inLikelyDiv = msgIcon.parents('div[id="AC_likely_problems"]');
                var inPotentialDiv = msgIcon.parents('div[id="AC_potential_problems"]');

                if (inLikelyDiv.length) { // likely problem
                    msgIcon.attr('src', 'images/warning.png');
                    msgIcon.attr('title', AChecker.lang.warning);
                    msgIcon.attr('alt', AChecker.lang.warning);
                } 
                if (inPotentialDiv.length) { // potential problem
                    msgIcon.attr('src', 'images/info.png');
                    msgIcon.attr('title', AChecker.lang.manual_check);
                    msgIcon.attr('alt', AChecker.lang.manual_check);
                } 
            }
        });
    };
    
    /**
     * private
     * Modify the number of problems on the tab bar. 
     * Called by makeDecisions()
     */
    var changeNumOfProblems = function () {
        var divsToLookup = ["AC_likely_problems", "AC_potential_problems"];
        var divIDsToUpdateErrorNum = ["AC_num_of_likely", "AC_num_of_potential"];
        var arrayNumOfProblems = new Array(2);

        // decide the tab to work on and number of problems
        for (var i in divsToLookup) {
            var currentDiv = $('div[id="' + divsToLookup[i] + '"]');
            // find number of all problems (checkboxes) in the current tab
            var total = $(currentDiv).find("input[class=AC_childCheckBox]").length;
            var checked = $(currentDiv).find("input[class=AC_childCheckBox]:checked").length;
            
            var numOfProblems = total - checked;
            $("#" + divIDsToUpdateErrorNum[i]).html(numOfProblems);
            
            arrayNumOfProblems[i] = numOfProblems;
        }
        
        return arrayNumOfProblems;
    };
    
    /**
     * retrieve and display seal
     * Called by makeDecisions()
     */
    var showSeal = function (btn_make_decision) {
        var ajaxPostStr = "uri" + "=" + $.URLEncode($('input[name="uri"]').attr('value')) + "&" + 
                          "jsessionid" + "=" + $('input[name="jsessionid"]').attr('value') + "&" +
                          "gids[]=" + $('input[name="radio_gid[]"][type="hidden"]').attr('value');

        $.ajax({
            type: "POST",
            url: "checker/get_seal_html.php",
            data: ajaxPostStr,
            
            success: function (data) {
                // display seal
                $('#' + AChecker.output.sealDivID).html(data);

                // inform the user that the seal has been issued and displayed at the top seal container
                var serverMsgSpan = $(btn_make_decision).parents('tr:eq(0)').find('span[id^="server_response"]');
                serverMsgSpan.html(serverMsgSpan.text() + AChecker.lang.get_seal);
            }
        });
    };
    
    /**
     * Click event on "make decision" buttons. It does:
     * 1. ajax post to save into db
     * 2. prompt success or error msg returned from server besides the "make decision" button
     * 3. flip warning/info icons besides problems with pass decisons made to green pass icons
     * 4. change the number of problems on the tab bar
     * 5. when the number of problems is reduced to 0, 
     *    ajax request the seal html from server and display it in seal container
     */
    var makeDecision = function (btn_make_decision) {
        var ajaxPostStr = "";

        $('input[class="AC_childCheckBox"]').each(function () {
            if (this.checked) {
                ajaxPostStr += $(this).attr('name') + "=" + "P" + "&";
            } else {
                ajaxPostStr += $(this).attr('name') + "=" + "N" + "&";
            }
        });

        ajaxPostStr += "uri" + "=" + $.URLEncode($('input[name="uri"]').attr('value')) + "&" + 
            "output" + "=" + $('input[name="output"]').attr('value') + "&" +
            "jsessionid" + "=" + $('input[name="jsessionid"]').attr('value');
    
        $.ajax({
            type: "POST",
            url: "checker/save_decisions.php",
            data: ajaxPostStr,

            success: function (data) {
                // display success message
                displaySuccessMsg(btn_make_decision, data);
                
                // flip icon to green pass icon
                flipMsgIcon(btn_make_decision);
                
                // modify and store the number of problems on the tab bar
                var arrayNumOfProblems = changeNumOfProblems();
                
                // No more likely problems, display congrats message on "likely problems" tab
                if (arrayNumOfProblems[0] === 0) {
                    $("#AC_congrats_msg_for_likely").html(AChecker.lang.congrats_likely);
                    $("#AC_congrats_msg_for_likely").addClass("congrats_msg");
                } else {
                    $("#AC_congrats_msg_for_likely").html("");
                    $("#AC_congrats_msg_for_likely").removeClass("congrats_msg");
                }
                
                // No more potential problems, display congrats message on "potential problems" tab
                if (arrayNumOfProblems[1] === 0) {
                    $("#AC_congrats_msg_for_potential").html(AChecker.lang.congrats_potential);
                    $("#AC_congrats_msg_for_potential").addClass("congrats_msg");
                } else {
                    $("#AC_congrats_msg_for_potential").html("");
                    $("#AC_congrats_msg_for_potential").removeClass("congrats_msg");
                }
                
                // if all errors, likely, potential problems are 0, retrieve seal
                if (arrayNumOfProblems[0] === 0 && arrayNumOfProblems[1] === 0) {
                    // find the number of errors
                    var numOfErrors = $('#AC_num_of_errors').text();

                    if (numOfErrors === 0) {
                        showSeal(btn_make_decision);
                    }
                } else {
                    $('#' + AChecker.output.sealDivID).html("");
                }
            }, 
            
            error: function (xhr, errorType, exception) {
                // display error message
                displayErrorMsg(btn_make_decision, $(xhr.responseText).text());
            }
        });
    };

    $(document).ready(
        function () {
            //clicking the "select all" checkbox should check or uncheck all child checkboxes
            $(".AC_selectAllCheckBox").click(function () {
                var table = $(this).parents('table:eq(0)');
                $(table).find('.AC_childCheckBox').attr('checked', this.checked);
                if (this.checked) {
                    $(table).find('tr').addClass("selected");
                } else {
                    $(table).find('tr').removeClass("selected");
                }
            });
        
            //clicking the last unchecked or checked checkbox should check or uncheck the parent "select all" checkbox
            $('.AC_childCheckBox').click(function () {
                undoSelectAll(this);
            });
        
            //clicking the last unchecked or checked checkbox should check or uncheck the parent "select all" checkbox
            $('.AC_problem_detail').click(function () {
                $(this).siblings().find('.AC_childCheckBox').each(
                    function () {
                        $(this).attr('checked', !this.checked);
                        undoSelectAll(this);
                    }
                );
            });
        
            // clicking on "make decision" button
            $('input[id^="AC_btn_make_decision"]').click(function () {
                makeDecision(this);
            });
        
        }
    );
})(jQuery);
