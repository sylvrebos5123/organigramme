<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);


echo '<style>

td{text-align:center;padding:5px;}

</style>';
//echo '<javascript>';
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
where cpas_contrats.statut='N' and ((cpas_contrats.start_date <= '".$date_effectif."') and (cpas_contrats.end_date > '".$date_effectif."' or cpas_contrats.end_date = '0000-00-00' or cpas_contrats.end_date = '00-00-0000' or cpas_contrats.end_date = '' or cpas_contrats.end_date is null))
order by nom;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);

$tab_contrats=fn_ResultToArray($result,'id_contrat'); */

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

$tab_contrats=fn_ResultToArray($result,'id_contrat');

echo "
<table>
	<tr style='height:50px;padding-top:5px;margin-top:5px;background-color:#E4F8D2;'>
		<td>NOM</td>
		<td>PRENOM</td>
		<td>DEBUT CONTRAT</td>
		<td>FIN CONTRAT</td>
		<td>MOTIF SORTIE</td>
		<td>ARTICLE BUDGETAIRE</td>
		<td>HORS DEPARTEMENT</td>
		<td>DEPARTEMENT</td>
		<td>SERVICE</td>
		<td>CELLULE</td>
		<td>FONCTION</td>
		<td>GRADE CADRE</td>
		<td>BAREME CADRE</td>
		<td>CODE CADRE</td>
		<td>TYPE CADRE</td>
		<td>STATUT</td>
		<td>STATUT SPECIAL</td>
		<td>REGIME</td>
		<td>EQUIV. TEMPS PLEIN</td>
	</tr>
";

$i=0;
foreach($tab_contrats as $key=> $value)
{
	$i++;
	if(($i%2)==0)
	{
		echo '<tr style="background-color:#ddd;">';
	}
	else
	{
		echo '<tr style="background-color:#fff;">';
	}
	
	echo '<td>'.$value['nom'].'</td>';
	echo '<td>'.$value['prenom'].'</td>';
	echo '<td>'.transformDate($value['start_date']).'</td>';
	echo '<td>'.transformDate($value['end_date']).'</td>';
	echo '<td>'.$value['motif_sortie'].'</td>';
	$art_budgetaire=explode('-',$array_article_budgetaire[$value['id_article_budgetaire']]['F']);
	echo '<td>'.$art_budgetaire[0].'</td>';
	echo '<td>'.$array_hors_departement[$value['id_hors_dep']]['F'].'</td>';
	echo '<td>'.$array_departement[$value['id_dep']]['F'].'</td>';
	echo '<td>'.$array_service[$value['id_ser']]['F'].'</td>';
	echo '<td>'.$array_cellule[$value['id_cel']]['F'].'</td>';
	echo '<td>'.$array_fonction[$value['id_fonc']]['F'].'</td>';
	echo '<td>'.$array_grade[$value['id_grade_cadre']]['F'].'</td>';
	echo '<td>'.$array_bareme[$value['id_bareme_cadre']].'</td>';
	echo '<td>'.$array_code[$value['id_code_cadre']].'</td>';
	echo '<td>'.$array_type_cadre[$value['type_cadre']].'</td>';
	echo '<td>'.$array_statut[$value['id_statut']]['F'].'</td>';
	echo '<td>'.$array_statut_special[$value['id_statut_special']]['F'].'</td>';
	echo '<td>'.$array_regime[$value['id_regime']]['F'].'</td>';
	echo '<td>'.$value['id_equiv_tp'].'</td>';
	/****************Mvt service*****************************/
 
	/* $sql="select * from cpas_mouvements_services 
	where 
	statut='N' and 
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
			$art_budgetaire=explode('-',$array_article_budgetaire[$value_ser['id_article_budgetaire']]['F']);
			echo '<td>'.$art_budgetaire[0].'</td>';
			echo '<td>'.$array_hors_departement[$value_ser['id_hors_dep']]['F'].'</td>';
			echo '<td>'.$array_departement[$value_ser['id_dep']]['F'].'</td>';
			echo '<td>'.$array_service[$value_ser['id_ser']]['F'].'</td>';
			echo '<td>'.$array_cellule[$value_ser['id_cel']]['F'].'</td>';
		}
	}
	else
	{
		echo '<td colspan="5">---</td>';
	} */
	
	/****************Mvt fonction*****************************/
 
	/* $sql="select * from cpas_mouvements_fonctions 
	where 
	statut='N' and 
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
			
			echo '<td>'.$array_fonction[$value_fct['id_fonc']]['F'].'</td>';
		}
	}
	else
	{
		echo '<td>---</td>';
	} */
	
	/****************Mvt barème*****************************/
 
	/* $sql="select * from cpas_mouvements_baremes 
	where 
	statut='N' and 
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
			echo '<td>'.$array_grade[$value_baremes['id_grade_cadre']]['F'].'</td>';
			echo '<td>'.$array_bareme[$value_baremes['id_bareme_cadre']].'</td>';
			echo '<td>'.$array_code[$value_baremes['id_code_cadre']].'</td>';
			echo '<td>'.$array_type_cadre[$value_baremes['type_cadre']].'</td>';
		}
	}
	else
	{
		echo '<td colspan="4">---</td>';
	} */
	
	/****************Mvt statut*****************************/
 
	/* $sql="select * from cpas_mouvements_statuts 
	where 
	statut='N' and ((cpas_mouvements_statuts.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_statuts.date_debut_statut <= '".$date_effectif."')) 
	order by cpas_mouvements_statuts.date_debut_statut desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	$tab_mvt_statuts=fn_ResultToArray($result,'id_mvt_statut');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_statuts!=null)
	{
		foreach($tab_mvt_statuts as $key_statuts=> $value_statuts)
		{
			echo '<td>'.$array_statut[$value_statuts['id_statut']]['F'].'</td>';
			echo '<td>'.$array_statut_special[$value_statuts['id_statut_special']]['F'].'</td>';
		}
	}
	else
	{
		echo '<td colspan="2">---</td>';
	} */
	
	/****************Mvt régime*****************************/
 
	/* $sql="select * from cpas_mouvements_regimes 
	where 
	statut='N' and ((cpas_mouvements_regimes.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_regimes.date_debut_regime <= '".$date_effectif."')) 
	order by cpas_mouvements_regimes.date_debut_regime desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_regimes=fn_ResultToArray($result,'id_mvt_regime');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_regimes!=null)
	{
		foreach($tab_mvt_regimes as $key_regimes=> $value_regimes)
		{
			echo '<td>'.$array_regime[$value_regimes['id_regime']]['F'].'</td>';
			echo '<td>'.$value_regimes['id_equiv_tp'].'</td>';
		}
	}
	else
	{
		echo '<td colspan="2">---</td>';
	} */
	
	echo '</tr>';
	

}//fin tab_contrats

/********Close connexion***************/
	mysqli_close($lien);
echo '</table>';


echo '<javascript>';
echo "
document.getElementById('bnt_gen_effectifs').style.visibility='visible';
";


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
