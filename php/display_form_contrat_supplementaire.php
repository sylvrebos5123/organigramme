<?php
//ob_clean();

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
//$dico_script='\\annuaire\\tools\\php_dico_array.php';
//include_once($rootpath.'\\organigramme\\tools\\pba_tools.php'); //function de connection
//include_once($rootpath.'\\organigramme/php/genere_list_form5.php');
//include_once($rootpath.$dico_script); //function de traduction
 //function de traduction
include_once('../tools/function_dico.php');
include('params.php');

/***Fonction qui transforme la date du format français vers le format américain ou inversément***************************/
function transformDate($date)
{
	$date_result='';
	if(($date=='')||($date==null))
	{
		$date_result='';
	}
	else
	{
		$tab_date=array();
		$tab_date=explode("-", $date);
		$date_result=$tab_date[2].'-'.$tab_date[1].'-'.$tab_date[0];
		
		//return $date_result;
	}
	return $date_result;
}


$disabled="";



/************************/  


if($id_contrat==0)
{
	echo '<h1>Ajout de contrat</h1><br>';
	
	
	$start_date='';
	$end_date='';
	$motif_sortie='';
	
	//$actif=1;
	$lien="./php/ajout_contrat_supplementaire.php";
	
}
else
{
	echo '<h1>Aperçu du contrat N°'.$id_contrat.'</h1><br>';
	
	include('../connect_db.php');

	$sql="SELECT * FROM cpas_contrats where id_contrat=".$id_contrat.";";

	//var_dump($sql);
	$result=mysqli_query($lien,$sql);
	//ferme la connection//
	mysqli_close($lien);

	$tab_contrats=mysqli_fetch_assoc($result);
	
	foreach($tab_contrats as $key => $value)
	{
		
		$start_date=transformDate($tab_contrats['start_date']);
		$end_date=transformDate($tab_contrats['end_date']);
		$motif_sortie=$tab_contrats['motif_sortie'];
		//$actif=$tab_contrats['actif'];
		
		$lien="./php/modif_contrat_supplementaire.php";
	}
}


//include('display_form_services.php');
/* include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_fonction.php'); */
//var_dump($array_departement);

?>

<div style="background-color:#E4F8D2;"> 
<form id="FORM_CONTRAT_SUP" name="FORM_CONTRAT_SUP" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">

<table border="1" cellspacing="3" cellpadding="3">

	<tr><td colspan="2"><h3>Généralités</h3></td></tr>
	<tr>
		<td><?php echo dico("start_date","F");?> : </td>
		<td><input type="text" id="start_date" name="start_date" size="25" onchange="SetColorButton('FORM_CONTRAT_SUP');" onkeyup="SetColorButton('FORM_CONTRAT_SUP');" value="<?php echo $start_date;?>" <?php echo $disabled;?>/> 
		<span style="margin-left:12px;"><?php echo dico("end_date","F");?> : </span>
		<input type="text" id="end_date" name="end_date" size="25" onchange="SetColorButton('FORM_CONTRAT_SUP');" onkeyup="SetColorButton('FORM_CONTRAT_SUP');" value="<?php echo $end_date;?>" <?php echo $disabled;?>/></td>
	</tr>
	<tr>
		<td><?php echo dico("motif_sortie","F");?> : </td>
		<td>
		<input type="text" id="motif_sortie" name="motif_sortie" style="width:100%" onchange="SetColorButton('FORM_CONTRAT_SUP');" onkeyup="SetColorButton('FORM_CONTRAT_SUP');" value="<?php echo $motif_sortie;?>" <?php echo $disabled;?>/>
		</td>
	</tr>
	<!--<tr>
			<td><?php echo dico("Contrat","F");?> : </td>
			<td>
			<select id="actif" name="actif" onchange="SetColorButton('FORM_CONTRAT_SUP');" onkeyup="SetColorButton('FORM_CONTRAT_SUP');" value="<?php echo $actif;?>" <?php echo $disabled;?>/>
		
			<?php
			if($actif==1)
			{
			?>
				<option value="0">Inactif</option>
				<option value="1" selected>Actif</option>
			<?php
			}
			else
			{
			?>	
				<option value="0" selected>Inactif</option>
				<option value="1">Actif</option>
			<?php
			}
			?>
			</select>
			</td>		
	</tr>-->
</table>

	
	<!--
	<input type="hidden" id="modif_bareme" name="modif_bareme" value="0"/>
	<input type="hidden" id="modif_regime" name="modif_regime" value="0"/>
	<input type="hidden" id="modif_grade" name="modif_grade" value="0"/>
	<input type="hidden" id="modif_code" name="modif_code" value="0"/>
	<input type="hidden" id="modif_statut" name="modif_statut" value="0"/>
		
	<input type="hidden" id="modif_service" name="modif_service" value="0"/>
	<input type="hidden" id="modif_fonction" name="modif_fonction" value="0"/>
	-->
	<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
	<input type="hidden" id="id_contrat" name="id_contrat" value="<?php echo $id_contrat;?>" />	
	
	<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_CONTRAT_SUP');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_CONTRAT_SUP')" value="<?php echo dico("reset","F");?>" />
	</p>
</form>
</div>





	