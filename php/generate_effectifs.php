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
$array_column[]='A'; $array_title[]='NOM';
$array_column[]='B'; $array_title[]='PRÉNOM';
$array_column[]='C'; $array_title[]="DATE D'ENGAGEMENT";
$array_column[]='D'; $array_title[]="DATE DE SORTIE";
$array_column[]='E'; $array_title[]='MOTIF SORTIE';
$array_column[]='F'; $array_title[]="ARTICLE BUDGETAIRE";
$array_column[]='G'; $array_title[]='HORS DÉPARTEMENT';
$array_column[]='H'; $array_title[]='DÉPARTEMENT';
$array_column[]='I'; $array_title[]='SERVICE';
$array_column[]='J'; $array_title[]='CELLULE';
$array_column[]='K'; $array_title[]='FONCTION';
$array_column[]='L'; $array_title[]='GRADE';
$array_column[]='M'; $array_title[]='BARÈME';
$array_column[]='N'; $array_title[]='CODE';
$array_column[]='O'; $array_title[]='TYPE CADRE';
$array_column[]='P'; $array_title[]='STATUT';
$array_column[]='Q'; $array_title[]='STATUT SPÉCIAL';
$array_column[]='R'; $array_title[]='RÉGIME';
$array_column[]='S';$array_title[]='Equivalent temps-plein';




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
/*************/

include('verification.php');
include('params.php');

/**********************************/
include('../arrays_libelle/array_article_budgetaire.php');
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

/*******Connexion database***************/
include('../connect_db.php');

/*******Lire les contrats actifs à la date demandée*****************************/
/* $sql="
select id_contrat,nom,prenom,start_date,end_date,motif_sortie from cpas_agents
join cpas_contrats
on
cpas_contrats.id_agent = cpas_agents.id_agent
where ((cpas_contrats.start_date <= '".$date_effectif."') and (cpas_contrats.end_date > '".$date_effectif."' or cpas_contrats.end_date = '0000-00-00' or cpas_contrats.end_date = '00-00-0000' or cpas_contrats.end_date = '' or cpas_contrats.end_date is null))
order by nom;

"; */

$sql="
select 
*
from 
cpas_effectifs_".$new_date_effectif."
where registre_id<990000
order by nom,prenom;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);


$nb_agents=mysqli_num_rows($result);
$nb_agents=$nb_agents+1;

$tab_contrats=fn_ResultToArray($result,'id_contrat');


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

$sheet->getStyle('A1:S'.$nb_agents)->applyFromArray($styleArrayBorder);


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



foreach($tab_contrats as $key=> $value)
{
	$num_ligne++;
	
	$sheet->setCellValue($array_column[0].$num_ligne,$value['nom']);
	$sheet->setCellValue($array_column[1].$num_ligne,$value['prenom']);
	$sheet->setCellValue($array_column[2].$num_ligne,transformDate($value['start_date']));
	$sheet->setCellValue($array_column[3].$num_ligne,transformDate($value['end_date']));
	$sheet->setCellValue($array_column[4].$num_ligne,$value['motif_sortie']);
	
	$art_budgetaire=explode('-',$array_article_budgetaire[$value['id_article_budgetaire']]['F']);
	$sheet->setCellValue($array_column[5].$num_ligne,$art_budgetaire[0]);
	$sheet->setCellValue($array_column[6].$num_ligne,$array_hors_departement[$value['id_hors_dep']]['F']);
	$sheet->setCellValue($array_column[7].$num_ligne,$array_departement[$value['id_dep']]['F']);
	$sheet->setCellValue($array_column[8].$num_ligne,$array_service[$value['id_ser']]['F']);
	$sheet->setCellValue($array_column[9].$num_ligne,$array_cellule[$value['id_cel']]['F']);
	$sheet->setCellValue($array_column[10].$num_ligne,$array_fonction[$value['id_fonc']]['F']);
	$sheet->setCellValue($array_column[11].$num_ligne,$array_grade[$value['id_grade_cadre']]['F']);
	$sheet->setCellValue($array_column[12].$num_ligne,$array_bareme[$value['id_bareme_cadre']]);
	$sheet->setCellValue($array_column[13].$num_ligne,$array_code[$value['id_code_cadre']]);
	$sheet->setCellValue($array_column[14].$num_ligne,$array_type_cadre[$value['type_cadre']]);
	$sheet->setCellValue($array_column[15].$num_ligne,$array_statut[$value['id_statut']]['F']);
	$sheet->setCellValue($array_column[16].$num_ligne,$array_statut_special[$value['id_statut_special']]['F']);
	$sheet->setCellValue($array_column[17].$num_ligne,$array_regime[$value['id_regime']]['F']);
	$sheet->setCellValue($array_column[18].$num_ligne,$value['id_equiv_tp']);
		
	/****************Mvt service*****************************/
	
 /* 
	$sql="select * from cpas_mouvements_services 
	where 
	((cpas_mouvements_services.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_services.date_debut_service <= '".$date_effectif."')) 
	order by cpas_mouvements_services.date_debut_service desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
    $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_ser=fn_ResultToArray($result,'id_mvt_service');
	//var_dump( $tab_mvt_ser);
	if($tab_mvt_ser!=null)
	{
		foreach($tab_mvt_ser as $key_ser=> $value_ser)
		{
			$sheet->setCellValue($array_column[5].$num_ligne,$value_ser['article_budgetaire']);
			$sheet->setCellValue($array_column[6].$num_ligne,$array_hors_departement[$value_ser['id_hors_dep']]['F']);
			$sheet->setCellValue($array_column[7].$num_ligne,$array_departement[$value_ser['id_dep']]['F']);
			$sheet->setCellValue($array_column[8].$num_ligne,$array_service[$value_ser['id_ser']]['F']);
			$sheet->setCellValue($array_column[9].$num_ligne,$array_cellule[$value_ser['id_cel']]['F']);
		}
	}
	else
	{
		//echo '<td colspan="5">---</td>';
		$sheet->setCellValue($array_column[5].$num_ligne,'---');
		$sheet->setCellValue($array_column[6].$num_ligne,'---');
		$sheet->setCellValue($array_column[7].$num_ligne,'---');
		$sheet->setCellValue($array_column[8].$num_ligne,'---');
		$sheet->setCellValue($array_column[9].$num_ligne,'---');
	} */
	
	/****************Mvt fonction*****************************/
 
	/* $sql="select * from cpas_mouvements_fonctions 
	where 
	((cpas_mouvements_fonctions.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_fonctions.date_debut_fonction <= '".$date_effectif."')) 
	order by cpas_mouvements_fonctions.date_debut_fonction desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
    $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_fct=fn_ResultToArray($result,'id_mvt_fonction');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_fct!=null)
	{
		foreach($tab_mvt_fct as $key_fct=> $value_fct)
		{
			$sheet->setCellValue($array_column[10].$num_ligne,$array_fonction[$value_fct['id_fonc']]['F']);
		}
	}
	else
	{
		$sheet->setCellValue($array_column[10].$num_ligne,'---');
	} */
	
	/****************Mvt barème*****************************/
 
	/* $sql="select * from cpas_mouvements_baremes 
	where 
	((cpas_mouvements_baremes.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_baremes.date_debut_bareme <= '".$date_effectif."')) 
	order by cpas_mouvements_baremes.date_debut_bareme desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	$tab_mvt_baremes=fn_ResultToArray($result,'id_mvt_bareme');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_baremes!=null)
	{
		foreach($tab_mvt_baremes as $key_baremes=> $value_baremes)
		{
			
			$sheet->setCellValue($array_column[11].$num_ligne,$array_grade[$value_baremes['id_grade_cadre']]['F']);
			$sheet->setCellValue($array_column[12].$num_ligne,$array_bareme[$value_baremes['id_bareme_cadre']]);
			$sheet->setCellValue($array_column[13].$num_ligne,$array_code[$value_baremes['id_code_cadre']]);
			$sheet->setCellValue($array_column[14].$num_ligne,$array_type_cadre[$value_baremes['type_cadre']]);
		}
	}
	else
	{
		//echo '<td colspan="3">---</td>';
		$sheet->setCellValue($array_column[11].$num_ligne,'---');
		$sheet->setCellValue($array_column[12].$num_ligne,'---');
		$sheet->setCellValue($array_column[13].$num_ligne,'---');
		$sheet->setCellValue($array_column[14].$num_ligne,'---');
	} */
	
	/****************Mvt statut*****************************/
 /* 
	$sql="select * from cpas_mouvements_statuts 
	where 
	((cpas_mouvements_statuts.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_statuts.date_debut_statut <= '".$date_effectif."')) 
	order by cpas_mouvements_statuts.date_debut_statut desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_statuts=fn_ResultToArray($result,'id_mvt_statut');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_statuts!=null)
	{
		foreach($tab_mvt_statuts as $key_statuts=> $value_statuts)
		{
			$sheet->setCellValue($array_column[15].$num_ligne,$array_statut[$value_statuts['id_statut']]['F']);
			$sheet->setCellValue($array_column[16].$num_ligne,$array_statut_special[$value_statuts['id_statut_special']]['F']);
		}
	}
	else
	{
		//echo '<td colspan="2">---</td>';
		$sheet->setCellValue($array_column[15].$num_ligne,'---');
		$sheet->setCellValue($array_column[16].$num_ligne,'---');
	} */
	
	/****************Mvt régime*****************************/
 
/* 	$sql="select * from cpas_mouvements_regimes 
	where 
	((cpas_mouvements_regimes.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_regimes.date_debut_regime <= '".$date_effectif."')) 
	order by cpas_mouvements_regimes.date_debut_regime desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_regimes=fn_ResultToArray($result,'id_mvt_regime');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_regimes!=null)
	{
		foreach($tab_mvt_regimes as $key_regimes=> $value_regimes)
		{
			
			
			$sheet->setCellValue($array_column[17].$num_ligne,$array_regime[$value_regimes['id_regime']]['F']);
			$sheet->setCellValue($array_column[18].$num_ligne,$value_regimes['id_equiv_tp']);
		}
	}
	else
	{
		$sheet->setCellValue($array_column[17].$num_ligne,'---');
		$sheet->setCellValue($array_column[18].$num_ligne,'---');
		//echo '<td colspan="2">---</td>';
	} */
	
	//echo '</tr>';
	

}//fin tab_contrats

/********Close connexion***************/
	mysqli_close($lien);
/*echo '</table>';

echo "
document.getElementById('bnt_gen_effectifs').style.visibility='visible';
";*/


$sheet->setAutoFilter('A1:S1');
$sheet->getStyle('A1:S'.$nb_agents)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:S'.$nb_agents)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:S'.$nb_agents)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 
//$sheet->setAutoSize(true);

/* $writer = new PHPExcel_Writer_Excel2007($workbook);

$name_file='effectifs_'.$date_effectif.'_'.date('His').'.xlsx';

$records = './'.$name_file;

$writer->save($records);


	echo '<javascript>';
	echo 'window.open("'.$name_file.'","_blank");'; */

/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/
echo '<javascript>';
$file_name = 'effectifs_'.$date_effectif.'_'.date('Ymd-His').'.xlsx';
//$temp_xls_name = 'E:\\webserver\\test_cpas_ocmw\\www\\organigramme\\temp\\'.$file_name;
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
include('array_files.php');
$new_xls_name = $array_files['APERCU_EFFECTIF'].$file_name;

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
