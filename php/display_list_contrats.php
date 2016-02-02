<?php
ob_clean();

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

 //function de traduction
include_once('../tools/function_dico.php');

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
/**********************/

$disabled="";

include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_fonction.php'); 


/**********Params***************************************/
include('params.php');
/********************************/

include('../connect_db.php');

$sql="SELECT * FROM cpas_contrats 
	
	where id_agent=".$id_agent." and statut='N' order by start_date desc;";

//var_dump($sql);
$result=mysqli_query($lien,$sql);
//ferme la connection//
mysqli_close($lien);

$tab_contrats=fn_ResultToArray($result,'id_contrat');

/*************************/

$string_dep='';

if($tab_contrats==null)
{
	echo 'Pas encore de contrat<br>';
}
else
{
	foreach($tab_contrats as $key=> $value)
	{
		echo '<div class="modif_contrat" onclick="DisplayFormContratModif('.$value['id_contrat'].','.$id_agent.');">';
		
		if($value['actif']==1)
		{
			$actif="<span style='color:green;'>Contrat actif</span>";
		}
		else
		{
			$actif="<i>Contrat inactif</i>";
		}
		
		$string_dep='';
		/*********DEPARTEMENT*************/
		if( array_key_exists( $value['id_dep'],$array_departement ) ) 
		{	
			$dep=$array_departement[$value['id_dep']]['F'];
			
			if($value['id_dep']!=0)
			{
				$string_dep.=$dep;
			}
		}
		/*else
		{
			$dep=$array_departement[0]['F'];
		} */
		
		/*********SERVICE*************/
		
		if(array_key_exists( $value['id_ser'],$array_service ) )
		{	
			$ser=$array_service[$value['id_ser']]['F'];
			
			if($value['id_ser']!=0)
			{
				$string_dep.=' / '.$ser;
			}
		}
		/*else
		{
			$ser=$array_service[0]['F'];
			
		} */
		
		/*********CELLULE*************/
		
		if(array_key_exists($value['id_cel'],$array_cellule ))  
		{	
			$cel=$array_cellule[$value['id_cel']]['F'];
			
			if($value['id_cel']!=0)
			{
				$string_dep.=' / '.$cel;
			}
		}
		/*else
		{
			$cel=$array_cellule[0]['F'];
		} */
		
		/**********************/
		
		
		if( array_key_exists( $value['id_hors_dep'],$array_hors_departement ) )
		{	
			$hors_dep=$array_hors_departement[$value['id_hors_dep']]['F'];
			//$string_dep=$hors_dep;
		}
		else
		{
			$hors_dep=$array_hors_departement[0]['F'];
		} 
		
		if($value['id_hors_dep']!=0)
		{
			echo 'N° '.$value['id_contrat'].' '.$actif.' - DU '.transformDate($value['start_date']).' AU '.transformDate($value['end_date']).' ('.$hors_dep.')';
		}
		else
		{
			echo 'N° '.$value['id_contrat'].' '.$actif.' - DU '.transformDate($value['start_date']).' AU '.transformDate($value['end_date']).' ('.$string_dep.')';
		
		}
		/************Affichage lien contrat*********************************************/
		/*if($value['id_hors_dep']!=0)
		{
			echo $actif.' - '.$hors_dep.' - <b>Grade: </b>'.$array_grade[$value['id_grade']]['F'].' - '.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].' - <b>Occupation: </b>'.$value['id_equiv_tp'];
		}
		else
		{
			echo $actif.' - '.$string_dep.' - <b>Grade: </b>'.$array_grade[$value['id_grade']]['F'].' - '.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].' - <b>Occupation: </b>'.$value['id_equiv_tp'];
		}*/
		
		echo '</div>';
	}
}
?>