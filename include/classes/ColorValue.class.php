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
// $Id$

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include(AC_INCLUDE_PATH. 'classes/DAO/ColorMappingDAO.class.php');

/**
* ColorValue.class.php
* Class for accessibility validate
* This class accepts 3 color values: rgb(x,x,x), #xxxxxx, colorname
* and extracts according rgb values
*
* @access	public
* @author	Cindy Qi Li
* @package checker
*/

class ColorValue {

	// all private
	var $red;
	var $green;
	var $blue;
	
	var $isValid;

	function __construct($color)
	{
	  $color = str_replace(" ", "", $color);  // remove whitespaces
	  
	  if ($color[0] == '#') $color = substr($color, 1);
	  
	  $this->isValid = true;  // set default
	  if (strlen($color) == 6)
	  {
	    list($r, $g, $b) = array($color[0].$color[1],
	                             $color[2].$color[3],
	                             $color[4].$color[5]);
    }
    elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	  else if (substr($color, 0, 4) == "rgb(")
	  {
	  	$colorLine = substr($color, 4, strlen($color)-5);
	  	list($r, $g, $b) = explode(",", $colorLine);
	  }
	  else  // color name
	  {
			$colorMappingDAO = new ColorMappingDAO();
			$rows = $colorMappingDAO->GetByColorName($color);

//			$sql = "SELECT color_code FROM ". TABLE_PREFIX ."color_mapping WHERE color_name='".$color."'";
//			$result	= mysql_query($sql, $db) or die(mysql_error());
			
			if (!is_array($rows) == 0)
				$this->isValid = false;
			else
			{
				$row = $rows[0];
				$colorCode = $row["color_code"];

		    list($r, $g, $b) = array($colorCode[0].$colorCode[1],
		                             $colorCode[2].$colorCode[3],
		                             $colorCode[4].$colorCode[5]);
			}
	  }

	  $this->red = hexdec($r); 
	  $this->green = hexdec($g); 
	  $this->blue = hexdec($b);
	}

	/**
	* public
	* Return if the color value is valid
	*/
	function isValid()
	{
		return $this->isValid;
	}

	/**
	* public
	* Return red value
	*/
	function getRed()
	{
		return $this->red;
	}

	/**
	* public
	* Return green value
	*/
	function getGreen()
	{
		return $this->green;
	}

	/**
	* public
	* Return blue value
	*/
	function getBlue()
	{
		return $this->blue;
	}
}
?>