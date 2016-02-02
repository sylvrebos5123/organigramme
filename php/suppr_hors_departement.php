<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');

/**************Fonction qui met le résultat d'un select dans un tableau************************/
/*function fn_ResultToArray($result=null,$id_key_unic=null)
{
	//verifier validité de $result
	if($result==null)
	{
		echo "pas de parametre result";
		return false;
	}
	
	$tableau=array();
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
/***************/
	echo '<javascript>';

	include('../connect_db.php');
	
	/*****Mouvements actuels******************************/
	
	/*$sql="
	select count(*) as nbr_agents 
	from cpas_mouvements_services
	where 
	id_hors_dep=".$id_hors_dep." and (actif=1 or (actif=0 and date_debut_service > CURDATE()));
	"; */
	
	$sql="
	select count(*) as nbr_agents
	from cpas_mouvements_services
	JOIN
	cpas_contrats
	ON
	cpas_mouvements_services.id_contrat=cpas_contrats.id_contrat
	where 
	cpas_mouvements_services.id_hors_dep=".$id_hors_dep." and (cpas_contrats.actif=1 or (cpas_contrats.actif=0 and date_debut_service > CURDATE())) and cpas_mouvements_services.statut='N';
	";
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de sélection des agents');";
		exit;
	}
	
	//$tab_contrats_actuels=fn_ResultToArray($result,'id_mvt_service');
	$tab_result=mysqli_fetch_assoc($result);
	
	
	
	if($tab_result['nbr_agents'] > 0)
	{
		
		/*$msg="Ces agents sont actuellement actifs pour ce département :".addslashes($liste_agents).". 
		Veuillez rectifier cela avant de supprimer le département.";*/
		$msg="Des mouvements de services (récents ou prévus dans le futur) existent encore pour ce département. Impossible de supprimer ce département.";
		echo "alert('$msg');";
		exit;
	}
	
	
	
	/*******Mettre le département en inactif********************************/
	$sql="
	update cpas_hors_departements
	set 
	actif=0
	where 
	id_hors_dep=".$id_hors_dep.";
	";
	
	
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression du service hors-département');";
		exit;
	}
	else
	{
		echo "alert('Suppression du service hors-département réussie');";
	}
	mysqli_close($lien);

echo "myHttpRequest('./generated_files/generate_hors_departements.php?');";
echo "DisplayOrganigramme();";
echo "CloseToLeft('modal_externe',100,0);";
exit;
?>