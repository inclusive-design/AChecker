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
//include_once(AC_INCLUDE_PATH.'classes/DAO/UserDecisionsDAO.class.php');
//include_once(AC_INCLUDE_PATH.'classes/AccessibilityRpt.class.php');
//include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelinesDAO.class.php');
//include_once(AC_INCLUDE_PATH.'classes/DAO/ChecksDAO.class.php');
//include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineGroupsDAO.class.php');
//include_once(AC_INCLUDE_PATH.'classes/DAO/GuidelineSubgroupsDAO.class.php');

include_once(AC_INCLUDE_PATH.'fileExport/tcpdf/config/lang/eng.php');
include_once(AC_INCLUDE_PATH.'fileExport/tcpdf/tcpdf.php');

class acheckerTCPDF extends TCPDF {
	
	var $error_lines = array();
	var $groups = array();
	var $subgroups = array();
	
	var $problem;
	
		/**
	* public
	*/
	function acheckerTCPDF($problem, $error_lines, $groups, $subgroups)
	{
		
//		debug_to_log('======================here==============================');
		// run parent constructor
		parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		$this->error_lines = $error_lines;
		$this->groups = $groups;
		$this->subgroups = $subgroups;
		
//		debug_to_log('======================$this->errors==============================');
//		debug_to_log($this->error_lines);
//		debug_to_log('======================$this->groups==============================');
//		debug_to_log($this->groups);
//		debug_to_log('======================$this->subgroups==============================');
//		debug_to_log($this->subgroups);
		
		$this->$problem = $problem;
	}
	
	/**
	* provate
	* main process to start creating file
	*/
	private function Initialize() 
	{		
		// set document information
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor('Nicola Asuni');
		$this->SetTitle('TCPDF Example 020');
		$this->SetSubject('TCPDF Tutorial');
		$this->SetKeywords('TCPDF, PDF, example, test, guide');
		
		// set default header data
		$this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 020', PDF_HEADER_STRING);
		
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
	public function	getPDF() 
	{
		$this->Initialize();
		
		
		// set font
		$pdf->SetFont('helvetica', '', 20);
		// add a page
		$pdf->AddPage();
		
		$pdf->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);
		
		$pdf->Ln(5);
		
		$pdf->SetFont('times', '', 9);
		
		//$pdf->SetCellPadding(0);
		//$pdf->SetLineWidth(2);
		
		// set color for background
		$pdf->SetFillColor(255, 255, 200);
		
		$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue. Sed vel velit erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras eget velit nulla, eu sagittis elit. Nunc ac arcu est, in lobortis tellus. Praesent condimentum rhoncus sodales. In hac habitasse platea dictumst. Proin porta eros pharetra enim tincidunt dignissim nec vel dolor. Cras sapien elit, ornare ac dignissim eu, ultricies ac eros. Maecenas augue magna, ultrices a congue in, mollis eu nulla. Nunc venenatis massa at est eleifend faucibus. Vivamus sed risus lectus, nec interdum nunc.
		
		Fusce et felis vitae diam lobortis sollicitudin. Aenean tincidunt accumsan nisi, id vehicula quam laoreet elementum. Phasellus egestas interdum erat, et viverra ipsum ultricies ac. Praesent sagittis augue at augue volutpat eleifend. Cras nec orci neque. Mauris bibendum posuere blandit. Donec feugiat mollis dui sit amet pellentesque. Sed a enim justo. Donec tincidunt, nisl eget elementum aliquam, odio ipsum ultrices quam, eu porttitor ligula urna at lorem. Donec varius, eros et convallis laoreet, ligula tellus consequat felis, ut ornare metus tellus sodales velit. Duis sed diam ante. Ut rutrum malesuada massa, vitae consectetur ipsum rhoncus sed. Suspendisse potenti. Pellentesque a congue massa.';
		
		// print some rows just as example
		for ($i = 0; $i < 10; ++$i) {
		    $pdf->MultiRow('Row '.($i+1), $text."\n");
		}
		
		// reset pointer to the last page
		$pdf->lastPage();
		
		// ---------------------------------------------------------
		
		//Close and output PDF document
		$path = AC_INCLUDE_PATH.'fileExport/tcpdf/example.pdf';
		$pdf->Output($path, 'F');

	}
	
	
	
	
	
}
?>