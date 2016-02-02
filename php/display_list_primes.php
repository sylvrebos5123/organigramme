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
		select * from cpas_primes where id_agent=".$id_agent." and statut='N' ;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Aucune prime n'existe pour cet agent. <br><br>";
	
	exit;
}

mysqli_close($lien);


$tab_primes=fn_ResultToArray($result,'id_prime');



include('../arrays_libelle/array_type_prime.php');

echo '<div style="position:absolute;">';
echo '<table border="0" cellpadding="0" cellspacing="0">';
echo '<tr>';
	echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Date d\'octroi</td>';
	echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Date de clôture</td>';
	echo '<td class="td_list" style="width:300px;padding:5px;text-align:center;">Type de prime</td>';
	echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Actif/Inactif</td>';
	echo '<td class="td_list" style="width:45px;padding:5px;text-align:center;"></td>';
	
echo '</tr>';
echo '</table>';
echo '</div>';



echo '<div style="height:90px;overflow:auto;width:100%;">';

echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">';

$actif="";
$style="";
foreach($tab_primes as $key => $value)
{	
	
	//if($value['actif']==1)
	//{
	//	echo '<tr class="td_list_content" style="background-color:#DFF7CB;">';
	//} 
	//else
	//{
		echo '<tr class="td_list_content">';
	//}	
		echo '<td style="width:100px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_octroi']).'</td>';
		echo '<td style="width:104px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_cloture']).'</td>';
		echo '<td style="width:304px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$array_type_prime[$value['id_type_prime']]['F'].'</td>';
		// prime active ou inactive (clôturée ou pas encore activée)
		if($value['actif']==0)
		{
			$style="font-style:italic;";
			$actif="Inactif";
		}
		else
		{
			$style="color:#1a7c1e;font-weight:bold;";
			$actif="Actif";
		}
		
		echo '<td style="width:104px;padding:5px;text-align:center;border-bottom:1px solid #ddd;'.$style.'">'.$actif.'</td>';
		
		
		echo '<td style="width:45px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">
			<span class="bnt_modif" title="Cliquer pour corriger ce mouvement" onclick="DisplayFormPrime('.$value['id_prime'].','.$id_agent.','.$value['id_type_prime'].');"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer ce mouvement" onclick="SupprPrime('.$value['id_prime'].','.$id_agent.');"></span>
			
			</td>';
		
		echo '</tr>';
	
}
echo '</table>';

echo '</div>';


?>