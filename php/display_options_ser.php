<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
include('params.php');
/*******************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	//verifier validité de $result
	if($result==null)
	{
		echo "pas de paramètre result";
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


include('../connect_db.php');


$sql="
		select * from cpas_services where id_dep=".$id_dep." and actif=1 order by label_F;
";

//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo "<option value='0'>---</option>"; 
	
	exit;
}

mysqli_close($lien);


$tab_ser=fn_ResultToArray($result,'id_ser');

echo '<option value="0">---</option>'; 

foreach($tab_ser as $key => $value)
{
		
	echo '<option value="'.$value['id_ser'].'" >'.$value['label_F'].'</option>';
			
}
?>