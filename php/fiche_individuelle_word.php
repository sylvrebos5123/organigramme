<?php
header ('Content-type: text/html; charset=utf-8'); 
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
require_once $rootpath.'\\organigramme\\PHPWord_0.6.2_Beta\\PHPWord.php';
include('params.php');

/***Fonction pour nettoyer les accents dans le nom du fichier**********************************/
function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
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

/***********Connexion***************************/
include('../connect_db.php');


$sql="
select
cpas_agents.id_agent
,registre_id
,nom
,prenom
,date_naissance
from cpas_agents
JOIN
cpas_signaletiques_agents
on cpas_agents.id_agent=cpas_signaletiques_agents.id_agent
where cpas_agents.id_agent=".$id_agent.";
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Impossible de lire cet agent<br><br>";
	
}

$tab_agents=mysqli_fetch_assoc($result); 

/*************************************************/



$sql="
select
*
from cpas_contrats
where statut='N' and id_agent=".$id_agent.";
";
var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Aucun contrat<br><br>";
	
	exit;
}

$nb_contrats=mysqli_num_rows($result);

mysqli_close($lien);


$tab_contrats=fn_ResultToArray($result,'id_contrat');
var_dump($tab_contrats);
/************************************************/
include('../arrays_libelle/array_type_cadre.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_article_budgetaire.php');
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_statut.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
include('../arrays_libelle/array_regime.php');

/************************************************/
// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Define table style arrays
$styleTable = array('borderSize'=>6, 'borderColor'=>'689F3B', 'cellMargin'=>10, 'size'=>10);
$styleFirstRow = array('borderBottomSize'=>5, 'borderBottomColor'=>'689F3B', 'bgColor'=>'D2F4B5');

// Define cell style arrays
$styleCell = array('valign'=>'center');
$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);

// Define font style for first row
$fontStyle = array('bold'=>true, 'size'=>10, 'align'=>'center');
$paragraphStyle = array('bold'=>true, 'size'=>10,'align' => 'center');
$PHPWord->addParagraphStyle('pStyle', $paragraphStyle);

$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'size'=>12));
$PHPWord->addFontStyle('gros_titre', array('bold'=>true, 'size'=>12, 'color'=>'689F3B','underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE));

$PHPWord->addParagraphStyle('pStyle_center', array('align'=>'center', 'spaceAfter'=>200));

$PHPWord->addParagraphStyle('pStyle_left', array('align'=>'left', 'spaceAfter'=>100));

/********************************************
Signalétique nom, prénom, date naissance
***************/
$txt="Fiche individuelle - ".$tab_agents['nom'].' '.$tab_agents['prenom'];
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'gros_titre','pStyle_center');

/*$txt="NOM: ".$tab_agents['nom'];
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');

$txt="PRENOM: ".$tab_agents['prenom'];
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');*/
$txt="ID REGISTRE: ".$tab_agents['registre_id'];
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');

$txt="DATE DE NAISSANCE: ".transformDate($tab_agents['date_naissance']);
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');


/**********Titre contrat + Date début/fin contrat******************************/
$i=0;
foreach($tab_contrats as $key =>$value) 
{
	$i++;
	$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),array('spaceAfter'=>200));
	if($nb_contrats>1)
	{
		$txt="Détails du contrat ".$i;
	}
	else
	{
		$txt="Détails du contrat";
	}
	$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'gros_titre','pStyle_center');
	
	$txt="DEBUT: ".transformDate($value['start_date']).'        FIN: '.transformDate($value['end_date']);
	$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');
	
	//$txt="FIN: ".transformDate($value['end_date']);
	//$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');
	
	if(($value['motif_sortie']=='')||($value['motif_sortie']==null))
	{
		$txt="MOTIF SORTIE: /";
	}
	else
	{
		$txt="MOTIF SORTIE: ".$value['motif_sortie'];
	}
	$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'pStyle_left');
	
	
	/***********Connexion***************************/
		include('../connect_db.php');

/*****************************************************
	Lecture des Mouvements
	********************************************************/
		$sql="
		select
		*
		from cpas_mouvements_services
		where statut='N' and id_contrat=".$value['id_contrat']." order by date_debut_service desc;
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		if(mysqli_num_rows($result)==0)
		{
			echo "Aucun mvt<br><br>";
			
		}

		$tab_mvt_ser=fn_ResultToArray($result,'id_mvt_service');
		
		/********Titre mouvements service + titre colonnes**********************************/
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),array('spaceAfter'=>200));
		
		$txt="Mouvements de service";
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','pStyle_left');
		
		$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

		// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			
			$txt="Début";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Echéance";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Art. budgétaire";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Dep/Hors dep.";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Service";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Cellule";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
		
		/*****************Lecture*******************************/
		foreach($tab_mvt_ser as $key_ser =>$value_ser) 
		{
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

			// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			$txt=transformDate($value_ser['date_debut_service']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=transformDate($value_ser['date_echeance_service']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$art_budgetaire=explode("-",$array_article_budgetaire[$value_ser['id_article_budgetaire']]['F']);
			$txt=$art_budgetaire[0];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			if(($value_ser['id_hors_dep']==0)||($value_ser['id_hors_dep']=='')||($value_ser['id_hors_dep']==null))
			{
				
				$txt=$array_departement[$value_ser['id_dep']]['F'];
			}
			else
			{
				$txt=$array_hors_departement[$value_ser['id_hors_dep']]['F'];
			}
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_service[$value_ser['id_ser']]['F'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_cellule[$value_ser['id_cel']]['F'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
		}
		
		/*****************************************************
	Lecture des Mouvements fonctions
	********************************************************/
		$sql="
		select
		*
		from cpas_mouvements_fonctions
		where statut='N' and id_contrat=".$value['id_contrat']." order by date_debut_fonction desc;
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		if(mysqli_num_rows($result)==0)
		{
			echo "Aucun mvt<br><br>";
			
		}

		$tab_mvt_fct=fn_ResultToArray($result,'id_mvt_fonction');
		
		/********Titre mouvements fonctions + titre colonnes**********************************/
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),array('spaceAfter'=>200));
		$txt="Mouvements de fonction";
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','pStyle_left');
		
		$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

		// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			
			$txt="Début";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Echéance";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Fonction";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Ouvrier/Employé";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Catégorie";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
		
		/*****************Lecture*******************************/
		foreach($tab_mvt_fct as $key_fct =>$value_fct) 
		{
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

			// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			$txt=transformDate($value_fct['date_debut_fonction']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=transformDate($value_fct['date_echeance_fonction']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_fonction[$value_fct['id_fonc']]['F'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			
			$txt=$value_fct['ouvrier_employe'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$value_fct['categorie'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
		}
		
		/*****************************************************
	Lecture des Mouvements barèmes/codes/grades
	********************************************************/
		$sql="
		select
		*
		from cpas_mouvements_baremes
		where statut='N' and id_contrat=".$value['id_contrat']." order by date_debut_bareme desc;
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		if(mysqli_num_rows($result)==0)
		{
			echo "Aucun mvt<br><br>";
			
		}

		$tab_mvt_baremes=fn_ResultToArray($result,'id_mvt_bareme');
		
		/********Titre mouvements baremes + titre colonnes**********************************/
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),array('spaceAfter'=>200));
		$txt="Mouvements de barème";
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','pStyle_left');
		
		$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

		// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			
			$txt="Début";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Echéance";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Barème/Code";
			$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Grade";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Type de cadre";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="AU CADRE";
			$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
		
		/*****************Lecture*******************************/
		foreach($tab_mvt_baremes as $key_baremes =>$value_baremes) 
		{
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

			// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			$txt=transformDate($value_baremes['date_debut_bareme']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=transformDate($value_baremes['date_echeance_bareme']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_bareme[$value_baremes['id_bareme']].$array_code[$value_baremes['id_code']];
			$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_grade[$value_baremes['id_grade']]['F'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_type_cadre[$value_baremes['type_cadre']];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_bareme[$value_baremes['id_bareme_cadre']].$array_code[$value_baremes['id_code_cadre']];
			$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
		}
		
		
		/*****************************************************
	Lecture des Mouvements statuts
	********************************************************/
		$sql="
		select
		*
		from cpas_mouvements_statuts
		where statut='N' and id_contrat=".$value['id_contrat']." order by date_debut_statut desc;
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		if(mysqli_num_rows($result)==0)
		{
			echo "Aucun mvt<br><br>";
			
		}

		$tab_mvt_statuts=fn_ResultToArray($result,'id_mvt_statut');
		
		/********Titre mouvements statuts + titre colonnes**********************************/
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),array('spaceAfter'=>200));
		$txt="Mouvements de statut";
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','pStyle_left');
		
		$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

		// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			
			$txt="Début";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Echéance";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			
			$txt="Statut";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Statut spécial";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Contractuel/nommé";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
		/*****************Lecture*******************************/
		foreach($tab_mvt_statuts as $key_statuts =>$value_statuts) 
		{
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

			// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			$txt=transformDate($value_statuts['date_debut_statut']);
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=transformDate($value_statuts['date_echeance_statut']);
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_statut[$value_statuts['id_statut']]['F'];
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_statut_special[$value_statuts['id_statut_special']]['F'];
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			
			if($value_statuts['contractuel_nomme']=="N")
			{
				$txt="Nommé";
			}
			else
			{
				$txt="Contractuel";
			}
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
		}
		
			/*****************************************************
	Lecture des Mouvements régimes
	********************************************************/
		$sql="
		select
		*
		from cpas_mouvements_regimes
		where statut='N' and id_contrat=".$value['id_contrat']." order by date_debut_regime desc;
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		if(mysqli_num_rows($result)==0)
		{
			echo "Aucun mvt<br><br>";
			
		}

		$tab_mvt_regimes=fn_ResultToArray($result,'id_mvt_regime');
		
		/********Titre mouvements régimes + titre colonnes**********************************/
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),array('spaceAfter'=>200));
		$txt="Mouvements de régime";
		$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','pStyle_left');
		
		$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

		// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			
			$txt="Début";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Echéance";
			$table->addCell(1500)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			
			$txt="Régime";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt="Equiv. temps plein";
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
		
		/*****************Lecture*******************************/
		foreach($tab_mvt_regimes as $key_regimes =>$value_regimes) 
		{
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

			// Add table
			$table = $section->addTable('myOwnTableStyle');
			
			$table->addRow();
			$txt=transformDate($value_regimes['date_debut_regime']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=transformDate($value_regimes['date_echeance_regime']);
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$array_regime[$value_regimes['id_regime']]['F'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
			$txt=$value_regimes['id_equiv_tp'];
			$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)));
			
		}
}// FIN Foreach contrats



// Save File

/*$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$nom=wd_remove_accents($tab_agents['nom'], 'utf-8');
$prenom=wd_remove_accents($tab_agents['prenom'], 'utf-8');
if(($prenom=='')||($prenom==null)||($prenom=='/'))
{
	$nom_fichier=$nom.'_'.date('Ymd').'_'.date('His').'.docx';
}
else
{
	$nom_fichier=$nom.'_'.$prenom.'_'.date('Ymd').'_'.date('His').'.docx';
}
$objWriter->save($nom_fichier);*/



/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/
echo '<javascript>';
$nom=wd_remove_accents($tab_agents['nom'], 'utf-8');
$prenom=wd_remove_accents($tab_agents['prenom'], 'utf-8');
if(($prenom=='')||($prenom==null)||($prenom=='/'))
{
	$file_name=$nom.'_'.date('Ymd-His').'.docx';
}
else
{
	$file_name=$nom.'_'.$prenom.'_'.date('Ymd-His').'.docx';
}

//$temp_xls_name = 'E:\\webserver\\test_cpas_ocmw\\www\\organigramme\\temp\\'.$file_name;
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
include('array_files.php');
$new_xls_name = $array_files['FICHE_AGENT'].$file_name;

$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');

$records = $temp_xls_name;

$objWriter->save($records); 

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


//echo 'window.open("php/'.$nom_fichier.'","_blank","menubar=no, status=no, scrollbars=no, menubar=no, width=200, height=100");';

?>

