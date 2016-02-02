<?php
//echo 'coucou';
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include($rootpath.'\\includes\\php_linguistique.php');


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

/********/

include('connect_db.php');

/*if($nom_champ=="id_dep")
{
	$flag='flag_resp_dep=1';
}	
else
{
	$flag='flag_resp_ser=1';
}*/

$sql="
SELECT id_agent,nom,prenom,id_dep,id_ser,id_cel,flag_resp_dep,flag_resp_ser,actif FROM cpas_agents where ".$nom_champ."=".$valeur_champ." and actif=1 order by nom;
";

$result=mysqli_query($lien, $sql);

//echo $sql.'<br>';
if(mysqli_num_rows($result)==0)
{
	echo 'Pas de responsable connu';
	exit;
}

mysqli_close($lien);

//$tab_resp=fn_ResultToArray($result,'id_agent');
$tab_personnel=fn_ResultToArray($result,'id_agent');
//echo $sql;
echo '<br>';
foreach($tab_personnel as $key=>$value)
{
	if($value['flag_resp_ser']==1)
	{
		$style='style="color:#fd0000;background-color:white;"';
		//echo '<span >Responsable : '.$value['nom'].' '.$value['prenom'].'</span><br>';
	}
	else
	{
		$style='background-color:white;';
		
	}
	
	echo '<div '.$style.' >'.$value['nom'].' '.$value['prenom'].'</div>';
	
}

?>