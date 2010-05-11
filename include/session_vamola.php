<?php 
// Le variabili di sessione sono da sistemare, alcune sono inutili

if ((isset($_REQUEST["validate_uri"]) || isset($_REQUEST["validate_file"])) ||  !isset($_GET["tab_ris"]))
{
	unset($_SESSION["validate_file"]);
	unset($_SESSION["validate_uri"]);
	unset($_SESSION["uploadfile"]);
	unset($_SESSION["uri"]);
	unset($_SESSION["risultati"]);
	unset($_SESSION["tab_ris"]);
}


if(!isset($_REQUEST["validate_uri"]) &&  !isset($_REQUEST["validate_file"]) && !isset($_GET["tab_ris"]) && !isset($_REQUEST["all_checks"]) && !isset($_REQUEST["no_checks"]))
{
	unset($_SESSION["flag_gid"]);
}


// NOTA DI SIMO: Unire parte sopra e sotto
//MB
if(isset($_REQUEST["validate_uri"]) ||  isset($_REQUEST["validate_file"]) || (!isset($_GET["tab_ris"]) && !isset($_REQUEST["all_checks"]) && !isset($_REQUEST["no_checks"])))//ho premuto uno dei tasti "valida"
{
	unset($_SESSION["validate_file"]);
	unset($_SESSION["validate_uri"]);
	unset($_SESSION["uploadfile"]);
	unset($_SESSION["uri"]);
	//unset($_SESSION["risultati"]);
	//unset($_SESSION["tab_ris"]);
	unset($_SESSION["show"]);	//per gestire output wcag
	unset($_SESSION["show_nav"]); //per gestire output wcag
	
	unset($_SESSION["risultati"]);//cancello i vecchi risultati
	unset($_SESSION["tab_ris"]); //cancello il tab
	unset($_SESSION["visual_img"]);// per il controllo visivo sull'alternativa visuale delle immagini
	unset($_SESSION["new_web_site"]);
	unset($_SESSION["css_disable"]);
	unset($_SESSION["disable"]);
	unset($_SESSION["enable_html_validation"]);
	unset($_SESSION["enable_css_validation"]);
	unset($_SESSION["req"]);
	//$_SESSION["new_web_site"]=1;
	$_SESSION["req"][0]="0";
	
}

if(!isset($_REQUEST["validate_uri"]) &&  !isset($_REQUEST["validate_file"]) && !isset($_GET["tab_ris"]) && !isset($_REQUEST["all_checks"]) && !isset($_REQUEST["no_checks"]))//ricarico la pagina
{
	$_REQUEST["new_web_site"]=1;
}



if (isset($_REQUEST["validate_file"]))
	$_SESSION["validate_file"] = $_REQUEST["validate_file"];

if (isset($_REQUEST["validate_uri"]))
	$_SESSION["validate_uri"] = $_REQUEST["validate_uri"];

if (isset($_FILES["uploadfile"]))
	$_SESSION["uploadfile"] = $_FILES["uploadfile"];
	
if (isset($_REQUEST["uri"]))
	$_SESSION["uri"] = $_REQUEST["uri"];
	
if (isset($_REQUEST["gid"]))
	$_SESSION["gid"] = $_REQUEST["gid"];
else 
	$_SESSION["gid"] = array();
	
if (isset($_REQUEST["tab_ris"]))
	$_SESSION["tab_ris"] = $_REQUEST["tab_ris"];


	
//abilito i validatori html e css se il req 1 � selezionato
if($_REQUEST["req"][0]=="100" && (isset($_REQUEST["validate_uri"]) ||  isset($_REQUEST["validate_file"])))
{
	$_REQUEST["enable_html_validation"]=1;
	$_REQUEST["enable_css_validation"]=1;
	$_SESSION["enable_html_validation"]=1;
	$_SESSION["enable_css_validation"]=1;
}	




//opzioni selezionate di default


if (!isset($_SESSION["flag_gid"])) 
{
	$_SESSION["gid"][]=10;
	$_SESSION["flag_gid"]=1;
}




if(!isset($_SESSION["new_web_site"]))
	$_SESSION["new_web_site"]=1;

if(!isset($_SESSION["enable_html_validation"]))
	$_SESSION["enable_html_validation"]=0;	

if(!isset($_SESSION["enable_css_validation"]))
	$_SESSION["enable_css_validation"]=0;


if(isset($_REQUEST['gid']) && in_array(7, $_REQUEST["gid"]) && !in_array(7, $_SESSION["gid"]))
	$_SESSION["gid"][]= 7;


if(isset($_REQUEST['gid']) && in_array(8, $_REQUEST["gid"]) && !in_array(8, $_SESSION["gid"]))
	$_SESSION["gid"][]= 8;		
	

if(isset($_REQUEST['gid']) && in_array(9, $_REQUEST["gid"]) && !in_array(9, $_SESSION["gid"]))
	$_SESSION["gid"][]= 9;

if(isset($_REQUEST['gid']) && in_array(10, $_REQUEST["gid"]) && !in_array(10, $_SESSION["gid"]))
	$_SESSION["gid"][]= 10;		
	
	
if(isset($_REQUEST["new_web_site"]))
	$_SESSION["new_web_site"]=1;
elseif (!isset($_GET["tab_ris"]))
	$_SESSION["new_web_site"]=0;
	
if(isset($_REQUEST["css_disable"]))
	$_SESSION["css_disable"]=1;
elseif (!isset($_GET["tab_ris"]))
	$_SESSION["css_disable"]=0;	
	
if(isset($_REQUEST["enable_html_validation"]))
	$_SESSION["enable_html_validation"]=1;
elseif (!isset($_GET["tab_ris"]))
	$_SESSION["enable_html_validation"]=0;

if(isset($_REQUEST["enable_css_validation"]))
	$_SESSION["enable_css_validation"]=1;
elseif (!isset($_GET["tab_ris"]))
	$_SESSION["enable_css_validation"]=0;	


if(isset($_GET["tab_ris"]))
	$_SESSION["tab_ris"]=$_GET["tab_ris"];
	
if(!isset($_SESSION["tab_ris"]))
	$_SESSION["tab_ris"]=1;
	
if(isset($_REQUEST["req"][0]))
	$_SESSION["req"]=$_REQUEST["req"];

if(isset($_REQUEST["visual_img"]))
	$_SESSION["visual_img"]=1;
else
	$_SESSION["visual_img"]=0;	
	
	
if(isset($_REQUEST["all_checks"]))
{
	for($i=0;$i<22;$i++)
		$_SESSION["req"][$i]=100+$i;
	//MB se seleziono tutti i requisiti, attivo anche il checkbox "Legge Stanca"
	if(!in_array(10, $_SESSION["gid"]))
		$_SESSION["gid"][]=10;	
	
}

if(isset($_REQUEST["no_checks"])){
	
	unset($_SESSION["req"]);
	unset($_REQUEST["req"]);
	$_SESSION["req"][0]="0";
	$_REQUEST["req"][0]="0";
	if(isset($_SESSION['gid']) && in_array(10, $_SESSION["gid"]))
	{
		//MB se deseleziono tutti i requisiti, disattivo anche il checkbox "Legge Stanca"
		unset($_SESSION['gid'][array_search(10,$_SESSION['gid'])]);
		
	}
}	

//MB se non � selezionato il checkbox "Legge Stanca" elimino gli eventuali check memorizzati nella sessione (in modo che non vengano eseguiti)
if(isset($_SESSION['gid']) && !in_array(10, $_SESSION["gid"]))
	$_SESSION["req"]= array();

	
	
// Simo aggiunta Wcag
if(!isset($_SESSION["show"]) && !isset($_GET["show"]))
{
	$_SESSION["show"]="stanca";
}
else if(isset($_GET["show"]))
	$_SESSION["show"]=$_GET["show"];
	

/*	
if (isset($_SESSION["validate_file"]) && isset($_SESSION["uploadfile"]))
{
	if (is_valid_filename($_SESSION['uploadfile']))
	{
		//echo "file caricato valido";
	}
	else echo "file caricato non valido";
}

if (isset($_SESSION["validate_uri"]) && isset($_SESSION["uri"]))
{
	if (is_valid_uri($_SESSION['uri']))
	{
		//echo "uri valido";
	}
	else echo "uri non valido";
}
*/




// Simo: Validazione uri, per eliminare javascript
// Restituisce true se il nome del file non � vuoto e se non � composto unicamente da http://
function is_valid_uri($uri) {
	
	$uri = trim($uri);
	if ($uri === "" || uri === "http://")
		return false;	
	else return true;
}



// Simo: Validazione filename per eliminare javascript
// Restituisce true se il file ha un'estensione .html, .htm o .xhtml
function is_valid_filename($file) {
	
	$file_extension = trim(strtolower(substr($file, strrpos($file,"."))));
	$allowed_ext = array (".html", ".htm", ".xhtml", ".php", ".asp");
	
	if (in_array($file_extension, $allowed_ext))
		return true;
	else return false;
}



?>