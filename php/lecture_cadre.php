<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);


echo '<style>

td{text-align:center;padding:5px;}

</style>';
//echo '<javascript>';
/***************Fonction qui met le résultat des records dans un array*********************************************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	$tableau=null;
	
	if($result==null)
	{
		echo 'no result';
		return false;
	}
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
/*************/

include('verification.php');
include('params.php');

/**********************************/
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_fonction.php'); 
//include('../arrays_libelle/array_statut.php');
//include('../arrays_libelle/array_regime.php');
/*************/

$date_effectif=transformDate($date_situation_effectifs);

/*******Connexion database***************/
include('../connect_db.php');

/*******Lire places au cadre*****************************/
$sql="
SELECT * from 
cpas_places_cadre
where id_cadre=".$id_cadre." and statut='N' order by type_cadre desc,id_dep asc,id_ser asc;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);


/********Close connexion***************/
mysqli_close($lien);

$tab_cadres=fn_ResultToArray($result,'id_place_cadre');


echo "
<table>
	<tr style='height:50px;padding-top:5px;margin-top:5px;background-color:#E4F8D2;'>
		
		<td>HORS DEP./DEP.</td>
		<td>SERVICE</td>
		<td>BAREME</td>
		<td>FONCTION</td>
		<td>GRADE</td>
		<td>TYPE CADRE</td>
		<td>ARTICLE BUDGETAIRE</td>
		<td>EQUIV. TEMPS PLEIN</td>
	</tr>

";
//echo '<table>';
$i=0;
foreach($tab_cadres as $key=> $value)
{
	$i++;
	if(($i%2)==0)
	{
		echo '<tr style="background-color:#ddd;">';
	}
	else
	{
		echo '<tr style="background-color:#fff;">';
	}
	
	if(($value['id_hors_dep']==0)||($value['id_hors_dep']=='')||($value['id_hors_dep']==null))
	{
		echo '<td>'.$array_departement[$value['id_dep']]['F'].'</td>';
	}
	else	
	{
		echo '<td>'.$array_hors_departement[$value['id_hors_dep']]['F'].'</td>';
	}
	echo '<td>'.$array_service[$value['id_ser']]['F'].'</td>';
	//echo '<td>'.$array_cellule[$value['id_cel']]['F'].'</td>';
	if(($value['id_code']==0)||($value['id_code']=='')||($value['id_code']==null))
	{
		echo '<td>'.$array_bareme[$value['id_bareme']].'</td>';
	}
	else
	{
		echo '<td>'.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].'</td>';
	}
	
	//echo '<td>'.$array_code[$value['id_code']].'</td>';
	echo '<td>'.$array_fonction[$value['id_fonc']]['F'].'</td>';
	echo '<td>'.$array_grade[$value['id_grade']]['F'].'</td>';
	if($value['type_cadre']==1)
	{
		echo '<td>CADRE STANDARD</td>';
	}
	else
	{
		if($value['type_cadre']==2)
		{
			echo '<td>CADRE DIRIGEANT</td>';
		}
	}
	echo '<td>'.$value['article_budgetaire'].'</td>';
	echo '<td>'.$value['id_equiv_tp'].'</td>';
	
	echo '</tr>';
}//fin foreach tab_cadres	
	
	
	
	

echo '</table>';


echo '<javascript>';
echo "
document.getElementById('bnt_gen_cadre').style.visibility='visible';
";
?>
