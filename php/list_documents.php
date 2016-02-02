<?php
//ob_clean();
header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

/*******************/
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
/****************************/


include('../connect_db.php');


/**************DEPARTEMENTS*******************************************/

$sql="
		select * from cpas_departements where actif=1 order by label_F;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	
	echo 'No result';
	
	
	exit;
}
$tab_dep=fn_ResultToArray($result,'id_dep');
?>
<style>
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
<div align="right">
	<div id="DIV_CLOSE_PANNEL" onmouseover="DisplayTitleClose();" onmouseout="document.getElementById('box_close').style.visibility='hidden';" style="padding-top:2px;margin-right:10px;margin-top:10px;cursor:default;font-size:15pt;font-family:Verdana;font-weight:bold;color:white;height:25px;width:27px;text-align:center;background-color:#3d3d3d;border:2px solid #ccc;" onclick="CloseToLeft('modal_externe',100,0);">
	X<div id="box_close" style="visibility:hidden;z-index:500;font-size:10pt;"><div class="head">box</div></div>
</div>
<!--<div id="accordion">-->
<div align="left" style="margin:5px;font-size:14pt;"
class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" 
 >
Base de données Excel
</div>
<div align="left" style="margin:5px;">
<!--<span class="lien_menu" onclick="GenerateExcel('<?php echo 'bd_personnel_'.date('Ymd');?>');">Ouvrir le fichier</span><br><br>
-->
<form id="FORM_SELECT_BD" name="FORM_SELECT_BD" action="" method="post">

Date de la situation des agents:
<input type="text" id="date_situation_effectifs" name="date_situation_effectifs" size="27" value="" />
<input type="button" style="visibility:visible;" onclick="GenerateExcel('bd_personnel');" value="OK" />
</form>
</div>
<div align="left" style="margin:5px;font-size:14pt;"
class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" 
 >
Tableau Personnel Word
</div>
<div align="left" style="margin:5px;">



	<form id="FORM_TABLEAU_PERSO" name="FORM_TABLEAU_PERSO" action="" method="post">
	<select id="id_dep" name="id_dep" style="margin:0;width:250px;">
	<?php
	echo '<option value="0">Tous les départements</option>';
	foreach($tab_dep as $key => $value)
	{
		if($key!=0)
		{
			echo '<option value="'.$key.'">'.$value['label_F'].'</option>';
			//echo '<span class="lien_menu" onclick="DisplayTabPersonnel('.$key.');" > - '.$value['label_F'].'</span><br><br>';
		}
	} 
	?>
	</select>
	Date de la situation des agents:
	<input type="text" id="date_situation_effectifs_tableau" name="date_situation_effectifs_tableau" size="22" value="" />
	<input type="button" style="visibility:visible;" onclick="DisplayTabPersonnel();" value="OK" />
	</form>
</div>
<div align="left" style="margin:5px;font-size:14pt;"
class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" 
 >
Agents entrants
</div>
<div align="left" style="margin:5px;">

<form id="FORM_SELECT_DATES" name="FORM_SELECT_DATES" action="./php/generate_personnel_entrants.php" method="post">

Date de début :
<input type="text" id="date_debut" name="date_debut" size="27" value="" />
	
Date de fin :
<input type="text" id="date_fin" name="date_fin" size="27" value="" />

<input type="button" style="visibility:visible;" onclick="fFormSubmit('FORM_SELECT_DATES');" value="OK" />
	
</form>

<div align="left" style="margin:5px;font-size:14pt;"
class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" 
 >
Agents sortants
</div>
<div align="left" style="margin:5px;">

<form id="FORM_SELECT_DATES_OUT" name="FORM_SELECT_DATES_OUT" action="./php/generate_personnel_sortants.php" method="post">

Date de début :
<input type="text" id="date_debut_out" name="date_debut_out" size="27" value="" />
	
Date de fin :
<input type="text" id="date_fin_out" name="date_fin_out" size="27" value="" />

<input type="button" style="visibility:visible;" onclick="fFormSubmit('FORM_SELECT_DATES_OUT');" value="OK" />
	
</form>

</div>
<!--</div>-->

<!--</div>-->