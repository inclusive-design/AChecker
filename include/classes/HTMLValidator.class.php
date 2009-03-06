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
			if (!Utility::isURIValid($this->validate_content))
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
		return file_get_contents($this->validator_url. "?uri=".$uri);
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
		$pattern1 = '/'. preg_quote('<div id="result">', '/') . '.*'. preg_quote('</div><!-- end of "result" -->', '/').'/s';   // when no errors
		$pattern2 = '/('.preg_quote('<ol id="error_loop">', '/').'.*'. preg_quote('</ol>', '/').')/s'; // when has errors

		if (preg_match($pattern1, $full_result, $matches))
			return $matches[0];
		else if (preg_match($pattern2, $full_result, $matches))
		{
			$result = $matches[1];
			
			$search = array('src="images/info_icons/error.png"',
											'src="images/info_icons/info.png"',
											'src="images/info_icons/warning.png"');
			$replace = array('src="images/error.png" width="15" height="15"',
											'src="images/info.png" width="15" height="15"',
											'src="images/warning.png" width="15" height="15"');
			
			return str_replace($search, $replace, $result);
		}
		else
		{
			$this->contain_errors = true;
			$this->msg = "Cannot find result report from the return of the validator";
			return false;
		}
	}
	
	/**
	* private
	* return number of errors by striping it out from validation output returned from 3rd party
	*/
	function stripOutNumOfErrors($full_result)
	{
		$pattern1 = '/' .preg_quote('<th>Result:</th>', '/').'\s*'.preg_quote('<td colspan="2" class="invalid">', '/').
								'\s*(\w+) Error/s';   // when has errors

		// when has errors
		if (preg_match($pattern1, $full_result, $matches))  // when has errors
			return $matches[1];
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
