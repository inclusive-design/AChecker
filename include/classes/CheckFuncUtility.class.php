<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto			    */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

/**
* CheckFuncUtility.class.php
* Utility class for check eval functions
*
* @access	public
* @author	Cindy Qi Li
* @package checker
*/

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");

class CheckFuncUtility {
	
	/**
	* check syntax of the code that is used in eval()
	* @access  public
	* @param   $code
	* @return  true: correct syntax; 
	*          false: wrong syntax
	* @author  Cindy Qi Li
	*/
	public static function validateSyntax($code) 
	{
    	return @eval('return true;' . $code);
	}

	/**
	* Convert php code defined in checks.func into script 
	* @access  public
	* @param   $code
	* @return  true: correct syntax; 
	*          false: wrong syntax
	* @author  Cindy Qi Li
	*/
	public static function convertCode($code) 
	{
    	
	}
}
?>  
