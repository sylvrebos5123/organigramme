<?php
header ('Content-type: text/html; charset=utf-8'); 
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
require_once $rootpath.'\\organigramme\\PHPWord_0.6.2_Beta\\PHPWord.php';
echo $rootpath.'\\organigramme\\PHPWord_0.6.2_Beta\\PHPWord.php';
include('params.php');
include('array_files.php');

echo '<javascript>';
if(($date_situation_effectifs_tableau=='')||($date_situation_effectifs_tableau=='00-00-0000')||($date_situation_effectifs_tableau=='0000-00-00')||($date_situation_effectifs_tableau==null))
{
	echo "alert('Veuillez sélectionner une date de situation pour les effectifs.');";
	
	exit;
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
/****************************************************************/

$date_situation_effectifs=$date_situation_effectifs_tableau;
$date_effectif=transformDate($date_situation_effectifs);
$new_date_effectif=str_replace('-', '', $date_effectif);

/************************************************************************
**************Construction des effectifs à une date donnée************
************************************************************************/
include('creation_table_effectifs.php');


/***********Lecture des agents à la tête du département***************************/
include('../connect_db.php');



$sql="select 
*
from 
cpas_effectifs_".$new_date_effectif."
where id_dep=".$id_dep." 
and id_ser=0 
and id_cel=0 
and registre_id<990000
order by nom,prenom;";

$result=mysqli_query($lien, $sql);

$tab_agents_dep=fn_ResultToArray($result,'id_contrat'); 

/*************************************************/


$sql="select 

distinct(id_ser)

from 

cpas_effectifs_".$new_date_effectif."

where id_dep=".$id_dep." and id_ser<>0

;";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun service');";
	
	exit;
}

mysqli_close($lien);


$tab_services=fn_ResultToArray($result,'id_ser');
//var_dump($tab_services);
/************************************************/
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');
include('../arrays_libelle/array_statut.php');
include('../arrays_libelle/array_grade.php');
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
$PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));

$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','Personnel '.$array_departement[$id_dep]['F'])).' - Situation au '.$date_situation_effectifs,'rStyle','pStyle');



foreach($tab_agents_dep as $key_agents_dep =>$value_agents_dep) 
{
	$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

// Add table
	$table = $section->addTable('myOwnTableStyle');
	
	$table->addRow();
	$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$value_agents_dep['nom']." ".$value_agents_dep['prenom'])));
	
	if(($value_agents_dep['date_echeance_regime']=='0000-00-00')||($value_agents_dep['date_echeance_regime']=='00-00-0000'))
	{
		$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_dep['id_regime']]['F'])));
	}
	else
	{
		$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_dep['id_regime']]['F'].' '.$value_agents_dep['date_echeance_regime'])));
	
	}
	$table->addCell(1000)->addText($value_agents_dep['langue']);
	$table->addCell(3000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_dep['id_grade']]['F'])));
	
	if($value_agents_dep['id_statut_special']==0)
	{
		$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_dep['id_statut']]['F'])));
	
	}
	else
	{
		$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_dep['id_statut']]['F'].' '.$array_statut_special[$value_agents_dep['id_statut_special']]['F'])));
	}
	
}

$i=0;

foreach($tab_services as $key_ser =>$value_ser) 
{
/********************************************************
Lecture titre du service
***********************************/	

	// Define cell style arrays
	$styleCell = array('valign'=>'center','bgColor'=>'FFD04F');
	
	$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
	$table = $section->addTable('myOwnTableStyle');
	//$title=html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value['id_ser']]['F']));
	$table->addRow(500);
	//$table->setMTitle($title);
	$table->addCell(5000,$styleCell)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value_ser['id_ser']]['F'])),$fontStyle);

/***************************************************
Lecture des agents faisant partie d'un service (et pas d'une cellule)
********************************/	
	include('../connect_db.php');
	
	$sql="select 
	*
	from 
	cpas_effectifs_".$new_date_effectif."
	where id_ser=".$value_ser['id_ser']." 
	and id_cel=0 
	and registre_id<990000
	order by nom,prenom;";
	
	
	$result=mysqli_query($lien, $sql);

	
	if(mysqli_num_rows($result)>0)
	{
		$tab_agents_ser=fn_ResultToArray($result,'id_contrat');
		//var_dump($tab_agents_ser);
		
		foreach($tab_agents_ser as $key_agents_ser =>$value_agents_ser) 
		{
				$i++;
				$table->addRow();
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_ser['nom']." ".$value_agents_ser['prenom'])));
				
				if(($value_agents_ser['date_echeance_regime']=='0000-00-00')||($value_agents_ser['date_echeance_regime']=='00-00-0000'))
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_ser['id_regime']]['F'])));
				}
				else
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_ser['id_regime']]['F'].' '.$value_agents_ser['date_echeance_regime'])));
				
				}
				$table->addCell(1000)->addText($value_agents_ser['langue']);
				$table->addCell(3000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_ser['id_grade']]['F'])));
				
				if($value_agents_ser['id_statut_special']==0)
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_ser['id_statut']]['F'])));
				
				}
				else
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_ser['id_statut']]['F'].' '.$array_statut_special[$value_agents_ser['id_statut_special']]['F'])));
				}
		}// FIN FOREACH TAB_AGENTS_SER
	}
		$i=0;
		
		
		$sql="select 

		distinct(id_cel)

		from 

		cpas_effectifs_".$new_date_effectif."

		where id_ser=".$value_ser['id_ser']." and id_cel<>0

		;";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		
		if(mysqli_num_rows($result)>0)
		{
		
/*******************************************************************************		
Si une cellule existe, lire le titre de la cellule + les agents contenus à l'intérieur
*******************************************************************************/
			$tab_cellules=fn_ResultToArray($result,'id_cel');
			//var_dump($tab_cellules);
		
			foreach($tab_cellules as $key3 =>$value3) 
			{
				
					$styleCell = array('valign'=>'center','bgColor'=>'cccccc');
			
					$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
					$table = $section->addTable('myOwnTableStyle');
					//$title=html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value['id_ser']]['F']));
					$table->addRow(500);
					//$table->setMTitle($title);
					$table->addCell(5000,$styleCell)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_cellule[$value3['id_cel']]['F'])),$fontStyle);


				$sql="select 
				*
				from 
				cpas_effectifs_".$new_date_effectif."
				where 
				id_cel=".$value3['id_cel']." 
				and registre_id<990000
				order by nom,prenom;";
				
				//var_dump($sql);
				$result=mysqli_query($lien, $sql);

				
				if(mysqli_num_rows($result)>0)
				{
					$tab_agents_cel=fn_ResultToArray($result,'id_contrat');
					//var_dump($tab_agents_cel);
					
					foreach($tab_agents_cel as $key_agents_cel =>$value_agents_cel) 
					{
						
						
							$i++;
							$table->addRow();
							$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_cel['nom']." ".$value_agents_cel['prenom'])));
							
							if(($value_agents_cel['date_echeance_regime']=='0000-00-00')||($value_agents_cel['date_echeance_regime']=='00-00-0000'))
							{
								$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_cel['id_regime']]['F'])));
							}
							else
							{
								$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_cel['id_regime']]['F'].' '.$value_agents_cel['date_echeance_regime'])));
							
							}
							$table->addCell(1000)->addText($value_agents_cel['langue']);
							$table->addCell(3000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_cel['id_grade']]['F'])));
							
							if($value_agents_cel['id_statut_special']==0)
							{
								$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_cel['id_statut']]['F'])));
							
							}
							else
							{
								$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_cel['id_statut']]['F'].' '.$array_statut_special[$value_agents_cel['id_statut_special']]['F'])));
							}
						
					}// FIN FOREACH TAB_AGENTS_CEL
				}
				$i=0;

			}// FIN FOREACH TAB_CEL
		
		}

		mysqli_close($lien);

 }//FIN FOREACH TAB_SERVICES



/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/

$file_name = 'tableau_personnel_'.$id_dep.'_'.$new_date_effectif.'_'.date('Ymd-His').'.docx';
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès

$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');

$records = $temp_xls_name;

$objWriter->save($records); 

// génération du fichier xlsx dans  $temp_xls_name
$xlsx_genrate = false;
if (file_exists($temp_xls_name))
{
 
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

