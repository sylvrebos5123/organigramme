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

/*if(($art_budgetaire=='')||($art_budgetaire==null))
{
	echo "alert('Veuillez encoder un article budgétaire.');";
	//echo "return false;";
	exit;
}

if(($id_grade==0)&&($id_fonction==0))
{
	echo "alert('Veuillez sélectionner au minimum un grade ou une fonction.');";
	//echo "return false;";
	exit;
}

if($id_bareme==0)
{
	echo "alert('Veuillez sélectionner un barème.');";
	//echo "return false;";
	exit;
}

if(($id_equiv_tp==0)||($id_equiv_tp==0.00)||($id_equiv_tp=='')||($id_equiv_tp==null))
{
	echo "alert('Veuillez encoder une équivalence temps plein.');";
	//echo "return false;";
	exit;
}*/

include('../connect_db.php');


$sql="
			update cpas_places_cadre 
			set
			cancel_date=NOW()
			,cancel_user='".$session_username."'
			,statut='S'
			where
			id_place_cadre='".$id_place_cadre."'
			;
	";	
	

	
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	//$last_id=mysqli_insert_id($lien);
	
	if(!$result)
	{
		echo "alert('Problème de suppression');";
		exit;
	}
	else
	{
		echo "alert('Suppression réussie');";
	}
	mysqli_close($lien);



if($type_cadre==2)
{
	echo "document.getElementById('FORM_CADRE_DIRIGEANT').innerHTML='';";
	echo "DisplayListCadreDirigeant('".$id_cadre."');";
}
else
{
	if($type_cadre==1)
	{
		echo "document.getElementById('FORM_CADRE_STANDARD').innerHTML='';";
		echo "DisplayListCadreStandard('FORM_SELECT_SERVICE');";
	}
}

exit;
?>