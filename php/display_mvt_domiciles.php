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
		select * from cpas_mouvements_domiciles where id_agent=".$id_agent." and statut='N' order by date_mvt desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Aucun mvt de domicile<br><br>";
	
	exit;
}

mysqli_close($lien);

//$dernier_mvt_domiciles=mysqli_fetch_assoc($result);
$tab_mvt_domiciles=fn_ResultToArray($result,'id_mvt_domicile');





//echo '<div class="td_list_title"> >> Mouvements du passé <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mvt ultérieur" onclick="DisplayFormMvt(\'domicile\',\''.$dernier_mvt_domiciles['id_agent'].'\',\''.$id_contrat.'\');">+</span></div>';
echo '<div style="position:absolute;">';
echo '<table border="0" cellpadding="0" cellspacing="0">';
echo '<tr>';
	echo '<td class="td_list" style="width:90px;padding:5px;text-align:center;">Date mvt</td>';
	echo '<td class="td_list" style="width:155px;padding:5px;text-align:center;">Adresse</td>';
	echo '<td class="td_list" style="width:80px;padding:5px;text-align:center;">Code postal</td>';
	echo '<td class="td_list" style="width:105px;padding:5px;text-align:center;">Localité</td>';
	echo '<td class="td_list" style="width:105px;padding:5px;text-align:center;">Région</td>';
	echo '<td class="td_list" style="width:60px;padding:5px;text-align:center;">Bxl/H.Bxl</td>';
	echo '<td class="td_list" style="width:25px;padding:5px;text-align:center;"></td>';
	//echo '<td class="td_list" style="width:110px;padding:5px;text-align:center;">Créé par</td>';
	//echo '<td class="td_list" style="width:110px;padding:5px;text-align:center;"">Modifié par</td>';
echo '</tr>';
echo '</table>';
echo '</div>';



echo '<div style="height:90px;overflow:auto;width:100%;">';

echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">';

foreach($tab_mvt_domiciles as $key => $value)
{
	//if($value['id_mvt_domicile'] != $dernier_mvt_domiciles['id_mvt_domicile'])
	//{<input type="button" title="Programmer un nouveau domicile" value="Ajout d'une nouvelle adresse"
	
	if($value['actif']==1)
	{
		echo '<tr class="td_list_content" style="background-color:#DFF7CB;">';
	} 
	else
	{
		echo '<tr class="td_list_content">';
	}	
		echo '<td style="width:120px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_mvt']).'</td>';
		echo '<td style="width:159px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">';
		echo $value['adresse_domicile'].' '.$value['num_domicile'].' <br>bte : '.$value['bte_domicile'];
		
		echo '</td>';
		echo '<td style="width:89px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['code_postal'].'</td>';
		echo '<td style="width:109px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['localite'].'</td>';
		echo '<td style="width:109px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['region'].'</td>';
		echo '<td style="width:78px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['bxl_hbxl'].'</td>';
		
		echo '<td style="width:45px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">
			<span class="bnt_modif" title="Cliquer pour corriger ce mouvement" onclick="DisplayFormMvt(\'DIV_FORM_MVT_DOM\',\'domicile\','.$value['id_mvt_domicile'].','.$id_agent.',0);"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer ce mouvement" onclick="SupprMvt(\'domicile\','.$value['id_mvt_domicile'].','.$id_agent.',0);"></span>
			
		</td>';
		//echo '<td style="width:114px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
		//echo '<td style="width:128px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
		echo '</tr>';
	//}
}
echo '</table>';
//}
echo '</div>';

//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'domicile\',\''.$id_agent.'\');" />';

?>