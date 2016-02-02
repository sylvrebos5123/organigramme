<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

/*******Fonction permettant de mettre le résultat d'une requête dans un array******************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	/*verifier validité de $result*/
	if($result==null)
	{
		echo "pas de paramètre result<br>";
		return false;
	}
	
	$tableau=array();
	while($datas = mysqli_fetch_assoc($result))
	{
		if($id_key_unic==null)
		{
			$tableau[]=$datas;
		}else{
			$tableau[$datas[$id_key_unic]]=$datas;
		}
		
	}
	return $tableau;
}
/***************/
include('../connect_db.php');

$sql="select 
cpas_mouvements_fonctions.id_mvt_fonction
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_mouvements_fonctions.date_debut_fonction
,cpas_mouvements_fonctions.date_echeance_fonction
from cpas_agents
join cpas_mouvements_fonctions
on cpas_agents.id_agent=cpas_mouvements_fonctions.id_agent
where statut='N';
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

mysqli_close($lien);

$tab_mvt_fct=fn_ResultToArray($result,'id_mvt_fonction');
/*********************/

include('../connect_db.php');

$sql="select 
cpas_mouvements_services.id_mvt_service
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_mouvements_services.date_debut_service
,cpas_mouvements_services.date_echeance_service
from cpas_agents
join cpas_mouvements_services
on cpas_agents.id_agent=cpas_mouvements_services.id_agent
where statut='N';
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

//mysqli_close($lien);

$tab_mvt_ser=fn_ResultToArray($result,'id_mvt_service');
/***************************************/



$sql="select 
cpas_mouvements_baremes.id_mvt_bareme
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_mouvements_baremes.date_debut_bareme
,cpas_mouvements_baremes.date_echeance_bareme
from cpas_agents
join cpas_mouvements_baremes
on cpas_agents.id_agent=cpas_mouvements_baremes.id_agent
where statut='N';
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

//mysqli_close($lien);

$tab_mvt_baremes=fn_ResultToArray($result,'id_mvt_bareme');
/***************************************/



$sql="select 
cpas_mouvements_statuts.id_mvt_statut
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_mouvements_statuts.date_debut_statut
,cpas_mouvements_statuts.date_echeance_statut
from cpas_agents
join cpas_mouvements_statuts
on cpas_agents.id_agent=cpas_mouvements_statuts.id_agent
where statut='N';
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

//mysqli_close($lien);

$tab_mvt_statuts=fn_ResultToArray($result,'id_mvt_statut');
/***************************************/


$sql="select 
cpas_mouvements_regimes.id_mvt_regime
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_mouvements_regimes.date_debut_regime
,cpas_mouvements_regimes.date_echeance_regime
from cpas_agents
join cpas_mouvements_regimes
on cpas_agents.id_agent=cpas_mouvements_regimes.id_agent
where statut='N';
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

//mysqli_close($lien);

$tab_mvt_regimes=fn_ResultToArray($result,'id_mvt_regime');
/***************************************/
/*
include('../connect_db.php');

$sql="select 
cpas_mouvements_domiciles.id_mvt_domicile
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_mouvements_domiciles.date_mvt
from cpas_agents
join cpas_mouvements_domiciles
on cpas_agents.id_agent=cpas_mouvements_domiciles.id_agent;
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

mysqli_close($lien);

$tab_mvt_domiciles=fn_ResultToArray($result,'id_mvt_domicile');*/
/***************************************/


//include('../connect_db.php');

$sql="select 
cpas_contrats.id_contrat
,cpas_agents.id_agent
,cpas_agents.nom
,cpas_agents.prenom
,cpas_contrats.start_date
,cpas_contrats.end_date
from cpas_agents
join cpas_contrats
on cpas_agents.id_agent=cpas_contrats.id_agent
where cpas_contrats.statut='N';
";
//var_dump( $sql);

$result=mysqli_query($lien, $sql);

//var_dump($result);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}

mysqli_close($lien);

$tab_mvt_contrats=fn_ResultToArray($result,'id_contrat');
/***************************************/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Organigramme CPAS - Calendrier échéances</title>
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.print.css' media='print' />

<script type='text/javascript' src='jquery/jquery-1.7.1.min.js'></script>
<script type='text/javascript' src='jquery/jquery-ui-1.8.17.custom.min.js'></script>
<script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			editable: false,
			header: {
				left: 'prevYear,prev,next,nextYear today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonText :
			{
			today:    'Aujourd\'hui',
			month:    'mois',
			week:     'semaine',
			day:      'jour'	
			},
			monthNames:
			['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
			'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
			dayNamesShort:
			['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
			events: [
				<?php
				$array_contrats=array();
				foreach($tab_mvt_contrats as $key =>$value)
				{
				?>
					//DEBUT CONTRAT
					{
						title: "<?php echo 'Début de contrat pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['start_date']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						backgroundColor: '#777',
						borderColor: '#777',
						textColor: 'white'
					},
					//FIN CONTRAT
					{
						title: "<?php echo 'Fin de contrat pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['end_date']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						backgroundColor: 'black',
						borderColor: 'black',
						textColor: 'white'
					},
				<?php
					$array_contrats[$value['id_agent']]=$value['start_date'];
				}
				?>
				<?php
	
			
				foreach($tab_mvt_fct as $key =>$value)
				{
				/*********Si la date des différents mvt = date début contrat 
				alors il n'est pas nécessaire d'afficher tous les mvt
				sinon afficher les changements de situation
	************************************************/
					if($array_contrats[$value['id_agent']] != $value['date_debut_fonction'])
					{
				?>
					{
						title: "<?php echo 'Nouveau mouvement de fonction pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_debut_fonction']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'orange_clair',
						borderColor: '#FC8702',
						textColor: 'white'
					},
					<?php
					/****FIN DU IF****/
					}
					?>
					{
						title: "<?php echo 'Echéance de fonction pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_echeance_fonction']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'orange_fonce',
						borderColor: '#FC8702',
						textColor: 'white'
					},
				<?php
				}
				?>
				<?php
				foreach($tab_mvt_ser as $key =>$value)
				{
				/*********Si la date des différents mvt = date début contrat 
				alors il n'est pas nécessaire d'afficher tous les mvt
				sinon afficher les changements de situation
	************************************************/
				if($array_contrats[$value['id_agent']] != $value['date_debut_service'])
				{
				?>
					{
						title: "<?php echo 'Nouveau mouvement de service pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_debut_service']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'jaune_clair',
						borderColor: '#FCBF02',
						textColor: 'black'
					},
				<?php
				/****FIN DU IF****/
				}
				?>
					{
						title: "<?php echo 'Echéance de service pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_echeance_service']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'jaune_fonce',
						borderColor: '#FCBF02',
						textColor: 'black'
					},
					
				<?php
				}
				?>
				<?php
				foreach($tab_mvt_baremes as $key =>$value)
				{
				/*********Si la date des différents mvt = date début contrat 
				alors il n'est pas nécessaire d'afficher tous les mvt
				sinon afficher les changements de situation
	************************************************/
					if($array_contrats[$value['id_agent']] != $value['date_debut_bareme'])
					{
				?>
					{
						title: "<?php echo 'Nouveau mouvement de barème/code/grade pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_debut_bareme']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'vert_clair',
						borderColor: '#1a7c1e',
						textColor: 'white'
					},
				<?php
				/****FIN DU IF****/
					}
				?>
					{
						title: "<?php echo 'Echéance de barème/code/grade pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_echeance_bareme']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'vert_fonce',
						borderColor: '#1a7c1e',
						textColor: 'white'
					},
				<?php
				}
				?>
				
				
				<?php
				foreach($tab_mvt_statuts as $key =>$value)
				{
				/*********Si la date des différents mvt = date début contrat 
				alors il n'est pas nécessaire d'afficher tous les mvt
				sinon afficher les changements de situation
	************************************************/
					if($array_contrats[$value['id_agent']] != $value['date_debut_statut'])
					{
				?>
						{
							title: "<?php echo 'Nouveau mouvement de statut pour '.$value['nom'].' '.$value['prenom'];?>",
							<?php
							$tab_date=explode('-',$value['date_debut_statut']);
							?>
							start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
							className: 'rose_clair',
							borderColor: 'magenta',
							textColor: 'white'
						},
				<?php
				/****FIN DU IF****/
					}
				?>
					{
						title: "<?php echo 'Echéance de statut pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_echeance_statut']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'rose_fonce',
						borderColor: 'magenta',
						textColor: 'white'
					},
					
				<?php
				}
				?>
				<?php
				foreach($tab_mvt_regimes as $key =>$value)
				{
					/*********Si la date des différents mvt = date début contrat 
				alors il n'est pas nécessaire d'afficher tous les mvt
				sinon afficher les changements de situation
	************************************************/
					if($array_contrats[$value['id_agent']] != $value['date_debut_regime'])
					{
				?>
						{
							title: "<?php echo 'Nouveau mouvement de régime pour '.$value['nom'].' '.$value['prenom'];?>",
							<?php
							$tab_date=explode('-',$value['date_debut_regime']);
							?>
							start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
							className: 'bleu_clair',
							borderColor: 'blue',
							textColor: 'white'
						},
				<?php
				/****FIN DU IF****/
					}
				?>
					{
						title: "<?php echo 'Echéance de régime pour '.$value['nom'].' '.$value['prenom'];?>",
						<?php
						$tab_date=explode('-',$value['date_echeance_regime']);
						?>
						start: new Date(<?php echo $tab_date[0];?>, <?php echo $tab_date[1]-1;?>, <?php echo $tab_date[2];?>),
						className: 'bleu_fonce',
						borderColor: 'blue',
						textColor: 'white'
					},
				<?php
				}
				?>
				
				{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					backgroundColor: 'white',
					borderColor: 'white',
					textColor: 'white'
				}
			]
		});
		
	});

</script>
<style type='text/css'>
	.lien_menu
	{
		cursor:default;
		text-align:left;
		padding-left:10px;
		font-size:1em;
		font-family:Verdana,Arial;
		background-color:white;
	}

	.lien_menu:hover
	{
		cursor:default;
		text-align:left;
		padding-left:10px;
		font-size:1em;
		font-family:Verdana,Arial;
		color:white;
		background-color:grey;
	}



	body {
		margin-top: 40px;
		
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
	

	#calendar {
		width: 750px;
		margin: 0 auto;
		margin-top: 40px;
		}
		
	#legende {
		margin-left:790px;
		margin-top:40px;
		border:1px solid #777;
		padding:10px;
		width:300px;
		/*height:270px;*/
		}
	
	.bleu_clair,
	.bleu_clair div,
	.bleu_clair span
	{
		background-color:#7575FF;
	}
	
	.bleu_fonce,
	.bleu_fonce div,
	.bleu_fonce span 
	{
		background-color:blue;
	
	}
	.rose_clair,
	.rose_clair div,
	.rose_clair span 
	{
		background-color:#FF70FF;
	}
	
	.rose_fonce,
	.rose_fonce div,
	.rose_fonce span 
	{
		background-color:magenta;
	}
	
	.vert_clair,
	.vert_clair div,
	.vert_clair span
	{
		background-color:#62C166;
	}
	
	.vert_fonce,
	.vert_fonce div,
	.vert_fonce span
	{
		background-color:#1A7C1E;
	}
	
	.orange_clair,
	.orange_clair div,
	.orange_clair span
	{
		background-color:#FFA84C;
	}
	
	.orange_fonce,
	.orange_fonce div,
	.orange_fonce span
	{
		background-color:#FC8702;
	}
	
	.jaune_clair,
	.jaune_clair div,
	.jaune_clair span
	{
		background-color:#FFDC75;
	}
	
	.jaune_fonce,
	.jaune_fonce div,
	.jaune_fonce span
	{
		background-color:#FCBF02;
	}
	
	.gris,
	.gris div,
	.gris span
	{
		background-color:#777;
	}
	
	.noir,
	.noir div,
	.noir span
	{
		background-color:black;
	}
	

</style>
</head>
<body>
<span><img src="../images/logo.gif" align="middle"/> </span>

<span style='font-family: "Lucida Grande", Verdana, sans-serif;font-size:10px;color:#777777;margin: 0 0 20px 0;'>Organigramme CPAS Ixelles | <span style="color:#1a7c1e;font-weight:bold;">Calendrier des échéances et mouvements agents</span><br></span>
<br>
<div id='calendar' align='left' style='float:left;'></div>
<div id='legende'>
<span style="font-weight:bold;">LEGENDE</span><br><br>
<!--
	CONTRAT
	-->
	<table>
		<tr>
			<td><div class="gris" style="width:30px;height:20px;"></div></td><td>Début de contrat</td>
		</tr>
		<tr>
			<td><div class="noir" style="width:30px;height:20px;"></div></td><td>Fin de contrat</td>
		</tr>
	</table>
	<br></br>
	<!--
	SERVICE
	-->
	<table>
		<tr>
			<td><div class="jaune_clair" style="width:30px;height:20px;"></div></td><td>Nouveau service</td>
		</tr>
		<tr>
			<td><div class="jaune_fonce" style="width:30px;height:20px;"></div></td><td>Echéance de service</td>
		</tr>
	</table>
	<br></br>
	<!--
	FONCTION
	-->
	<table>
		<tr>
			<td><div class="orange_clair" style="width:30px;height:20px;"></div></td><td>Nouvelle fonction</td>
		</tr>
		<tr>
			<td><div class="orange_fonce" style="width:30px;height:20px;"></div></td><td>Echéance de fonction</td>
		</tr>
	</table>
	<br></br>
	<!--
	BAREME/CODE/GRADE
	-->
	<table>
		<tr>
			<td><div class="vert_clair" style="width:30px;height:20px;"></div></td><td>Nouveau barème/code/grade</td>
		</tr>
		<tr>
			<td><div class="vert_fonce" style="width:30px;height:20px;"></div></td><td>Echéance de barème/code/grade</td>
		</tr>
	</table>
	<br></br>
	<!--
	STATUT
	-->
	<table>
		<tr>
			<td><div class="rose_clair" style="width:30px;height:20px;"></div></td><td>Nouveau statut</td>
		</tr>
		<tr>
			<td><div class="rose_fonce" style="width:30px;height:20px;"></div></td><td>Echéance de statut</td>
		</tr>
	</table>
	<br></br>
	<!--
	REGIME
	-->
	<table>
		<tr>
			<td><div class="bleu_clair" style="width:30px;height:20px;"></div></td><td>Nouveau régime</td>
		</tr>
		<tr>
			<td><div class="bleu_fonce" style="width:30px;height:20px;"></div></td><td>Echéance de régime</td>
		</tr>
	</table>
</div>
</body>
</html>
