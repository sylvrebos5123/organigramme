<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



include('verification.php');
include('params.php');

echo '<javascript>';

if(($date_debut_fonction=='')||($date_debut_fonction=="00-00-0000")||($date_debut_fonction=="0000-00-00"))
{
	echo "alert('Date de début vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}


if(dateValid($date_debut_fonction)==0)
{
	echo "alert('Date de début invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_echeance_fonction!='')&&($date_echeance_fonction!="00-00-0000")&&($date_echeance_fonction!="0000-00-00"))
{
	if(dateValid($date_echeance_fonction)==0)
	{
		echo "alert('Date d\'échéance invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}

	if(compareDate($date_debut_fonction,$date_echeance_fonction)==0)
	{
		echo "alert('La date d\'échéance est plus petite que la date de début! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if($id_fonc==0)
{
	echo "alert('Vous n\'avez sélectionné aucune fonction! Veuillez sélectionner une fonction pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}
//include('params.php');


/***Si on encode une nouvelle fonction dans le formulaire SERVICE/FCT*****/
if($autre_type_fonc!='')
{
//$autre_type_fonc='test';
	include('../connect_db.php');


	$sql="
	insert into cpas_fonctions 
	(id_fonc,label_F,label_N,actif,creation_date,creation_user)
	values
	('','".addslashes($autre_type_fonc)."','".addslashes($autre_type_fonc)."(NL)',1,NOW(),'".$session_username."');
	";
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);
	
	$id=mysqli_insert_id($lien);
	mysqli_close($lien);
	
	//echo 'ID '.$id;
	
	$id_fonc=$id;
	
	echo "myHttpRequest('./generated_files/generate_fonctions.php?');";
}

include('../connect_db.php');


	$sql="
			
		insert into cpas_mouvements_fonctions 
		(id_mvt_fonction
		,id_agent
		,id_contrat
		,id_fonc
		,ouvrier_employe
		,categorie
		,flag_resp_dep
		,flag_resp_ser
		,actif
		,date_debut_fonction
		,date_echeance_fonction
		,creation_date
		,creation_user)
		values
		(''
		,'".$id_agent."'
		,'".$id_contrat."'
		,'".$id_fonc."'
		,'".$ouvrier_employe."'
		,'".addslashes($categorie)."'
		,'".$flag_resp_dep."'
		,'".$flag_resp_ser."'
		,'".$actif."'
		,'".transformDate($date_debut_fonction)."'
		,'".transformDate($date_echeance_fonction)."'
		,NOW()
		,'".$session_username."'
		);
	
	";
	//var_dump($sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème d\'ajout du mouvement de fonction');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de fonction ajouté avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_fonctions set actif=0 where id_contrat='".$id_contrat."';
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_fonctions set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_fonction<=CURDATE() order by date_debut_fonction desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	
		
	$sql="update cpas_contrats,cpas_mouvements_fonctions 
		set
		
		cpas_contrats.id_fonc=cpas_mouvements_fonctions.id_fonc
		,cpas_contrats.ouvrier_employe=cpas_mouvements_fonctions.ouvrier_employe
		,cpas_contrats.categorie=cpas_mouvements_fonctions.categorie
		,cpas_contrats.flag_resp_dep=cpas_mouvements_fonctions.flag_resp_dep
		,cpas_contrats.flag_resp_ser=cpas_mouvements_fonctions.flag_resp_ser
		,cpas_contrats.date_echeance_fonction=cpas_mouvements_fonctions.date_echeance_fonction
		where cpas_contrats.id_contrat=cpas_mouvements_fonctions.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_fonctions.id_contrat = '".$id_contrat."'
		and cpas_mouvements_fonctions.actif = 1;";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème de mise à jour du contrat');";
		exit;
	}
	else
	{
		echo "alert('Mise à jour du contrat réussie');";
	}
	/************/
	
	
	mysqli_close($lien);



echo "document.getElementById('bnt_fonctions').className='bnt_open_list';";
echo "DisplayMvt('LIST_MVT_FCT','fonctions','$id_agent','$id_contrat');";

echo "document.getElementById('DIV_FORM_MVT_FCT').innerHTML='';"; 

exit;		
?>