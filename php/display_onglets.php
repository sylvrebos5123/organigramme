<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

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


if($id_agent==0)
{
	
	$id_registre="";
	$nom="";
	$prenom="";
	$initiales="";
	$langue="F";
	$genre=1;
	$niss="";
	$date_naissance="00-00-0000";
	$nationalite="";
	$id_civilite="";
	$niveau_etudes='';
	$libelle_diplome='';
	$id_selor='';
	$zone_libre_selor="";
	$prime_linguistique='';
	$tel_prive="";

}
else
{
	include('../connect_db.php');

	$sql="select * from cpas_agents 
	left join cpas_signaletiques_agents
	on cpas_agents.id_agent=cpas_signaletiques_agents.id_agent
	where cpas_agents.id_agent='".$id_agent."';
	";
	//var_dump( $sql);

	$result=mysqli_query($lien, $sql);

	//var_dump($result);
	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_agents=mysqli_fetch_assoc($result);
	/**********/
	
	$id_registre=$tab_agents['registre_id'];
	$nom=$tab_agents['nom'];
	$prenom=$tab_agents['prenom'];
	$initiales=$tab_agents['initiales'];
	$langue=$tab_agents['langue'];
	$genre=$tab_agents['genre'];
	$niss=$tab_agents['niss'];
	$date_naissance=transformDate($tab_agents['date_naissance']);
	$nationalite=$tab_agents['nationalite'];
	$id_civilite=$tab_agents['id_civilite'];
	$niveau_etudes=$tab_agents['niveau_etudes'];
	$libelle_diplome=$tab_agents['libelle_diplome'];
	$id_selor=$tab_agents['id_selor'];
	$zone_libre_selor=$tab_agents['zone_libre_selor'];
	$prime_linguistique=$tab_agents['prime_linguistique'];
	$tel_prive=$tab_agents['tel_prive'];
	
}

 //function de traduction
include_once('../tools/function_dico.php');

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

/***************Fonction qui met le résultat des records dans un array*********************************************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	$tableau=null;
	
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



?>

<!-- Bouton fermeture de la modalbox-->
<div align="right">
	<div id="DIV_CLOSE_PANNEL" onmouseover="DisplayTitleClose();" onmouseout="document.getElementById('box_close').style.visibility='hidden';" style="padding-top:2px;margin-right:10px;margin-top:10px;cursor:default;font-size:15pt;font-family:Verdana;font-weight:bold;color:white;height:25px;width:27px;text-align:center;background-color:#3d3d3d;border:2px solid #ccc;" onclick="CloseToLeft('modal_externe',100,0);">
	X<div id="box_close" style="visibility:hidden;z-index:500;font-size:10pt;"><div class="head">box</div></div>
	</div>
	
</div>

<!-- Onglets-->
<div class="systeme_onglets">
        <div class="onglets">
		<?php
		if($id_agent==0)
		{
		?>
			<span class="onglet_1 onglet" id="onglet_infogenerale" >Informations générales</span>
			<span class="onglet_0 onglet" id="onglet_contrat_sup" onclick="alert('Vous devez d\'abord enregistrer les informations générales');">Contrat(s)</span>
			<span class="onglet_0 onglet" id="onglet_diplome" onclick="alert('Vous devez d\'abord enregistrer les informations générales');">Diplômes</span>
			<span class="onglet_0 onglet" id="onglet_domicile" onclick="alert('Vous devez d\'abord enregistrer les informations générales');">Adresse & tél.</span>
			<span class="onglet_0 onglet" id="onglet_prime" onclick="alert('Vous devez d\'abord enregistrer les informations générales');">Primes, anciennetés, fin de carrière,...</span>
      
		<?php    
		}
		else
		{
		?>
            <span class="onglet_1 onglet" id="onglet_infogenerale" onclick="javascript:change_onglet('infogenerale','contrat_sup','diplome','domicile','prime');">Informations générales</span>
            
			<span class="onglet_0 onglet" id="onglet_contrat_sup" onclick="javascript:change_onglet('contrat_sup','infogenerale','diplome','domicile','prime');">Contrat(s) </span>
			<span class="onglet_0 onglet" id="onglet_diplome" onclick="javascript:change_onglet('diplome','infogenerale','contrat_sup','domicile','prime');">Diplômes</span>
			<span class="onglet_0 onglet" id="onglet_domicile" onclick="javascript:change_onglet('domicile','infogenerale','contrat_sup','diplome','prime');">Adresse & tél.</span>
			<span class="onglet_0 onglet" id="onglet_prime" onclick="javascript:change_onglet('prime','infogenerale','contrat_sup','diplome','domicile');">Primes, anciennetés, fin de carrière,...</span>
			
		<?php    
		}
		?>
		</div>
        <div class="contenu_onglets">
            <div class="contenu_onglet" id="contenu_onglet_infogenerale" style="display:block;" > 
                <?php include('display_form_info_generales.php'); ?>
            </div>
           
			
			<div class="contenu_onglet" id="contenu_onglet_contrat_sup" > 
                <?php 
				
				include('gestion_contrats.php'); 
				?>
				
            </div>

			
			<div class="contenu_onglet" id="contenu_onglet_diplome" > 
               <?php include('display_form_diplomes.php'); ?>
            </div>
			<div class="contenu_onglet" id="contenu_onglet_domicile" > 
                <?php include('display_form_adresse_tel.php'); ?>
            </div>
			<div class="contenu_onglet" id="contenu_onglet_prime" > 
                
				<?php
				
				include('display_complements_fin_carriere.php');
				
				include('display_form_anciennetes.php');
				
				
				//Gestion des primes (liste + formulaire ajout/modif)
				include('gestion_primes.php');
				?>
        </div>
    </div>
	

   