<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



include('verification.php');
include('params.php');

echo '<javascript>';
if(($date_debut_service=='')||($date_debut_service=="00-00-0000")||($date_debut_service=="0000-00-00"))
{
	echo "alert('Date de début vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}



if(dateValid($date_debut_service)==0)
{
	echo "alert('Date de début invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_echeance_service!='')&&($date_echeance_service!="00-00-0000")&&($date_echeance_service!="0000-00-00"))
{
	if(dateValid($date_echeance_service)==0)
	{
		echo "alert('Date d\'échéance invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}

	if(compareDate($date_debut_service,$date_echeance_service)==0)
	{
		echo "alert('La date d\'échéance est plus petite que la date de début! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if(($id_article_budgetaire=='')||($id_article_budgetaire==0)||($id_article_budgetaire==null))
{
	echo "alert('Article budgétaire vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}

if($choix_groupe=="DEP")
{
	$id_hors_dep=0;
}
else
{
	$id_dep=0;
	$id_ser=0;
	$id_cel=0;
}

if(($id_dep==0)&&($id_hors_dep==0))
{
	echo "alert('Vous n\'avez sélectionné aucun département! Veuillez sélectionner le département auquel l\'agent appartient.');";
	//echo "return false;";
	exit;
}





include('../connect_db.php');


	$sql="
			update cpas_mouvements_services 
			set
			id_agent='".$id_agent."'
			,id_contrat='".$id_contrat."'
			,id_article_budgetaire='".$id_article_budgetaire."'
			,id_hors_dep='".$id_hors_dep."'
			,id_dep='".$id_dep."'
			,id_ser='".$id_ser."'
			,id_cel='".$id_cel."'
			,actif='".$actif."'
			,date_debut_service='".transformDate($date_debut_service)."'
			,date_echeance_service='".transformDate($date_echeance_service)."'
			,modif_date=NOW()
			,modif_user='".$session_username."'
			where id_mvt_service='".$id_mvt_service."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de modification du mouvement de service');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de service modifié avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_services set actif=0 where id_contrat='".$id_contrat."';
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_services set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_service<=CURDATE() order by date_debut_service desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	$sql="update cpas_contrats,cpas_mouvements_services 
		set
		cpas_contrats.id_article_budgetaire=cpas_mouvements_services.id_article_budgetaire
		,cpas_contrats.id_hors_dep=cpas_mouvements_services.id_hors_dep
		,cpas_contrats.id_dep=cpas_mouvements_services.id_dep
		,cpas_contrats.id_ser=cpas_mouvements_services.id_ser
		,cpas_contrats.id_cel=cpas_mouvements_services.id_cel
		,cpas_contrats.date_echeance_service=cpas_mouvements_services.date_echeance_service
		where cpas_contrats.id_contrat=cpas_mouvements_services.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_services.id_contrat = '".$id_contrat."'
		and cpas_mouvements_services.actif = 1;";
	
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


echo "document.getElementById('bnt_services').className='bnt_open_list';"; 
echo "DisplayMvt('LIST_MVT_SER','services','$id_agent','$id_contrat');";
echo "DisplayListContrats('$id_agent');";
echo "document.getElementById('DIV_FORM_MVT_SER').innerHTML='';"; 


exit;	
?>