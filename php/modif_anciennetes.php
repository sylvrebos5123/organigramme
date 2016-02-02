<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

/**********Params***************************************/
include('params.php');

/***Fonction qui transforme la date du format français vers le format américain ou inversément***************************/
/*function transformDate($date)
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
}*/

/**********connexion**************************/
include('../connect_db.php');


$action="";



$sql="
select id_agent from cpas_anciennetes where id_agent='".$id_agent."';
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



/*************Requete**************/

if($action=="INSERT")
{
	$sql="
			insert into cpas_anciennetes 
			(id_anciennete
			,id_agent
			,anc_prive_annee
			,anc_prive_mois
			,anc_public_annee
			,anc_public_mois
			,anc_bxl_annee
			,anc_bxl_mois
			,creation_date
			,creation_user) 
			values 
			(''
			,'".$id_agent."'
			,'".$anc_prive_annee."'
			,'".$anc_prive_mois."'
			,'".$anc_public_annee."'
			,'".$anc_public_mois."'
			,'".$anc_bxl_annee."'
			,'".$anc_bxl_mois."'
			,NOW()
			,'".$session_username."');
	";
}
else
{
	if($action=="UPDATE")
	{
		$sql="
		update cpas_anciennetes
		set
		anc_prive_annee='".$anc_prive_annee."'
		,anc_prive_mois='".$anc_prive_mois."'
		,anc_public_annee='".$anc_public_annee."'
		,anc_public_mois='".$anc_public_mois."'
		,anc_bxl_annee='".$anc_bxl_annee."'
		,anc_bxl_mois='".$anc_bxl_mois."'
		,modif_date=NOW()
		,modif_user='".$session_username."'
		where id_agent='".$id_agent."';
		";
	}
}
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);

echo '<javascript>';
$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_ANCIENNETE');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;


?>