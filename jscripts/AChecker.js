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

	// global vars on the output form of the validation index page
	AChecker.output.outputDivIds = new Array("errors", "likely_problems", "potential_problems", "html_validation_result","css_validation_result");
	AChecker.output.makeDecisionButtonId = "make_decision";

	/**
	 * global string function of trim()
	 */
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");
	};

	/**
	 * Open up a 600*800 popup window
	 */
	AChecker.popup = function(url){
		var newwindow=window.open(url,'popup','height=600,width=800,scrollbars=yes,resizable=yes');
		if (window.focus) {newwindow.focus();}
	};
	
	/**
	 * Toggle the collapse/expand images, alt texts and titles associated with the link
	 * @param objId
	 */
	AChecker.toggleToc = function (objId) {
		var toc = document.getElementById(objId);
		if (toc == null) return;

		if (toc.style.display == 'none')
		{
			toc.style.display = '';
			document.getElementById("toggle_image").src = "images/arrow-open.png";
			document.getElementById("toggle_image").alt = "Collapse";
			document.getElementById("toggle_image").title = "Collapse";
		}
		else
		{
			toc.style.display = 'none';
			document.getElementById("toggle_image").src = "images/arrow-closed.png";
			document.getElementById("toggle_image").alt = "Expand";
			document.getElementById("toggle_image").title = "Expand";
		}
	};
	
	/**
	 * Hide the object with the given id
	 * @param objId - The id value of the object to hide 
	 */
	AChecker.hideByID = function (objId) {
		// hide button "make decision" when "known problems" tab is selected
		e = document.getElementById(objId);
		if (e != null) {
				e.style.display = 'none';
		}
	};

	/**
	 * Show the object with the given id
	 * @param objId - The id value of the object to show 
	 */
	AChecker.showByID = function (objId) {
		// hide button "make decision" when "known problems" tab is selected
		e = document.getElementById(objId);
		if (e != null) {
				e.style.display = 'block';
		}
	};

	/**
	 * Show the div with id == the given divId while hide all other divs in the array allDivIds
	 * @param divId: the id of the div to show
	 *        allDivIds: The array of div Ids that are in the same group of divId. divId must be in this array.
	 * @returns return false if divId does not exist. Otherwise, show divId and hide other divs in the array allDivIds 
	 */
	AChecker.showDivOutof = function (divId, allDivIds) {
		var i;

		var divToShow = document.getElementById(divId);
		if (divToShow == null) return false;

		for (i in allDivIds) {
			if (allDivIds[i] == divId) {
				AChecker.showByID(allDivIds[i]);
				eval('document.getElementById("menu_'+ allDivIds[i] +'").className = "active"');
			} else {
				AChecker.hideByID(allDivIds[i]);
				eval('document.getElementById("menu_'+ allDivIds[i] +'").className = ""');
			}
		}
	};
})();
