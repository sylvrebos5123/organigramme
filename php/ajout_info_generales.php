<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

echo "<javascript>";



/***Fonction qui transforme la date du format français vers le format américain ou inversément***************************/
function transformDate($date)
{
	$date_result='';
	if(($date=='')||($date==null))
	{
		$date_result='';
		
	}
	else
	{
		$tab_date=array();
		$tab_date=explode("-", $date);
		$date_result=$tab_date[2].'-'.$tab_date[1].'-'.$tab_date[0];
		
		//return $date_result;
	}
	return $date_result;
}

/**********Params***************************************/
include('params.php');

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

/*********Vérifie si id_registre est vide ou null******************/
if(($id_registre=='')||($id_registre==null)||($id_registre==0))
{
	
	echo "alert('id_registre vide. Veuillez encoder un n° de registre pour l\'agent en cours');";
	exit;
}

/********Vérifie s'il existe déjà un id_registre identique*******************************************/
include('../connect_db.php');


$sql="
		select id_agent,registre_id from cpas_agents where registre_id='".$id_registre."';
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==1)
{
	echo "alert('Cet id_registre existe déjà dans la table. Veuillez encoder un autre n° de registre pour l\'agent en cours');";
	exit;
}
//mysqli_close($lien);


/**********agent**************************/
//include('../connect_db.php');


$sql="
		insert into cpas_agents (id_agent,registre_id,nom,prenom,initiales,genre,langue,creation_date,creation_user) 
		values ('','".$id_registre."','".addslashes($nom)."','".addslashes($prenom)."','".$initiales."','".$genre."','".$langue."',NOW(),'".$session_username."');
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

//mysqli_close($lien);


/***************Sélectionner l'id_agent*********************************/
//include('../connect_db.php');


$sql="
		select id_agent,registre_id from cpas_agents where registre_id=".$id_registre.";
";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);


//mysqli_close($lien);

$tab_agents_id=mysqli_fetch_assoc($result);
//var_dump($tab_agents_id);

$id_agent_new=$tab_agents_id['id_agent'];
/***********signalétique*********************************/

//include('../connect_db.php');


$sql="
		insert into cpas_signaletiques_agents (id_sign_agent,id_agent,niss,nationalite,date_naissance,id_civilite) 
		values ('','".$id_agent_new."','".$niss."','".addslashes($nationalite)."','".transformDate($date_naissance)."','".$id_civilite."');	
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

mysqli_close($lien);

//echo '<javascript>';

$message="Ajout de l\'agent :".addslashes($nom).' '.addslashes($prenom);
echo "alert('".$message."');";
echo "
var MyForm=document.getElementById('FORM_INFO_GN');
MyForm.elements['bnt_sauver'].disabled=true;
MyForm.elements['bnt_sauver'].style.background='';
";
echo 'ReloadFormAgent('.$id_agent_new.',\''.$nom_champ.'\',\''.$valeur_champ.'\');';
exit;

?>