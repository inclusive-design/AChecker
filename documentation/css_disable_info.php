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



<h2 class="titolo_req">Chiarimenti sull'opzione "Disabilita la verifica dei CSS"</h2>	

<div id="cont_req">

<div class="txt_req">
<p> 
Certi controlli relativi ad alcuni Requisiti necessitano di accedere ai fogli di stile CSS associati alla pagina da validare. Nel caso di fogli di stile particolarmente elaborati, tale operazione pu&ograve; risultare molto lenta. Per evitare questo tipo di controlli &egrave; sufficiente attivare il checkbox "Disabilita la verifica dei CSS".
<br/><br/>

Di seguito sono riportati i testi dei requisiti coinvolti:

</p>

</div>

<div id="req_5" class="txt_req"  style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 5</h3>

      <p><strong>Enunciato: </strong>Evitare oggetti e scritte lampeggianti o
        in movimento le cui frequenze di intermittenza possano provocare disturbi
        da epilessia fotosensibile, disturbi della concentrazione o che possano
        causare il malfunzionamento delle tecnologie assistive utilizzate. Qualora
        esigenze informative richiedano comunque il loro utilizzo, avvisare l&#8217;utente
        del possibile rischio prima di presentarli e predisporre metodi che consentano
      di evitare tali elementi.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 7.1, 7.2, 7.3</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (j)</p>
	</div>
	
	<div id="req_6" class="txt_req"  style="margin-bottom:1em;">	
      <h3>Requisito <abbr title="numero">n.</abbr> 6</h3>
      <p><strong>Enunciato: </strong>Garantire che siano sempre distinguibili
      il contenuto informativo  (<span xml:lang="en" lang="en"><em>foreground</em></span>)
      e lo sfondo (<span xml:lang="en" lang="en"><em>background</em></span>),
      ricorrendo a un sufficiente contrasto (nel caso del testo) o a differenti
      livelli sonori (in caso di parlato con sottofondo musicale). Un testo in
      forma di immagine in genere &egrave; da evitare ma, se non &egrave; possibile
      farne a meno, deve essere realizzato con gli stessi criteri di distinguibilit&agrave; indicati
      in precedenza.</p>

      <p><strong>Riferimenti WCAG 1.0:</strong> 2.2</p>
      <p><strong>Riferimenti Sec. 508:</strong> non presente</p>
	</div>

	<div id="req_12" class="txt_req"  style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 12</h3>

      <p><strong>Enunciato:</strong> La presentazione e i contenuti testuali
        di una pagina devono potersi adattare alle dimensioni della finestra
        del browser utilizzata dall&#8217;utente senza sovrapposizione degli
        oggetti presenti o perdita di informazioni tali da rendere incomprensibile
        il contenuto, anche in caso di ridimensionamento, ingrandimento o riduzione
        dell&#8217;area di visualizzazione e/o dei caratteri rispetto ai valori
      predefiniti di tali parametri.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 3.4</p>
      <p><strong>Riferimenti Sec. 508:</strong> non presente</p>
	</div>	
	
	<div id="req_21" class="txt_req"  style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 21</h3>

      <p><strong>Enunciato:</strong> I collegamenti presenti in una pagina devono essere selezionabili e attivabili 
tramite comandi da tastiera, tecnologia in emulazione di tastiera o tramite 
sistemi di puntamento diversi dal mouse. Per facilitare la selezione e 
l&rsquo;attivazione dei collegamenti con queste tecnologie assistive &egrave; anche necessario garantire che:</p>
      <ul>
        <li>la distanza verticale di liste di link e la spaziatura orizzontale tra link consecutivi sia di almeno 1 em; </li>
        <li>le distanze orizzontale e verticale tra i pulsanti di un modulo (form) sia di almeno 1 em; </li>
        <li>le dimensioni dei pulsanti in un modulo (form) siano tali da rendere chiaramente leggibile l'etichetta in essi contenuta, per esempio utilizzando opportunamente il margine tra l'etichetta e i bordi del pulsante.</li>

      </ul>
      <p><strong>Riferimenti WCAG 1.0:</strong> non presente</p>
      <p><strong>Riferimenti Sec. 508:</strong> non presente</p>
	</div>	
	
	
</div>
<?php
include(AC_INCLUDE_PATH.'footer.inc.php');
?>