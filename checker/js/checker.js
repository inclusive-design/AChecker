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
	AChecker.output.outputDivIds = new Array("errors", "likely_problems", "potential_problems", "html_validation_result","css_validation_result");
	AChecker.output.makeDecisionButtonId = "make_decision";

	/**
	 * Display the clicked tab and show/hide "made decision" button according to the displayed tab.
	 * @param tab: "validate_uri", "validate_file", "validate_paste"
	 */
	AChecker.input.initialize = function (tab) {
		// initialize input form
		AChecker.hideByID("spinner");
		AChecker.showDivOutof(tab, AChecker.input.inputDivIds);
		
		// initialize output form
		var div_errors = document.getElementById("errors");

		if (div_errors != null)
		{
			// show tab "errors", hide other tabs
			AChecker.showDivOutof("errors", AChecker.output.outputDivIds);			

			// hide button "make decision" as tab "errors" are selected
			AChecker.hideByID(AChecker.output.makeDecisionButtonId);
		} else { //if (div_errors == null) {
			document.getElementById("checkuri").focus();
		}
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

		if (divId == "errors" || divId == "html_validation_result" || divId == "css_validation_result") {
			AChecker.hideByID(AChecker.output.makeDecisionButtonId);
		} else {
			AChecker.showByID(AChecker.output.makeDecisionButtonId);
		}
		
		return false;
	};
})();
