<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };


include('verification.php');
include('params.php');

echo '<javascript>';
if(($date_debut_statut=='')||($date_debut_statut=="00-00-0000")||($date_debut_statut=="0000-00-00"))
{
	echo "alert('Date de début vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}


if(dateValid($date_debut_statut)==0)
{
	echo "alert('Date de début invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_echeance_statut!='')&&($date_echeance_statut!="00-00-0000")&&($date_echeance_statut!="0000-00-00"))
{
	if(dateValid($date_echeance_statut)==0)
	{
		echo "alert('Date d\'échéance invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}


	if(compareDate($date_debut_statut,$date_echeance_statut)==0)
	{
		echo "alert('La date d\'échéance est plus petite que la date de début! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}
if(($id_statut==0)&&($id_statut_special==0))
{
	echo "alert('Vous n\'avez sélectionné aucun statut! Veuillez sélectionner au moins un statut pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}
 

include('../connect_db.php');
	
	
	$sql="
			update cpas_mouvements_statuts 
			set
			id_statut='".$id_statut."'
			,id_statut_special='".$id_statut_special."'
			,contractuel_nomme='".$contractuel_nomme."'
			,date_debut_statut='".transformDate($date_debut_statut)."'
			,date_echeance_statut='".transformDate($date_echeance_statut)."'
			,actif='".$actif."'
			,modif_date=NOW()
			,modif_user='".$session_username."'
			
			where id_mvt_statut='".$id_mvt_statut."';
	";
	
	
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de modification du mouvement de statut');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de statut modifié avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_statuts set actif=0 where id_contrat='".$id_contrat."';
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_statuts set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_statut<=CURDATE() order by date_debut_statut desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	
		
	$sql="update cpas_contrats,cpas_mouvements_statuts 
		set
		cpas_contrats.id_statut=cpas_mouvements_statuts.id_statut
		,cpas_contrats.id_statut_special=cpas_mouvements_statuts.id_statut_special
		,cpas_contrats.contractuel_nomme=cpas_mouvements_statuts.contractuel_nomme
		,cpas_contrats.date_echeance_statut=cpas_mouvements_statuts.date_echeance_statut
		where cpas_contrats.id_contrat=cpas_mouvements_statuts.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_statuts.id_contrat = '".$id_contrat."'
		and cpas_mouvements_statuts.actif = 1;";
	
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


echo "document.getElementById('bnt_statuts').className='bnt_open_list';";

echo "DisplayMvt('LIST_MVT_STATUT','statuts','$id_agent','$id_contrat');";

echo "document.getElementById('DIV_FORM_MVT_STATUT').innerHTML='';"; 

exit;	
?>