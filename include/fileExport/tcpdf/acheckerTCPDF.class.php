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
* acheckerTCPDF
* Class to generate error report in html format 
* @access	public
* @author	Casian Olga
* @package checker
*/
//define('AC_INCLUDE_PATH', '../include/');
if (!defined("AC_INCLUDE_PATH")) exit;
include(AC_INCLUDE_PATH.'../../vitals.inc.php');
include_once(AC_INCLUDE_PATH.'fileExport/tcpdf/config/lang/eng.php');
include_once(AC_INCLUDE_PATH.'fileExport/tcpdf/tcpdf.php');

class acheckerTCPDF extends TCPDF {

	var $known = array();
	var $likely = array();
	var $potential = array();
	
	var $problem;
	
	/**
	* public
	*/
	function acheckerTCPDF($problem)
	{
		// run parent constructor
		parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		$this->$problem = $problem;
	}
	
	/**
	* private
	* main process to start creating file
	*/
	private function Initialize() 
	{		
		// set document information
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor('Web Accessibility Checker');
		$this->SetTitle('Web Accessibility Checker');		// to change
		$this->SetSubject('Web Accessibility Checker Report');
		$this->SetKeywords('Web, Accessibility, Checker');
		
		// set default header data
		$this->SetHeaderData('checker_logo.jpg', '40', 'Web Accessibility Checker', 'atutor.ca/achecker');
		
		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		//set some language-dependent strings
		$this->setLanguageArray($l);
	}
	
	/**
	* public
	* main process to start creating file
	*/
	public function	getGuidelinePDF($known) 
	{
		$this->Initialize();		
		
		// set font
		$this->SetFont('helvetica', 'B', 14);
		// add a page
		$this->AddPage();
		
		$this->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);
		
		$this->Ln(5);
		debug_to_log($known);
		foreach($known as $group_title => $group_content) {
			$this->Ln(3);
			// set color for background
			$this->SetFillColor(255, 255, 200);				
			$this->SetTextColor(165,7,7);
			$this->SetFont('helvetica', 'B', 12);	
			$this->MultiCell(0, 5, $group_title, 0, 'L', 0, 1, '' ,'', true);
			$this->Ln(3);
		
			foreach($group_content as $subgroup_title => $subgroup_content) {
				$this->SetFont('helvetica', 'B', 10);
				$this->SetTextColor(165,7,7);
				$this->MultiCell(7, 5, '', 0, 'l', 0, 0, '' ,'', true);	// makes padding
				$this->MultiCell(0, 5, $subgroup_title, 0, 'L', 0, 1, '' ,'', true);
				$this->Ln(3);

				foreach($subgroup_content as $check_group) {
					$this->SetTextColor(0);	
					$this->SetFont('helvetica', 'B', 10);
					$this->MultiCell(14, 5, '', 0, 'l', 0, 0, '' ,'', true);	// makes padding
					$this->MultiCell(23, 5, $check_group['check_label']." ".$check_group['check_id'].":", 0, 'L', 0, 0, '' ,'', true);
					$this->SetTextColor(26,74,114);
					$this->SetFont('helvetica', 'B', 10);
					
					$this->writeHTML($check_group['error'], true, false);
					
//					$this->MultiCell(0, 5, $check_group['error'], 1, 'L', 1, 1, '' ,'', true);
					
					$this->SetTextColor(0);
					$this->SetFont('helvetica', '', 10);
					$this->SetFillColor(247, 243, 255);
					$this->Ln(3);
					$this->MultiCell(21, 5, '', 0, 'l', 0, 0, '' ,'', true);	// makes padding					
					$str = $check_group['repair']['label'].": ".$check_group['repair']['detail'];
					$this->writeHTMLCell(0, 5, $this->GetX(), $this->GetY(), $str, 0, 1, true);
					$this->Ln(3);
					
//					$this->MultiCell(0, 5, $check_group['repair']['label'].": ".$check_group['repair']['detail'], 1, 'L', 1, 1, '' ,'', true);
					
					foreach($check_group['errors'] as $error) {
						$this->MultiCell(21, 5, '', 0, 'l', 0, 0, '' ,'', true);	// makes padding
						$path = $error['base_href']."images/".$error['img_src'];
						$this->Image($path, $this->GetX(), $this->GetY(), 4, 4, '', '', 'T', true, 300, '', false, false, 1, false, false, false);
						$this->SetFont('helvetica', 'BI', 10);
						$location = " ".$error['line_text']." ".$error['line_nr'].", ".$error['col_text']." ".$error['col_nr'].":";
						$this->MultiCell(0, 5, $location, 0, 'L', 0, 1, '' ,'', true);
						$this->SetFont('courier', '', 9);
						$this->Ln(3);
						
						$this->MultiCell(21, 5, '', 0, 'l', 0, 0, '' ,'', true);	// makes padding
						 
						$this->SetFillColor(248, 248, 248);	
						$this->MultiCell(0, 5, html_entity_decode($error['html_code']), 0, 'L', 0, 1, '' ,'', true);
						
						if ($error['css_code'] != '') {
							$this->Ln(3);
							$this->SetFont('helvetica', '', 10);
							$this->MultiCell(21, 5, '', 0, 'l', 0, 0, '' ,'', true);	// makes padding
							$this->writeHTMLCell(0, 5, $this->GetX(), $this->GetY(), $error['css_code'], 0, 1, false);
						}
						$this->Ln(3);
						
				// uncomment to include images
//								if (($error['error_img'] != '') && ($error['error_img']['img_src'] != '')) {
//									$img_height = ($error['error_img']['height'])/3.5;
//									$this->Image($error['error_img']['img_src'], $this->GetX()+18, $this->GetY(),0, 0, '', '', 'T', true, 300, '', false, false, 1, false, false, false);
//									$this->Ln($img_height+3);
//								}
							
					}
				}

		
			}
		}
		// reset pointer to the last page
		$this->lastPage();
		// ---------------------------------------------------------
	
		//Close and output PDF document
		$path = AC_INCLUDE_PATH.'fileExport/example.pdf';
		$this->Output($path, 'F');
	
	}
	
	
	
	
	
}
?>