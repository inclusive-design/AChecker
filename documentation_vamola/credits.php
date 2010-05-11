<?php


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

<h2 class="titolo_req"">Credits</h2>
<div style="text-align:center;">
<div class="contenitore-loghi">

<a href="http://atrc.utoronto.ca/"><img style="border:none;padding:3%;" src="<?php echo 'themes/'. $_SESSION['prefs']['PREF_THEME'] ?>/images/atrclogo_large.jpg"  alt="Adaptive Technology Resource Centre" /></a> 
<a  href="http://www.unibo.it"><img style="border:none;padding:3%;" src="<?php echo 'themes/'. $_SESSION['prefs']['PREF_THEME'] ?>/images/logo_unibo_large.gif"  alt="Universit&agrave; di Bologna" /></a>
<a  href="http://www.asphi.it"><img style="border:none;padding:3%;" src="<?php echo 'themes/'. $_SESSION['prefs']['PREF_THEME'] ?>/images/logo_asphi.gif"  alt="Fondazione Asphi Onlus" /></a>
<a  href="http://www.regione.emilia-romagna.it/"><img style="border:none;padding:3%;" src="<?php echo 'themes/'. $_SESSION['prefs']['PREF_THEME'] ?>/images/logopictra_large.jpg" alt="Regione Emilia-Romagna" /></a>
<a  href="http://www.regionedigitale.net/wcm/erdigitale/pagine/pagina_documentazione/interventi/pitercompa06.htm"><img style="border:none;padding:3%" src="<?php echo 'themes/'. $_SESSION['prefs']['PREF_THEME'] ?>/images/piter_large.gif"  alt="Piano Telematico dell'Emilia Romagna" /></a>
</div>
</div>

<?php
include(AC_INCLUDE_PATH.'footer.inc.php');
?>