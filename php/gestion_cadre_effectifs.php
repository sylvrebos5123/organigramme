<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

 //function de traduction
include_once('../tools/function_dico.php');

// include de la langue
include('../includes/php_linguistique.php');
include('../exchange/array_lbl_cal.php'); //tableaux et fonction pour les libellés de calendrier / langue



//@params
if (isset($_GET['session_langue']))
 $session_langue=trim($_GET['session_langue']);
else
{
 if (isset($_POST['session_langue']))
  $session_langue=trim($_POST['session_langue']);
 else
  $session_langue='F';
}

/***************Libellé******************************/
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

/****************Lecture liste des cadres********************/

	include('../connect_db.php');
					
	$sql="select * from cpas_cadres order by date_situation desc;
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}
	
	
	mysqli_close($lien);
	
	$tab_cadre=fn_ResultToArray($result,'id_cadre');


/**********PARAMS******************/

include('params.php');
include('verification.php');


ob_clean();
header('Content-Type: text/html; charset=utf-8');


?>

<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="author" content="Matt Everson of Astuteo, LLC – http://astuteo.com/slickmap" />
	<title>Cadre/effectifs CPAS</title>
	<link rel="stylesheet" type="text/css" media="screen, print" href="../slickmap.css" />
	<link rel='stylesheet' type='text/css' href='../css/css_onglets.css' />
		
	<link rel='stylesheet' type='text/css' href="<?php echo $rootpath.'/css/jquery.ui.timepicker.css';?>" />
	<link rel="stylesheet" href="../css/my_style.css" type="text/css" media="screen" title="no title" charset="utf-8" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo $rootpath.'/css/smoothness/jquery-ui-1.8.21.custom.css';?>"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<style>
	.form input[type="search"] 
	{ 
	/*background-color: #eee; */
	background-image: url(images/icon_search.png);
	background-repeat:no-repeat;
	/*background-position: 2px 2px 2px 2px;*/ /* position of the right icon */
	border: 1px solid #ccc; 
	padding-bottom: 5px; 
	padding-top: 5px; 
	padding-left: 22px; 
	font-family: Keffeesatz, Arial; 
	color: #4b4b4b; 
	font-size: 14px; 
	-webkit-border-radius: 5px; 
	margin-bottom: 15px; 
	margin-top:-2px;
	}
	
	#DIV_CLOSE_PANNEL #box_close {
		
		/* position absolute so that z-index can be defined and able to move this item using javascript */
		position:absolute;
		top:0px;
		left:0px;
		z-index:200; 

		/* image of the right rounded corner */
		background: url(./tail.gif) no-repeat right top; 
		height:52px;
		/*border:1px solid red;*/


		/* add padding 8px so that the tail would appear */
		padding-right:8px;
		
		/* set the box position manually */
		margin-left:105px;
		
	}
	
	#DIV_CLOSE_PANNEL #box_close .head {
		/* image of the left rounded corner */
		background:url(./head.gif) no-repeat 0 0;
		height:30px;
		color:#eee;
		/*border:1px solid green;*/
		/*color:black;*/
		/* force text display in one line */
		white-space:nowrap;

		/* set the text position manually */
		padding-left:8px;
		padding-top:22px;
		padding-right:5px;
	}
	</style>
	<script language="javascript" type="text/javascript" src="../javascript/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>	
	<script language="javascript" type="text/javascript" src="../javascript/jquery-1.4.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="../javascript/jquery-ui-1.8.21.custom.min.js"></script>
	<script language="javascript" type="text/javascript" src="../javascript/jquery.ui.timepicker.js"></script>
	<script language="javascript" type="text/javascript" src="../javascript/general_script.js"></script>
	<script language="javascript" type="text/javascript" src="../javascript/base64_script.js"></script>
	
	
<script type="text/javascript">

var bw = new checkBrowser();
if (!document.getElementById)
 document.getElementById = getObjectById;

String.prototype.trim = function() {return this.replace(/^([\s\t\n]|\&nbsp\;)+|([\s\t\n]|\&nbsp\;)+$/g, '');}


</script>
</head>
<body>


<?php
include('../arrays_libelle/array_departement.php');

$disabled='';
?>
<img src="../images/logo.gif" align="middle"/> 


<br><br>
		<h2>Gestion cadre/effectifs CPAS Ixelles</h2><br>

<?php $lien="lecture_cadre.php"?>
<div align="right">
	<input type="button" id="bnt_gen_cadre_effectifs" style="background-color:#FF8A05;" onclick="GenerateCadreEffectifs();" value="Générer une comparaison cadre/effectifs"/>
</div>

<form id="FORM_CADRE" name="FORM_CADRE" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	<table>
		<tr>
			<td><?php echo dico("selection_cadre","F");?> : </td>
			<td>

				<select name="id_cadre" id="id_cadre">
					<option value="0" selected>---</option>
					<?php
					foreach($tab_cadre as $key =>$value)
					{
						
						echo'<option value="'.$value['id_cadre'].'">'.transformDate($value['date_situation']).'</option>';
					}
					?>
		
				</select>
		
	
			<!--<input type="text" id="date_situation" name="date_situation" size="20" value="00-00-0000"  <?php echo $disabled;?>/>
			-->
			</td>
			<td>
			<input type="button" id="bnt_sauver" style="visibility:visible;width:150px;" onclick="DisplayCadre();" value="<?php echo dico("afficher","F");?>" disabled />
	
			</td>
		</tr>	
	</table>		
</form>
	
<div id="LIST_CADRE" style="height:150px;overflow:auto;background-color:#ddd;padding:5px;">
	<br/>
	<br/>
	<br/>
	<h1><center>SELECTIONNER UNE DATE POUR AFFICHER LE CADRE</center></h1>
</div>
<br>
<center>
	<input type="button" id="bnt_gen_cadre" style="visibility:hidden;" onclick="GenerateCadre();" value="Générer le cadre en Excel"/>
</center>

<!-- EFFECTIFS-->
<form id="FORM_EFFECTIFS" name="FORM_EFFECTIFS" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	<table>
		<tr>
			<td><?php echo dico("date_situation_effectifs","F");?> : </td>
			<td>
			
			<input type="text" id="date_situation_effectifs" name="date_situation_effectifs" size="20" value="00-00-0000"  <?php echo $disabled;?>/>
			
			</td>
			<td>
			<input type="button" id="bnt_sauver" style="visibility:visible;width:150px;" onclick="DisplayEffectifs();" value="<?php echo dico("afficher","F");?>" disabled />
			
			</td>
		</tr>	
	</table>		
</form>
	
<div id="LIST_EFFECTIFS" style="height:300px;overflow:auto;background-color:#ddd;padding:5px;">
	<br/>
	<br/>
	<br/>
	<h1><center>SELECTIONNER UNE DATE POUR AFFICHER LES EFFECTIFS</center></h1>
</div>
<br>

<center>
	<input type="button" id="bnt_gen_effectifs" style="visibility:hidden;" onclick="GenerateEffectifs();" value="Générer les effectifs en Excel"/>
</center>



	<div id="WaitingZone" style="filter: Alpha(Opacity=60); -moz-opacity:0.6; opacity: 0.6;display:none;position:absolute; background-color:#999999;width:100%;height:100%;padding-top:200px;top:0px;left:0px;z-index:2000;"> 
	<center>
	<br/>
	<br/>
	<br/>
	<span style="color:white;font-size:18pt;font-weight:bold;">Chargement en cours...</span><br/>
	<img src="../images/253-1.GIF" />
	</center>
	</div>	<br/>



</body>
</html>

<script>

$('#bnt_gen_cadre_effectifs').mouseover(function() { $(this).css("background-color","#FFA647");});
$('#bnt_gen_cadre_effectifs').mouseout(function() { $(this).css("background-color","#FF8A05");});
<?php
$year_min = date('Y')-70;
$year_max = date('Y')+10;
?>

$( "#date_situation_effectifs" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});



function myHttpRequest2(url,TargetId)
{
	//alert(TargetId);
	var t=document.getElementById(TargetId);
	var w=document.getElementById("WaitingZone");
	//alert(t+"\n"+w);
	var currentDate = new Date();
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
	
		xhr.open("GET", url+"ts="+timeStamp, true);
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
	var timeStamp = currentDate.getTime();
	xhr_object.open("GET", url+"ts="+timeStamp, false);
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
		
	 return responseHtml;
	}
} 



function fFormSubmit(idForm)
{	
	var MyForm=document.getElementById(idForm);
	//alert(MyForm);
	var nbrElements = MyForm.length;
	var MyFormAction = MyForm.action;
	var msg=MyForm.action+"?";
	//alert(MyForm.action);
	
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
					//alert("-]"+MyElementName+"[-="+MyElementValue);
					msg+=MyElementName+"="+encodeURIComponent(MyElementValue)+"&";
				}
			}else{
				//alert("-]"+MyElementName+"[-="+MyElementValue);
				msg+=MyElementName+"="+encodeURIComponent(MyElementValue)+"&";
			}
		}
	}
	

	var HttpResponse = myHttpRequest(msg);
	

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

function SetValueEquivTP(id_form)
{
	var MyForm=document.getElementById(id_form);
	
	var id_regime=MyForm.elements['id_regime'].value;
	//alert("1 --"+id_regime);
	//alert("2 --"+Tableau_equiv_tp[15]);
	//alert("3 --"+Tableau_equiv_tp[id_regime]);
	MyForm.elements['id_equiv_tp'].value=Tableau_equiv_tp[id_regime];
}

function DisplayCadre()
{
	var MyObjet = document.getElementById('LIST_CADRE');
		
	var id_cadre=document.getElementById('FORM_CADRE').elements['id_cadre'].value;
	
	var HttpResponse = myHttpRequest('lecture_cadre.php?id_cadre='+id_cadre+'&');
		
	MyObjet.innerHTML = HttpResponse;
}

function DisplayEffectifs()
{
	//var w=document.getElementById("WaitingZone");
	
	//w.style.display='block';
	
	//var contenu_attente=document.getElementById('WaitingZone').innerHTML;
	
	//alert(document.getElementById('WaitingZone').innerHTML);
	
	var MyObjet = document.getElementById('LIST_EFFECTIFS');
		
	var date_situation_effectifs=document.getElementById('FORM_EFFECTIFS').elements['date_situation_effectifs'].value;
	
	var HttpResponse = myHttpRequest('lecture_effectifs.php?date_situation_effectifs='+date_situation_effectifs+'&');
	
	//var HttpResponse = myHttpRequest2('lecture_effectifs.php?date_situation_effectifs='+date_situation_effectifs+'&',MyObjet);
	//MyObjet.innerHTML = contenu_attente;	
	
	MyObjet.innerHTML = HttpResponse;
	//w.style.display='none';
}

function GenerateCadre()
{
	var id_cadre=document.getElementById('FORM_CADRE').elements['id_cadre'].value;
	
	myHttpRequest2('generate_cadre.php?id_cadre='+id_cadre+'&','');
}


function GenerateEffectifs()
{
	var date_situation_effectifs=document.getElementById('FORM_EFFECTIFS').elements['date_situation_effectifs'].value;
	
	myHttpRequest2('generate_effectifs.php?date_situation_effectifs='+date_situation_effectifs+'&','');
}

function GenerateCadreEffectifs()
{
	var id_cadre=document.getElementById('FORM_CADRE').elements['id_cadre'].value;
	
	var date_situation_effectifs=document.getElementById('FORM_EFFECTIFS').elements['date_situation_effectifs'].value;
	
	myHttpRequest2('generate_cadre_effectifs.php?date_situation_effectifs='+date_situation_effectifs+'&id_cadre='+id_cadre+'&','');
}

 
function DisplayFormMvt(id_div,type_form,id_mvt,id_agent,id_contrat)
{
	var MyObjet = document.getElementById(id_div);
		
	var HttpResponse = myHttpRequest('php/display_form_mvt_'+type_form+'.php?id_mvt_'+type_form+'='+id_mvt+'&id_agent='+id_agent+'&id_contrat='+id_contrat+'&');
		
	MyObjet.innerHTML = HttpResponse;
		
	<?php
	$year_min = date('Y')-70;
	//$year_max = date('Ymd');
	$year_max = date('Y')+10;
	?>
	//$( "#date_mvt" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>', maxDate: '-1'});
	$( "#date_mvt" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_debut_service" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_service" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
	$( "#date_debut_fonction" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_fonction" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
	$( "#date_debut_grade" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_grade" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
	$( "#date_debut_bareme" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_bareme" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
	$( "#date_debut_code" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_code" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
	$( "#date_debut_statut" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_statut" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
	$( "#date_debut_regime" ).datepicker({ yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	$( "#date_echeance_regime" ).datepicker({ defaultDate:'0',yearRange:'<?php echo $year_min.':'.$year_max;?>'});
	
}


	
	
function GenerateExcel(nom_fichier)
{
	
	myHttpRequest2('php/test_excel.php?nom_fichier='+nom_fichier+'&','');
	
}	


/************************************** JQuery ******************************************************/
$(

               function()
               {
			   
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
                                      //,yearRange:'<?php echo $year_min.':'.$year_max; ?>'
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