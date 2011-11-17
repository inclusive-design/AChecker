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

/*global window, jQuery*/

var AChecker = AChecker || {};

(function ($) {

    /**
     * global string function of trim()
     */
    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, "");
    };

    /**
     * Open up a 600*800 popup window
     */
    AChecker.popup = function (url) {
        var newwindow = window.open(url, 'popup', 'height=600, width=800, scrollbars=yes, resizable=yes');
        if (window.focus) {newwindow.focus();}
    };

    /**
     * Toggle the collapse/expand images, alt texts and titles associated with the link
     * @param objId
     */
    AChecker.toggleDiv = function (objId, toggleImgId) {
        var toc = $("#" + objId);
        if (!toc) {
            return;
        }

        var toggleImg = $("#" + toggleImgId);
        if (toc.is(":visible")) {
            toggleImg.attr("src", "images/arrow-closed.png");
            toggleImg.attr("alt", "Expand");
            toggleImg.attr("title", "Expand");
        } else {
            toggleImg.attr("src", "images/arrow-open.png");
            toggleImg.attr("alt", "Collapse");
            toggleImg.attr("title", "Collapse");
        }

        toc.slideToggle();
    };

    /**
     * Display and activate the selected tab div
     * @param divId: the id of the tab div to display
     *        divMapping: The mapping between the tab IDs and corresponding menu IDs.
     * @returns return false if divId does not exist. Otherwise, show divId and hide other divs in the array allDivIds 
     */
    AChecker.showDivOutof = function (divId, divMapping) {
        if (!$("#" + divId)) {
            return false;
        }

        for (var eachDivId in divMapping) {
            if (eachDivId === divId) {
                $("#" + divId).show();
                $("#" + divMapping[eachDivId].menuID).addClass("active");
            } else {
                $("#" + eachDivId).hide();
                $("#" + divMapping[eachDivId].menuID).removeClass("active");
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
        var cDivs = [];

        var d = $("#" + parentDivID); // parent div to expand the disabled div
        var e = $("#" + divID);  // the dynamically generated disabled div

        var xPos = e.offsetLeft;
        var yPos = e.offsetTop;
        var oWidth = e.offsetWidth;    
        var oHeight = e.offsetHeight;
        cDivs[cDivs.length] = document.createElement("DIV");
        cDivs[cDivs.length - 1].style.width = oWidth + "px";
        cDivs[cDivs.length - 1].style.height = oHeight + "px";
        cDivs[cDivs.length - 1].style.position = "absolute";
        cDivs[cDivs.length - 1].style.left = xPos + "px";
        cDivs[cDivs.length - 1].style.top = yPos + "px";
        cDivs[cDivs.length - 1].style.backgroundColor = "#999999";
        cDivs[cDivs.length - 1].style.opacity = 0.6;
        cDivs[cDivs.length - 1].style.filter = "alpha(opacity=60)";
        d.appendChild(cDivs[cDivs.length - 1]);
    };
})(jQuery);
