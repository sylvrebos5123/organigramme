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

include('verification.php');
include('params.php');

echo '<javascript>';
if(($date_situation_effectifs=='')||($date_situation_effectifs=='00-00-0000')||($date_situation_effectifs=='0000-00-00')||($date_situation_effectifs==null))
{
	echo "alert('Veuillez sélectionner une date de situation pour les effectifs.');";
	
	exit;
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



include('../arrays_libelle/array_article_budgetaire.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_hors_departement.php');
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
include('../arrays_libelle/array_type_cadre.php');
include('../arrays_libelle/array_contractuel_nomme.php');
/****************************************************************/


$date_effectif=transformDate($date_situation_effectifs);
$new_date_effectif=str_replace('-', '', $date_effectif);

/************************************************************************
**************Construction des effectifs à une date donnée************
************************************************************************/
include('creation_table_effectifs.php');



/***************Lecture des contrats****************************************/
include('../connect_db.php');


$sql=
"
select 
*
from 
cpas_contrats 
where 
start_date <= '".$date_effectif."'
AND
(end_date >= '".$date_effectif."' or end_date='0000-00-00' or end_date='' or end_date is null)
AND
statut='N';
;
";

$result=mysqli_query($lien, $sql);



$tab_contrats=fn_ResultToArray($result,"id_contrat");

/**************************Lecture agents + signalétiques****************************************************/


$result=array();



$sql="select 
*
from 
cpas_effectifs_".$new_date_effectif."
where registre_id<990000
order by nom,prenom;";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

mysqli_close($lien);

$Row=fn_ResultToArray($result,"id_agent");

$nb_agents=count($Row)+1;

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
$tab_grade_cadre=array();
$tab_bareme_cadre=array();
$tab_code_cadre=array();
$tab_type_cadre=array();
$tab_statut_special=array();
$tab_contractuel_nomme=array();
$tab_hors_dep=array();


foreach($tab_contrats as $key => $value)
{
	if(($value['id_hors_dep']==0)||($value['id_hors_dep']=='')||($value['id_hors_dep']==null))
	{
		$tab_dep[$value['id_agent']][$value['id_contrat']]= $array_departement[$value['id_dep']]['F'];
		$tab_hors_dep[$value['id_agent']][$value['id_contrat']]='';
	
	}
	else
	{
		$tab_dep[$value['id_agent']][$value['id_contrat']]= '';
		$tab_hors_dep[$value['id_agent']][$value['id_contrat']]=$array_hors_departement[$value['id_hors_dep']]['F'];
	}
	
	
	$tab_ser[$value['id_agent']][$value['id_contrat']]= $array_service[$value['id_ser']]['F'];

	$tab_cel[$value['id_agent']][$value['id_contrat']]= $array_cellule[$value['id_cel']]['F'];
	
	$tab_OE[$value['id_agent']][$value['id_contrat']]= $value['ouvrier_employe'];
	
	$tab_grade[$value['id_agent']][$value['id_contrat']]= $array_grade[$value['id_grade']]['F'];
	
	$tab_fct[$value['id_agent']][$value['id_contrat']]= $array_fonction[$value['id_fonc']]['F'];
	
	$tab_cat[$value['id_agent']][$value['id_contrat']]= $value['categorie'];
	
	$tab_bareme[$value['id_agent']][$value['id_contrat']]= $array_bareme[$value['id_bareme']];
	
	$tab_code[$value['id_agent']][$value['id_contrat']]= $array_code[$value['id_code']];
	
	$tab_date_code[$value['id_agent']][$value['id_contrat']]= transformDate($value['date_echeance_bareme']);
	
	$tab_statut[$value['id_agent']][$value['id_contrat']]= $array_statut[$value['id_statut']]['F'];

	$tab_date_statut[$value['id_agent']][$value['id_contrat']]= transformDate($value['date_echeance_statut']);
	
	$tab_regime[$value['id_agent']][$value['id_contrat']]= $array_regime[$value['id_regime']]['F'];
	
	$tab_date_regime[$value['id_agent']][$value['id_contrat']]= transformDate($value['date_echeance_regime']);
	
	$tab_equiv_tp[$value['id_agent']][$value['id_contrat']]= $value['id_equiv_tp'];
	
	$art_budgetaire=explode("-",$array_article_budgetaire[$value['id_article_budgetaire']]['F']);
	$tab_art_budgetaire[$value['id_agent']][$value['id_contrat']]=$art_budgetaire[0];
	
	$tab_date_entree[$value['id_agent']][$value['id_contrat']]=transformDate($value['start_date']);
	
	$tab_date_sortie[$value['id_agent']][$value['id_contrat']]=transformDate($value['end_date']);

	$tab_motif_sortie[$value['id_agent']][$value['id_contrat']]=$value['motif_sortie'];
	
	$tab_grade_cadre[$value['id_agent']][$value['id_contrat']]=$array_grade[$value['id_grade_cadre']]['F'];
	
	$tab_bareme_cadre[$value['id_agent']][$value['id_contrat']]=$array_bareme[$value['id_bareme_cadre']];
	
	$tab_code_cadre[$value['id_agent']][$value['id_contrat']]=$array_code[$value['id_code_cadre']];
	if(($value['type_cadre']=='')||($value['type_cadre']==null))
	{
		$value['type_cadre']=0;
	}
	$tab_type_cadre[$value['id_agent']][$value['id_contrat']]=$array_type_cadre[$value['type_cadre']];
	
	$tab_statut_special[$value['id_agent']][$value['id_contrat']]=$array_statut_special[$value['id_statut_special']]['F'];
	if($value['contractuel_nomme']=='C')
	{
		$tab_contractuel_nomme[$value['id_agent']][$value['id_contrat']]="Contractuel";
	}
	else
	{
		if($value['contractuel_nomme']=='N')
		{
			$tab_contractuel_nomme[$value['id_agent']][$value['id_contrat']]="Nommé";
		}
		else
		{
			$tab_contractuel_nomme[$value['id_agent']][$value['id_contrat']]="";
		}
	
	}
	
}//FIN FOREACH TAB_CONTRATS


/** PHPExcel_IOFactory */
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

include ($rootpath.'\\organigramme\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel.php');
include($rootpath.'\\organigramme\\PHPExcel_1.8.0_doc\\Classes\\PHPExcel\\Writer\\Excel2007.php');

// LETTRE COLONNE  ****** TITRE COLONNE CORRESPONDANTE  *****/    
$array_column=array(); $array_title=array();
$array_column[]='A'; $array_title[]='NOM';
$array_column[]='B'; $array_title[]='Prénom';
$array_column[]='C'; $array_title[]='N°';
$array_column[]='D'; $array_title[]='Langue';
$array_column[]='E'; $array_title[]='N.I.S.S.';
$array_column[]='F'; $array_title[]='Dep./Hors dep.';
$array_column[]='G'; $array_title[]='Service';
$array_column[]='H'; $array_title[]='Cellule';
$array_column[]='I'; $array_title[]='Employé/Ouvrier';
$array_column[]='J'; $array_title[]='Contractuel/Nommé'; 
$array_column[]='K'; $array_title[]='Type de cadre';
$array_column[]='L'; $array_title[]='Grade cadre';
$array_column[]='M'; $array_title[]='Barème cadre';
$array_column[]='N'; $array_title[]='Code cadre';
$array_column[]='O'; $array_title[]='Grade';
$array_column[]='P'; $array_title[]='Fonction';
$array_column[]='Q'; $array_title[]='CATEGORIE';
$array_column[]='R'; $array_title[]='Barème';
$array_column[]='S'; $array_title[]='Code';
$array_column[]='T'; $array_title[]='Echéance barème/code';
$array_column[]='U'; $array_title[]='Statut';
$array_column[]='V'; $array_title[]='Statut spécial';
$array_column[]='W'; $array_title[]='Echéance statut';
$array_column[]='X'; $array_title[]='Régime';
$array_column[]='Y'; $array_title[]='Echéance régime';
$array_column[]='Z'; $array_title[]='Equivalent temps-plein';
$array_column[]='AA'; $array_title[]="Date d'engagement";
$array_column[]='AB'; $array_title[]="Niveau d'études";
$array_column[]='AC'; $array_title[]='Diplômes';
$array_column[]='AD'; $array_title[]='Selor';
$array_column[]='AE'; $array_title[]='Prime de bilinguisme';
$array_column[]='AF'; $array_title[]='Date de naissance';
$array_column[]='AG'; $array_title[]='Civilité';
$array_column[]='AH'; $array_title[]='Genre';
$array_column[]='AI'; $array_title[]='Nationalité';
$array_column[]='AJ'; $array_title[]='Téléphone';
$array_column[]='AK'; $array_title[]="Rue";
$array_column[]='AL'; $array_title[]="N°";
$array_column[]='AM'; $array_title[]='Boîte';
$array_column[]='AN';$array_title[]='Code postal'; 
$array_column[]='AO';$array_title[]='Localité';
$array_column[]='AP';$array_title[]='BXL/HORS BXL';
$array_column[]='AQ';$array_title[]='REGION';
$array_column[]='AR';$array_title[]="ARTICLE BUDGETAIRE";
$array_column[]='AS';$array_title[]="DATE DE SORTIE";
$array_column[]='AT';$array_title[]='MOTIF';


$workbook = new PHPExcel;

$sheet = $workbook->getActiveSheet();

$styleArrayBorder = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$sheet->getStyle('A1:AT'.$nb_agents)->applyFromArray($styleArrayBorder);

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

//Affectations de chaque records dans le fichier xls
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
		$grade_cadre='';
		$bareme_cadre='';
		$code_cadre='';
		$type_cadre='';
		$statut_special='';
		$contractuel_nomme='';
		$hors_dep='';
		
		//$tab_new_cel=array();
		foreach($tab_dep[$value['id_agent']] as $key2 => $value2)
		{
				$dep.=$tab_dep[$value['id_agent']][$key2]."\r\n";
				$ser.=$tab_ser[$value['id_agent']][$key2]."\r\n";
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
				$grade_cadre.=$tab_grade_cadre[$value['id_agent']][$key2]."\r\n";
				$bareme_cadre.=$tab_bareme_cadre[$value['id_agent']][$key2]."\r\n";
				$code_cadre.=$tab_code_cadre[$value['id_agent']][$key2]."\r\n";
				$type_cadre.=$tab_type_cadre[$value['id_agent']][$key2]."\r\n";
				$statut_special.=$tab_statut_special[$value['id_agent']][$key2]."\r\n";
				$contractuel_nomme.=$tab_contractuel_nomme[$value['id_agent']][$key2]."\r\n";
				$hors_dep.=$tab_hors_dep[$value['id_agent']][$key2]."\r\n";
		}
			//echo "DEP".$dep;
				$sheet->setCellValue($array_column[5].$num_ligne,$dep.$hors_dep);
				
				
				$sheet->setCellValue($array_column[6].$num_ligne,$ser);
				

				$sheet->setCellValue($array_column[7].$num_ligne,$cel);
			

				$sheet->setCellValue($array_column[8].$num_ligne,$ouvrier_employe);
				
				$sheet->setCellValue($array_column[9].$num_ligne,$contractuel_nomme);
				
				$sheet->setCellValue($array_column[10].$num_ligne,$type_cadre);

				$sheet->setCellValue($array_column[11].$num_ligne,$grade_cadre);
				
				$sheet->setCellValue($array_column[12].$num_ligne,$bareme_cadre);
				

				$sheet->setCellValue($array_column[13].$num_ligne,$code_cadre);
				
				$sheet->setCellValue($array_column[14].$num_ligne,$grade);
				

				$sheet->setCellValue($array_column[15].$num_ligne,$fonction);
				

				$sheet->setCellValue($array_column[16].$num_ligne,$categorie);
				

				$sheet->setCellValue($array_column[17].$num_ligne,$bareme);
				

				$sheet->setCellValue($array_column[18].$num_ligne,$code);
				
				
				$sheet->setCellValue($array_column[19].$num_ligne,$date_code);
				
				
				$sheet->setCellValue($array_column[20].$num_ligne,$statut);
				
				$sheet->setCellValue($array_column[21].$num_ligne,$statut_special);
				
				$sheet->setCellValue($array_column[22].$num_ligne,$date_statut);
				
				
				$sheet->setCellValue($array_column[23].$num_ligne,$regime);
				
				
				$sheet->setCellValue($array_column[24].$num_ligne,$date_regime);
				
				
				$sheet->setCellValue($array_column[25].$num_ligne,$equiv_tp);
				
				
				$sheet->setCellValue($array_column[26].$num_ligne,$date_entree);
				
				
				if($value['niveau_etudes']!=0)
				{
					$sheet->setCellValue($array_column[27].$num_ligne,$array_diplome[$value['niveau_etudes']]['F']);
				}
				$sheet->setCellValue($array_column[28].$num_ligne,$value['libelle_diplome']);
				
				
				$selor='';
				if($value['id_selor']!=0)
				{
					$selor.=$array_selor[$value['id_selor']]['F']."\r\n";
				}
				
				if($value['zone_libre_selor']!=0)
				{
					$selor.=$array_selor[$value['zone_libre_selor']]['F']."\r\n";
				}
				$sheet->setCellValue($array_column[29].$num_ligne,$selor);
				
				
				$sheet->setCellValue($array_column[30].$num_ligne,$value['prime_linguistique']);
				$sheet->setCellValue($array_column[31].$num_ligne,transformDate($value['date_naissance']));
				
				if($value['id_civilite']!=0)
				{
					$sheet->setCellValue($array_column[32].$num_ligne,$array_civilite[$value['id_civilite']][$value['langue']]);
				}
				
				if($value['genre']==1)
				{
					$sheet->setCellValue($array_column[33].$num_ligne,'M');
				}
				else
				{
					$sheet->setCellValue($array_column[33].$num_ligne,'F');
				}
				$sheet->setCellValue($array_column[34].$num_ligne,$value['nationalite']);
				$sheet->setCellValue($array_column[35].$num_ligne,$value['tel_prive']);
				$sheet->setCellValue($array_column[36].$num_ligne,$value['adresse_domicile']);
				$sheet->setCellValue($array_column[37].$num_ligne,$value['num_domicile']);
				$sheet->setCellValue($array_column[38].$num_ligne,$value['bte_domicile']);
				$sheet->setCellValue($array_column[39].$num_ligne,$value['code_postal']);
				$sheet->setCellValue($array_column[40].$num_ligne,$value['localite']);
				$sheet->setCellValue($array_column[41].$num_ligne,$value['bxl_hbxl']);
				$sheet->setCellValue($array_column[42].$num_ligne,$value['region']);
			/********************************/	
			
				$sheet->setCellValue($array_column[43].$num_ligne,$art_budgetaire);
				
				$sheet->setCellValue($array_column[44].$num_ligne,$date_sortie);
					
				$sheet->setCellValue($array_column[45].$num_ligne,$motif_sortie);

}//FIN foreach $Row


//filtre colonnes + alignement des cellules
$sheet->setAutoFilter('A1:AT1');
$sheet->getStyle('A1:AT'.$nb_agents)->getAlignment()
		->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:AT'.$nb_agents)->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:AT'.$nb_agents)->getAlignment()
		->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); 


/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/

$file_name = $nom_fichier.'_'.$new_date_effectif.'_'.date('Ymd-His').'.xlsx';
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
//include('array_files.php');
//$new_xls_name = $array_files['DATABASE_PERSO'].$file_name;

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

