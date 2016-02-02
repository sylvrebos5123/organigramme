<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
echo '<javascript>';


/**********Params***************************************/
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

//$champs_signaletiques='';



/*MAJ de la table signaletique avec les champs à modifier*/
include('../connect_db.php');


$sql="
		update cpas_contrats
		set 
		start_date='".transformDate($start_date)."',end_date='".transformDate($end_date)."'
		,motif_sortie='".addslashes($motif_sortie)."',actif=".$actif.",modif_date=NOW(),modif_user='".$session_username."'
		where id_contrat='".$id_contrat."';
";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);

mysqli_close($lien);
/**********/

//echo '<javascript>';
$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "DisplayListContrats(".$id_agent.");";
//echo "DisplayContratHisto(".$id_agent.");";

echo "
var MyForm=document.getElementById('FORM_CONTRAT_SUP');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;
?>