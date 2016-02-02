<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

include('params.php');


if (isset($_GET['choix_groupe']))
 $choix_groupe = trim($_GET['choix_groupe']);
else
{
 if (isset($_POST['choix_groupe']))
  $choix_groupe = trim($_POST['choix_groupe']);
 else
  $choix_groupe = '';
}

if($choix_groupe == '')
{
	echo '<span style="color:red;">Veuillez sélectionner un groupe hiérarchique</span>';
	exit;
}

if($choix_groupe=="DEP")
{
	if($id_dep==0)
	{
		echo '<span style="color:red;">Veuillez sélectionner un département</span>';
		exit;
	}

	if($id_ser==0)
	{
		echo '<span style="color:red;">Veuillez sélectionner un service</span>';
		exit;
	}
}
else
{
	if($id_hors_dep==0)
	{
		echo '<span style="color:red;">Veuillez sélectionner un service hors-département</span>';
		exit;
	
	}

}

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

if($choix_groupe=="DEP")
{
$sql="
		select * from cpas_places_cadre where id_cadre=".$id_cadre." and id_dep=".$id_dep." and id_ser=".$id_ser." and type_cadre=1 and statut='N' order by creation_date desc;
";
}
else
{
	$sql="
		select * from cpas_places_cadre where id_cadre=".$id_cadre." and id_hors_dep=".$id_hors_dep." and type_cadre=1 and statut='N' order by creation_date desc;
	";
}
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Il n'y a pas encore de place pour ce cadre. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter une place au cadre.';
	echo "<br><br>";
	if($choix_groupe=="DEP")
	{
		echo '<input type="button" onclick="DisplayFormCadreStandard(0,'.$id_cadre.',0,'.$id_dep.','.$id_ser.');" value="Ajout place"/>';
	}
	else
	{
		echo '<input type="button" onclick="DisplayFormCadreStandard(0,'.$id_cadre.','.$id_hors_dep.',0,0);" value="Ajout place"/>';
	
	}
	exit;
}

mysqli_close($lien);


$tab_places_cadre=fn_ResultToArray($result,'id_place_cadre');


include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_type_cadre.php');

//Mvt effectué
if($tab_places_cadre!=null)
{
	
	echo '<div class="td_list" style="padding:5px;">';
	if($choix_groupe=="DEP")
	{
		echo 'Liste du cadre pour le service <span style="font-weight:bold;">'.$array_departement[$id_dep]['F'].' / '.$array_service[$id_ser]['F'].'</span>';
	}
	else
	{
		echo 'Liste du cadre pour le service <span style="font-weight:bold;">'.$array_hors_departement[$id_hors_dep]['F'].'</span>';
	
	}
	echo '</div>';



	echo '<div style="height:80px;overflow:auto;width:100%;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	foreach($tab_places_cadre as $key => $value)
	{	
			echo '<tr class="td_list_content" >';
			echo '<td style="width:204px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$value['article_budgetaire'].'</td>';
			//echo '<td style="width:304px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$value['grade_fonction'].'</td>';
			//echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_bareme[$value['id_bareme']].'</td>';
			//echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_code[$value['id_code']].'</td>';
			if(($value['id_code']==0)||($value['id_code']=='')||($value['id_code']==null))
			{
				echo '<td style="width:80px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_bareme[$value['id_bareme']].'</td>';
			}
			else
			{
				echo '<td style="width:80px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].'</td>';
			}
			echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_grade[$value['id_grade']]['F'].'</td>';
			echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_fonction[$value['id_fonc']]['F'].'</td>';
			echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$value['id_equiv_tp'].'</td>';
			
			echo '<td style="width:45px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;">
			<span class="bnt_modif" onclick="DisplayFormCadreStandard('.$value['id_place_cadre'].','.$value['id_cadre'].','.$value['id_hors_dep'].','.$value['id_dep'].','.$value['id_ser'].');" title="Cliquez pour modifier cet enregistrement" alt="Cliquez pour modifier cet enregistrement"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer cet enregistrement" onclick="SupprPlaceCadre('.$value['id_place_cadre'].','.$value['id_cadre'].',1);"></span>
			
			</td>';
			echo '</tr>';
		
	}
	echo '</table>';
	
	echo '</div>';
}
echo '<input type="button" onclick="DisplayFormCadreStandard(0,'.$id_cadre.','.$id_hors_dep.','.$id_dep.','.$id_ser.');" value="Ajout place"/>';
	
?>