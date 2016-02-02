<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

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


include('params.php');

echo '<javascript>';

if(($date_situation=='')||($date_situation=='00-00-0000')||($date_situation=='0000-00-00'))
{
	echo "alert('Veuillez sélectionner une date svp.');";
	//echo "return false;";
	exit;
}

include('../connect_db.php');


	
	
	$sql="
			update cpas_cadres 
			set
			date_situation='".transformDate($date_situation)."'
			where
			id_cadre='".transformDate($id_cadre)."';
	";
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	//$last_id=mysqli_insert_id($lien);
	
	if(!$result)
	{
		echo "alert('Problème de modification de la date');";
		exit;
	}
	else
	{
		echo "alert('Modification de la date réussie');";
	}
	mysqli_close($lien);


echo "DisplayListCadres();";
echo "DisplayFormDateCadre('".$id_cadre."');";
//echo "DisplayOngletsCadre('".$id_cadre."');";
//echo "CloseToLeft('modal_externe',100,0);";
exit;
?>