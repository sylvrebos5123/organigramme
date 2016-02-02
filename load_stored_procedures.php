<?php
include('connect_db.php');

/************Contrats actifs********************/
	$sql="
		CALL procedure_contrats_actifs;
	";	
	
	$result=mysqli_query($lien, $sql);
/************procedure services********************/
	$sql="
		CALL procedure_mvt_services;
	";	
	
	$result=mysqli_query($lien, $sql);
	
/************procedure fonctions********************/
	$sql="
		CALL procedure_mvt_fonctions;
	";	
	
	$result=mysqli_query($lien, $sql);
	
/************procedure baremes********************/
	$sql="
		CALL procedure_mvt_baremes;
	";	
	
	$result=mysqli_query($lien, $sql);
	
/************procedure statuts********************/
	$sql="
		CALL procedure_mvt_statuts;
	";	
	
	$result=mysqli_query($lien, $sql);	
	
/************procedure regimes********************/
	$sql="
		CALL procedure_mvt_regimes;
	";	
	$result=mysqli_query($lien, $sql);
/************procedure domiciles********************/
	$sql="
		CALL procedure_mvt_domiciles;
	";	
	
	$result=mysqli_query($lien, $sql);	
	
/************Primes/alloc. actives********************/
	$sql="
		CALL procedure_primes_actives;
	";	
	
	$result=mysqli_query($lien, $sql);
/*****************/	
	mysqli_close($lien);
?>