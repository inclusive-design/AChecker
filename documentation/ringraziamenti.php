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


	<h2 class="titolo_req">Ringraziamenti</h3>	

<div id="cont_req">

	<div id="ringraziamenti" class="txt_ringraziamenti">
      <h3><strong>Si ringraziano, per la loro collaborazione: </strong></h3>
	      
	      		<p>Filippo Borghesi</p>	      


	     
	</div>
</div>
<?php
include(AC_INCLUDE_PATH.'footer.inc.php');
?>