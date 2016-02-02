<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');


/***************/
echo '<javascript>';

include('../connect_db.php');
	
	/*****Mouvements actuels******************************/
	
	
	/*$sql="
	select count(*) as nbr_agents 
	from cpas_mouvements_services
	where 
	id_ser=".$id_ser." and (actif=1 or (actif=0 and date_debut_service > CURDATE()));
	"; */
	
	$sql="
	select count(*) as nbr_agents
	from cpas_mouvements_services
	JOIN
	cpas_contrats
	ON
	cpas_mouvements_services.id_contrat=cpas_contrats.id_contrat
	where 
	cpas_mouvements_services.id_ser=".$id_ser." and (cpas_contrats.actif=1 or (cpas_contrats.actif=0 and date_debut_service > CURDATE())) and cpas_mouvements_services.statut='N';
	";
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de sélection des agents');";
		exit;
	}
	
	//$tab_contrats_actuels=fn_ResultToArray($result,'id_mvt_service');
	$tab_result=mysqli_fetch_assoc($result);
	//echo $nbr_agents;
	//$liste_agents='';
	
	
	if($tab_result['nbr_agents'] > 0)
	{
		
		
		$msg="Des mouvements de services (récents ou prévus dans le futur) existent encore pour ce service. Impossible de supprimer ce service.";
		echo "alert('$msg');";
		exit;
	}
	
	
	
	/*******Mettre le département en inactif********************************/
	$sql="
	update cpas_services
	set 
	actif=0
	where 
	id_ser=".$id_ser.";
	";
	
	
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du service');";
		exit;
	}
	else
	{
		echo "alert('Suppression de service réussie');";
	}
	mysqli_close($lien);

echo "myHttpRequest('./generated_files/generate_services.php?');";
echo "DisplayOrganigramme();";
echo "CloseToLeft('modal_externe',100,0);";
exit;
?>