<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

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
/**********Params***************************************/
include('params.php');

/**********agent**************************/
if($modif_domicile==1)
{
	include('../connect_db.php');


	$sql="
			update cpas_signaletiques_agents 
			set 
			adresse_domicile='".addslashes($adresse_domicile)."'
			,num_domicile='".addslashes($num_domicile)."'
			,bte_domicile='".addslashes($bte_domicile)."'
			,code_postal='".$code_postal."'
			,localite='".addslashes($localite)."'
			,bxl_hbxl='".$bxl_hbxl."'
			,region='".addslashes($region)."'
			where id_agent='".$id_agent."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
	
	//ajout dans la table de mvt
	
	include('../connect_db.php');


	$sql="
			insert into cpas_mouvements_domiciles
			(id_mvt_domicile
			,id_agent
			,adresse_domicile
			,num_domicile
			,bte_domicile
			,code_postal
			,localite
			,region
			,bxl_hbxl
			,date_mvt
			,creation_date
			,creation_user)	
			values
			(''
			,'".$id_agent."'
			,'".addslashes($adresse_domicile)."'
			,'".addslashes($num_domicile)."'
			,'".addslashes($bte_domicile)."'
			,'".$code_postal."'
			,'".addslashes($localite)."'
			,'".addslashes($region)."'
			,'".$bxl_hbxl."'
			,CURDATE()
			,NOW()
			,''
			);
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);

}


if($modif_tel==1)
{
	include('../connect_db.php');


	$sql="
			update cpas_signaletiques_agents 
			set 
			tel_prive='".addslashes($tel_prive)."'
			where id_agent='".$id_agent."';
	";
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
	
	

}



echo '<javascript>';
$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_ADRESSE_TEL');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;

?>