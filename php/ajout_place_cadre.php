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

if(($art_budgetaire=='')||($art_budgetaire==null))
{
	echo "alert('Veuillez encoder un article budgétaire.');";
	//echo "return false;";
	exit;
}

if($id_grade==0)
{
	echo "alert('Veuillez sélectionner un grade.');";
	//echo "return false;";
	exit;
}

if($id_bareme==0)
{
	echo "alert('Veuillez sélectionner un barème.');";
	//echo "return false;";
	exit;
}

//if(($id_equiv_tp==0)||($id_equiv_tp==0.00)||($id_equiv_tp=='')||($id_equiv_tp==null))
if(($id_equiv_tp=='')||($id_equiv_tp==null))
{
	echo "alert('Veuillez encoder une équivalence temps plein svp.');";
	//echo "return false;";
	exit;
}

if($type_cadre==1)
{
	if(($id_dep!=0)&&($id_ser!=0))
	{
		$id_hors_dep=0;
	}
}
include('../connect_db.php');
	
	
$sql="
			insert into cpas_places_cadre 
			(id_place_cadre
			,article_budgetaire
			,id_hors_dep
			,id_dep
			,id_ser
			,id_grade
			,id_fonc
			,id_bareme
			,id_code
			,id_equiv_tp
			,type_cadre
			,id_cadre
			,creation_date
			,creation_user
			)
			values
			(''
			,'".$art_budgetaire."'
			,'".$id_hors_dep."'
			,'".$id_dep."'
			,'".$id_ser."'
			,'".$id_grade."'
			,'".$id_fonc."'
			,'".$id_bareme."'
			,'".$id_code."'
			,'".$id_equiv_tp."'
			,'".$type_cadre."'
			,'".$id_cadre."'
			,NOW()
			,'".$session_username."'
			);
	";
	
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	//$last_id=mysqli_insert_id($lien);
	
	if(!$result)
	{
		echo "alert('Problème d\'ajout');";
		exit;
	}
	else
	{
		echo "alert('Ajout réussi');";
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
//echo "DisplayListCadres();";
//echo "DisplayFormDateCadre('".$last_id."');";
//echo "DisplayOngletsCadre('".$last_id."');";
//echo "CloseToLeft('modal_externe',100,0);";
exit;
?>