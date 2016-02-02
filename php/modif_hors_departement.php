<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');
echo '<javascript>';

include('../connect_db.php');

	$sql="
	update cpas_hors_departements
	set 
	label_F = '".addslashes($label_F)."'
	,label_N = '".addslashes($label_N)."'
	,modif_date=NOW()
	,modif_user='".$session_username."'
	,indice_ordre='".$indice_ordre."'
	where 
	id_hors_dep=".$id_hors_dep.";
	";
	
	
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de modification de service');";
		exit;
	}
	else
	{
		echo "alert('Modification de service réussie');";
	}
	mysqli_close($lien);

echo "myHttpRequest('./generated_files/generate_hors_departements.php?');";
echo "DisplayOrganigramme();";
echo "CloseToLeft('modal_externe',100,0);";
exit;
?>