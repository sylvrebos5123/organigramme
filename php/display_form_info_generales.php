<?php

$disabled="";
$disabled_id_registre="";

if($id_agent==0)
{
	
	$lien="./php/ajout_info_generales.php";
}
else
{

/*********Lecture de tous les champs des tables cpas_agents et cpas_signaletiques_agents***********************************/
	
	
	$lien="./php/modif_info_generales.php";
	$disabled_id_registre="disabled";
	
}
?>


<form id="FORM_INFO_GN" name="FORM_INFO_GN" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	<table cellspacing="3" cellpadding="3">
		<tr>
		<td colspan="2"><h1>Informations générales</h1><br></td>
		</tr>
		<tr>
			<td>Id-Registre : </td>
			<td><input type="text" id="id_registre" name="id_registre" size="30" value="<?php echo $id_registre;?>" <?php echo $disabled;?> <?php echo $disabled_id_registre;?>/></td> 
		
		</tr>
		<tr>
			<td><?php echo dico("nom","F");?> : </td>
			<td><input type="text" id="nom" name="nom" size="30" value="<?php echo $nom;?>" <?php echo $disabled;?>/></td>   
			<td><?php echo dico("prenom","F");?> : </td>
			<td><input type="text" id="prenom" name="prenom" size="30" value="<?php echo $prenom;?>" <?php echo $disabled;?>/></td>
		</tr>
		<tr>
			<td><?php echo dico("civilite","F");?> : </td>
			<td><select id="id_civilite" name="id_civilite" <?php echo $disabled;?>>
				<?php
				include('../arrays_libelle/array_civilite.php');
				
				foreach($array_civilite as $key => $value)
				{
					
					if($id_civilite==$key)
					{
						echo '<option value="'.$key.'" selected>'.$array_civilite[$key]['F'].'</option>';
					}
					else
					{
						echo '<option value="'.$key.'">'.$array_civilite[$key]['F'].'</option>';
					}
				}
				
				
				?>
			</select>
			</td>
			<td><?php echo dico("initiales","F");?> : </td>
			<td><input type="text" id="initiales" name="initiales" size="5" value="<?php echo $initiales;?>" <?php echo $disabled;?>/></td>
			
		</tr>
		
		<tr>
			<td>
			<?php echo dico("genre","F");?> : 
			</td>
			<td>
			<select id="genre" name="genre" <?php echo $disabled;?>>
				<?php
				if($genre==1)
				{
				?>
					<option value="1" selected><?php echo dico("homme","F");?></option>
					<option value="2"><?php echo dico("femme","F");?></option>
				<?php
				}
				else
				{
				?>	
					<option value="1"><?php echo dico("homme","F");?></option>
					<option value="2" selected><?php echo dico("femme","F");?></option>
				<?php
				}
				?>	
			</select>
			</td>
			<td><?php echo dico("langue","F");?> : </td>
			<td>
				<select id="langue" name="langue" <?php echo $disabled;?>>
				<?php
				if($langue=='F')
				{
				?>
					<option value="F" selected>F</option>
					<option value="N">N</option>
				<?php
				}
				else
				{
				?>	
					<option value="F">F</option>
					<option value="N" selected>N</option>
				<?php
				}
				?>	
				</select>
			</td>
		</tr>
		
		
		<tr>
			<td><?php echo dico("niss","F");?> : </td>
			<td><input type="text" id="niss" name="niss" size="30" value="<?php echo $niss;?>" <?php echo $disabled;?>/></td>
			<td><?php echo dico("date_naissance","F");?> : </td>
			<td><input type="text" id="date_naissance" name="date_naissance" size="30" value="<?php echo $date_naissance;?>" <?php echo $disabled;?>/></td>
		</tr>
		
		<tr>
			
			<td><?php echo dico("nationalite","F");?> : </td>
			<td><input type="text" id="nationalite" name="nationalite" size="30" value="<?php echo $nationalite;?>" <?php echo $disabled;?>/></td>
		</tr>
		
		
	</table>
		<input type="hidden" id="nom_champ" name="nom_champ" value="<?php echo $nom_champ;?>" />
		<input type="hidden" id="valeur_champ" name="valeur_champ" value="<?php echo $valeur_champ;?>" />
		<input type="hidden" id="id_dep" name="id_dep" value="<?php echo $id_dep;?>" />
		<input type="hidden" id="id_ser" name="id_ser" value="<?php echo $id_ser;?>" />
		<input type="hidden" id="id_cel" name="id_cel" value="<?php echo $id_cel;?>" />
		<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<br>
	<p align="center">	
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_INFO_GN');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_INFO_GN')" value="<?php echo dico("reset","F");?>" />
	</p>
</form>


