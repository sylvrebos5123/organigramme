<?php
header ('Content-type: text/html; charset=utf-8'); 
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
require_once $rootpath.'\\organigramme\\PHPWord_0.6.2_Beta\\PHPWord.php';
include('params.php');



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

/***********Lecture des agents à la tête du département***************************/
include('../connect_db.php');


$sql="
select
cpas_contrats.id_agent 
,cpas_contrats.id_contrat
,nom
,prenom
,id_regime
,date_echeance_regime
,langue
,id_ser
,id_cel
,id_grade
,id_statut
from cpas_agents
JOIN
cpas_contrats
on cpas_agents.id_agent=cpas_contrats.id_agent
where cpas_contrats.id_dep=".$id_dep."
and cpas_contrats.id_ser=0
and cpas_contrats.id_cel=0
and cpas_contrats.actif=1
order by nom;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Aucun agent<br><br>";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

	//exit;
}

//mysqli_close($lien);


$tab_agents_dep=fn_ResultToArray($result,'id_contrat'); 
//var_dump($tab_agents_dep);
/*************************************************/

//include('../connect_db.php');


$sql="
select
*
from cpas_services
where id_dep=".$id_dep." and actif=1;
";
var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "Aucun service<br><br>";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

	exit;
}

mysqli_close($lien);


$tab_services=fn_ResultToArray($result,'id_ser');
var_dump($tab_services);
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
$styleTable = array('borderSize'=>6, 'borderColor'=>'689F3B', 'cellMargin'=>20, 'size'=>10);
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
//$section->addText('I am styled by two style definitions.', 'rStyle', 'pStyle');
//$section->addText('I have only a paragraph style definition.', null, 'pStyle');

//$section->addText('I am styled by two style definitions.', 'rStyle', 'pStyle');
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','Personnel '.$array_departement[$id_dep]['F'])).' - Situation au '.date('d-m-Y'),'rStyle','pStyle');



foreach($tab_agents_dep as $key_agents_dep =>$value_agents_dep) 
{
	$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

// Add table
	$table = $section->addTable('myOwnTableStyle');
	
	$table->addRow();
	$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$value_agents_dep['nom']." ".$value_agents_dep['prenom'])));
	$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_dep['id_regime']]['F'].' '.$value_agents_dep['date_echeance_regime'])));
	
	$table->addCell(2000)->addText($value_agents_dep['langue']);
	$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_dep['id_grade']]['F'])));
	
	$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_dep['id_statut']]['F'])));
	
}

$i=0;

foreach($tab_services as $key_ser =>$value_ser) 
{
/********************************************************
Lecture titre du service
***********************************/	

	// Define cell style arrays
	$styleCell = array('valign'=>'center','bgColor'=>'cccccc');
	
	$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
	$table = $section->addTable('myOwnTableStyle');
	//$title=html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value['id_ser']]['F']));
	$table->addRow(900);
	//$table->setMTitle($title);
	$table->addCell(9000,$styleCell)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value_ser['id_ser']]['F'])),$fontStyle);

/***************************************************
Lecture des agents faisant partie d'un service (et pas d'une cellule)
********************************/	
	include('../connect_db.php');

	$sql="
	select
	cpas_contrats.id_agent 
	,cpas_contrats.id_contrat
	,nom
	,prenom
	,id_regime
	,date_echeance_regime
	,langue
	,id_ser
	,id_cel
	,id_grade
	,id_statut
	from cpas_agents
	JOIN
	cpas_contrats
	on cpas_agents.id_agent=cpas_contrats.id_agent
	where cpas_contrats.id_ser=".$value_ser['id_ser']."
	and cpas_contrats.id_cel=0
	and cpas_contrats.actif=1
	order by id_ser,nom;
	";
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	if(mysqli_num_rows($result)==0)
	{
		echo "Aucun agent<br><br>";
		//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

		//exit;
	}
	else
	{
		$tab_agents_ser=fn_ResultToArray($result,'id_contrat');
		//var_dump($tab_agents_ser);
		
		foreach($tab_agents_ser as $key_agents_ser =>$value_agents_ser) 
		{
			//if(($value['id_ser']==$value2['id_ser']) && ($value2['id_cel']==0))
			//{
				$i++;
				$table->addRow();
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_ser['nom']." ".$value_agents_ser['prenom'])));
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_ser['id_regime']]['F'].' '.$value_agents_ser['date_echeance_regime'])));
				
				$table->addCell(2000)->addText($value_agents_ser['langue']);
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_ser['id_grade']]['F'])));
				
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_ser['id_statut']]['F'])));
			//}
			//else
			//{
				
			//}
		}// FIN FOREACH TAB_AGENTS_SER
	}
		$i=0;
		
		$sql="
		select
		*
		from cpas_cellules
		where id_ser=".$value_ser['id_ser'].";
		";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		if(mysqli_num_rows($result)==0)
		{
			echo "Aucune cellule<br><br>";


		}
		else
		{
		
/*******************************************************************************		
Si une cellule existe, lire le titre de la cellule + les agents contenus à l'intérieur
*******************************************************************************/
			$tab_cellules=fn_ResultToArray($result,'id_cel');
			//var_dump($tab_cellules);
		
			foreach($tab_cellules as $key3 =>$value3) 
			{
				//if($value['id_cel']==$value3['id_cel'])
				//{
					$styleCell = array('valign'=>'center','bgColor'=>'FFBF00');
			
					$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
					$table = $section->addTable('myOwnTableStyle');
					//$title=html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value['id_ser']]['F']));
					$table->addRow(900);
					//$table->setMTitle($title);
					$table->addCell(9000,$styleCell)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_cellule[$value3['id_cel']]['F'])),$fontStyle);

				//}
				
				//include('../connect_db.php');


				$sql="
				select
				cpas_contrats.id_agent 
				,cpas_contrats.id_contrat
				,nom
				,prenom
				,id_regime
				,date_echeance_regime
				,langue
				,id_ser
				,id_cel
				,id_grade
				,id_statut
				from cpas_agents
				JOIN
				cpas_contrats
				on cpas_agents.id_agent=cpas_contrats.id_agent
				where cpas_contrats.id_cel=".$value3['id_cel']."
				and cpas_contrats.actif=1
				order by id_cel,nom;
				";
				//var_dump($sql);
				$result=mysqli_query($lien, $sql);

				if(mysqli_num_rows($result)==0)
				{
					echo "Aucun agent<br><br>";
					//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

					//exit;
				}
				else
				{
					$tab_agents_cel=fn_ResultToArray($result,'id_contrat');
					//var_dump($tab_agents_cel);
					
					foreach($tab_agents_cel as $key_agents_cel =>$value_agents_cel) 
					{
						
						//if($value['id_ser']==$value2['id_ser'])
						//{
							$i++;
							$table->addRow();
							$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_cel['nom']." ".$value_agents_cel['prenom'])));
							$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_cel['id_regime']]['F'].' '.$value_agents_cel['date_echeance_regime'])));
							
							$table->addCell(2000)->addText($value_agents_cel['langue']);
							$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_cel['id_grade']]['F'])));
							
							$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_cel['id_statut']]['F'])));
						//}
						//else
						//{
							
						//}
					}// FIN FOREACH TAB_AGENTS_CEL
				}
				$i=0;
				//mysqli_close($lien);


				
			}// FIN FOREACH TAB_CEL
		
		
		}

		mysqli_close($lien);

	
	
 }//FIN FOREACH TAB_SERVICES




// Save File
//$PHPWord=html_entity_decode(iconv('UTF-8', 'windows-1252',$PHPWord));
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$nom_fichier='tableau_personnel_'.$id_dep.'_'.date('YmdHis').'.docx';
$objWriter->save($nom_fichier);

echo '<javascript>';
echo 'window.open("php/'.$nom_fichier.'","_blank","menubar=no, status=no, scrollbars=no, menubar=no, width=200, height=100");';

?>

