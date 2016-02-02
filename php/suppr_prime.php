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
			update cpas_primes 
			set
			actif=0
			,cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where id_prime='".$id_prime."';
	";

	
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de suppression de la prime');";
		exit;
	}
	else
	{
		echo "alert('Prime supprimée avec succès');";
	}
	
	
	/************/
	mysqli_close($lien);



echo "DisplayListPrimes('".$id_agent."');";
echo "document.getElementById('DIV_FORM_PRIME').innerHTML='';"; 

exit;	
?>