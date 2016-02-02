<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');


/***************/
echo '<javascript>';




include('../connect_db.php');


	$sql="
			update cpas_mouvements_regimes 
			set
			actif=0
			,cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where id_mvt_regime='".$id_mvt_regime."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du mouvement de régime');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de régime supprimé avec succès');";
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

echo "document.getElementById('DIV_FORM_MVT_REGIME').innerHTML='';"; 


exit;	
?>