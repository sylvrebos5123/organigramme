<?php
//ob_clean();

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
//$dico_script='\\annuaire\\tools\\php_dico_array.php';
//include_once($rootpath.'\\organigramme\\tools\\pba_tools.php'); //function de connection
//include_once($rootpath.'\\organigramme/php/genere_list_form5.php');
//include_once($rootpath.$dico_script); //function de traduction
 //function de traduction
include_once('../tools/function_dico.php');

$disabled="";


$lien="./php/modif_diplome.php";


?>

<form id="FORM_DIPLOME" name="FORM_DIPLOME" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table cellspacing="3" cellpadding="3" valign="top">
	<tr>
		<td colspan="2"><h1>Diplômes</h1></td>
	</tr>
	
	<tr>
		<td height="60px" style="vertical-align:top;"><?php echo dico("niveau_etude","F");?> : </td>
		<td style="vertical-align:top;">
		<select id="niveau_etudes" name="niveau_etudes" onchange="SetColorButton('FORM_DIPLOME');" <?php echo $disabled;?>>
			
			<?php
			
			/***********/
			include('../arrays_libelle/array_diplome.php');
			
			foreach($array_diplome as $key => $value)
			{
				//$new_key=str_replace('"', '', $key);
				
				if($niveau_etudes==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_diplome[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_diplome[$key]['F'].'</option>';
				}
			}
			
			
			?>
		</select>
		</td>
		
		<td style="vertical-align:top;"><?php echo dico("diplome(s)","F");?> : </td>
		<td style="vertical-align:top;">
		<textarea id="libelle_diplome" name="libelle_diplome" maxlength="200" onkeyup="SetColorButton('FORM_DIPLOME');" <?php echo $disabled;?>>
		<?php echo $libelle_diplome;?>
		</textarea> 
		<!--<input type="text"  size="60" value="<?php echo $libelle_diplome;?>" <?php echo $disabled;?>>-->
		</td>
		
	</tr>
	
	<tr>
		<td><?php echo dico("selor","F");?> : </td>
		<td>
		<select id="id_selor" name="id_selor" onchange="SetColorButton('FORM_DIPLOME');" <?php echo $disabled;?>>
			<?php
			
			/*****************/
			include('../arrays_libelle/array_selor.php');

			foreach($array_selor as $key => $value)
			{
				//$new_key=str_replace('"', '', $key);
				
				if($id_selor==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_selor[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_selor[$key]['F'].'</option>';
				}
			}
			
			?>
		</select>
		</td>	
		<td><?php echo dico("selor_supplementaire?","F");?> </td>
		<td>
	
		<select id="zone_libre_selor" name="zone_libre_selor" onchange="SetColorButton('FORM_DIPLOME');" <?php echo $disabled;?>>
			<?php
			
			/*****************/
			//include('../arrays_libelle/array_selor.php');

			foreach($array_selor as $key2 => $value2)
			{
				//$new_key=str_replace('"', '', $key);
				
				if($zone_libre_selor==$key2)
				{
					echo '<option value="'.$key2.'" selected>'.$array_selor[$key2]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key2.'">'.$array_selor[$key2]['F'].'</option>';
				}
			}
			
			?>
		</select>
		</td>
	</tr>
	
	<tr>
		<td><?php echo dico("prime_linguistique","F");?></td>
		<td>
		<select id="prime_linguistique" name="prime_linguistique" value="<?php echo $prime_linguistique;?>" onchange="SetColorButton('FORM_DIPLOME');" <?php echo $disabled;?>/>
		
			<?php
			if($prime_linguistique=='OUI')
			{
			?>
				<option value="NON"><?php echo dico("NON","F");?></option>
				<option value="OUI" selected><?php echo dico("OUI","F");?></option>
			<?php
			}
			else
			{
			?>	
				<option value="NON" selected><?php echo dico("NON","F");?></option>
				<option value="OUI"><?php echo dico("OUI","F");?></option>
				
			<?php
			}
			?>
			</select>
		
		</td>
	</tr>
	
</table>	
	
	
	<!--<input type="hidden" id="id_registre" name="id_registre" value="<?php echo $id_registre;?>" />-->
	<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
	
	
	<p align="center">
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_DIPLOME');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_DIPLOME')" value="<?php echo dico("reset","F");?>" />

	</p>
</form>



