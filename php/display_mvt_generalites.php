<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
include('params.php');

echo "
<style>
.td_list_gen
	{
		cursor:default;
		border-style:solid;
		border-width:1px;
		border-color:#fff;
		width=180px;
		height=20px;
		margin:0px;
		padding:2px;
		/*color:#FFF;*/
		background: #aaa url('./images/white-highlight.png') top left repeat-x;
	}
	.td_list_hover
	{
		cursor:default;
		border-style:solid;
		border-width:1px;
		border-color:#ccc;
		width=180px;
		height=20px;
		margin:0px;
		padding:2px;
		background-color:#ddd;
	}
</style>
";

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
		select * from cpas_contrats where id_agent=".$id_agent." order by id_contrat desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Aucun contrat créé";
	exit;
}
/*else
{*/
mysqli_close($lien);


$tab_mvt_contrats=fn_ResultToArray($result,'id_contrat');

//include('../arrays_libelle/array_departement.php');
//include('../arrays_libelle/array_service.php');
//include('../arrays_libelle/array_cellule.php');
//include('../arrays_libelle/array_code.php');


echo '<div style="position:fixed;">';
echo '<table border="0" cellpadding="0" cellspacing="0">';
echo '<tr>';
	//echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Date Création</td>';
	echo '<td class="td_list_gen" style="width:100px;padding:5px;text-align:center;">Id-contrat</td>';
	echo '<td class="td_list_gen" style="width:100px;padding:5px;text-align:center;">Date entrée</td>';
	echo '<td class="td_list_gen" style="width:100px;padding:5px;text-align:center;">Date sortie</td>';
	echo '<td class="td_list_gen" style="width:200px;padding:5px;text-align:center;">Motif sortie</td>';
	echo '<td class="td_list_gen" style="width:100px;padding:5px;text-align:center;">Statut</td>';
	//echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Equivalent temps plein</td>';
	//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Créé par</td>';
	//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Modifié par</td>';
echo '</tr>';
echo '</table>';
echo '</div>';

$i=0;

echo '<div id="LIST_HISTO_CONTRATS" style="height:60px;overflow:auto;width:677px;">';
echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:30px;">';
foreach($tab_mvt_contrats as $key => $value)
{
	$i++;
	
	//$tab_cel=explode('-',$array_cellule[$value['id_cel']]['F']);
	
	if(($i%2)==0)
	{
		echo '<tr style="background-color:white;">';
	}
	else
	{
		echo '<tr>';
	}
	//echo '<td style="width:100px;padding:5px;text-align:center;">'.transformDate($value['creation_date']).'</td>';
	echo '<td style="width:104px;padding:5px;text-align:center;"> C-'.$value['id_contrat'].'</td>';
	echo '<td style="width:104px;padding:5px;text-align:center;">'.transformDate($value['start_date']).'</td>';
	echo '<td style="width:104px;padding:5px;text-align:center;">'.transformDate($value['end_date']).'</td>';
	echo '<td style="width:220px;padding:5px;text-align:center;">'.$value['motif_sortie'].'</td>';
	
	if($value['actif']==1)
	{
		echo '<td style="width:104px;padding:5px;text-align:center;color:green;">Actif</td>';
	}
	else
	{
		echo '<td style="width:104px;padding:5px;text-align:center;color:red;">Inactif</td>';
	}
	//echo '<td style="width:120px;padding:5px;text-align:center;">'.$array_equivalent_temps_plein[$value['equiv_tp']].'</td>';
	//echo '<td style="width:64px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
	//echo '<td style="width:70px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
	echo '</tr>';
}
echo '</table>';
echo '</div>';
//}
?>