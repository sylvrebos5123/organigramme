<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

include ($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel.php');
include($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\Writer\\Excel2007.php');

echo "<javascript>";

include('verification.php');
include('params.php');


if($date_debut=='')
{
	echo "alert('Date de début vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if($date_fin=='')
{
	echo "alert('Date de fin vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(compareDate($date_debut,$date_fin)==0)
{
	 echo "alert('Attention la date de début est plus grande que la date de fin. Veuillez recommencer svp.');";
	 exit;
}

/*if(($start_date!='00-00-0000')&&($start_date!='0000-00-00')&&($start_date!=''))
{
	if(dateValid($start_date)==0)
	{
		echo "alert('Date de début de contrat invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if(($end_date!='00-00-0000')&&($end_date!='0000-00-00')&&($end_date!=''))
{
	if(dateValid($end_date)==0)
	{
		echo "alert('Date de fin de contrat invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}*/

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
/*****************/
include('../connect_db.php');

/******************Comptage nb_contrats par agent***********************************************************************/
$sql="
select
count(id_contrat) as nb_contrats
,cpas_contrats.id_agent
,nom
,prenom
FROM
cpas_contrats
join 
cpas_agents
ON
cpas_contrats.id_agent
=cpas_agents.id_agent

group by nom,prenom
order by nom;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun contrat entre ces 2 dates');";
	
	exit;
}



$array_nb_contrats=fn_ResultToArray($result,'id_agent');

/***************************************************/
$sql="
select 
cpas_agents.id_agent
,nom
,prenom
,date_naissance 
,registre_id
,start_date
,id_statut
,id_regime
,id_fonc
,id_ser
,id_cel
,id_dep
,id_hors_dep
from cpas_agents
join cpas_signaletiques_agents
on cpas_agents.id_agent=cpas_signaletiques_agents.id_agent
JOIN
cpas_contrats
on cpas_contrats.id_agent=cpas_agents.id_agent
WHERE
start_date>='".transformDate($date_debut)."' and start_date<='".transformDate($date_fin)."'
and registre_id<990000
order by nom;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun contrat entre ces 2 dates');";
	
	exit;
}


$nb_records=mysqli_num_rows($result);
$nb_records=$nb_records+1;

$array_contrats=fn_ResultToArray($result,'id_agent');

mysqli_close($lien);
//var_dump($array_contrats);
//var_dump($array_nb_contrats);


$tab_nb_contrats=array();

foreach($array_nb_contrats as $key => $value)
{
	$tab_nb_contrats[$value['nom'].'-'.$value['prenom']]=$value['nb_contrats'];
}
//var_dump($tab_nb_contrats);

/**********************************************************/
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_statut.php');
include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_regime.php');

/***********************************************************/
// LETTRE COLONNE  ****** TITRE COLONNE CORRESPONDANTE  *****/    
$array_column=array(); $array_title=array();
$array_column[]='A'; $array_title[]='MOTIF';
$array_column[]='B'; $array_title[]='NOM';
$array_column[]='C'; $array_title[]='Prénom';
$array_column[]='D'; $array_title[]='N° matricule';
$array_column[]='E'; $array_title[]='Date entrée';
$array_column[]='F'; $array_title[]='Date début changement';
$array_column[]='G'; $array_title[]='Type contrat';
$array_column[]='H'; $array_title[]='Régime';
$array_column[]='I'; $array_title[]='Fonction';
$array_column[]='J'; $array_title[]='Dep./Hors dep.';
$array_column[]='K'; $array_title[]='Service';
$array_column[]='L'; $array_title[]='Cellule';


$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();

// Titres colonnes
for($i=0;$i<count($array_column);$i++)
{
	$sheet->setCellValue($array_column[$i].'1',$array_title[$i]);
	$sheet->getColumnDimension($array_column[$i])->setWidth(35);
	$sheet->getRowDimension($array_column[$i].'1')->setRowHeight(20);
	$sheet->getStyle($array_column[$i].'1')->getFill()->applyFromArray(
		array(
			'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array('rgb' => 'E9E9E9'),
			'endcolor'   => array('rgb' => 'E9E9E9')
		)
	);
}

$num_ligne=1;
//$tab_new_cel=array();
//$num_colonne=0;
include('../connect_db.php');


foreach($array_contrats as $key => $value)
{
	
	$num_ligne++;
	
	
	if($tab_nb_contrats[$value['nom'].'-'.$value['prenom']] >= 2)
	{
		

		/*****************************************************************************************/
		$sql="
		select
		*
		FROM
		cpas_contrats
		join 
		cpas_agents
		ON
		cpas_contrats.id_agent
		=cpas_agents.id_agent
		where nom='".$value['nom']."' and prenom='".$value['prenom']."'
		and start_date < '".$value['start_date']."';
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		
		if(mysqli_num_rows($result)>0)
		{
			//echo "Contrat sup : contrat avant '".$value['start_date']."' pour '".$value['nom']."' '".$value['prenom']."'<br><br>";
			$sheet->setCellValue($array_column[0].$num_ligne,'Contrat supplémentaire');
		}
		
		
		
		//$tab_contrats_nom=fn_ResultToArray($result,'id_contrat');
		
	}
	else
	{
		$sheet->setCellValue($array_column[0].$num_ligne,'Engagement');
	}
	
	$sheet->setCellValue($array_column[1].$num_ligne,$value['nom']);
	$sheet->setCellValue($array_column[2].$num_ligne,$value['prenom']);
	$sheet->setCellValue($array_column[3].$num_ligne,$value['registre_id']);
	$sheet->setCellValue($array_column[4].$num_ligne,transformDate($value['start_date']));
	$sheet->setCellValue($array_column[5].$num_ligne,'');
	$sheet->setCellValue($array_column[6].$num_ligne,$array_statut[$value['id_statut']]['F']);
	$sheet->setCellValue($array_column[7].$num_ligne,$array_regime[$value['id_regime']]['F']);
	$sheet->setCellValue($array_column[8].$num_ligne,$array_fonction[$value['id_fonc']]['F']);
	if(($value['id_hors_dep']==0)||($value['id_hors_dep']=='')||($value['id_hors_dep']==null))
	{
		$sheet->setCellValue($array_column[9].$num_ligne,$array_departement[$value['id_dep']]['F']);
	}
	else
	{
		$sheet->setCellValue($array_column[9].$num_ligne,$array_hors_departement[$value['id_hors_dep']]['F']);
	}
	$sheet->setCellValue($array_column[10].$num_ligne,$array_service[$value['id_ser']]['F']);
	$sheet->setCellValue($array_column[11].$num_ligne,$array_cellule[$value['id_cel']]['F']);
	//$tab_new_cel=explode('-',$array_cellule[$value['id_cel']]['F']);
	//$cel.=$tab_new_cel[1]."\r\n";
	/* if(($array_cellule[$value['id_cel']]['F']=='')||($array_cellule[$value['id_cel']]['F']==null))
	{
		$sheet->setCellValue($array_column[9].$num_ligne,$array_service[$value['id_ser']]['F']);
	}
	else
	{
		$sheet->setCellValue($array_column[9].$num_ligne,$array_service[$value['id_ser']]['F'].' / '.$array_cellule[$value['id_cel']]['F']);
	} */
}

mysqli_close($lien);



$sheet->setAutoFilter('A1:L1');
$sheet->getStyle('A1:L'.$nb_records)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:L'.$nb_records)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:L'.$nb_records)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 



/* $nom_fichier='personnel_entrant_'.date('YmdHis');

$writer = new PHPExcel_Writer_Excel2007($workbook);

$records = './'.$nom_fichier.'.xlsx';

$writer->save($records); */

//echo 'window.open("php/'.$nom_fichier.'.xlsx","_blank");';


/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/

$file_name = 'personnel_entrant_'.date('Ymd-His').'.xlsx';
$temp_xls_name = 'E:\\webserver\\test_cpas_ocmw\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
include('array_files.php');
$new_xls_name = $array_files['AGENTS_IN'].$file_name;

$writer = new PHPExcel_Writer_Excel2007($workbook);

$records = $temp_xls_name;

$writer->save($records);

// génération du fichier xlsx dans  $temp_xls_name
$xlsx_genrate = false;
if (file_exists($temp_xls_name))
{
 if (!copy($temp_xls_name, $new_xls_name))
 {
  echo "alert('Erreur de la copie');";
 }
 
 $xlsx_genrate = true;
 
}
else
	 echo "alert('Fichier xlsx non créé');";
  //==> Génération de l'erreur du fichier xlsx non créé

if ($xlsx_genrate == true)
{
 echo 'window.open("/organigramme/temp/'.$file_name.'","_blank");';
} 
	
?>
