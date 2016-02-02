<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');
echo '<javascript>';

include('../connect_db.php');


	$sql="
			insert into cpas_cellules 
			(id_cel
			,label_F
			,label_N
			,id_ser
			,id_dep
			,actif
			,fax
			,creation_date
			,creation_user)
			values
			(''
			,'".$label_F."'
			,'".$label_N."'
			,'".$id_ser."'
			,'".$id_dep."'
			,1
			,'".$fax."'
			,NOW()
			,'".$session_username."'
			);
	";
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème d\'ajout de cellule');";
		exit;
	}
	else
	{
		echo "alert('Ajout de cellule réussi');";
	}
	mysqli_close($lien);

echo "myHttpRequest('./generated_files/generate_cellules.php?');";
echo "DisplayOrganigramme();";
echo "CloseToLeft('modal_externe',100,0);";
exit;
?>