<?php
// vide buffer LG //
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

/*if(isset($_GET['langue']))
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


/*****/
	
include('../connect_db.php');

$sql="SELECT * FROM cpas_hors_departements;";

var_dump($sql);
$result=mysqli_query($lien,$sql);
//ferme la connection//
mysqli_close($lien);

$tab_hors_dep=fn_ResultToArray($result,"id_hors_dep");
	
/****************************************************************/

$outStr="";
	

/*$headStr="<?php
header ('Content-type: text/html; charset=utf-8'); \n";		*/	
		
$headStr="<?php \n";	
		
$nomTable="array_hors_departement";

$initialisation="$".$nomTable."=array(); \n";	

$outStr.="$".$nomTable."[0]['F']='---'; \n";
$outStr.="$".$nomTable."[0]['N']='---'; \n";
foreach ($tab_hors_dep as $key => $value)
{
	//if($value['actif']==1)
	//{
		$outStr.="$".$nomTable."[".$value['id_hors_dep']."]['F']=\"".$value['label_F']."\"; \n";

		$outStr.="$".$nomTable."[".$value['id_hors_dep']."]['N']=\"".$value['label_N']."\"; \n";
	//}
	
}

//$outStr.="$"."count_dep=".$value['id_dep']."; \n";
	


$footStr="?>";		

$totalStr=$headStr;
$totalStr.= $initialisation;
$totalStr.= "\n";
$totalStr.= $outStr;
$totalStr.=$footStr;
				
$nomFichier="../arrays_libelle/".$nomTable.".php";
$fichierOuvert = @fopen($nomFichier, "a");
ftruncate($fichierOuvert,0);
fputs($fichierOuvert,$totalStr);
//!mb_check_encoding($fichierOuvert, 'UTF-8') or return false;
fclose($fichierOuvert);
echo "Fichier sauvegardé et modifié !";
?>