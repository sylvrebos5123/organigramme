<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');
echo '<javascript>';

include('../connect_db.php');


	$sql="
			insert into cpas_departements 
			(id_dep
			,label_F
			,label_N
			,actif
			,creation_date
			,creation_user
			,indice_ordre)
			values
			(''
			,'".$label_F."'
			,'".$label_N."'
			,1
			,NOW()
			,'".$session_username."'
			,'".$indice_ordre."'
			);
	";
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème d\'ajout de département');";
		exit;
	}
	else
	{
		echo "alert('Ajout de département réussi');";
	}
	mysqli_close($lien);

echo "myHttpRequest('./generated_files/generate_departements.php?');";
echo "DisplayOrganigramme();";
echo "CloseToLeft('modal_externe',100,0);";
exit;
?>