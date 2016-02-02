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
		select * from cpas_cadres where statut='N' order by date_situation desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo '<div style="width:160px;">';
	echo "<br><br>";
	echo "Il n'y a pas encore de situation de cadre encodée. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter un nouveau cadre.';
	echo "<br><br>";
	echo '</div>';
	exit;
}

mysqli_close($lien);


$tab_cadres=fn_ResultToArray($result,'id_cadre');


/*include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_grade.php');*/
include('../arrays_libelle/array_type_cadre.php');

//Mvt effectué
if($tab_cadres!=null)
{
	
	echo '<div style="width:160px;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
		echo '<td class="td_list_title" style="width:150px;padding:5px;text-align:center;">Liste des cadres</td>';
		
		//echo '<td class="td_list" style="width:22px;padding:5px;text-align:center;"></td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Créé par</td>';
		//echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Modifié par</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';



	echo '<div style="height:90px;overflow:auto;width:100%;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	foreach($tab_cadres as $key => $value)
	{	
			echo '<tr class="td_list_content">';
			echo '<td style="width:154px;padding:5px;text-align:center;border-left:#ddd 1px solid;border-bottom:1px solid #ddd;" onclick="DisplayFormDateCadre('.$value['id_cadre'].');">'.transformDate($value['date_situation']).'</td>';
			
			//echo '<td style="width:22px;padding:5px;text-align:center;border-bottom:1px solid #ddd;"></td>';
			
			//echo '<td style="width:64px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
			//echo '<td style="width:70px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
			echo '</tr>';
		
	}
	echo '</table>';
	
	echo '</div>';
}

?>