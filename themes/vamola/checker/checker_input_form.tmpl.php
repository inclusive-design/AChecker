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
		
<form   enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >

	<div class="input-form">
		<div  id="right-input-form">

		
			<!--Simo: checked default-->
			<div id="general-option">
				 <fieldset class="group_form"><legend class="group_form">Generale</legend>						
					
				
					<div class="general-option-row">
						<input type="checkbox" name="new_web_site" id="new_web_site" value="1" 
						<?php if (($_SESSION["new_web_site"]==1))
						{	echo 'checked="checked"'; }
						?>
						/>
						<label for='new_web_site'>Valida un sito di nuova realizzazione (<acronym lang="en" title="Document Type Definition" xml:lang="en">DTD</acronym> <span lang="en" xml:lang="en">Strict</span>) </label><a class="req_link"  href="<?php echo $this->base_path ?>documentation_vamola/nuova_realizzazione_info.php" title="Visualizza le informazioni su questa opzione"><img  src="themes/vamola/images/bookd.gif" alt="Informazioni sull'opzione 'Valida un sito di nuova realizzazione'"/></a>
						<br/>
						<input type="checkbox" name="css_disable" id="css_disable" value="1" 
						<?php if (($_SESSION["css_disable"]==1))
						{	echo 'checked="checked"'; }
						?>
						/>
						
						<label for='css_disable'>Disabilita la verifica dei CSS </label><a class="req_link" href="<?php echo $this->base_path ?>documentation_vamola/css_disable_info.php" title="Visualizza le informazioni su questa opzione"><img  src="themes/vamola/images/bookd.gif" alt="Informazioni sull'opzione 'Disabilita la verifica dei CSS'" /></a>
						
						
						<br/>
						
						<input type="checkbox" name="visual_img" id="visual_img" value="0" 
						<?php if (($_SESSION["visual_img"]==1))
						{	echo 'checked="checked"'; }
						?>
						/>
						<label for='visual_img'>Controllo visivo alternativa testuale </label><a class="req_link" href="<?php echo $this->base_path ?>documentation_vamola/visual_img_info.php" title="Visualizza le informazioni su questa opzione"><img  src="themes/vamola/images/bookd.gif" alt="Informazioni sull'opzione 'Controllo visivo alternativa testuale'" /></a>
					
					
					</div>	
					<!-- tolgo check 
						<div class="general-option-row">
						<input   type="checkbox" name="enable_html_validation" id="enable_html_validation" value="1" 
								<?php //if (($_SESSION["enable_html_validation"]==1) ) echo 'checked="checked"'; ?>
							/>
						<label for='enable_html_validation'><?php //echo _AC("enable_html_validator"); ?></label>
					</div>	
					<div class="general-option-row">
						<input   type="checkbox" name="enable_css_validation" id="enable_css_validation" value="2" 
								<?php //if (($_SESSION["enable_css_validation"]==1) ) echo 'checked="checked"'; ?>
							/>
						<label for='enable_css_validation'>Attiva validatore CSS</label>
					</div>
					-->
				</fieldset>
			</div>	<!-- general-option-->	
				
			
	  		<!-- Simo: requisiti fuori da option	 -->	
			
	  		<div id="requisite-option">
	  		
			<?php	$requisiti=$this->req_stanca;?>
			
				<fieldset class="group_form"><legend class="group_form">Requisiti</legend>		
				<div id="requisiti">
					<div style="width:33%;float:left;padding:1%;text-align:left;"><!-- colonna sinistra-->
					<?php
						for ($i=0; $i<sizeof($requisiti); $i++)
						{
							if($requisiti[$i]=='106')
							{
					?>
								<div>
								<input <?php  if( $i==8 || $i==9 || $i==12 || $i==14 || $i==15 || $i==16 || $i==18 || $i==19 || $i==21) echo('disabled="disabled"');?> type="checkbox" name="req[]" <?php echo('id="req_'.($i+1).'" value="'.$requisiti[$i].'"') ?> 
									<?php if (isset($_SESSION["req"]))
											{	foreach($_SESSION["req"] as $req) 
													if ($req == $requisiti[$i] && !( $i==8 || $i==9 || $i==12 || $i==14 || $i==15 || $i==16 || $i==18 || $i==19 || $i==21)) echo 'checked="checked"';
											}		
									?> />
								<label for='req_<?php echo($i+1)?>'>Requisiti 7 e 8</label> <a class="req_link" href="<?php echo $this->base_path ?>documentation_vamola/requisiti.php#req_<?php echo($i+1)?>" title="Visualizza il testo dei Requisiti 7 e 8"><img  src="themes/vamola/images/bookd.gif" alt="Testo dei requisiti 7 e 8" /></a>
								</div>		
								
							<?php
							}
							elseif($requisiti[$i]!='107')
							{
							?>
								<div>
								<input <?php if($i==8 || $i==9 || $i==12 || $i==14 || $i==15 || $i==16 || $i==18 || $i==19 || $i==21) echo('disabled="disabled"');?>  type="checkbox" name="req[]" <?php echo('id="req_'.($i+1).'" value="'.$requisiti[$i].'"') ?> 
									<?php if (isset($_SESSION["req"]))
											{	foreach($_SESSION["req"] as $req) 
													if ($req == $requisiti[$i] && !( $i==8 || $i==9 || $i==12 || $i==14 || $i==15 || $i==16 || $i==18 || $i==19 || $i==21)) echo 'checked="checked"'; 
											}		
									?> />
								<label for='req_<?php echo($i+1)?>'>Requisito <?php echo($i+1)?></label> <a class="req_link" href="<?php echo $this->base_path ?>documentation_vamola/requisiti.php#req_<?php echo($i+1)?>" title="Visualizza il testo del Requisito <?php echo($i+1)?>"><img  src="themes/vamola/images/bookd.gif" alt="Testo del requisito <?php echo($i+1)?>" /></a>
								</div>
					<?php	
							}
							
							if($i==7)
							{
					?>		
									</div><!--fine colonna sinistra-->
						
									<div style="width:30%;float:left;padding:1%;text-align:left;"><!-- colonna centro-->
					<?php				
							}
							elseif($i==14)
							{
					?>
										</div><!--fine colonna centro-->
						
										<div style="width:30%;float:left;padding:1%;text-align:left;"><!--prova colonna destra-->
							
					<?php
							}
						}
				 	?>
			
				 	
					</div><!--fine colonna destra-->
				</div>
					
				
						<div id="sel_tutti"> 
							<input type="submit" name="all_checks" value="Seleziona tutti" class="submit" />
							<input type="submit" name="no_checks" value="Deseleziona tutti" class="submit" />
						</div>
					
				</fieldset>
					
			</div> <!--requisite-option-->

			
		</div> <!--right-input-form-->	

		<div   id="left-input-form">			
			 <fieldset class="group_form"><legend class="group_form"><span lang="en" xml:lang="en">Valida</span></legend>	
				
			 	<h2 class="validation_title"><label for="check_uri">Validazione tramite <acronym lang="en" title="Uniform Resource Locator" xml:lang="en">URL</acronym></label></h2>
				<div class="row-input" >
					
					
<!--			</div>-->
		
<!--			<div class="row-input">-->
					<input type="text" name="uri" id="check_uri" value="<?php if (isset($_POST['uri'])) echo $_POST['uri']; else echo $this->default_uri_value; ?>"  />
					<input type="submit" name="validate_uri"  value="<?php echo _AC("check_it"); ?>"  class="submit" />
				</div>
				<h2 class="validation_title"><label for="check_file">Validazione tramite <span lang="en" xml:lang="en">upload</span></label></h2>
				<div class="row-input">
					
<!--			</div>-->
		<!--	<div class="row-input">-->
					<input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
					<input type="file" id="check_file" name="uploadfile" />
					<input type="submit" name="validate_file" value="<?php echo _AC("check_it"); ?>"  class="submit" />
				</div>
			  </fieldset>
			 <!--MB text e bottoni-->
 <!--			AGGIUNTA PER WCAG2 -->
			 <fieldset class="group_form"><legend class="group_form"><span lang="en" xml:lang="en"> Linee Guida </span></legend>	

			 
			 	<div id="linee_guida">
					<div style="float:left;padding:1%;text-align:left;"><!-- prova colonna sinistra-->

						<div>
						<!--	Simo:aggiunto l'if senno' dava warning se non era settato-->
						<input  type="checkbox" name="gid[]" id='guid_10' value='10' 

							<?php 

									if (isset ($_SESSION['gid']) && in_array(10, $_SESSION["gid"]))
										echo 'checked="checked"'; 
														
							?> />
						<label for='guid_10'>Legge Stanca </label>
						</div>
							
						<div>
						<!--	Simo:aggiunto l'if senno' dava warning se non era settato-->
						<input  type="checkbox" name="gid[]" id='guid_7' value='7' 
							<?php 
									if (isset ($_SESSION['gid']) && in_array(7, $_SESSION["gid"]))
										echo 'checked="checked"'; 
							?> />
						<label for='guid_7'>Wcag 2.0 Livello A </label>
						</div>
						
						<div>
						<input  type="checkbox" name="gid[]" id='guid_8' value='8' 
							<?php 
									if (isset ($_SESSION['gid']) && in_array(8, $_SESSION["gid"]))
										echo 'checked="checked"'; 
							?> />
						<label for='guid_8'>Wcag 2.0 Livello AA </label>
						</div>
						
						<div>
						<input  type="checkbox" name="gid[]" id='guid_9' value='9' 
							<?php 
									if (isset ($_SESSION['gid']) && in_array(9, $_SESSION["gid"]))
										echo 'checked="checked"'; 
							?> />
						<label for='guid_9'>Wcag 2.0 Livello AAA </label>
						</div>
						
					</div>
				</div>

			 
			 </fieldset>

			 
			 		
		</div>
		
		
	</div>	
</form>



</div>

<?php
	// Simo: Eliminate le funzioni per validare uri e file con javascript, sostituite da funzioni php
?>
