<?php
// vide buffer LG //
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };



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

$sql="SELECT * FROM cpas_types_primes order by label_F;";

var_dump($sql);
$result=mysqli_query($lien,$sql);
//ferme la connection//
mysqli_close($lien);

$tab_types_primes=fn_ResultToArray($result,"id_type_prime");
	
/****************************************************************/

$outStr="";
	

$headStr="<?php \n";			
				
$nomTable="array_type_prime";

$initialisation="$".$nomTable."=array(); \n";	


$outStr.="$".$nomTable."[0]['F']='---'; \n";
$outStr.="$".$nomTable."[0]['N']='---'; \n";
foreach ($tab_types_primes as $key => $value)
{
	
	$outStr.="$".$nomTable."[".$value['id_type_prime']."]['F']=\"".addslashes($value['label_F'])."\"; \n";
	$outStr.="$".$nomTable."[".$value['id_type_prime']."]['N']=\"".addslashes($value['label_N'])."\"; \n";

}

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