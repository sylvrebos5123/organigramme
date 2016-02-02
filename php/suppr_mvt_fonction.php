<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');


/***************/
echo '<javascript>';




include('../connect_db.php');


	$sql="
			update cpas_mouvements_fonctions 
			set
			actif=0
			,cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where id_mvt_fonction='".$id_mvt_fonction."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du mouvement de fonction');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de fonction supprimé avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_fonctions set actif=0 where id_contrat='".$id_contrat."';
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_fonctions set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_fonction<=CURDATE() order by date_debut_fonction desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	
		
	$sql="update cpas_contrats,cpas_mouvements_fonctions 
		set
		
		cpas_contrats.id_fonc=cpas_mouvements_fonctions.id_fonc
		,cpas_contrats.ouvrier_employe=cpas_mouvements_fonctions.ouvrier_employe
		,cpas_contrats.categorie=cpas_mouvements_fonctions.categorie
		,cpas_contrats.flag_resp_dep=cpas_mouvements_fonctions.flag_resp_dep
		,cpas_contrats.flag_resp_ser=cpas_mouvements_fonctions.flag_resp_ser
		,cpas_contrats.date_echeance_fonction=cpas_mouvements_fonctions.date_echeance_fonction
		where cpas_contrats.id_contrat=cpas_mouvements_fonctions.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_fonctions.id_contrat = '".$id_contrat."'
		and cpas_mouvements_fonctions.actif = 1;";
	
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



echo "document.getElementById('bnt_fonctions').className='bnt_open_list';";
echo "DisplayMvt('LIST_MVT_FCT','fonctions','$id_agent','$id_contrat');";

echo "document.getElementById('DIV_FORM_MVT_FCT').innerHTML='';"; 


exit;	
?>