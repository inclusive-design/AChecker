<?php
define('AC_INCLUDE_PATH', '../include/');

include(AC_INCLUDE_PATH.'vitals.inc.php');
//include_once(AC_INCLUDE_PATH. 'lib/output.inc.php');

use Mpdf\Mpdf as Mpdf;

class acheckerMPDF extends Mpdf {

	function __construct()
	{
		//Call parent constructor
		parent::__construct([
			'debug' => true,
            'mode' => 'utf-8',
            'allow_output_buffering' => true,
			'tempDir' => AC_EXPORT_RPT_DIR
		]);
	
	}
	

	public function getPDF() 
	{		
		// set filename
		$date = AC_date('%Y-%m-%d');
		$time = AC_date('%H-%i-%s');
		$filename = 'achecker_'.$date.'_'.$time.$rand_str;		
		$this->SetHeader('Document Title');
		$this->WriteHTML('Document text');
		// close and save PDF document		
		$path = AC_EXPORT_RPT_DIR.$filename.'.pdf';  
		$this->Output($filename.'.pdf', 'D');	

		return $path;
	}
	
	
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    hello
    <?php 
    $pdf = new acheckerMPDF();
	return $path = $pdf->getPDF();
    
    ?>
</body>
</html>