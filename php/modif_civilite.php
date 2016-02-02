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
		update cpas_signaletiques_agents set id_civilite='".$id_civilite."',id_etat_civil='".$id_etat_civil."'
		where id_agent='".$id_agent."';
";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);

mysqli_close($lien);


echo '<javascript>';
$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_CIVILITE');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;

?>