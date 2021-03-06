<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);


echo '<style>

td{text-align:center;padding:5px;}

</style>';

/*****************************************/
include ($rootpath.'\\organigramme\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel.php');
include($rootpath.'\\organigramme\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\Writer\\Excel2007.php');

// LETTRE COLONNE  ****** TITRE COLONNE CORRESPONDANTE  *****/    
$array_column=array(); $array_title=array();
$array_column[]='A'; $array_title[]='HORS DEP./DEP.';
$array_column[]='B'; $array_title[]='SERVICE';
$array_column[]='C'; $array_title[]="BAREME";
$array_column[]='D'; $array_title[]="FONCTION";
$array_column[]='E'; $array_title[]='GRADE';
$array_column[]='F'; $array_title[]="TYPE CADRE";
$array_column[]='G'; $array_title[]='ARTICLE BUDGETAIRE';
$array_column[]='H'; $array_title[]='EQUIV. TEMPS PLEIN';

/***************Fonction qui met le r�sultat des records dans un array*********************************************/
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

//$date_effectif=transformDate($date_situation_effectifs);

/*******Connexion database***************/
include('../connect_db.php');

/*******Lire places au cadre*****************************/
$sql="
SELECT * from 
cpas_places_cadre
join cpas_cadres
on cpas_places_cadre.id_cadre=cpas_cadres.id_cadre
where cpas_places_cadre.id_cadre=".$id_cadre." and cpas_places_cadre.statut='N' order by type_cadre desc,id_dep asc,id_ser asc;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);

$nb_records=mysqli_num_rows($result);
$nb_records=$nb_records+1;
/********Close connexion***************/
mysqli_close($lien);

$tab_cadres=fn_ResultToArray($result,'id_place_cadre');



/*echo "
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

";*/
//echo '<table>';

/*************D�finition style + nb lignes et colonnes dans XLS************************************************************/
$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();

$styleArrayBorder = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$sheet->getStyle('A1:H'.$nb_records)->applyFromArray($styleArrayBorder);


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

//Affectations de chaque records danns le fichier xls
$num_ligne=1;
$date_cadre='';

foreach($tab_cadres as $key=> $value)
{
	$num_ligne++;
	
	$date_cadre=$value['date_situation'];
	
	if(($value['id_hors_dep']==0)||($value['id_hors_dep']=='')||($value['id_hors_dep']==null))
	{
		//echo '<td>'.$array_departement[$value['id_dep']]['F'].'</td>';
		$sheet->setCellValue($array_column[0].$num_ligne,$array_departement[$value['id_dep']]['F']);
	}
	else	
	{
		//echo '<td>'.$array_hors_departement[$value['id_hors_dep']]['F'].'</td>';
		$sheet->setCellValue($array_column[0].$num_ligne,$array_hors_departement[$value['id_hors_dep']]['F']);
	}
	//echo '<td>'.$array_service[$value['id_ser']]['F'].'</td>';
	$sheet->setCellValue($array_column[1].$num_ligne,$array_service[$value['id_ser']]['F']);
	
	if(($value['id_code']==0)||($value['id_code']=='')||($value['id_code']==null))
	{
		//echo '<td>'.$array_bareme[$value['id_bareme']].'</td>';
		$sheet->setCellValue($array_column[2].$num_ligne,$array_bareme[$value['id_bareme']]);
	}
	else
	{
		//echo '<td>'.$array_bareme[$value['id_bareme']].$array_code[$value['id_code']].'</td>';
		$sheet->setCellValue($array_column[2].$num_ligne,$array_bareme[$value['id_bareme']].$array_code[$value['id_code']]);
	}
	
	//echo '<td>'.$array_fonction[$value['id_fonc']]['F'].'</td>';
	$sheet->setCellValue($array_column[3].$num_ligne,$array_fonction[$value['id_fonc']]['F']);
	//echo '<td>'.$array_grade[$value['id_grade']]['F'].'</td>';
	$sheet->setCellValue($array_column[4].$num_ligne,$array_grade[$value['id_grade']]['F']);
	
	if($value['type_cadre']==1)
	{
		//echo '<td>CADRE STANDARD</td>';
		$sheet->setCellValue($array_column[5].$num_ligne,'CADRE STANDARD');
	}
	else
	{
		if($value['type_cadre']==2)
		{
			//echo '<td>CADRE DIRIGEANT</td>';
			$sheet->setCellValue($array_column[5].$num_ligne,'CADRE DIRIGEANT');
		}
	}
	//echo '<td>'.$value['article_budgetaire'].'</td>';
	$sheet->setCellValue($array_column[6].$num_ligne,$value['article_budgetaire']);
	//echo '<td>'.$value['id_equiv_tp'].'</td>';
	$sheet->setCellValue($array_column[7].$num_ligne,$value['id_equiv_tp']);
	//echo '</tr>';
}//fin foreach tab_cadres	
	

//echo '</table>';


$sheet->setAutoFilter('A1:H1');
$sheet->getStyle('A1:H'.$nb_records)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:H'.$nb_records)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:H'.$nb_records)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 
//$sheet->setAutoSize(true);

/* $writer = new PHPExcel_Writer_Excel2007($workbook);

$name_file='cadre_'.$date_cadre.'_'.date('His').'.xlsx';

$records = './'.$name_file;

$writer->save($records);


	echo '<javascript>';
	echo 'window.open("'.$name_file.'","_blank");'; */
	
/*****************************************************************************************
*********** Cr�ation du fichier dans un r�pertoire temporaire et copie sur filesrv*********
*******************************************************************************************/
echo '<javascript>';
$file_name = 'cadre_'.$date_cadre.'_'.date('Ymd-His').'.xlsx';
//$temp_xls_name = 'E:\\webserver\\test_cpas_ocmw\\www\\organigramme\\temp\\'.$file_name;
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'acc�s
include('array_files.php');
$new_xls_name = $array_files['CADRE'].$file_name;

$writer = new PHPExcel_Writer_Excel2007($workbook);

$records = $temp_xls_name;

$writer->save($records);

// g�n�ration du fichier xlsx dans  $temp_xls_name
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
	 echo "alert('Fichier xlsx non cr��');";
  //==> G�n�ration de l'erreur du fichier xlsx non cr��

if ($xlsx_genrate == true)
{
 echo 'window.open("/organigramme/temp/'.$file_name.'","_blank");';
} 
?>
