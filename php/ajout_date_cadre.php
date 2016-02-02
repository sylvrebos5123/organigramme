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
			insert into cpas_cadres 
			(id_cadre
			,date_situation
			)
			values
			(''
			,'".transformDate($date_situation)."'
			
			);
	";
	//var_dump( $sql);
	
	$result=mysqli_query($lien, $sql);
	
	$last_id=mysqli_insert_id($lien);
	
	if(!$result)
	{
		echo "alert('Problème d\'ajout de la date');";
		exit;
	}
	else
	{
		echo "alert('Ajout de la date réussi');";
	}
	mysqli_close($lien);
	
	if($id_cadre_a_dupliquer != 0)
	{
		include('../connect_db.php');


		$sql="
				insert into 
				cpas_places_cadre
				(article_budgetaire,id_hors_dep,id_dep,id_ser,id_grade,id_fonc,id_bareme,id_code,id_equiv_tp,type_cadre,id_cadre,creation_date,creation_user,modif_date,modif_user,statut)
				
				select 
				article_budgetaire,id_hors_dep,id_dep,id_ser,id_grade,id_fonc,id_bareme,id_code,id_equiv_tp,type_cadre,".$last_id.",CURRENT_DATE(),'".$session_username."','0000-00-00','','N' from cpas_places_cadre where id_cadre = ".$id_cadre_a_dupliquer."

		";
		//var_dump( $sql);
		
		$result=mysqli_query($lien, $sql);
		
		//$last_id=mysqli_insert_id($lien);
		
		if(!$result)
		{
			echo "alert('Problème de duplication du cadre');";
			exit;
		}
		else
		{
			echo "alert('Duplication cadre réussi');";
		}
		mysqli_close($lien);
		
	
	}


echo "DisplayListCadres();";
echo "DisplayFormDateCadre('".$last_id."');";
echo "DisplayOngletsCadre('".$last_id."');";
echo "DisplayListCadreDirigeant('".$last_id."');";
//echo "CloseToLeft('modal_externe',100,0);";
exit;
?>