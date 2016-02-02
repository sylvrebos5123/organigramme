<?php
//ob_clean();
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

/********************TITRE*********/
/* echo "<br><h3>Historique des mouvements contenus dans le contrat</h3><br>		
<h2>Encodez différentes situations dans le temps afin de garder toutes traces d'évolution de carrière d'un agent. </h2>";
 */
 /*******/

include('../connect_db.php');


$sql="
		select * from cpas_mouvements_services where id_contrat=".$id_contrat." and statut='N' order by date_debut_service desc,id_mvt_service desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	
	//echo '<div class="td_list_title"> >> Mouvement(s) de service <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_SER\',\'service\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';

	echo "Il n'y a pas encore de mouvement encodé pour cet agent. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter un mouvement de service.';
	echo "<br><br>";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'service\',\''.$id_agent.'\');" />';

	exit;
}
/*else
{*/
mysqli_close($lien);

//$dernier_mvt_services=mysqli_fetch_assoc($result);
$tab_mvt_services=fn_ResultToArray($result,'id_mvt_service');



/***********************************************************/
include('../arrays_libelle/array_article_budgetaire.php');
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
//$tab_cel=array();
if($tab_mvt_services!=null)
{
	//echo '<div class="td_list_title"> >> Mouvement(s) de service <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_SER\',\'service\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';
	echo '<div style="position:absolute;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Date de début</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Date d\'échéance</td>';
		//echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Id-contrat</td>';
		echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Article budgétaire</td>';
		echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Dep./Hors dep.</td>';
		echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Service</td>';
		echo '<td class="td_list" style="width:110px;padding:5px;text-align:center;">Cellule</td>';
		echo '<td class="td_list" style="width:12px;padding:5px;text-align:center;"></td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Créé par</td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Modifié par</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';

	
		$load_groupe_dep='';
		//corps tableau
		echo '<div style="height:90px;overflow:auto;width:100%;">';
		echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">';
		foreach($tab_mvt_services as $key => $value)
		{
			if(($value['id_dep']=='')||($value['id_dep']==null)||($value['id_dep']==0))
			{
				$load_groupe_dep="DisplayChoixDep('FORM_MVT_SERVICE','HORS_DEP');";
			}
			else
			{
				$load_groupe_dep="DisplayChoixDep('FORM_MVT_SERVICE','DEP');";
			}
			
			if($value['actif']==1)
			{
				
				//echo '<tr style="background-color:#FFD14B;">';
				echo '<tr class="td_list_content" style="background-color:#DFF7CB;"  
				onclick="'.$load_groupe_dep.'">';
			} 
			else
			{
				echo '<tr class="td_list_content" 
				onclick="'.$load_groupe_dep.'">';
			}
				
			echo '<td style="width:95px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_debut_service']).'</td>';
			echo '<td style="width:99px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_echeance_service']).'</td>';
			//echo '<td style="width:104px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;"> C-'.$value['id_contrat'].'</td>';
			$array_art=explode('-',$array_article_budgetaire[$value['id_article_budgetaire']]['F']);
			echo '<td style="width:104px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">'.$array_art[0].'</td>';
			echo '<td style="width:104px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">';
			if(($value['id_dep']=='')||($value['id_dep']==null)||($value['id_dep']==0))
			{
				echo $array_hors_departement[$value['id_hors_dep']]['F'];
			}
			else
			{
				echo $array_departement[$value['id_dep']]['F'];
			}
			
			echo '</td>';
			echo '<td style="width:104px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">'.$array_service[$value['id_ser']]['F'].'</td>';
			echo '<td style="width:114px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">'.$array_cellule[$value['id_cel']]['F'].'</td>';
			echo '<td style="width:45px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;">
			<span class="bnt_modif" title="Cliquer pour corriger ce mouvement" onclick="DisplayFormMvt(\'DIV_FORM_MVT_SER\',\'service\','.$value['id_mvt_service'].','.$id_agent.','.$id_contrat.');"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer ce mouvement" onclick="SupprMvt(\'service\','.$value['id_mvt_service'].','.$id_agent.','.$id_contrat.');"></span>
			</td>';
			
			//echo '<td style="width:12px;padding:5px;vertical-align:middle;text-align:center;border-bottom:1px solid #ddd;"></td>';
			
			//echo '<td style="width:64px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
			//echo '<td style="width:70px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
			echo '</tr>';
			
		}
		echo '</table>';
		echo '</div>';
}

//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'service\',\''.$id_agent.'\');" />';
?>

