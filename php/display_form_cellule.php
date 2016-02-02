<?php
ob_clean();
header ('Content-type: text/html; charset=utf-8');

if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
//$dico_script='\\annuaire\\tools\\php_dico_array.php';

//include_once($rootpath.$dico_script); //function de traduction
 //function de traduction
include_once('../tools/function_dico.php');

include('params.php');

/*if(isset($_GET['nom_champ']))
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
}*/
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
	<!--<div id="title" style="visibility:hidden;">aaaa</div>-->
	
	
</div>


<?php 
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');

if($id_cel==0)
{
	echo '<h1>Ajout d\'une cellule pour le service '.$array_service[$id_ser]['F'].'</h1><br>';
	//$id_ser=0;
	$label_F='';
	$label_N='';
	$fax="";
	$lien="./php/ajout_cellule.php";
}
else
{
	echo '<h1>Modification du cellule '.$array_cellule[$id_cel]['F'].'</h1><br>';
	
	include('../connect_db.php');

	$sql="SELECT * FROM cpas_cellules where id_cel=".$id_cel.";";

	//var_dump($sql);
	$result=mysqli_query($lien,$sql);
	//ferme la connection//
	mysqli_close($lien);

	$tab_cellules=mysqli_fetch_assoc($result);
/***************************************************/	
	$label_F=$tab_cellules['label_F'];
	$label_N=$tab_cellules['label_N'];
	$fax=$tab_cellules['fax'];
	$lien="./php/modif_cellule.php";
}



?>

<div style="background-color:#E4F8D2;"> 
<form id="FORM_CELLULE" name="FORM_CELLULE" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">

	<table border="1" cellspacing="3" cellpadding="3">
		
		<tr>
			<td><?php echo dico("label_F","F");?> : </td>
			<td><input type="text" id="label_F" name="label_F" size="70" onchange="SetColorButton('FORM_CELLULE');" onkeyup="SetColorButton('FORM_CELLULE');" value="<?php echo $label_F;?>"/>  </td>
		</tr>
		<tr><br></tr>
		<tr>		
			<td><?php echo dico("label_N","F");?> : </td>
			<td><input type="text" id="label_N" name="label_N" size="70" onchange="SetColorButton('FORM_CELLULE');" onkeyup="SetColorButton('FORM_CELLULE');" value="<?php echo $label_N;?>" /></td>
			
		</tr>
		<tr><br></tr>
		<tr>	
			<td><?php echo dico("fax","F");?> : </td>
			<td><input type="text" id="fax" name="fax" size="70" onchange="SetColorButton('FORM_CELLULE');" onkeyup="SetColorButton('FORM_CELLULE');" value="<?php echo $fax;?>" /></td>
			
		</tr>
	</table>
	<br>
	<input type="hidden" id="id_dep" name="id_dep" value="<?php echo $id_dep;?>" />
	<input type="hidden" id="id_ser" name="id_ser" value="<?php echo $id_ser;?>" />
	<input type="hidden" id="id_cel" name="id_cel" value="<?php echo $id_cel;?>" />
	<p align="center">
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_CELLULE');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_CELLULE')" value="<?php echo dico("reset","F");?>" />
	</p>

</form>
</div>
