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
include('../connect_db.php');


$sql="
		update cpas_signaletiques_agents 
		set 
		niveau_etudes='".$niveau_etudes."'
		,id_selor='".$id_selor."'
		,zone_libre_selor='".$zone_libre_selor."'
		,libelle_diplome='".addslashes($libelle_diplome)."'
		,prime_linguistique='".$prime_linguistique."'
		where id_agent='".$id_agent."';
";
var_dump( $sql);
$result=mysqli_query($lien, $sql);

mysqli_close($lien);



echo '<javascript>';
$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_DIPLOME');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;

?>