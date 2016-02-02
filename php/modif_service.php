<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('params.php');
echo '<javascript>';

include('../connect_db.php');

	$sql="
	update cpas_services
	set 
	label_F = '".addslashes($label_F)."'
	,label_N = '".addslashes($label_N)."'
	,fax = '".addslashes($fax)."'
	,modif_date=NOW()
	,modif_user='".$session_username."'
	where 
	id_ser=".$id_ser.";
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

echo "myHttpRequest('./generated_files/generate_services.php?');";
echo "DisplayOrganigramme();";
echo "CloseToLeft('modal_externe',100,0);";
exit;
?>