<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
session_start();

include('php/params.php');


if(($session_username!="")||($session_username!='undefined'))
{
	
	$_SESSION['session_username']=$session_username;
	$_SESSION['session_langue']=$session_langue;
	header('Location: ./organigramme.php');
	
	exit;
}
else
{
	
	if(($session_username=="")||($session_username=='undefined'))
	{
	//echo "alert('Erreur!');";
		echo "
			alert('Utilisateur inconnu! Veuillez vous connecter à l'Intranet.')
		";
		header('Location: http://intranet.cpasixelles.be/coquille_intranet/index4.php');
		exit;
	}
	
	
	
	
	
}

//header('Location: ../index.php');

?>