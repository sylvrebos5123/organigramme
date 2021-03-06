<?php
header ('Content-type: text/html; charset=utf-8'); 
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
require_once $rootpath.'\\organigramme\\PHPWord_0.6.2_Beta\\PHPWord.php';
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


/***********Lecture des agents à la tête d'un hors-département***************************/
include('../connect_db.php');



$sql="select 
*
from 
cpas_effectifs_".$new_date_effectif."
where id_hors_dep<>0  
and registre_id<990000
order by nom,prenom;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);

/* if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun agent');";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

	//exit;
} */

//mysqli_close($lien);


$tab_agents_hors_dep=fn_ResultToArray($result,'id_contrat'); 

/*************************************************/


$sql="select 

distinct(id_hors_dep)

from 

cpas_effectifs_".$new_date_effectif."

where id_hors_dep<>0

;";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

/* if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun service');";
	//echo "Aucun service<br><br>";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

	exit;
} */

//mysqli_close($lien);


$tab_hors_departements=fn_ResultToArray($result,'id_hors_dep');

/***********Lecture des agents à la tête du département***************************/
//include('../connect_db.php');



$sql="select 
*
from 
cpas_effectifs_".$new_date_effectif."
where id_dep<>0 
and id_ser=0 
and id_cel=0 
and registre_id<990000
order by nom,prenom;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);

/* if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun agent');";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

	//exit;
} */

//mysqli_close($lien);


$tab_agents_dep=fn_ResultToArray($result,'id_contrat'); 
//var_dump($tab_agents_dep);
/*************************************************/


$sql="select 

distinct(id_dep)

from 

cpas_effectifs_".$new_date_effectif."

where id_dep<>0

;";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

/* if(mysqli_num_rows($result)==0)
{
	echo "alert('Aucun service');";
	//echo "Aucun service<br><br>";
	//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

	exit;
} */

mysqli_close($lien);


$tab_departements=fn_ResultToArray($result,'id_dep');
//var_dump($tab_services);
/************************************************/
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_hors_departement.php');
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
$styleTable = array('borderSize'=>4, 'borderColor'=>'689F3B', 'cellMargin'=>5, 'size'=>5);
$styleFirstRow = array('borderBottomSize'=>4, 'borderBottomColor'=>'689F3B', 'bgColor'=>'D2F4B5');

// Define cell style arrays
$styleCell = array('valign'=>'center');
$styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);

// Define font style for first row
$fontStyle = array('bold'=>true, 'size'=>10, 'align'=>'center');
$paragraphStyle = array('bold'=>true, 'size'=>10,'align' => 'center');
$PHPWord->addParagraphStyle('pStyle', $paragraphStyle);

$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'size'=>12));
$PHPWord->addFontStyle('r2Style', array('bold'=>true, 'size'=>10));
$PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>50));
$PHPWord->addParagraphStyle('p2Style', array('align'=>'left', 'spaceBefore'=>150));
$txt="Situation du personnel du CPAS au ".$date_situation_effectifs;
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','pStyle');





include('../connect_db.php');

foreach($tab_hors_departements as $key_hors_dep =>$value_hors_dep) 
{
/********************************************************
Lecture titre du hors-département
***********************************/	
	
	// Define cell style arrays
	$styleCell = array('valign'=>'center','bgColor'=>'FCA549');
	
	$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
	$table = $section->addTable('myOwnTableStyle');
	//$title=html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value['id_ser']]['F']));
	$table->addRow(500);
	//$table->setMTitle($title);
	$table->addCell(5000,$styleCell)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_hors_departement[$value_hors_dep['id_hors_dep']]['F'])),$fontStyle);

/***************************************************
Lecture des agents faisant partie d'un hors-département  
********************************/	
	
	
	$sql="select 
	*
	from 
	cpas_effectifs_".$new_date_effectif."
	where id_hors_dep=".$value_hors_dep['id_hors_dep']."  
	and registre_id<990000
	order by nom,prenom;";
	
	
	//var_dump($sql);
	//echo "<br><br>";
	$result=mysqli_query($lien, $sql);

	
	if(mysqli_num_rows($result)>0)
	{
		$tab_agents_hors_dep=fn_ResultToArray($result,'id_contrat');
		//var_dump($tab_agents_ser);
		
		$i=0;
		foreach($tab_agents_hors_dep as $key_agents_hors_dep =>$value_agents_hors_dep) 
		{
				$i++;
				$table->addRow();
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_hors_dep['nom']." ".$value_agents_hors_dep['prenom'])));
				if(($value_agents_hors_dep['date_echeance_regime']=='0000-00-00')||($value_agents_hors_dep['date_echeance_regime']=='00-00-0000'))
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_hors_dep['id_regime']]['F'])));
				}
				else
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_hors_dep['id_regime']]['F'].' '.$value_agents_hors_dep['date_echeance_regime'])));
				
				}
				$table->addCell(1000)->addText($value_agents_hors_dep['langue']);
				$table->addCell(3000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_grade[$value_agents_hors_dep['id_grade']]['F'])));
				
				if($value_agents_hors_dep['id_statut_special']==0)
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_hors_dep['id_statut']]['F'])));
				
				}
				else
				{
					$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_statut[$value_agents_hors_dep['id_statut']]['F'].' '.$array_statut_special[$value_agents_hors_dep['id_statut_special']]['F'])));
				}
				
		}// FIN FOREACH TAB_AGENTS_HORS_DEP
	}
}

foreach($tab_departements as $key_dep =>$value_dep) 
{
/********************************************************
Lecture titre du département
***********************************/	
	
	// Define cell style arrays
	$styleCell = array('valign'=>'center','bgColor'=>'A9E079');
	
	$PHPWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
	$PHPWord->addTableStyle('myOwnTable2Style', $styleTable);
	$table = $section->addTable('myOwnTableStyle');
	//$title=html_entity_decode(iconv('UTF-8', 'windows-1252',$array_service[$value['id_ser']]['F']));
	$table->addRow(500);
	//$table->setMTitle($title);
	$table->addCell(5000,$styleCell)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_departement[$value_dep['id_dep']]['F'])),$fontStyle);

/***************************************************
Lecture des agents faisant partie d'un département  (et pas d'un service ni d'une cellule)
********************************/	
	
	
	$sql="select 
	*
	from 
	cpas_effectifs_".$new_date_effectif."
	where id_dep=".$value_dep['id_dep']." 
	and id_ser=0 
	and id_cel=0 
	and registre_id<990000
	order by nom,prenom;";
	
	
	//var_dump($sql);
	//echo "<br><br>";
	$result=mysqli_query($lien, $sql);

	
	if(mysqli_num_rows($result)>0)
	{
		$tab_agents_dep=fn_ResultToArray($result,'id_contrat');
		//var_dump($tab_agents_ser);
		
		$i=0;
		foreach($tab_agents_dep as $key_agents_dep =>$value_agents_dep) 
		{
				$i++;
				$table->addRow();
				$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_dep['nom']." ".$value_agents_dep['prenom'])));
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
		}// FIN FOREACH TAB_AGENTS_DEP
	}

	/*************Lecture des services inclus dans le département en cours********************************/	
		$sql="select 

		distinct(id_ser)

		from 

		cpas_effectifs_".$new_date_effectif."

		where id_dep=".$value_dep['id_dep']." and id_ser<>0

		;";
		//var_dump($sql);
		$result=mysqli_query($lien, $sql);

		/* if(mysqli_num_rows($result)==0)
		{
			echo "alert('Aucun service');";
			//echo "Aucun service<br><br>";
			//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

			exit;
		} */

		//mysqli_close($lien);


		$tab_services=fn_ResultToArray($result,'id_ser');
		
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
			//include('../connect_db.php');
			
			$sql="select 
			*
			from 
			cpas_effectifs_".$new_date_effectif."
			where id_ser=".$value_ser['id_ser']." 
			and id_cel=0 
			and registre_id<990000
			order by nom,prenom;";
			
			
			//var_dump($sql);
			//echo "<br><br>";
			$result=mysqli_query($lien, $sql);

			/* if(mysqli_num_rows($result)==0)
			{
				echo "alert('Aucun agent');";
				//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

				//exit;
			} */
			if(mysqli_num_rows($result)>0)
			{
				$tab_agents_ser=fn_ResultToArray($result,'id_contrat');
				//var_dump($tab_agents_ser);
				$i=0;
				foreach($tab_agents_ser as $key_agents_ser =>$value_agents_ser) 
				{
						$i++;
						$table->addRow();
						$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$i.' - '.$value_agents_ser['nom']." ".$value_agents_ser['prenom'])));
						//$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_ser['id_regime']]['F'].' '.$value_agents_ser['date_echeance_regime'])));
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

				/* if(mysqli_num_rows($result)==0)
				{
					echo "alert('Aucune cellule');";

				} */
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

						/**********Lecture des agents**********************/

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

						/* if(mysqli_num_rows($result)==0)
						{
							echo "alert('Aucun agent');";
							//echo '<input type="button" value="Ajout d\'un mouvement ultérieur" onclick="DisplayFormMvt(\'regime\',\''.$id_agent.'\');" />';

							//exit;
						} */
						if(mysqli_num_rows($result)>0)
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
									//$table->addCell(2000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$array_regime[$value_agents_cel['id_regime']]['F'].' '.$value_agents_cel['date_echeance_regime'])));
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
						//mysqli_close($lien);


						
					}// FIN FOREACH TAB_CEL
				
				
				}

		 }//FIN FOREACH TAB_SERVICES
	//$section = $PHPWord->createSection();
}//FIN FOREACH TAB_DEPARTEMENTS
mysqli_close($lien);


/***************Liste des totaux************************************************/
/*******Connexion database***************/
include('../connect_db.php');

/***************Total général Agents **********************/
$sql="select 
distinct(id_agent)
from 
cpas_effectifs_".$new_date_effectif."
where 
registre_id<990000;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);
$nb_agents=mysqli_num_rows($result);

/***************Total des agents hors-département**********************/
$sql="select 
distinct(id_agent)
from 
cpas_effectifs_".$new_date_effectif."
where 
id_hors_dep<>0
and id_dep=0
and registre_id<990000;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);
$nb_agents_hors_dep=mysqli_num_rows($result);

/***************Total des agents département (pas extérieur et pas art 60)**********************/
$sql="select 
distinct(id_agent)
from 
cpas_effectifs_".$new_date_effectif."
where 
(id_dep<>0 and id_dep<>5)
and id_statut<>4
and registre_id<990000;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);
$nb_agents_dep=mysqli_num_rows($result);

/***************Total des agents département extérieur (et pas art 60)**********************/
$sql="select 
distinct(id_agent)
from 
cpas_effectifs_".$new_date_effectif."
where 
id_dep=5
and id_statut<>4
and registre_id<990000;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);
$nb_agents_ext=mysqli_num_rows($result);

/***************Total des agents art 60 (et pas exterieur)**********************/
$sql="select 
distinct(id_agent)
from 
cpas_effectifs_".$new_date_effectif."
where 
(id_dep<>0 and id_dep<>5)
and id_statut=4
and registre_id<990000;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);
$nb_agents_60=mysqli_num_rows($result);

/***************Total des agents art 60 dep exterieur**********************/
$sql="select 
distinct(id_agent)
from 
cpas_effectifs_".$new_date_effectif."
where 
id_dep=5
and id_statut=4
and registre_id<990000;";

//var_dump($sql);
$result=mysqli_query($lien, $sql);
$nb_agents_60_ext=mysqli_num_rows($result);


mysqli_close($lien);

/*********************************************************/
$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252','')),'rStyle','p2Style');
$table = $section->addTable('myOwnTable2Style');
$table->addRow();

$txt="TOTAL DES AGENTS CPAS EN SERVICE HORS-DEPARTEMENT ";
$table->addCell(7000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'r2Style');
$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$nb_agents_hors_dep)),'r2Style');

$table->addRow();

$txt="TOTAL DES AGENTS CPAS ";
$table->addCell(7000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'r2Style');
$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$nb_agents_dep)),'r2Style');

$table->addRow();

$txt="TOTAL DES AGENTS HORS CPAS (ASBL OU DELEGUES SYNDICAUX)";
$table->addCell(7000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'r2Style');
$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$nb_agents_ext)),'r2Style');

$table->addRow();

$txt="TOTAL DES ARTICLES 60 CPAS ";
$table->addCell(7000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'r2Style');
$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$nb_agents_60)),'r2Style');

$table->addRow();
$txt="TOTAL DES ARTICLES 60 HORS CPAS (ASBL)";
$table->addCell(7000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'r2Style');
$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$nb_agents_60_ext)),'r2Style');


$table->addRow();
$txt="TOTAL GENERAL DES AGENTS ";
$table->addCell(7000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'r2Style');
$table->addCell(1000)->addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$nb_agents)),'r2Style');

//$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','p2Style');

//$section -> addText(html_entity_decode(iconv('UTF-8', 'windows-1252',$txt)),'rStyle','p2Style');
// Save File
//$PHPWord=html_entity_decode(iconv('UTF-8', 'windows-1252',$PHPWord));
 /* $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$nom_fichier='tableau_personnel_ALL_DEP'.$new_date_effectif.'_'.date('Ymd-His').'.docx';

$objWriter->save($nom_fichier); */  
 

/*****************************************************************************************
*********** Création du fichier dans un répertoire temporaire et copie sur filesrv*********
*******************************************************************************************/

$file_name = 'tableau_personnel_ALL_DEP_'.$new_date_effectif.'_'.date('Ymd-His').'.docx';
//$temp_xls_name = 'E:\\webserver\\test_cpas_ocmw\\www\\organigramme\\temp\\'.$file_name;
$temp_xls_name = 'F:\\webserver\\testweb\\www\\organigramme\\temp\\'.$file_name;
// fichier php contenant les chemins d'accès
include('array_files.php');
$new_xls_name = $array_files['TABLEAU_PERSO'].$file_name;

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

//echo 'window.open("php/'.$nom_fichier.'","_blank");';


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

