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
* acheckerTFPDF
* Class to generate error report in PDF file (both by lines and by guidelines)
* for each of types: known, likely, potential, html, css and all selected 
* @access	public
* @author	Casian Olga
*/
if (!defined("AC_INCLUDE_PATH")) exit;
define("_SYSTEM_TTFONTS", AC_INCLUDE_PATH.'lib/tfpdf/font/unifont/');
include_once(AC_INCLUDE_PATH. 'lib/output.inc.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/GuidelinesDAO.class.php');
include_once(AC_INCLUDE_PATH.'lib/tfpdf/tfpdf.php');

class acheckerTFPDF extends tFPDF {

	// all private
	// arrays that contain all data about errors of specific type
	var $known = array();
	var $likely = array();
	var $potential = array();
	var $html = array();
	var $css = array();
	
	// numbers of errors to display for each problem type
	var $error_nr_known = 0;
	var $error_nr_likely = 0;
	var $error_nr_potential = 0;
	var $error_nr_html = 0;
	var $error_nr_css = 0;
	
	// css and html error messages 
	// css validator is only available at validating url, not at validating a uploaded file or pasted html
	var $css_error = '';
	var $html_error = '';
	
	
	/**
	* public
	* error arrays and numbers setter
	* @param
	* $known, $likely, $potential: arrays that contain errors of specific type
	* $html, $css: arrays of validation errors
	* $error_nr_known, $error_nr_likely, $error_nr_potential: nr of errors
	* $error_nr_html, $error_nr_css: nr of errors
	* $css_error: empty if css validation was required with URL input, otherwise string with error msg
	*/
	function __construct($known, $likely, $potential, $html, $css, 
		$error_nr_known, $error_nr_likely, $error_nr_potential, $error_nr_html, $error_nr_css, $css_error, $html_error)
	{
		//Call parent constructor
		parent::__construct('P','mm','A4');
	
		$this->known = $known;
		$this->likely = $likely;
		$this->potential = $potential;
		$this->html = $html;
		$this->css = $css;
		
		$this->error_nr_known = $error_nr_known;
		$this->error_nr_likely = $error_nr_likely;
		$this->error_nr_potential = $error_nr_potential;
		$this->error_nr_html = $error_nr_html;
		$this->error_nr_css = $error_nr_css;
		
		$this->css_error = $css_error;
		$this->html_error = $html_error;
	}
	
	/**
	* defining header
	*/
	function Header()
	{
	    // logo
	    $this->Image(AC_BASE_HREF.'images/jpg/achecker.jpg', 12, 10, 38);
	    
	    // title and url
	    $this->SetFont('Helvetica', 'B', 10);
	    $this->Cell(150);
	    $this->SetTextColor(0);
	    $this->Cell(40,5,'Web Accessibility Checker', 0, 2, 'R');
	    $this->SetFont('Helvetica', '', 0);
	    $this->Cell(40,5,'atutor.ca/achecker', 0, 1, 'R');
	    $this->Ln(8);
	}
	
	/**
	* defining footer
	*/
	function Footer()
	{
	    // position at 1.5 cm from bottom
	    $this->SetY(-15);
	    $this->SetFont('Helvetica','',8);
	    $this->SetTextColor(0);
	    
	    // page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	/**
	* private
	* prints time, date, [resource title,] [resource url,] str with guidelines
	* @param
	* $title: validated content title (fount in <title> tag); if empty title will not be displayed
	* $uri: validated content URL (only in case of url input); if empty URL will not be displayed
	* $guidelines_text: string of all guidelines separated by ", "
	* $time: time when function to create file called (showed in file title and inside document)
	*/
	private function printInfo($title, $uri, $guidelines_text, $time) 
	{	
		$this->AliasNbPages();		

		// add Unicode fonts (uses UTF-8)
		$this->AddFont('DejaVu',  '',  'DejaVuSansCondensed.ttf', true);
		$this->AddFont('DejaVu', 'B',  'DejaVuSansCondensed-Bold.ttf', true);
		$this->AddFont('DejaVu', 'I',  'DejaVuSansCondensed-Oblique.ttf', true);
		$this->AddFont('DejaVu', 'BI', 'DejaVuSansCondensed-BoldOblique.ttf', true);
		
		// add a page
		$this->AddPage();
		
		// time
		$time_to_print = str_replace("-", ":", $time);

		// date
		$today = getdate();
		$date_to_print = AC_date('%l').' '.AC_date('%F').' '.$today['mday'].', '.$today['year'];
		$this->SetFont('DejaVu', '', 9);
		$this->Write(5, $date_to_print. ' '. $time_to_print);
		$this->Ln(8);	

		// url
		if ($uri != '') {
			$this->SetFont('DejaVu', '', 12);
			$this->Write(5, _AC('file_source_url').': '.$uri);
			$this->Ln(5);
		}
		
		// title
		if ($title != '') {
			$this->SetFont('DejaVu', '', 12);			
			$this->Write(5, _AC('file_source_title').': '.$title);
			$this->Ln(8);
		}

		// guidelines
		$this->SetFont('DejaVu', 'B', 12);
		$this->Write(5, _AC("accessibility_review") . ' ('. _AC("guidelines"). ': ');
		$this->SetTextColor(165,7,7);
		$this->Write(5, $guidelines_text);
		$this->SetTextColor(0);
		$this->Write(5, ')');
		$this->Ln(6);
	}

	/**
	* private
	* prints report for 1 problem type by guidelines
	* @param
	* $problem_type: known, potential or likely; corresponding array in class should be set before calling
	*/
	private function printGuideline($problem_type) 
	{	
		if ($problem_type == 'known') {
			$array = $this->known;
			$nr = $this->error_nr_known;
		} else if ($problem_type == 'likely') {
			$array = $this->likely;
			$nr = $this->error_nr_likely;
		} else if ($problem_type == 'potential') {
			$array = $this->potential;
			$nr = $this->error_nr_potential;
		}
		
		if ($nr == '') $nr = 0;		
	
		// str with error type and nr of errors
		$this->SetFont('DejaVu', 'B', 14);
		$this->SetTextColor(0);
		$this->Write(5, _AC('file_report_'.$problem_type).' ('.$nr.' '._AC('file_report_found').'):');		
		$this->Ln(10);
		
		// show congratulations if no errors found
		if ($nr == 0) {
			$this->Ln(3);
			$this->SetTextColor(0, 128, 0);
			$path = AC_BASE_HREF."images/jpg/feedback.jpg";
			$this->SetX(11);
			$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
			$this->SetX(17);
			$this->SetFont('DejaVu', 'B', 12);
			$this->Write(5, _AC('congrats_no_'.$problem_type));
			$this->Ln(3);
		} 

		// Display the list of problems, including the problems that the user has made pass decisions on.
		if (is_array($array) && count($array) > 0) { // make report on errors
			// group level output
			foreach($array as $group_title => $group_content) {
				$this->Ln(3);						
				$this->SetTextColor(165,7,7);
				$this->SetX(10);
				$this->SetFont('DejaVu', 'B', 12);	
				$this->Write(5, $group_title);
				$this->Ln(8);
			
				// subgroup level output
				foreach($group_content as $subgroup_title => $subgroup_content) {
					$this->SetFont('DejaVu', 'B', 10);
					$this->SetTextColor(165,7,7);
					$this->SetX(17);
					$this->Write(5, $subgroup_title);
					$this->Ln(8);
	
					// check level output
					foreach($subgroup_content as $check_group) {
						$this->SetTextColor(0);	
						$this->SetFont('DejaVu', 'B', 10);
						$this->SetX(21);
						$check = $check_group['check_label']." ".$check_group['check_id'].": ".strip_tags($check_group['error']);
						$this->Write(5, $check);				
						$this->SetTextColor(0);
						$this->SetFont('DejaVu', '', 10);
						$this->Ln(8);					
						if (is_array($check_group['repair'])) {
							$this->SetX(28);
							$this->Write(5, $check_group['repair']['label'].": ".strip_tags($check_group['repair']['detail']));
							$this->Ln(8);
						}
						
						// one error output
						foreach($check_group['errors'] as $error) {
							// error icon img, line, column, error text
							$img_data = explode(".", $error['img_src']);		
							$path = $error['base_href']."images/jpg/".$img_data[0].".jpg";
							$this->Image($path, $this->GetX()+18, $this->GetY(), 4, 4);
							$this->SetX(32);
							$this->SetFont('DejaVu', 'BI', 9);
							$this->SetTextColor(0);
							$location = " ".$error['line_text']." ".$error['line_nr'].", ".$error['col_text']." ".$error['col_nr'].":";
							$this->Write(5, $location);
							$this->Ln(5);
							
							// html code of error (if there is image in error show full img src in even if string is >100 long)
							$this->SetTextColor(0);
							$this->SetX(28);
							$this->SetFont('DejaVu', '', 9);						
							if (($error['error_img'] != '') && ($error['error_img']['img_src'] != '')) {
								preg_match('/src=(.)*/', html_entity_decode($error['html_code']), $match);
								$img_parts = explode('"', $match[0]);
								if (count($img_parts)<3) {
									$error['html_code'] = "<img ".$img_parts[0].'"'.$error['error_img']['img_src'].'" ...';
								}
							}
							$str = str_replace("\t", "    ", html_entity_decode($error['html_code']));
							$this->Write(5, $str);
							$this->Ln(8);
							
							// css code
							if ($error['css_code'] != '') {
								$pattern = "/CSS.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s/";
								if (preg_match($pattern, strip_tags($error['css_code']), $matches)) {
									$this->SetFont('Helvetica', '', 8);
									$this->SetX(28);
									$this->MultiCell(0, 5, $matches[0], 0, 'L', false);	
									$this->Ln(3);
								}							
							}	
							
							// if user is logged in display labels 'passed', 'failed' or 'no decision'
							if ((isset($_SESSION['user_id'])) && ($problem_type != 'known')) {
								$this->SetFont('DejaVu', 'B', 10);
								$this->SetX(170);
								if ($error['test_passed'] == 'true') {
									$this->SetTextColor(134, 218, 130);
									$this->Write(5, strtoupper(_AC('file_passed')));
								} else if ($error['test_passed'] == false) {
									$this->SetTextColor(246, 114, 114);
									$this->Write(5, strtoupper(_AC('file_failed')));
								} else if ($error['test_passed'] == 'none') {
									$this->SetTextColor(106, 175, 233);
									$this->Write(5, strtoupper(_AC('file_no_decision')));
								}
								$this->Ln(10);								
							} // end if user is logged in
						} // end of roreach $error
					} 		
				} 
			} 
		} // end of else (for shownig errors)
		
	}
	
	/**
	* private
	* prints report for 1 problem type by lines
	* @param
	* $problem_type: known, potential or likely; corresponding array in class should be set before calling
	*/
	private function printLine($problem_type) 
	{
		if ($problem_type == 'known') {
			$array = $this->known;
			$nr = $this->error_nr_known;
		} else if ($problem_type == 'likely') {
			$array = $this->likely;
			$nr = $this->error_nr_likely;
		} else if ($problem_type == 'potential') {
			$array = $this->potential;
			$nr = $this->error_nr_potential;
		}
		
		if ($nr == '') $nr = 0;	
		
		// str with error type and nr of errors
		$this->SetFont('DejaVu', 'B', 14);
		$this->SetTextColor(0);
		$this->Write(5, _AC('file_report_'.$problem_type).' ('.$nr.' '._AC('file_report_found').'):');		
		$this->Ln(10);
		
		// show congratulations if no errors found
		if ($nr == 0) {
			$this->Ln(3);
			$this->SetTextColor(0, 128, 0);
			$path = AC_BASE_HREF."images/jpg/feedback.jpg";
			$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
			$this->SetX(14);
			$this->SetFont('DejaVu', 'B', 12);
			$this->Write(5, _AC('congrats_no_'.$problem_type));
		} else { // else make report on errors

			// known
			if ($problem_type == 'known') {
				foreach($array as $error) {
					// error icon img, line, column, error text
					$img_data = explode(".", $error['img_src']);		
					$path = $error['base_href']."images/jpg/".$img_data[0].".jpg";
					$this->Image($path, $this->GetX()+7, $this->GetY(), 4, 4);
					$this->SetX(21);
					$this->SetTextColor(0);
					$this->SetFont('DejaVu', 'BI', 9);
					$location = " ".$error['line_text']." ".$error['line_nr'].", ".$error['col_text']." ".$error['col_nr'].":  ";
					$this->Write(5, $location);
					$this->SetTextColor(26, 74, 114);
					$this->SetFont('DejaVu', '', 10);
					$this->Write(5, strip_tags($error['error']));
					$this->Ln(7);

					// html code of error (if there is image in error show full img src in even if string is >100 long)
					$this->SetFont('DejaVu', '', 9);
					$this->SetTextColor(0);
					$this->SetX(17);
					if (($error['image'] != '') && ($error['image']['src'] != '')) {
						preg_match('/src=(.)*/', html_entity_decode($error['html_code']), $match);
						$img_parts = explode('"', $match[0]);
						if (count($img_parts)<3) {
							$error['html_code'] = "<img ".$img_parts[0].'"'.$error['image']['src'].'" ...';
						}
					}
					$str = str_replace("\t", "    ", html_entity_decode($error['html_code']));
					$this->Write(5, $str);
					$this->Ln(8);
					
					// css code
					if ($error['css_code'] != '') {
						$pattern = "/CSS.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s/";
						if (preg_match($pattern, strip_tags($error['css_code']), $matches)) {
							$this->SetFont('Helvetica', '', 8);
							$this->SetX(17);
							$this->MultiCell(0, 5, $matches[0], 0, 'L', false);	
							$this->Ln(3);
						}							
					}
					
					// repair
					if (is_array($error['repair'])) {
						$this->SetX(17);
						$this->SetFont('DejaVu', '', 10);
						$this->Write(5, $error['repair']['label'].": ".strip_tags($error['repair']['detail']));
						$this->Ln(10);
					}
				}
			} else { // likely and potential. needed to show 'passed', 'failed' or 'no decision' label	
				foreach($array as $category) {
					foreach($category as $error) {
						// error icon img, line, column, error text
						$img_data = explode(".", $error['img_src']);		
						$path = $error['base_href']."images/jpg/".$img_data[0].".jpg";
						$this->Image($path, $this->GetX()+7, $this->GetY(), 4, 4);
						$this->SetX(21);
						$this->SetTextColor(0);
						$this->SetFont('DejaVu', 'BI', 9);
						$location = " ".$error['line_text']." ".$error['line_nr'].", ".$error['col_text']." ".$error['col_nr'].":  ";
						$this->Write(5, $location);
						$this->SetTextColor(26, 74, 114);
						$this->SetFont('DejaVu', '', 10);
						$this->Write(5, strip_tags($error['error']));
						$this->Ln(7);
									
						// html code of error (if there is image in error show full img src in even if string is >100 long)
						$this->SetFont('DejaVu', '', 9);
						$this->SetTextColor(0);
						$this->SetX(17);
						if (($error['image'] != '') && ($error['image']['src'] != '')) {
							preg_match('/src=(.)*/', html_entity_decode($error['html_code']), $match);
							$img_parts = explode('"', $match[0]);
							if (count($img_parts)<3) {
								$error['html_code'] = "<img ".$img_parts[0].'"'.$error['image']['src'].'" ...';
							}
						}
						$str = str_replace("\t", "    ", html_entity_decode($error['html_code']));
						$this->Write(5, $str);
						$this->Ln(8);

						// css code
						if ($error['css_code'] != '') {
							$pattern = "/CSS.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s.*\s/";
							if (preg_match($pattern, strip_tags($error['css_code']), $matches)) {
								$this->SetFont('Helvetica', '', 8);
								$this->SetX(17);
								$this->MultiCell(0, 5, $matches[0], 0, 'L', false);	
								$this->Ln(3);
							}							
						}
						
						// if user is logged in display labels 'passed', 'failed' or 'no decision'
						if ((isset($_SESSION['user_id'])) && ($problem_type != 'known')) {
							$this->SetFont('DejaVu', 'B', 10);
							$this->SetX(170);
							if ($error['test_passed'] == 'true') {
								$this->SetTextColor(134, 218, 130);
								$this->Write(5, strtoupper(_AC('file_passed')));
							} else if ($error['test_passed'] == false) {
								$this->SetTextColor(246, 114, 114);
								$this->Write(5, strtoupper(_AC('file_failed')));
							} else if ($error['test_passed'] == 'none') {
								$this->SetTextColor(106, 175, 233);
								$this->Write(5, strtoupper(_AC('file_no_decision')));
							}
							$this->Ln(10);						
						} // end if user is logged in
					}
				}
			
			} // end else for likely & potential
		} // end else for showing errors
			
	}
	
	/**
	* private
	* prints report for HTML validation; corresponding array in class should be set before calling
	*/
	private function printHTML() 
	{
		// str with error type and nr of errors
		if ($this->error_nr_html == -1) {
			$this->SetTextColor(0, 0, 255);
			$path = AC_BASE_HREF."images/jpg/info.jpg";
			$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
			$this->SetX(14);
			$this->SetFont('DejaVu', 'B', 12);			
			$this->Write(5,_AC("html_validator_disabled"));
			$this->SetTextColor(0);
		} else {				
			$this->SetFont('DejaVu', 'B', 14);
			$this->SetTextColor(0);
			$this->Write(5, _AC('file_report_html').' ('.$this->error_nr_html.' '._AC('file_report_found').'):');		
			$this->Ln(10);
			$this->SetFont('DejaVu', 'B', 12);
			$this->Write(5,strip_tags(_AC("html_validator_provided_by")));
			$this->Ln(10);		
		
			// show congratulations if no errors found
			if ($this->error_nr_html == 0 && $this->html_error == '') {
				// no html validation errors, passed
				$this->Ln(3);
				$this->SetTextColor(0, 128, 0);
				$path = AC_BASE_HREF."images/jpg/feedback.jpg";
				$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
				$this->SetX(14);
				$this->SetFont('DejaVu', 'B', 12);
				$this->Write(5, _AC("congrats_html_validation"));
			} else if($this->error_nr_html == 0 && $this->html_error != '') {
				// html validation errors
				$this->Ln(3);
				$this->SetTextColor(0);
				$this->SetFont('DejaVu', '', 10);
				$this->Write(5, $this->html_error);
			} else { // else make report on errors
				foreach($this->html as $error) {
					// error icon img, line, column, error text
					$img_data = explode(".", $error['img_src']);		
					$path = AC_BASE_HREF."images/jpg/".$img_data[0].".jpg";
					$this->Image($path, $this->GetX()+7, $this->GetY(), 4, 4);
					$this->SetX(21);
					if ($error['line'] != '' && $error['col'] != '') {
						$this->SetTextColor(0);
						$this->SetFont('DejaVu', 'BI', 9);
						$location = " "._AC('line')." ".$error['line'].", "._AC('column')." ".$error['col'].":  ";
						$this->Write(5, $location);
					}
					$this->SetTextColor(26, 74, 114);
					$this->SetFont('DejaVu', '', 10);
					$this->Write(5, html_entity_decode(strip_tags($error['err'])));
					$this->Ln(7);
	
					// html code of error
					if ($error['html_1'] != '' || $error['html_2'] != '' || $error['html_3'] != '') {
						$this->SetFont('DejaVu', '', 9);
						$this->SetX(17);
						$this->SetTextColor(0);
						$str = str_replace("\t", "    ", html_entity_decode(htmlspecialchars_decode($error['html_1'], ENT_QUOTES)));
						$this->Write(5, $str);
						$this->SetTextColor(255, 0 ,0);
						$str = str_replace("\t", "    ", html_entity_decode(htmlspecialchars_decode($error['html_2'], ENT_QUOTES)));
						$this->Write(5, $str);
						$this->SetTextColor(0);
						$str = str_replace("\t", "    ", html_entity_decode(htmlspecialchars_decode($error['html_3'], ENT_QUOTES)));
						$this->Write(5, $str);
						$this->Ln(10);
					}
					
					// text
					if ($error['text'] != '') {
						$this->SetX(17);
						$this->SetFont('DejaVu', '', 10);
						$this->Write(5, html_entity_decode(strip_tags($error['text'])));
						$this->Ln(10);
					}
				}
			}
		} // end of else 
		
	}
	
	/**
	* private
	* prints report for CSS validation; corresponding array in class should be set before calling
	*/
	private function printCSS() 
	{		
		// str with error type and nr of errors
		if ($this->css_error == '' && $this->error_nr_css != -1) {
			$this->SetFont('DejaVu', 'B', 14);
			$this->SetTextColor(0);
			$this->Write(5, _AC('file_report_css').' ('.$this->error_nr_css.' '._AC('file_report_found').'):');		
			$this->Ln(10);
			$this->SetFont('DejaVu', 'B', 12);
			$this->Write(5,strip_tags(_AC("css_validator_provided_by")));
			$this->Ln(10);
		} else if ($this->css_error == '' && $this->error_nr_css == -1) {
			// css validator is disabled
			$this->SetTextColor(0, 0, 255);
			$path = AC_BASE_HREF."images/jpg/info.jpg";
			$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
			$this->SetX(14);
			$this->SetFont('DejaVu', 'B', 12);			
			$this->Write(5,_AC("css_validator_disabled"));
			$this->SetTextColor(0);
		}
		
		if ($this->css_error != '') {
			// css validator is only available at validating url, not at validating a uploaded file or pasted html
			$this->Ln(3);
			$this->SetTextColor(0, 0, 255);
			$path = AC_BASE_HREF."images/jpg/info.jpg";
			$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
			$this->SetX(14);
			$this->SetFont('DejaVu', 'B', 12);
			$this->Write(5, $this->css_error);
		} else {
			if ($this->error_nr_css == 0) {
				// show congratulations if no errors found
				$this->Ln(3);
				$this->SetTextColor(0, 128, 0);
				$path = AC_BASE_HREF."images/jpg/feedback.jpg";
				$this->Image($path, $this->GetX(), $this->GetY(), 4, 4);
				$this->SetX(14);
				$this->SetFont('DejaVu', 'B', 12);
				$this->Write(5, _AC("congrats_css_validation"));
			} else { // else make report on errors
				foreach($this->css as $uri => $group) {
					// uri
					$this->Ln(3);
					$this->SetX(17);
					$this->SetTextColor(0);
					$this->SetFont('DejaVu', 'B', 10);
					$this->Write(5, "URI: ");
					$this->SetTextColor(26, 74, 114);
					$this->SetFont('DejaVu', 'B', 12);
					$this->Write(5, $uri);
					$this->Ln(10);
					
					foreach($group as $error) {
						// line, code
						$this->SetX(17);
						$this->SetTextColor(0);
						$this->SetFont('DejaVu', 'BI', 9);
						$location = _AC('line')." ".$error['line'].":  ";
						$this->Write(5, $location);
						if ($error['code'] != '') {
							$this->SetFont('DejaVu', '', 9);
							$this->Write(5, $error['code']); 
						}
						$this->Ln(7);
						
						// parse
						if ($error['parse'] != '') {
							$this->SetX(17);
							$this->SetFont('DejaVu', '', 10);
							$str = str_replace("\t", "    ", strip_tags(htmlspecialchars_decode(html_entity_decode($error['parse']), ENT_QUOTES)));
							$this->Write(5, $str);
							$this->Ln(10);
						}
					} // end foreach error
				} // end foreach group
			}
		}
		
	}
	
	/**
	* public
	* main process of creating file
	* @param
	* $title: validated content title (fount in <title> tag); if empty title will not be displayed
	* $uri: validated content URL (only in case of url input); if empty URL will not be displayed
	* $problem: problem type on which to create report (can be: known, likely, potential, html, css or all)
	* $mode: 'guideline' or 'line'; pdf document may have different layout (for known, likely, potential) depending on this var
	* $_gids: array of guidelines that were used as testing criteria
	*/
	public function	getPDF($title, $uri, $problem, $mode, $_gids) 
	{		
		// set filename
		$date = AC_date('%Y-%m-%d');
		$time = AC_date('%H-%i-%s');
		$filename = 'achecker_'.$date.'_'.$time.$rand_str;		
		
		$guidelinesDAO = new GuidelinesDAO();
		$guideline_rows = $guidelinesDAO->getGuidelineByIDs($_gids);
		
		// get list of guidelines separated by ','
		if (is_array($guideline_rows)) {
			foreach ($guideline_rows as $id => $row) {
				$guidelines_text .= $row["title"]. ', ';
			}
		}
		$guidelines_text = substr($guidelines_text, 0, -2); // remove ending space and ,
	
		// print time, date, [resource title,] [resource url,] str with guidelines
		$this->printInfo($title, $uri, $guidelines_text, $time);

		// if report by guideline
		if ($mode == 'guideline') {
			if ($problem == 'all') {
				$this->printGuideline('known');
				$this->AddPage();
				$this->printGuideline('likely');
				$this->AddPage();
				$this->printGuideline('potential');
				if ($this->error_nr_html != -1) {
					$this->AddPage();
					$this->printHTML();
				}
				if ($this->error_nr_css != -1) {
					$this->AddPage();
					$this->printCSS();
				}
			} else if ($problem == 'html') {
				$this->printHTML();
			} else if ($problem == 'css') {
				$this->printCSS();
			} else {
				$this->printGuideline($problem);
			}
		} 
		// if report by line
		else if ($mode == 'line') {
			if ($problem == 'all') {
				$this->printLine('known');
				$this->AddPage();
				$this->printLine('likely');
				$this->AddPage();
				$this->printLine('potential');
				if ($this->error_nr_html != -1) {
					$this->AddPage();
					$this->printHTML();
				}
				if ($this->error_nr_css != -1) {
					$this->AddPage();
					$this->printCSS();
				}
			} else if ($problem == 'html') {
				$this->printHTML();
			} else if ($problem == 'css') {
				$this->printCSS();
			} else {			
				$this->printLine($problem);
			}
		}

		// close and save PDF document		
		$path = AC_EXPORT_RPT_DIR.$filename.'.pdf';  
		$this->Output($path, 'F');
		
		return $path;
	}
	
	
}
?>