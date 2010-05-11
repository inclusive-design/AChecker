<?php

if (!defined("AC_INCLUDE_PATH")) die("Error: AC_INCLUDE_PATH is not defined.");

class VamolaBasicChecks {

        //controllo se ad un elemento è associata più di una classe
        //es: class="classe1 classe2"
        public static function isMultiClass($e)
        {
           
            if(isset($e->attr["class"]) && stripos(trim($e->attr["class"]),' ')!==false)
                return true;
            else
                return false;
        }


	public static function getSiteUri($uri){
			
			
			if(stripos($uri,".php")!==false || stripos($uri,".html")!==false || stripos($uri,".asp")!==false || stripos($uri,".htm")!==false || stripos($uri,".xhtml")!==false || stripos($uri,".xhtm")!==false)
			{	
				//devo eliminare la parte dopo l'ultimo /
				$uri=strrev($uri);
				$posizione= stripos($uri,"/");
				$uri=strrev($uri);
				$uri=substr($uri,0,-$posizione);
			}
			//se c'e', elimino lo / alla fine dell'url
			if(substr($uri,-1)=="/")
				$uri=substr($uri,0,-1);
				
			return $uri;
	}
	
	
	
	// Prende in input l'URL su cui si fa la validazione e l'URL contenuto nei vari attributi href, longdesc ecc e restituisce una stringa contenente il merge tra le due stringhe (o solo la seconda stringa, nel caso fosse gia' un URL assoluto) 
	function mergeUri($uriSite, $relUri)
	{
			if ( strpos($uriSite, "http://") === false)
			{
				$uriSite = "http://". $uriSite;
			}
			// se l'URL parziale contiene gia' http allora e' gia' un url assoluto
			if ( strpos($relUri, "http://") !== false)
			{
				return $relUri;
			}
			// Se l'ultimo carattere di urlSite e' /  slashUrlSite = TRUE;
			if (substr($uriSite, -1) === "/") 
			{
				$slashUrlSite = TRUE;
			}
			// Se il primo carattere di imgRelUrl e' / slashImgRelUrl = TRUE;
			if (substr($relUri, 0, 1) === "/") 
			{
				$slashRelUrl = TRUE;
			}
			if ($slashUrlSite == TRUE && $slashRelUrl == TRUE ) 
			{
				// Se entrambi hanno lo slash, ne levo uno
				$uriSite = substr($uriSite, 0, -1);
			}
			if ($slashUrlSite == FALSE && $slashRelUrl == FALSE ) 
			{
				// Se nessuno ha lo slash, ne aggiungo uno
				$uriSite = $uriSite. "/";
			}
			return $uriSite . $relUri;
	}
	
	
	
	// Restituisce una stringa con tutti gli id dei check, separati da virgola, che riguardano la guideline e l'elemento specificato
	// Simo: Spostato in GuidelinesDAO
//	function getCheckByTagAndGuideline($tag, $guideline)
//	{
//		global $db;
//	
//		$sql = "select distinct c.check_id,c.html_tag
//					from ". TABLE_PREFIX ."guidelines g, 
//					     ". TABLE_PREFIX ."guideline_groups gg, 
//					     ". TABLE_PREFIX ."guideline_subgroups gs, 
//					     ". TABLE_PREFIX ."subgroup_checks gc,
//					     ". TABLE_PREFIX ."checks c
//					where g.guideline_id = '". $guideline ."'
//					  and g.guideline_id = gg.guideline_id
//					  and gg.group_id = gs.group_id
//					  and gs.subgroup_id = gc.subgroup_id
//					  and gc.check_id = c.check_id
//					  and c.html_tag = '" . $tag ."'
//					order by c.html_tag";
//		$check = ",";
//		$result	= mysql_query($sql, $db) or die(mysql_error());
//		while ($row = mysql_fetch_assoc($result))
//		{
//			$check .=  $row["check_id"].",";
//		}
//		return $check;
//	}


	//rimuove tutti gli elementi <object> figli di $e e restituisce il contenuto sotto forma di "plaintext"
	public static function remove_obj($e)
	{

		$contenuto_obj=$e->plaintext;
		$figli_obj=$e->find('object');
		
		foreach ($figli_obj as $obj)
		{
			
			$txt=$obj->plaintext;
			if($txt!=null || $txt!='')
			{
				$arr=explode($txt, $contenuto_obj, 2);
				$contenuto_obj=implode($arr);
			}
		}
		
		return $contenuto_obj;
	}

	//rimuove tutti gli elementi figlil figli di $e e restituisce il contenuto sotto forma di "plaintext"
	public static function remove_children($e)
	{

		$contenuto_obj=$e->plaintext;
		$figli=$e->children();
		
		foreach ($figli as $obj)
		{
			
			$txt=$obj->plaintext;
			if($txt!=null || $txt!='')
			{
				$arr=explode($txt, $contenuto_obj, 2);
				$contenuto_obj=implode($arr);
			}
		}
		
		return $contenuto_obj;
	}	
	
	
	
	//esegue il controllo ricorsivo su obj per valutare l'esistenza di un alternativo testuale
	public static function check_obj($e, $content_dom)
	{
				$testo=VamolaBasicChecks::remove_obj($e);
				
				if (isset($testo) && trim($testo)!='')// l'elemento contiene del testo
				{
				
					return true;
				}
				else //controllo i figli
				{		
						$obj_children = $e->children();
						
						if ($obj_children==null || sizeof($obj_children)==0)
						return false;
						$flag=0;
						foreach ($obj_children as $child)
						{
			
							if($child->tag=="object")
							{   $flag=1; //c'è almeno un object figlio
								if(VamolaBasicChecks::check_obj($child, $content_dom)==false)
									return false;
							}
						}
						if($flag==0)
							return false;
						
						return true;
				}
		
	}	
	

	/*
	* Ricerca e restituisce il valore di una proprieta' CSS (il valore compreso tra ":" e ";")
	* Esegue la ricerca nello stile inline e nel foglio di stile (id, class, nome proprietà)
	* Prende come parametri l'elemento e il nome della proprieta'
	*/
	public static function get_p_css($e, $p)
	{
		
			//controllo sullo stile inline
			if(isset($e->attr["style"])){
				
					$inline=VamolaBasicChecks::GetElementStyleInline($e->attr["style"],$p);
					//verifico "!important"
					$posizione = stripos($inline, "!important");
					if ($posizione !==false)
					{
							//tolgo "!important" e ritorno il valore della proprietà
							//echo str_ireplace("!important", "", $inlinea_inline);
							$inline = str_ireplace($p,"",$inline);
							$inline = str_ireplace(":","",$inline);
							$inline = str_ireplace("!important", "", $inline);
							return $inline;
							
					}
			}
			
			
			//controllo sullo stile interno e sugli stili esterni
			//$best: memorizzera' il valore della regola che ha priorita' piu' alta contenuta nello stile interno/esterno
			//relativamente all'elemento $e e alla proprita' $p
			$best=null;
			
			
			//id
			if(isset($e->attr["id"]))
			{
						$id=VamolaBasicChecks::GetElementStyleId($e,$e->attr["id"],$p);
						$best=VamolaBasicChecks::getPriorityInfo($best,$id);	
			}
			//classe
			if(isset($e->attr["class"]))
			{
                                                
						$class=VamolaBasicChecks::GetElementStyleClass($e,$e->attr["class"],$p);
						$best=VamolaBasicChecks::getPriorityInfo($best,$class);
			}			
			//tag
			$tag=VamolaBasicChecks::GetElementStyle($e,$e->tag,$p);
			
			
			$best=VamolaBasicChecks::getPriorityInfo($best,$tag);

						
			
			//cerco *, nel foglio interno/esterno
			//applico l'eventuale proprieta' di * se:
			//nello stile interno o esterno non e' dichiarata la proprieta' $p per l'elemento $e
			//se quella dichiarata in * è important, ma quella dello stile i o e non lo e'
			
			$best_all=VamolaBasicChecks::GetElementStyle($e,'*',$p);	
						
			
			if($best==null || (stripos($best["valore"],"!important")===false && stripos($best_all["valore"],"!important")!==false))
					$best=$best_all;
			
			//se arrivo qui lo stile inline non ha !important dato che lo controllo all'inizio
			//lo stile inline ha sempre priorita' massima, a meno che una regola in uno stile interno/esterno non contenga  !important
			
			if($inline!=null && $inline!="")
			{
				if(stripos($best["valore"],"!important")===false)//non c'e' !important nel foglio di stile
							
					return $inline;
			}
			//se nello stile inline $p non c'e' restituisco il valore di $best
			//echo("<p>regola</p>");
			//print_r($best["css_rule"]);
			
			
			//array css contiene le regole dei css che verranno stampate in output
			
			global $array_css;
			
			if(isset($best["css_rule"]))
			{
				$same=false;
				if(sizeof($array_css)>0)
				foreach($array_css as $rule)
				{
					
					if(sizeof($rule["prev"]) == sizeof($best["css_rule"]["prev"]))
					{
						for ($i=0; $i<sizeof($rule["prev"]); $i++)
						{
							if($rule["prev"][$i]==$best["css_rule"]["prev"][$i])
								$same=true;
							else
								{ 
									$same=false;
									break;
								}
						}
						if ($same==true)
							break;
					}
					
					
				
				}
				
				if($same==false)
				
					array_push($array_css,$best["css_rule"]);
			}
			
			return $best["valore"];
			
			
	}

		
	public static function get_p_css_a($e, $p, $link_sel)
	{
		

			//$best: memorizzera' il valore della regola che ha priorita' piu' alta contenuta nello stile interno/esterno,
			//relativamente all'elemento $e e alla proprita' $p
			$best=null;
			
			//id
			if(isset($e->attr["id"]))
			{
						$id=VamolaBasicChecks::GetElementStyleId($e,$e->attr["id"].":".$link_sel,$p);
						$best=VamolaBasicChecks::getPriorityInfo($best,$id);	
			}
			//classe
			if(isset($e->attr["class"]))
			{
						$class=VamolaBasicChecks::GetElementStyleClass($e,$e->attr["class"].":".$link_sel,$p);
						$best=VamolaBasicChecks::getPriorityInfo($best,$class);
			}			
			//tag
			$tag=VamolaBasicChecks::GetElementStyle($e,$e->tag.":".$link_sel,$p);
			$best=VamolaBasicChecks::getPriorityInfo($best,$tag);

						
			//se nello stile inline $p non c'e' restituisco il valore di $best
			//echo("<p>regola</p>");
			//print_r($best["css_rule"]);
			
						
			global $array_css;
			
			if(isset($best["css_rule"]))
			{
				$same=false;
				if(sizeof($array_css)>0)
				foreach($array_css as $rule)
				{
					
					if(sizeof($rule["prev"]) == sizeof($best["css_rule"]["prev"]))
					{
						for ($i=0; $i<sizeof($rule["prev"]); $i++)
						{
							if($rule["prev"][$i]==$best["css_rule"]["prev"][$i])
								$same=true;
							else
								{ 
									$same=false;
									break;
								}
						}
						if ($same==true)
							break;
					}
					
					
				
				}
				
				if($same==false)
				
					array_push($array_css,$best["css_rule"]);
			}
			
			return $best["valore"];
			
			
	}	
	
	/*Prende in input due strutture dati rappresentanti due regole css
	(ogni struttura contiene il valore della proprieta' e il numero di id, class e tag contenuti nel selettore)
	Restituisce la regola che ha priorità più alta in base alla tipologia dei selettori
	Se le due regole hanno identica priorita, restituisce quella con posizione maggiore
	*/
	public static function getPriorityInfo($info1, $info2)
	{
			
		
			if($info1==null || $info1=="")
				return $info2;
			if($info2==null || $info2=="")
				return $info1; 
			
			if(stripos($info1["valore"],"!important")!==false && stripos($info2["valore"],"!important")===false)
			{
					$best=$info1;
			}
			elseif(stripos($info1["valore"],"!important")===false && stripos($info2["valore"],"!important")!==false)
			{
					$best=$info2;
			}
			else 
			//hanno entrambe !important o non lo hanno nessuna della due, quindi verifico il numeo di id
			{	
						
						if($info1["num_id"]>$info2["num_id"])
						{
							$best=$info1;
						}
						elseif($info1["num_id"]<$info2["num_id"])
						{
							$best=$info2;
						}
						else
						{	//stesso numero di id, controllo il numero di class
							
								if($info1["num_class"]>$info2["num_class"])
								{
									$best=$info1;
								}
								elseif($info1["num_class"]<$info2["num_class"])
								{
									$best=$info2;
								}
								else
								{	//stesso numero di id e class, controllo in numero di tag
										
									if($info1["num_tag"]>$info2["num_tag"])
									{  //stesso o maggiore numero di id, class e tag: la priorità è della nuova regola
										$best=$info1;
									}
									elseif($info1["num_tag"]<$info2["num_tag"])
									{
										$best=$info2;
									}
									else 
									{
										//le due regole sono perfettamente equivalenti, quindi restituisco
										// con idcss piu' piccolo (idcss == 0 è il foglio interno).
										if($info1["css_rule"]["idcss"]>$info2["css_rule"]["idcss"])
											$best=$info1;
										elseif($info1["css_rule"]["idcss"]<$info2["css_rule"]["idcss"])
											$best=$info2;	
										else 
											{//le due regole equivalenti sono nello stesso css (interno o esterno)
												
												if($info1["css_rule"]["posizione"]>$info2["css_rule"]["posizione"])
													$best=$info1;
												else 
													$best=$info2;
											}
										/*	
										echo("<p>info1</p>");
										print_r($info1);	
										echo("<p>info2</p>");
										print_r($info2);
										*/
									}
									
								}
						}
			}
		
		return $best;
	
	}
	
	
	
	/*
	* Controlla la presenza di text-decoration: blink
	*/
	public static function check_blink($e, $content_dom)
	{

			$inlinea = VamolaBasicChecks::get_p_css($e, "text-decoration", $b);	
			//echo("<p>blink--->".$inlinea."</p>");	
			if (strpos($inlinea, "blink")!== false)
				return false;	
			
			return true;
	}	

	/**MODIFICA FILO
	* Funzione che cerca suddivide la struttura di uno stile (interno o esterno) e ne ricava i selettori e gli attributi
	*/
	public static function GetCSSDom($css_content,$b){
				
		global $selettori;
		global $attributi;
		global $attributo_selettore;
		
		//in posizione 0 memorizzo lo stile interno (tutti quelli in <style></style>, inclusi gli @import
		//lo inizializzo a array(), anche se non c'è nessuno stile interno.
		//$selettori[0]=array();
		
		//tolgo questa parte di filippo
		//while (eregi('/\*',$css_content)) {
			/* Trovo il punto esatto dove inizia il commento */
			//$start=strpos($css_content,'/*');
			/* Prelevo tutto quello che si trova prima del commento */
			//$prima=substr($css_content,0,$start);
			/* Trovo il punto esatto dove finisce il commento */
			//$end=strpos($css_content,'*/');
			/* Ricavo tutto il codice che segue il commento */
			//$dopo=stristr($css_content,'*/');
			//$dopo=substr($dopo,2);
		
			//$css_content=$prima.$dopo;	
		 //}
		
		 
		 //MB elimino i commenti
		 $css_content=preg_replace('/\/\*(.|\s)*?\*\//', '',$css_content);
		 
		 
	
			/* Inserisco all'inizio del codice del CSS la parentesi graffa '}' per facilitare
			   l'estrazione degli elementi: ad ogni lettura prendo da '}' a '}' */
		$css_content='}'.$css_content;
		$i=0;
		
		while(eregi('}([^}]*)}',$css_content,$elemento)){
			 $elemento[1]=$elemento[1].'}';
			 $css_content=substr($css_content,strlen($elemento[1]));
			 $elemento[$i]=trim($elemento[1]);
			 $selettore=substr($elemento[1],0,strpos($elemento[1],'{'));
			 $selettori[$b][$i]=trim($selettore)."{";
			 
				// Dentro $selettori ho la lisat dei selettori;
			 if (eregi('\{(.*)\}',$elemento[1],$attributo)) {
				$attributo[1]=trim($attributo[1]);
				$attributi[$b][$i]=$attributo[1];
			 }
			 $cont=0;
			 while(eregi('^([^;]*);',$attributi[$b][$i],$singolo)){
				$attributi[$b][$i]=substr($attributi[$b][$i],strlen($singolo[1])+1);
				$attributo_selettore[$b][$i][$cont]=trim($singolo[1]);
				
				//Controlli per eliminiare gli spazi bianchi dai selettori
				$pos_spazio=strpos($attributo_selettore[$b][$i][$cont],':');
				$stringa_prima=substr($attributo_selettore[$b][$i][$cont],0,$pos_spazio);
				$stringa_prima=trim($stringa_prima); 
				$stringa_dopo=substr($attributo_selettore[$b][$i][$cont],$pos_spazio+1,strlen($attributo_selettore[$b][$i][$cont])-strlen($string_prima)-1);
				$stringa_dopo=trim($stringa_dopo);
				$attributo_selettore[$b][$i][$cont]=$stringa_prima.':'.$stringa_dopo;
				$attributo_selettore[$b][$i][$cont]=trim($attributo_selettore[$b][$i][$cont]);
				
				$cont++;	
			 }		 
		$i++;
		}
		

	}

	//restituisce il valore della proprieta $val in uno stile inline $stile
	public static function GetElementStyleInline($stile,$val){
		
        //creo un array contenente tutte le regole separate da ";"
        $array_pr = split(";", $stile);
        $arr_val= array();
        $valore_proprieta="";
        
               
        $i=0;
        foreach($array_pr as $regola)
        {
        	//spezzo ogni regola, separata dai ":" in: proprieta=>valore
        	$appoggio=split(":", trim($regola)); 
        	if(isset($array_val[trim($appoggio[0])]) && stripos($array_val[$appoggio[0]]["val"], "!important")!==false)
        	{
        		if(stripos($appoggio[1], "!important")!==false)
        		{
        			$array_val[$appoggio[0]]["val"]=trim($appoggio[1]);
        			$array_val[$appoggio[0]]["pos"]=trim($i);
        		}
        			
        		
        	}
        	else 
        	{
        		$array_val[$appoggio[0]]["val"]=trim($appoggio[1]);
        		$array_val[$appoggio[0]]["pos"]=trim($i);
        	}
        	$i++;
        }
        
    
        //cerco se la proprieta' $val è definita e la restituisco
        switch($val){
       				case "margin-top":
						if(isset($array_val[$val]) || isset($array_val["margin"]))
							$valore_proprieta=VamolaBasicChecks::getTop(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["margin"]));
					break;
					
					case "margin-bottom":
						if(isset($array_val[$val]) || isset($array_val["margin"]))
							$valore_proprieta=VamolaBasicChecks::getBottom(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["margin"]));
					break;
					
					case "margin-left":
						if(isset($array_val[$val]) || isset($array_val["margin"]))
							$valore_proprieta=VamolaBasicChecks::getLeft(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["margin"]));
					break;
					
					case "margin-right":
						if(isset($array_val[$val]) || isset($array_val["margin"]))
							$valore_proprieta=VamolaBasicChecks::getRight(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["margin"]));
					break;
					
					case "padding-top":
						if(isset($array_val[$val]) || isset($array_val["padding"]))
							$valore_proprieta=VamolaBasicChecks::getTop(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["padding"]));
					break;
					
					case "padding-bottom":
						if(isset($array_val[$val]) || isset($array_val["padding"]))
							$valore_proprieta=VamolaBasicChecks::getBottom(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["padding"]));
					break;
					
					case "padding-left":
						if(isset($array_val[$val]) || isset($array_val["padding"]))
							$valore_proprieta=VamolaBasicChecks::getLeft(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["padding"]));
					break;
					
					case "padding-right":
						if(isset($array_val[$val]) || isset($array_val["padding"]))
							$valore_proprieta=VamolaBasicChecks::getRight(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["padding"]));
					break;
					
					case "background-color":
						if(isset($array_val[$val]) || isset($array_val["background"]))
							$valore_proprieta=VamolaBasicChecks::getBgColor(VamolaBasicChecks::get_priority_prop($array_val[$val],$array_val["background"]));
					break;
						
					default:
						if(isset($array_val[$val]))
							$valore_proprieta = $array_val[$val]["val"];
					break;
        
        }
        
        return $valore_proprieta;
        
	}
	
	
	//riceve il contenuto della proprietà background/background-color
	//e restituisce il colore di background se definito
	public static function getBgColor($stringa_valori){
		
	
				$nomi_colori=array ('black' ,
									'silver',
									'gray' ,
									'white' ,
									'maroon' ,
									'red' ,
									'purple' ,
									'fuchsia' ,
									'green' ,
									'lime' ,
									'olive' ,
									'yellow' ,
									'navy' ,
									'blue' ,
									'teal' ,
									'aqua' ,
									'gold' ,
									'navy');       
		
									
				$array_valori=split(" ", $stringa_valori);
				
				foreach($array_valori as $val)
				{
						if(stripos($val,"#")!==false || stripos($val,"rgb(")!==false)
						{
							return $val;
						}
						else// controllo i nomi dei colori
						{
							foreach($nomi_colori as $colore)
								if(stripos($val,$colore)!==false)
									return $colore;
						}
				}
			
	}
	
	//riceve il contenuto della proprietà margin/padding e restituisce il valore del margin/padding sinistro
	public static function getLeft($stringa_valori){
		
				$has_important=stripos($stringa_valori,"!important");
				//se c'è rimuovo !important e lo attacco alla fine
				if($has_important!==false)
				{
					$stringa_valori=str_ireplace("!important","",$stringa_valori);
					$stringa_valori=trim($stringa_valori);
				}
				$array_valori=split(" ", $stringa_valori);
				$size=sizeof($array_valori);
				if($size<=0)
					return "";
				else
					$val_ret= $array_valori[$size-1]; //ultimo valore, quindi left
				
				
				if($has_important===false)
					return $val_ret; 
				else
					return "".$val_ret." !important";
		
	}
	
	//riceve il contenuto della proprietà margin/padding e restituisce il valore del margin/padding destro
	public static function getRight($stringa_valori)
	{
				$has_important=stripos($stringa_valori,"!important");
				//se c'è rimuovo !important
				if($has_important!==false)
				{
					$stringa_valori=str_ireplace("!important","",$stringa_valori);
					$stringa_valori=trim($stringa_valori);
				}
				
				$array_valori=split(" ", $stringa_valori);
				$size=sizeof($array_valori);
				if($size<=0)
					return "";
				else
				{
					if ($size>=2)
						$val_ret = $array_valori[1]; //secondo valore, quindi right
					else
					
						$val_ret = $array_valori[0]; //primo valore
				}
				
				if($has_important===false)
					return $val_ret; 
				else
					return "".$val_ret." !important";
		
	}
	
	//riceve il contenuto della proprietà margin/padding e restituisce il valore del margin/padding alto
	public static function getTop($stringa_valori)
	{
		
				$has_important=stripos($stringa_valori,"!important");
				//se c'è rimuovo !important
				if($has_important!==false)
				{
					$stringa_valori=str_ireplace("!important","",$stringa_valori);
					$stringa_valori=trim($stringa_valori);
				}		
		
				$array_valori=split(" ", $stringa_valori);
				if(sizeof($array_valori)<=0)
					return "";
				else
					$val_ret = $array_valori[0];//primo valore, quindi top
		
				if($has_important===false)
					return $val_ret; 
				else
					return "".$val_ret." !important";			
			
	}
	
	//riceve il contenuto della proprietà margin/padding e restituisce il valore del margin/padding basso
	public static function getBottom($stringa_valori)
	{

				$has_important=stripos($stringa_valori,"!important");
				//se c'è rimuovo !important
				if($has_important!==false)
				{
					$stringa_valori=str_ireplace("!important","",$stringa_valori);
					$stringa_valori=trim($stringa_valori);
				}			
		
				$array_valori=split(" ", $stringa_valori);
				$size=sizeof($array_valori);
				if($size<=0)
					return "";
				else
				{
					if ($size>=3)
						$val_ret = $array_valori[2]; //terzo valore, quindi bottom
					else
						$val_ret = $array_valori[$size-1]; //secondo o primo valore	
				}
				
				if($has_important===false)
					return $val_ret; 
				else
					return "".$val_ret." !important";			
		
	}
		
		
	
	
	//funzione per parametrizzare la ricerca nei fogli di stile di id, class o elementi generici (tag).
	//$marker contiene "#", "." o "" rispettivamente per id, classi o elementi generici.
	//vecchia: public static function getElementStyleGeneric($e,$marker,$tag,$val,$idcss){
	public static function getElementStyleGeneric($e,$marker,$tag,$val){
		
				global $selettori_appoggio;
				$info_proprieta = null;
				$elemento=$marker.$tag;
		
	
			
				//if(isset ($selettori_appoggio[$idcss][$marker.$tag]))
				if(isset ($selettori_appoggio[$marker.$tag]))
				{
					//$array_subset_selettori= $selettori_appoggio[$idcss][$marker.$tag];
					$array_subset_selettori= $selettori_appoggio[$marker.$tag];
					$info_proprieta=VamolaBasicChecks::get_proprieta($array_subset_selettori,$val,$e,$marker.$tag);
					//print_r($array_subset_selettori);
	
				}

				return $info_proprieta;

	}

	
	//restituisce il valore della proprieta' di priorita' più alta in base alla posizione o a "!important"
	//ad esempio viene usata per quelle regole che contengono sia la definizione di margin che di margin-top
	public static function get_priority_prop($reg1, $reg2)
	{

				if(!isset($reg1))
					$valore_proprieta_new = $reg2["val"];
				elseif(!isset($reg2))
					$valore_proprieta_new = $reg1["val"];
				elseif(stripos($reg1["val"],"!important")===false && stripos($reg2["val"],"!important")===false)
				{
					if($reg1["pos"] > $reg2["pos"])
						$valore_proprieta_new =$reg1["val"];
					else
						$valore_proprieta_new =$reg2["val"];
				}elseif(stripos($reg1["val"],"!important")!==false)
				{
					$valore_proprieta_new =$reg1["val"];
				}
				else 
				{
					$valore_proprieta_new =$reg2["val"];
				}
		
				return $valore_proprieta_new;
	}
	
	
	/*
		$array_subset_selettori contiene tutte le regole (semplici e composte) che hanno in ultima
		posizione dei selettori della regola (es per elem p: p{}, div>p{}, .class p{}) l'elemento
		$elemento_radice (cioè un tag, un id o un class)
		$val= prorieta' da ricercare
		$e_original = l'elemento vero e proprio, necessario per verificare l'associazione delle regole composte,
					  verificando le discendenze ($e->parent() per " " o ">", $e->prev_sibling() per "+") 
	*/
	public static function get_proprieta($array_subset_selettori,$val,$e_original,$elem_radice){
		
		global $selettori_appoggio;
		$valore_proprieta=null;
		$num_id=0;
		$num_class=0;
		$num_tag=0;
		$num_regola=0;//lo uso nel foreach per tenere traccia della posizione della regola di priorita' maggiore associata a $elem_radice
		
		$spazio="{_}"; //serve per i casi in cui uno spazio tra due elementi è significativo. es: "div.class" e "div .class"
			foreach ($array_subset_selettori as $array_regole)
			{	
				
				
				
				//verifico se in [$regola]["regole"] e' contenuta la proprieta' $val e la memorizzo in $valore_proprieta_new
				//uso un case per le proprietà particolari come margin e padding
				//per queste proprieta' la funzione VamolaBasicChecks::get_priority_prop valuta quale proprieta' ha la priorita' maggiore
				//ad es tra margin-top e margin (cioe' se una delle due "sovrascrive" l'altra)
				
				$num_id_new=0;
				$num_class_new=0;
				$num_tag_new=0;
				$valore_proprieta_new=null;
				
				//NOTA: questo switch potrebbe essere incluso in una funzione riutilizzata anche da getElementStyleInline
				switch($val)
				{
									
					case "margin-top":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["margin"]))
							$valore_proprieta_new=VamolaBasicChecks::getTop(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["margin"]));
					break;
					
					case "margin-bottom":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["margin"]))
							$valore_proprieta_new=VamolaBasicChecks::getBottom(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["margin"]));
					break;
					
					case "margin-left":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["margin"]))
							$valore_proprieta_new=VamolaBasicChecks::getLeft(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["margin"]));
					break;
					
					case "margin-right":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["margin"]))
							$valore_proprieta_new=VamolaBasicChecks::getRight(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["margin"]));
					break;
					
					case "padding-top":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["padding"]))
							$valore_proprieta_new=VamolaBasicChecks::getTop(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["padding"]));
					break;
					
					case "padding-bottom":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["padding"]))
							$valore_proprieta_new=VamolaBasicChecks::getBottom(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["padding"]));
					break;
					
					case "padding-left":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["padding"]))
							$valore_proprieta_new=VamolaBasicChecks::getLeft(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["padding"]));
					break;
					
					case "padding-right":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["padding"]))
							$valore_proprieta_new=VamolaBasicChecks::getRight(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["padding"]));
					break;
					
					case "background-color":
						if(isset($array_regole["regole"][$val]) || isset($array_regole["regole"]["background"]))
							$valore_proprieta_new=VamolaBasicChecks::getBgColor(VamolaBasicChecks::get_priority_prop($array_regole["regole"][$val],$array_regole["regole"]["background"]));
					break;
						
					default:
						if(isset($array_regole["regole"][$val]))
							$valore_proprieta_new = $array_regole["regole"][$val]["val"];
					break;
					
				}
				
				$ris=null;		
				// se il valore di una proprieta è stato trovato verifico se puo' essere applicata all'elemento considerato	
				if($valore_proprieta_new!=null)
				{
					
						if(stripos($elem_radice,"#")!==false)
								$num_id_new=1;
							elseif(stripos($elem_radice,".")!==false)
								$num_class_new=1;
							else
								$num_tag_new=1;
					
						
						if (sizeof($array_regole["prev"])==1)  //la regola corrente e' "semplice", non ci sono predecessori
						{			
							$ris=true;
							
								
						}
						else  //la regola e' "composta" (es: div > p a)
						{
							
							//la verifica tiene conto che una regola composta ha priorità su una "semplice", anche se la semplice è successiva!
							//es: "div > p{}" & "p{}" => per <div><p></p></div> vince "div > p{}"
							//controllo se l'elemento rientra nella regola "composta"
							//se si, verifico se in [$regola]["regole"] è contenuta la proprietà $val
							$i=1;//inizio dal primo padre dell'elemento corrente
							$e=$e_original;
														
							while($i<sizeof($array_regole["prev"]) && $ris!==false)
							{
									
								
									//NOTA: questa serie di if/elseif e lo switch successivo potrebbero
									//essere unificati in un unica serie di if/else
									//$elemento puo' contenere '>', '+', un id, una classe un tag
									if($array_regole["prev"][$i] == ">")
									{
										$tipo=">";
									}
									elseif($array_regole["prev"][$i] == "+")
									{
										$tipo="+";
							
									}
									elseif($array_regole["prev"][$i] == $spazio)
									{
										$tipo="spazio";
									}
									elseif(stripos($array_regole["prev"][$i],".")!==false)//classe
									{
										$tipo="class";
																				
									}
									elseif(stripos($array_regole["prev"][$i],"#")!==false)//id
									{
										$tipo="id";
									}
									else //tag
									{
										$tipo="tag";
									
																			
									}
									
											switch($tipo)
											{
												case ">":
															//casi div > p, #id > p, .class > p
															if(stripos($array_regole["prev"][$i+1],"#")!==false)
															{			
																		$e=$e->parent();
																		//id: controllo che il predecessore abbia l'id della regola
																																		
																		if($e  != null && $e ->id == str_replace('#','',$array_regole["prev"][$i+1]))
																		{
																			$ris=true;
																			$num_id_new++;
																		}	
																		else
																			$ris=false;
															}
															elseif(stripos($array_regole["prev"][$i+1],".")!==false)
															{
																		$e=$e->parent();
																		//class: controllo che il predecessore abbia la class della regola
																		if($e  != null && $e ->class == str_replace('.','',$array_regole["prev"][$i+1]))
																		{
																			$ris=true;
																			$num_class_new++;
																		}
																		else
																			$ris=false;
															}
															else 
															{			
																		$e=$e->parent();
																		//tag: controllo che il predecessore sia il tag della regola
																		if($e  != null && $e ->tag == $array_regole["prev"][$i+1])
																		{
																			$ris=true;
																			$num_tag_new++;
																		}
																		else
																			$ris=false;
															}
															$i++;
												break;
												
												case "+":
															if(stripos($array_regole["prev"][$i+1],"#")!==false)
															{
																		$e->prev_sibling();
																		//id: controllo che il predecessore abbia l'id della regola
																		if($e != null && $e->id == str_replace('#','',$array_regole["prev"][$i+1]))
																		{
																			$ris=true;
																			$num_id_new++;
																		}
																		else
																			$ris=false;
															}
															elseif(stripos($array_regole["prev"][$i+1],".")!==false)
															{
																		$e->prev_sibling();
																		//class: controllo che il predecessore abbia la class della regola
																		if($e != null && $e->class == str_replace('.','',$array_regole["prev"][$i+1]))
																		{
																			$ris=true;
																			$num_class_new++;
																		}
																		else
																			$ris=false;
															}
															else 
															{
																		$e->prev_sibling();
																		//tag: controllo che il predecessore sia il tag della regola
																		if($e != null && $e->tag == $array_regole["prev"][$i+1])
																		{
																			$ris=true;
																			$num_tag_new++;
																		}
																		else
																			$ris=false;
															}
															$i++;	
												break;
												
												case "spazio":
															//casi: div #id, #id #id, .class #id, div .class, #id .class, .class .class
															if(stripos($array_regole["prev"][$i+1],"#")!==false)
															{			
																	
																		$e=$e->parent();
																		while($e  != null && $e->id != str_replace('#','',$array_regole["prev"][$i+1]))
																			$e=$e->parent();
																		
																		//id: controllo che il predecessore abbia l'id della regola
																		if($e != null && $e->id == str_replace('#','',$array_regole["prev"][$i+1]))
																		{
																			$ris=true;
																			$num_id_new++;
																		}
																		else
																			$ris=false;
															}
															elseif(stripos($array_regole["prev"][$i+1],".")!==false)
															{
																		$e=$e->parent();
																		while($e != null && $e->class != str_replace('.','',$array_regole["prev"][$i+1]))
																			$e=$e->parent();
																		
																		//class: controllo che il predecessore abbia la class della regola
																		if($e != null && $e->class == str_replace('.','',$array_regole["prev"][$i+1]))
																		{
																			$ris=true;
																			$num_class_new++;
																		}
																		else
																			$ris=false;
															}
															else 
															{	
																		$e=$e->parent();
																		while($e != null && $e->tag != $array_regole["prev"][$i+1])
																			$e=$e->parent();
																		
																		//tag: controllo che il predecessore sia il tag della regola
																		if($e != null && $e->tag == $array_regole["prev"][$i+1])
																		{
																			$ris=true;
																			$num_tag_new++;
																		}
																		else
																			$ris=false;
															}
															$i++;
												break;
												
												
												case "tag":
															//casi: p.classe, p#id, div p
															if(stripos($array_regole["prev"][$i-1],".")!==false)// p.class
															{
																if($e->tag==$array_regole["prev"][$i])
																{
																	$ris=true;
																	$num_tag_new++;
																}	
																else 
																	$ris=false;
																
															}
															elseif(stripos($array_regole["prev"][$i-1],"#")!==false)//p#id
															{
																if($e->tag==$array_regole["prev"][$i])
																{
																	$ris=true;
																	$num_tag_new++;
																}
																else 
																	$ris=false;
															}
															else // div p
															{
																//$e=$e->parent();
																while($e != null && $e->tag != $array_regole["prev"][$i])
																{
																	
																	$e=$e->parent();
																}
																//tag: controllo che il predecessore sia il tag della regola
																if($e != null /*&& $e->tag == $array_regole["prev"][$i]*/)
																{
																	$ris=true;
																	$num_tag_new++;
																}
																else
																	$ris=false;
																
															}
		
															
													
												break;
												
												case "id": 
															//casi: #id p, #id .class, #id #id
															//$e=$e->parent();
															while($e != null && $e->id != str_replace('#','',$array_regole["prev"][$i]))
															{
																
																$e=$e->parent();
															}
														
															//tag: controllo che il predecessore sia il tag della regola
															if($e != null /*&& $e->tag == $array_regole["prev"][$i]*/)
															{
																$ris=true;
																$num_tag_new++;
															}
															else
																$ris=false;
															
															
												break;
												
												case "class":
															//casi: .id p, .id .class, .id #id
															//$e=$e->parent();
															
															while($e != null && $e->class != str_replace('.','',$array_regole["prev"][$i]))
															{	
																$e=$e->parent();
															}
															//tag: controllo che il predecessore sia il tag della regola
															//if($e != null && $e->tag == $array_regole["prev"][$i])
															if($e != null /*&& $e->class == str_replace('.','',$array_regole["prev"][$i])*/)
															{
																$ris=true;
																$num_tag_new++;
																
															}
															else
																$ris=false;
																
															
												break;
												
												
												
											}//end case
									$i++;
								
							}//end while
							
						}//end else regola composta
						
							
							if ($ris== true)
							{  	//la nuova regola analizzata è applicabile
							 	//controllo se la priorita della nuova supera quella della precedente															
								
								if(stripos($valore_proprieta_new,"!important")!==false && stripos($valore_proprieta,"!important")===false)
								{
										//$proprieta non è !important mentre $proprietà_new si, quindi sovrascrivo $proprieta
										$valore_proprieta=$valore_proprieta_new;
										$num_id=$num_id_new;
										$num_class=$num_class_new;
										$num_tag=$num_tag_new;
										$num_regola_best=$num_regola;
								}
								elseif(stripos($valore_proprieta_new,"!important")===false && stripos($valore_proprieta,"!important")===false 
										|| stripos($valore_proprieta_new,"!important")!==false && stripos($valore_proprieta,"!important")!==false) 
										//hanno entrambe !important o non lo hanno nessuna della due, quindi verifico il numeo di id
								{	
											
											if($num_id_new>$num_id)
											{
												$valore_proprieta=$valore_proprieta_new;
												$num_id=$num_id_new;
												$num_class=$num_class_new;
												$num_tag=$num_tag_new;
												$num_regola_best=$num_regola;
											}
											elseif($num_id_new==$num_id)
											{	//stesso numero di id, controllo il numero di class
												
													if($num_class_new>$num_class)
													{
														$valore_proprieta=$valore_proprieta_new;
														$num_id=$num_id_new;
														$num_class=$num_class_new;
														$num_tag=$num_tag_new;
														$num_regola_best=$num_regola;
													}
													elseif($num_class_new==$num_class)
													{	//stesso numero di id e class, controllo in numero di tag
															
														if($num_tag_new>=$num_tag)
														{  //stesso o maggiore numero di id, class e tag: la priorità è della nuova regola
															$valore_proprieta=$valore_proprieta_new;
															$num_id=$num_id_new;
															$num_class=$num_class_new;
															$num_tag=$num_tag_new;
															$num_regola_best=$num_regola;
														}
														
													}
											}
								}
							

							$valore_proprieta_new=null;	
							}
							
				}

			 $num_regola++;
			}
		
		
		if($valore_proprieta==null)
			return null;
		//creo la struttura info_proprieta'
		//memorizzare il numero di id, class e tag è necessario per verificare la priorita'
		//delle regole trovate partendo da un id, una class o un tag
		// non sempre infatti una regola che nei selettori ha come ultimo (o unico) discendente un id o class batte una che
		//termina con un tag
		//es:
		// "#id2 p{}" batte "#id1{}" (se p contiene id="id1")
		
		$info_proprieta=array("valore"=>$valore_proprieta, "num_id"=>$num_id, "num_class"=>$num_class, "num_tag"=>$num_tag, "css_rule"=>$array_subset_selettori[$num_regola_best]);
		//echo("<p>regola per ". $elem_radice."</p>");
		//print_r($array_subset_selettori[$num_regola-1]);
		
		return $info_proprieta;
	}
	
	
	
	
	//riorganizzo i fogli di stile, partendo dagli array di Filippo, in una struttura dati piu' articolata
	public static function setCssSelectors($content_dom){
		
		global $selettori;
		global $attributo_selettore;
		global $selettori_appoggio;
		global $array_css;
		global $flag_selettori_appoggio;
		$array_css=array();
		
		//echo("<p>entro in cssselector</p>");
		
		//echo("<p>isset selettori_appoggio=".isset ($selettori_appoggio)."</p>");
		//print_r($selettori_appoggio);
		
		if(isset ($flag_selettori_appoggio))
		{
			
			//return ($selettori_appoggio);
			return;
		}
		else 
		{
			$flag_selettori_appoggio=true;
		}
		

		$spazio="{_}";
		
		//$selettori_appoggio =array();
		
		
		// parte in prova
		
		$csslist = VamolaBasicChecks::get_style_external($content_dom);
		$cssinternal= VamolaBasicChecks::get_style_internal($content_dom);
		//echo("<p>Stampo la lista di stili</p>");
		//print_r($csslist);
		//print_r($cssinternal);
		//MB creo la struttura dati contenente i dati dei css
		VamolaBasicChecks::prepare_css_arrays($csslist,$cssinternal);
			
		//fine parte in prova
		for ($idcss =0; $idcss < sizeof($selettori); $idcss++)
		{
			
			//$selettori_appoggio[$idcss] =array();
			for($i=0;$i<count($selettori[$idcss]);$i++){
					$sel_string =str_ireplace('{','',$selettori[$idcss][$i]); //rimuovo "{"
					
					$sel_string = str_ireplace('>', ' > ', $sel_string);// metto gli spazi tra i ">"
					$sel_string = str_ireplace('+', ' + ', $sel_string);// metto gli spazi tra i "+"
					
					//uso il simbolo $spazio e per indicare che un id o una classe e' preceduta da uno spazio
					//infatti "p.nome_classe" è diverso da "p .nome_classe"
					$sel_string = str_ireplace(' .', ' '.$spazio.'.', $sel_string);// metto uno $spazio prima di "."
					$sel_string = str_ireplace(' #', ' '.$spazio.'#', $sel_string);// metto uno $spazio prima di "#"
					
					$sel_string = str_ireplace('.', ' .', $sel_string);// metto uno spazio prima di "."
					$sel_string = str_ireplace('#', ' #', $sel_string);// metto uno spazio prima di "#"
					//echo ("<p>1 sel_string =".$sel_string."</p>");
					while (stripos($sel_string,'  ')!==false) //rimuovo gli spazzi multipli
					{
						$sel_string = str_ireplace('  ', ' ', $sel_string);
						//echo ("<p>sel_string =".$sel_string."</p>");
					}
					
					//rimuovo i {_} ridondanti
					$sel_string = str_ireplace('> {_}', '>', $sel_string);
					
					
					$selettori_array = split (',', $sel_string); //creo un array dei selettori che sono separati da ","
					foreach ($selettori_array as $sel)
					{		
							$sel=trim($sel);
							//rimuovo eventuali $spazio all'inizio della stringa
							$sel= preg_replace("/^".$spazio."/","",$sel);
							//rimuovo eventuali $spazio alla fine della stringa
							$sel= preg_replace("/".$spazio."$/","",$sel);
							$sel=trim($sel);
							$selettore_array = split(" ",$sel);
							//nell'ultima posizione di $selettore_array c'è il selettore piu' a dx prima di una "," o di "{"
							$last=$selettore_array[sizeof($selettore_array) -1]; //ultimo elemento a dx, es: "div > p br" ---> br 
							
							$array_appoggio = array();
							$array_appoggio["idcss"]=$idcss;
							$array_appoggio["posizione"]=$i;
							//"regole" contiene: $prorieta =>valore
							$regole=$attributo_selettore[$idcss][$i];
						
							if(sizeof($regole)>0)
							{
								$pos_prop=0;
								foreach ($regole as $regola)
								{
										
									//print_r($array_appoggio);							
									$regola = trim($regola);
									$regola = split(":", $regola);
									if(sizeof($regola==2))
									{
										$proprieta=trim($regola[0]);
										$valore=trim($regola[1]);
										
										if(!isset ($array_appoggio["regole"][$proprieta]))
										{
											$array_appoggio["regole"][$proprieta]["val"]=$valore;
											$array_appoggio["regole"][$proprieta]["pos"]=$pos_prop;
										}
										elseif(stripos($array_appoggio["regole"][$proprieta]["val"],"!important")!==false ) //la proprietà è già stata impostata ed è !important, la posso sovrascrivere solo se anche quella che sto analizzando è !important
										{
											if(stripos($valore,"!important")!==false)	
												$array_appoggio["regole"][$proprieta]["val"] = $valore;
												$array_appoggio["regole"][$proprieta]["pos"]=$pos_prop;
										}
										else
										{
											$array_appoggio["regole"][$proprieta]["val"] = $valore;
											$array_appoggio["regole"][$proprieta]["pos"]=$pos_prop;
										}
									
									}
									$pos_prop++;
									
								}
							}

							//memorizzo i "predecessori". es: il selettore =" div > p br", allora i predecessori di br (considero anche br stesso) sono br, p, > e div. li memorizzo da dx a sx
							//if(sizeof($selettore_array)==1) //il selettore è formato da un solo elemento
							
								for($j=sizeof($selettore_array)-1,$k=0; $j>=0; $j--,$k++)
								{
									$array_appoggio["prev"][$k] = $selettore_array[$j]; 
								}
							
					
							
							//if(isset($selettori_appoggio[$idcss][$last])) //ho già inserito questo elemento (tag, id, class) almeno una volta
							if(isset($selettori_appoggio[$last])) //ho gia' inserito questo elemento (tag, id, class) almeno una volta 
							{
								//$posizione = sizeof($selettori_appoggio[$idcss][$last]);
								$posizione = sizeof($selettori_appoggio[$last]);
								$selettori_appoggio[$last][$posizione]=$array_appoggio;
							}
							else 
							{
									$selettori_appoggio[$last][0]=$array_appoggio;
							}
					}
				
			}
			
		}
			

				//echo("<p>Selettori appoggio</p>");
				//print_r($selettori_appoggio);
				//echo(sizeof($selettori_appoggio));
				/*
				foreach($selettori_appoggio as $i => $s)
				{
					
						echo("<p>__".$i."</p>");
						foreach($s as $j => $a)
						{	
							echo("<p>____".$j."</p>");
	
							print_r($a);
							echo("<p></p>");
						}
						echo("<p></p>");
					
				}
				*/
	}

	
	
	
	//Funzione che ricerca un determinato attributo all'interno dell'id associato ad un tag
	public static function GetElementStyleId($e,$id,$val){
		
		return VamolaBasicChecks::getElementStyleGeneric($e,'#',$id,$val);
	}	

	//Funzione che ricerca un determinato attributo all'interno della class associata ad un tag, in un foglio di stile esterno
	public static function GetElementStyleClass($e,$class,$val){
			
		return VamolaBasicChecks::getElementStyleGeneric($e,'.',$class,$val);
	}	
	
	
	
	//Funzione che ricerca un determinato attributo all'interno di un selettore identificato con il tag in un foglio di stile esterno
	public static function GetElementStyle($e,$child,$val){
		//return VamolaBasicChecks::getElementStyleGeneric($e,'',$child,$val,$idcss);
		return VamolaBasicChecks::getElementStyleGeneric($e,'',$child,$val);
	}	
	
		

	//Funzione per il requsitio 21 che recupera i valori di distanza veticali di un li
	public static function GetVerticalDistance($e){
	
			global $m_bottom;
			global $p_bottom;
			global $m_top;
			global $p_top;
			
			$m_bottom ="";
			$p_bottom ="";
			$m_top ="";
			$p_top ="";	
			
			
			$m_bottom = VamolaBasicChecks::get_p_css($e->prev_sibling(),"margin-bottom");
			$p_bottom = VamolaBasicChecks::get_p_css($e->prev_sibling(),"padding-bottom");
			$m_top = VamolaBasicChecks::get_p_css($e,"margin-top");
			$p_top =VamolaBasicChecks::get_p_css($e,"padding-top");	
			
			$m_bottom=trim(str_ireplace("!important","",$m_bottom));
			$p_bottom=trim(str_ireplace("!important","",$p_bottom));
			$m_top=trim(str_ireplace("!important","",$m_top));
			$p_top=trim(str_ireplace("!important","",$p_top));
	
	}	
	
	

	
	//Funzione per il requsitio 21 che recupera i valori di distanza orizzontali di un li
	public static function GetHorizontalDistance($e){
	
			global $m_left;
			global $p_left;
			global $m_right;
			global $p_right;
			
			$m_left ="";
			$p_left ="";
			$m_right="";
			$p_right="";
			
			$m_right = VamolaBasicChecks::get_p_css($e->prev_sibling(),"margin-right");
			$p_right =VamolaBasicChecks::get_p_css($e->prev_sibling(),"padding-right");
			$m_left = VamolaBasicChecks::get_p_css($e->prev_sibling(),"margin-left");
			$p_left = VamolaBasicChecks::get_p_css($e->prev_sibling(),"padding-left");
			
			$m_right=trim(str_ireplace("!important","",$m_right));
			$p_right=trim(str_ireplace("!important","",$p_right));
			$m_left=trim(str_ireplace("!important","",$m_left));
			$p_left=trim(str_ireplace("!important","",$p_left));	
	
	}
	
	


	//Funzione per il requsitio 21 che recupera i valori di distanza veticali basso delle liste
	public static function GetVerticalListBottomDistance($tag){
	
			global $m_bottom;
			global $p_bottom;
			$m_bottom ="";
			$p_bottom ="";
			
			$m_bottom = VamolaBasicChecks::get_p_css($tag,"margin-bottom");
			$p_bottom = VamolaBasicChecks::get_p_css($tag,"padding-bottom");
			$m_bottom=trim(str_ireplace("!important","",$m_bottom));
			$p_bottom=trim(str_ireplace("!important","",$p_bottom));
			
	}
	
	//Funzione per il requsitio 21 che recupera i valori di distanza veticali alto delle liste
	public static function GetVerticalListTopDistance($tag){
	
			global $m_top;
			global $p_top;
			$m_top ="";
			$p_top ="";
			$m_top = VamolaBasicChecks::get_p_css($tag,"margin-top");
			$p_top =VamolaBasicChecks::get_p_css($tag,"padding-top");
			$m_top=trim(str_ireplace("!important","",$m_top));
			$p_top=trim(str_ireplace("!important","",$p_top));	
	
	}


	//Funzione per il requsitio 21 che recupera i valori di distanza orizzontale sinistra delle liste
	public static function GetHorizontalListLeftDistance($tag){
	
			global $m_left;
			global $p_left;
			$m_left ="";
			$p_left ="";
			$m_left = VamolaBasicChecks::get_p_css($tag,"margin-left");
			$p_left = VamolaBasicChecks::get_p_css($tag,"padding-left");
			$m_left=trim(str_ireplace("!important","",$m_left));
			$p_left=trim(str_ireplace("!important","",$p_left));	
	}

	//Funzione per il requsitio 21 che recupera i valori di distanza orizzontale destra delle liste
	public static function GetHorizontalListRightDistance($tag){
	
			global $m_right;
			global $p_right;
			$m_right ="";
			$p_right ="";
			$m_right = VamolaBasicChecks::get_p_css($tag,"margin-right");
			$p_right = VamolaBasicChecks::get_p_css($tag,"padding-right");
			$m_right=trim(str_ireplace("!important","",$m_right));
			$p_right=trim(str_ireplace("!important","",$p_right));
	}

	
	public static function getForegroundA($e, $link_sel){
		
		//cerco il valore di foreground esplicitamente definito per l'elemento link $e 
		$foreground=VamolaBasicChecks::get_p_css_a($e, "color", $link_sel);

		return $foreground;
		

	}	
	
	
	public static function getBackgroundA($e, $link_sel){
		
		//cerco il valore di background esplicitamente definito per l'elemento $e
		$background=VamolaBasicChecks::get_p_css_a($e, "background-color", $link_sel);
		return $background;

	}	
	
	
	public static function getForeground($e){
		
		//cerco il valore di foreground esplicitamente definito per l'elemento $e
		$foreground=VamolaBasicChecks::get_p_css($e, "color");
		
		//i link non ereditano "color" definito in style
		if ($foreground=="" && $e->tag=="a")
			return $foreground;
		
		//per gli elementi normali se foreground == "" significa che il valore non è stato definito per $e: ricerco tra i suoi genitori
		while(($foreground=="" || $foreground==null)  && $e->tag!=null && $e->tag!="body")
		{
				$e=$e->parent();
				$foreground=VamolaBasicChecks::get_p_css($e, "color");
		}
		
		//se non trovo nessun foreground, controllo se è definito nel body, se no gli assegno il nero
		//NOTA: va aggiunto il controllo su link, alink, ...
		if($foreground=="" || $foreground==null)
		{
			if($e->tag=="body" && isset($e->attr["text"]))
				$foreground = $e->attr["text"];
			else
				$foreground="#000000";	
		}
		return $foreground;
		

	}
	
	
	
	public static function getBackground($e){
		
		//cerco il valore di background esplicitamente definito per l'elemento $e
		$background=VamolaBasicChecks::get_p_css($e, "background-color");
		
		
		
		//se background == "" significa che il valore non è stato definito per $e: ricerco tra i suoi genitori
		while(($background=="" || $background==null) && $e->tag!=null && $e->tag!="body")
		{
				$e=$e->parent();
				$background=VamolaBasicChecks::get_p_css($e, "background-color");
				if($background=="" || $background==null)//controllo se c'è bgcolor che ha priorità inferiore dello stile
				{
					if(($e->tag=="table" || $e->tag=="tr" || $e->tag=="td") && isset($e->attr["bgcolor"]))
						$background = $e->attr["bgcolor"];
				}
				
		}
		
		
		//se non trovo nessun background controllo che sia definito nel body, se no gli assegno il bianco
		if($background=="" || $background==null || $background=="transparent" )
		{
			
			if($e->tag=="body" && isset($e->attr["bgcolor"]))
				$background = $e->attr["bgcolor"];
			else
				$background="#ffffff";
		}
		return $background;

	}	
	
	
	
	
	//Restituisce true se la misura di $value è relativa
	public static function isRelative($value){
				
		$value=trim(str_ireplace("!important","",$value));
		
		$a_value=preg_split('/ /', $value);
		//print_r($a_value);
		
		foreach($a_value as $value)
		{
			if($value=="auto" || $value==' ' || $value==0)
					;//ok
			elseif((substr($value,strlen($value)-2,2)!="em") && (substr($value,strlen($value)-1,1)!="%") && (substr($value,strlen($value)-2,2)!="px") )
				return false;
			//else 
			//	return true;
		}
		return true;
	}
	
	//check per verificare se la misura della proprieta' $val associata all'elemento $e e' relativa
	public static function checkRelative($e, $val){
		
		$fs= VamolaBasicChecks::get_p_css($e,$val);
		if($fs!="" && $fs!=null){
		
			return	VamolaBasicChecks::isRelative($fs);
		}
		else 
			return true;
	}
	
	
	
	//Restituisce true se la misura di $value è in px
	public static function isPx($value){

		$value=trim(str_ireplace("!important","",$value));

		$a_value=preg_split('/ /', $value);
		
		$ret=false;
		foreach($a_value as $value)
		{
			if(substr($value,strlen($value)-2,2)=="px")
				$ret =true;
			//else
			//	return false;
		}
		return $ret;
	}
	
	//check per verificare la presenza di px nella proprieta' $val relativa all'elemento $e
	public static function checkPx($e, $val){
		
		$fs= VamolaBasicChecks::get_p_css($e,$val);
		if($fs!="" && $fs!=null){
		
			return !VamolaBasicChecks::isPx($fs);
		}
		else 
			return true;
	}	
	
	

	//MODIFICA FILO SPETTAZA DALL'ORIGINALE
	//FUNZIONE PER CALCOLARE IL RAPPORTO DI BRILLANTEZZA
	public static function CalculateBrightness($color1, $color2)
	{
	
		include_once (AC_INCLUDE_PATH . "classes/ColorValue.class.php");

		//echo("<p>CalcolateBrightness</p>");
		//echo("<p>Colori prima di ColorValue: color1=".$color1.  "color2=".$color2. "</p>");
		
		$color1 = new ColorValue($color1);
		$color2 = new ColorValue($color2);
		
		//echo("<p>Colori dopo ColorValue: color1=".$color1.  "color2=".$color2. "</p>");
		
		if (!$color1->isValid() || !$color2->isValid())
			return true;
		
		$colorR1 = $color1->getRed();
		$colorG1 = $color1->getGreen();
		$colorB1 = $color1->getBlue();
		
		$colorR2 = $color2->getRed();
		$colorG2 = $color2->getGreen();
		$colorB2 = $color2->getBlue();

		$brightness1 = (($colorR1 * 299) + 
							($colorG1 * 587) + 
							($colorB1 * 114)) / 1000;

		$brightness2 = (($colorR2 * 299) + 
							($colorG2 * 587) + 
							($colorB2 * 114)) / 1000;

		$difference = 0;
		if ($brightness1 > $brightness2)
		{
			$difference = $brightness1 - $brightness2;
		}
		else 
		{
			$difference = $brightness2 - $brightness1;
		}

		return $difference;
	}
	//MODIFICA FILO SPEZZATA DALL'ORIGINALE
	//FUNZIONE PER IL CALCOLLO DELLA DIFFERENZA DI CONTRASTO DI COLORE
	public static function CalculateColorDifference($color1, $color2)
	{
		include_once (AC_INCLUDE_PATH . "classes/ColorValue.class.php");

		//echo("<p>CalcolateColorDifference</p>");
		//echo("<p>Colori prima di ColorValue: color1=".$color1.  "color2=".$color2. "</p>");
		
		$color1 = new ColorValue($color1);
		$color2 = new ColorValue($color2);
		
		//echo("<p>Colori dopo ColorValue: color1=".$color1.  "color2=".$color2. "</p>");
		
		if (!$color1->isValid() || !$color2->isValid())
			return true;
		
		$colorR1 = $color1->getRed();
		$colorG1 = $color1->getGreen();
		$colorB1 = $color1->getBlue();
		
		$colorR2 = $color2->getRed();
		$colorG2 = $color2->getGreen();
		$colorB2 = $color2->getBlue();
		
		//echo("<p>Colori dopo ColorValue: colorR1=".$colorR1.  "colorR2=".$colorR2. "</p>");
		//echo("<p>Colori dopo ColorValue: colorG1=".$colorG1.  "colorG2=".$colorG2. "</p>");
		//echo("<p>Colori dopo ColorValue: colorB1=".$colorB1.  "colorB2=".$colorB2. "</p>");
		// calculate the color difference
		$difference = 0;
		// red
		if ($colorR1 > $colorR2)
		{
			$difference = $colorR1 - $colorR2;
			  
		}
		else
		{
			$difference = $colorR2 - $colorR1;
			
		}
		
		// green
		if ($colorG1 > $colorG2)
		{
			$difference += $colorG1 - $colorG2;
		}
		else
		{
			$difference += $colorG2 - $colorG1;
		}
		
		// blue
		if ($colorB1 > $colorB2)
		{
			$difference += $colorB1 - $colorB2;
		}
		else
		{
			$difference += $colorB2 - $colorB1;
		}
		
		//echo("<p>difference=".$difference."</p>");
		return $difference;
	}
	
	//MODIFICA FILO: Funzione per la conversione del colore
	public static function convert_color_to_hex($f_color) {
    /* Se il colore è indicato in esadecimale lo restituisco così com'è */
   		$a=strpos($f_color,"#");
		
	//MBif($a!=0){
	if($a!== false){
			$f_color=substr($f_color,$a+1);
        	return $f_color;
   	}
    /* Se è in formato RGB lo converto in esadecimale poi lo restituisco */
    elseif (eregi('rgb',$f_color)) {
        if (eregi('\(([^,]+),',$f_color,$red)) {$red=dechex($red[1]); }
        if (eregi(',([^,]+),',$f_color,$green)) {$green=dechex($green[1]); }
        if (eregi(',([^\)]+)\)',$f_color,$blue)) {$blue=dechex($blue[1]); }
        $f_color=$red.$green.$blue;
        return $f_color;
    }
    /* La stessa cosa faccio se è indicato con il proprio nome */
    else {
        switch ($f_color) {
        	
            case 'black':       return '000000';
            case 'silver':      return 'c0c0c0';
            case 'gray':        return '808080';
            case 'white':       return 'ffffff';
            case 'maroon':      return '800000';
            case 'red':         return 'ff0000';
            case 'purple':      return '800080';
            case 'fuchsia':     return 'ff00ff';
            case 'green':       return '008800';
            case 'lime':        return '00ff00';
            case 'olive':       return '808000';
            case 'yellow':      return 'ffff00';
            case 'navy':        return '000080';
            case 'blue':        return '0000ff';
            case 'teal':        return '008080';
            case 'aqua':        return '00ffff';
            case 'gold':        return 'ffd700';
            case 'navy';        return '000080';
        }
    }
	}
	
	//prende in input un elemento e restituisce la relativa table
	public static function getTable($e)
	{
				
				while($e->parent()->tag!="table" && $e->parent()->tag!=null)
					$e=$e->parent();
				
				if($e->parent()->tag=="html")
					return null;
				else
					return $e->parent();	
				
	}
	
	//prende un array di id (attributo headers di un elemento td) e verifica che ogni id sia associato a un th
	public static function checkIdInTable($t,$ids)
	{
			
			$th=$t->find("th");
			$num=0;
			
				for($i=0; $i<sizeof($ids); $i++)
				{
					for($j=0; $j<sizeof($th); $j++)
					{	
						
						
						if(isset($th[$j]->attr['id']) && $th[$j]->attr['id']==$ids[$i])
						{	
							
							$num++;
							break;
						}
					
					}
				}
				
				
				if($num==sizeof($ids)) //ho trovato un id in un th per ogni id di un td
					return true;
				else 
					return false;
	}
	
	//verifica l'esistenza di un'intestazione di riga per un elemento td
	public static function getRowHeader($e)
	{
		
		while($e->prev_sibling()!=null && $e->prev_sibling()->tag!="th")
		{	
	
			$e=$e->prev_sibling();
		}
		
		if($e->prev_sibling()==null)
			return null;
		else
			
			return $e->prev_sibling();
			/*
			if(isset($e->attr["scope"]) && $e->attr["scope"]=="row")
				return $e;
			else
				return null;
			*/	
				
	}
	//verifica l'esistenza di un'intestazione di colonna per un elemento td
	public static function getColHeader($e)
	{
		
		
		$pos=0;
		$e_count=$e;
		//trovo la posizione nella riga di td
		while($e_count->prev_sibling()!=null )
		{
			$pos++;
			$e_count =$e_count->prev_sibling();
		}
		
			
		$t=VamolaBasicChecks::getTable($e);
		//non c'è il tag <table>
		if($t==null)
		{
			return true; //tabella mal composta
		}
		
		$tr=$t->find("tr");
		
		
		if($tr==null || sizeof($tr)==0)
			return true; //tabella mal composta
		
		for($i=0; $i<sizeof($tr)-1;$i++)
		{	
			$th_next=$tr[$i+1]->find("th");
			if($th_next==null || sizeof($th_next)==0)
			break; //l'i-esima tr contiene l'intestazione più interna
		}
		
		
		$h=$tr[$i]->childNodes();
		//verifico che la casella in posizione $pos della presunta riga di intestazione sia effettivamente un'intestazione 
		if(isset($h[$pos]) && $h[$pos]->tag=="th" /*&& isset($h[$pos]->attr["scope"]) && $h[$pos]->attr["scope"]=="col"*/)
			return $h[$pos];
		else 
			return null;
		
		
	
	}
	
	public static function rec_check_15005($e)
	{
		if($e->tag=='script' || $e->tag=='object' || $e->tag=='applet' || isset($e->attr['onload']) || isset($e->attr['onunload']) || isset($e->attr['onclick']) || isset($e->attr['ondblclick'])
		   || isset($e->attr['onmousedown'])|| isset($e->attr['onmouseup'])|| isset($e->attr['onmouseover']) || isset($e->attr['onmousemove'])|| isset($e->attr['onmouse'])|| isset($e->attr['onblur'])
		   || isset($e->attr['onkeypress'])|| isset($e->attr['onkeydown'])|| isset($e->attr['onkeyup'])|| isset($e->attr['onsubmit'])|| isset($e->attr['onreset'])|| isset($e->attr['onselect'])
		   || isset($e->attr['onchange']))
		return false;
		
		else 
		$c= $e->children();
		$res=true; 
		foreach ($c as $elem )
		{
			$res=VamolaBasicChecks::rec_check_15005($elem);
			if($res==false)
				return $res;
		}
		return $res;
		


	}
	
// funzioni per i css
	
	/* ritorna la lista degli stili esterni presenti nella pagina */
	  public static function get_style_external($content_dom){
		
		global $csslist;
		
		//MB
		
		//$dom=str_get_dom($content);
		$dom=$content_dom;
		$vettore_link=$dom->find('link');
		//$vettore_link=array_reverse($vettore_link);
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
			
			
			$i=0;
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
	  public static function get_style_internal($content_dom){
		
				
		//MB
		
		//$dom=str_get_dom($content);
		$dom=$content_dom;
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
					$import = substr($cssint, 7, stripos($cssint,";") +1  );
					
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
					//echo($indirizzo);
				}
			}
		}
		return $cssint;
		
	}


	
   //La funzione crea l'array degli stili (interni ed esterni) da sottoporre alla validazione.
    
  public static function prepare_css_arrays($array_css_esterni,$ci){
  	
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
  
  
  //restituisce il codice css che ha provocato un errore, relativamente all'ultimo check sui css che è stato eseguito
  //viene richiamata in AccessibilityValidator dopo l'esecuizione di ogni check
  //restituisce $css_code che, nel caso in cui il check non abbia riscontrato errori su un css interno/esterno o
  //non sia un check sui css, viene impostata a ""
  public static function getCssOutput()
  {

			//MB:per stampare le regole dei check sui CSS														
			//regole css relative all'errore
			global $csslist;			
			global $array_css;
			
			$spazio="{_}";
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
			$array_css=array();
			return $css_code;		
			//MB: per i check sui CSS (fine)

  }
		
	
	
}
?>