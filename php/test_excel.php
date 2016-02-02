<?php
header ('Content-type: text/html; charset=utf-8'); 
error_reporting(E_ALL);
set_time_limit(0);

//date_default_timezone_set('Europe/London');
if (isset($_GET['nom_fichier']))
 $nom_fichier=trim($_GET['nom_fichier']);
else
{
 if (isset($_POST['nom_fichier']))
  $nom_fichier=trim($_POST['nom_fichier']);
 else
  $nom_fichier='';
}

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

/*******Fonction permettant de mettre le résultat d'une requête dans un array******************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	/*verifier validité de $result*/
	if($result==null)
	{
		echo "pas de paramètre result<br>";
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




include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_statut.php');
include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_regime.php');
include('../arrays_libelle/array_equivalent_temps_plein.php');
include('../arrays_libelle/array_diplome.php');
include('../arrays_libelle/array_selor.php');
include('../arrays_libelle/array_civilite.php');

/***************Lecture des contrats****************************************/
include('../connect_db.php');


$sql=
"
select 
*
from 
cpas_contrats 
where actif=1
;
";

$result=mysqli_query($lien, $sql);


//mysqli_close($lien);

$tab_contrats=fn_ResultToArray($result,"id_contrat");

/**************************Lecture agents + signalétiques****************************************************/

//include('../connect_db.php');

$result=array();


$sql="select 
distinct(cpas_agents.id_agent)
,cpas_agents.nom
,cpas_agents.prenom
,cpas_agents.registre_id
,cpas_agents.genre
,cpas_agents.langue
,cpas_signaletiques_agents.niss
,cpas_signaletiques_agents.nationalite
,cpas_signaletiques_agents.date_naissance
,cpas_signaletiques_agents.id_civilite
,cpas_signaletiques_agents.niveau_etudes
,cpas_signaletiques_agents.libelle_diplome
,cpas_signaletiques_agents.id_selor
,cpas_signaletiques_agents.zone_libre_selor
,cpas_signaletiques_agents.prime_linguistique
,cpas_signaletiques_agents.adresse_domicile
,cpas_signaletiques_agents.num_domicile
,cpas_signaletiques_agents.bte_domicile
,cpas_signaletiques_agents.code_postal
,cpas_signaletiques_agents.localite
,cpas_signaletiques_agents.bxl_hbxl
,cpas_signaletiques_agents.region
,cpas_signaletiques_agents.tel_prive

from 
cpas_agents,cpas_contrats,cpas_signaletiques_agents
where cpas_contrats.actif=1 and cpas_contrats.id_agent=cpas_agents.id_agent and cpas_agents.id_agent=cpas_signaletiques_agents.id_agent
order by cpas_agents.nom";

$result=mysqli_query($lien, $sql);

$nb_agents=mysqli_num_rows($result);

//En tenant compte de la ligne titre dans Excel
$nb_agents=$nb_agents+1;

mysqli_close($lien);

$Row=fn_ResultToArray($result,"id_agent");
/*********************************************/

$tab_dep=array();
$tab_ser=array();
$tab_cel=array();
$tab_OE=array();
$tab_grade=array();
$tab_fct=array();
$tab_cat=array();
$tab_bareme=array();
$tab_code=array();
$tab_date_code=array();
$tab_statut=array();
$tab_date_statut=array();
$tab_regime=array();
$tab_date_regime=array();
$tab_equiv_tp=array();
$tab_date_entree=array();
$tab_date_sortie=array();
$tab_motif_sortie=array();
$tab_art_budgetaire=array();
//$tab_nb_contrats=array();
//$nb_contrats=0;

foreach($tab_contrats as $key => $value)
{
	
	$tab_dep[$value['id_agent']][$value['id_contrat']]= $array_departement[$value['id_dep']]['F'];

	$tab_ser[$value['id_agent']][$value['id_contrat']]= $array_service[$value['id_ser']]['F'];

	$tab_cel[$value['id_agent']][$value['id_contrat']]= $array_cellule[$value['id_cel']]['F'];
	
	$tab_OE[$value['id_agent']][$value['id_contrat']]= $value['ouvrier_employe'];
	
	$tab_grade[$value['id_agent']][$value['id_contrat']]= $array_grade[$value['id_grade']]['F'];
	
	$tab_fct[$value['id_agent']][$value['id_contrat']]= $array_fonction[$value['id_fonc']]['F'];
	
	$tab_cat[$value['id_agent']][$value['id_contrat']]= $value['categorie'];
	
	$tab_bareme[$value['id_agent']][$value['id_contrat']]= $array_bareme[$value['id_bareme']];
	
	$tab_code[$value['id_agent']][$value['id_contrat']]= $array_code[$value['id_code']];
	
	$tab_date_code[$value['id_agent']][$value['id_contrat']]= transformDate($value['date_echeance_code']);
	
	$tab_statut[$value['id_agent']][$value['id_contrat']]= $array_statut[$value['id_statut']]['F'];

	$tab_date_statut[$value['id_agent']][$value['id_contrat']]= transformDate($value['date_echeance_statut']);
	
	$tab_regime[$value['id_agent']][$value['id_contrat']]= $array_regime[$value['id_regime']]['F'];
	
	$tab_date_regime[$value['id_agent']][$value['id_contrat']]= transformDate($value['date_echeance_regime']);
	
	
	$tab_equiv_tp[$value['id_agent']][$value['id_contrat']]= $value['id_equiv_tp'];
	
	
	$tab_art_budgetaire[$value['id_agent']][$value['id_contrat']]=$value['article_budgetaire'];
	
	$tab_date_entree[$value['id_agent']][$value['id_contrat']]=transformDate($value['start_date']);
	
	$tab_date_sortie[$value['id_agent']][$value['id_contrat']]=transformDate($value['end_date']);

	$tab_motif_sortie[$value['id_agent']][$value['id_contrat']]=$value['motif_sortie'];
	
	
}//FIN FOREACH TAB_CONTRATS


/** PHPExcel_IOFactory */
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
//include_once($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\IOFactory.php'); //function de connection
//include '../PHPExcel/IOFactory.php'; // 



echo '<hr />';
include ($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel.php');
include($rootpath.'\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\Writer\\Excel2007.php');

// LETTRE COLONNE  ****** TITRE COLONNE CORRESPONDANTE  *****/    
$array_column=array(); $array_title=array();
$array_column[]='A'; $array_title[]='NOM';
$array_column[]='B'; $array_title[]='Prénom';
$array_column[]='C'; $array_title[]='N°';
$array_column[]='D'; $array_title[]='Langue';
$array_column[]='E'; $array_title[]='N.I.S.S.';
$array_column[]='F'; $array_title[]='Département';
$array_column[]='G'; $array_title[]='Service';
$array_column[]='H'; $array_title[]='Cellule';
$array_column[]='I'; $array_title[]='Employé/Ouvrier';
$array_column[]='J'; $array_title[]='Grade';
$array_column[]='K'; $array_title[]='Fonction';
$array_column[]='L'; $array_title[]='CATEGORIE';
$array_column[]='M'; $array_title[]='Barème';
$array_column[]='N'; $array_title[]='Code';
$array_column[]='O'; $array_title[]='Echéance code';
$array_column[]='P'; $array_title[]='Statut';
$array_column[]='Q'; $array_title[]='Echéance statut';
$array_column[]='R'; $array_title[]='Régime';
$array_column[]='S'; $array_title[]='Echéance régime';
$array_column[]='T'; $array_title[]='Equivalent temps-plein';
$array_column[]='U'; $array_title[]="Date d'engagement";
$array_column[]='V'; $array_title[]="Niveau d'études";
$array_column[]='W'; $array_title[]='Diplômes';
$array_column[]='X'; $array_title[]='Selor';
$array_column[]='Y'; $array_title[]='Prime de bilinguisme';
$array_column[]='Z'; $array_title[]='Date de naissance';
$array_column[]='AA'; $array_title[]='Civilité';
$array_column[]='AB'; $array_title[]='Genre';
$array_column[]='AC'; $array_title[]='Nationalité';
$array_column[]='AD'; $array_title[]='Téléphone';
$array_column[]='AE'; $array_title[]="Rue";
$array_column[]='AF'; $array_title[]="N°";
$array_column[]='AG'; $array_title[]='Boîte';
$array_column[]='AH'; $array_title[]='Code postal';
$array_column[]='AI'; $array_title[]='Localité';
$array_column[]='AJ'; $array_title[]='BXL/HORS BXL';
$array_column[]='AK'; $array_title[]='REGION';
$array_column[]='AL'; $array_title[]="ARTICLE BUDGETAIRE";
$array_column[]='AM'; $array_title[]="DATE DE SORTIE";
$array_column[]='AN'; $array_title[]='MOTIF';

$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();

$styleArrayBorder = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$sheet->getStyle('A1:AN'.$nb_agents)->applyFromArray($styleArrayBorder);
//unset($styleArrayBorder);

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
//$num_colonne=0;
foreach($Row as $key => $value)
{
	$num_ligne++;
	$sheet->setCellValue($array_column[0].$num_ligne,$value['nom']);
	$sheet->setCellValue($array_column[1].$num_ligne,$value['prenom']);
	$sheet->setCellValue($array_column[2].$num_ligne,$value['registre_id']);
	$sheet->setCellValue($array_column[3].$num_ligne,$value['langue']);
	$sheet->setCellValue($array_column[4].$num_ligne,$value['niss']);
	/* $sheet->getStyle('D3')->getAlignment()
->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); */
	
	//if(array_key_exists($value['id_agent'],$tab_dep))
	//{
		$dep='';
		$ser='';
		$cel='';
		$ouvrier_employe='';
		$grade='';
		$fonction="";
		$categorie='';
		$bareme="";
		$code='';
		$date_code='';
		$statut='';
		$date_statut='';
		$regime='';
		$date_regime='';
		$equiv_tp='';
		$date_entree='';
		$date_sortie='';
		$motif_sortie='';
		$art_budgetaire='';
		
		$tab_new_cel=array();
		foreach($tab_dep[$value['id_agent']] as $key2 => $value2)
		{
				$dep.=$tab_dep[$value['id_agent']][$key2]."\r\n";
				$ser.=$tab_ser[$value['id_agent']][$key2]."\r\n";
				//$tab_new_cel=explode('-',$tab_cel[$value['id_agent']][$key2]);
				$cel.=$tab_cel[$value['id_agent']][$key2]."\r\n";
				$ouvrier_employe.=$tab_OE[$value['id_agent']][$key2]."\r\n";
				$grade.=$tab_grade[$value['id_agent']][$key2]."\r\n";
				$fonction.=$tab_fct[$value['id_agent']][$key2]."\r\n";
				$categorie.=$tab_cat[$value['id_agent']][$key2]."\r\n";
				$bareme.=$tab_bareme[$value['id_agent']][$key2]."\r\n";
				$code.=$tab_code[$value['id_agent']][$key2]."\r\n";
				$date_code.=$tab_date_code[$value['id_agent']][$key2]."\r\n";
				$statut.=$tab_statut[$value['id_agent']][$key2]."\r\n";
				$date_statut.=$tab_date_statut[$value['id_agent']][$key2]."\r\n";
				$regime.=$tab_regime[$value['id_agent']][$key2]."\r\n";
				$date_regime.=$tab_date_regime[$value['id_agent']][$key2]."\r\n";
				$equiv_tp.=$tab_equiv_tp[$value['id_agent']][$key2]."\r\n";
				$date_entree.=$tab_date_entree[$value['id_agent']][$key2]."\r\n";
				$date_sortie.=$tab_date_sortie[$value['id_agent']][$key2]."\r\n";
				$motif_sortie.=$tab_motif_sortie[$value['id_agent']][$key2]."\r\n";
				$art_budgetaire.=$tab_art_budgetaire[$value['id_agent']][$key2]."\r\n";
		}
			//echo "DEP".$dep;
				$sheet->setCellValue($array_column[5].$num_ligne,$dep);
				//$sheet->getStyle($array_column[5].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[6].$num_ligne,$ser);
				//$sheet->getStyle($array_column[6].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[7].$num_ligne,$cel);
				//$sheet->getStyle($array_column[7].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[8].$num_ligne,$ouvrier_employe);
				//$sheet->getStyle($array_column[8].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[9].$num_ligne,$grade);
				//$sheet->getStyle($array_column[9].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[10].$num_ligne,$fonction);
				//$sheet->getStyle($array_column[10].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[11].$num_ligne,$categorie);
				//$sheet->getStyle($array_column[11].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[12].$num_ligne,$bareme);
				//$sheet->getStyle($array_column[12].$num_ligne)->getAlignment()->setWrapText(true);

				$sheet->setCellValue($array_column[13].$num_ligne,$code);
				//$sheet->getStyle($array_column[13].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[14].$num_ligne,$date_code);
				//$sheet->getStyle($array_column[14].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[15].$num_ligne,$statut);
				//$sheet->getStyle($array_column[15].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[16].$num_ligne,$date_statut);
				//$sheet->getStyle($array_column[16].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[17].$num_ligne,$regime);
				//$sheet->getStyle($array_column[17].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[18].$num_ligne,$date_regime);
				//$sheet->getStyle($array_column[18].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[19].$num_ligne,$equiv_tp);
				//$sheet->getStyle($array_column[19].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[20].$num_ligne,$date_entree);
				//$sheet->getStyle($array_column[20].$num_ligne)->getAlignment()->setWrapText(true);
				
				if($value['niveau_etudes']!=0)
				{
					$sheet->setCellValue($array_column[21].$num_ligne,$array_diplome[$value['niveau_etudes']]['F']);
				}
				$sheet->setCellValue($array_column[22].$num_ligne,$value['libelle_diplome']);
				//$sheet->getStyle($array_column[22].$num_ligne)->getAlignment()->setWrapText(true);
				
				$selor='';
				if($value['id_selor']!=0)
				{
					$selor.=$array_selor[$value['id_selor']]['F']."\r\n";
				}
				
				if($value['zone_libre_selor']!=0)
				{
					$selor.=$array_selor[$value['zone_libre_selor']]['F']."\r\n";
				}
				$sheet->setCellValue($array_column[23].$num_ligne,$selor);
				//$sheet->getStyle($array_column[23].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[24].$num_ligne,$value['prime_linguistique']);
				$sheet->setCellValue($array_column[25].$num_ligne,transformDate($value['date_naissance']));
				
				if($value['id_civilite']!=0)
				{
					$sheet->setCellValue($array_column[26].$num_ligne,$array_civilite[$value['id_civilite']][$value['langue']]);
				}
				
				if($value['genre']==1)
				{
					$sheet->setCellValue($array_column[27].$num_ligne,'M');
				}
				else
				{
					$sheet->setCellValue($array_column[27].$num_ligne,'F');
				}
				$sheet->setCellValue($array_column[28].$num_ligne,$value['nationalite']);
				$sheet->setCellValue($array_column[29].$num_ligne,$value['tel_prive']);
				$sheet->setCellValue($array_column[30].$num_ligne,$value['adresse_domicile']);
				$sheet->setCellValue($array_column[31].$num_ligne,$value['num_domicile']);
				$sheet->setCellValue($array_column[32].$num_ligne,$value['bte_domicile']);
				$sheet->setCellValue($array_column[33].$num_ligne,$value['code_postal']);
				$sheet->setCellValue($array_column[34].$num_ligne,$value['localite']);
				$sheet->setCellValue($array_column[35].$num_ligne,$value['bxl_hbxl']);
				$sheet->setCellValue($array_column[36].$num_ligne,$value['region']);
			/********************************/	
			
				$sheet->setCellValue($array_column[37].$num_ligne,$art_budgetaire);
				//$sheet->getStyle($array_column[37].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[38].$num_ligne,$date_sortie);
				//$sheet->getStyle($array_column[38].$num_ligne)->getAlignment()->setWrapText(true);
				
				$sheet->setCellValue($array_column[39].$num_ligne,$motif_sortie);
				//$sheet->getStyle($array_column[39].$num_ligne)->getAlignment()->setWrapText(true);
	

}//FIN foreach $Row





$sheet->setAutoFilter('A1:AN1');
$sheet->getStyle('A1:AN'.$nb_agents)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:AN'.$nb_agents)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:AN'.$nb_agents)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 
//$sheet->setAutoSize(true);

$writer = new PHPExcel_Writer_Excel2007($workbook);
$name_file=$nom_fichier.'_'.date('His').'.xlsx';
$records = './'.$name_file;

$writer->save($records);


	echo '<javascript>';
	echo 'window.open("php/'.$name_file.'","_blank");';

//} else {
 //   echo "alert('Le fichier $nom_fichier n\'existe pas.');";
//}
//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//$sheetData = $objPHPExcel->getActiveSheet()->setAutoFilter('A1:A8');
//var_dump($sheet);
//$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//var_dump($sheet);

// Entêtes (headers) PHP qui vont bien pour la création d'un fichier Excel CSV
 /* header( "Content-type: application/vnd.ms-excel; charset=latin1" );
 header("Content-disposition: attachment; filename=fichier.xls");
 //header('Content-Type: text/html; charset=utf-8');
 header("Content-Type: application/force-download");
 //header("Content-Transfer-Encoding: application/vnd.ms-excel\n");
 
 header("Pragma: no-cache");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
 header("Expires: 0"); */
 
 //echo utf8_decode($outputCsv);  // On 
?>

