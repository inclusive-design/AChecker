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
// $Id: checker_input_form.tmpl.php 463 2011-01-27 20:39:26Z cindy $

var AChecker = AChecker || {};

(function() {

	/*************** Global Javascript variables ***************/
	// The mapping between the tab IDs and their corresponding menu IDs on the validator input form
	AChecker.inputDivMapping = {
		"AC_by_uri": "AC_menu_by_uri", 
		"AC_by_upload": "AC_menu_by_upload", 
		"AC_by_paste": "AC_menu_by_paste"
	}
	/*************** End of Global Javascript variables ***************/

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
				$("#"+ AChecker.inputDivMapping[allDivIds[i]]).addClass("active");
			} else {
				AChecker.hideByID(allDivIds[i]);
				$("#"+ AChecker.inputDivMapping[allDivIds[i]]).removeClass("active");
			}
		}
	};
	
	/**
	 * Covers the DIV (divID) with a dynamically-generated disabled look-and-feel div.
	 * The disabled div has the same size of divID (the 1st parameter) and is appended
	 * onto the parentDivID (the 2nd parameter).
	 * and append it to the pa 
	 * @param divID: the div to cover
	 * @param parentDivID: the parent div of divID (1st parameter)
	 */
	AChecker.disableDiv = function (divID, parentDivID) {
		var cDivs = new Array();
		
		d = document.getElementById(parentDivID); // parent div to expand the disabled div
		e = document.getElementById(divID);  // the dynamically generated disabled div

	    xPos = e.offsetLeft;
	    yPos = e.offsetTop;
	    oWidth = e.offsetWidth;    
	    oHeight = e.offsetHeight;
	    cDivs[cDivs.length] = document.createElement("DIV");
	    cDivs[cDivs.length-1].style.width = oWidth+"px";
	    cDivs[cDivs.length-1].style.height = oHeight+"px";
	    cDivs[cDivs.length-1].style.position = "absolute";
	    cDivs[cDivs.length-1].style.left = xPos+"px";
	    cDivs[cDivs.length-1].style.top = yPos+"px";
	    cDivs[cDivs.length-1].style.backgroundColor = "#999999";
	    cDivs[cDivs.length-1].style.opacity = .6;
	    cDivs[cDivs.length-1].style.filter = "alpha(opacity=60)";
	    d.appendChild(cDivs[cDivs.length-1]);
	};
})();
