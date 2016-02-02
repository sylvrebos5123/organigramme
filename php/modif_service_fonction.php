<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

echo '<javascript>';
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

/***Si on encode une nouvelle fonction dans le formulaire SERVICE/FCT*****/
if($autre_type_fonc!='')
{
//$autre_type_fonc='test';
	include('../connect_db.php');


	$sql="
	insert into cpas_fonctions 
	(id_fonc,label_F,label_N,actif,creation_date,creation_user)
	values
	('','".$autre_type_fonc."','".$autre_type_fonc."(NL)',1,NOW(),'');
	";
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);
	
	$id=mysqli_insert_id($lien);
	mysqli_close($lien);
	
	//echo 'ID '.$id;
	
	$id_fonc=$id;
	
	echo "myHttpRequest('./generated_files/generate_fonctions.php?');";
}

if($modif_service==1)
{

	/**********agent**************************/
	include('../connect_db.php');


	$sql="
	update cpas_agents 
	set 
	id_dep='".$id_dep."',id_ser='".$id_ser."',id_cel='".$id_cel."',id_fonc='".$id_fonc."',flag_resp_dep='".$flag_resp_dep."',flag_resp_ser='".$flag_resp_ser."',modif_date=NOW(),modif_user='".$session_username."'
	where id_agent='".$id_agent."';
	";
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);



	/***********signalétique*********************************/


	include('../connect_db.php');


	$sql="
	update cpas_signaletiques_agents
	set
	zone_libre_service='".$zone_libre_service."'
	,zone_libre_fonction='".$zone_libre_fonction."'
	,ouvrier_employe='".$ouvrier_employe."'
	,categorie='".$categorie."'
	,article_budgetaire='".$art_budgetaire."'
	,modif_date=NOW()
	,modif_user='".$session_username."' 
	where id_agent='".$id_agent."';
	";

	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
	
	
	
	// ajout table mvt
	include('../connect_db.php');


	$sql="
	insert into cpas_mouvements_services
	(id_mvt_service
	,id_agent
	,article_budgetaire
	,id_dep
	,id_ser
	,id_cel
	,zone_libre_service
	,date_mvt
	,creation_date
	,creation_user
	)
	values
	(''
	,'".$id_agent."'
	,'".$art_budgetaire."'
	,'".$id_dep."'
	,'".$id_ser."'
	,'".$id_cel."'
	,'".$zone_libre_service."'
	,CURDATE()
	,NOW()
	,'".$session_username."' 
	);
	";

	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
	
	
}

if($modif_fonction==1)
{
	/**********agent**************************/
	include('../connect_db.php');


	$sql="
	update cpas_agents 
	set 
	id_fonc='".$id_fonc."'
	,flag_resp_dep='".$flag_resp_dep."'
	,flag_resp_ser='".$flag_resp_ser."'
	,modif_date=NOW()
	,modif_user='".$session_username."'
	where id_agent='".$id_agent."';
	";
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);



	/***********signalétique*********************************/


	include('../connect_db.php');


	$sql="
	update cpas_signaletiques_agents
	set
	zone_libre_fonction='".$zone_libre_fonction."'
	,ouvrier_employe='".$ouvrier_employe."'
	,categorie='".$categorie."'
	,modif_date=NOW()
	,modif_user='".$session_username."' 
	where id_agent='".$id_agent."';
	";

	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
	
	
	
	// ajout table mvt
	include('../connect_db.php');


	$sql="
	insert into cpas_mouvements_fonctions
	(id_mvt_fonc
	,id_agent
	,id_fonc
	,zone_libre_fonction
	,ouvrier_employe
	,categorie
	,flag_resp_dep
	,flag_resp_ser
	,date_mvt
	,creation_date
	,creation_user
	)
	values
	(''
	,'".$id_agent."'
	,'".$id_fonc."'
	,'".$zone_libre_fonction."'
	,'".$ouvrier_employe."'
	,'".$categorie."'
	,'".$flag_resp_dep."'
	,'".$flag_resp_ser."'
	,CURDATE()
	,NOW()
	,'".$session_username."' 
	);
	";

	//var_dump($sql);
	$result=mysqli_query($lien, $sql);

	mysqli_close($lien);
}

$message="Modifications enregistrées";
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_SERVICE_FCT');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
exit;

?>