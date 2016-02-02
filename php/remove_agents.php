<?php
header('Content-Type: text/html; charset=utf-8');

include('params.php');
include('verification.php');
//echo $art_budgetaire;
//var_dump($_GET['bnt']);

echo '<javascript>';
if(!isset($_GET['bnt']))
{
	echo "alert('Veuillez cocher un ou plusieurs agents à déplacer.');";
	//echo "return false;";
	exit;
} 
if(($date_debut_service=='')||($date_debut_service=="00-00-0000")||($date_debut_service=="0000-00-00"))
{
	echo "alert('Date de début vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}

if(transformDate($date_debut_service) < date('Y-m-d'))
	{
		echo "alert('La date de début est dépassée! Veuillez choisir une date plus récente.');";
		//echo "return false;";
		exit;
	}



if(dateValid($date_debut_service)==0)
{
	echo "alert('Date de début invalide! Veuillez recommencer.');";
	//echo "return false;";
	exit;
}

if(($date_echeance_service!='')&&($date_echeance_service!="00-00-0000")&&($date_echeance_service!="0000-00-00"))
{
	if(dateValid($date_echeance_service)==0)
	{
		echo "alert('Date d\'échéance invalide! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}

	if(compareDate($date_debut_service,$date_echeance_service)==0)
	{
		echo "alert('La date d\'échéance est plus petite que la date de début! Veuillez recommencer.');";
		//echo "return false;";
		exit;
	}
}

/*if($art_budgetaire=='')
{
	echo "alert('Article budgétaire vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}*/
if(($id_article_budgetaire=='')||($id_article_budgetaire==0)||($id_article_budgetaire==null))
{
	echo "alert('Article budgétaire vide! Veuillez remplir le champ prévu.');";
	//echo "return false;";
	exit;
}


if($choix_groupe=="DEP")
{
	$id_hors_dep=0;
}
else
{
	$id_dep=0;
	$id_ser=0;
	$id_cel=0;
}
if(($id_dep==0)&&($id_hors_dep==0))
{
	echo "alert('Vous n\'avez sélectionné aucun département! Veuillez sélectionner le département auquel l\'agent appartient.');";
	//echo "return false;";
	exit;
}
if(isset($_GET['bnt']))
{
	$bnt=$_GET['bnt'];
	
	if(is_array($bnt))
	{
	  //echo 'Bnt est un array';
	  if( ($bnt=="") || ($bnt==null) )
	  {
		//echo 'Le tableau bnt est vide<br>';
		echo "alert('Le tableau bnt est vide');";
	  }
	  else
	  {	
		include('../connect_db.php');
		
		foreach($bnt as $key=>$value)
		{
			
			/*********Modification du contrat de l'agent****************/
			
			
			
			$sql="
					insert into cpas_mouvements_services 
					(id_mvt_service
					,id_agent
					,id_contrat
					,id_article_budgetaire
					,id_hors_dep
					,id_dep
					,id_ser
					,id_cel
					,date_debut_service
					,date_echeance_service
					,creation_date
					,creation_user)
					values
					(''
					,'".$key."'
					,'".$value."'
					,'".$id_article_budgetaire."'
					,'".$id_hors_dep."'
					,'".$id_dep."'
					,'".$id_ser."'
					,'".$id_cel."'
					,'".transformDate($date_debut_service)."'
					,'".transformDate($date_echeance_service)."'
					,NOW()
					,'".$session_username."'
					);
			";
			
			$result=mysqli_query($lien, $sql);
			
			$last_id=mysqli_insert_id($lien);
			
			//echo "alert('$last_id');";
			
			if(!$result)
			{
				echo "alert('Problème de mutation de service pour l'id_contrat '".$value.");";
				exit;
			}
			
			if(transformDate($date_debut_service) == date('Y-m-d'))
			{
			
				
				
				/*******Mettre tous les mvt du contrat à inactifs*********************************/
				$sql="
					update cpas_mouvements_services set actif=0 where id_contrat='".$value."';
					
				";
				
				//var_dump( $sql);
				$result=mysqli_query($lien, $sql);
				
				/**********Mettre à 1 le mvt du contrat le plus récent*************************************************/
				$sql="
					update cpas_mouvements_services set actif=1 where id_mvt_service='".$last_id."';
					
				";
				
				//var_dump( $sql);
				$result=mysqli_query($lien, $sql);
				
				/*********************Mise à jour du contrat*********************************************************/
				$sql="update cpas_contrats,cpas_mouvements_services 
					set
					cpas_contrats.id_article_budgetaire=cpas_mouvements_services.id_article_budgetaire
					,cpas_contrats.id_hors_dep=cpas_mouvements_services.id_hors_dep
					,cpas_contrats.id_dep=cpas_mouvements_services.id_dep
					,cpas_contrats.id_ser=cpas_mouvements_services.id_ser
					,cpas_contrats.id_cel=cpas_mouvements_services.id_cel
					,cpas_contrats.date_echeance_service=cpas_mouvements_services.date_echeance_service
					where cpas_contrats.id_contrat=cpas_mouvements_services.id_contrat
					and cpas_mouvements_services.id_contrat = '".$value."'
					and cpas_mouvements_services.actif = 1;";
				
				//var_dump( $sql);
				$result=mysqli_query($lien, $sql);
				
				if(!$result)
				{
					echo "alert('Problème de mise à jour du contrat pour l\'id_contrat '".$value."');";
					exit;
				}
				
				/************/
			}
			
			
		}
		mysqli_close($lien);
	  }
	}
	else
	{
		echo "alert('Bnt n\'est pas un array');";
		exit;
	}
	
	
}
echo "
	var MyForm=document.getElementById('FORM_AGENTS_REMOVE');
	MyForm.elements['bnt_sauver'].disabled=true;
	MyForm.elements['bnt_sauver'].style.background='';
	";
	echo "alert('Déplacement effectué avec succès');";
	echo "CloseToLeft('modal_externe',100,0);";
exit;
?>