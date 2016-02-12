<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

include ($rootpath.'\\organigramme\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel.php');
include($rootpath.'\\organigramme\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\Writer\\Excel2007.php');

echo "<javascript>";

include('verification.php');

include('params.php');


if($date_debut_out=='')
{
	echo "alert('Date de début vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if($date_fin_out=='')
{
	echo "alert('Date de fin vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(compareDate($date_debut_out,$date_fin_out)==0)
{
	 echo "alert('Attention la date de début est plus grande que la date de fin. Veuillez recommencer svp.');";
	 exit;
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
/*****************/
include('../connect_db.php');

$sql="
select 
cpas_agents.id_agent
,cpas_contrats.id_contrat
,nom
,prenom 
,registre_id
,motif_sortie
,end_date
,id_statut
,id_statut_special
,id_regime
,id_dep
,id_hors_dep
,id_ser
,id_cel
from cpas_agents
JOIN
cpas_contrats
on cpas_contrats.id_agent=cpas_agents.id_agent
WHERE
end_date>='".transformDate($date_debut_out)."' and end_date<='".transformDate($date_fin_out)."'
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
$array_contrats=fn_ResultToArray($result,'id_contrat');

mysqli_close($lien);


/**********************************************************/
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_statut.php');
//include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_regime.php');

/***********************************************************/
// LETTRE COLONNE  ****** TITRE COLONNE CORRESPONDANTE  *****/    
$array_column=array(); $array_title=array();
$array_column[]='A'; $array_title[]='NOM';
$array_column[]='B'; $array_title[]='Prénom';
$array_column[]='C'; $array_title[]='N° matricule';
$array_column[]='D'; $array_title[]='MOTIF SORTIE';
$array_column[]='E'; $array_title[]='Date sortie';
$array_column[]='F'; $array_title[]='Statut';
$array_column[]='G'; $array_title[]='Statut spécial';
$array_column[]='H'; $array_title[]='Régime';
$array_column[]='I'; $array_title[]='Dep./Hors dep.';
$array_column[]='J'; $array_title[]='Service';
$array_column[]='K'; $array_title[]='Cellule';


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


foreach($array_contrats as $key => $value)
{
	
	$num_ligne++;
	
	$sheet->setCellValue($array_column[0].$num_ligne,$value['nom']);
	$sheet->setCellValue($array_column[1].$num_ligne,$value['prenom']);
	$sheet->setCellValue($array_column[2].$num_ligne,$value['registre_id']);
	$sheet->setCellValue($array_column[3].$num_ligne,$value['motif_sortie']);
	$sheet->setCellValue($array_column[4].$num_ligne,transformDate($value['end_date']));
	$sheet->setCellValue($array_column[5].$num_ligne,$array_statut[$value['id_statut']]['F']);
	$sheet->setCellValue($array_column[6].$num_ligne,$array_statut_special[$value['id_statut_special']]['F']);
	$sheet->setCellValue($array_column[7].$num_ligne,$array_regime[$value['id_regime']]['F']);
	if(($value['id_hors_dep']==0)||($value['id_hors_dep']=='')||($value['id_hors_dep']==null))
	{
		$sheet->setCellValue($array_column[8].$num_ligne,$array_departement[$value['id_dep']]['F']);
	}
	else
	{
		$sheet->setCellValue($array_column[8].$num_ligne,$array_hors_departement[$value['id_hors_dep']]['F']);
	}
	$sheet->setCellValue($array_column[9].$num_ligne,$array_service[$value['id_ser']]['F']);
	$sheet->setCellValue($array_column[10].$num_ligne,$array_cellule[$value['id_cel']]['F']);
	
	
}

$sheet->setAutoFilter('A1:K1');
$sheet->getStyle('A1:K'.$nb_records)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:K'.$nb_records)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:K'.$nb_records)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 



/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/

$file_name = 'personnel_sortant_'.date('Ymd-His').'.xlsx';
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
//include('array_files.php');
//$new_xls_name = $array_files['AGENTS_OUT'].$file_name;

$writer = new PHPExcel_Writer_Excel2007($workbook);

$records = $temp_xls_name;

$writer->save($records);

// génération du fichier xlsx dans  $temp_xls_name
$xlsx_genrate = false;
if (file_exists($temp_xls_name))
{
 /*if (!copy($temp_xls_name, $new_xls_name))
 {
  echo "alert('Erreur de la copie');";
 }*/
 
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
