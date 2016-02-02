<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



include('verification.php');
include('params.php');

echo '<javascript>';
if(($date_debut_regime=='')||($date_debut_regime=="00-00-0000")||($date_debut_regime=="0000-00-00"))
{
	echo "alert('Date de début vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}


if(dateValid($date_debut_regime)==0)
{
	echo "alert('Date de début invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_echeance_regime!='')&&($date_echeance_regime!="00-00-0000")&&($date_echeance_regime!="0000-00-00"))
{
	if(dateValid($date_echeance_regime)==0)
	{
		echo "alert('Date d\'échéance invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}


	if(compareDate($date_debut_regime,$date_echeance_regime)==0)
	{
		echo "alert('La date d\'échéance est plus petite que la date de début! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}
if($id_regime==0)
{
	echo "alert('Vous n\'avez sélectionné aucun régime! Veuillez sélectionner un régime pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

/* if($id_equiv_tp==0)
{
	echo "alert('Equivalence temps plein vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
} */





include('../connect_db.php');
	

$sql="
			insert into cpas_mouvements_regimes 
			(id_mvt_regime
			,id_agent
			,id_contrat
			,id_regime
			,id_equiv_tp
			,date_debut_regime
			,date_echeance_regime
			,actif
			,creation_date
			,creation_user)
			values
			(''
			,'".$id_agent."'
			,'".$id_contrat."'
			,'".$id_regime."'
			,'".$id_equiv_tp."'
			,'".transformDate($date_debut_regime)."'
			,'".transformDate($date_echeance_regime)."'
			,'".$actif."'
			,NOW()
			,'".$session_username."'
			);
	";
	
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);
	if(!$result)
	{
		echo "alert('Problème d\'ajout du mouvement de régime');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de régime ajouté avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_regimes set actif=0 where id_contrat='".$id_contrat."';
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_regimes set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_regime<=CURDATE() order by date_debut_regime desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	
		
	$sql="update cpas_contrats,cpas_mouvements_regimes 
		set
		cpas_contrats.id_regime=cpas_mouvements_regimes.id_regime
		,cpas_contrats.id_equiv_tp=cpas_mouvements_regimes.id_equiv_tp
		,cpas_contrats.date_echeance_regime=cpas_mouvements_regimes.date_echeance_regime
		where cpas_contrats.id_contrat=cpas_mouvements_regimes.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_regimes.id_contrat = '".$id_contrat."'
		and cpas_mouvements_regimes.actif = 1;";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de mise à jour du contrat');";
		exit;
	}
	else
	{
		echo "alert('Mise à jour du contrat réussie');";
	}
	/************/
	mysqli_close($lien);


echo "document.getElementById('bnt_regimes').className='bnt_open_list';";

echo "DisplayMvt('LIST_MVT_REGIME','regimes','$id_agent','$id_contrat');";
//echo "DisplayListContrats('$id_agent');";
echo "document.getElementById('DIV_FORM_MVT_REGIME').innerHTML='';"; 

exit;	
?>