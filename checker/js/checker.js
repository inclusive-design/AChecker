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
// $Id: checker_results.tmpl.php 460 2011-01-25 18:26:41Z cindy $

var AChecker = AChecker || {};
AChecker.utility = AChecker.utility || {};
AChecker.input = AChecker.input || {};
AChecker.output = AChecker.output || {};

(function() {
	// global vars on the input form of the validation index page
	AChecker.input.inputDivIds = new Array("by_uri", "by_upload", "by_paste");
	AChecker.input.inputButtonIds = new Array("validate_uri", "validate_file", "validate_paste");

	// global vars on the output form of the validation index page
	AChecker.output.outputDivIds = new Array("AC_errors", "AC_likely_problems", "AC_potential_problems", "AC_html_validation_result","AC_css_validation_result");
	AChecker.output.makeDecisionButtonId = "make_decision";

	/**
	 * Display the clicked tab and show/hide "made decision" button according to the displayed tab.
	 * @param tab: "validate_uri", "validate_file", "validate_paste"
	 *        rptFormat: "by_guideline", "by_line"
	 */
	AChecker.input.initialize = function (tab, rptFormat) {
		// initialize input form
		AChecker.hideByID("spinner");
		AChecker.showDivOutof(tab, AChecker.input.inputDivIds);
		
		// initialize output form
		var div_errors_id = "AC_errors";
		var div_errors = document.getElementById(div_errors_id);

		if (div_errors != null)
		{
			// show tab "errors", hide other tabs
			AChecker.showDivOutof(div_errors_id, AChecker.output.outputDivIds);			

			// hide button "make decision" as tab "errors" are selected
			AChecker.hideByID(AChecker.output.makeDecisionButtonId);
		} else { // no output yet, set focus on "check by uri" input box
			document.getElementById("checkuri").focus();
		}
		
		// link click event on radio buttons on "options" => "report format"
		$("#option_rpt_gdl").click(clickOptionRptGDL);
		$("#option_rpt_line").click(clickOptionRptLine);
		
		// initialized the "options" => "guidelines" section, based on the selected "report format"
		if (rptFormat == "by_guideline") {
			$("#option_rpt_gdl").trigger("click");
		} else if (rptFormat == "by_line") {
			$("#option_rpt_line").trigger("click");
		}
	};
	
	var clickOptionRptGDL = function() {
		$("#guideline_in_checkbox").hide();
		$("#guideline_in_radio").show();
	};
	
	var clickOptionRptLine = function() {
		$("#guideline_in_checkbox").show();
		$("#guideline_in_radio").hide();
	};
	
	/**
	 * Show the div with id == the given divId while hide all other divs in the array allDivIds
	 * @param divId: the id of the div to show
	 *        allDivIds: The array of div Ids that are in the same group of divId. divId must be in this array. 
	 */
	AChecker.input.onClickTab = function (divId) {
		AChecker.showDivOutof(divId, AChecker.input.inputDivIds);
		return false;
	};

	var disableClickablesAndShowSpinner = function (spinnerID) {
		var menuIds = new Array("menu_by_uri", "menu_by_upload", "menu_by_paste");
		// disable the tabs on the input form
		for (var i in menuIds) {
			e = document.getElementById(menuIds[i]);
			e.onclick = function () {return false;};
		}
		
		AChecker.showByID(spinnerID);
		document.getElementById(spinnerID).focus();
	};
	
	/**
	 * Validates if a uri is provided
	 */
	AChecker.input.validateURI = function () {
		// check uri
		var uri = document.getElementById("checkuri").value;
		if (!uri || uri=="<?php echo $default_uri_value; ?>" ) {
			alert('Please provide a uri!');
			return false;
		}
		disableClickablesAndShowSpinner("spinner_by_uri");
	};
		
	/**
	 * Validates if a html file is provided
	 */
	AChecker.input.validateUpload = function () {
		// check file type
		var upload_file = document.getElementById("checkfile").value;
		if (!upload_file || upload_file.trim()=='') {
			alert('Please provide a html file!');
			return false;
		}
		
		file_extension = upload_file.slice(upload_file.lastIndexOf(".")).toLowerCase();
		if(file_extension != '.html' && file_extension != '.htm') {
			alert('Please upload html (or htm) file only!');
			return false;
		}
		disableClickablesAndShowSpinner("spinner_by_file");
	};

	/**
	 * Validates if a html file is provided
	 */
	AChecker.input.validatePaste = function () {
		// check file type
		var paste_html = document.getElementById("checkpaste").value;
		if (!paste_html || paste_html.trim()=='') {
			alert('Please provide a html input!');
			return false;
		}
		disableClickablesAndShowSpinner("spinner_by_paste");
	};

	/**
	 * Show the div with id == the given divId while hide all other divs in the array allDivIds
	 * @param divId: the id of the div to show
	 *        allDivIds: The array of div Ids that are in the same group of divId. divId must be in this array. 
	 */
	AChecker.output.onClickTab = function (divId) {
		window.location.hash = 'output_div';
		AChecker.showDivOutof(divId, AChecker.output.outputDivIds);

		if (divId == "AC_errors" || divId == "AC_html_validation_result" || divId == "AC_css_validation_result") {
			AChecker.hideByID(AChecker.output.makeDecisionButtonId);
		} else {
			AChecker.showByID(AChecker.output.makeDecisionButtonId);
		}
		
		return false;
	};
	
    /**
     * private
     * clicking the last unchecked or checked child checkbox should check or uncheck the parent "select all" checkbox
     */
	var undoSelectAll = function(this_child) {
        if ($(this_child).parents('table:eq(0)').find('.AC_selectAllCheckBox').attr('checked') == true && this_child.checked == false)
            $(this_child).parents('table:eq(0)').find('.AC_selectAllCheckBox').attr('checked', false);
        if (this_child.checked == true) {
            var flag = true;
            $(this_child).parents('table:eq(0)').find('.AC_childCheckBox').each(
                function() {
                    if (this.checked == false)
                        flag = false;
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
    var displaySuccessMsg = function(btn_make_decision, message) {
	    serverMsgSpan = $(btn_make_decision).parents('tr:eq(0)').find('span[id^="server_response"]');
	    serverMsgSpan.addClass("gd_success");
	    serverMsgSpan.html(message);
    };
    
    /**
     * private
     * Display server response message - error.
     * Called by makeDecisions()
     */
    var displayErrorMsg = function(btn_make_decision, message) {
	    serverMsgSpan = $(btn_make_decision).parents('tr:eq(0)').find('span[id^="server_response"]');
	    serverMsgSpan.addClass("gd_error");
	    serverMsgSpan.html(message);
    };
    
    /**
     * private
     * When the pass decision is made, flip the likely/potential icons to green congrats icons;
     * When the pass decision is cancelled, flip green congrats icons to the likely/potential icons.
     * Called by makeDecisions() 
     */
    var flipMsgIcon = function(btn_make_decision) {
        $(btn_make_decision).parents('table:eq(0)').find('.AC_childCheckBox').each(function () {
            // find out the id of message icon
            var checkboxName = $(this).attr('name')+"";
            msgIconID = checkboxName.replace('d[', '');
            msgIconID = msgIconID.replace(']', '');
            msgIconIDValue = '#msg_icon_' + msgIconID;
    		
            msgIcon = $(msgIconIDValue);
            if (this.checked == true) {
                msgIcon.attr('src','images/feedback.gif');
                msgIcon.attr('title',passDecisionText);
                msgIcon.attr('alt',passDecisionText);
            } else {
                // find out the problem is a likely or a potential
                inLikelyDiv = msgIcon.parents('div[id="AC_likely_problems"]');
                inPotentialDiv = msgIcon.parents('div[id="AC_potential_problems"]');
    			
                if (inLikelyDiv.length){ // likely problem
                	msgIcon.attr('src','images/warning.png');
                    msgIcon.attr('title',warningText);
                    msgIcon.attr('alt',warningText);
                } 
                if (inPotentialDiv.length){ // potential problem
                	msgIcon.attr('src','images/info.png');
                    msgIcon.attr('title',manualCheckText);
                    msgIcon.attr('alt',manualCheckText);
                } 
    		}
    	});
    };
    
    /**
     * private
     * Modify the number of problems on the tab bar. 
     * Called by makeDecisions()
     */
    var changeNumOfProblems = function() {
        var divsToLookup = new Array("AC_likely_problems", "AC_potential_problems");
    	var divIDsToUpdateErrorNum = new Array("AC_num_of_likely", "AC_num_of_potential");
    	var arrayNumOfProblems = new Array(2);
    	
        // decide the tab to work on and number of problems
        for (i in divsToLookup) {
        	currentDiv = $('div[id="'+divsToLookup[i]+'"]');
            // find number of all problems (checkboxes) in the current tab
            var total = $(currentDiv).find("input[class=AC_childCheckBox]").length;
            var checked = $(currentDiv).find("input[class=AC_childCheckBox]:checked").length;
            
            var numOfProblems = total - checked;
            $("#"+divIDsToUpdateErrorNum[i]).html(numOfProblems);
            
            arrayNumOfProblems[i] = numOfProblems;
        }
        
        return arrayNumOfProblems;
    };
    
    /**
     * retrieve and display seal
     * Called by makeDecisions()
     */
    var showSeal = function(btn_make_decision) {
    	var ajaxPostStr = "uri" + "=" + $.URLEncode($('input[name="uri"]').attr('value')) + "&" + 
                          "jsessionid" + "=" + $('input[name="jsessionid"]').attr('value') + "&" +
                          "gids[]="+$('input[name="radio_gid[]"][type="hidden"]').attr('value');

    	$.ajax({
            type: "POST",
            url: "checker/get_seal_html.php",
            data: ajaxPostStr,
            
            success: function(data) {
    		    // display seal
    			$('#seals_div').html(data);
    			
    			// inform the user that the seal has been issued and displayed at the top seal container
    		    serverMsgSpan = $(btn_make_decision).parents('tr:eq(0)').find('span[id^="server_response"]');
    		    serverMsgSpan.html(serverMsgSpan.text() + getSealText);
    	    }
    	});
    };
    
    var setSelectedClass = function(this_checkbox) {
    	   if ($(this_checkbox).attr("checked") == true)
    	   {
    	         $(this_checkbox).parent().parent().addClass("selected");  
    	   } 
    	   else
    	  {   
    	      $(this_checkbox).parent().parent().removeClass("seleted");

    	  }    	
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
    var makeDecision = function(btn_make_decision) {
    	var ajaxPostStr = "";
    	
    	$('input[class="AC_childCheckBox"]').each(function () {
    		if (this.checked == true) {
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

            success: function(data) {
	            // display success message
    		    displaySuccessMsg(btn_make_decision, data);
    		    
    		    // flip icon to green pass icon
    		    flipMsgIcon(btn_make_decision);
    		    
    		    // modify and store the number of problems on the tab bar
    		    arrayNumOfProblems = changeNumOfProblems();
    		    
    		    // No more likely problems, display congrats message on "likely problems" tab
    		    if (arrayNumOfProblems[0] == 0) {
    		    	$("#AC_congrats_msg_for_likely").html(congratsMsgForLikely);
    		    	$("#AC_congrats_msg_for_likely").addClass("congrats_msg");
    		    }
    		    
    		    // No more potential problems, display congrats message on "potential problems" tab
    		    if (arrayNumOfProblems[0] == 0) {
    		    	$("#AC_congrats_msg_for_potential").html(congratsMsgForPotential);
    		    	$("#AC_congrats_msg_for_potential").addClass("congrats_msg");
    		    }
    		    
    		    // if all errors, likely, potential problems are 0, retrieve seal
    		    if (arrayNumOfProblems[0] == 0 && arrayNumOfProblems[1] == 0) {
    		    	// find the number of errors
        		    numOfErrors = $('#AC_num_of_errors').text();
        		    
        		    if (numOfErrors == 0) {
        		    	showSeal(btn_make_decision);
        		    }
    		    }
	        }, 
	        
            error: function(xhr, errorType, exception) {
	            // display error message
	        	displayErrorMsg(btn_make_decision, $(xhr.responseText).text());
	        }
        });
    };

    $(document).ready(
	    function() {
	        //clicking the "select all" checkbox should check or uncheck all child checkboxes
	        $(".AC_selectAllCheckBox").click(function() {
                var table = $(this).parents('table:eq(0)');
	        	$(table).find('.AC_childCheckBox').attr('checked', this.checked);
	        	if (this.checked == true) {
	        		$(table).find('tr').addClass("selected");
	        	} else {
	        		$(table).find('tr').removeClass("selected");
	        	}
            });
	        
	        //clicking the last unchecked or checked checkbox should check or uncheck the parent "select all" checkbox
	        $('.AC_childCheckBox').click(function() {
	        	undoSelectAll(this);
	        });
	        
	        //clicking the last unchecked or checked checkbox should check or uncheck the parent "select all" checkbox
	        $('.AC_problem_row').click(function() {
	        	$(this).find('.AC_childCheckBox').each(
                    function() {
                    	$(this).attr('checked', !this.checked);
                    	undoSelectAll(this);
                    }
                );
	        });
	        
	        // clicking on "make decision" button
	        $('input[id^="AC_btn_make_decision"]').click(function() {
        		makeDecision(this);
	        });
	        
	     }
    );
})();

