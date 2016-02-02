<?php
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
session_start();

/*************Vérification de l'utilisateur***********************************/
if(($_SESSION['session_username']=="")||($_SESSION['session_username']=='undefined'))
{

	header('Location: http://testweb.cpasixelles.be/intranet_v2/user/login');
	exit;
}
// include de la langue
include('./includes/php_linguistique.php');
include('./exchange/array_lbl_cal.php'); //tableaux et fonction pour les libellés de calendrier / langue

/********* Chargement des stored procedures permettant la mise à jour automatique des contrats et listing libellés************************/
include('load_stored_procedures.php');
/**********PARAMS******************/
include('php/params.php');



/***************Libellés******************************/
$libelle=array();



$libelle['afficher_calendrier']['F']="Cliquer pour afficher le calendrier";
$libelle['afficher_calendrier']['N']="Klikken om het tijdschema aan te geven";

$libelle['modifier']['F']="Modifier";
$libelle['modifier']['N']="Wijzigen";

$libelle['ajouter']['F']="Ajouter";
$libelle['ajouter']['N']="Toevoegen";

$libelle['heures']['F']="Heures";
$libelle['heures']['N']="Uren";

$libelle['minutes']['F']="Minutes";
$libelle['minutes']['N']="Minuten";

$libelle['fermer']['F']="Fermer";
$libelle['fermer']['N']="Sluiten";



 /******Fonction qui permet de retourner un array contenant l'ensemble des records d'une table**************************************/

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




ob_clean();
header('Content-Type: text/html; charset=utf-8');


?>

<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="author" content="Sylvie Vrebos" />
	<title>Organigramme CPAS</title>
	<link rel="stylesheet" type="text/css" media="screen, print" href="slickmap.css" />
	<link rel='stylesheet' type='text/css' href='./css/css_onglets.css' />
		
	<link rel='stylesheet' type='text/css' href='./css/jquery.ui.timepicker.css' />
	<link rel="stylesheet" href="./css/my_style.css" type="text/css" media="screen" title="no title" charset="utf-8" />
	
	<link rel="stylesheet" type="text/css" href="./css/smoothness/jquery-ui-1.8.21.custom.css"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

 
	<script language="javascript" type="text/javascript" src="./javascript/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/javascript" src="./js/jquery.easing.1.3.js"></script>	
	<script language="javascript" type="text/javascript" src="./javascript/jquery-1.4.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="./javascript/jquery-ui-1.8.21.custom.min.js"></script>
	<script language="javascript" type="text/javascript" src="./javascript/jquery.ui.timepicker.js"></script>
	<script language="javascript" type="text/javascript" src="./javascript/general_script.js"></script>
	<script language="javascript" type="text/javascript" src="./javascript/base64_script.js"></script>
	<!--<script language="javascript" type="text/javascript" src="./js/fhttprequest_pba.js"></script>-->
	
	
<script type="text/javascript">

var bw = new checkBrowser();
if (!document.getElementById)
 document.getElementById = getObjectById;

String.prototype.trim = function() {return this.replace(/^([\s\t\n]|\&nbsp\;)+|([\s\t\n]|\&nbsp\;)+$/g, '');}


</script>
</head>
<body>
<div id="page_modal" style=" filter: Alpha(Opacity=70); -moz-opacity:0.7; opacity: 0.7;display:none;position:fixed;width:100%;height:100%;background-color:#999999;top:0px;left:0px;z-index:400;"></div>
<div id="modal_externe" style="margin-left:-19px;position:absolute;top:100px;width:100%;display:none;z-index:400;">


	<div id="modal_interne" style="background-color:white;padding-left:10px;padding-bottom:10px;border:1px solid #999999;width:735px;margin-left:auto;margin-right:auto;z-index:400;">
			
		<div id='NEW'>
		
		<!--
			ICI s'affiche les différents formulaires dans le modalbox
		-->	
		
		</div>
		
	</div>
	
</div>


<?php
include('arrays_libelle/array_departement.php');

?>
<div style="margin-left:600px;margin-top:-20px;position:absolute;z-index:390;">
	<div class="form">
		<form id="FORM_SEARCH_AGENT" name="FORM_SEARCH_AGENT">
				<div style="float:left;">
					
					<input type="search" id="nom_agent" name="nom_agent" placeholder="Rechercher un agent" size="50" />
				
				</div>	
					
				<div class="bnt_ajout_agent" title="Ajout d'un agent" onclick="LoadFormAgent(0,'',0);"></div>
				
		</form>
	</div>	<br><br>
</div>
<div>
<img src="images/logo.gif" align="middle"/> 

<a style="padding:5px;text-decoration:none;" class="lien_menu" href="fullcalendar-1.5.3/calendrier_echeances.php" target="_blank">Calendrier des échéances</a>

<span style="padding:5px;" class="lien_menu" onclick="DisplayEncodageCadre();">Encodage du cadre</span>
<a style="padding:5px;text-decoration:none;" class="lien_menu" href="php/gestion_cadre_effectifs.php" target="_blank">Gestion cadre/effectifs</a>
<span style="padding:5px;" class="lien_menu" onclick="LoadListDocument();">Exportation de fichiers</span>
<a style="padding:5px;text-decoration:none;background-color:#C92E2F;color:white;" class="lien_menu" href="pdf/manuel_utilisation.pdf" title="Ouvrir le manuel au format pdf" target="_blank">Manuel d'utilisation</a>
<a style="padding:5px;" class="lien_menu" href="pdf/manuel_technique.pdf" title="Manuel service informatique" target="_blank"><img src="images/icon_help.png" height="40px"/> Helpdesk</a>
</div>
<br><br>
		<h2>Organigramme CPAS Ixelles</h2><br>
<div>

<div id="DIV_LIST_GRAPH">
<!--Lecture de l'organigramme-->
</div>




<div id="modalbox" style="width:0px;height:0px;visibility:hidden;display:none;z-index:400;"></div>



<div id="WaitingZone" style="filter: Alpha(Opacity=60); -moz-opacity:0.6; opacity: 0.6;display:none;position:absolute; background-color:#999999;width:100%;height:100%;padding-top:200px;top:0px;left:0px;z-index:2000;"> <!--left:10%;top:25%;-->
<center>
<br/>
<br/>
<br/>
<span style="color:white;font-size:18pt;font-weight:bold;">Chargement en cours...</span><br/>
<img src="images/253-1.GIF" />
</center>
</div>	<br/>



</body>
</html>

<script>
<?php
/***********Equivalent temps plein**************************************/
include('arrays_libelle/array_equivalent_temps_plein.php');

// Déclaration du tableau JavaScript
$tableau_javacript="Tableau_equiv_tp";

printf("var %s = new Array();\n",$tableau_javacript);

foreach($array_equivalent_temps_plein as $key1 => $value1)
{
	printf("%s[%s]= '%s';\n",$tableau_javacript, $key1, is_string($value1) ?  "$value1" : $value1);
	
	printf("var nb_elements= %s;\n",$key1);
}


/***********Agents**************************************/


// Déclaration du tableau JavaScript
$tableau_javacript="Tableau_agents";

include('connect_db.php');


$sql="
SELECT id_agent,nom,prenom 
from 
cpas_agents

order by nom asc;

";
//var_dump( $sql);
$result=mysqli_query($lien, $sql);
//var_dump( $result);

$tab_agents=fn_ResultToArray($result,'id_agent');
mysqli_close($lien);


printf("var %s = [ \n",$tableau_javacript);


foreach($tab_agents as $key1 => $value1)
{
	$nom_agent=addslashes($value1['nom']).' '.addslashes($value1['prenom']);
	$id_agent=$value1['id_agent'];
	printf("{label: '%s', value: '%s'},\n",$nom_agent, $id_agent, is_string($nom_agent) ?  "$nom_agent" : $nom_agent);
	
}
	printf("{label: '---', value: 0}\n");
	printf("];");
?>


function Apparait(DivId)
{
	var MyDiv=document.getElementById(DivId);
	MyDiv.style.display="block";
	
}

function Disparait(DivId)
{
	var MyDiv=document.getElementById(DivId);
	MyDiv.style.display="none";
}

/**********Fonction AJAX asynchrone******************************/
function myHttpRequest2(url,TargetId)
{
	//alert(TargetId);
	var t=document.getElementById(TargetId);
	var w=document.getElementById("WaitingZone");
	//alert(t+"\n"+w);
	var currentDate = new Date();
	//var session_username="<?php echo $session_username;?>";
	var session_username="<?php echo $_SESSION['session_username'];?>";
	var timeStamp = currentDate.getTime();
	var xhr = null;
	//var SyncroFlag=true;
	
	w.style.display='block';
	if(window.XMLHttpRequest) // Firefox
	   xhr = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
	   xhr = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non supporté par le navigateur
	   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	   //return;
	}
	
		xhr.open("GET", url+"session_username="+session_username+"&ts="+timeStamp, true);
		//alert(xhr);
		//xhr.open(MethodPost, ""+Url+"?"+Param+"&ts="+timeStamp+"", SyncroFlag); 
		xhr.send(null);
	
		xhr.onreadystatechange = function() {
			//alert(xhr.readyState);
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				//if(w!=null)
				//{
					w.style.display='none';
				//}
				//alert("t ="+t);
				//if(t!=null)
				//{
					var resp=xhr.responseText;
					//alert('resp ='+resp);
					var contenu = resp.split('<javascript>');
					//alert(contenu);
					var html=contenu[0];
					var jscp=contenu[1];
					//alert(html);
				if(t!=null)
				{
					if(html!=null)
					{
						t.innerHTML=html;
					}
				}
				    if ((jscp!=null) && (jscp!= ''))
					{
					 //alert(jscp);
						  eval(jscp);
					}
			}
		};		
	
}

/**********Fonction AJAX synchrone******************************/
function myHttpRequest(url)
{

	var xhr_object = null;

	if(window.XMLHttpRequest) // Firefox
	   xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non supporté par le navigateur
	   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	   
	}
	
	var currentDate=new Date();
	//var session_username="<?php echo $session_username;?>";
	var session_username="<?php echo $_SESSION['session_username'];?>";
	var timeStamp = currentDate.getTime();
	xhr_object.open("GET", url+"session_username="+session_username+"&ts="+timeStamp, false);
	xhr_object.send(null);
	
	if(xhr_object.readyState == 4)
	{
	
	
	 var tableau=xhr_object.responseText.split('<javascript>');
	 var responseHtml = tableau[0];
	 if(tableau.length!=1)
	 
	 {
		 var responseScript = tableau[1];
		 
		 eval(responseScript);
		 
	 }
	//alert(responseHtml);	
	 return responseHtml;
	}
} 


// effet animation
function CloseToLeft(DivId,Start,End)
{
	var MyDiv=document.getElementById(DivId);	
	MyDiv.style.width=Start+"%";
	Start=Start-10;
	if(Start>End){
		setTimeout("CloseToLeft('"+DivId+"',"+Start+","+End+");",50);
		
	}else{
		Disparait(DivId);
		Disparait("page_modal");	
	}
}
// effet animation
function MoveFromLeft(DivId,Start,End)
{
	var MyDiv=document.getElementById(DivId);	
	MyDiv.style.width=Start+"%";
	Start=Start+10;
	if(Start<=End){
		setTimeout("MoveFromLeft('"+DivId+"',"+Start+","+End+");",30);
	}
}

/*******Etiquette pour le bouton de fermeture de la modalbox********************************************************************/
function DisplayTitleClose()
{
	var start_left = Math.round($('#DIV_CLOSE_PANNEL').offset().left - 100);
	var start_top = 70;
	
	var new_left = Math.round($('#DIV_CLOSE_PANNEL').offset().left - 120);
	var new_top = 50;
	
	$('#box_close').css({top:start_top,left:start_left});
	$('#box_close').animate({left: new_left,top:new_top},{duration:500});
	$('#box_close .head').html('Fermer la fenêtre');
	$('#box_close').css({visibility:'visible'});	
}


function DisplayListContrats(id_agent)
{
	var MyObjet = document.getElementById("DIV_LIST_CONTRATS");
	
	var HttpResponse = myHttpRequest('php/display_list_contrats.php?id_agent='+id_agent+'&');
	
	//console.log(HttpResponse);
	MyObjet.innerHTML=HttpResponse;
	
}

/********Ajout/modification d'un service *****************************************************/
function LoadFormService(id_ser,id_dep)
{	
	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
		
	var HttpResponse = myHttpRequest('php/display_form_service.php?id_ser='+id_ser+'&id_dep='+id_dep+'&');
	MyObjet.innerHTML = HttpResponse;
	
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100); 
		
}
/********Ajout/modification d'une cellule*****************************************************/
function LoadFormCellule(id_cel,id_ser,id_dep)
{
	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
		
	var HttpResponse = myHttpRequest('php/display_form_cellule.php?id_cel='+id_cel+'&id_ser='+id_ser+'&id_dep='+id_dep+'&');
	MyObjet.innerHTML = HttpResponse;
	
	
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100); 

}
/********Ajout/modification d'un département*****************************************************/
function LoadFormDepartement(id_dep)
{
	
	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
		
	var HttpResponse = myHttpRequest('php/display_form_departement.php?id_dep='+id_dep+'&');
	MyObjet.innerHTML = HttpResponse;
	
	
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100); 	
}

/********Ajout/modification d'un service hors département*****************************************************/
function LoadFormHorsDepartement(id_hors_dep)
{
	
	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
		
	var HttpResponse = myHttpRequest('php/display_form_hors_departement.php?id_hors_dep='+id_hors_dep+'&');
	MyObjet.innerHTML = HttpResponse;
	
	
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100); 
		
}
/********Suppression d'un département*****************************************************/
function SupprDepartement(id_dep)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer ce département ainsi que tous les services contenus à l\'intérieur?'))
	{
		myHttpRequest('php/suppr_departement.php?id_dep='+id_dep+'&');
	
	}
}
/********Suppression d'un service *****************************************************/
function SupprService(id_ser)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?'))
	{
		myHttpRequest('php/suppr_service.php?id_ser='+id_ser+'&');
	
	}
}
/********Suppression d'une cellule*****************************************************/
function SupprCellule(id_cel)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer cette cellule ?'))
	{	
		myHttpRequest('php/suppr_cellule.php?id_cel='+id_cel+'&');
		
	}
}
/********Suppression d'un service hors département*****************************************************/
function SupprHorsDepartement(id_hors_dep)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer ce service hors-département?'))
	{
		myHttpRequest('php/suppr_hors_departement.php?id_hors_dep='+id_hors_dep+'&');
	}
}

/********Suppression d'un mouvement*****************************************************/
function SupprMvt(type_mvt,id_mvt,id_agent,id_contrat)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer ce mouvement?'))
	{
		myHttpRequest('php/suppr_mvt_'+type_mvt+'.php?id_mvt_'+type_mvt+'='+id_mvt+'&id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
	}
}

/********Suppression d'une place au cadre*****************************************************/
function SupprPlaceCadre(id_place_cadre,id_cadre,type_cadre)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer cette place au cadre?'))
	{
		myHttpRequest('php/suppr_place_cadre.php?id_place_cadre='+id_place_cadre+'&id_cadre='+id_cadre+'&type_cadre='+type_cadre+'&');
	
	}
}

/********Suppression d'une prime/allocation*****************************************************/
function SupprPrime(id_prime,id_agent)
{
	if (confirm('Êtes-vous sûr de vouloir supprimer ce type de prime?'))
	{
		myHttpRequest('php/suppr_prime.php?id_prime='+id_prime+'&id_agent='+id_agent+'&');
	}
}

/***********Ouverture de la fiche des agents avec onglets*************************************/
function LoadFormAgent(id_agent,nom_champ,valeur_champ)
{
	
	Apparait("page_modal");

	
	var MyObjet = document.getElementById("NEW");
	
	
	var HttpResponse = myHttpRequest('php/display_onglets.php?id_agent='+id_agent+'&nom_champ='+nom_champ+'&valeur_champ='+valeur_champ+'&');
	MyObjet.innerHTML = HttpResponse;
	
	
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100); 
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear()-14;
	
	$( "#date_naissance" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	//liste contrats
	var MyObjet = document.getElementById("DIV_LIST_CONTRATS");
	
	var HttpResponse = myHttpRequest('php/display_list_contrats.php?id_agent='+id_agent+'&');
	
	MyObjet.innerHTML=HttpResponse;
	
	
	// mvt de domicile
	var MyObjet = document.getElementById("DIV_LIST_MVT_DOMICILE");
	
	var HttpResponse = myHttpRequest('php/display_mvt_domiciles.php?id_agent='+id_agent+'&');
	
	MyObjet.innerHTML = HttpResponse;
	
	//liste des primes
	var MyObjet = document.getElementById("DIV_LIST_PRIME");
	
	var HttpResponse = myHttpRequest('php/display_list_primes.php?id_agent='+id_agent+'&');
	
	MyObjet.innerHTML = HttpResponse;
	
		
}

/*******Déplacement d'un ou plusieurs agents vers un autre service*******************************************************/
function DeplacerListAgent(nom_champ,valeur_champ)
{
	
	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
	
	var HttpResponse = myHttpRequest('php/display_agents_to_remove.php?nom_champ='+nom_champ+'&valeur_champ='+valeur_champ+'&');
	//MyObjet.innerHTML = id_agent;
	
	MyObjet.innerHTML = HttpResponse;
	
	var now = new Date();
	var year_min  = now.getFullYear();
	var year_max  = now.getFullYear()+20;
	
	$( "#date_debut_service" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_echeance_service" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
    
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100);
	
}

/**********Après enregistrement de l'onglet infos générales**************************************************/
function ReloadFormAgent(id_agent,nom_champ,valeur_champ)
{
	var MyObjet = document.getElementById("NEW");
	
	var HttpResponse = myHttpRequest('php/display_onglets.php?id_agent='+id_agent+'&nom_champ='+nom_champ+'&valeur_champ='+valeur_champ+'&');
	
	MyObjet.innerHTML = HttpResponse;
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear()-14;
	
	$( "#date_naissance" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	//liste contrats
	DisplayListContrats(id_agent);
	
	// mvt de domicile
	var MyObjet = document.getElementById("DIV_LIST_MVT_DOMICILE");
	
	var HttpResponse = myHttpRequest('php/display_mvt_domiciles.php?id_agent='+id_agent+'&');
	
	MyObjet.innerHTML = HttpResponse;
	
	//liste des primes
	var MyObjet = document.getElementById("DIV_LIST_PRIME");
	
	var HttpResponse = myHttpRequest('php/display_list_primes.php?id_agent='+id_agent+'&');
	
	MyObjet.innerHTML = HttpResponse;
	
}

function DisplayListPrimes(id_agent)
{
	//liste des primes
	var MyObjet = document.getElementById("DIV_LIST_PRIME");
	
	var HttpResponse = myHttpRequest('php/display_list_primes.php?id_agent='+id_agent+'&');
	
	MyObjet.innerHTML = HttpResponse;
}

/*

function DisplayListDep(lg,modif_user)
{
	var MyObjet = document.getElementById("DIV_LIST_DEP");
	var HttpResponse = myHttpRequest('php/liste_departements.php?lg='+lg+'&modif_user='+modif_user+'&url=gestion_drh/profil_departement_drh.php&lg=F&modif_user=vrebos.sylvie&css=profil_departement_drh.css&');
	MyObjet.innerHTML = HttpResponse;
}

function LoadDepartements()
{
	Apparait("page_modal");

	var MyObjet = document.getElementById("NEW");
	MyObjet.style.height='510px';
	var HttpResponse = myHttpRequest('gestion_drh/gestion_departements.php?');
	
	MyObjet.innerHTML = HttpResponse;
	
	DisplayListDep('F','vrebos.sylvie');
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100);
}

function DisplayListHorsDep(lg,modif_user)
{
	var MyObjet = document.getElementById("DIV_LIST_HORS_DEP");
	var HttpResponse = myHttpRequest('php/liste_hors_departements.php?lg='+lg+'&modif_user='+modif_user+'&url=gestion_drh/profil_hors_departement_drh.php&lg=F&modif_user=vrebos.sylvie&css=profil_hors_departement_drh.css&');
	MyObjet.innerHTML = HttpResponse;
}

function LoadHorsDepartements()
{
	Apparait("page_modal");

	var MyObjet = document.getElementById("NEW");
	MyObjet.style.height='510px';
	var HttpResponse = myHttpRequest('gestion_drh/gestion_hors_departements.php?');
	
	MyObjet.innerHTML = HttpResponse;
	
	DisplayListHorsDep('F','vrebos.sylvie');
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100);
}


function DisplayListSer(lg,modif_user)
{
	var MyObjet = document.getElementById("DIV_LIST_SER");
	var HttpResponse = myHttpRequest('php/liste_services.php?lg='+lg+'&modif_user='+modif_user+'&url=gestion_drh/profil_service_drh.php&lg='+lg+'&modif_user='+modif_user+'&css=profil_service_drh.css&');
	MyObjet.innerHTML = HttpResponse;
}



function LoadServices()
{
	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
	MyObjet.style.height='510px';
	var HttpResponse = myHttpRequest('gestion_drh/gestion_services.php?');
	
	MyObjet.innerHTML = HttpResponse;
	
}

*/

function change_onglet(name,other1,other2,other3,other4)
{	
	document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
	
	if(document.getElementById('onglet_'+name).className == 'onglet_1 onglet')
		document.getElementById('contenu_onglet_'+name).style.display = 'block';
	
	document.getElementById('onglet_'+other1).className = 'onglet_0 onglet';
	document.getElementById('contenu_onglet_'+other1).style.display = 'none';
	document.getElementById('onglet_'+other2).className = 'onglet_0 onglet';
	document.getElementById('contenu_onglet_'+other2).style.display = 'none';
	document.getElementById('onglet_'+other3).className = 'onglet_0 onglet';
	document.getElementById('contenu_onglet_'+other3).style.display = 'none';
	document.getElementById('onglet_'+other4).className = 'onglet_0 onglet';
	document.getElementById('contenu_onglet_'+other4).style.display = 'none';

}

function Corriger(idForm)
{
	var MyForm=document.getElementById(idForm);
	var nbrElements = MyForm.length;
	for(var x=0;x<nbrElements;x++)
	{
		var MyElement = MyForm.elements[x];
		var MyElementName = MyElement.name;
		
		document.getElementById(MyElementName).disabled=false;
	}
	
	document.getElementById('bnt_sauver').style.visibility="visible";
	document.getElementById('bnt_reset').style.visibility="visible";
}


function ApparaitreAutreFonction(idForm,input_fonction)
{
	var MyForm=document.getElementById(idForm);

	if(MyForm.elements[input_fonction].value==1000)
	{
		
		MyForm.elements['autre_type_fonc'].style.visibility="visible";
		MyForm.elements['autre_type_fonc'].value='Précisez la fonction';
		MyForm.elements['autre_type_fonc'].select();
	}
	else
	{
		
		MyForm.elements['autre_type_fonc'].style.visibility="hidden";
		MyForm.elements['autre_type_fonc'].value='';
	}
}


/**********Permet de mettre en paramètres tous les champs du formulaire + l'action****************************************************/
function fFormSubmit(idForm)
{	
	var MyForm=document.getElementById(idForm);

	var nbrElements = MyForm.length;
	var MyFormAction = MyForm.action;
	var msg=MyForm.action+"?";
	
	for(var x=0;x<nbrElements;x++)
	{
		var MyElement = MyForm.elements[x];
		var MyElementName = MyElement.name;
		var MyElementValue = MyElement.value;
		
		if( MyElementName!="")
		{
			if((MyElement.type=="radio")|| (MyElement.type=="checkbox"))
			{
				var MyElementChecked = MyElement.checked;
				if(MyElementChecked==true)
				{
					msg+=MyElementName+"="+encodeURIComponent(MyElementValue)+"&";
				}
			}else{
				msg+=MyElementName+"="+encodeURIComponent(MyElementValue)+"&";
			}
		}
	}
	

	var HttpResponse = myHttpRequest(msg);
	

}

function resetFields(id_form)
{
	var MyForm = document.getElementById(id_form);
	MyForm.reset();
	
	MyForm.elements["bnt_sauver"].disabled=true;
	MyForm.elements["bnt_sauver"].style.background="";
}

function SetDisabledButton(id_form)
{
	var MyForm=document.getElementById(id_form);
	
	MyForm.elements["bnt_sauver"].disabled=true;
	MyForm.elements["bnt_sauver"].style.background="";
	
}

function SetColorButton(id_form)
{
	var MyForm=document.getElementById(id_form);
	
	if(MyForm.elements["bnt_sauver"].disabled==true)
	{
		MyForm.elements["bnt_sauver"].disabled=false;
		MyForm.elements["bnt_sauver"].style.background="#B0F276";
	}
}

function SetValue(id_input,value_input,id_form)
{
	var MyForm=document.getElementById(id_form);
	
	MyForm.elements[id_input].value=value_input;	
	SetColorButton(id_form);
}

/******Permet de mettre automatiquement les valeurs du grade, barème et code (côté contrat) aux niveau des champs pour le cadre
   ****Voir gestion contrat/mouvement de barème************************************************/
function SetValueCadre(id_input,id_form)
{
	var MyForm=document.getElementById(id_form);
	
	//MyForm.elements[id_input].value=value_input;	
	MyForm.elements[id_input+'_cadre'].value=MyForm.elements[id_input].value;
	SetColorButton(id_form);
}

/*****Met automatiquement le coefficient de l'équivalence temps après sélection du régime*****************************************************/
function SetValueEquivTP(id_form)
{
	var MyForm=document.getElementById(id_form);
	
	var id_regime=MyForm.elements['id_regime'].value;

	MyForm.elements['id_equiv_tp'].value=Tableau_equiv_tp[id_regime];
		
}


/****************************************************************************/
function DisplayMvt(name_div,type_form,id_agent,id_contrat)
{
	
	var MyObjet = document.getElementById(name_div);
	var HttpResponse='';
	if(type_form != "domiciles")
	{
		var MyBnt = document.getElementById("bnt_"+type_form);
		
		if(MyBnt.className == 'bnt_open_list')
		{
			HttpResponse = myHttpRequest('php/display_mvt_'+type_form+'.php?id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
			
			MyObjet.innerHTML = HttpResponse;
			
			MyBnt.className = 'bnt_close_list';
		}
		else
		{
			MyObjet.innerHTML = '';
			MyBnt.className = 'bnt_open_list';
		}
	}
	else
	{
		HttpResponse = myHttpRequest('php/display_mvt_'+type_form+'.php?id_agent='+id_agent+'&');
			
		MyObjet.innerHTML = HttpResponse;
	}
} 



/**********Affichage des différents types de mouvements après création d'un nouveau contrat*******************************************************************************/
function DisplayFormContratModif(id_contrat,id_agent)
{
	var MyObjet = document.getElementById("FORM_CONTRAT");
	
	var HttpResponse = myHttpRequest('php/display_form_contrat_supplementaire.php?id_contrat='+id_contrat+'&id_agent='+id_agent+'&');
	MyObjet.innerHTML=HttpResponse;
	
	
	var MyObjet = document.getElementById("HISTO_MVT");
	
	var HttpResponse = myHttpRequest('php/display_list_mvt.php?id_contrat='+id_contrat+'&id_agent='+id_agent+'&');
	MyObjet.innerHTML=HttpResponse;
	
	
	//SERVICE
	var MyObjet = document.getElementById('LIST_MVT_SER');
		
	var HttpResponse = myHttpRequest('php/display_mvt_services.php?id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse;  
	
	document.getElementById('bnt_services').className = 'bnt_close_list';
	
	document.getElementById("DIV_FORM_MVT_SER").innerHTML='';
	
	//FONCTION
	 var MyObjet = document.getElementById('LIST_MVT_FCT');
		
	var HttpResponse = myHttpRequest('php/display_mvt_fonctions.php?id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse; 
	
	document.getElementById("DIV_FORM_MVT_FCT").innerHTML='';
	
	
	//BAREME/CODE/GRADE
	var MyObjet = document.getElementById('LIST_MVT_BAREME');
		
	var HttpResponse = myHttpRequest('php/display_mvt_baremes.php?id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse; 
	
	document.getElementById("DIV_FORM_MVT_BAREME").innerHTML='';
	
	
	//STATUT
	var MyObjet = document.getElementById('LIST_MVT_STATUT');
		
	var HttpResponse = myHttpRequest('php/display_mvt_statuts.php?id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse; 
	
	document.getElementById("DIV_FORM_MVT_STATUT").innerHTML='';
	
	//REGIME
	var MyObjet = document.getElementById('LIST_MVT_REGIME');
		
	var HttpResponse = myHttpRequest('php/display_mvt_regimes.php?id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse; 
	
	document.getElementById("DIV_FORM_MVT_REGIME").innerHTML=''; 
	
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear()+1;
	var year_min_end=now.getFullYear()-30;
	
	$( "#start_date" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#end_date" ).datepicker({ defaultDate:'0',yearRange:year_min_end+':'+year_max});
	
}

function DisplayFormContrat(id_contrat,id_agent)
{
	var MyObjet = document.getElementById("FORM_CONTRAT");
	var HttpResponse='';
	
	if(id_contrat==0)
	{
		var nom_champ=document.getElementById("FORM_TYPE_SER").elements['nom_champ'].value;
		var valeur_champ=document.getElementById("FORM_TYPE_SER").elements['valeur_champ'].value;
		
		HttpResponse = myHttpRequest('php/display_form_contrat_supplementaire.php?id_contrat='+id_contrat+'&id_agent='+id_agent+'&nom_champ='+nom_champ+'&valeur_champ='+valeur_champ+'&');
	
	}
	else
	{
		HttpResponse = myHttpRequest('php/display_form_contrat_supplementaire.php?id_contrat='+id_contrat+'&id_agent='+id_agent+'&');
		
	}
	MyObjet.innerHTML=HttpResponse;
	
	document.getElementById("HISTO_MVT").innerHTML='';
	
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear()+1;
	
	$( "#start_date" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#end_date" ).datepicker({ defaultDate:'0'});
	
	$( "#date_naissance" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
}

function FiltreSerCel(id_input,value_input,id_form)
{
	var MyForm = document.getElementById(id_form);
	var MyInput = MyForm.elements[id_input].name;
	
	if(MyInput=='id_ser')
	{
		MyForm.elements['id_cel'].disabled=false;
		var HttpResponse = myHttpRequest('php/display_options_cel.php?id_ser='+value_input+'&');
		
		MyForm.elements['id_cel'].innerHTML = HttpResponse;
		
	}
	else
	{
		if(MyInput=='id_dep')
		{
			MyForm.elements['id_ser'].disabled=false;
			var HttpResponse = myHttpRequest('php/display_options_ser.php?'+MyInput+'='+value_input+'&');
		
			MyForm.elements['id_ser'].innerHTML = HttpResponse;
		
			MyForm.elements['id_cel'].innerHTML ='';
		
		}
	}
}

function DisplayChoixDep(id_form,type_dep)
{
	var MyForm=document.getElementById(id_form);
	
	if(type_dep=="DEP")
	{
		MyForm.elements['choix_groupe'].value="DEP";
		
		document.getElementById("GROUP_DEP").style.visibility='visible';
		document.getElementById("GROUP_DEP").style.height='auto';
		
		document.getElementById("GROUP_HORS_DEP").style.visibility='hidden';
		document.getElementById("GROUP_HORS_DEP").style.height='0px';
	
	}
	else
	{
		if(type_dep=="HORS_DEP")
		{
			MyForm.elements['choix_groupe'].value="HORS_DEP";
			
			document.getElementById("GROUP_DEP").style.visibility='hidden';
			document.getElementById("GROUP_DEP").style.height='0px';
			
			document.getElementById("GROUP_HORS_DEP").style.visibility='visible';
			document.getElementById("GROUP_HORS_DEP").style.height='auto';
	
		}
	
	}

}

/*******Affichage de l'organigramme hiérarchique du CPAS d'Ixelles***********************************************************************/
function DisplayOrganigramme()
{
		
	var HttpResponse = myHttpRequest2('graph.php?',"DIV_LIST_GRAPH");
		
}

/******************************************************************/
function DisplayResponsable(nom_champ,valeur_champ)
{
	var HttpResponse ='';
	var MyObjet = document.getElementById("info_"+nom_champ+"_"+valeur_champ);
	
	HttpResponse = myHttpRequest('display_responsable.php?nom_champ='+nom_champ+'&valeur_champ='+valeur_champ+'&');

	$('#info_'+nom_champ+'_'+valeur_champ).click(function () {
		
			var new_left = Math.round($(this).offset().left - $('#primaryNav li').offset().left);
			var new_top = Math.round($(this).offset().top + 20);
		//Set it to current item position and text
			
				$('#box').css({visibility:'visible'});
				$('#box').animate({left: new_left,top:new_top},{duration:500});
				$('#box .head').html(HttpResponse);
		
	});
	
}
	
/*********Pour afficher/cacher les cellules de l'organigramme hiérachique du CPAS**************************************************/
function DisplayDivCellules(id_ser)
{
	var MyObjet = document.getElementById("CEL_"+id_ser);
	var MyBnt = document.getElementById("display_cel"+id_ser);
	
	if(MyBnt.innerHTML=='Afficher les cellules')
	{
		var HttpResponse = myHttpRequest('display_cellules.php?id_ser='+id_ser+'&');
		MyObjet.style.visibility='visible';
		MyObjet.style.height='auto';
		MyBnt.innerHTML='Cacher les cellules';
		
		MyObjet.innerHTML = HttpResponse;
	}
	else
	{
		MyObjet.style.visibility='hidden';
		MyObjet.style.height='0';
		MyBnt.innerHTML='Afficher les cellules';
		
		MyObjet.innerHTML ='';
	}
	
}

function DisplayBnt(id_ser)
{
	var MyObjet = document.getElementById("bnt_edit_del_"+id_ser+'');
	MyObjet.style.visibility='visible';
}

function HideBnt(id_ser)
{
	var MyObjet = document.getElementById("bnt_edit_del_"+id_ser+'');
	MyObjet.style.visibility='hidden';
}

function Close()
{
	$('#box').css({visibility:'hidden'});
	
}


/*********Modalbox avec la liste des documents qu'on peut exporter***********************************************************/
function LoadListDocument()
{

	Apparait("page_modal");
	
	var MyObjet = document.getElementById("NEW");
	
	var HttpResponse = myHttpRequest('php/list_documents.php?');
	
	
	MyObjet.innerHTML = HttpResponse;
	
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100); 
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear();
	
	$( "#date_situation_effectifs" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_situation_effectifs_tableau" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_debut" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_fin" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_debut_out").datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_fin_out" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
}


/********Génération d'un tableau en Word du personnel du CPAS**************************************************************************************************/
function DisplayTabPersonnel()
{
	var myForm=document.getElementById('FORM_TABLEAU_PERSO');
	var id_dep=myForm.elements['id_dep'].value;
	var date_situation_effectifs_tableau=myForm.elements['date_situation_effectifs_tableau'].value;
	if((id_dep=='')||(id_dep== null)||(id_dep==0))
	{
		// Tous les départements
		myHttpRequest2('php/tableau_personnel_word_all.php?id_dep='+id_dep+'&date_situation_effectifs_tableau='+date_situation_effectifs_tableau+'&','');
		
	}
	else
	{
		// Pour un département précis
		myHttpRequest2('php/tableau_personnel_word2.php?id_dep='+id_dep+'&date_situation_effectifs_tableau='+date_situation_effectifs_tableau+'&','');
		
	}
}



/********Affichage du formulaire d'encodage selon le type de mouvement******************************************************************/
 
function DisplayFormMvt(id_div,type_form,id_mvt,id_agent,id_contrat)
{
	var MyObjet = document.getElementById(id_div);
		
	var HttpResponse = myHttpRequest('php/display_form_mvt_'+type_form+'.php?id_mvt_'+type_form+'='+id_mvt+'&id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse;
		
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear()+10;
	
	
	$( "#date_mvt" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_debut_service" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_service" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	$( "#date_debut_fonction" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_fonction" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	$( "#date_debut_grade" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_grade" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	$( "#date_debut_bareme" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_bareme" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	$( "#date_debut_code" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_code" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	$( "#date_debut_statut" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_statut" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
	$( "#date_debut_regime" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_echeance_regime" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
}

/**********Affichage du formulaire d'jout/modification d'une prime/allocation***********************************************************/
function DisplayFormPrime(id_prime,id_agent,id_type_prime)
{
	var MyObjet = document.getElementById('DIV_FORM_PRIME');
		
	var HttpResponse = myHttpRequest('php/display_form_prime.php?id_prime='+id_prime+'&id_agent='+id_agent+'&id_type_prime='+id_type_prime+'&');
		
	MyObjet.innerHTML = HttpResponse;
	
	if(id_type_prime==5) // alloc fonction supérieur
	{
		document.getElementById('div_input_grade').style.display="block";
		document.getElementById('div_alloc_fonc_sup').style.display="block";
		//document.getElementById('div_prime_comp').style.display="none";
	}
	else
	{
		if(id_type_prime==6) // prime compensatoire 
		{
			//document.getElementById('div_prime_comp').style.display="block";
			document.getElementById('div_input_grade').style.display="none";
			document.getElementById('div_alloc_fonc_sup').style.display="block";
		}
		else
		{
			document.getElementById('div_input_grade').style.display="none";
			document.getElementById('div_alloc_fonc_sup').style.display="none";
			//document.getElementById('div_prime_comp').style.display="none";
		}
	
	}
		
	
	var now = new Date();
	var year_min  = now.getFullYear()-70;
	var year_max  = now.getFullYear()+10;
	
	$( "#date_octroi" ).datepicker({ yearRange:year_min+':'+year_max});
	$( "#date_cloture" ).datepicker({ yearRange:year_min+':'+year_max});
	//$( "#echeance_prime_compensatoire" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_echeance_biennale" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	$( "#date_echeance_code" ).datepicker({ defaultDate:'0',yearRange:year_min+':'+year_max});
	
}

function DisplayOptionsPrime(id_type_prime)
{
	var myForm=document.getElementById('FORM_PRIME');
	var value_prime=myForm.elements['id_type_prime'].value;
	
	if(value_prime==5) // alloc fonction supérieur
	{
		document.getElementById('div_input_grade').style.display="block";
		document.getElementById('div_alloc_fonc_sup').style.display="block";
		//document.getElementById('div_prime_comp').style.display="none";
	}
	else
	{
		if(value_prime==6)// prime compensatoire 
		{
			//document.getElementById('div_prime_comp').style.display="block";
			document.getElementById('div_input_grade').style.display="none";
			document.getElementById('div_alloc_fonc_sup').style.display="block";
		}
		else
		{
			document.getElementById('div_input_grade').style.display="none";
			document.getElementById('div_alloc_fonc_sup').style.display="none";
			//document.getElementById('div_prime_comp').style.display="none";
		}
	
	}
}
	
/**********Génération de la DB de tous les agents présents au CPAS**********************************************************/
	
function GenerateExcel(nom_fichier)
{	
	var myForm=document.getElementById('FORM_SELECT_BD');
	var date_situation_effectifs=myForm.elements['date_situation_effectifs'].value;

	myHttpRequest2('php/generate_bd_personnel.php?nom_fichier='+nom_fichier+'&date_situation_effectifs='+date_situation_effectifs+'&','');
	
}
	
/**********Génération d'une liste des agents engagés à une période définie par l'utilisateur**********************************************************/

function GeneratePersonnelEntrants(nom_fichier)
{
	
	myHttpRequest2('php/generate_personnel_entrants.php?nom_fichier='+nom_fichier+'&','');
	
}
/**********Génération d'une fiche individuelle en Word pour un agent**********************************************************/
function GenerateFicheIndividuelle(id_agent)
{
	
	myHttpRequest2('php/fiche_individuelle_word.php?id_agent='+id_agent+'&','');
	
}


/**********Listing de tous les cadres encodés*******************************/

function DisplayListCadres()
{
	var MyObjet = document.getElementById('LIST_CADRES');
		
	var HttpResponse = myHttpRequest('php/display_list_cadres.php?');
		
	MyObjet.innerHTML = HttpResponse;
		
}

/**************Affichage de la liste des places pour le cadre dirigeant***************************************************************/

function DisplayListCadreDirigeant(id_cadre)
{
	var MyObjet = document.getElementById('LIST_CADRE_DIRIGEANT');
		
	var HttpResponse = myHttpRequest('php/display_list_cadre_dirigeant.php?id_cadre='+id_cadre+'&');
		
	MyObjet.innerHTML = HttpResponse;
		
}

/**************Affichage de la liste des places pour le cadre standard d'après un service sélectionné***************************************************************/

function DisplayListCadreStandard(id_form)
{
		
	var MyForm=document.getElementById(id_form);
	var nbrElements = MyForm.length;
	var MyFormAction = MyForm.action;
	var msg=MyForm.action+"?";
	
	for(var x=0;x<nbrElements;x++)
	{
		var MyElement = MyForm.elements[x];
		var MyElementName = MyElement.name;
		var MyElementValue = MyElement.value;
		
		if( MyElementName!="")
		{
			if((MyElement.type=="radio")|| (MyElement.type=="checkbox"))
			{
				var MyElementChecked = MyElement.checked;
				if(MyElementChecked==true)
				{
					msg+=MyElementName+"="+encodeURIComponent(MyElementValue)+"&";
				}
			}else{
				msg+=MyElementName+"="+encodeURIComponent(MyElementValue)+"&";
			}
		}
	}
	
	document.getElementById('FORM_CADRE_STANDARD').innerHTML="";
	
	var MyObjet = document.getElementById('LIST_CADRE_STANDARD');
	
	var HttpResponse = myHttpRequest(msg);
		
	MyObjet.innerHTML = HttpResponse;
		
}
/**************Permet de passer d'un onglet à l'autre***************************************************************/
function change_onglet_cadre(name,other1)
{	
	document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
	
		document.getElementById('contenu_onglet_'+name).style.display = 'block';
	
	document.getElementById('onglet_'+other1).className = 'onglet_0 onglet';
	document.getElementById('contenu_onglet_'+other1).style.display = 'none';
	
}

/*******Affichage de la modalbox pour l'encodage du cadre*************************************************/
function DisplayEncodageCadre()
{
	
	Apparait("page_modal");
    
	var MyObjet = document.getElementById("NEW");
	
	var HttpResponse = myHttpRequest('php/encodage_cadre.php?');
	
	MyObjet.innerHTML = HttpResponse;
	
	DisplayListCadres();
	Apparait('modal_externe');
	MoveFromLeft('modal_externe',0,100);
}

/*******Affichage des onglets cadre standard et cadre dirigeant*************************************************/
function DisplayOngletsCadre(id_cadre)
{
	var MyObjet = document.getElementById("LIST_ONGLETS");
	
	
	var HttpResponse = myHttpRequest('php/display_onglets_cadre.php?id_cadre='+id_cadre+'&');
	MyObjet.innerHTML = HttpResponse;
}

/******Encodage de la date de la situation du cadre****************************************************************/
function DisplayFormDateCadre(id_cadre)
{

	if(id_cadre==0)
	{
		document.getElementById("LIST_ONGLETS").innerHTML ="";
	}
	else
	{
		DisplayOngletsCadre(id_cadre);
		DisplayListCadreDirigeant(id_cadre);
	}
	
	var MyObjet = document.getElementById("DIV_FORM_DATE_CADRE");
	
	var HttpResponse = myHttpRequest('php/display_form_date_cadre.php?id_cadre='+id_cadre+'&');
	MyObjet.innerHTML = HttpResponse;
	
	var now = new Date();
	var year_min  = now.getFullYear()-50;
	var year_max  = now.getFullYear()+10;
	
	$( "#date_situation" ).datepicker({ yearRange:year_min+':'+year_max});
}


/*******Affichage du formulaire d'ajout/modification d'une place au cadre dirigeant*************************************************/
function DisplayFormCadreDirigeant(id_place_cadre,id_cadre)
{
	var MyObjet = document.getElementById("FORM_CADRE_DIRIGEANT");
	
	var HttpResponse = myHttpRequest('php/display_form_cadre_dirigeant.php?id_place_cadre='+id_place_cadre+'&id_cadre='+id_cadre+'&');
	MyObjet.innerHTML = HttpResponse;
}
	
/*******Affichage du formulaire d'ajout/modification d'une place au cadre standard*************************************************/

function DisplayFormCadreStandard(id_place_cadre,id_cadre,id_hors_dep,id_dep,id_ser)
{
	var MyObjet = document.getElementById("FORM_CADRE_STANDARD");
	
	
	var HttpResponse = myHttpRequest('php/display_form_cadre_standard.php?id_place_cadre='+id_place_cadre+'&id_cadre='+id_cadre+'&id_hors_dep='+id_hors_dep+'&id_dep='+id_dep+'&id_ser='+id_ser+'&');
	MyObjet.innerHTML = HttpResponse;

}


/*************Chargement de l'organigramme et rafraichissement des listing libellés***********************************************************/
DisplayOrganigramme();
myHttpRequest2('./generated_files/generate_departements.php?','');
myHttpRequest2('./generated_files/generate_hors_departements.php?','');
myHttpRequest2('./generated_files/generate_services.php?','');
myHttpRequest2('./generated_files/generate_cellules.php?','');
myHttpRequest2('./generated_files/generate_fonctions.php?','');
myHttpRequest2('./generated_files/generate_grades.php?','');
myHttpRequest2('./generated_files/generate_baremes.php?','');
myHttpRequest2('./generated_files/generate_codes.php?','');
myHttpRequest2('./generated_files/generate_statuts.php?','');
myHttpRequest2('./generated_files/generate_regimes.php?','');
myHttpRequest2('./generated_files/generate_equivalents_temps_plein.php?','');
myHttpRequest2('./generated_files/generate_niveau_etudes.php?','');
myHttpRequest2('./generated_files/generate_selor.php?','');
myHttpRequest2('./generated_files/generate_articles_budgetaires.php?','');
myHttpRequest2('./generated_files/generate_types_primes.php?','');

//var lien_dico="<?php echo $rootpath.'/annuaire/tools/dico_generate_array.php?';?>";


/************************************** JQuery ******************************************************/

/******Liste pour la barre de recherche des agents******************************************/
$(

	   function()
	   {
			   
				$('#nom_agent').autocomplete({
				  source: Tableau_agents,
				  focus: function(event, ui) {
					$(this).val(ui.item.label);
					return false;
				  },
				  select: function(event, ui) {
					$(this).val(ui.item.label);
					alert(ui.item.label);
					LoadFormAgent(ui.item.value,'',0);
					return false;
				  }
			});
				
			
			
    
			   
			   
/*********************DATEPICKER*******************/
                       $.datepicker
                       .setDefaults
                       ( 
                               {
                                     
<?php

$list_day_min = '';
foreach ($array_short_weekday[$session_langue] as $value_day_min)
{
 $list_day_min .= ($list_day_min == '') ? "'".htmlentities($value_day_min,ENT_COMPAT,'UTF-8')."'"  : ",'".htmlentities($value_day_min,ENT_COMPAT,'UTF-8')."'";
}
echo 'dayNamesMin:['.$list_day_min.']'."\n";

$list_month = '';
foreach ($array_month[$session_langue] as $value_month)
{
 $list_month .= ($list_month == '') ? "'".htmlentities($value_month,ENT_COMPAT,'UTF-8')."'"  : ",'".htmlentities($value_month,ENT_COMPAT,'UTF-8')."'";
}
echo ',monthNames:['.$list_month.']'."\n";

$list_month = '';
foreach ($array_short_month[$session_langue] as $value_month)
{
 $list_month .= ($list_month == '') ? "'".htmlentities($value_month,ENT_COMPAT,'UTF-8')."'"  : ",'".htmlentities($value_month,ENT_COMPAT,'UTF-8')."'";
}
echo ',monthNamesShort:['.$list_month.']'."\n";
$list_day_min = '';
$list_month = ''; 
?>
                                      ,dateFormat:'dd-mm-yy'
                                      ,changeYear:true
                                      ,defaultDate:'0'
                                      ,showAnim:'slideDown'
                                      ,showOn:'focus'
                                      //,buttonText:'<?php echo $libelle['afficher_calendrier'][$session_langue]?>'
                                      //,buttonImage:'images/calendar.gif'
                                      //,buttonImageOnly:true
                                      ,showButtonPanel:true 
                                      ,closeText: "X"
                                      ,currentText:"<?php echo $array_lbl['TODAY'][$session_langue] ?>"
                               }
                       );
               
			   

                       
					$.timepicker
					.setDefaults({
					  timeSeparator: ':',
					  showLeadingZero: true,
					  showMinutesLeadingZero: true,
					  showPeriodLabels: true,
					  hourText:'<?php echo $libelle['heures'][$session_langue];?>',
					  minuteText:'<?php echo $libelle['minutes'][$session_langue];?>',
					  amPmText: ['', ''],
					  closeButtonText: '<?php echo $libelle['fermer'][$session_langue];?>',
					  hours: {starts: 8, ends: 17},
					  minutes: { starts: 00, ends: 30, interval: 30},
					  showCloseButton:true,
					  showOn:'both'
					
					 });

				
			   }//FIN FUNCTION
        )// FIN DOCUMENT

		

		
</script>	