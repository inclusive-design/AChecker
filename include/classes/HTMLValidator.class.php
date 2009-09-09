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
* HTMLValidator
* Class for html validate
* This class sends html url or content to 3rd party validator (http://validator.w3.org/check) 
* and retrieve the returned results.
* @access	public
* @author	Cindy Qi Li
* @package checker
*/


include_once(AC_INCLUDE_PATH.'classes/Utility.class.php');

class HTMLValidator {

	// all private
	var $validator_url = "http://validator.w3.org/check";   // url of 3rd party validator
	//var $validator_url = "local";
	var $local_validator_command = "./";
	var $local_validator_path = "../local_validator/local_markup_validator/check "; // path validatore 
	var $local_validator_option = ""; // opzioni da dare al validatore, attualmente nessuna
	
	
	var $full_return; 						// full return from the 3rd party validator
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
	function HTMLValidator($type, $content)
	{
		$this->validate_type = $type;
		$this->validate_content = $content;
		
		if ($this->validate_type == "fragment")
			$result = $this->validate_fragment($this->validate_content);
			
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
		if 	($this->validator_url == "http://validator.w3.org/check")
			$return = @file_get_contents($this->validator_url. "?uri=".$uri);
		else 
		{
			$sys_command = $this->local_validator_command . $this->local_validator_path . $this->local_validator_option;
		
			exec($sys_command . "uri=".$uri, $retval);
	
			if (sizeof($retval) == 0)
			{
				// Se il validatore interno non e' disponibile, uso comunque quello esterno
				$this->validator_url = "http://validator.w3.org/check";
				// Se non trovo output dal validatore interno, uso il validatore esterno
				$return = @file_get_contents($this->validator_url. "?uri=".$uri);
			}
			else {
				$this->validator_url = "local";
				// Risultato validatore interno, il primo return chiama l'url invece che lo script
				//$return = file_get_contents($this->validator_url. "?uri=".$uri);
				//print_r($retval);
				echo "validatore interno!";
				$return = implode($retval);
				return implode($retval);
			}
		}
		//return file_get_contents($this->validator_url. "?uri=".$uri);
		return $return;
	}
	
	/**
	* private
  * send fragment to 3rd party and return its response
	*/
	function validate_fragment($fragment)
	{
		$data = array ('fragment' => $fragment, 'output' => 'html');
		
		$data = http_build_query($data);

		$response = $this->do_post_request($this->validator_url, $data);		

		return $response;
	}

	/**
	* private
  * send post request and html content to 
	*/
  function do_post_request($url, $data, $optional_headers = null)
  {
     $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
               ));
     if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
     }
     $ctx = stream_context_create($params);

     if (!($fp = @fopen($url, 'rb', false, $ctx))) 
     {
				$this->contain_errors = true;
				$this->msg = "Problem with $url, $php_errormsg";
				return false;
     }
     $response = @stream_get_contents($fp);
     if ($response === false) 
     {
				$this->contain_errors = true;
				$this->msg = "Problem reading data from $url, $php_errormsg";
				return false;
     }
     return $response;
  }
  
	/**
	* private
	* return errors/warnings by striping it out from validation output returned from 3rd party
	*/
	function stripOutResult($full_result)
	{
		// Simo: se viene usato il validatore interno bisogna modificare il risultato del validatore per renderlo simile a quello del validatore esterno
		if (  $this->validator_url == 'local')
		{		 
			$full_result = str_replace('<ol>', '<ol id="error_loop">', $full_result);
			$search = array('<span class="err_type">Error</span>',
							'<span class="err_type">Info</span>',
							'<span class="err_type">Warning</span>',"Line"," column");
			$replace = array('<span class="err_type"><img src="themes/vamola/images/error.png" alt="Error" title="Error" width="15" height="15" /></span>',
							 '<span class="err_type"><img src="themes/vamola/images/info.png" alt="Info" title="Info" width="15" height="15" /></span>',
							 '<span class="err_type"><img src="themes/vamola/images/warning.png" alt="Warning" title="Warning" width="15" height="15" /></span>',"Linea", ", Colonna");
		}
		elseif (  $this->validator_url == 'http://validator.w3.org/check') 
		{		
			$search = array('src="images/info_icons/error.png"',
											'src="images/info_icons/info.png"',
											'src="images/info_icons/warning.png"');
			$replace = array('src="images/error.png" width="15" height="15"',
											'src="themes/vamola/images/info.png" width="15" height="15"',
											'src="themes/vamola/images/warning.png" width="15" height="15"');
		}	
	 
		$pattern1 = '/'. preg_quote('<div id="result">', '/') . '.*'. preg_quote('</div><!-- end of "result" -->', '/').'/s';   // when no errors
		$pattern2 = '/('.preg_quote('<ol id="error_loop">', '/').'.*)'. preg_quote('</ol>', '/').'/s'; // when has errors
		
		// Pattern per validatore interno quando incontra errori nel parsing
		$pattern3 = '/Sorry! This document can not be checked./';
		

		
		// Simo: Sezione per ricavare il doctype dichiarato.
		$full_result_notag = strip_tags($full_result);
		$full_result_notag = str_ireplace(array("\n","\r", "/n","/r"), "", $full_result_notag);
		$full_result_notag = str_ireplace("  ", " ", $full_result_notag);
		$full_result_notag = str_ireplace("(no Doctype found)", "Nessun Doctype dichiarato", $full_result_notag);
		$start_dtd = strpos($full_result_notag, "Doctype:");
		// Per il validatore esterno, ma e' soggetto a cambiamenti nel tempo
		$end_dtd = strpos($full_result_notag, "(detect automatically) HTML5 (experimental)");
		
		if (  $this->validator_url == 'local')
			$end_dtd = strpos($full_result_notag, "(detect automatically) XHTML 1.0 Strict");
		
		
		if ($end_dtd === false){
			$end_dtd = strpos($full_result_notag, "Root Namespace:");
		}
		if ($start_dtd !== false && $end_dtd !== false){	
			$this->doctype = trim(substr($full_result_notag,$start_dtd+8,$end_dtd-($start_dtd+8)));
		}
		else $this->doctype = "";

		// Fine sezione doctype	
		
		
		
		
		if (@preg_match($pattern1, $full_result, $matches))
			return $matches[0];
		else if (@preg_match($pattern2, $full_result, $matches))
		{
			$result = $matches[1];

			$result = str_replace($search, $replace, $result);
		//	$result = str_replace('<ol id="error_loop">', "", $result);
		//	$result = str_replace('</ol>', "", $result);
			$pattern4 = '/<p class="helpwanted">(.*?)<\/p>/si';
			
			// Aggiungo l'ol finale
			$result .= "</ol>";
			
			$result = preg_replace($pattern4,"", $result);

			return $result;
		}
		else if (@preg_match($pattern3, $full_result, $matches))
		{
			$this->contain_errors = true;
			$this->msg = '<p class="msg_err">Errore nel validatore markup HTML. Spiacenti, questo documento non pu&ograve; essere verificato.</p><br/>';
			return false;
		}
		else
		{
			$this->contain_errors = true;
			$this->msg = '<p class="msg_err">Errore nel validatore markup HTML. Non &egrave; stato possibile trovare il report dei risultati dal validatore.</p><br/>';
			return false;
		}
	}
	
	/**
	* private
	* return number of errors by striping it out from validation output returned from 3rd party
	*/
	function stripOutNumOfErrors($full_result)
	{
		// Stringa di errore del validatore interno
		if ( $this->validator_url == 'local')
		{
			$pattern1 = '/Failed validation,\s*(\w+) error/';
		}
		elseif (  $this->validator_url == 'http://validator.w3.org/check') 
		{
			$pattern1 = '/' .preg_quote('<th>Result:</th>', '/').'\s*'.preg_quote('<td colspan="2" class="invalid">', '/').	'\s*(\w+) Error/s';  		 // when has errors
		}		
		if (@preg_match($pattern1, $full_result, $matches))  // when has errors
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
	
	
	// Sezione per restituire il Doctype
	/**
	* public 
	* return validation report in html
	*/
	function getDoctype()
	{
		return $this->doctype;
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