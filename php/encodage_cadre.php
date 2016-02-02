<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

// include de la langue
include($rootpath.'/includes/php_linguistique.php');
include($rootpath.'/exchange/array_lbl_cal.php'); //tableaux et fonction pour les libellés de calendrier / langue


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

?>
<style>
#DIV_CLOSE_PANNEL #box_close {
		
		/* position absolute so that z-index can be defined and able to move this item using javascript */
		position:absolute; 
		/*display:block;*/
		
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
<div align="right">
	<div id="DIV_CLOSE_PANNEL" onmouseover="DisplayTitleClose();" onmouseout="document.getElementById('box_close').style.visibility='hidden';" style="padding-top:2px;margin-right:10px;margin-top:10px;cursor:default;font-size:15pt;font-family:Verdana;font-weight:bold;color:white;height:25px;width:27px;text-align:center;background-color:#3d3d3d;border:2px solid #ccc;" onclick="CloseToLeft('modal_externe',100,0);">
	X<div id="box_close" style="visibility:hidden;z-index:500;font-size:10pt;"><div class="head">box</div></div>
	</div>	
	
</div>

<h1>Encodage du cadre
<span style="cursor:default;background:url('./images/bnt_plus.png') no-repeat;" title="Ajouter une nouvelle situation de cadre" alt="Ajouter une nouvelle situation de cadre" onclick="DisplayFormDateCadre(0);"/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</h1><br><br>


<div id="LIST_CADRES" class="div_list_cadres"></div>

<div id="FORM_ONGLETS" class="fond_form">
	<div id="DIV_FORM_DATE_CADRE" ></div>
	<div id="LIST_ONGLETS" style="padding:5px;">
	<p class="msg_accueil">Encodage du cadre. <br>Pour ajouter ou modifier une situation du cadre, cliquez dans la fenêtre se trouvant à gauche.</p>
	</div>
	
</div>
	



