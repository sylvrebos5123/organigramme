<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

/*
if(isset($_GET['langue']))
{
	$langue=$_GET['langue'];
	
}else{
	if(isset($_POST['langue']))
	{
		$langue=$_POST['langue'];
	}else{
		$langue='F';
	}
}

*/

if(isset($_GET['nom_champ']))
{
	$nom_champ=$_GET['nom_champ'];
	
}else{
	if(isset($_POST['nom_champ']))
	{
		$nom_champ=$_POST['nom_champ'];
	}else{
		$nom_champ=0;
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

function fn_ResultToArray($result=null,$id_key_unic=null)
{
	//verifier validité de $result
	if($result==null)
	{
		echo "pas de parametre result";
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


/************/
	
include('../connect_db.php');

$sql="SELECT * FROM v_departements_services_cellules where '".$nom_champ."'='".$valeur_champ."';";

var_dump($sql);
$result=mysqli_query($lien,$sql);
//ferme la connection//
mysqli_close($lien);

//$tab_result=mysqli_fetch_assoc($result);
$tab_result=fn_ResultToArray($result,$nom_champ);

include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');

foreach($tab_result as $key =>$value)
{
	/***********/
	if($nom_champ=="ser")
	{
		foreach($array_cellule as $key => $value)
		{
			//$new_key=str_replace('"', '', $key);
			
			if($id_ser==$key)
			{
				echo '<option value="'.$key.'" selected>'.$array_cellule[$key]['F'].'</option>';
			}
			else
			{
				echo '<option value="'.$key.'">'.$array_cellule[$key]['F'].'</option>';
			}
			
		}
		/*if($value['service_F']==$array_service['id_ser'])
		{
			
		}*/
	}
	
}

?>