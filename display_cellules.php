<?php
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
//include($rootpath.'\\includes\\php_linguistique.php');


/*******params**********************/
if(isset($_GET['id_ser']))
{
	$id_ser=$_GET['id_ser'];
	
}else{
	if(isset($_POST['id_ser']))
	{
		$id_ser=$_POST['id_ser'];
	}else{
		$id_ser=0;
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
	
$sql="
SELECT * FROM cpas_cellules where id_ser=".$id_ser." and actif=1 order by label_F;
";

$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	echo 'Pas de cellule<br>';
	exit;
}

mysqli_close($lien);

$tab_cel=fn_ResultToArray($result,'id_cel');

echo '<ul>';
foreach($tab_cel as $key=>$value)
{
	echo '<div id="DIV_id_cel_'.$value['id_cel'].'" style="margin-top:-5px;margin-left:20px;background-color:white;z-index:30;"></div>';
								
	echo '<li><div id="info_id_cel_'.$value['id_cel'].'" onmouseover="document.getElementById(\'bnt_edit_del_cel_'.$value['id_cel'].'\').style.visibility=\'visible\';" onmouseout="document.getElementById(\'bnt_edit_del_cel_'.$value['id_cel'].'\').style.visibility=\'hidden\';" >
					
		<a class="info" onclick="DisplayResponsable(\'id_cel\','.$value['id_cel'].');">'.$value['label_F'].'</a>';
	
			echo '<div id="bnt_edit_del_cel_'.$value['id_cel'].'" style="cursor:default;visibility:hidden;height:63px;margin-top:2px;margin-left:10px;padding-left:10px;">';
				//echo '<span class="bnt_ajout_ser" title="Ajouter un service" onclick="LoadFormService(0,\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
				echo '<span class="bnt_modif" title="Modifier cette cellule"  onclick="LoadFormCellule(\''.$value['id_cel'].'\',\''.$value['id_ser'].'\',\''.$value['id_dep'].'\');">&nbsp;</span>';
				echo '<span class="bnt_suppr" title="Supprimer cette cellule" onclick="SupprCellule(\''.$value['id_cel'].'\');">&nbsp;</span>';
				
			echo ' </div>';
	echo '</div>';
	echo '</li>';
	//echo '<div id="DIV_id_cel_'.$value['id_cel'].'" style="background-color:white;"></div>';
}

//echo '<div id="box_cel" style="visibility:hidden;"><div class="head_cel"></div></div>';

echo '</ul>';

exit;	
?>