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
		select * from cpas_places_cadre where id_cadre=".$id_cadre." and type_cadre=2 and statut='N' order by creation_date desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Il n'y a pas encore de place pour ce cadre. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter une place au cadre.';
	echo "<br><br>";
	
	exit;
}

mysqli_close($lien);


$tab_places_cadre=fn_ResultToArray($result,'id_place_cadre');


include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_type_cadre.php');

//Mvt effectué
if($tab_places_cadre!=null)
{
	
	echo '<div>';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
		echo '<td class="td_list" style="width:150px;padding:5px;text-align:center;">Liste du cadre dirigeant</td>';
		
		//echo '<td class="td_list" style="width:22px;padding:5px;text-align:center;"></td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Créé par</td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Modifié par</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';



	echo '<div style="height:120px;overflow:auto;width:100%;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	foreach($tab_places_cadre as $key => $value)
	{	
			echo '<tr class="td_list_content" >';
			echo '<td style="width:204px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$value['article_budgetaire'].'</td>';
			echo '<td style="width:80px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_grade[$value['id_grade']]['F'].'</td>';
			echo '<td style="width:80px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_fonction[$value['id_fonc']]['F'].'</td>';
			if(($value['id_code']==0)||($value['id_code']=='')||($value['id_code']==null))
			{
				echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_bareme[$value['id_bareme']].'</td>';
			}
			else
			{
				echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].'</td>';
			}
			//echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$array_code[$value['id_code']].'</td>';
			echo '<td style="width:54px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" >'.$value['id_equiv_tp'].'</td>';
			
			
			echo '<td style="width:45px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;">
			<span class="bnt_modif" onclick="DisplayFormCadreDirigeant('.$value['id_place_cadre'].','.$value['id_cadre'].');" title="Cliquez pour modifier cet enregistrement" alt="Cliquez pour modifier cet enregistrement"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer cet enregistrement" onclick="SupprPlaceCadre('.$value['id_place_cadre'].','.$value['id_cadre'].',2);"></span>
			
			</td>';
			echo '</tr>';
		
	}
	echo '</table>';
	
	echo '</div>';
}

?>