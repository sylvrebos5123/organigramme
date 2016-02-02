<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');


/***************/
echo '<javascript>';




include('../connect_db.php');


	$sql="
			update cpas_mouvements_baremes 
			set
			actif=0
			,cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where id_mvt_bareme='".$id_mvt_bareme."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du mouvement de barème');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de barème supprimé avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_baremes set actif=0 where id_contrat='".$id_contrat."';
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_baremes set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_bareme<=CURDATE() order by date_debut_bareme desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	$sql="update cpas_contrats,cpas_mouvements_baremes 
		set
		cpas_contrats.id_bareme=cpas_mouvements_baremes.id_bareme
		,cpas_contrats.id_code=cpas_mouvements_baremes.id_code
		,cpas_contrats.id_grade=cpas_mouvements_baremes.id_grade
		,cpas_contrats.date_echeance_bareme=cpas_mouvements_baremes.date_echeance_bareme
		,cpas_contrats.id_bareme_cadre=cpas_mouvements_baremes.id_bareme_cadre
		,cpas_contrats.id_code_cadre=cpas_mouvements_baremes.id_code_cadre
		,cpas_contrats.id_grade_cadre=cpas_mouvements_baremes.id_grade_cadre
		,cpas_contrats.type_cadre=cpas_mouvements_baremes.type_cadre
		where cpas_contrats.id_contrat=cpas_mouvements_baremes.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_baremes.id_contrat = '".$id_contrat."'
		and cpas_mouvements_baremes.actif = 1;";
	
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



echo "document.getElementById('bnt_baremes').className='bnt_open_list';"; 
echo "DisplayMvt('LIST_MVT_BAREME','baremes','$id_agent','$id_contrat');";

echo "document.getElementById('DIV_FORM_MVT_BAREME').innerHTML='';"; 


exit;	
?>