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
* AccessibilityValidator
* Class for accessibility validate
* This class checks the accessibility of the given html based on requested guidelines. 
* @access	public
* @author	Cindy Qi Li
* @package checker
*/
if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");

include (AC_INCLUDE_PATH . "lib/simple_html_dom.php");
include_once (AC_INCLUDE_PATH . "classes/Checks.class.php");
include_once (AC_INCLUDE_PATH . "classes/BasicFunctions.class.php");
include_once (AC_INCLUDE_PATH . "classes/CheckFuncUtility.class.php");
include_once (AC_INCLUDE_PATH . "classes/DAO/ChecksDAO.class.php");

define("SUCCESS_RESULT", "success");
define("FAIL_RESULT", "fail");

class AccessibilityValidator {

	// all private
	var $num_of_errors = 0;              // number of errors
	
	var $validate_content;               // html content to check
	var $guidelines;                     // array, guidelines to check on
	var $uri;                            // the URI that $validate_content is from, used in check image size in BasicFunctions
	
	// structure: line_number, check_id, result (success, fail)
	var $result = array();               // all check results, including success ones and failed ones
	
	var $check_for_all_elements_array = array(); // array of the to-be-checked check_ids 
	var $check_for_tag_array = array();          // array of the to-be-checked check_ids 
	var $prerequisite_check_array = array();     // array of prerequisite check_ids of the to-be-checked check_ids 
//	var $next_check_array = array();             // array of the next check_ids of the to-be-checked check_ids
	var $check_func_array = array();         // array of all the check functions 
		
	var $content_dom;                    // dom of $validate_content

	var $line_offset;                    // 1. ignore the problems on the lines before the line of $line_offset
	                                     // 2. report line_number = real_line_number - $line_offset 
	/**
	 * public
	 * $content: string, html content to check
	 * $guidelines: array, guidelines to check on
	 */
	function AccessibilityValidator($content, $guidelines, $uri = '')
	{
		$this->validate_content = $content;
		$this->guidelines = $guidelines;
		$this->line_offset = 0;
		$this->uri = $uri;
	}
	
	/* public
	 * Validation
	 */
	public function validate()
	{
		// dom of the content to be validated
		$this->content_dom = $this->get_simple_html_dom($this->validate_content);
		
		if($_SESSION["css_disable"]!=1)
		{		// eseguo questa parte solo se non è selezionato il check che disabilita i controlli CSS
				//Modifica Filo
				$csslist = $this ->get_style_external($this->validate_content);
				$cssinternal= $this ->get_style_internal($this->validate_content);
				//File Modifica Filo		
		}

		// prepare gobal vars used in BasicFunctions.class.php to fasten the validation
		$this->prepare_global_vars();
		
		// set arrays of check_id, prerequisite check_id, next check_id
		$this->prepare_check_arrays($this->guidelines);

		if($_SESSION["css_disable"]!=1)
		{
			//creo la struttura dati contenente i dati dei css
			$this->prepare_css_arrays($csslist,$cssinternal); 
			global $selettori_appoggio;
			VamolaBasicChecks::sistemaSelettori();
	
		}
		
		$this->validate_element($this->content_dom->find('html'));
		$this->finalize();

		// end of validation process
	}
	
	/** private
	 * set global vars used in Checks.class.php and BasicFunctions.class.php
	 * to fasten the validation process.
	 * return nothing.
	 */
	private function prepare_global_vars()
	{
		global $header_array, $base_href;

		// find all header tags which are used in BasicFunctions.class.php
		$header_array = $this->content_dom->find("h1, h2, h3, h4, h5, h6, h7");

		// find base href, used to check image size
		$all_base_elements = $this->content_dom->find("base");

		if (is_array($all_base_elements))
		{
			foreach ($all_base_elements as $base)
			{
				if (isset($base->attr['href']))
				{
					$base_href = $base->attr['href'];
					break;
				}
			}
		}

		// set all check functions
		$checksDAO = new ChecksDAO();
		$rows = $checksDAO->getAllOpenChecks();
		
		if (is_array($rows))
		{
			foreach ($rows as $row)
				$this->check_func_array[$row['check_id']] = CheckFuncUtility::convertCode($row['func']);
		}
	}
	
	/** private
	 * return a simple_html_dom on the given content.
	 * Because accessibility check is based on the root html element <html>,
	 * check if dom has html tag <html>, if no, add it and the end tag to the content
	 * and return the dom on modified content.
	 */
	private function get_simple_html_dom($content)
	{
		$dom = str_get_dom($content);
		
		if (count($dom->find('html')) == 0)
			$dom = str_get_dom("<html>".$content."</html>");
			
		return $dom;
	}
	
	/**
	 * private
	 * generate arrays of check ids, prerequisite check ids, next check ids
	 * array structure:
	 check_array
	 (
	 [html_tag] => Array
	 (
	 [0] => check_id 1
	 [1] => check_id 2
	 ...
	 )
	 ...
	 )

	 prerequisite_check_array
	 (
	 [check_id] => Array
	 (
	 [0] => prerequisite_check_id 1
	 [1] => prerequisite_check_id 2
	 ...
	 )
	 ...
	 )

//	 next_check_array
//	 (
//	 [check_id] => Array
//	 (
//	 [0] => next_check_id 1
//	 [1] => next_check_id 2
//	 ...
//	 )
	 ...
	 )
	 */
	private function prepare_check_arrays($guidelines)
	{
		
		if (!($guideline_query = $this->convert_array_to_string($guidelines, ',')))
			return false;
		// validation process
		else  
		{
			// SIMO: gestione tab per Wcag e Stanca ///////////////////////////////////////////////
			$idStanca = 10; // Identificativo Legge Stanca
			$idWcagA = 7;
			$idWcagAA = 8;
			$idWcagAAA = 8;
			
			$isWcag = FALSE;
			$isStanca = FALSE;
				
			$arrayGid = $guidelines;
			if (is_array($arrayGid) && in_array($idStanca, $arrayGid))
				$isStanca = TRUE;
			
			if (is_array($arrayGid) && (in_array($idWcagA, $arrayGid) || in_array($idWcagAA, $arrayGid) || in_array($idWcagAAA, $arrayGid)))
				$isWcag = TRUE;	
				
			if ($isStanca && $isWcag)
			{
				$_SESSION["show_nav"]="true";
				$_SESSION["show"]="all";
				$_SESSION["tab_ris"]="1";
			}
			else if ($isStanca && !$isWcag)
			{
				$_SESSION["show"]="stanca";$_SESSION["tab_ris"]="1";
			}
			else if (!$isStanca && $isWcag)
			{
				$_SESSION["show"]="wcag";$_SESSION["tab_ris"]="5";
			}	
			// SIMO: fine gestione tab per Wcag e Stanca //////////////////////////////////////////
			
			$checksDAO = new ChecksDAO();
			
			// generate array of "all element"
			$rows = $checksDAO->getOpenChecksForAllByGuidelineIDs($guideline_query);
			//MB $rows = $checksDAO->getOpenChecksForAllByGroupIDs($guideline_query);
			
			$count = 0;
			if (is_array($rows))
			{
				foreach ($rows as $id => $row)
					$this->check_for_all_elements_array[$count++] = $row["check_id"];
			}
			
			// generate array of check_id
			$rows = $checksDAO->getOpenChecksNotForAllByGuidelineIDs($guideline_query);
			//MB $rows = $checksDAO->getOpenChecksNotForAllByGroupIDs($guideline_query);
			
			if (is_array($rows))
			{
				foreach ($rows as $id => $row)
				{
					if ($row["html_tag"] <> $prev_html_tag && $prev_html_tag <> "") $count = 0;
					
					$this->check_for_tag_array[$row["html_tag"]][$count++] = $row["check_id"];
					
					$prev_html_tag = $row["html_tag"];
				}
			}
			
			// generate array of prerequisite check_ids
			
			$rows = $checksDAO->getOpenPreChecksByGuidelineIDs($guideline_query);

			if (is_array($rows))
			{
				foreach ($rows as $id => $row)
				{
					if ($row["check_id"] <> $prev_check_id)  $prerequisite_check_array[$row["check_id"]] = array();
					
					array_push($prerequisite_check_array[$row["check_id"]], $row["prerequisite_check_id"]);
					
					$prev_check_id = $row["check_id"];
				}
			}
			$this->prerequisite_check_array = $prerequisite_check_array;

			// generate array of next check_ids
//			$rows = $checksDAO->getOpenNextChecksByGuidelineIDs($guideline_query);
//
//			if (is_array($rows))
//			{
//				foreach ($rows as $id => $row)
//				{
//					if ($row["check_id"] <> $prev_check_id)  $next_check_array[$row["check_id"]] = array();
//					
//					array_push($next_check_array[$row["check_id"]], $row["next_check_id"]);
//					
//					$prev_check_id = $row["check_id"];
//				}
//			}
//			$this->next_check_array = $next_check_array;
//			debug($this->next_check_array);
			return true;
		}
	}

	/**
	 * private
	 * Recursive function to validate html elements
	 */
	private function validate_element($element_array)
	{
		foreach($element_array as $e)
		{
			// generate array of checks for the html tag of this element
			if (is_array($this->check_for_tag_array[$e->tag]))
				$check_array[$e->tag] = array_merge($this->check_for_tag_array[$e->tag], $this->check_for_all_elements_array);
			else
				$check_array[$e->tag] = $this->check_for_all_elements_array;
				
			foreach ($check_array[$e->tag] as $check_id)
			{
				// check prerequisite ids first, if fails, report failure and don't need to proceed with $check_id
				$prerequisite_failed = false;
//debug($check_id);
//debug($this->prerequisite_check_array[$check_id]);
//debug($this->next_check_array[$check_id]);
				if (is_array($this->prerequisite_check_array[$check_id]))
				{
					foreach ($this->prerequisite_check_array[$check_id] as $prerequisite_check_id)
					{
						$check_result = $this->check($e, $prerequisite_check_id);
						
						if ($check_result == FAIL_RESULT)
						{
							$prerequisite_failed = true;
							break;
						}
					}
				}

				// if prerequisite check passes, proceed with current check_id
				if (!$prerequisite_failed)
				{
					$check_result = $this->check($e, $check_id);
					
					// if check_id passes, proceed with next checks
//					if ($check_result == SUCCESS_RESULT)
//					{
//						if (is_array($this->next_check_array[$check_id]))
//							foreach ($this->next_check_array[$check_id] as $next_check_id)
//							{
//								$this->check($e, $next_check_id);
//							}
//					}
				}
			}
			
			$this->validate_element($e->children());
		}
	}

	/**
	 * private
	 * check given html dom node for given check_id, save result into $this->result
	 * parameters:
	 * $e: simple html dom node
	 * $check_id: check id
	 *
	 * return "success" or "fail"
	 */
	private function check($e, $check_id)
	{
		global $msg;
		//echo "Check" . $check_id . "<br/>"; 
		// don't check the lines before $line_offset
		if ($e->linenumber <= $this->line_offset) return;
		
		// Simo: vecchia eval
		// run function for $check_id
		if(($check_id > 1074 && $check_id < 1085) || $check_id >1091 && $check_id <1105 
			|| $check_id >=5000 && $check_id <=5025 || $check_id >=6000 && $check_id <=6001 || $check_id >=6003 && $check_id <=6011 
			|| $check_id >=12000 && $check_id <=12031 || $check_id >=21007 && $check_id <=21023)	
		{	// Simo: per ora niente css
			//$check_result = true;
			
			global $csslist;			
			global $array_css;
			$array_css=array();
			$spazio="{_}";
			
			eval("\$check_result = Checks::check_" . $check_id . "(\$e, \$this->content_dom, 1);");
								if ($check_result)  // success
								{
									$result = SUCCESS_RESULT;
								}
								else
								{
									$result = FAIL_RESULT;
								}
								
																
								//regole css relative all'errore
								$css_code="";
								if( isset($array_css) && $array_css!=null)
								{
									//print_r($array_css);
									
										$css_code="<p style='padding:1em'>Regole CSS relative all'errore: </p>\n\t\n\t<pre>\n\t\n\t";
										foreach($array_css as $rule)
										{
											if($rule["idcss"]==sizeof($csslist))//ultimo posto, stile interno
												$css_code=$css_code."CSS interno (contenuto nell'elemento <code>style</code> della pagina):\n\t\n\t      ";
											else
												$css_code=$css_code."CSS esterno (<a title='link al CSS esterno' href='".$csslist[$rule["idcss"]]."'>".$csslist[$rule["idcss"]]."</a>):\n\t\n\t      ";
											
											for($i=sizeof($rule["prev"])-1; $i>=0;$i--)
											{
												$css_code=$css_code." ".$rule["prev"][$i];
											}
											$css_code=str_ireplace(" .",".",$css_code);
											$css_code=str_ireplace(" #","#",$css_code);
											$css_code=str_ireplace(">.","> .",$css_code);
											$css_code=str_ireplace(">#","> #",$css_code);
											$css_code=str_ireplace("+.","+ .",$css_code);
											$css_code=str_ireplace("+#","+ #",$css_code);
											$css_code=str_ireplace(" ".$spazio,"",$css_code);
											
											$css_code=$css_code."{\n\t\n\t";
											
											foreach($rule["regole"] as $prop => $value)
											{
												$css_code=$css_code."            ".$prop.":".$value["val"].";\n\t";
											}
											$css_code=$css_code."      }\n\t\n\t";
										}
										$css_code=$css_code."</pre>\n\t";
										
										//echo($css_code);
								}
			
		}
		else 
			eval("\$check_result = Checks::check_" . $check_id . "(\$e, \$this->content_dom);");
		
		// Simo: funzione nuova che prende dal db
		//$check_result = eval($this->check_func_array[$check_id]);
		
		
		
		//echo $check_id . "=".$check_result."funzione ". $this->check_func_array[$check_id].  "<br/>";
		if (is_null($check_result))
		{ // when $check_result is not true/false, must be something wrong with the check function.
		  // show warning message and skip this check
			$checksDAO = new ChecksDAO();
			$row = $checksDAO->getCheckByID($check_id);
			$msg->addError(array('CHECK_FUNC', $row['html_tag'].': '._AC($row['name'])));
			
			// skip this check
			$check_result = true;
		}
		
		if ($check_result)  // success
		{
			$result = SUCCESS_RESULT;
		}
		else
		{
			$result = FAIL_RESULT;
		}

		// find out checked html tag code
		// http://www.atutor.ca/atutor/mantis/view.php?id=3768
		// Display link text with Check 19 to make it easier to make decisions
		if($check_id == 19) 
			$html_code = $e->outertext;
		else
			$html_code = substr($e->outertext, 0, strpos($e->outertext, '>')+1);

		// minus out the $line_offset from $linenumber 
		//MB salvo anche i controlli andati a buon fine se chiamata REST (soluzione momentanea) 
		if(isset($_GET['output']) && $_GET['output']=='rest')
			$this->save_result($e->linenumber-$this->line_offset, $e->colnumber, $html_code, $css_code, $check_id, $result);
		else 
		if ($result == FAIL_RESULT)
			$this->save_result($e->linenumber-$this->line_offset, $e->colnumber, $html_code, $css_code, $check_id, $result);

		
		return $result;
	}
	
	/**
	 * private
	 * get check result from $result. Return false if the result is not found.
	 * Parameters:
	 * $line_number: line number in the content for this check
	 * $check_id: check id
	 */
//	private function get_check_result($line_number, $col_number, $check_id)
//	{
//		foreach($this->result as $one_result)
//		{
//			if ($one_result["line_number"] == $line_number && $one_result["col_number"] == $col_number && $one_result["check_id"] == $check_id)
//				return $one_result["result"];
//		}
//		
//		return false;
//	}

	/**
	 * private
	 * save each check result
	 * Parameters:
	 * $line_number: line number in the content for this check
	 * $check_id: check id
	 * $result: result to save
	 */
	private function save_result($line_number, $col_number, $html_code, $css_code, $check_id, $result)
	{
		array_push($this->result, array("line_number"=>$line_number, "col_number"=>$col_number, "html_code"=>$html_code, "css_code"=>$css_code, "check_id"=>$check_id, "result"=>$result));
		
		return true;
	}
	
	/**
	 * private
	 * convert the given array to a string of the array elements separated by the given delimiter.
	 * For example:
	 * array ([0] => 7, [1] => 8)
	 * delimiter: ,
	 * is converted to string "7, 8"
	 */
	private function convert_array_to_string($in_array, $delimiter)
	{
		$count = 0;
		if (is_array($in_array))
		{
			foreach ($in_array as $element)
			{
				if ($count == 0) $str = $element;
				else $str .= $delimiter . $element;
				
				$count++;
			}
			return $str;
		}
		else
			return false;
	}
	
	/**
	 * private 
	 * generate class value: array of error results, number of errors
	 */
	private function finalize()
	{
		$this->num_of_errors = count($this->result);
	}
	
	/**
	 * public 
	 * set line offset
	 */
	public function setLineOffset($lineOffset)
	{
		$this->line_offset = $lineOffset;
	}
	
	/**
	 * public 
	 * return line offset
	 */
	public function getLineOffset()
	{
		return $this->line_offset;
	}
	
	/**
	 * public 
	 * return array of all checks that have been done, including successful and failed ones
	 */
	public function getValidationErrorRpt()
	{
		return $this->result;
	}
	

	/**
	 * public 
	 * return number of errors
	 */
	public function getNumOfValidateError()
	{
		return $this->num_of_errors;
	}

	/**
	 * public 
	 * return array of all checks that have been done by check id, including successful and failed ones
	 */
	public function getResultsByCheckID($check_id)
	{
		$rtn = array();
		foreach ($this->result as $oneResult)
			if ($oneResult["check_id"] == $check_id)
				array_push($rtn, array("line_number"=>$oneResult["line_number"], "col_number"=>$oneResult["col_number"], "check_id"=>$oneResult["check_id"], "result"=>$oneResult["result"]));
	
		return $rtn;
	}

	/**
	 * public 
	 * return array of all checks that have been done by line number, including successful and failed ones
	 */
	public function getResultsByLine($line_number)
	{
		$rtn = array();
		foreach ($this->result as $oneResult)
			if ($oneResult["line_number"] == $line_number)
				array_push($rtn, array("line_number"=>$oneResult["line_number"], "col_number"=>$oneResult["col_number"], "check_id"=>$oneResult["check_id"], "result"=>$oneResult["result"]));
	
		return $rtn;
	}

	
	
	
// funzioni per i css
	
	/* ritorna la lista degli stili esterni presenti nella pagina */
	function get_style_external($content){
		
		global $csslist;
		
		//MB
		$dom=str_get_dom($content);
		$vettore_link=$dom->find('link');
		$vettore_link=array_reverse($vettore_link);
		$i=0;
		foreach($vettore_link as $link)
		{	
			if ($link->attr["type"]=="text/css" && $link->attr["rel"]=="stylesheet" 
				&&(!isset($link->attr["media"]) ||$link->attr["media"]=="all" ||$link->attr["media"]=="screen" ))
			{
				$csslist[$i]=$link->attr["href"];
				$i++;
			}
					
		}
		
			
			
			if ($csslist=="") return $csslist;			
			//MB ripulisco gli url dei css
			global $uri;
			$uri2=VamolaBasicChecks::getSiteUri($uri);
			
			$i=1;
			//modifico gli indirizzi relativi dei fogli di stile
			foreach($csslist as $foglio)
			{
				$foglio=str_replace('"','',$foglio);
				if(stripos($foglio,"http://")===false)//indirizzo relativo
				{
					if(substr($foglio,0,1)=="/")
						$foglio=$uri2.$foglio;
					else
						$foglio=$uri2."/".$foglio;
				}
				$csslist[$i]=$foglio;
				$i++;
			}
			
			//print_r($csslist);
			return $csslist;
			
		}
	
	//La funzione ritorna i css di una pagina
	function get_style_internal($content){
		
				
		//MB
		$dom=str_get_dom($content);
		//echo("<p> contenuto di style: </p>");
		
		//modifico l'url del sito da validare in modo da porterci aggiungere l'indirizzo di un css relativo
		global $uri;
		$uri2=VamolaBasicChecks::getSiteUri($uri);
		
		$vettore_stili_interni=$dom->find('style');
		$cssint="";
		for($i=0;$i<sizeof($vettore_stili_interni);$i++)
		{
			if(!isset($vettore_stili_interni[$i]->attr["media"]) || $vettore_stili_interni[$i]->attr["media"]=="all" 
			|| $vettore_stili_interni[$i]->attr["media"]=="screen" )
			{
				$cssint=$cssint.$vettore_stili_interni[$i]->innertext;
				$cssint=trim($cssint);
				while(substr($cssint,0,7)=="@import")
				{
					$import = substr($cssint, 0, stripos($cssint,";") +1  );
					$cssint= str_ireplace($import, "", $cssint);
								
					$indirizzo = substr($import, stripos($import, '(')+1, stripos($import, ')') - stripos($import, '(') -1) ;
					$indirizzo = str_ireplace('"','', $indirizzo);
					
					if(stripos($indirizzo,"http://")===false)//indirizzo relativo
					{
						if(substr($indirizzo,0,1)=="/")
							$indirizzo=$uri2.$indirizzo;
						else
							$indirizzo=$uri2."/".$indirizzo;
					}
					
					$cssint= @file_get_contents($indirizzo)."\n".$cssint;
					
				}
			}
		}
		return $cssint;
		
	}


	
   //La funzione crea l'array degli stili (interni ed esterni) da sottoporre alla validazione.
    
  function prepare_css_arrays($array_css_esterni,$ci){
		for($b=0;$b<count($array_css_esterni);$b++){
			$css_content=@file_get_contents($array_css_esterni[$b]);
			VamolaBasicChecks::GetCSSDom($css_content,$b);  	
		}
		
		
		//MB
		//Insrisco nell'ultima posizione lo stile interno
		if($ci!=""){
			
				VamolaBasicChecks::GetCSSDom($ci,$b);
		}		
		
		
  }

	
	
}
?>  
