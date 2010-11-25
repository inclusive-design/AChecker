<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2010                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
* CSSValidator
* Class for css validate
* This class sends css url to 3rd party validator
* and retrieve the returned results.
* @access	public
* @author	Simone Spagnoli
* @package checker
*/

include_once(AC_INCLUDE_PATH.'classes/Utility.class.php');

class CSSValidator {

	var $validator_url = "http://jigsaw.w3.org/css-validator/validator";
	var $local_validator_command = "java -jar ";
	var $local_validator_path = "../local_validator/local_css_validator/css-validator.jar "; // path validatore 
	var $local_validator_option = "--output=xhtml --warning=0 --lang=it ";
	
	
	var $full_return;             // full return from the 3rd party validator
	var $result;                  // result section stripped from $full_return
	var $num_of_errors = 0;       // number of errors
	
	var $contain_errors = false;  // true or false. if error happens in process
	var $msg;                     // not null when $contain_errors is true. detail error message in process
	
	var $validate_type;           // uri or fragment
	var $validate_content;        // uri or html content
	
	/**
	* private
  * main process
	*/
	function CSSValidator($type, $content)
	{
		$this->validate_type = $type;
		$this->validate_content = $content;

		if ($this->validate_type == "uri")
		{
			if (Utility::getValidURI($this->validate_content) === false)
			{
				$this->contain_errors = true;
				$this->msg = "Error: Cannot connect to <strong>".$uri. "</strong>";
				return false;
			}
			$result = $this->validate_uri($this->validate_content);
		}

		if (!result) return false;
		else
		{
			$this->full_return = $result;
			
			$this->result = $this->stripOutResult($result);
			$this->num_of_errors = $this->stripOutNumOfErrors($result);
			return true;
		}
	}
	
	/**
	* private
  * send uri to 3rd party and return its response
	*/
	function validate_uri($uri)
	{

		$sys_command = $this->local_validator_command . $this->local_validator_path . $this->local_validator_option;

		exec($sys_command . $uri, $retval);

		if (sizeof($retval) == 0)
		{// Se non trovo output dal validatore interno, uso il validatore esterno
			$content = @file_get_contents($this->validator_url. "?uri=".$uri."&warning=0&lang=it");
		}
		else {
			// Risultato validatore interno
			//echo "validatore interno";
			$content = implode($retval);
		}
	
		if ($content != null)
		{
			return $content;
		}
		return false;	
	}

  
	/**
	* private
	* return errors/warnings by striping it out from validation output returned from 3rd party
	*/
	function stripOutResult($full_result)
	{

		$pattern1 = '/('.preg_quote("<div id='congrats'>", '/').'.*)/s'; // nessun errore
		//$pattern2 = '/('.preg_quote('<div id="errors">', '/').'.*)/s'; // when has errors
		$pattern2 = '/('.preg_quote("<div class='error-section-all'>", '/').'.*)/s'; // when has errors
		$pattern3 = '/('.preg_quote('<p class="backtop"><a href="#banner">&uarr; Top</a></p>', '/').'.*)/s'; // when has errors
		$pattern4 = '/('.preg_quote('<div id="warnings">', '/').'.*)/s'; // when has errors

		
	
		if (preg_match($pattern1, $full_result, $matches))
			return $matches[0];
		else if (preg_match($pattern2, $full_result, $matches))
		{

			if (preg_match($pattern3, $full_result, $matches2))
			{
				$result_exp = explode('<p class="backtop"><a href="#banner">&uarr; Top</a></p>', $matches[0]);
			}
			else if (preg_match($pattern4, $full_result, $matches2))
			{
				$result_exp = explode('<div id="warnings">', $matches[0]);
			}
			
			$result = $result_exp[0];

			
			$res_exp = explode("<div class='error-section'>", $result);
			
			// Formatta il risultato
			for ($i=0; $i<sizeof($res_exp); $i++)
			{
				if ($i==0)
				{		
					// "primo ciclo";
					$res_exp[$i] = str_replace("<div class='error-section-all'>", "<ul class='msg_err'>", $res_exp[$i]);
					//$res_exp[$i] = str_replace('<div id="errors">', "<ol>", $res_exp[$i]);
					
				}
				elseif ( $i==(sizeof($res_exp)-1) )
				{
					// "ultimo ciclo";
					$res_exp[$i] =  '<li class="msg_err">'. $res_exp[$i];
					$res_exp[$i] = str_replace("<h4>", '<span class="msg"><strong>', $res_exp[$i]);
					$res_exp[$i] = str_replace("</h4>", '</strong></span>', $res_exp[$i]);
					$res_exp[$i] = str_replace("<table>", "<table class='css_error' cellspacing='4px'><tr><th>Riga</th><th>Selettore</th><th>Errore</th></tr>", $res_exp[$i]);
					
					$res_exp_int = explode("</div>", $res_exp[$i]);	
					for ($j=0; $j<sizeof($res_exp_int)-1; $j++)
					{
						if ($j==0)
						{		
							//"primo ciclo interno";
							$res_exp_int[$j] = $res_exp_int[$j] . "</li>";
						}
						else if ($j==sizeof($res_exp_int)-2)
						{		
							//"ultimo ciclo interno";
						//	$res_exp_int[$j] = "</div>";
						}
						else 
						{
							//"ciclo interno";
							$res_exp_int[$j] = "</ul>";
						}		
					}
					$res_exp[$i] = implode('', $res_exp_int);

				}
				else 
				{
					// "ciclo nel mezzo";
					$res_exp[$i] =  '<li class="msg_err">'. $res_exp[$i];
					$res_exp[$i] = str_replace("</div>", "</li>", $res_exp[$i]);
					$res_exp[$i] = str_replace("<h4>", '<span class="msg"><strong>', $res_exp[$i]);
					$res_exp[$i] = str_replace("</h4>", '</strong></span>', $res_exp[$i]);
					$res_exp[$i] = str_replace("<table>", "<table class='css_error' cellspacing='4px'><tr><th>Riga</th><th>Selettore</th><th>Errore</th></tr>", $res_exp[$i]);
					
				}				
			}
			
			$result = implode('', $res_exp);
			

			
			return $result;
		}
		else
		{
			$this->contain_errors = true;
			$this->msg = '<p class="msg_err">Errore nel validatore CSS. Non &egrave; stato possibile trovare il report dei risultati dal validatore.</p><br/>';
			return false;
		}

	}
	
	/**
	* private
	* return number of errors by striping it out from validation output returned from 3rd party
	*/
	function stripOutNumOfErrors($full_result)
	{

		$pattern1 = '/Errori \((\w+)\)/';
			
		if (preg_match($pattern1, $full_result, $matches))  // se fa match restituisce il numero degli errori trovati
		{
			return $matches[1];}
		else
		{
			return 0;
		}
	}
	
	/**
	* public 
	* return validation report in html
	*/
	function getValidationRpt()
	{
		return $this->result;
	}

	// public 
	function getNumOfValidateError()
	{
		return $this->num_of_errors;
	}

	/**
	* public 
	* return error message
	*/
	function getErrorMsg()
	{
		return $this->msg;
	}
	
	/**
	* public 
	* return true or false: if error happens during process
	*/
	function containErrors()
	{
		return $this->contain_errors;
	}

	
}
?>  