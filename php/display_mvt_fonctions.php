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
		select * from cpas_mouvements_fonctions where id_contrat=".$id_contrat." and statut='N' order by date_debut_fonction desc;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	//echo '<div class="td_list_title"> >> Mouvement(s) de fonction <span style="background-color:#B0F276;color:black;font-weight:bold;height:30px;width:30px;" title="Ajout d\'un mouvement dans le temps" onclick="DisplayFormMvt(\'DIV_FORM_MVT_FCT\',\'fonction\','.$id_agent.','.$id_contrat.');">&nbsp;+&nbsp;</span></div>';
	echo "Il n'y a pas encore de mouvement encodé pour cet agent. "; 
	echo 'Cliquez sur le bouton "+" pour ajouter un mouvement de fonction.';
	echo "<br><br>";
	
	exit;
}

mysqli_close($lien);


$tab_mvt_fonctions=fn_ResultToArray($result,'id_mvt_fonction');

include('../arrays_libelle/array_fonction.php');



if($tab_mvt_fonctions!=null)
{
	
	echo '<div style="position:absolute;">';
	echo '<table border="0" cellpadding="0" cellspacing="0">';
	echo '<tr>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Date de début</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Date d\'échéance</td>';
		//echo '<td class="td_list" style="width:100px;padding:5px;text-align:center;">Id-contrat</td>';
		echo '<td class="td_list" style="width:155px;padding:5px;text-align:center;">Fonction</td>';
		echo '<td class="td_list" style="width:55px;padding:5px;text-align:center;">O/E</td>';
		echo '<td class="td_list" style="width:95px;padding:5px;text-align:center;">Catégorie</td>';
		echo '<td class="td_list" style="width:80px;padding:5px;text-align:center;">Resp.</td>';
		echo '<td class="td_list" style="width:45px;padding:5px;text-align:center;"></td>';	
		//echo '<td class="td_list" style="width:110px;padding:5px;text-align:center;">Créé par</td>';
		//echo '<td class="td_list" style="width:110px;padding:5px;text-align:center;"">Modifié par</td>';
	echo '</tr>';
	echo '</table>';
	echo '</div>';

	//$i=0;

	$date_auj=date('Y-m-d');
	$date_la_plus_récente="";
	//echo $date_auj;

	echo '<div style="height:90px;overflow:auto;width:100%;">';

	echo '<table border="0" cellpadding="0" cellspacing="0" style="margin-top:25px;">';

	foreach($tab_mvt_fonctions as $key => $value)
	{
		
			//if(($value['date_debut_fonction'] <= $date_auj)&&($value['date_echeance_fonction'] >= $date_auj))
			if($value['actif']==1)
			{
				//echo '<tr style="background-color:#FFD14B;">';
				echo '<tr " class="td_list_content" style="background-color:#DFF7CB;vertical-align:middle;">';
			} 
			else
			{
				echo '<tr class="td_list_content" >';
			}
			echo '<td align="middle" style="width:95px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_debut_fonction']).'</td>';
			echo '<td style="width:99px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.transformDate($value['date_echeance_fonction']).'</td>';
			
			//echo '<td style="width:104px;padding:5px;text-align:center;">C-'.$value['id_contrat'].'</td>';
			echo '<td style="width:159px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">';
			echo $array_fonction[$value['id_fonc']]['F'];
			/*if(($value['zone_libre_fonction']!=0)||($value['zone_libre_fonction']!=''))
			{
				echo '<br>'.$array_fonction[$value['zone_libre_fonction']]['F'];
			}*/
			echo '</td>';
			echo '<td style="width:59px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['ouvrier_employe'].'</td>';
			echo '<td style="width:99px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">'.$value['categorie'].'</td>';
			echo '<td style="width:84px;padding:5px;text-align:center;border-bottom:1px solid #ddd;">';
			
			if($value['flag_resp_dep']==1)
			{
				echo "Resp.dep";
			}
			if($value['flag_resp_ser']==1)
			{
				echo "Resp.ser";
			}
			
			echo '</td>';
			echo '<td style="width:45px;padding:5px;text-align:center;">
			<span class="bnt_modif" title="Cliquer pour corriger ce mouvement" onclick="DisplayFormMvt(\'DIV_FORM_MVT_FCT\',\'fonction\','.$value['id_mvt_fonction'].','.$id_agent.','.$id_contrat.');"></span>
			<span class="bnt_suppr" title="Cliquer pour supprimer ce mouvement" onclick="SupprMvt(\'fonction\','.$value['id_mvt_fonction'].','.$id_agent.','.$id_contrat.');"></span>
			
			</td>';
			//echo '<td style="width:114px;padding:5px;text-align:center;">'.$value['creation_user'].'</td>';
			//echo '<td style="width:128px;padding:5px;text-align:center;">'.$value['modif_user'].'</td>';
			echo '</tr>';
		//}
	}
	echo '</table>';
	echo '</div>';
}
//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'fonction\',\''.$id_agent.'\');" />';
?>