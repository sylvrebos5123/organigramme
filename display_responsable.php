<?php
//echo 'coucou';
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include($rootpath.'\\organigramme\\includes\\php_linguistique.php');


/*******params**********************/
if(isset($_GET['nom_champ']))
{
	$nom_champ=$_GET['nom_champ'];
	
}else{
	if(isset($_POST['nom_champ']))
	{
		$nom_champ=$_POST['nom_champ'];
	}else{
		$nom_champ='';
	}
}

if(isset($_GET['valeur_champ']))
{
	$valeur_champ=$_GET['valeur_champ'];
	
}else{
	if(isset($_POST['valeur_champ']))
	{
		$valeur_champ=$_POST['valeur_champ'];
	}else{
		$valeur_champ=0;
	}
}



/*****************Fonction qui met le résultat de la requête dans un tableau******************************************************************************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	$tableau=null;
	/*verifier validité de $result*/
	if($result==null)
	{
		echo 'no result';
		return false;
	}
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


echo "
<style>
.surligne
{
background-color:none;
}

.surligne:hover
{
background-color:#666;
}

.icone_liste
{
width:25px;height:25px;
background:url('./images/icone_liste_25px.png') no-repeat;

}

.icone_liste:hover
{
width:25px;height:25px;
background:#ddd url('./images/icone_liste_25px.png') no-repeat;

}
</style>


";
/********/

include('connect_db.php');

/*
if($nom_champ=="id_hors_dep")
{
	$flag='cpas_contrats.flag_resp_dep=1';

}


$sql="
SELECT * FROM `cpas_agents`
 join `cpas_contrats`
 on cpas_agents.id_agent=cpas_contrats.id_agent
 WHERE
 cpas_contrats.".$nom_champ."=".$valeur_champ." and ".$flag." and cpas_contrats.actif=1 ;

";

$result=mysqli_query($lien, $sql);
var_dump($sql);
//echo $sql.'<br>';


if(mysqli_num_rows($result)==0)
{
	echo '<div>Pas de responsable connu</div>';
	//exit;
}
else
{
	echo '<div>Responsable : ';
	$tab_resp=mysqli_fetch_assoc($result);
	
	echo '<span class="surligne" onclick="LoadFormAgent(\''.$tab_resp['id_agent'].'\',\''.$nom_champ.'\',\''.$valeur_champ.'\');">'.$tab_resp['nom'].' '.$tab_resp['prenom'].'</span></div><br>';

}

mysqli_close($lien);*/

if(($nom_champ=="id_dep") || ($nom_champ=="id_hors_dep"))
{
	$flag='cpas_contrats.flag_resp_dep=1';
}	
else
{
	//$flag='cpas_contrats.flag_resp_dep=0';
	$flag='cpas_contrats.flag_resp_ser=1';
} 

/*$sql="
SELECT id_agent,nom,prenom,id_dep,id_ser,id_cel,flag_resp_dep,flag_resp_ser,actif FROM cpas_agents where ".$nom_champ."=".$valeur_champ." and ".$flag." and actif=1;
";*/

/*
$sql="
select 
*
from cpas_agents
join cpas_mouvements_fonctions
on cpas_agents.id_agent=cpas_mouvements_fonctions.id_agent
where 
cpas_mouvements_fonctions.actif=1 ;
";*/

$sql="
SELECT * FROM `cpas_agents`
 join `cpas_contrats`
 on cpas_agents.id_agent=cpas_contrats.id_agent
 WHERE
 cpas_contrats.".$nom_champ."=".$valeur_champ." and ".$flag." and cpas_contrats.actif=1 ;
";

$result=mysqli_query($lien, $sql);

//echo $sql.'<br>';


if(mysqli_num_rows($result)==0)
{
	echo '<div>Pas de responsable connu</div>';
	//exit;
}
else
{
	$tab_resp=mysqli_fetch_assoc($result);
	echo '<div class="icone_liste" style="float:left;" title="Générer une fiche individuelle" onclick="GenerateFicheIndividuelle('.$tab_resp['id_agent'].');"></div><div>Responsable : ';
	
	echo '<span class="surligne" onclick="LoadFormAgent(\''.$tab_resp['id_agent'].'\',\''.$nom_champ.'\',\''.$valeur_champ.'\');">'.$tab_resp['nom'].' '.$tab_resp['prenom'].'</span></div><br>';

}

//mysqli_close($lien);



/*****************************************************************/

//include('connect_db.php');



$sql="
SELECT * FROM `cpas_agents`
 join `cpas_contrats`
 on cpas_agents.id_agent=cpas_contrats.id_agent
 WHERE
 cpas_contrats.".$nom_champ."=".$valeur_champ." 
 and cpas_contrats.actif=1 
 and cpas_contrats.flag_resp_dep=0 
 and cpas_contrats.flag_resp_ser=0 
 order by cpas_agents.nom;
";

$result=mysqli_query($lien, $sql);

//echo $sql.'<br>';
if(mysqli_num_rows($result)==0)
{
	echo '<div style="overflow-y:auto;overflow-x:hidden;height:100px;">';
	echo "Pas d'agent existant";
	echo '</div>';
	
	//echo '<span class="surligne" onclick="LoadFormAgent(0,\''.$nom_champ.'\','.$valeur_champ.');">Ajout d\'un agent</span> ';

	//echo '<div align="right" class="bnt_close" style="margin-top:5px;" onclick="Close();">Fermer la fenêtre</div>';

	//exit;
}
else
{
	$tab_personnel=fn_ResultToArray($result,'id_agent');
	echo '<div style="border:1px solid #D8D8D8;padding:5px;overflow-y:auto;overflow-x:hidden;height:100px;width:270px;">';

	foreach($tab_personnel as $key=>$value)
	{
		if($value['flag_resp_ser']==1)
		{
			$style='style="color:#fd0000;background-color:#999;font-size:11pt;"';
			//echo '<span >Responsable : '.$value['nom'].' '.$value['prenom'].'</span><br>';
		}
		else
		{
			$style='style="background-color:#999;"';
			
		}
		echo '<div class="icone_liste" style="float:left;" title="Générer une fiche individuelle" onclick="GenerateFicheIndividuelle('.$value['id_agent'].');"></div>';
		echo '<div style="padding:5px;margin:5px;">';
		//echo '<span class="icone_liste" title="Générer une fiche individuelle" onclick="GenerateFicheIndividuelle('.$value['id_agent'].');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
		echo '<span class="surligne"  onclick="LoadFormAgent(\''.$value['id_agent'].'\',\''.$nom_champ.'\',\''.$valeur_champ.'\');">'.$value['nom'].' '.$value['prenom'].'</span></div>';
		
	}
	echo '</div>';
}

//mysqli_close($lien);





//echo '<span class="surligne" onclick="LoadFormAgent(0,\''.$nom_champ.'\','.$valeur_champ.');">Ajout d\'un agent</span> ';
//echo ' - ';
echo '<span class="surligne" onclick="DeplacerListAgent(\''.$nom_champ.'\','.$valeur_champ.');">Déplacer des agents</span> <br>';

/* if($nom_champ=='id_dep')
{
	echo '<span class="surligne" onclick="LoadFormService(0,'.$valeur_champ.');">Ajout d\'un service</span>';	
} */

if($nom_champ=='id_ser')
{
	//include('connect_db.php');

	
	$sql="
	SELECT * FROM `cpas_services`
	WHERE
	 ".$nom_champ."=".$valeur_champ." ;
	";

	$result=mysqli_query($lien, $sql);

	//var_dump($sql);
	if(mysqli_num_rows($result)==0)
	{
		echo 'Problème de lecture du service';
		
		exit;
	}

	//mysqli_close($lien);

	$tab_ser=mysqli_fetch_assoc($result);
/************************************************/
	
	/*echo '<span class="surligne" onclick="LoadFormCellule(0,'.$valeur_champ.','.$tab_ser['id_dep'].');">Ajout d\'une cellule</span>';
	echo ' - ';
	echo '<span class="surligne" onclick="LoadFormService('.$valeur_champ.','.$tab_ser['id_dep'].');">Modif service</span>';
*/
}

/* if($nom_champ=='id_cel')
{
	echo '<span class="surligne" onclick="LoadFormCellule('.$valeur_champ.',0,0);">Modif cellule</span>';
	
} */

echo '<div align="right" class="bnt_close" style="margin-top:5px;" onclick="Close();">Fermer la fenêtre</div>';
mysqli_close($lien);
exit;
?>