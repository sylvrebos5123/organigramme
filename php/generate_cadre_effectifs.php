<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

include ($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel.php');
include($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\Writer\\Excel2007.php');
/*************/

include('verification.php');
include('params.php');

echo '<javascript>';

if(($id_cadre=='')||($id_cadre==0)||($id_cadre==null))
{
	echo "alert('Veuillez sélectionner un cadre.');";
	
	exit;
}

if(($date_situation_effectifs=='')||($date_situation_effectifs=='00-00-0000')||($date_situation_effectifs=='0000-00-00')||($date_situation_effectifs==null))
{
	echo "alert('Veuillez sélectionner une date de situation pour les effectifs.');";
	
	exit;
}
/******************************/
// LETTRE COLONNE  ****** TITRE COLONNE CORRESPONDANTE  *****/    
$array_column=array(); $array_title=array();
$array_column[]='A'; $array_title[]='HORS DEP./DEP.';
$array_column[]='B'; $array_title[]='SERVICE';
$array_column[]='C'; $array_title[]="BAREME";
$array_column[]='D'; $array_title[]="FONCTION";
$array_column[]='E'; $array_title[]='GRADE';
$array_column[]='F'; $array_title[]="TYPE CADRE";
$array_column[]='G'; $array_title[]='ARTICLE BUDGETAIRE';
$array_column[]='H'; $array_title[]='CADRE ';
$array_column[]='I'; $array_title[]='EFFECTIFS '.$date_situation_effectifs;


//var_dump($array_title);



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


/**********************************/
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_fonction.php'); 
include('../arrays_libelle/array_statut.php');
include('../arrays_libelle/array_regime.php');
include('../arrays_libelle/array_type_cadre.php');
/*************/

//$date_effectif=transformDate($date_situation_effectifs);
//$date_effectif=transformDate($date_situation_effectifs);
$date_effectif=transformDate($date_situation_effectifs);
$new_date_effectif=str_replace('-', '', $date_effectif);

/************************************************************************
**************Construction des effectifs à une date donnée************
************************************************************************/
include('creation_table_effectifs.php');
/********************************************/

/*******Connexion database***************/
include('../connect_db.php');

/***************************************************************************
********************************Lire places au cadre************************
****************************************************************************/
$sql="
SELECT * from 
cpas_places_cadre
join cpas_cadres
on
cpas_places_cadre.id_cadre=cpas_cadres.id_cadre
where cpas_places_cadre.id_cadre=".$id_cadre." and cpas_places_cadre.statut='N' order by type_cadre desc,id_dep asc,id_ser asc;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);

$nb_records=mysqli_num_rows($result);
$nb_records=$nb_records+1;
$tab_cadres=fn_ResultToArray($result,'id_place_cadre');



/*************Définition style + nb lignes et colonnes dans XLS************************************************************/
$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();

$styleArrayBorder = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$sheet->getStyle('A1:I'.$nb_records)->applyFromArray($styleArrayBorder);

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
/*****************************************************************************************************
******************Lecture du cadre demandé et addition des equiv. temps plein côté effectifs**********
*****************************************************************************************************/

$num_ligne=1;
$champs_conditions="";
$date_cadre='';

foreach($tab_cadres as $key_cadres => $value_cadres)
{
	$num_ligne++;
	$champs_conditions="";
	/*************************CHAMPS CADRE**************************************************/
	if(($value_cadres['id_hors_dep']==0)||($value_cadres['id_hors_dep']=='')||($value_cadres['id_hors_dep']==null))
	{
		$sheet->setCellValue($array_column[0].$num_ligne,$array_departement[$value_cadres['id_dep']]['F']);
	}
	else	
	{
		$sheet->setCellValue($array_column[0].$num_ligne,$array_hors_departement[$value_cadres['id_hors_dep']]['F']);
	}

	$sheet->setCellValue($array_column[1].$num_ligne,$array_service[$value_cadres['id_ser']]['F']);
	
	if(($value_cadres['id_code']==0)||($value_cadres['id_code']=='')||($value_cadres['id_code']==null))
	{
		$sheet->setCellValue($array_column[2].$num_ligne,$array_bareme[$value_cadres['id_bareme']]);
	}
	else
	{
		$sheet->setCellValue($array_column[2].$num_ligne,$array_bareme[$value_cadres['id_bareme']].$array_code[$value_cadres['id_code']]);
	}
	
	$sheet->setCellValue($array_column[3].$num_ligne,$array_fonction[$value_cadres['id_fonc']]['F']);

	$sheet->setCellValue($array_column[4].$num_ligne,$array_grade[$value_cadres['id_grade']]['F']);
	
	if($value_cadres['type_cadre']==1)
	{
		$sheet->setCellValue($array_column[5].$num_ligne,'CADRE STANDARD');
	}
	else
	{
		if($value_cadres['type_cadre']==2)
		{
			$sheet->setCellValue($array_column[5].$num_ligne,'CADRE DIRIGEANT');
		}
	}
	$sheet->setCellValue($array_column[6].$num_ligne,$value_cadres['article_budgetaire']);
	
	$sheet->setCellValue($array_column[7].$num_ligne,$value_cadres['id_equiv_tp']);
	
	/**********************EFFECTIFS********************************************/
	if($value_cadres['type_cadre']==2)
	{
		$champs_conditions.="type_cadre=2
		 and id_bareme_cadre=".$value_cadres['id_bareme'];	
	}
	else
	{
		$champs_conditions.="
		id_hors_dep=".$value_cadres['id_hors_dep']." 
	     and id_dep=".$value_cadres['id_dep']."
	     and id_ser=".$value_cadres['id_ser']." 
		 and id_bareme_cadre=".$value_cadres['id_bareme']."
	     and type_cadre=1";
	}
	
	if($value_cadres['id_code']==0)
	{
		$champs_conditions.=" and (id_code_cadre=0 or id_code_cadre='' or id_code_cadre=null or cpas_codes.libelle < 4 )";
		//$champs_conditions.=" and (id_code_cadre=0 or id_code_cadre='' or id_code_cadre=null or id_code_cadre=1 or id_code_cadre=2 or id_code_cadre=3)";
	}
	else
	{
		$champs_conditions.=" and id_code_cadre=".$value_cadres['id_code'];
	}
	
	
	if(($value_cadres['id_fonc'] != 0)&&($value_cadres['id_grade'] != 0))
	{
		$champs_conditions.="and id_fonc=".$value_cadres['id_fonc']."
	   and id_grade_cadre=".$value_cadres['id_grade'];
		
	}
	else
	{
		if(($value_cadres['id_fonc'] == 0)&&($value_cadres['id_grade'] != 0))
		{
			$champs_conditions.=" and id_grade_cadre=".$value_cadres['id_grade'];
		}
		else
		{
			if(($value_cadres['id_fonc'] != 0)&&($value_cadres['id_grade'] == 0))
			{
				$champs_conditions.="and id_fonc=".$value_cadres['id_fonc'];
			}
		
		}
	
	}
	
	/*$sql="select SUM(id_equiv_tp) as nb_equiv from cpas_effectifs_".$new_date_effectif." 
	   where ".$champs_conditions.";";*/
	   
	 $sql="select SUM(id_equiv_tp) as nb_equiv from cpas_effectifs_".$new_date_effectif." 
	   join cpas_codes
	   on cpas_effectifs_".$new_date_effectif.".id_code_cadre=cpas_codes.id_code
	   where ".$champs_conditions.";";
	
	//echo $sql;
	
	$result=mysqli_query($lien, $sql);
	
	//$tab_result=fn_ResultToArray($result,'id_contrat');
	$tab_result=mysqli_fetch_assoc($result);
	//var_dump($tab_result);
	if($tab_result['nb_equiv']=='')
	{
		$nb_equiv=0;
	}
	else
	{
		$nb_equiv=$tab_result['nb_equiv'];
	}
	//echo $nb_equiv.'<br>';
	
	$sheet->setCellValue($array_column[8].$num_ligne,$nb_equiv);
	
	$date_cadre=$value_cadres['date_situation'];
	//var_dump($tab_result);
}


/********Close connexion***************/
mysqli_close($lien);





$sheet->setAutoFilter('A1:I1');
$sheet->getStyle('A1:I'.$nb_records)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:I'.$nb_records)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:I'.$nb_records)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 
//$sheet->setAutoSize(true);

/* $writer = new PHPExcel_Writer_Excel2007($workbook);

$name_file='cadre_effectifs_'.$date_cadre.'_'.date('Ymd').'_'.date('His').'.xlsx';

$records = './'.$name_file;

$writer->save($records);


echo 'window.open("'.$name_file.'","_blank");'; */

/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/
//echo '<javascript>';
$file_name = 'cadre_effectifs_'.$date_cadre.'_'.date('Ymd-His').'.xlsx';
$temp_xls_name = 'E:\\webserver\\test_cpas_ocmw\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
include('array_files.php');
$new_xls_name = $array_files['CADRE_EFFECTIFS'].$file_name;

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

/*******Connexion database***************/
include('../connect_db.php');

/********************************************/
/*********************************************************************************
************************DROP TABLE *****************
*******************************************************************************/

$sql="drop table if EXISTS cpas_effectifs_".$new_date_effectif.";";

$result=mysqli_query($lien, $sql);

mysqli_close($lien);

?>
