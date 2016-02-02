<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
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

/*******************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	//verifier validité de $result
	if($result==null)
	{
		echo "pas de parametre result";
		return false;
	}
	
	$tableau=array();
	while($datas = mysqli_fetch_assoc($result))
	{
		if($id_key_unic==null)
		{
			$tableau[]=$datas;
		}else{
			$tableau[$datas[$id_key_unic]]=$datas;
		}
		
	}
	return $tableau;
}

include('../connect_db.php');



$sql="
		select * from cpas_mouvements_baremes where id_contrat=".$id_contrat." and statut='N' order by date_debut_bareme desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	//echo '<div class="td_list_title"> >> Mouvement(s) de bareme <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_BAREME\',\'bareme\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';
	echo "Il n'y a pas encore de mouvement encodé pour cet agent. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter un mouvement de barème et grade.';
	echo "<br><br>";
	
	exit;
}

mysqli_close($lien);


$tab_mvt_baremes=fn_ResultToArray($result,'id_mvt_bareme');


include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_type_cadre.php');

//Mvt effectué
if($tab_mvt_baremes!=null)
{
	//echo '<div class="td_list_title"> >> Mouvement(s) de barème <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_BAREME\',\'bareme\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';

	echo '<div style="position:absolute;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Date de début</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Date d\'échéance</td>';
		//echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Id-contrat</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Barème/Code</td>';
		
		echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Grade</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Type de cadre</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">AU CADRE</td>';
		echo '<td class="td_list" style="width:45px;padding:5px;text-align:center;"></td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Créé par</td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Modifié par</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';



	echo '<div style="height:90px;overflow:auto;width:100%;">';
	echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">';
	foreach($tab_mvt_baremes as $key => $value)
	{
		
		
			if($value['actif']==1)
			{
				//echo '<tr style="background-color:#FFD14B;">';
				echo '<tr class="td_list_content" style="background-color:#DFF7CB;">';
			} 
			else
			{
				echo '<tr class="td_list_content">';
			}	
			
			
			echo '<td style="width:95px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_debut_bareme']).'</td>';
			echo '<td style="width:99px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_echeance_bareme']).'</td>';
			//echo '<td style="width:104px;padding:5px;text-align:center;"> C-'.$value['id_contrat'].'</td>';
			echo '<td style="width:99px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].'</td>';
		
			echo '<td style="width:104px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$array_grade[$value['id_grade']]['F'].'</td>';
			echo '<td style="width:99px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$array_type_cadre[$value['type_cadre']].'</td>';
			echo '<td style="width:99px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$array_bareme[$value['id_bareme_cadre']].$array_code[$value['id_code_cadre']].'</td>';
			echo '<td style="width:45px;padding:5px;text-align:center;">
			<span class="bnt_modif" title="Cliquer pour corriger ce mouvement" onclick="DisplayFormMvt(\'DIV_FORM_MVT_BAREME\',\'bareme\','.$value['id_mvt_bareme'].','.$id_agent.','.$id_contrat.');"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer ce mouvement" onclick="SupprMvt(\'bareme\','.$value['id_mvt_bareme'].','.$id_agent.','.$id_contrat.');"></span>
			
			</td>';
			
			//echo '<td style="width:64px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
			//echo '<td style="width:70px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
			echo '</tr>';
		
	}
	echo '</table>';
	
	echo '</div>';
}

?>