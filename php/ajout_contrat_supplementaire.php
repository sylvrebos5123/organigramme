<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

echo '<javascript>';


include('verification.php');
include('params.php');


if($start_date=='')
{
	echo "alert('Date de début de contrat vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

/*if($end_date=='')
{
	echo "alert('Date de fin de contrat vide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}*/

if(($start_date!='00-00-0000')&&($start_date!='0000-00-00')&&($start_date!=''))
{
	if(dateValid($start_date)==0)
	{
		echo "alert('Date de début de contrat invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if(($end_date!='00-00-0000')&&($end_date!='0000-00-00')&&($end_date!=''))
{
	if(dateValid($end_date)==0)
	{
		echo "alert('Date de fin de contrat invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

/*if(($date_echeance_code!='00-00-0000')&&($date_echeance_code!='0000-00-00')&&($date_echeance_code!=''))
{
	if(dateValid($date_echeance_code)==0)
	{
		echo "alert('Date d\'échéance de code invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if(($date_echeance_statut!='00-00-0000')&&($date_echeance_statut!='0000-00-00')&&($date_echeance_statut!=''))
{
	if(dateValid($date_echeance_statut)==0)
	{
		echo "alert('Date d\'échéance de statut invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if(($date_echeance_regime!='00-00-0000')&&($date_echeance_regime!='0000-00-00')&&($date_echeance_regime!=''))
{
	if(dateValid($date_echeance_regime)==0)
	{
		echo "alert('Date d\'échéance de régime invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}*/

if(($end_date!='00-00-0000')&&($end_date!='0000-00-00')&&($end_date!=''))
{
	if(compareDate($start_date,$end_date)==0)
	{
	 echo "alert('Attention la date de début de contrat est plus grande. Veuillez recommencer svp.');";
	 exit;
	}
}

/**********Vérifier si le contrat est actif ou non*****************************************************/
if
(
  (transformDate($start_date) > date('Y-m-d'))
  || 
  (
	(transformDate($end_date) < date('Y-m-d'))&&($end_date!="00-00-0000")&&($end_date!='')&&($end_date!=null)
  )
)
{
	$actif=0;
}
else
{
	$actif=1;
}




/*Ajout d'un enregistrement dans la table cpas_contrats*/
include('../connect_db.php');


$sql="
		insert into cpas_contrats 
		(id_contrat,id_agent,start_date
		,end_date,motif_sortie
		,actif,creation_date,creation_user)
		values 
		('',".$id_agent.",'".transformDate($start_date)."'
		,'".transformDate($end_date)."','".addslashes($motif_sortie)."'
		,".$actif.",NOW(),'".addslashes($session_username)."')
		;
";
//var_dump($sql);

$result=mysqli_query($lien, $sql);

if(!$result)
{
	echo "alert('Problème d\'ajout du contrat');";
	exit;
}
else
{
	echo "alert('Ajout du contrat');";
}
$last_id_contrat = mysqli_insert_id($lien);
//var_dump($LastId);
mysqli_close($lien);


/*******************/

		
echo "DisplayListContrats(".$id_agent.");";
//echo "DisplayContratHisto(".$id_agent.");";
echo "DisplayFormContratModif(".$last_id_contrat.",".$id_agent.");";

exit;
?>