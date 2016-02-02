<?php
//include('verification.php');
//include('params.php');
/***************Fonction qui met le résultat des records dans un array*********************************************/
/*function fn_ResultToArray($result=null,$id_key_unic=null)
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
}*/

/*******Connexion database***************/
include('../connect_db.php');

/********************************************/
/*********************************************************************************
************************CREER TABLE EFFECTIFS A LA DATE DEMANDEE*****************
*******************************************************************************/
$date_effectif=transformDate($date_situation_effectifs);
$new_date_effectif=str_replace('-', '', $date_effectif);

$sql="drop table if EXISTS cpas_effectifs_".$new_date_effectif.";";

$result=mysqli_query($lien, $sql);
/***********************************/

$sql = "
CREATE TABLE cpas_effectifs_".$new_date_effectif." (
id_contrat INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
id_agent INT(11) NOT NULL,
nom VARCHAR(50) NOT NULL,
prenom VARCHAR(50) NOT NULL,
registre_id INT(11) NOT NULL,
langue VARCHAR(1) NOT NULL,
niss VARCHAR(50) NULL,
id_dep INT(11) NOT NULL,
id_hors_dep INT(11) NOT NULL,
id_ser INT(11) NOT NULL,
id_cel INT(11) NOT NULL,
ouvrier_employe CHAR(1) NULL,
contractuel_nomme CHAR(1) NULL,
type_cadre INT(1) NOT NULL,
id_grade_cadre INT(3) NOT NULL,
id_bareme_cadre INT(2) NOT NULL,
id_code_cadre INT(2) NOT NULL,
id_grade INT(3) NOT NULL,
id_fonc INT(3) NOT NULL,
categorie VARCHAR(30) NULL,
id_bareme INT(2) NOT NULL,
id_code INT(2) NOT NULL,
date_echeance_bareme DATE NOT NULL,
id_statut INT(5) NOT NULL,
id_statut_special INT(5) NOT NULL,
date_echeance_statut DATE NOT NULL,
id_regime INT(5) NOT NULL,
date_echeance_regime DATE NOT NULL,
id_equiv_tp VARCHAR(5) NOT NULL,
start_date DATE NOT NULL,
niveau_etudes INT(2) NOT NULL,
libelle_diplome LONGTEXT NOT NULL,
id_selor INT(2) NOT NULL,
zone_libre_selor INT(2) NOT NULL,
prime_linguistique VARCHAR(5) NOT NULL,
date_naissance DATE NOT NULL,
id_civilite INT(2) NOT NULL,
genre tinyint(4) NOT NULL,
nationalite VARCHAR(50) NOT NULL,
tel_prive VARCHAR(20) NULL,
adresse_domicile VARCHAR(100) NULL,
num_domicile VARCHAR(6) NULL,
bte_domicile VARCHAR(5) NULL,
code_postal INT(5) NULL,
localite VARCHAR(50) NULL,
bxl_hbxl VARCHAR(10) NULL,
region VARCHAR(40) NULL,
id_article_budgetaire INT(3) NOT NULL,
end_date DATE NOT NULL,
motif_sortie VARCHAR(250) NULL

) DEFAULT CHARACTER SET utf8;";

//var_dump( $sql);
$result=mysqli_query($lien, $sql);


/*******Lire les contrats actifs à la date demandée*****************************/
$sql="
select 
*
from cpas_agents
join cpas_contrats
on
cpas_contrats.id_agent = cpas_agents.id_agent
join cpas_signaletiques_agents
on
cpas_agents.id_agent=cpas_signaletiques_agents.id_agent
where 
((cpas_contrats.start_date <= '".$date_effectif."') 
	and 
(cpas_contrats.end_date > '".$date_effectif."' or cpas_contrats.end_date = '0000-00-00' or cpas_contrats.end_date = '00-00-0000' or cpas_contrats.end_date = '' or cpas_contrats.end_date is null))
and cpas_contrats.statut='N'
order by nom;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);

$tab_contrats=fn_ResultToArray($result,'id_contrat');

/*****************************/
$requetes_insert="";
$champs_insert="";
$valeurs_insert="";

foreach($tab_contrats as $key=> $value)
{
	$champs_insert="";
	$valeurs_insert="";
	
	/***************Insertions des infos agents (signalétiques et diplômes)************************************/
	
 	$champs_insert.="id_contrat,id_agent,nom,prenom
					,registre_id,langue
					,start_date,end_date
					,motif_sortie,niss
					,niveau_etudes,libelle_diplome
					,id_selor,zone_libre_selor
					,prime_linguistique,date_naissance
					,id_civilite,genre
					,nationalite
					,tel_prive"; 
					
	
	 $valeurs_insert.=
				"'',".$value['id_agent'].",'".addslashes($value['nom'])."','".addslashes($value['prenom'])."'
				   ,".$value['registre_id'].",'".addslashes($value['langue'])."'
				   ,'".$value['start_date']."','".$value['end_date']."'
				   ,'".addslashes($value['motif_sortie'])."','".addslashes($value['niss'])."'
				   ,".$value['niveau_etudes'].",'".addslashes($value['libelle_diplome'])."'
				   ,".$value['id_selor'].",".$value['zone_libre_selor']."
				   ,'".$value['prime_linguistique']."','".$value['date_naissance']."'
				   ,".$value['id_civilite'].",".$value['genre']."
				   ,'".addslashes($value['nationalite'])."'
				   ,'".addslashes($value['tel_prive'])."'"; 
	/****************Mvt service*****************************/
 
	$sql="select * from cpas_mouvements_services 
	where 
	((cpas_mouvements_services.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_services.date_debut_service <= '".$date_effectif."')) 
	and cpas_mouvements_services.statut='N'
	order by cpas_mouvements_services.date_debut_service desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
	$result=mysqli_query($lien, $sql);
   
	$tab_mvt_ser=fn_ResultToArray($result,'id_mvt_service');
	//var_dump( $tab_mvt_ser);
	if($tab_mvt_ser!=null)
	{
		foreach($tab_mvt_ser as $key_ser=> $value_ser)
		{
		
			
			$champs_insert.=",id_article_budgetaire,id_hors_dep,id_dep,id_ser,id_cel";
			$valeurs_insert.=",".$value_ser['id_article_budgetaire'].",".$value_ser['id_hors_dep'].",".$value_ser['id_dep'].",".$value_ser['id_ser'].",".$value_ser['id_cel'];
		}
	}
	else
	{
		$champs_insert.=",id_article_budgetaire,id_hors_dep,id_dep,id_ser,id_cel";
		$valeurs_insert.=",0,0,0,0,0";
		
	}
	
	/****************Mvt fonction*****************************/
 
	$sql="select * from cpas_mouvements_fonctions 
	where 
	((cpas_mouvements_fonctions.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_fonctions.date_debut_fonction <= '".$date_effectif."')) 
	and cpas_mouvements_fonctions.statut='N'
	order by cpas_mouvements_fonctions.date_debut_fonction desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	$tab_mvt_fct=fn_ResultToArray($result,'id_mvt_fonction');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_fct!=null)
	{
		foreach($tab_mvt_fct as $key_fct=> $value_fct)
		{
			$champs_insert.=",id_fonc,ouvrier_employe,categorie";
			$valeurs_insert.=",".$value_fct['id_fonc'].",'".$value_fct['ouvrier_employe']."','".addslashes($value_fct['categorie'])."'";
		
		}
	}
	else
	{
		$champs_insert.=",id_fonc,ouvrier_employe,categorie";
		$valeurs_insert.=",0,'',''";
	}
	
	/****************Mvt barème*****************************/
 
	$sql="select * from cpas_mouvements_baremes 
	where 
	((cpas_mouvements_baremes.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_baremes.date_debut_bareme <= '".$date_effectif."')) 
	and cpas_mouvements_baremes.statut='N'
	order by cpas_mouvements_baremes.date_debut_bareme desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
    $result=mysqli_query($lien, $sql);
   
	
	$tab_mvt_baremes=fn_ResultToArray($result,'id_mvt_bareme');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_baremes!=null)
	{
		foreach($tab_mvt_baremes as $key_baremes=> $value_baremes)
		{
			
			$champs_insert.=",id_grade,id_bareme,id_code,id_grade_cadre,id_bareme_cadre,id_code_cadre,type_cadre";
			$valeurs_insert.=",".$value_baremes['id_grade'].",".$value_baremes['id_bareme'].",".$value_baremes['id_code'].",".$value_baremes['id_grade_cadre'].",".$value_baremes['id_bareme_cadre'].",".$value_baremes['id_code_cadre'].",".$value_baremes['type_cadre'];
		
		}
	}
	else
	{
		$champs_insert.=",id_grade,id_bareme,id_code,id_grade_cadre,id_bareme_cadre,id_code_cadre,type_cadre";
		$valeurs_insert.=",0,0,0,0,0,0,0";
	}
	
	/****************Mvt statut*****************************/
 
	$sql="select * from cpas_mouvements_statuts 
	where 
	((cpas_mouvements_statuts.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_statuts.date_debut_statut <= '".$date_effectif."')) 
	and cpas_mouvements_statuts.statut='N'
	order by cpas_mouvements_statuts.date_debut_statut desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	$tab_mvt_statuts=fn_ResultToArray($result,'id_mvt_statut');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_statuts!=null)
	{
		foreach($tab_mvt_statuts as $key_statuts=> $value_statuts)
		{
			$champs_insert.=",id_statut,id_statut_special,contractuel_nomme";
			$valeurs_insert.=",".$value_statuts['id_statut'].",".$value_statuts['id_statut_special'].",'".$value_statuts['contractuel_nomme']."'";
		
		}
	}
	else
	{
		$champs_insert.=",id_statut,id_statut_special,contractuel_nomme";
		$valeurs_insert.=",0,0,''";
	}
	
	/****************Mvt régime*****************************/
 
	$sql="select * from cpas_mouvements_regimes 
	where 
	((cpas_mouvements_regimes.id_contrat = ".$value['id_contrat'].") and (cpas_mouvements_regimes.date_debut_regime <= '".$date_effectif."')) 
	and cpas_mouvements_regimes.statut='N'
	order by cpas_mouvements_regimes.date_debut_regime desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_regimes=fn_ResultToArray($result,'id_mvt_regime');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_regimes!=null)
	{
		foreach($tab_mvt_regimes as $key_regimes=> $value_regimes)
		{
			$champs_insert.=",id_regime,id_equiv_tp";
			$valeurs_insert.=",".$value_regimes['id_regime'].",'".$value_regimes['id_equiv_tp']."'";
		}
	}
	else
	{
			$champs_insert.=",id_regime,id_equiv_tp";
			$valeurs_insert.=",0,0";
	}
	
	/****************Mvt domicile*****************************/
 
	$sql="select * from cpas_mouvements_domiciles 
	where 
	((cpas_mouvements_domiciles.id_agent = ".$value['id_agent'].") and (cpas_mouvements_domiciles.date_mvt <= '".$date_effectif."')) 
	and cpas_mouvements_domiciles.statut='N'
	order by cpas_mouvements_domiciles.date_mvt desc LIMIT 0,1;";
   
   //var_dump( $sql);
   
   $result=mysqli_query($lien, $sql);
   
	
	
	$tab_mvt_domiciles=fn_ResultToArray($result,'id_mvt_domicile');
	//var_dump( $tab_mvt_fct);
	if($tab_mvt_domiciles!=null)
	{
		foreach($tab_mvt_domiciles as $key_domiciles=> $value_domiciles)
		{
			$champs_insert.=",adresse_domicile
							,num_domicile,bte_domicile
							,code_postal,localite
							,region,bxl_hbxl";
			
			 $valeurs_insert.=
			",'".addslashes($value_domiciles['adresse_domicile'])."'
			,'".addslashes($value_domiciles['num_domicile'])."','".addslashes($value_domiciles['bte_domicile'])."'
			,".$value_domiciles['code_postal'].",'".addslashes($value_domiciles['localite'])."'
			,'".addslashes($value_domiciles['region'])."','".$value_domiciles['bxl_hbxl']."'";
		}
	}
	else
	{
			$champs_insert.=",adresse_domicile
							,num_domicile,bte_domicile
							,code_postal,localite
							,region,bxl_hbxl";
			
			$valeurs_insert.=",'','','',0,'','',''";
	}
	/********************************************************************************************
	***************	Insertion de tous les records correspondant aux effectifs demandés************
	*********************************************************************************************/
	$requetes_insert="insert into cpas_effectifs_".$new_date_effectif." (".$champs_insert.") values (".$valeurs_insert.");";
	//echo $requetes_insert;
	
	$result=mysqli_query($lien, $requetes_insert);
}//fin tab_contrats
	
/********Close connexion***************/
mysqli_close($lien);

?>