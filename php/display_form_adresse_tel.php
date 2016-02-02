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



$lien="./php/modif_adresse_tel.php";


?>
 <h1>Gestion des adresses</h1><br>
 
 
 <div id="DIV_LIST_MVT_DOMICILE"></div>
 <input type="button" title="Programmer un nouveau domicile" value="Ajout d'une nouvelle adresse"
	onclick="DisplayFormMvt('DIV_FORM_MVT_DOM','domicile',0,<?php echo $id_agent;?>,0);"/>
	
 <div id="DIV_FORM_MVT_DOM"></div>
 <br>
 
 
<form id="FORM_ADRESSE_TEL" name="FORM_ADRESSE_TEL" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
<!--<span onclick="Corriger('FORM_SERVICE');">Corriger</span><br>-->
<h1>Téléphone privé</h1><br>
<table>	
	<!--
	<tr>
		<td><?php echo dico("adresse_domicile","F");?> : </td>
		<td colspan="3">
		<input type="text" id="adresse_domicile" name="adresse_domicile" size="50" value="<?php echo $adresse_domicile;?>" onkeyup="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');" <?php echo $disabled;?>>
		</td>
	</tr>
	
	<tr>	
		<td>
		<?php echo dico("num_domicile","F");?> : 
		</td>
		<td>
		<input type="text" id="num_domicile" name="num_domicile" size="4" value="<?php echo $num_domicile;?>" onkeyup="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');" <?php echo $disabled;?>>
		</td>
		
		<td><?php echo dico("bte_domicile","F");?> : </td>
		<td>
		<input type="text" id="bte_domicile" name="bte_domicile" size="4" value="<?php echo $bte_domicile;?>" onkeyup="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');" <?php echo $disabled;?>>
			
		</td>
	</tr>
	<tr>
		<td><?php echo dico("code_postal","F");?> : </td>
		<td>
		<input type="text" id="code_postal" name="code_postal" value="<?php echo $code_postal;?>" onkeyup="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');"/>
		</td>
		
		<td><?php echo dico("localite","F");?> : </td>
		<td>
		<input type="text" id="localite" name="localite" size="32" value="<?php echo $localite;?>" onkeyup="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');"/>
		</td>	
	</tr>
	
	<tr>
		<td>
		<?php echo dico("region","F");?> : 
		</td>
		<td>
		<input type="text" id="region" name="region" value="<?php echo $region;?>" onkeyup="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');"/>
		</td>
		<td>
		<select id="bxl_hbxl" name="bxl_hbxl" onchange="SetValue('modif_domicile',1,'FORM_ADRESSE_TEL');">
		<?php
		include('../arrays_libelle/array_bxl_hbxl.php');
			for($i=0;$i<count($array_bxl_hbxl);$i++)
			{
				//echo $array_ouvrier_employe[$key];
				if($bxl_hbxl==$array_bxl_hbxl[$i])
				{
					echo '<option value="'.$array_bxl_hbxl[$i].'" selected>'.$array_bxl_hbxl[$i].'</option>';
				}
				else
				{
					echo '<option value="'.$array_bxl_hbxl[$i].'">'.$array_bxl_hbxl[$i].'</option>';
				}
			}
		?>
		</select>
		</td>
	</tr>
	-->
	
	<tr>
		<td><?php echo dico("tel_prive","F");?> : </td>
		<td>
		<input type="text" id="tel_prive" name="tel_prive" size="30" onkeyup="SetValue('modif_tel',1,'FORM_ADRESSE_TEL');" value="<?php echo $tel_prive;?>"/>
		</td>
	</tr>
</table>	

	<input type="hidden" id="modif_domicile" name="modif_domicile" value="0"/>
	<input type="hidden" id="modif_tel" name="modif_tel" value="0"/>
	<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<br>
	<p align="center">
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_ADRESSE_TEL');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_ADRESSE_TEL')" value="<?php echo dico("reset","F");?>" />
	</p>
</form>



