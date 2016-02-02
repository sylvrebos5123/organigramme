<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



include('verification.php');
include('params.php');

echo '<javascript>';

if(($date_octroi=='')||($date_octroi=="00-00-0000")||($date_octroi=="0000-00-00"))
{
	echo "alert('Date d\'octroi de la prime vide! Veuillez remplir le champ prévu.');";
	
	exit;
}


if(dateValid($date_octroi)==0)
{
	echo "alert('Date d\'octroi invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_cloture!='')&&($date_cloture!="00-00-0000")&&($date_cloture!="0000-00-00"))
{
	if(dateValid($date_cloture)==0)
	{
		echo "alert('Date de clôture invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}

	if(compareDate($date_octroi,$date_cloture)==0)
	{
		echo "alert('La date de clôture est plus petite que la date d\'octroi! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

/* if($id_bareme==0)
{
	echo "alert('Vous n\'avez sélectionné aucun barème! Veuillez sélectionner un barème pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

if($id_grade==0)
{
	echo "alert('Vous n\'avez sélectionné aucun grade! Veuillez sélectionner un grade pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

if($id_bareme_cadre==0)
{
	echo "alert('Veuillez sélectionner un barème (CADRE) pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

if($id_grade_cadre==0)
{
	echo "alert('Veuillez sélectionner un grade (CADRE) pour l\'agent en cours.');";
	//echo "return false;";
	exit;
} */

/**********Vérifier si la prime est active ou non*****************************************************/
if
(
  (transformDate($date_octroi) > date('Y-m-d'))
  || 
  (
	(transformDate($date_cloture) < date('Y-m-d'))&&($date_cloture!="00-00-0000")&&($date_cloture!='')&&($date_cloture!=null)
  )
)
{
	$actif=0;
}
else
{
	$actif=1;
}


include('../connect_db.php');

	$sql="
			update cpas_primes set
			id_agent='".$id_agent."'
			,id_type_prime='".$id_type_prime."'
			,date_octroi='".transformDate($date_octroi)."'
			,date_cloture='".transformDate($date_cloture)."'
			,id_grade='".$id_grade."'
			,date_echeance_code='".transformDate($date_echeance_code)."'
			,date_echeance_biennale='".transformDate($date_echeance_biennale)."'
			,actif='".$actif."'
			,modif_date=NOW()
			,modif_user='".$session_username."'
			where id_prime='".$id_prime."'
			;
	";

	
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de modification de la prime');";
		exit;
	}
	else
	{
		echo "alert('Prime modifiée avec succès');";
	}
	
	
	/************/
	mysqli_close($lien);



echo "DisplayListPrimes('".$id_agent."');";
echo "document.getElementById('DIV_FORM_PRIME').innerHTML='';"; 

exit;	
?>