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


include_once(AC_INCLUDE_PATH.'header.inc.php');

//if (isset($this->error)) echo $this->error;

?>
<div class="center-input-form">
		
		<?php
			
			global $msg;
			if ($msg->containsErrors()){	 
				$msg->printAll();
			}
		?>
		
<form   enctype="multipart/form-data" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >

	<div class="input-form">
		<div  id="right-input-form">

		<!-- Simo: requisiti fuori da option	 -->	
			
	  		<div id="requisite-option">
	  		
			<?php	$requisiti=$this->req_stanca;?>
			
				<!--TOSI VIRRUSO-->
				<fieldset class="group_form"><legend class="group_form">Contrast Type</legend>		
				<div id="contrastType">
					<!-- Hidden per Legge Stanca e requisito 6 selezionati -->
					<input type="hidden" name="req[]" id="req_7" value="105" />
					<input type="hidden" name="gid[]" id='guid_10' value='10' />				
					<input type="checkbox" name="contrastType[]" value="WCAG1" checked="checked" /> Wcag 1.0 - Legge Stanca <br/>
					<input type="checkbox" name="contrastType[]" value="WCAG2AA" /> Wcag 2.0 Livello AA <br/>
					<input type="checkbox" name="contrastType[]" value="WCAG2AAA" /> Wcag 2.0 Livello AAA <br/>
				</div>
				</fieldset>
					
			</div> <!--requisite-option-->

			
		</div> <!--right-input-form-->	

		<div   id="left-input-form">			
			 <fieldset class="group_form"><legend class="group_form"><span lang="en" xml:lang="en">Valida</span></legend>	
				
			 	<h2 class="validation_title"><label for="check_uri">Validazione tramite <acronym lang="en" title="Uniform Resource Locator" xml:lang="en">URL</acronym></label></h2>
				<div class="row-input" >
					
					<input type="text" name="uri" id="check_uri" value="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $this->default_uri_value; ?>"  />
					<input type="submit" name="validate_uri"  value="<?php echo _AC("check_it"); ?>"  class="submit" />
				</div>
				<h2 class="validation_title"><label for="check_file">Validazione tramite <span lang="en" xml:lang="en">upload</span></label></h2>
		
				<div class="row-input">			
					<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
					<input type="file" id="check_file" name="uploadfile" />
					<input type="submit" name="validate_file" value="<?php echo _AC("check_it"); ?>"  class="submit" />
				</div>
			  </fieldset>		
		</div>
		
		
	</div>	
</form>



</div>