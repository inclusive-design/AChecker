<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 by Greg Gay, Cindy Li                             */
/* Adaptive Technology Resource Centre / University of Toronto          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

/**
* BasicFunctions.class.php
* Class for basic functions provided to users in writing check functions
*
* @access	public
* @author	Simone Spagnoli e Matteo Battistelli
* @package  checker
*/

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");
include_once(AC_INCLUDE_PATH. 'classes/VamolaBasicChecks.class.php');
include_once(AC_INCLUDE_PATH. 'classes/BasicChecks.class.php');
include_once(AC_INCLUDE_PATH. 'classes/ColorValue.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/LangCodesDAO.class.php');
include_once(AC_INCLUDE_PATH. 'classes/DAO/ChecksDAO.class.php');	


// Simo: in realta' non c'e' bisogno dell'extend dato che non uso funzioni di BasicFunctions
class BasicFunctionsVamola extends BasicFunctions {
	
		
	// Prenderle da Checks.class
	// Eliminare parametri in input
	// Aggiungere:
	//	global $global_e, $global_content_dom;
		
	//	$e = $global_e;
	//	$content_dom = $global_content_dom;
	//  Inserire la chiamata a funzione nel database: "return BasicFunctionsVamola::check_1000();"

	

	
//MB: funzioni usate dai check (richiamano quelle di VamolaBasicChecks

	public static function check_blink()
	{
		global $global_e, $global_content_dom;
		return VamolaBasicChecks::check_blink($e, $content_dom);
	}
	
	
	
	// controllo che h2-h6 non siano in prima posizione (995-999)
	public static function check_995()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		if($headers_array[0]->linenumber==$e->linenumber && $headers_array[0]->colnumber==$e->colnumber)
			return false;
		else
			return true;
			
			
		return false;
	}
	
	
	
	public static function check_1000()
	{// controllo che non si ripetano gli h1
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
	
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=0; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		
		$headers_array_2 = $content_dom->find('h1');
		if(sizeof($headers_array_2)>1 && ($headers_array_2[0]->linenumber!=$e->linenumber ||
 		$headers_array_2[0]->colnumber!=$e->colnumber))
			return false;
		else
			return true;
	}
	
	
	public static function check_1001()
	{ //h3
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' )
			return false;
		else
			return true;
	}
	
	
	public static function check_1002()
	{   //h4
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' || $headers_array[$i-1]->tag == 'h2')
			return false;
		else
			return true;
	}	

	
	public static function check_1003()
	{   //h5
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
	
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' || $headers_array[$i-1]->tag == 'h2' || $headers_array[$i-1]->tag == 'h3')
			return false;
		else
			return true;
	}	
	
		
	public static function check_1004()
	{   //h6
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$headers_array = $content_dom->find('h1, h2, h3, h4, h5, h6');
		
		for ($i=1; $i < sizeof($headers_array); $i++)
		{
			if($headers_array[$i]->linenumber==$e->linenumber && $headers_array[$i]->colnumber==$e->colnumber)
			break;
		}
		if($headers_array[$i-1]->tag == 'h1' || $headers_array[$i-1]->tag == 'h2' || $headers_array[$i-1]->tag == 'h3' || $headers_array[$i-1]->tag == 'h4')
			return false;
		else
			return true;
	}	
	
	
	/* Da 1005 a 1014 return false */
	
	//form a: pseudo 20
	// per i 1044 fino a 1047
	public static function check_1044()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		if (isset($e->attr["target"]) && $e->attr["target"]!="_self" && $e->attr["target"]!="")
 			return ( isset($e->attr["title"]));
 		else
 			return true;

	}

	
	//div: pseudo 26
	public static function check_1055()
	{		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		$div_array = $content_dom->find("div");
		$i=0;
		for ($i=0; $i < sizeof($div_array); $i++)
		{
			if($div_array[$i]->linenumber==$e->linenumber && $div_array[$i]->colnumber==$e->colnumber)
				break;
		}
		
		if($i < sizeof($div_array)-1) //$e isn't the last <div>
		{
			$c1=$div_array[$i]->children();
			$c2=$div_array[$i+1]->children();
			//is the same image?
			if($c1[0]->tag=="img" && $c2[0]->tag=="img" && $c1[0]->attr['src'] == $c2[0]->attr['src']/*$c1[0]->src == $c2[0]->src*/)
			{	
					return false;
			}
	 
		}
		
		return true;
	}
	
	
	
	//p: pseudo 27
	public static function check_1056()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$p_array = $content_dom->find("p");
		$i=0;
		for ($i=0; $i < sizeof($p_array); $i++)
		{
			if($p_array[$i]->linenumber==$e->linenumber && $p_array[$i]->colnumber==$e->colnumber)
				break;
		}
		
		if($i < sizeof($p_array)-1) //$e isn't the last <p>
		{
			$c1=$p_array[$i]->children();
			$c2=$p_array[$i+1]->children();
			//is the same image?
			if($c1[0]->tag=="img" && $c2[0]->tag=="img" && $c1[0]->attr['src'] == $c2[0]->attr['src'])
			{	
					return false;
			}
	 
		}
		
		return true;
	}	
	
	
	
	// Pseudocodice 28
	// Br usati per implementare le liste
	public static function check_1057()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		$padre = $e->parent();
		// Se dopo un br c'e' una immagine faccio partire il check		
		$fratello = $e->next_sibling();
		if ($fratello->tag == "img")
		{
			$src_img = $fratello->attr["src"];
			//echo "cerco:". $src_img . " ";
			$num_repeated_img = 0;
			$figli = $padre->children();
			foreach ($figli as $child)
			{
				if ($child->tag == "img" && $child->attr["src"] == $src_img)
				{
					// Se tra i fratelli trovo un'altra immagine con lo stesso src allora l'immagine e' ripetuta
					$num_repeated_img = $num_repeated_img+1;				
				}
			}

			if ($num_repeated_img > 1)
			{
				return false;				
			}
		}	

		return true;	
	
	}

	
	
	// Pseudocodice 29
	// Tr (tabelle)  usate per implementare le liste
	public static function check_1058()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$table = $e->parent();
	
		$img = $e->find('img', 0);
		
		// Se dentro un tr c'e' una immagine faccio partire il check
		if ($img != null)
		{
			$src_img = $img->attr["src"]; 
		
			$num_repeated_img = 0;
			$table_row = $table->children();
			
			foreach ($table_row as $child)
			{
				if ($child->tag == "tr")
				{
					$child_img = $child->find('img',0);
					if ($child_img != null)
					{
						$src_img_child = $child_img->attr["src"];
						if ($src_img_child == $src_img)
						{
							// Se tra le altre righe trovo un'altra immagine con lo stesso src allora l'immagine e' ripetuta
							$num_repeated_img = $num_repeated_img+1;
						}					
					}				
				}	
			}
			if ($num_repeated_img > 1)
			{
				return false;				
			}	
		
		
		}

		return true;
	
	}
	
	
	// Pseudocodice 30
	// Dl deve avere come figli solo dt e dd, e dt deve per forza essere seguito da un dd
	public static function check_1059()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		$dl_child = $e->children();
		foreach ($dl_child as $child)
			{
				if ($child->tag != "dt" && $child->tag != "dd")
				{
					return false;			
				}
				elseif ($child->tag == "dt")
				{
					$dt_broth = $child->next_sibling();
					if ($dt_broth->tag != "dd")
					{
						return false;			
					}
				}
		}
		return true;
	
	}
	

	// Pseudocodice 35
	// Ogni frameset deve contenere noframes
	public static function check_1066()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
				
		foreach ($e->children() as $child)
			if ($child->tag == "noframes")
				return true;

	}
	
		// Pseudocodice 32 
	// Frame: verifica esistenza file longdesc remoto
	public static function check_1067()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
				
		
		if (($_POST["uri"]) != "http://")
		{
			if (isset($e->attr["longdesc"]))
			{	

				$ld_uri = explode("/",$_POST["uri"]);
				$ld_uri = array_slice($ld_uri, 0, sizeof($ld_uri)-1);
				$ur = implode("/", $ld_uri);

				$ld_path = $ur . "/" . $e->attr["longdesc"];
		
				$AgetHeaders = @get_headers($ld_path);
				if (preg_match("|200|", $AgetHeaders[0])) {
					return true;
				} else {
					return false;
				}
			}
		}
		return true;
	
	}
	
	// Pseudocodice 32 
	// Iframe: verifica esistenza file longdesc remoto
	public static function check_1068()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
				

		if (($_POST["uri"]) != "http://")
		{
			if (isset($e->attr["longdesc"]))
			{	

				$ld_uri = explode("/",$_POST["uri"]);
				$ld_uri = array_slice($ld_uri, 0, sizeof($ld_uri)-1);
				$ur = implode("/", $ld_uri);

				$ld_path = $ur . "/" . $e->attr["longdesc"];
		
				$AgetHeaders = @get_headers($ld_path);
				if (preg_match("|200|", $AgetHeaders[0])) {
					return true;
				} else {
					return false;
				}
			}
		}
		return true;
	
	}
	
	// Check 225
	// HTML se il doctype non e' strict ritorna false
	public static function check_1069()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
				
		
		$doctypes = $content_dom->find("doctype");

		if (count($doctypes) == 0) return false;
		
		foreach ($doctypes as $doctype)
		{
			foreach ($doctype->attr as $doctype_content => $garbage)
				if (stristr($doctype_content, "-//W3C//DTD HTML 4.01//EN") ||
						stristr($doctype_content, "-//W3C//DTD HTML 4.0//EN") ||
						stristr($doctype_content, "-//W3C//DTD XHTML 1.0 Strict//EN"))
					return true;
		}
		return false;
	}

	
	
	public static function check_1070()
	{
		global $htmlValidator;

		if (!isset($htmlValidator)) return true;
		
		return ($htmlValidator->getNumOfValidateError() == 0);
	}
	
	
	
	public static function check_1073()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		foreach ($e->children() as $child)
		{
			if ($child->tag == "link")
			{
				$rel_val = strtolower(trim($child->attr["rel"]));
				
				if ($rel_val == "stylesheet" && isset($child->attr["href"]))
					return false;
			}
			if($child->tag =="style"){
				return false;
			}
		}	
		return true;
	}
	
	
	public static function check_3000()
	{		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			if (!isset($e->attr["alt"]))
			{
				return false;
			}
		}	
		return true;
	}

	// Input: se l'attributo alt e' di lunghezza zero ritorna falso	(alt="")
	public static function check_3001()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim == "") 
				{
					return false;
				}
			}
		}	
		return true;
	}
	
	// Input: se l'attributo alt e' di lunghezza maggiore di LUNGHEZZA_MASSIMA ritorna falso	
	public static function check_3002()
	{
global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		$MAX_LENGTH = 80;
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim != "" && strlen($alt) > $MAX_LENGTH ) 
				{
					return false;
				}
			}
		}	
		return true;		
	}
	
	
	// Input: se nel contenuto dell'attributo alt trovo l'estensione di una immagine ritorna falso	
	public static function check_3003()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
				{
					$pos_gif = stripos($alt,".gif");
					$pos_jpg = stripos($alt,".jpg");
					$pos_jpeg = stripos($alt,".jpeg");
					$pos_png = stripos($alt,".png");
					$pos_bmp = stripos($alt,".bmp");
					$pos_tga = stripos($alt,".tga");
					
					if($pos_gif !== FALSE || $pos_jpg !== FALSE || $pos_jpeg !== FALSE || 
					   $pos_png !== FALSE || $pos_bmp !== FALSE || $pos_tga !== FALSE ) 					
					{
						return false;
					}
				}
			}
		}	
		return true;	
	}
	
	// Input: se l'attributo alt c'e', se non e' di dimensione zero, se non e' troppo lungo, se non contiene l'estensione di una immagine, ritorna falso lo stesso per segnalare di controllare che l'alt abbia un contenuto adeguato 	
	public static function check_3004()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;
		if ($e->attr["type"] == "button" || $e->attr["type"] == "image")
		{
			
			if (isset($e->attr["alt"])) 
			{	
				$alt = $e->attr["alt"];
				$alt_trim = trim($alt);
				if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
				{
					$pos_gif = stripos($alt,".gif");
					$pos_jpg = stripos($alt,".jpg");
					$pos_jpeg = stripos($alt,".jpeg");
					$pos_png = stripos($alt,".png");
					$pos_bmp = stripos($alt,".bmp");
					$pos_tga = stripos($alt,".tga");
					
					if($pos_gif === FALSE && $pos_jpg === FALSE && $pos_jpeg === FALSE && 
					   $pos_png === FALSE && $pos_bmp === FALSE && $pos_tga === FALSE ) 					
					{
						return false;
					}
				}
			}
		}	
		return true;	
	}


	// Area: se l'attributo alt e' di lunghezza zero ritorna falso	(alt="")
	public static function check_3006()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim == "") 
			{
				return false;
			}
		}
		return true;
	}
	
	// Area: se l'attributo alt e' di lunghezza maggiore di LUNGHEZZA_MASSIMA ritorna falso	
	public static function check_3007()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) > $MAX_LENGTH ) 
			{
				return false;
			}
		}
	
		return true;		
	}
	
	
	// Area: se nel contenuto dell'attributo alt trovo l'estensione di una immagine ritorna falso	
	public static function check_3008()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;
				
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif !== FALSE || $pos_jpg !== FALSE || $pos_jpeg !== FALSE || 
				   $pos_png !== FALSE || $pos_bmp !== FALSE || $pos_tga !== FALSE ) 					
				{
					return false;
				}
			}
		}

		return true;	
	}
	
	// Area: se l'attributo alt c'e', se non e' di dimensione zero, se non e' troppo lungo, se non contiene l'estensione di una immagine, ritorna falso lo stesso per segnalare di controllare che l'alt abbia un contenuto adeguato 	
	public static function check_3009()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif === FALSE && $pos_jpg === FALSE && $pos_jpeg === FALSE && 
				   $pos_png === FALSE && $pos_bmp === FALSE && $pos_tga === FALSE ) 					
				{
					return false;
				}
			}
		}
	
		return true;	
	}


	// Img: se l'attributo alt e' di lunghezza zero ritorna falso	(alt="")
	public static function check_3011()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim == "") 
			{
				return false;
			}
		}
		return true;
	}
	
	// Img: se l'attributo alt e' di lunghezza maggiore di LUNGHEZZA_MASSIMA ritorna falso	
	public static function check_3012()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) > $MAX_LENGTH ) 
			{
				return false;
			}
		}
	
		return true;		
	}
	
	
	// Img: se nel contenuto dell'attributo alt trovo l'estensione di una immagine ritorna falso	
	public static function check_3013()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;
				
		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif !== FALSE || $pos_jpg !== FALSE || $pos_jpeg !== FALSE || 
				   $pos_png !== FALSE || $pos_bmp !== FALSE || $pos_tga !== FALSE ) 					
				{
					return false;
				}
			}
		}

		return true;	
	}
	
	// Img: se l'attributo alt c'e', se non e' di dimensione zero, se non e' troppo lungo, se non contiene l'estensione di una immagine, ritorna falso lo stesso per segnalare di controllare che l'alt abbia un contenuto adeguato 	
	public static function check_3014()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$MAX_LENGTH = 80;

		if (isset($e->attr["alt"])) 
		{	
			$alt = $e->attr["alt"];
			$alt_trim = trim($alt);
			if ($alt_trim != "" && strlen($alt) < $MAX_LENGTH ) 
			{
				$pos_gif = stripos($alt,".gif");
				$pos_jpg = stripos($alt,".jpg");
				$pos_jpeg = stripos($alt,".jpeg");
				$pos_png = stripos($alt,".png");
				$pos_bmp = stripos($alt,".bmp");
				$pos_tga = stripos($alt,".tga");
				
				if($pos_gif === FALSE && $pos_jpg === FALSE && $pos_jpeg === FALSE && 
				   $pos_png === FALSE && $pos_bmp === FALSE && $pos_tga === FALSE ) 					
				{
					return false;
				}
			}
		}
	
		return true;	
	}
	

	// Pseudocodice 37
	// Img: se validazione tramite uri e se trovo longdesc controllo se il file esiste
	public static function check_3015()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		if (($_POST["uri"]) != "http://")
		{
			if (isset($e->attr["longdesc"]))
			{	

				$ld_uri = explode("/",$_POST["uri"]);
				$ld_uri = array_slice($ld_uri, 0, sizeof($ld_uri)-1);
				$ur = implode("/", $ld_uri);

				$ld_path = $ur . "/" . $e->attr["longdesc"];
		
				$AgetHeaders = @get_headers($ld_path);
				if (preg_match("|200|", $AgetHeaders[0])) {
					return true;
				} else {
					return false;
				}
			}
		}
		return true;

	}

	
	//pseudocodice 2a 2.3 
	// object: verifico ricorsivamente la presenza di alternativi testuali
	// Uguale per 3017 e 3019
	public static function check_3017()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		if ($e->parent()->tag!='object')
		return VamolaBasicChecks::check_obj($e,$content_dom);
		else
		return true;
	}
	
	//ritorna false se l'alternativo testuale di object contiene il nome di un file
	public static function check_3018()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		$testo=VamolaBasicChecks::remove_obj($e);
		
		$estensioni= array(".jpg",".jpeg", ".gif", ".png", ".bmp", ".tga", ".mpeg", ".avi", ".mpg");
		if (isset($testo) && trim($testo)!='')// l'elemento contiene del testo
		{
			//echo($e->plaintext);
			
			foreach($estensioni as $est)
			{
			if(stripos($testo,$est) !== false)
			return false;
			
			}
		}
		return true;
	}
	
	
	/***************
	*REQUISITO 5  *
	*			   *
	****************/		
	
		//Pseudocodice 3.3 (Doc 3a)
	//Richiede di verificare se i .gif sono animati (su img)
	
	public static function check_5027()
	{
		global $global_e, $global_content_dom;

		$e = $global_e;
		$content_dom = $global_content_dom;
		
		$ext = strtolower(substr(trim($e->attr["src"]), -4));
		
		return !($ext == ".gif" );	
	}	

	//Pseudocodice 3.4 (Doc 3a)
	//Richiede di verificare se i .png sono animati (su img)
	public static function check_5028()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		$ext = strtolower(substr(trim($e->attr["src"]), -4));
		
		return !($ext == ".png" );	
	}	
	

	
	/**************
	* Requisito 6 *
	**************/
	
	//Pseudocodice 4.2
	public static function check_6000(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//controllo solo gli elementi testuali
		if($e->tag=="p" || $e->tag=="span" || $e->tag=="strong" || $e->tag=="em" || 
		   $e->tag=="q" || $e->tag=="cite" || $e->tag=="blockquote" || $e->tag=="li" || 
		   $e->tag=="dd" ||  $e->tag=="dt" || $e->tag=="td" ||  $e->tag=="th" || 
		   $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6" || 
		   $e->tag=="label" || $e->tag=="acronym" || $e->tag=="abbr" || $e->tag=="code" || $e->tag=="pre")
		{
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
			
				$background=VamolaBasicChecks::getBackground($e);
				$foreground=VamolaBasicChecks::getForeground($e);
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		}
		
		return true;
	}
	
	public static function check_6001(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
		if($e->tag=="p" || $e->tag=="span" || $e->tag=="strong" || $e->tag=="em" || 
		   $e->tag=="q" || $e->tag=="cite" || $e->tag=="blockquote" || $e->tag=="li" || 
		   $e->tag=="dd" ||  $e->tag=="dt" || $e->tag=="td" ||  $e->tag=="th" || 
		   $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6" || 
		   $e->tag=="label" || $e->tag=="acronym" || $e->tag=="abbr" || $e->tag=="code" || $e->tag=="pre")		
		{
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$background=VamolaBasicChecks::getBackground($e);
				$foreground=VamolaBasicChecks::getForeground($e);	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		}
		
		return true;
	}	
	
	//Pseudocodice 4.3
	//su <body>, restituisce un messaggio solo se c'è almeno un'immagine
	public static function check_6002(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		if (BasicChecks::count_children_by_tag($e, "img") > 0)
			return false;
		else
			return true;
	}
	
	//Pseudocodice 4.4
	

	
	public static function check_6003()
	{
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$bg=VamolaBasicChecks::get_p_css($e,"background-image");
		
		if($bg!=""){
			return false;
		}
		return true;
	}
	

	//link visitati
	public static function check_6004(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "visited");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["vlink"]))
						$foreground=$app->attr["vlink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"visited");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link visitati
	public static function check_6005(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "visited");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["vlink"]))
						$foreground=$app->attr["vlink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"visited");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);;	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}
	

	//link attivati
	public static function check_6006(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "active");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["alink"]))
						$foreground=$app->attr["alink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link attivati
	public static function check_6007(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "active");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["alink"]))
						$foreground=$app->attr["alink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);;	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}
		

	//link hover
	public static function check_6008(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "hover");
				//if($foreground=="" || $foreground==null)

				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"hover");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link hover
	public static function check_6009(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "hover");
				//if($foreground=="" || $foreground==null)

				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"hover");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);;	
				
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
				
		
		return true;
	}	
	
	
	//link non visitati
	public static function check_6010(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "link");
				if($foreground=="" || $foreground==null)
					$foreground=VamolaBasicChecks::getForeground($e);
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["link"]))
						$foreground=$app->attr["link"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				if ($ris < 125)
				{	
					return false;
				}
				else{
					return true;
				}
				
		
		return true;
	}
	
	//link non visitati
	public static function check_6011(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG1", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//elementi testuali
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;

				$foreground=VamolaBasicChecks::getForegroundA($e, "link");
				if($foreground=="" || $foreground==null)
					$foreground=VamolaBasicChecks::getForeground($e);
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body" && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["link"]))
						$foreground=$app->attr["link"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::CalculateBrightness(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	

				$ris= VamolaBasicChecks::CalculateColorDifference($background,$foreground);
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				if($ris > 499)
				{
					return true;
					
				}else
				{
					return false;
				}
		return true;
	}		
	
	/*********************
	* WCAG2 AA Algorithm *
	* by Virruso e Tosi  *
	*********************/	
	
	public static function check_6012(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AA", $_GET['contrastType']))
			return true;
		//WCAG2.0 Contrast check
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//controllo solo gli elementi testuali
		if($e->tag=="p" || $e->tag=="span" || $e->tag=="strong" || $e->tag=="em" || 
		   $e->tag=="q" || $e->tag=="cite" || $e->tag=="blockquote" || $e->tag=="li" || 
		   $e->tag=="dd" ||  $e->tag=="dt" || $e->tag=="td" ||  $e->tag=="th" || 
		   $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6" || 
		   $e->tag=="label" || $e->tag=="acronym" || $e->tag=="abbr" || $e->tag=="code" || $e->tag=="pre")
		{
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
			
				$background=VamolaBasicChecks::getBackground($e);
				$foreground=VamolaBasicChecks::getForeground($e);
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				
				
				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				if($e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6")
					$bold="bold";
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 3;
				else
					$threashold = 4.5;
				
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		}
		
		return true;
		
	}
	
	//link visitati
	public static function check_6013(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AA", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "visited");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["vlink"]))
						$foreground=$app->attr["vlink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"visited");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				
				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 3;
				else
					$threashold = 4.5;
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}
	
	//link attivati
	public static function check_6014(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AA", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "active");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["alink"]))
						$foreground=$app->attr["alink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				

				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 3;
				else
					$threashold = 4.5;
					
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}

	//link hover
	public static function check_6015(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AA", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "hover");
				//if($foreground=="" || $foreground==null)

				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"hover");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				

				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 3;
				else
					$threashold = 4.5;
					
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}
		
	//link non visitati
	public static function check_6016(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AA", $_GET['contrastType']))
			return true;
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "link");
				if($foreground=="" || $foreground==null)
					$foreground=VamolaBasicChecks::getForeground($e);
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["link"]))
						$foreground=$app->attr["link"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				

				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 3;
				else
					$threashold = 4.5;
					
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}

	/**********************
	* WCAG2 AAA Algorithm *
	* by Virruso e Tosi   *
	**********************/	
	
	public static function check_6017(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AAA", $_GET['contrastType']))
			return true;
		//WCAG2.0 Contrast check
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';
		//controllo solo gli elementi testuali
		if($e->tag=="p" || $e->tag=="span" || $e->tag=="strong" || $e->tag=="em" || 
		   $e->tag=="q" || $e->tag=="cite" || $e->tag=="blockquote" || $e->tag=="li" || 
		   $e->tag=="dd" ||  $e->tag=="dt" || $e->tag=="td" ||  $e->tag=="th" || 
		   $e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6" || 
		   $e->tag=="label" || $e->tag=="acronym" || $e->tag=="abbr" || $e->tag=="code" || $e->tag=="pre")
		{
				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
			
				$background=VamolaBasicChecks::getBackground($e);
				$foreground=VamolaBasicChecks::getForeground($e);
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				
				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				if($e->tag=="h1" || $e->tag=="h2" || $e->tag=="h3" || $e->tag=="h4" || $e->tag=="h5" || $e->tag=="h6")
					$bold="bold";
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 4.5;
				else
					$threashold = 7;
				
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		}
		
		return true;
		
	}
	
	//link visitati
	public static function check_6018(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AAA", $_GET['contrastType']))
			return true;
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "visited");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["vlink"]))
						$foreground=$app->attr["vlink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"visited");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				

				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 4.5;
				else
					$threashold = 7;
				
				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}
	
	//link attivati
	public static function check_6019(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AAA", $_GET['contrastType']))
			return true;

		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "active");
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["alink"]))
						$foreground=$app->attr["alink"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				

				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 4.5;
				else
					$threashold = 7;

				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}

	//link hover
	public static function check_6020(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AAA", $_GET['contrastType']))
			return true;

		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "hover");
				//if($foreground=="" || $foreground==null)

				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"hover");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				

				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 4.5;
				else
					$threashold = 7;

				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}
		
	//link non visitati
	public static function check_6021(){
		if (!isset($_GET['contrastType']) || !in_array("WCAG2AAA", $_GET['contrastType']))
			return true;
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		$background='';
		$foreground='';

				
				if(trim(VamolaBasicChecks::remove_children($e))=="" ) //l'elemento non contiene testo "visibile": non eseguo il controllo del contrasto
					return true;
				
				$foreground=VamolaBasicChecks::getForegroundA($e, "link");
				if($foreground=="" || $foreground==null)
					$foreground=VamolaBasicChecks::getForeground($e);
				if($foreground=="" || $foreground==null)
				{	
					$app=$e->parent();
					while($app->tag!="body"  && $app->tag!=null)
						$app=$app->parent();
					if($app!=null && isset($app->attr["link"]))
						$foreground=$app->attr["link"];
						
				}
				if($foreground=="" || $foreground==null)
					return true;
				
				$background=VamolaBasicChecks::getBackgroundA($e,"active");
				if($background=="" ||$background==null)
					$background=VamolaBasicChecks::getBackground($e);
					
				$background=VamolaBasicChecks::convert_color_to_hex($background);
				$foreground=VamolaBasicChecks::convert_color_to_hex($foreground);
				
				$ris=VamolaBasicChecks::ContrastRatio(strtolower($background),strtolower($foreground));
				//echo "tag->"; echo $e->tag; echo " bg->"; echo $background; echo " fr->"; echo $foreground; echo " ris="; echo $ris; echo "<br>";	
				
				$size = VamolaBasicChecks::fontSizeToPt($e);
				$bold = VamolaBasicChecks::get_p_css($e,"font-weight");
				//echo "FINAL SIZE: ".$size. " BOLD: ".$bold." <br/>";
				
				if($size<0) //formato non supportato
					return true;
				else if($size>=18 || ($bold=="bold" && $size>=14))
					$threshold = 4.5;
				else
					$threashold = 7;

				if ($ris < $threshold)
				{	
					return false;
				}
				else{
					return true;
				}

		
		return true;
	}
	
	
	/***************
	*REQUISITO 9  *
	*			   *
	****************/	
	//Pseudocodice 2.1
	// Elemento TABLE, tipologia 2
	//
	public static function check_9000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		// Se summary non c'e' (null), oppure � vuoto (""):
		//L'elemento <table> non contiene l'attributo summary. Tale attributo � necessario per fornire una descrizione sul contenuto e sull�organizzazione della tabella.
		
		if (!isset($e->attr["summary"]) || $e->attr["summary"]=='')
			return false;
		else
			return true;
	}

	// Elemento TABLE, tipologia 1
	//
	public static function check_9001()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		
		// Se summary c'e', e non � vuoto:
		//Verificare che l�attributo summary dell�elemento <table> descriva in maniera adeguata il contenuto e l�organizzazione della tabella.

		if (isset($e->attr["summary"]) && $e->attr["summary"]!='')
			return false; 
		else
			return true;
	}	
	//Pseudocodice 2.2
	// Elemento TABLE, tipologia 2
	//	
	public static function check_9002()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		
		// Se non c'e' caption ne' title:
		// 
		//L'elemento <table> non contiene l'attributo title n� l'elemento <caption>, necessari per descrivere la natura della tabella.

				
		$th =$e->find("caption");
		if( ($th== null || sizeof($th)==0) && !isset($e->attr["title"]))
			return false;
		else
			return true;
	}		
	
	// Elemento TABLE, tipologia 1
	//	

	public static function check_9003()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		// Se caption c'e':
		//Verificare che l'elemento <caption> descriva in maniera adeguata la natura della tabella.
		$th =$e->find("caption");
		if( ($th== null || sizeof($th)==0))
			return true;
		else
			return false;
	}			


	//Pseudocodice 2.3
	// Elemento TABLE, tipologia 1
	//	
	public static function check_9005()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		// Se non c'e' nessun th:	
		// Nessun elemento <th> presente all�interno della tabella. Se si tratta di una tabella dati � necessario specificarne le intestazioni tramite questo elemento.

		$th =$e->find("th");
		if( ($th== null || sizeof($th)==0))
			return false;
		else
			return true;
	}	
	// Elemento TABLE, tipologia 1
	//
	public static function check_9006()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
		// Se c'e' th:	
		// Verificare che gli elementi <th> della tabella siano utilizzati per specificare una intestazione e non a scopo decorativo

		$th =$e->find("th");
		if( ($th != null && sizeof($th)!=0))
			return false;
		else
			return true;
	}
	
	//Pseudocodice 2.4
	// Elemento TABLE, tipologia 2
	//
	public static function check_9007()
	{	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
		
		// Se c'e' almeno un th e nessun abbr:	
		// Non � stato individuato alcun attributo abbr per gli elementi <th> presenti nella tabella. Nel caso di etichette di intestazione lunghe pu� essere utile fornirne abbreviazioni.


		$th =$e->find("th");
		
		if( ($th == null || sizeof($th)==0))
			return true;
		else
		{
	
			for($i=0; $i<sizeof($th); $i++)
			{
				
				if(isset($th[$i]->attr['abbr']) && $th[$i]->attr['abbr']!="")
					return true; //c'� almeno un abbr
					
			}
			//se esco dal for non ho trovato nessun abbr
			return false;
		}
	}
		
	
	
	/**************
	* Requisito 10 *
	**************/
	
	//Pseudocodice 3.1

	// Elemento TD, tipologia 0
	//
	public static function check_10000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
		
		//Uno degli id indicati nell�attributo headers dell�elemento <td> non esiste, cio� tale id non � associato a nessuna intestazione.
		
		
		if (!isset ($e->attr["headers"]))
			return true; //non c'� l'attributo headers
		else 
		{
			$headers=$e->attr["headers"];
			$ids=explode(' ',$headers);
			$t=VamolaBasicChecks::getTable($e);
			if($t==null)
				return false;
			return VamolaBasicChecks::checkIdInTable($t,$ids);

		}
		
		
	}
	
	// Elemento TD, tipologia 0
	//
	public static function check_10001()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
	
	
	
		//A questo elemento <td> non � associata nessuna intestazione. Nell�elemento l�attributo headers non � definito o � vuoto ed, inoltre, l�elemento non rientra nello scope di nessuna cella di intestazione.
		
		 if (isset ($e->attr["headers"]) && trim($e->attr["headers"])!='')
			return true; // c'� l'attributo headers
		else
		{
			$t=VamolaBasicChecks::getTable($e);
			if($t==null)
				return true;
			elseif($t->find("th")!=null)//se c'e' almeno un th e' una tabella dati
			{
				
				if(VamolaBasicChecks::getRowHeader($e)==null && VamolaBasicChecks::getColHeader($e)==null)
					return false;
				else 
					return true;
			}
		}
		return true;
		
	}	
	
	public static function check_13001()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
		
		// Se la TABLE ha th | thead | tbody | tfoot | caption:
		// Se questa � una tabella di layout, si ricorda che va evitato l�utilizzo degli elementi marcatori di struttura quali th, thead, tbody, tfoot e caption.
		
		if($e->find("th")!=null || $e->find("th")!=null || $e->find("thead")!=null || $e->find("tbody")!=null || $e->find("tfoot")!=null || $e->find("caption")!=null)
			return false;
		else 
			return true;
		
	}	
	
		
	/***************
	*REQUISITO 12  *
	*			   *
	****************/
	
	//12000 - 12031: conrolli sulle misure relative e contenuto di px per tutti gli elementi
	//sulle proprietà: font-size, line-height, padding-top, padding-bottom, padding-left, 
	//padding-right, margin-top, margin -bottom, margin -left, margin –right, top, 
	//bottom, left, right, width e height
	
	public static function check_12000(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'font-size');
		
	}
	
	public static function check_12001(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'font-size');
		
	}

	public static function check_12002(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'line-height');
		
	}
	
	public static function check_12003(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'line-height');
		
	}	

	
	public static function check_12004(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-top');
	}
	
	
	public static function check_12005(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-top');

	}		
	
	
	public static function check_12006(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-bottom');

	}
	
	public static function check_12007(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-bottom');

	}	
	
	public static function check_12008(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-right');

	}
	
	public static function check_12009(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-right');
		
	}	


	public static function check_12010(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'padding-left');

	}
	
	public static function check_12011(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'padding-left');
		
	}	
	

	public static function check_12012(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-top');
	}
	
	
	public static function check_12013(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-top');

	}		
	
	
	public static function check_12014(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-bottom');

	}
	
	public static function check_12015(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-bottom');

	}	
	
	public static function check_12016(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;			
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-right');

	}
	
	public static function check_12017(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-right');
		
	}	


	public static function check_12018(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'margin-left');

	}
	
	public static function check_12019(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'margin-left');
		
	}
	
	
	public static function check_12020(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'top');

	}
	
	public static function check_12021(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'top');
		
	}	
	
	public static function check_12022(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'bottom');

	}
	
	public static function check_12023(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'bottom');
		
	}	
	
	
	public static function check_12024(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'left');

	}
	
	public static function check_12025(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'left');
		
	}	
	
	public static function check_12026(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'right');

	}
	
	public static function check_12027(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'right');
		
	}	
	
	public static function check_12028(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'width');

	}
	
	public static function check_12029(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'width');
		
	}		
	
	
	public static function check_12030(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkRelative($e,'height');

	}
	
	public static function check_12031(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		return VamolaBasicChecks::checkPx($e,'height');
		
	}	
	
	
	
	/**************
	* Requisito 14 *
	**************/
	// Pseudocodice 2.1
	// Etichetta implicita in elemento input
	public static function check_14000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
	
		if ($e->attr["type"] != "button" && $e->attr["type"] != "submit" &&
			$e->attr["type"] != "reset" && $e->attr["type"] != "image" && 
			$e->attr["type"] != "hidden")
		{
			return BasicFunctionsVamola::check_14002();
		}
		else
			return true;
	}
	
	
	// Pseudocodice 2.1
	// Etichetta implicita in elemento select e textarea
	// check 14001 e 14002
	public static function check_14002()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
	
		
		$foundLabel = FALSE;
 		$isImplicit = FALSE;
 
		$parentFormElement = $e->parent();
		$brotherFormElement = $e->prev_sibling();
		
		$idFormElement = $e->attr["id"];
		
		$labelFormElements = $content_dom->find("label");  
		foreach ($labelFormElements as $label)
		{
			if (strtolower(trim($label->attr["for"])) == strtolower(trim($idFormElement)) && $idFormElement!="")		
				$labelFormElement = $label;
		}
		
		// Etichetta esplicita
		if ( $labelFormElement != NULL || $labelFormElement != "")
		{
			$foundLabel = TRUE;
			$isImplicit = FALSE;
		}	

		// etichetta implicita come padre o fratello
	    else if ( ($parentFormElement->tag == "label" && 
		 		   $parentFormElement->attr["for"] == NULL) || 
		 		  ($brotherFormElement->tag == "label" && 
 				   $brotherFormElement->attr["for"] == NULL))
		{ 
     		 $foundLabel = TRUE;
     		 $isImplicit = TRUE;
		}
		
		if ( $foundLabel == TRUE && $isImplicit == TRUE )
		{
			return false;
		}
		else
		    	return true;
	}
	
	
	// Pseudocodice 2.1
	// Nessuna etichetta esplicita in elemento input
	public static function check_14003()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
	
		$e->attr["type"]=strtolower(trim($e->attr["type"]));
		if ($e->attr["type"] != "button" && $e->attr["type"] != "submit" &&
			$e->attr["type"] != "reset" && $e->attr["type"] != "image" && 
			$e->attr["type"] != "hidden")
		{
			return BasicFunctionsVamola::check_14005();
		}
		else
			return true;
	}
	

	// Pseudocodice 2.1
	// Nessuna etichetta esplicita in elemento select e textarea
	// Per 14004 e 14005
	public static function check_14005()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
	
		$foundLabel = FALSE;
 		$isImplicit = FALSE;
 
		$parentFormElement = $e->parent();
		$brotherFormElement = $e->prev_sibling();
		
		$idFormElement = $e->attr["id"];
		
		$labelFormElements = $content_dom->find("label");  
		foreach ($labelFormElements as $label)
		{
			if (strtolower(trim($label->attr["for"])) == strtolower(trim($idFormElement)) && $idFormElement!="")		
				$labelFormElement = $label;
		}
		
		// Etichetta esplicita
		if ( $labelFormElement != NULL || $labelFormElement != "")
		{
			$foundLabel = TRUE;
			$isImplicit = FALSE;
		}	

		// etichetta implicita come padre o fratello
	    else if ( ($parentFormElement->tag == "label" && 
		 		   $parentFormElement->attr["for"] == NULL) || 
		 		  ($brotherFormElement->tag == "label" && 
 				   $brotherFormElement->attr["for"] == NULL))
		{
     		 $foundLabel = TRUE;
     		 $isImplicit = TRUE;
		}
		
		if ( $foundLabel == TRUE && $isImplicit == TRUE )
		{
			return true;
		}
		else if ( $foundLabel == FALSE)
		{
			return false;
		}
		else
		    return true;
	}

	
	
	/***************
	* Requisito 15 *
	***************/
	
	//Pseudocodice 2.1
	// Elemento BODY, tipologia 0
	public static function check_15000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;		
		
		// Non � presente alcun elemento <noscript> che fornisca una versione alternativa per gli elementi <script> presenti nella pagina.
		$script=$e->find('script');
		
		if(!isset($script) || sizeof($script)==0)//c'� almeno un elemento <script>
			return true;
		else //verifico che sia presente almeno un elemento <noscript>
			{
				$noscript=$e->find('noscript');
				if(!isset($noscript) || sizeof($noscript)==0)
				return false;		
			}

		return true;	
	}
	
	//Pseudocodice 2.4
	// Elemento APPLET, tipologia 0
	public static function check_15002()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
	
		
		$testo=trim($e->plaintext)=="";
		if (isset($testo) && $testo!='')
			return false;
		return true;		
	}
	
	
	// Elemento APPLET, tipologia 2
	public static function check_15004()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
	
		$testo=trim($e->plaintext);
		$estensioni= array(".class");
		if (isset($testo) && $testo!='')// l'elemento contiene del testo
		{
			//echo($e->plaintext);
			
			foreach($estensioni as $est)
			{
			if(stripos($testo,$est) !== false)
			return false;
			
			}
		}
		return true;	
	}
	
	//Pseudocodice 2.5 
	// Elemento HTML, tipologia 1
	public static function check_15005()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
	
		//Assicurarsi che le pagine siano utilizzabili quando script, applet, o altri oggetti di programmazione sono disabilitati o non supportati. Se ci� non fosse possibile fornire una spiegazione testuale della funzionalit� svolta e garantire un�alternativa testuale equivalente.
		return VamolaBasicChecks::rec_check_15005($e);	
	}
	
	
	/***************
	* Requisito 16 *  
	***************/
	//Pseudocodice 3.1
	//vengono richiamati i check 21001 - 21007
	
	//Pseudocodice 3.2
	//body, tipologia 1
	//nota: farlo anche per applet oltre che per object?
	public static function check_16000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		$o=$e->find('object');
		if(isset($o) && sizeof($o)>0)
			return false;
		else 
			return true;
		//Verificare che eventuali applet o oggetti di programmazione dotati di una propria specifica	interfaccia, siano indipendenti da uno specifico dispositivo di input."
		
		
	}	
	
	
	//Controllo che alla presenza di onmouseover corrisponda la presenza di onfocus
	public static function check_16001($e, $content_dom){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;
					
		if(isset($e->attr["onmouseover"])){
			if(!isset($e->attr["onfocus"]))
				return false;
		}		
		return true;
	}
	/*
	//Controllo che alla presenza di onmouseout corrisponda la presenza di onblur
	public static function check_16002(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;			
		
		if(isset($e->attr["onmouseout"])){
			if(!isset($e->attr["onblur"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onmousedown corrisponda la presenza di onkeydown
	public static function check_16003(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;			
		
		if(isset($e->attr["onmousedown"])){
			if(!isset($e->attr["onkeydown"]))
				return false;
		}
		return true;		
	}
	
	//Controllo che alla presenza di onmouseup corrisponda la presenza di onkeyup
	public static function check_16004(){

		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
				
		if(isset($e->attr["onmouseup"])){
			if(!isset($e->attr["onkeyup"]))
				return false;
		}
		return true;		
	}
	*/
	//Controllo che alla presenza di onclick corrisponda la presenza di onkeypress
	public static function check_16005(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
			if($e->tag !== "input" && ($e->attr["type"]!=="button" || $e->attr["type"]!=="submit" || $e->attr["type"]!=="reset"))
			{
				if(isset($e->attr["onclick"])){
					if(!isset($e->attr["onkeypress"]))
						return false;
				}
			}
		return true;		
	}
	/*
	//Controllo che non sia presente ondblclick
	public static function check_16006(){

		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;			
		
		if(isset($e->attr["ondblclick"])) 
				return false;
		else
			return true;		
	}
	
	//Controllo che non sia presente onmousemove
	public static function check_16007(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		if(isset($e->attr["onmousemove"]))
				return false;
		else
			return true;		
	}	
	*/	
	
	
	
	/***************
	* Requisito 18 *
	***************/
	//consiglia di verificare la trascrizione di un filmato puntato da un link
	public static function check_18000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		// come il check_20 con l'aggiunta di ".avi"
		// il check_20 esegue lo stesso controllo del check_145
		//return Checks::check_20($e, $content_dom);		
		$ext = strtolower(substr(trim($e->attr["href"]), -4));
		
		return !($ext == ".wmv" || $ext == ".mpg" || $ext == ".mov" || $ext == ".ram" || $ext == ".aif" || $ext == ".avi");
	}

	
	
	/***************
	* Requisito 19 *
	***************/
	//pseudocodice 5.1
	//elemento a, tipologia 0
	public static function check_19000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		$t=$e->innertext();
		if(stripos($t,"click here")!==false || stripos($t,"clicca")!==false)
			return false;
		else
			return true;
		//Evitare di utilizzare frasi come "Clicca qui" o "Click here" come testo di un link. Il testo dovrebbe fornire informazioni sulla natura della destinazione del collegamento ipertestuale.
	}
	
	
	/***************
	* Requisito 20 *
	***************/	
	
	//controlla la presenza di <meta http-equiv="refresh" contet="x">
	//E’ stato rilevato un elemento <meta http-equiv="refresh" contet="x"> che causa il refresh automatico della pagina entro x seconi. E’ necessario avvertire l’utente della presenza di questa funzionalità e del tempo entro il quale avverà il refresh. Inoltre deve essere fornito un meccanismo che consenta all’utente di controllare tale funzionalità o, in alternativa, un collegamento ad una versione equivalente. 
	////check sul tag <meta>	
		public static function check_20000()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;			
		
		if(isset($e->attr["http-equiv"]) && isset($e->attr["content"]))
		{
			if(strtolower($e->attr["http-equiv"])=="refresh" && is_numeric($e->attr["content"]))
				return false;
		}
			return true;	
	}
	
	//controlla la presenza di <meta http-equiv="refresh" contet="x;url">
	//E’ stato rilevato un elemento <meta http-equiv="refresh" content="x;url"> che causa il reindirizzamento automatico della pagina all’indirizzo url entro x secondi. E’ necessario avvertire l’utente della presenza di questa funzionalità e del tempo entro il quale avverà il reindirizzamento. Inoltre deve essere fornito un meccanismo che consenta all’utente di controllare tale funzionalità o, in alternativa, un collegamento ad una versione equivalente. 
	//check sul tag <meta>
	public static function check_20001()
	{
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;			
		
		if(isset($e->attr["http-equiv"]) && isset($e->attr["content"]))
		{
			if(strtolower($e->attr["http-equiv"])=="refresh" && stripos($e->attr["content"],';')!==false && is_numeric(substr($e->attr["content"],0,stripos($e->attr["content"],';'))))
				return false;
		}
			return true;	
	}
	
	
	
	
	
	
	
	
	/***************
	*REQUISITO 21  *
	*			   *
	****************/
	

	/*Pseudocodice 7.3 */
	
	//controllo dello spazio verticale tra link consecutivi.
	//funzione richiamata su un elemento li
	public static function check_21008(){

		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
			//relativi al fratello precedente di $e
			global $m_bottom;
			global $p_bottom;
			//relativi a $e
			global $m_top;
			global $p_top;
			
			$prev=$e->prev_sibling();
			
			if($prev== null || $prev->tag!="li")//li è il primo elemento della lista
				return true;
			
			$a =$e->find("a");
			$a_prev =$e->prev_sibling()->find("a");
			if(($a== null || sizeof($a)==0) &&($a_prev== null || sizeof($a_prev)==0))//ne' li ne' il suo prev sono link
				return true;
			
			
			//verifico che non siano inline
			$inlinea=VamolaBasicChecks::get_p_css($e, "display");
			$inlinea2=VamolaBasicChecks::get_p_css($e->prev_sibling(), "display");
			

			if(($inlinea!="" && stripos($inlinea,"inline")!==null) && ($inlinea2!=="" || stripos($inlinea2,"inline")!==null))
				return true;		
				
				
				
			VamolaBasicChecks::GetVerticalDistance($e);
			//se non sono in em ritorno false
			if($m_bottom!="" && substr($m_bottom,-2, 2)!="em" || $p_bottom!="" && substr($p_bottom,-2, 2)!="em" || $m_top!="" && substr($m_top,-2, 2)!="em" || $p_top!="" &&substr($p_top,-2, 2)!="em")
			{
				return false; 
			}
			 
			
			$m_bottom = str_ireplace("em","",$m_bottom);
			$m_top = str_ireplace("em","",$m_top);
			$p_bottom = str_ireplace("em","",$p_bottom);
			$p_top = str_ireplace("em","",$p_top);
			
		
			if($p_top=="")
				$p_top=0;
			if($p_bottom=="")
				$p_bottom=0;
			if($m_top=="")
				$m_top=0;		
			if($m_bottom=="")
				$m_bottom=0;
			
				$dist= $p_top + $p_bottom + max( $m_bottom, $m_top );
				
				if($dist<0.5){
					return false;
				}
				
			return true;	
	}
			
	/* Pseudocodice 7.4*/
	//Controllo della distanza minima orizzontale di un li in caso di liste disposte inline
	public static function check_21009(){
			
			global $global_e, $global_content_dom;
		
			$e = $global_e;
			$content_dom = $global_content_dom;	
		
			VamolaBasicChecks::setCssSelectors($content_dom);

			//relativi al fratello precedente di $e
			global $m_right;
			global $p_right;
			//relativi a $e
			global $m_left;
			global $p_left;
			
			$prev=$e->prev_sibling();
			
			if($prev== null || $prev->tag!="li")//li è il primo elemento della lista
				return true;
			
			$a =$e->find("a");
			$a_prev =$e->prev_sibling()->find("a");
			if(($a== null || sizeof($a)==0) &&($a_prev== null || sizeof($a_prev)==0))//ne' li ne' il suo prev sono link
				return true;
				
			//verifico che siano inline
			$inlinea=VamolaBasicChecks::get_p_css($e, "display");
			$inlinea2=VamolaBasicChecks::get_p_css($e->prev_sibling(), "display");
			

			if(($inlinea=="" || stripos($inlinea,"inline")===null) && ($inlinea2=="" || stripos($inlinea2,"inline")===null))
				return true;	
				
			
			VamolaBasicChecks::GetHorizontalDistance($e);
			//se non sono in em ritorno false
			if($m_right!="" && substr($m_right,-2, 2)!="em" || $p_right!="" && substr($p_right,-2, 2)!="em" || $m_left!="" && substr($m_left,-2, 2)!="em" || $p_left!="" &&substr($p_left,-2, 2)!="em")
			{
				return false; 
			}
			 
			
			$m_right = str_ireplace("em","",$m_right);
			$m_left = str_ireplace("em","",$m_left);
			$p_right = str_ireplace("em","",$p_right);
			$p_left = str_ireplace("em","",$p_left);
			
		
			if($p_right=="")
					$p_right=0;
				if($p_left=="")
					$p_left=0;
				if($m_right=="")
					$m_right=0;		
				if($m_left=="")
					$m_left=0;
				
			$dist= $p_right + $p_left + $m_right + $m_left;
			if($dist<0.5){
				return false;	
			}
				
			return true;	
	}	

	
	
//Pseudocodice 7.5
  
	//Controllo lo spazio verticale tra liste di link
	//ol
	public static function check_21010(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
	    global $m_bottom;
		global $p_bottom;	

		$a =$e->find("a");
		if($a== null || sizeof($a)==0)//ol non contiene link
			return true;
		
		
		VamolaBasicChecks::GetVerticalListBottomDistance($e);
		
		
		
		
		if(($m_bottom!="" && substr($m_bottom,-2,2)!="em") || ($p_bottom!="" && substr($p_bottom,-2,2)!="em")){
				return false;
			}
			
		
			
		$m_bottom = substr($m_bottom,0,strlen($m_bottom)-2);
		$p_bottom = substr($p_bottom,0,strlen($p_bottom)-2);
		
		
		if($p_bottom=="")  
				$p_bottom=0;
		if($m_bottom=="")
				$m_bottom=0;
						
		
		
		$dist_bottom= $p_bottom + $m_bottom;
				
			
			
			if($dist_bottom<0.5){
				return false;
			}
		
			return true;
	}
	//ol
	public static function check_21011(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_top;	
		global $p_top;
	
		$a =$e->find("a");
		if($a== null || sizeof($a)==0)//ol non contiene link
			return true;
	
		VamolaBasicChecks::GetVerticalListTopDistance($e);
		
		
		if(($m_top!="" && substr($m_top,-2,2)!="em") || ($p_top!="" && substr($p_top,-2,2)!="em")){					
			return false;
		}

				
		$m_top = substr($m_top,0,strlen($m_top)-2);
		$p_top = substr($p_top,0,strlen($p_top)-2);
		
		
		if($p_top=="")  
				$p_top=0;
		if($m_top=="")
				$m_top=0;


			$dist_top= $p_top + $m_top;
						
			if($dist_top<0.5){
				return false;
			}
		
			return true;	
	}
	//ul
	public static function check_21012(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		//richiamo check_21010
		return BasicFunctionsVamola::check_21010();

	}
	//ul
	public static function check_21013(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		// richiamo check_21011
		return BasicFunctionsVamola::check_21011();
	}
	
	//Pseudocodice 7.6
	
	//Verifica delle corrette dimensioni ridefinite in un "input" con type "button"
	public static function check_21014(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")){
			
			$h =VamolaBasicChecks::get_p_css($e, "height");
			$pl = VamolaBasicChecks::get_p_css($e, "padding-left");
			$pr = VamolaBasicChecks::get_p_css($e, "padding-right");
			$pt = VamolaBasicChecks::get_p_css($e, "padding-top");
			$pb = VamolaBasicChecks::get_p_css($e, "padding-buttom");
			
			
			
			if($h !="" || $pl!="" || $pr!="" || $pt!="" || $pb!=""){
				return false;
			}	
		}
		return true;
	}
		
	public static function check_21015(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_top;	
	
		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")) {
		
			VamolaBasicChecks::GetVerticalListTopDistance($e);
			//echo("<p>BOTTONE= ".$m_top."");
			if(($m_top!="" && substr($m_top,-2,2)!="em")){
				return false;
			}
							
			$m_top = substr($m_top,0,strlen($m_top)-2);
			
			
			//echo("<p>BOTTONE= ".$m_top."</p>");
			//MB
			if($m_top =="")
				$m_top=0;
			//MBif($m_top !="" && $m_top<0.5){
			
			if($m_top<0.5){
				return false;
			}	
		}
		return true;
	}
	
	public static function check_21016(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_bottom;	

		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")){
		
			VamolaBasicChecks::GetVerticalListBottomDistance($e);
			//echo("<p>m_bottom =".$m_bottom."");
			if(($m_bottom!="" && substr($m_bottom,-2,2)!="em")){
				return false;
			}
							
			$m_bottom = substr($m_bottom,0,strlen($m_bottom)-2);
		
			//MB
			if($m_bottom =="")
				$m_bottom=0;			
			
			//MBif($m_bottom !="" && $m_bottom<0.5){
			//echo("<p>m_bottom =".$m_bottom."");
			if($m_bottom<0.5){
				return false;
			}	
		}
		return true;
	}
	
	public static function check_21017(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
	
		global $m_left;	

		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")) {
		
			VamolaBasicChecks::GetHorizontalListLeftDistance($e);
			
			if(($m_left!="" && substr($m_left,-2,2)!="em")){
				return false;
			}
							
			$m_left = substr($m_left,0,strlen($m_left)-2);
			
			
			//MB
			if($m_left =="")
				$m_left=0;			
			
			//MB if($m_left !="" && $m_left<0.5){
			if($m_left<0.5){
				return false;
			}	
		}
		return true;
	}
	
	public static function check_21018(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_right;	

		if($e->tag == "input" && ($e->attr["type"]=="button" || $e->attr["type"]=="submit" || $e->attr["type"]=="reset")){
		
			VamolaBasicChecks::GetHorizontalListRightDistance($e);
			
			if(($m_right!="" && substr($m_right,-2,2)!="em")){
				return false;
			}
							
			$m_right = substr($m_right,0,strlen($m_right)-2);
		
			//MB
			if($m_right =="")
				$m_right=0;
			
			//MB if($m_right !="" && $m_right<0.5){
			if($m_right<0.5){
				return false;
			}	
		}
		return true;
	}
		

	//Verifica delle corrette dimensioni ridefinite in un "button"
	public static function check_21019(){
		
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
			
			$h =VamolaBasicChecks::get_p_css($e, "height");
			$pl = VamolaBasicChecks::get_p_css($e, "padding-left");
			$pr = VamolaBasicChecks::get_p_css($e, "padding-right");
			$pt = VamolaBasicChecks::get_p_css($e, "padding-top");
			$pb = VamolaBasicChecks::get_p_css($e, "padding-buttom");
			
			
			
			if($h !="" || $pl!="" || $pr!="" || $pt!="" || $pb!=""){
				return false;
			}	
		//}
		return true;
	}
		
	public static function check_21020(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
	
		global $m_top;	
	
		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetVerticalListTopDistance($e);
			//echo("<p>BOTTONE= ".$m_top."");
			if(($m_top!="" && substr($m_top,-2,2)!="em")){
				return false;
			}
							
			$m_top = substr($m_top,0,strlen($m_top)-2);
			
			
			//echo("<p>BOTTONE= ".$m_top."</p>");
			//MB
			if($m_top =="")
				$m_top=0;
			//MBif($m_top !="" && $m_top<0.5){
			
			if($m_top<0.5){
				return false;
			}	
		//}
		return true;
	}
	
	public static function check_21021(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);
		
		global $m_bottom;	

		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetVerticalListBottomDistance($e);
			//echo("<p>m_bottom =".$m_bottom."");
			if(($m_bottom!="" && substr($m_bottom,-2,2)!="em")){
				return false;
			}
							
			$m_bottom = substr($m_bottom,0,strlen($m_bottom)-2);
		
			//MB
			if($m_bottom =="")
				$m_bottom=0;			
			
			//MBif($m_bottom !="" && $m_bottom<0.5){
			//echo("<p>m_bottom =".$m_bottom."");
			if($m_bottom<0.5){
				return false;
			}	
		//}
		return true;
	}
	
	public static function check_21022(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);

		global $m_left;	

		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetHorizontalListLeftDistance($e);
			
			if(($m_left!="" && substr($m_left,-2,2)!="em")){
				return false;
			}
							
			$m_left = substr($m_left,0,strlen($m_left)-2);
			
			
			//MB
			if($m_left =="")
				$m_left=0;			
			
			//MB if($m_left !="" && $m_left<0.5){
			if($m_left<0.5){
				return false;
			}	
		//}
		return true;
	}
	
	public static function check_21023(){
	
		global $global_e, $global_content_dom;
		
		$e = $global_e;
		$content_dom = $global_content_dom;	
		
		VamolaBasicChecks::setCssSelectors($content_dom);

		global $m_right;	

		//if(($e->tag == "input" && $e->attr["type"]=="button") || $e->tag == "button"){
		
			VamolaBasicChecks::GetHorizontalListRightDistance($e);
			
			if(($m_right!="" && substr($m_right,-2,2)!="em")){
				return false;
			}
							
			$m_right = substr($m_right,0,strlen($m_right)-2);
		
			//MB
			if($m_right =="")
				$m_right=0;
			
			//MB if($m_right !="" && $m_right<0.5){
			if($m_right<0.5){
				return false;
			}	
		//}
		return true;
	}		
	
	
	
	
	
	
	// return !(BasicFunctions::hasAttribute('frameborder') || BasicFunctions::hasAttribute('marginwidth') || BasicFunctions::hasAttribute('marginheight')); 
	//  return BasicFunctionsVamola::check_1056();
	//  return BasicFunctionsVamola::check_1066();
	//  return BasicFunctionsVamola::check_1067();
	//  return BasicFunctionsVamola::check_1068();
	//  return BasicFunctionsVamola::check_1069();
// return BasicFunctions::htmlValidated();
	
}
?>  
