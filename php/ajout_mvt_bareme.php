<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



include('verification.php');
include('params.php');

echo '<javascript>';

if(($date_debut_bareme=='')||($date_debut_bareme=="00-00-0000")||($date_debut_bareme=="0000-00-00"))
{
	echo "alert('Date de début vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}


if(dateValid($date_debut_bareme)==0)
{
	echo "alert('Date de début invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_echeance_bareme!='')&&($date_echeance_bareme!="00-00-0000")&&($date_echeance_bareme!="0000-00-00"))
{
	if(dateValid($date_echeance_bareme)==0)
	{
		echo "alert('Date d\'échéance invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}

	if(compareDate($date_debut_bareme,$date_echeance_bareme)==0)
	{
		echo "alert('La date d\'échéance est plus petite que la date de début! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

if($id_bareme==0)
{
	echo "alert('Vous n\'avez sélectionné aucun barème! Veuillez sélectionner un barème pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

if($id_grade==0)
{
	echo "alert('Vous n\'avez sélectionné aucun grade! Veuillez sélectionner un grade pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

if($id_bareme_cadre==0)
{
	echo "alert('Veuillez sélectionner un barème (CADRE) pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}

if($id_grade_cadre==0)
{
	echo "alert('Veuillez sélectionner un grade (CADRE) pour l\'agent en cours.');";
	//echo "return false;";
	exit;
}




include('../connect_db.php');

	$sql="
			insert into cpas_mouvements_baremes 
			(id_mvt_bareme
			,id_agent
			,id_contrat
			,id_bareme
			,id_code
			,id_grade
			,date_debut_bareme
			,date_echeance_bareme
			,id_bareme_cadre
			,id_code_cadre
			,id_grade_cadre
			,type_cadre
			,actif
			,creation_date
			,creation_user)
			values
			(''
			,'".$id_agent."'
			,'".$id_contrat."'
			,'".$id_bareme."'
			,'".$id_code."'
			,'".$id_grade."'
			,'".transformDate($date_debut_bareme)."'
			,'".transformDate($date_echeance_bareme)."'
			,'".$id_bareme_cadre."'
			,'".$id_code_cadre."'
			,'".$id_grade_cadre."'
			,'".$type_cadre."'
			,'".$actif."'
			,NOW()
			,'".$session_username."'
			);
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	if(!$result)
	{
		echo "alert('Problème d\'ajout du mouvement de barème');";
		exit;
	}
	else
	{
		echo "alert('Mouvement de barème ajouté avec succès');";
	}
	
	/*******Mettre tous les mvt du contrat à inactifs*********************************/
	$sql="
		update cpas_mouvements_baremes set actif=0 where id_contrat='".$id_contrat."';
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
	$sql="
		update cpas_mouvements_baremes set actif=1 where id_contrat='".$id_contrat."' and statut='N'
		and date_debut_bareme<=CURDATE() order by date_debut_bareme desc limit 1;
		
	";
	
	//var_dump( $sql);
	$result=mysqli_query($lien, $sql);
	
	/*********************Mise à jour du contrat*********************************************************/
	
	$sql="update cpas_contrats,cpas_mouvements_baremes 
		set
		cpas_contrats.id_bareme=cpas_mouvements_baremes.id_bareme
		,cpas_contrats.id_code=cpas_mouvements_baremes.id_code
		,cpas_contrats.id_grade=cpas_mouvements_baremes.id_grade
		,cpas_contrats.date_echeance_bareme=cpas_mouvements_baremes.date_echeance_bareme
		,cpas_contrats.id_bareme_cadre=cpas_mouvements_baremes.id_bareme_cadre
		,cpas_contrats.id_code_cadre=cpas_mouvements_baremes.id_code_cadre
		,cpas_contrats.id_grade_cadre=cpas_mouvements_baremes.id_grade_cadre
		,cpas_contrats.type_cadre=cpas_mouvements_baremes.type_cadre
		where cpas_contrats.id_contrat=cpas_mouvements_baremes.id_contrat
		and cpas_contrats.statut='N'
		and cpas_mouvements_baremes.id_contrat = '".$id_contrat."'
		and cpas_mouvements_baremes.actif = 1;";
	
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



echo "document.getElementById('bnt_baremes').className='bnt_open_list';"; 
echo "DisplayMvt('LIST_MVT_BAREME','baremes','$id_agent','$id_contrat');";
//echo "DisplayListContrats('$id_agent');";
echo "document.getElementById('DIV_FORM_MVT_BAREME').innerHTML='';"; 

exit;	
?>