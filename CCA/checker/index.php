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

define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
include_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/ChecksDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/UserLinksDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/Decision.class.php');

// Simo: inclusione file per le variabili di sessione //////////////////////////////
include_once(AC_INCLUDE_PATH. 'session_vamola.php');


$guidelinesDAO = new GuidelinesDAO();


// process to make decision
if (isset($_REQUEST['make_decision']) || isset($_REQUEST['reverse']))
{
	$decision = new Decision($_SESSION['user_id'], $_REQUEST['uri'], $_REQUEST['output'], $_REQUEST['jsessionid']);
	
	if ($decision->hasError())
	{
		$decision_error = $decision->getErrorRpt();  // displays in checker_input_form.tmpl.php
	}
	else
	{
		// make decsions
		if (isset($_REQUEST['make_decision'])) $decision->makeDecisions($_REQUEST['d']);
		
		// reverse decision
		if (isset($_REQUEST['reverse'])) 
		{
			foreach ($_REQUEST['reverse'] as $sequenceID => $garbage)
				$sequences[] = $sequenceID;
			
			$decision->reverseDecisions($sequences);
		}
	}
}
// end of process to made decision

// validate referer URI: error check and initialization
if ($_GET['uri'] == 'referer')
{
	// validate if the URI from referer matches the URI defined in user_links.user_link_id
	if (isset($_GET['id']))
	{
		$userLinksDAO = new UserLinksDAO();
		$row = $userLinksDAO->getByUserLinkID($_GET['id']);
		
		$pos_user_link_uri = strpos($row['URI'], '?');
		if ($pos_user_link_URI > 0) $user_link_uri = substr($row['URI'], 0, $pos_user_link_uri);
		else $user_link_uri = $row['URI'];

		$pos_referer_uri = strpos($_SERVER['HTTP_REFERER'], '?');
		if ($pos_referer_uri > 0) $referer_uri = substr($_SERVER['HTTP_REFERER'], 0, $pos_referer_uri);
		else $referer_uri = $_SERVER['HTTP_REFERER'];
		
		// guideline id must be given if the request is to check referer URI
		 if (!isset($_GET['gid']))
			$msg->addError('EMPTY_GID');
		else
		{
			$grow = $guidelinesDAO->getGuidelineByAbbr($_GET['gid']);
			if (!is_array($grow))
				$msg->addError('INVALID_GID');
		}
		
		if (!stristr($referer_uri, $user_link_uri))
			$msg->addError('REFERER_URI_NOT_MATCH');
		
		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] <> $row['user_id'])
			$msg->addError('USER_NOT_MATCH');
	}
	
	if (!$msg->containsErrors())
	{
		$_REQUEST['validate_uri'] = 1;
		$_REQUEST['uri'] = $_SERVER['HTTP_REFERER'];
		$_REQUEST['gid'] = array($grow[0]['guideline_id']);
	}
}

// a flag to record if there's problem validating html thru 3rd party web service
$error_happen = false;

// validate html
if (isset($_REQUEST["enable_html_validation"]))
	include(AC_INCLUDE_PATH. "classes/HTMLValidator.class.php");

// Simo: validazione css
if (isset($_REQUEST["enable_css_validation"]))
	include(AC_INCLUDE_PATH. "classes/CSSValidator.class.php");



if ($_REQUEST["validate_uri"])
{	
	
	//MB controllo selezione requisiti
	if(in_array(10,$_SESSION['gid']) && $_SESSION["req"][0]=="0")
	//if(!isset($_REQUEST["req"]))
	{	
		//print_r($_SESSION['gid']);
		$msg->addError('NO_REQUISITES_STANCA');
	}
	//MB~	
	
	if (!isset($_REQUEST["gid"]))
		$msg->addError('NO_GIDS');
	
	$uri = Utility::getValidURI($_REQUEST["uri"]);
	
	// Check if the given URI is connectable
	if ($uri === false)
	{
		$msg->addError(array('CANNOT_CONNECT', $_REQUEST['uri']));
	}
	
	// don't accept localhost URI
	if (stripos($uri, '://localhost') > 0)
	{
		$msg->addError('NOT_LOCALHOST');
	}
	
	
	
	if (!$msg->containsErrors())
	{
		$_REQUEST['uri'] = $uri;
		$validate_content = @file_get_contents($uri);
		
		if (isset($_REQUEST["enable_html_validation"]))
			$htmlValidator = new HTMLValidator("uri", $uri);

	    ////////////////////////////////////////////////////////////////////////////		
		//Simo: ho inserito il CSS Validator
		if (isset($_REQUEST["enable_css_validation"]))
			$cssValidator = new CSSValidator("uri", $uri);	
	    ////////////////////////////////////////////////////////////////////////////
	    			
		if (isset($_REQUEST["show_source"]))
			$source_array = file($uri);
	}
	////////////////////////////////////////////////////////////////////////////////
	// Simo: se c'e' un errore, lo visualizzo e visualizzo l'input form
	
	
	
	else {
		include ("checker_input_form.php");
		//MB ho spostato la stampa degli errori dentro checker_input_form
		/*
		global $msg; 
		$msg->printAll();
		*/
	}
	
	////////////////////////////////////////////////////////////////////////////////
		
	

	
}
	

if ($_REQUEST["validate_file"])
{
	
	//MB controllo selezione requisiti
	if(in_array(10,$_SESSION['gid']) && $_SESSION["req"][0]=="0")
	//if(!isset($_REQUEST["req"]))
		$msg->addError('NO_REQUISITES_STANCA');
	//MB~		
	
	if (!isset($_REQUEST["gid"]))
		$msg->addError('NO_GIDS');
	
	//MB{ controllo l'estensione del file
	if(!is_valid_filename($_FILES['uploadfile']['name']))
		$msg->addError(array('WRONG_FILE', $_FILES['uploadfile']['name']));	
	
	if ($msg->containsErrors()){

		include ("checker_input_form.php");
		//MB nota: la stampa degli errori � dentro checker_input_form
	}
	//}MB
	else
	{
		$validate_content = file_get_contents($_FILES['uploadfile']['tmp_name']);
	
		if (isset($_REQUEST["enable_html_validation"]))
			$htmlValidator = new HTMLValidator("fragment", $validate_content);
				
		
	    ////////////////////////////////////////////////////////////////////////////////		
		//Simo: ho inserito il CSS Validator
		//if (isset($_REQUEST["enable_css_validation"]))
		//	$cssValidator = new CSSValidator("uri", $uri);	
	    ////////////////////////////////////////////////////////////////////////////////		
			
		if (isset($_REQUEST["show_source"]))
			$source_array = file($_FILES['uploadfile']['tmp_name']);
		
	}
	
	
	
	
}
// end of validating html and css

$has_enough_memory = true;
if (isset($validate_content) && !Utility::hasEnoughMemory(strlen($validate_content)))
{
	$msg->addError('NO_ENOUGH_MEMORY');
	$has_enough_memory = false;
}




// validation and display result

if ($_REQUEST["validate_uri"] || $_REQUEST["validate_file"] )
{
	//MB if (!isset($_REQUEST["gid"])) $_REQUEST["gid"] = array(DEFAULT_GUIDELINE);
	// check accessibility
	include(AC_INCLUDE_PATH. "classes/AccessibilityValidator.class.php");

	if ($_REQUEST["validate_uri"]) $check_uri = $_REQUEST['uri'];
	
	if (isset($validate_content) && $has_enough_memory)
	{
		$aValidator = new AccessibilityValidator($validate_content, $_REQUEST["gid"], $check_uri);
		$aValidator->validate();
	}
	// end of checking accessibility

	// display validation results
	if (isset($aValidator) || isset($htmlValidator))
	{
		include ("checker_results.php");
	}
	else
	{
		$show_achecker_whatis = true;
	}
}
////////////////////////////////////////////////////////////////////////////////////
// Simo: Se ho gia' i risultati nella variabile di sessione, li mostro 
else if (isset($_SESSION["risultati"]))
{
	include ("checker_results.php");
}
else
{
////////////////////////////////////////////////////////////////////////////////////		
//Simo: input form solo nella pagina iniziale	
	// display initial validation form: input URI or upload a html file
	 
	include ("checker_input_form.php");
	$show_achecker_whatis = true;
}
/* rimuovo la descrizione di achecker, eventualmente andrebbe adattata per vamola'
if ($show_achecker_whatis)
{
	echo '<div id="output_div" class="validator-output-form">';
	echo _AC('achecker_whatis');
	echo '</div>';
}
*/

// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>
