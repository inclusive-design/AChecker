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



	<h2 class="titolo_req">Requisiti della legge 4/2004</h2>	

<div id="cont_req">

	<div id="req_1" class="txt_req" style="margin-bottom:1em;">
      <h3><strong>Requisito</strong> <abbr title="numero">n.</abbr>  1</h3>
      <p><strong>Enunciato:</strong> Realizzare le pagine e gli oggetti al loro
        interno utilizzando tecnologie definite da grammatiche formali pubblicate,
        nelle versioni pi&ugrave; recenti disponibili quando sono supportate
        dai programmi utente. Utilizzare elementi ed attributi in modo conforme
      alle specifiche, rispettandone l&#8217;aspetto semantico</p>
	
      <p>In particolare, per i linguaggi a marcatori  HTML (<span xml:lang="en" lang="en">HypertText
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
	
	<div id="req_2" class="txt_req" style="margin-bottom:1em;">		
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
	
	<div id="req_3" class="txt_req" style="margin-bottom:1em;">	     
      
      <h3>Requisito <abbr title="numero">n.</abbr> 3</h3>
      <p><strong>Enunciato: </strong>Fornire una alternativa testuale equivalente
        per ogni oggetto non di testo presente in una pagina e garantire che
        quando il contenuto non testuale di un oggetto cambia dinamicamente vengano
        aggiornati anche i relativi contenuti equivalenti predisposti. L&#8217;alternativa
        testuale equivalente di un oggetto non testuale deve essere commisurata
        alla funzione esercitata dall&#8217;oggetto originale nello specifico
        contesto.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 1.1, 6.2</p>

      <p><strong>Riferimenti Sec. 508<em>:</em></strong> 1194.22 (a)</p>

 	</div>
	
	<div id="req_4" class="txt_req" style="margin-bottom:1em;">	     
      <h3>Requisito <abbr title="numero">n.</abbr> 4</h3>
      <p><strong>Enunciato:</strong> Garantire che tutti gli elementi informativi
        e tutte le funzionalit&agrave; siano disponibili anche in assenza del
      particolare colore utilizzato per presentarli nella pagina.</p>

      <p><strong>Riferimenti WCAG 1.0:</strong> 2.1</p>
      <p><strong>Riferimenti Sec. 508<em>:</em></strong> 1194.22 (c)</p>
	</div>
	
	<div id="req_5" class="txt_req" style="margin-bottom:1em;">	      
      
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
	
	<div id="req_6" class="txt_req" style="margin-bottom:1em;">	
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
	
	<div id="req_7" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 7</h3>

      <p><strong>Enunciato:</strong>Utilizzare mappe immagine sensibili di tipo
        lato client piuttosto che lato server, eccetto nel caso in cui le zone
        sensibili non possano essere definite con una delle forme geometriche
        predefinite indicate nella DTD adottata</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 9.1</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (f)</p>
	</div>
	
	<div id="req_8" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 8</h3>

      <p><strong>Enunciato:</strong> Se vengono utilizzate mappe immagine lato
        server, fornire i collegamenti di testo alternativi necessari per poter
        ottenere tutte le informazioni o i servizi raggiungibili interagendo
        direttamente con la mappa.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 1.2</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (e)</p>
	</div>
	
	<div id="req_9" class="txt_req" style="margin-bottom:1em;">
		
      <h3>Requisito <abbr title="numero">n.</abbr> 9</h3>
      <p><strong>Enunciato: </strong>Per le tabelle dati usare gli elementi (marcatori)
        e gli attributi previsti dalla DTD adottata per descrivere i contenuti
      e identificare le intestazioni di righe e colonne.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 5.1, 5.5, 5.6</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (g)</p>
	</div>
	
	<div id="req_10" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 10</h3>
      <p><strong>Enunciato:</strong> Per le tabelle dati usare gli elementi (marcatori)
        e gli attributi previsti nella DTD adottata per associare le celle di
        dati e le celle di intestazione che hanno due o pi&ugrave; livelli logici
        di intestazione di righe o colonne.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 5.2</p>

      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (h)</p>
	</div>
	
	<div id="req_11" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 11</h3>
      <p><strong>Enunciato:</strong> Usare i fogli di stile per controllare la presentazione dei contenuti e organizzare le pagine in modo che possano essere lette anche quando i fogli di stile siano disabilitati o non supportati. </p>

      <p><strong>Riferimenti WCAG 1.0:</strong> 3.3, 6.1</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (d)</p>  
	</div>
	
	<div id="req_12" class="txt_req" style="margin-bottom:1em;">	      
      
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
	
	<div id="req_13" class="txt_req" style="margin-bottom:1em;">	      
      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 13</h3>
      <p><strong>Enunciato: </strong>Qualora si utilizzino le tabelle a scopo di impaginazione:</p>
      <ul>
        <li> garantire che il contenuto della tabella sia comprensibile
        anche quando questa viene letta in modo linearizzato,</li>

        <li>utilizzare gli elementi e gli attributi di una tabella rispettandone il valore semantico definito nella specifica del linguaggio a marcatori utilizzato.</li>
      </ul>
      <p><strong>Riferimenti WCAG 1.0:</strong> 5.3, 5.4</p>
      <p><strong>Riferimenti Sec. 508:</strong> non presente</p>  
	</div>
	
	<div id="req_14" class="txt_req" style="margin-bottom:1em;">	      
      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 14</h3>
      <p><strong>Enunciato:</strong> Nei moduli (form), associare in maniera
        esplicita le etichette ai rispettivi controlli, posizionandole in modo
        che per chi utilizza le tecnologie assistive la compilazione dei campi
      sia agevolata.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 10.2, 12.4</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (n)</p>     
	</div>
	
	<div id="req_15" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 15</h3>
      <p><strong>Enunciato: </strong>Garantire che le pagine siano utilizzabili
      quando script, applet,
        o altri oggetti di programmazione sono disabilitati oppure non supportati.
      Se questo non &egrave; possibile:</p>
      <ul>
        <li>fornire una spiegazione della funzionalit&agrave; svolta;</li>

        <li>garantire una alternativa testuale equivalente in modo analogo  a
          quanto indicato nel requisito <abbr title="numero">n.</abbr> 3</li>
      </ul>
      <p><strong>Riferimenti WCAG 1.0:</strong> 6.3</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (l),1194.22 (m)</p>
    </div>
	
	<div id="req_16" class="txt_req" style="margin-bottom:1em;">	
      
      <h3>Requisito <abbr title="numero">n.</abbr> 16</h3>
      <p><strong>Enunciato: </strong>Garantire che i gestori di eventi che attivano
         script, applet oppure
        altri oggetti di programmazione o che possiedono una propria specifica
        interfaccia, siano indipendenti da uno specifico dispositivo  di
        input.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 6.4, 9.2, 9.3 </p>

      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (l),1194.22 (m)</p>
	</div>
	
	<div id="req_17" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 17</h3>
      <p><strong>Enunciato: </strong>Garantire che le funzionalit&agrave; e
        le informazioni veicolate per mezzo di oggetti di programmazione, oggetti
        che utilizzino tecnologie non definite da grammatiche formali pubblicate,
        script e applet siano direttamente accessibili.</p>

      <p><strong>Riferimenti WCAG 1.0:</strong> 8.1</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (l), 1194.22 (m)</p>
	</div>
	
	<div id="req_18" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 18</h3>

      <p><strong>Enunciato:</strong> Qualora un  filmato o una presentazione
        multimediale siano indispensabili per la completezza dell&#8217;informazione
        fornita o del servizio erogato, predisporre una alternativa testuale
        equivalente sincronizzata in forma di sotto-titolazione e/o di descrizione
        vocale, oppure predisporre un riassunto o una semplice etichetta per
        ciascun elemento video o multimediale, tenendo conto del livello di importanza
        e delle difficolt&agrave; di realizzazione nel caso di presentazioni
      in tempo reale.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 1.3, 1.4</p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (b)</p>
	</div>
	
	<div id="req_19" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 19</h3>
      <p><strong>Enunciato: </strong>Rendere chiara la destinazione di ciascun
        collegamento ipertestuale (<em>link</em>) con testi significativi anche
        se letti indipendentemente dal proprio contesto
        oppure associare ai collegamenti testi alternativi che possiedano analoghe
        caratteristiche esplicative. Prevedere meccanismi che consentano
        di evitare la lettura ripetitiva di sequenze di collegamenti comuni
        a pi&ugrave; pagine.</p>
      <p><strong>Riferimenti WCAG 1.0:</strong> 13.1, 13.6</p>

      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (o)</p>
	</div>
	
	<div id="req_20" class="txt_req" style="margin-bottom:1em;">	      
      
      <h3>Requisito <abbr title="numero">n.</abbr> 20</h3>
      <p><strong>Enunciato: </strong>Se per la fruizione del servizio erogato
        in una pagina &egrave; previsto un intervallo di tempo predefinito entro
        il quale eseguire determinate azioni, &egrave; necessario avvisare esplicitamente
        l&#8217;utente, indicando il tempo massimo utile e fornendo eventuali
      alternative per fruire del servizio stesso.</p>

      <p><strong>Riferimenti WCAG 1.0:</strong> 7.4, 7.5 </p>
      <p><strong>Riferimenti Sec. 508:</strong> 1194.22 (p)</p>
	</div>
	
	<div id="req_21" class="txt_req" style="margin-bottom:1em;">	      
      
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
	
	<div id="req_22" class="txt_req" style="margin-bottom:1em;">	        
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