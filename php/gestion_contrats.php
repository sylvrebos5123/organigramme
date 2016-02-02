<?php
//ob_clean();

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

 //function de traduction
include_once('../tools/function_dico.php');

?>
<style>

.modif_contrat
{
	background:#ddd url('./images/white-highlight.png') top left repeat-x;
	border-bottom:1px solid #777;
	height:25px;
	padding-top:5px;
	cursor:default;
}

.modif_contrat:hover
{
	background-color:#fff;
}
</style>


<?php echo '<h1>Gestion contrat(s) de '.$nom.' '.$prenom.'</h1><br>';?>

<form id="FORM_TYPE_SER" name="FORM_TYPE_SER">
	<input type="hidden" id="nom_champ" name="nom_champ" value="<?php echo $nom_champ;?>"/>
	<input type="hidden" id="valeur_champ" name="valeur_champ" value="<?php echo $valeur_champ;?>"/>
</form>

<div id="DIV_LIST_CONTRATS" style="height:80px;overflow:auto;"> 

<!--
Affichage de la liste des contrats pour l'agent en cours
-->

</div>

<input type="button" value="Ajouter un contrat" onclick="DisplayFormContrat(0,<?php echo $id_agent;?>);" /><br><br>

<div id="FORM_CONTRAT">

<!--
Affichage formulaire initialisation contrat
-->

</div>


<div id="HISTO_MVT">
	
	<!--
	Mouvements de carrière
	-->
	
</div>
