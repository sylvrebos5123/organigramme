<?php

ob_clean();
header('Content-Type: text/html; charset=utf-8');
include('params.php');

function fn_ResultToArray($result=null,$id_key_unic=null)
{
	/*verifier validité de $result*/
	if($result==null)
	{
		echo "pas de parametre result";
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



if(isset($_GET['search']))
{
	$search=$_GET['search'];
	
}else{
	if(isset($_POST['search']))
	{
		$search=$_POST['search'];
	}else{
		$search=null;
	}
}

if(isset($_GET['type_form']))
{
	$type_form=$_GET['type_form'];
	
}else{
	if(isset($_POST['type_form']))
	{
		$type_form=$_POST['type_form'];
	}else{
		$type_form='';
	}
}
/********************************LECTURE DES DONNEES DE LA TABLE REUNION******************************************/

//$lien=mysqli_connect('localhost','root','','cpas_dev');
include('../connect_db.php');
//mysqli_set_charset($lien, $charset);
$sqlWhere="";
$search=mysqli_real_escape_string($lien,$search);


if(!empty($search))
{
 $sqlWhere=" and (nom like '%".addslashes($search)."%' or prenom like '%".addslashes($search)."%')";
}


$sql="
select 
	id_agent
	,nom
	,prenom
	,login_nt
	,email

from cpas_agents 
where 
	1=1
".$sqlWhere."	
ORDER BY nom ASC, prenom ASC
";
/*conection a la table*/
//connexionDataBase($lien,$database);
$result=mysqli_query($lien, $sql);

//var_dump($sql);
if(!$result)
{
	echo "erreur dans la requete:<i>".$sql."</i>";
	exit;
}
if(mysqli_num_rows($result)==0)
{
	echo 'Pas de résultat';
	echo "<javascript>";
	echo "Disparait('list_agents');";
	exit;
}
/*execution de la requete */
/* fermeture de la table */
mysqli_close($lien);
/*verfication du resultat */


$tab_agents = fn_ResultToArray($result,"id_agent");
//var_dump($tab_reunions);

/********************************AFFICHAGE DES DONNEES DE LA TABLE ******************************************/

foreach($tab_agents as $key=>$value)
{
	echo '<div class="lien_agent" onclick="FillName(\''.addslashes($value['nom' ]).' '.addslashes($value['prenom']).'\');
	LoadFormAgent
	(\''.$value['id_agent'].'\'
	,\'\'
	,0);">';
	//echo "<input type='button' onclick='FillNameEmail(\"".$value['login_nt' ]."\",\"".$value['email' ]."\");'>";
	echo $value['nom' ].' '.$value['prenom'];
	echo '</div>';
}

exit;
?>
