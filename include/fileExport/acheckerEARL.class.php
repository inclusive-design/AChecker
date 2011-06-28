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
// $Id: 

/**
* acheckerEARL
* Class to generate error report in XML based EARL format (.rdf file)
* for each of types: known, likely, potential, all 
* @access	public
* @author	Casian Olga
*/
if (!defined("AC_INCLUDE_PATH")) exit;
include_once(AC_INCLUDE_PATH. "classes/DAO/UsersDAO.class.php");
include_once(AC_INCLUDE_PATH. "classes/DAO/GuidelinesDAO.class.php");
include_once(AC_INCLUDE_PATH. "classes/DAO/LangCodesDAO.class.php");

	// strings of data to write in file and output with _AC();
//	achecker_file_title = 'AChecker - Web Accessibility Checker'
//	achecker_file_description = 'AChecker is an open source Web accessibility evaluation tool. It can be used to review the accessibility of Web pages based on a variety international accessibility guidelines.'
//	
//	achecker_file_input_file = 'File Input'
//	achecker_file_input_paste = 'Paste Input'
//	
//	achecker_file_passed = 'Passed'
//	achecker_file_failed = 'Failed'
//	achecker_file_no_decision = 'No Decision'

class acheckerEARL {

	// all private
	// arrays that contain all data about errors of specific type
	var $known = array();
	var $likely = array();
	var $potential = array();
	
	// numbers of errors to display for each problem type
	var $error_nr_known = 0;
	var $error_nr_likely = 0;
	var $error_nr_potential = 0;
	
	var $error_id = 1;			// error id (for error pointer)
	var $problem_prefix = '';	// prefix of current problem (for error pointer)
	var $curr_lang = '';		// current language
	
	var $achecker_file_url = 'http://www.atutor.ca/achecker/';
		
	
	/**
	* public
	* error arrays and numbers setter
	*/
	function acheckerEARL($known, $likely, $potential, $error_nr_known, $error_nr_likely, $error_nr_potential)
	{				
		$this->known = $known;
		$this->likely = $likely;
		$this->potential = $potential;	
		
		$this->error_nr_known = $error_nr_known;
		$this->error_nr_likely = $error_nr_likely;
		$this->error_nr_potential = $error_nr_potential;
	}
	
	/**
	* public
	* main process of creating file
	*/
	public function	getEARL($problem, $input_content_type, $title, $_gids) 
	{	
		$file_content = $this->getAssertorTestSubjectTestCriterionSections($input_content_type, $title, $_gids);
		
		$file_content .= '<!-- Test Result -->
		';
		
		$this->curr_lang = $this->getLangCode();
		if ($problem == 'all') {
			$file_content .= $this->getResultSection('known', $input_content_type);
			$file_content .= $this->getResultSection('likely', $input_content_type);
			$file_content .= $this->getResultSection('potential', $input_content_type);
		} else {
			$file_content .= $this->getResultSection($problem, $input_content_type);
		}
		
		$file_content .= '
</rdf:RDF>';
		
		$path = AC_INCLUDE_PATH.'fileExport/rdf.rdf';
		$handle = fopen($path, 'w');
		fwrite($handle, $file_content); 
		fclose($handle);
	}
	
	/**
	* private
	* computes Assertor, Test Subject, Test Criterion Sections
	* returns them as string
	*/
	private function getAssertorTestSubjectTestCriterionSections($input_content_type, $title, $_gids)
	{		
		// assertor	
		$file_content = '<rdf:RDF
         xmlns:earl="http://www.w3.org/ns/earl#"
         xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#">
         
         <!-- Assertor -->
         ';

		$user_data = $this->getUserData();		
		if ($user_data) {
			$username = $user_data[0];
			$email = $user_data[1];
			
			$file_content .= '<foaf:Group rdf:ID="assertor01">
			<dct:title>'.$username.' and '._AC('achecker_file_title').'</dct:title>
			<dct:hasVersion>'.VERSION.'</dct:hasVersion>
			<dct:description xml:lang="en">
				'._AC('achecker_file_description').'
			</dct:description>
			<earl:mainAssertor rdf:resource="'.$this->achecker_url.'"/>
			<foaf:member>
				<foaf:Person>
					<foaf:mbox rdf:resource="mailto:'.$email.'"/>
					<foaf:name>'.$username.'</foaf:name>
				</foaf:Person>
			</foaf:member>
		</foaf:Group>
		
		';			
		} else {
			$file_content .= '<earl:Software rdf:about="'.$this->achecker_url.'">
			<dct:title xml:lang="en">'._AC('achecker_file_title').'</dct:title>
			<dct:hasVersion>'.VERSION.'</dct:hasVersion>
			<dct:description xml:lang="en">
				'._AC('achecker_file_description').'
			</dct:description>
		</earl:Software>
		
		';
		}
	
		// test subject
		$file_content .= '<!-- Test Subject -->
		';
		
		if ($input_content_type == 'file') {
			$file_content .= '<rdf:Description xml:lang="en">'._AC('achecker_file_input_file').'</rdf:Description>';
		} else if ($input_content_type == 'paste') {
			$file_content .= '<rdf:Description xml:lang="en">'._AC('achecker_file_input_paste').'</rdf:Description>';
		} else {
			$file_content .= '<rdf:Description rdf:about="'.$input_content_type.'">';
		}
	
		$file_content .= '
			<dct:title xml:lang="en">'.$title.'</dct:title>';
		
		$date_to_print = AC_Date('%d-%m-%Y');		
		$file_content .= '
			<dct:date>'.$date_to_print.'</dct:date>
		</rdf:Description>
		
		';
		
		// test criterion
		$file_content .= '<!-- Test Criterion -->
		';
	
		$guidelinesDAO = new GuidelinesDAO();
		$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_gids);
		
		// display guidelines
		if (is_array($guideline_rows)) {
			foreach ($guideline_rows as $id => $row) {
				$file_content .= '<earl:TestRequirement rdf:about="'.$row["earlid"].'">
			<dct:title xml:lang="en">'.$row["abbr"].'</dct:title>
			<dct:description xml:lang="en">'._AC($row["long_name"]).'</dct:description>
		</earl:TestRequirement>
		
		';		
			}
		}				
		return $file_content;
	}
	
	/**
	* private
	* computes Result Section
	* returns it as string
	*/
	private function getResultSection($problem_type, $input_content_type) 
	{		
		$this->error_id = 1;
		
		if ($problem_type == 'known') {
			$array = $this->known;
			$nr = $this->error_nr_known;
			$this->problem_prefix = 'known';
			$file_content .= '<!-- ========================== Known problems ========================== -->
		';
		} else if ($problem_type == 'likely') {
			$array = $this->likely;
			$nr = $this->error_nr_likely;
			$this->problem_prefix = 'likely';
			$file_content .= '<!-- ========================== Likely problems ========================== -->
		';
		} else if ($problem_type == 'potential') {
			$array = $this->potential;
			$nr = $this->error_nr_potential;
			$this->problem_prefix = 'potential';
			$file_content .= '<!-- ========================== Potential problems ========================== -->
		';
		}
		
		// show congratulations if no errors found
		if ($nr == 0) {
			$file_content .= '<earl:TestResult rdf:ID="result_'.$this->problem_prefix.$this->error_id.'">
	        <earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_message" />
	        <earl:outcome rdf:resource="http://www.w3.org/ns/earl#passed" />
	    </earl:TestResult>
	    
	    ';
			// congrats message
			$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_message">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'._AC("congrats_no_$problem_type").'
			</ptr:expression>
		</ptr:ExpressionPointer>
		
		';
		} 		
		
		if ($problem_type == 'known') {
			foreach ($array as $error) {
				// error TestResult
				$file_content .= '<earl:TestResult rdf:ID="result_'.$this->problem_prefix.$this->error_id.'">
	        <earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_line" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_error" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_repair" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_html" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_css" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_img" />
	        <earl:outcome rdf:resource="http://www.w3.org/ns/earl#failed" />
	    </earl:TestResult>
	    
	    ';
				// error details
				// line
				$file_content .= '<ptr:LineCharPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_line">
	        <ptr:lineNumber>'.$error['line_nr'].'</ptr:lineNumber>
	        <ptr:charNumber>'.$error['col_nr'].'</ptr:charNumber>
	        ';
				
				if ($input_content_type == 'file') {
					$file_content .= '<ptr:reference rdf:resource="'._AC('achecker_file_input_file').'"/>';
				} else if ($input_content_type == 'paste') {
					$file_content .= '<ptr:reference rdf:resource="'._AC('achecker_file_input_paste').'"/>';
				} else {
					$file_content .= '<ptr:reference rdf:resource="'.$input_content_type.'"/>';
				}
				
				$file_content .= '
		</ptr:LineCharPointer>
		
		';
				
				// error text
				$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_error">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['error'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
		
		';
				
				// repair
				$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_repair">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['repair']['label'].': '.$error['repair']['detail'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
		
		';
				
				// html
				$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_html">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.html_entity_decode($error['html_code'], ENT_COMPAT, 'UTF-8').'
			</ptr:expression>
		</ptr:ExpressionPointer>
		
		';
				
				// css
				$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_css">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['css_code'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
		
		';
				
				// img
				$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_img">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['image']['src'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
		
		';
				
				$this->error_id++;
			
			} // end foeraech $error
			
		} 
		
		// likely and potential. needed to show 'passed', 'failed' or 'no decision'		
		else { 
			foreach ($array as $category) { // with decision, no decision
				foreach ($category as $error) {
					// error TestResult
					$file_content .= '<earl:TestResult rdf:ID="result_'.$this->problem_prefix.$this->error_id.'">
		    <earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_line" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_error" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_html" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_css" />
			<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_img" />
			';
					if (isset($_SESSION['user_id'])) {
						$file_content .= '<earl:pointer rdf:resource="#pointer_'.$this->problem_prefix.$this->error_id.'_decision" />
			';
					}
					$file_content .= '<earl:outcome rdf:resource="http://www.w3.org/ns/earl#failed" />
		</earl:TestResult>
		    
		';
					// error details
					// line
					$file_content .= '<ptr:LineCharPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_line">
		    <ptr:lineNumber>'.$error['line_nr'].'</ptr:lineNumber>
		    <ptr:charNumber>'.$error['col_nr'].'</ptr:charNumber>
		    ';
					
					if ($input_content_type == 'file') {
						$file_content .= '<ptr:reference rdf:resource="'._AC('achecker_file_input_file').'"/>';
					} else if ($input_content_type == 'paste') {
						$file_content .= '<ptr:reference rdf:resource="'._AC('achecker_file_input_paste').'"/>';
					} else {
						$file_content .= '<ptr:reference rdf:resource="'.$input_content_type.'"/>';
					}
					
					$file_content .= '
		</ptr:LineCharPointer>
			
		';
					
					// error text
					$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_error">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['error'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
			
		';
					
					// html
					$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_html">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.html_entity_decode($error['html_code'], ENT_COMPAT, 'UTF-8').'
			</ptr:expression>
		</ptr:ExpressionPointer>
			
		';
					
					// css
					$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_css">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['css_code'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
			
		';
					
					// img
					$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_img">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				'.$error['image']['src'].'
			</ptr:expression>
		</ptr:ExpressionPointer>
			
		';
					
					// if user is logged in display 'passed', 'failed' or 'no decision'
					if (isset($_SESSION['user_id'])) {	
						// decision
						$file_content .= '<ptr:ExpressionPointer rdf:ID="pointer_'.$this->problem_prefix.$this->error_id.'_decision">
			<ptr:expression rdf:parseType="Literal" xml:lang="'.$this->curr_lang.'">
				';
						if ($error['decision'] == 'true') $file_content .= _AC('achecker_file_passed');
						else if ($error['decision'] == false) $file_content .= _AC('achecker_file_failed');
						else if ($error['decision'] == 'none') $file_content .= _AC('achecker_file_no_decision');
						$file_content .= '
			</ptr:expression>
		</ptr:ExpressionPointer>
					
		';
					} // end if user is logged in
			
					$this->error_id++;					
					
				} // end foeraech $error
			} // end foeraech $category
		}
		
		return $file_content;
	}
	
	/**
	* private
	* returns username ([first] [last] ([login])) and email of current user
	* if no looged in user returns false
	*/
	private function getUserData()
	{			
		if (isset($_SESSION['user_id'])) {
			$userDAO = new UsersDAO();	
			$user_data = $userDAO->getUserByID($_SESSION['user_id']);
			
			if (($user_data['first_name'] != '') && ($user_data['last_name'] != '')) {
				$username = $user_data['first_name'].' '.$user_data['last_name'].' ('.$user_data['login'].')';
			} else $username = '('.$user_data['login'].')';
			
			return array($username, $user_data['email']);
		}
		else return false;			
	}
	
	/**
	* private
	* returns 2 letters code of currennt language
	*/
	private function getLangCode() {
		$lang_code = new LangCodesDAO();
		$code_2_letters = $lang_code->GetLangCodeBy3LetterCode($_SESSION['lang']);
		return $code_2_letters['code_2letters'];
	}
	
	
}
?>