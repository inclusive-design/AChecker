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
	AChecker.output.sealDivID = "seals_div";

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
     * private
     * getting values from input_form, sending them to form_to_session.php to write in session =======================================================
     */
	var sendFormValues = function(provided_obj, type) {		
		// checkboxes
		var enable_html_validation = document.getElementById("enable_html_validation").checked;	
		var enable_css_validation = document.getElementById("enable_css_validation").checked;	
		var show_source = document.getElementById("show_source").checked;			
		
		// radiobuttons
		var option_rpt_line = document.getElementById("option_rpt_line").checked;			
		var option_rpt_gdl = document.getElementById("option_rpt_gdl").checked;			

		// get value of checked radio or
		// get array of checkboxes checked
		if (option_rpt_gdl) {					// report by guidelines => guideline_in_radio 
	        for (i=0; i<9; i++) {
	        	if (document.getElementById("guideline_in_radio").getElementsByTagName("input")[i].checked) {
	        		radio_checked = document.getElementById("guideline_in_radio").getElementsByTagName("input")[i].value;
	        	}
	        }
	        var dataString = 'option_rpt_gdl=' + option_rpt_gdl + 
	        				'&radio_checked=' + radio_checked;
		} else { 								// report by lines => guideline_in_checkbox

			var curr_pos = 0;
			var checkbox_array = new Array();
	        for (i=0; i<9; i++) {
	        	if (document.getElementById("guideline_in_checkbox").getElementsByTagName("input")[i].checked) {
	        		checkbox_array[curr_pos] = document.getElementById("guideline_in_checkbox").getElementsByTagName("input")[i].value;
	        		curr_pos++;
	        	}
	        }
	        var dataString = 'option_rpt_line=' + option_rpt_line +
	        				'&checkbox_array=' + checkbox_array;
		}
		
		// determine provided obj type, form dataString
		if (type == "uri") {
			dataString += '&uri=' + provided_obj;
		}
		if (type == "file") {
			dataString += '&file=' + provided_obj;
		}
		if (type == "paste") {
			dataString += '&paste=' + provided_obj;
		}

		// finish forming string
		dataString +=	'&enable_html_validation=' + enable_html_validation +
						'&enable_css_validation=' + enable_css_validation +
						'&show_source=' + show_source;
		
		alert(dataString);
		
		$.ajax({
		type: "POST",
		url: "checker/form_to_session.php",
		data: dataString,
		success: function(){
	        alert("form_to_session.php => ok");
		},		
		error: function(xhr, errorType, exception) {
	        alert("form_to_session.php => error");
        }
		});
	};
	
	/**
	 * Validates if a uri is provided, if was sends to form_to_session.php =========================================================================================================
	 */
	AChecker.input.validateURI = function () {
		// check uri
		var uri = document.getElementById("checkuri").value;
		if (!uri || uri=="<?php echo $default_uri_value; ?>" ) {
			alert('Please provide a uri!');
			return false;
		}
		disableClickablesAndShowSpinner("spinner_by_uri");
		
		// get provided to validate object 
		var checkuri = document.getElementById("checkuri").value;				
		sendFormValues(checkuri, "uri");
	};
		
	/**
	 * Validates if a html file is provided, if was sends to form_to_session.php =================================================================================================
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
		
		// get provided to validate object 
		var file = document.getElementById("checkfile").value;				
		sendFormValues(file, "file");
	};

	/**
	 * Validates if a html file (paste) is provided, if was sends to form_to_session.php =========================================================================================
	 */
	AChecker.input.validatePaste = function () {
		// check file type
		var paste_html = document.getElementById("checkpaste").value;
		if (!paste_html || paste_html.trim()=='') {
			alert('Please provide a html input!');
			return false;
		}
		disableClickablesAndShowSpinner("spinner_by_paste");
		
		// get provided to validate object 
		var paste = document.getElementById("checkpaste").value;				
		sendFormValues(paste, "paste");
	};
	
	/**
	 * Validates file select menu, sends file & problem type to start_export.php ==================================================================================
	 */
	AChecker.input.validateFile = function (data) {
		// check selected items
		var file = document.getElementById("fileselect").value;
		var problem = document.getElementById("problemselect").value;
		if ((!file || file=="<?php echo _AC('select_file'); ?>") && (!problem || problem=="<?php echo _AC('select_problem'); ?>")) {
			alert('Please provide file type and problem!');
			return false;
		}
		if (!file || file=="<?php echo _AC('select_file'); ?>") {
			alert('Please provide a file type!');
			return false;
		}
		if (!problem || problem=="<?php echo _AC('select_problem'); ?>") {
			alert('Please provide a problem!');
			return false;
		}
		
		// show spinner		
		disableClickablesAndShowSpinner(data);
		//var progress = document.getElementById(array[1]);
		//if (progress.style.display == 'none') {
		//	progress.style.display = '';
		//}			     
		
		// make dataString and send it
		var dataString = 'file=' + file + '&problem=' + problem;
		$.ajax({
		type: "POST",
		url: "checker/start_export.php",
		data: dataString,
		success: function(){
	        progress.innerHTML = 'processing ' + dataString;
		},
		
		error: function(xhr, errorType, exception) {
        	progress.innerHTML = 'Error';
        }
		});
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
    			$('#'+AChecker.output.sealDivID).html(data);
    			
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
    	alert(ajaxPostStr);  				//================================
    	
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
    		    } else {
    		    	$("#AC_congrats_msg_for_likely").html("");
    		    	$("#AC_congrats_msg_for_likely").removeClass("congrats_msg");
    		    }
    		    
    		    // No more potential problems, display congrats message on "potential problems" tab
    		    if (arrayNumOfProblems[1] == 0) {
    		    	$("#AC_congrats_msg_for_potential").html(congratsMsgForPotential);
    		    	$("#AC_congrats_msg_for_potential").addClass("congrats_msg");
    		    } else {
    		    	$("#AC_congrats_msg_for_potential").html("");
    		    	$("#AC_congrats_msg_for_potential").removeClass("congrats_msg");
    		    }
    		    
    		    // if all errors, likely, potential problems are 0, retrieve seal
    		    if (arrayNumOfProblems[0] == 0 && arrayNumOfProblems[1] == 0) {
    		    	// find the number of errors
        		    numOfErrors = $('#AC_num_of_errors').text();
        		    
        		    if (numOfErrors == 0) {
        		    	showSeal(btn_make_decision);
        		    }
    		    } else {
    		    	$('#'+AChecker.output.sealDivID).html("");
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
	        $('.AC_problem_detail').click(function() {
	        	$(this).siblings().find('.AC_childCheckBox').each(
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

