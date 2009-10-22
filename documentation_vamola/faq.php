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








<h2 class="titolo_req">Faq sul progetto VaMoL&agrave;</h2>	

<div id="cont_req">
<div class="txt_req">
<h3><strong>Le domande pi&ugrave; frequenti:</strong></h3>
<ul>
	<li><a href="documentation_vamola/faq.php#domanda1">Cosa &egrave; Achecker e come si integra con VaMoL&agrave;?</a></li>
	<li><a href="documentation_vamola/faq.php#domanda2">Quali requisiti saranno verificati?</a></li>
	<li><a href="documentation_vamola/faq.php#domanda3">Come viene progettato, sviluppato e testato il validatore?</a></li>
	<li><a href="documentation_vamola/faq.php#domanda4">Quali tipologie di messaggi restituisce il validatore?</a></li>
	<li><a href="documentation_vamola/faq.php#domanda5">L'interfaccia di VaMoL&agrave; &egrave; definitiva?</a></li>
	<li><a href="documentation_vamola/faq.php#domanda6">VaMoL&agrave; usa validatori esterni?</a></li>
	<li><a href="documentation_vamola/faq.php#domanda7">A chi segnalare errori, suggerimenti, commenti ecc.?</a></li></a></li>
</ul>
</div>

	<div id="domanda1" class="txt_req">
      <h3><strong>Cosa &egrave; Achecker e come si integra con VaMoL&agrave;?</strong></h3>
			<p>
			<a class="postlink" href="http://www.atutor.ca/achecker/index.php">AChecker</a> &egrave; un'applicazione Web-based open source che permette di validare l'accessibilit&agrave; delle pagine Web in base a differenti linee guida e normative nazionali e internazionali. &egrave; stata sviluppata dall'<a class="postlink" href="http://atrc.utoronto.ca/">Adaptive Techonology Resource Centre (ATRC)</a> dell'Universit&agrave; di Toronto. Il framework sottostante l'applicazione AChecker &egrave; stato adottato come piattaforma sulla quale implementare VaMoL&agrave;. Le caratteristiche di questa applicazione (di essere open source, Web-based, matura, robusta e sostenuta da un'ampia comunit&agrave; di sviluppatori, gi&agrave; allineata alla validazione WCAG 2.0) ne hanno motivato la scelta. Le attivit&agrave; di progettazione e sviluppo di VaMoL&agrave; sono coordinate alle attivit&agrave; del gruppo di lavoro di ATRC.
			</p>	
	</div>
	
    <div id="domanda2" class="txt_req" >
    	<h3>Quali requisiti saranno verificati?</h3>
		<p>
			Nella realizzazione di VaMoL&agrave; i requisiti saranno considerati per gruppi correlati ad aspetti omogenei o a specifici elementi. In particolare, VaMoL&agrave; sar&agrave; sviluppato in 6 parti che fanno riferimento rispettivamente ai seguenti gruppi di requisiti:<br />
			<ol style="margin-left:2em;">
				<li> Requisiti relativi alla validit&agrave; del codice: 1, 2.</li>	
				<li> Requisiti correlati agli elementi multimediali: 3, 7, 8, 18.</li>
				<li> Requisiti relativi agli aspetti presentazionali: 4, 5, 6, 11, 12, 21.</li>
				<li> Requisiti sui moduli: 14.</li>
				<li> Requisiti relativi alle tabelle: 9, 10, 13.</li>
				<li> Requisiti correlati agli oggetti di programmazione: 15, 16, 17, 19, 20.</li>
			</ol>
		</p>
	</div>

	<div id="domanda3" class="txt_req" >
    	<h3>Come viene progettato, sviluppato e testato il validatore?</h3>
		<p>
			La progettazione e lo sviluppo di VaMoL&agrave; avvengono, per gruppi di requisiti, attraverso le seguenti fasi: <br />
			<ul>
				<li> Definizione delle specifiche del componente di validazione per il requisito (a cura del Gruppo Ristretto).</li>
				<li> Implementazione del prototipo (a cura del team di sviluppo).</li>
				<li> Test del prototipo e proposta di modifica delle specifiche (a cura del gruppo esteso).</li>
				<li> Eventuale correzione e definizione finale del prototipo (a cura del team di sviluppo).</li>
				<li> Rilascio della versione definitiva.</li>
			</ul>
		</p>
	</div>

	<div id="domanda4" class="txt_req" >
    	<h3>Quali tipologie di messaggi restituisce il validatore?</h3>
		<p>
			I messaggi di errore sono di 4 tipi e fanno riferimento a correzioni e controlli che l'utente DEVE sempre fare:
		<ol start="0" style="margin-left:2em;">
			<li> Errori da correggere. In questa categoria rientrano errori inequivocabili, rilevabili in modo completamente automatico, che l'utente DEVE CORREGGERE. Per esempio, verr&agrave; individuato un errore di questo tipo quando un elemento &lt;img&gt; viene usato senza l'attributo alt.</li>
			<li> Controlli manuali. In questa categoria sono segnalati i controlli che DEVONO ESSERE EFFETTUATI per completare la validazione. In questi casi il sistema automatico suggerisce le operazioni di verifica, ma non &egrave; in grado di compierle. Per esempio, per ogni attributo alt il validatore suggerisce all'utente di effettuare un controllo manuale sulla effettiva corrispondenza all'immagine.</li>
			<li> Errori potenziali relativi ai requisiti. In questa categoria rientrano errori rispetto alla corretta applicazione dei requisiti che possono essere rilevati attraverso euristiche e la cui effettiva presenza DEVE ESSERE VERIFICATA dall'utente. Per esempio, quando l'attributo alt &egrave; un URL (la cui presenza &egrave; rilevabile automaticamente) deve essere verificato che si tratti di un alternativo significativo.</li>
			<li> Errori potenziali generali. In questa categoria rientrano errori che possono essere rilevati attraverso euristiche, ma che non fanno riferimento a specifici requisiti della verifica tecnica (per esempio valutazioni generali, come la dimensione in byte della pagina, il numero di link, i meta, ecc). Queste segnalazioni potrebbero essere considerate come un primo supporto alla verifica soggettiva.</li>
		</ol>
		</p>
	</div>
	
	<div id="domanda5" class="txt_req" >
    	<h3 >L'interfaccia di VaMoL&agrave; &egrave; definitiva?</h3>
		<p>
			No, &egrave; una interfaccia predisposta per lo sviluppo e il test a partire da quella di AChecker. Pu&ograve; essere modificata, sia per introdurre migliorie utili alla verifica del sistema, sia per migliorare l'aspetto grafico. L'interfaccia utente definitiva sar&agrave; realizzata nei prossimi mesi e contributi ed idee da parte del Gruppo Esteso sono benvenute.
		</p>
	</div>
	
	<div id="domanda6" class="txt_req">
    	<h3 >VaMoL&agrave; usa validatori esterni?</h3>
		<p>	
		VaMoL&agrave; utilizza i validatori on line del W3C, sia per il codice HTML sia per i fogli di stile CSS. Inoltre sono previsti altre componenti di validazione legate a markup non HTML (controlli su CSS o oggetti specifici non inclusi)
		</p>
	</div>
		
	<div id="domanda7" class="txt_req" style="margin-bottom:2em;">
    	<h3 >A chi segnalare errori, suggerimenti, commenti ecc.?</h3>
		<p>	
		E' possibile inviare errori, suggerimenti e segnalazioni varie tramite l'indirizzo: vamola AT polocesena DOT unibo DOT it
		</p>
	</div>
	
</div>

<?php
// display footer
include(AC_INCLUDE_PATH.'footer.inc.php');

?>