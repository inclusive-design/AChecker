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
         /* output functions */
include_once(AC_INCLUDE_PATH.'header.inc.php');
?>



<h2 class="titolo_req">Perch&egrave; scegliere l'opzione "Sito da validare di nuova realizzazione"</h3>	

<div id="cont_req">

<div class="txt_req">
<p> 
Alcuni requisiti consentono di effettuare un percorso alternativo di adeguamento di siti pubblici particolarmente complessi. Con la formulazione "in sede di nuova applicazione", la Legge 04/2004 ha inteso considerare l'impatto che una applicazione immediata dei requisiti pu&ograve; avere sul panorama attuale dei siti Web pubblici con oggettive difficolt&agrave; operative di applicazione del requisito nell'enunciato generale. Per taluni requisiti &egrave; stato perci&ograve; indicato un possibile percorso di adeguamento. In questi casi, come &egrave; spiegato dettagliatamente in alcuni requisiti, i controlli sono meno vincolanti e l'opzione "Sito da validare di nuova realizzazione" non andrebbe selezionata.
<br/><br/>

Di seguito sono riportati i testi dei requisiti coinvolti:

</p>

</div>

	<div id="req_1" class="txt_req"  style="margin-bottom:1em;">
      <h3><strong>Requisito</strong> <abbr title="numero">n.</abbr>  1</h3>
      <p><strong>Enunciato:</strong> Realizzare le pagine e gli oggetti al loro
        interno utilizzando tecnologie definite da grammatiche formali pubblicate,
        nelle versioni pi&ugrave; recenti disponibili quando sono supportate
        dai programmi utente. Utilizzare elementi ed attributi in modo conforme
      alle specifiche, rispettandone l&#8217;aspetto semantico</p>
	
      <p>In particolare, per i linguaggi a marcatori HTML (<span xml:lang="en" lang="en">HypertText
          Markup Language</span>) e XHTML (<span xml:lang="en" lang="en">eXtensible HyperText Markup Language</span>):</p>
        <p class="rientro3">a) Per tutti i siti di nuova realizzazione, utilizzare almeno la versione
          4.01 dell'HTML o preferibilmente la versione 1.0 
		  dell'XHTML, in ogni
          caso  con DTD (<span xml:lang="en" lang="en">Document
          Type Definition</span> - Definizione del Tipo di Documento) di tipo
          Strict;</p>

        <p class="rientro3">b) Per i siti esistenti, in sede di prima applicazione, nel caso
          in cui non sia possibile ottemperare al punto a) &egrave; consentito
        utilizzare la versione dei linguaggi sopra indicati con DTD Transitional,
          ma con le seguenti avvertenze:</p>
        <ol style="margin-left:4em;">
          <li>	evitare di utilizzare, all&#8217;interno del linguaggio a marcatori
            con il quale la pagina &egrave; realizzata, elementi ed attributi
            per definirne le caratteristiche presentazionali (per esempio, caratteristiche
            dei caratteri del testo, colori del testo stesso e dello sfondo,
          ecc.), ricorrendo invece ai Fogli di Stile CSS (<span xml:lang="en" lang="en">Cascading
          Style Sheets</span>) per ottenere lo stesso effetto grafico; </li>

          <li>evitare la generazione di nuove finestre; ove ci&ograve; non fosse possibile, avvisare esplicitamente l'utente del cambiamento del focus;</li>
          <li>pianificare la transizione dell'intero sito alla versione con DTD
            Strict del linguaggio utilizzato. Il piano di transizione va presentato
            alla Presidenza del Consiglio dei Ministri &ndash; Dipartimento per
            l'Innovazione e le Tecnologie da parte del responsabile della accessibilit&agrave; informatica
            (Art. 9 Regolamento).</li>
        </ol>
		         
   <p><strong>Riferimenti <acronym xml:lang="en" lang="en" title="Web Content Accessibility Guidelines">WCAG</acronym> 1.0:</strong> 3.1,
	    3.2, 3.5, 3.6, 3.7, 11.1, 11.2</p>

      <p> <strong>Riferimenti Sec. 508:</strong> Non presente</p>
	</div>
	
	<div id="req_2" class="txt_req"  style="margin-bottom:1em;">		
      <h3>Requisito <abbr title="numero">n.</abbr> 2</h3>
      <p><strong>Enunciato: </strong>Non &egrave; consentito l'uso dei frame nella realizzazione di nuovi siti.</p>

      <p>In sede di prima applicazione, per i siti esistenti gi&agrave; realizzati
        con frame, &egrave; consentito l'uso di HTML 4.01 o XHTML 1.0 con DTD
        frameset, ma con le seguenti avvertenze:</p>
      <ul>
        <li>evitare di utilizzare, all&#8217;interno del linguaggio a marcatori
          con il quale la pagina &egrave; realizzata, elementi ed attributi per
          definirne le caratteristiche presentazionali (per esempio, caratteristiche
          dei caratteri, del testo, colori del testo stesso e dello sfondo, ecc.),
        ricorrendo invece ai Fogli di Stile CSS (Cascading Style Sheets) per
        ottenere lo stesso effetto grafico;</li>
        <li>fare in modo che ogni frame abbia un titolo significativo per facilitarne
          l'identificazione e la navigazione. Se necessario, descrivere anche
          lo scopo dei frame e la loro relazione;</li>

        <li>pianificare la transizione a XHTML almeno nella versione 1.0 con
          DTD Strict dell'intero sito. Il piano di transizione va presentato
          alla Presidenza del Consiglio dei Ministri &ndash; Dipartimento per
          l'Innovazione e le Tecnologie da parte del responsabile della accessibilit&agrave; informatica
          (Art. 9 Regolamento).</li>
      </ul>
      <p><strong>Riferimenti WCAG 1.0:</strong> 12.1, 12.2</p>
      <p><strong>Riferimenti Sec. 508<em>:</em></strong> 1194.22 (i)</p>
 	</div>
	
	
	<div id="req_22" class="txt_req"  style="margin-bottom:1em;">	     
       
      <h3>Requisito <abbr title="numero">n.</abbr> 22</h3>

      <p><strong>Enunciato: </strong>In sede di prima applicazione, per i siti
        esistenti, in ogni pagina che non possa essere ricondotta al rispetto
        dei presenti requisiti, fornire un collegamento a una pagina che li rispetti,
        contenga informazioni e funzionalit&agrave; equivalenti e sia aggiornata
        con la stessa frequenza della pagina originale, evitando la creazione
        di pagine di solo testo. Il collegamento alla pagina accessibile deve
        essere proposto in modo evidente all&#8217;inizio della pagina non accessibile.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 11.4</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (k)</p>
	</div>
</div>
<?php
include(AC_INCLUDE_PATH.'footer.inc.php');
?>