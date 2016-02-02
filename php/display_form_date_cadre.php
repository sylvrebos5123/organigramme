<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
//$dico_script='\\annuaire\\tools\\php_dico_array.php';
//include_once($rootpath.$dico_script); //function de traduction
 //function de traduction
include_once('../tools/function_dico.php');
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



include('params.php');
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
<!--<div align="right">
	<div id="DIV_CLOSE_PANNEL" onmouseover="DisplayTitleClose();" onmouseout="document.getElementById('box_close').style.visibility='hidden';" style="padding-top:2px;margin-right:10px;margin-top:10px;cursor:default;font-size:15pt;font-family:Verdana;font-weight:bold;color:white;height:25px;width:27px;text-align:center;background-color:#3d3d3d;border:2px solid #ccc;" onclick="CloseToLeft('modal_externe',100,0);">
	X<div id="box_close" style="visibility:hidden;z-index:500;font-size:10pt;"><div class="head">box</div></div>
	</div>
	
	
</div>-->

<?php
$options_dupliquer='';

if($id_cadre==0)
{
	
	echo '<div class="td_list_title" style="padding:5px;text-align:center;">Ajout d\'un cadre</div>';
		
	$date_situation='00-00-0000';
	
	// Options pour dupliquer un cadre
	
	include('../connect_db.php');
					
	$sql="select * from cpas_cadres order by date_situation desc;
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}
	
	
	
	if(mysqli_num_rows($result)==0)
	{
		$options_dupliquer='';
	}
	else
	{
		$tab_options_dupliquer=fn_ResultToArray($result,'id_cadre');
		
		$options_dupliquer=
		'
			<tr>
				<td>'.dico("dupliquer_cadre?","F").': </td>
				<td>
		';
		$options_dupliquer.='<select name="id_cadre_a_dupliquer" id="id_cadre_a_dupliquer">';
		$options_dupliquer.='<option value="0" selected>---</option>';
		foreach($tab_options_dupliquer as $key =>$value)
		{
			
			$options_dupliquer.='<option value="'.$value['id_cadre'].'">'.transformDate($value['date_situation']).'</option>';
		}
		
		$options_dupliquer.=
		'
			</select>
			</td>
		</tr>
		';
	}

	mysqli_close($lien);
	
	
	
	$lien="./php/ajout_date_cadre.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_cadres
	where id_cadre=".$id_cadre.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_cadre=mysqli_fetch_assoc($result);
	
	echo '<div class="td_list_title" style="padding:5px;text-align:center;">Lecture du cadre</div>';
		
	$date_situation=transformDate($tab_cadre['date_situation']);
	
	$lien="./php/modif_date_cadre.php";
	$options_dupliquer='';
}
$disabled='';
?>
<div style="background-color:#E4F8D2;"> 
<br>
	<form id="FORM_DATE_CADRE" name="FORM_DATE_CADRE" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
		
	<table>
	
	<tr>
		<td><?php echo dico("date_situation_cadre?","F");?> : </td>
		<td>
		
		<input type="text" id="date_situation" name="date_situation" size="20" value="<?php echo $date_situation;?>" <?php echo $disabled;?>/>
		
		</td>
		
	</tr>
	
	<?php echo $options_dupliquer;?>

	</table>

	
	<input type="hidden" id="id_cadre" name="id_cadre" value="<?php echo $id_cadre;?>" />


	<br>
	<p align="center">
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_DATE_CADRE');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_DATE_CADRE')" value="<?php echo dico("reset","F");?>" />
		
	</p>
	</form>
</div>