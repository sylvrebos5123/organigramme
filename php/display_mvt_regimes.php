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
		select * from cpas_mouvements_regimes where id_contrat=".$id_contrat." and statut='N' order by date_debut_regime desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	//echo '<div class="td_list_title"> >> Mouvement(s) de régime <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_REGIME\',\'regime\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';
	echo "Il n'y a pas encore de mouvement encodé pour cet agent. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter un mouvement de régime.';
	echo "<br><br>";
	
	exit;
}

mysqli_close($lien);


$tab_mvt_regimes=fn_ResultToArray($result,'id_mvt_regime');


include('../arrays_libelle/array_regime.php');
include('../arrays_libelle/array_equivalent_temps_plein.php');


if($tab_mvt_regimes!=null)
{
//Mvt 
	//echo '<div class="td_list_title"> >> Mouvement(s) de régime <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_REGIME\',\'regime\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';
		
	echo '<div style="position:absolute;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
		echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Date de début</td>';
		//echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Id-contrat</td>';
		echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Date d\'échéance</td>';
		echo '<td class="td_list" style="width:120px;padding:5px;text-align:center;">Régime</td>';
		echo '<td class="td_list" style="width:150px;padding:5px;text-align:center;">Equivalent temps plein</td>';
		echo '<td class="td_list" style="width:130px;padding:5px;text-align:center;"></td>';
		echo '<td class="td_list" style="width:35px;padding:5px;text-align:center;"></td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Créé par</td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Modifié par</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';



	echo '<div style="height:90px;overflow:auto;width:100%;">';
	echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">';
	foreach($tab_mvt_regimes as $key => $value)
	{
			if($value['actif']==1)
			{
				echo '<tr class="td_list_content" style="background-color:#DFF7CB;">';
			} 
			else
			{
				echo '<tr class="td_list_content">';
			}
			
			echo '<td style="width:100px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_debut_regime']).'</td>';
			
			echo '<td style="width:104px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_echeance_regime']).'</td>';
			echo '<td style="width:124px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$array_regime[$value['id_regime']]['F'].'</td>';
			
			echo '<td style="width:154px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['id_equiv_tp'].'</td>';
			echo '<td style="width:130px;padding:5px;text-align:center;border-bottom:1px solid #ddd;"></td>';
			echo '<td style="width:45px;padding:5px;text-align:center;">
			<span class="bnt_modif" title="Cliquer pour corriger ce mouvement" onclick="DisplayFormMvt(\'DIV_FORM_MVT_REGIME\',\'regime\','.$value['id_mvt_regime'].','.$id_agent.','.$id_contrat.');"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer ce mouvement" onclick="SupprMvt(\'regime\','.$value['id_mvt_regime'].','.$id_agent.','.$id_contrat.');"></span>
			
			</td>';
			//echo '<td style="width:64px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
			//echo '<td style="width:70px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
			echo '</tr>';
		
	}
	echo '</table>';
	echo '</div>';
}

?>