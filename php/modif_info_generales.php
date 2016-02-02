<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

/**********Params***************************************/
include('params.php');

/***Fonction qui transforme la date du format français vers le format américain ou inversément***************************/
function transformDate($date)
{
	$date_result='';
	if(($date=='')||($date==null))
	{
		$date_result='';
		
	}
	else
	{
		$tab_date=array();
		$tab_date=explode("-", $date);
		$date_result=$tab_date[2].'-'.$tab_date[1].'-'.$tab_date[0];
		
		//return $date_result;
	}
	return $date_result;
}
/**********agent**************************/
include('../connect_db.php');


$sql="
update cpas_agents 
set 
registre_id='".$id_registre."',nom='".addslashes($nom)."',prenom='".addslashes($prenom)."',initiales='".$initiales."',genre='".$genre."',langue='".$langue."',modif_date=NOW(),modif_user='".$session_username."'
where id_agent='".$id_agent."';
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

//mysqli_close($lien);

/*******************Vérification si une liaison agent existe dans la table cpas_signaletiques_agents*************************************************/
$action="";

//include('../connect_db.php');


$sql="
select id_agent from cpas_signaletiques_agents where id_agent='".$id_agent."';
";
var_dump($sql);
$result=mysqli_query($lien, $sql);
if(mysqli_num_rows($result)==0)
{
	$action="INSERT";
}
else
{
	$action="UPDATE";
}

//mysqli_close($lien);

/***********signalétique*********************************/


//include('../connect_db.php');

if($action=="INSERT")
{
	$sql="
			insert into cpas_signaletiques_agents (id_sign_agent,id_agent,niss,nationalite,date_naissance,id_civilite) 
			values ('','".$id_agent."','".addslashes($niss)."','".addslashes($nationalite)."','".transformDate($date_naissance)."','".$id_civilite."');
	";
}
else
{
	if($action=="UPDATE")
	{
		$sql="
		update cpas_signaletiques_agents
		set
		niss='".$niss."',nationalite='".addslashes($nationalite)."',date_naissance='".transformDate($date_naissance)."',id_civilite='".$id_civilite."' 
		where id_agent='".$id_agent."';
		";
	}
}
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
/*}
else
{
	if($action=="UPDATE")
	{
		include('../connect_db.php');


		$sql="
		update cpas_signaletiques_agents
		set
		niss='".$niss."',nationalite='".$nationalite."',date_naissance='".transformDate($date_naissance)."',lieu_naissance='".$lieu_naissance."',motif_sortie='".$motif_sortie."',article_budgetaire='".$art_budgetaire."' 
		where id_agent='".$id_agent."';
		";
		var_dump($sql);
		$result=mysqli_query($lien, $sql);

		mysqli_close($lien);
	}
}*/
echo '<javascript>';
$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_INFO_GN');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;


?>