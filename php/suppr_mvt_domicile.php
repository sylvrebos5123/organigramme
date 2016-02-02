<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



include('verification.php');
include('params.php');

echo '<javascript>';




include('../connect_db.php');
	
$sql="
			update cpas_mouvements_domiciles set
			actif=0
			,cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where id_mvt_domicile='".$id_mvt_domicile."'
			;
	";

	
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du mouvement de domicile');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de domicile supprimé avec succès');";
	}
	
	/*******Mettre tous les mvt de domicile à inactifs*********************************/
	$sql="
		update cpas_mouvements_domiciles set actif=0 where id_agent='".$id_agent."';
		
	";
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt de domicile le plus récent*************************************************/
	$sql="
		update cpas_mouvements_domiciles set actif=1 where id_agent='".$id_agent."' and statut='N'
		and date_mvt<=CURDATE() order by date_mvt desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour des données signalétiques*********************************************************/
	
	$sql="update cpas_signaletiques_agents,cpas_mouvements_domiciles 
	set
	cpas_signaletiques_agents.adresse_domicile=cpas_mouvements_domiciles.adresse_domicile
	,cpas_signaletiques_agents.num_domicile=cpas_mouvements_domiciles.num_domicile
	,cpas_signaletiques_agents.bte_domicile=cpas_mouvements_domiciles.bte_domicile
	,cpas_signaletiques_agents.code_postal=cpas_mouvements_domiciles.code_postal
	,cpas_signaletiques_agents.localite=cpas_mouvements_domiciles.localite
	,cpas_signaletiques_agents.region=cpas_mouvements_domiciles.region
	,cpas_signaletiques_agents.bxl_hbxl=cpas_mouvements_domiciles.bxl_hbxl
	where cpas_signaletiques_agents.id_agent=cpas_mouvements_domiciles.id_agent
	and cpas_signaletiques_agents.id_agent='".$id_agent."'
	and cpas_mouvements_domiciles.actif = 1;";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de mise à jour des données signalétiques');";
		exit;
	}
	else
	{
		echo "alert('Mise à jour des données signalétiques réussie');";
	}
	/************/

	mysqli_close($lien);



//$message="Mouvement modifié avec succès";
//echo "alert('".$message."');";
echo "DisplayMvt('DIV_LIST_MVT_DOMICILE','domiciles','$id_agent',0);";

echo "document.getElementById('DIV_FORM_MVT_DOM').innerHTML='';"; 


exit;	
?>