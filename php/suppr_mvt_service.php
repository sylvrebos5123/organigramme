<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');


/***************/
echo '<javascript>';




include('../connect_db.php');


	$sql="
			update cpas_mouvements_services 
			set
			actif=0
			,cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where id_mvt_service='".$id_mvt_service."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du mouvement de service');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de service supprimé avec succès');";
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
//echo "DisplayListContrats('$id_agent');";
echo "document.getElementById('DIV_FORM_MVT_SER').innerHTML='';"; 


exit;	
?>