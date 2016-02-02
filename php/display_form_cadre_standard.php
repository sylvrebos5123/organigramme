<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
//$dico_script='\\annuaire\\tools\\php_dico_array.php';
//include_once($rootpath.$dico_script); //function de traduction
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



include('params.php');



if($id_place_cadre==0)
{
	//echo "<h1>Ajout</h1><br>";
	$art_budgetaire="";
	//$grade_fonction="";
	$id_grade=0;
	$id_bareme=0;
	$id_code=0;
	$id_equiv_tp="0,00";
	//$id_grade_cadre=0;
	//$date_situation='00-00-0000';
	
	$lien="./php/ajout_place_cadre.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_places_cadre
	where id_place_cadre=".$id_place_cadre.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_cadre_dirigeant=mysqli_fetch_assoc($result);
	
	//echo '<h1>Modification</h1><br>';
	$art_budgetaire=$tab_cadre_dirigeant['article_budgetaire'];
	//$grade_fonction=$tab_cadre_dirigeant['grade_fonction'];
	$id_grade=$tab_cadre_dirigeant['id_grade'];
	$id_fonc=$tab_cadre_dirigeant['id_fonc'];
	//$date_situation=transformDate($tab_cadre_dirigeant['date_situation']);
	//$id_grade_cadre=$tab_cadre_dirigeant['id_grade_cadre'];
	$id_bareme=$tab_cadre_dirigeant['id_bareme'];
	$id_code=$tab_cadre_dirigeant['id_code'];
	$id_equiv_tp=$tab_cadre_dirigeant['id_equiv_tp'];
	//$actif=$tab_mvt_bareme['actif'];
	
	$lien="./php/modif_place_cadre.php";
}
$disabled='';

//include('../arrays_libelle/array_grade_cadre.php');
include('../arrays_libelle/array_grade.php');
include('../arrays_libelle/array_fonction.php');
include('../arrays_libelle/array_bareme.php');
include('../arrays_libelle/array_code.php');
?>
<div class="form_date_cadre"> 
<br>
	<form id="FORM_DATE_CADRE_STANDARD" name="FORM_DATE_CADRE_STANDARD" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
		
	<table>
	<tr>

		<td width="150px"><?php echo dico("art_budgetaire(s)","F");?> : </td>
		<td>
		
		<input type="text" id="art_budgetaire" name="art_budgetaire" style="width:98%" value="<?php echo $art_budgetaire;?>" <?php echo $disabled;?>/>
		<br><br>
		</td>

	</tr>
	
	<tr>

		<td width="150px"><?php echo dico("grade","F");?> : </td>
		<td width="520px">
		
		<!--<input type="text" id="grade_fonction" name="grade_fonction" style="width:99%" value="<?php echo $grade_fonction;?>"  <?php echo $disabled;?>/>
		-->
		<select id="id_grade" name="id_grade" style="width:100%" <?php echo $disabled;?>>
		<?php
			
			foreach($array_grade as $key => $value)
			{
				if($id_grade==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_grade[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_grade[$key]['F'].'</option>';
				}
			}
		?>
		
		</select>
		<!--<select id="id_fonc" name="id_fonc" style="width:100%" <?php echo $disabled;?>>
		<?php
			
			foreach($array_fonction as $key => $value)
			{
				if($id_fonc==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_fonction[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_fonction[$key]['F'].'</option>';
				}
			}
		?>
		
		</select>-->
		<br><br>
		</td>

	</tr>
	
	<tr>

		<td width="150px"><?php echo dico("id_grade_cadre","F");?> : </td>
		<td width="520px" >
		<select id="id_bareme" name="id_bareme" style="width:40%" <?php echo $disabled;?>>
		<?php
			
			foreach($array_bareme as $key => $value)
			{
				if($id_bareme==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_bareme[$key].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_bareme[$key].'</option>';
				}
			}
		?>
		
		</select>
		<select id="id_code" name="id_code" style="width:40%" <?php echo $disabled;?>>
		<?php
			
			foreach($array_code as $key => $value)
			{
				if($id_code==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_code[$key].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_code[$key].'</option>';
				}
			}
		?>
		
		</select>
		<br><br>
		</td>

	</tr>
	
	<tr>

		<td width="150px"><?php echo dico("id_equiv_tp","F");?> : </td>
		<td>
		
		<input type="text" id="id_equiv_tp" name="id_equiv_tp" size="10" value="<?php echo $id_equiv_tp;?>"  <?php echo $disabled;?>/>
		<br><br>
		</td>

	</tr>
	

	</table>

	<input type="hidden" id="id_hors_dep" name="id_hors_dep" value="<?php echo $id_hors_dep;?>" />
	<input type="hidden" id="id_dep" name="id_dep" value="<?php echo $id_dep;?>" />
	<input type="hidden" id="id_ser" name="id_ser" value="<?php echo $id_ser;?>" />
	<input type="hidden" id="id_cadre" name="id_cadre" value="<?php echo $id_cadre;?>" />
	<input type="hidden" id="id_place_cadre" name="id_place_cadre" value="<?php echo $id_place_cadre;?>" />
	<input type="hidden" id="type_cadre" name="type_cadre" value="1" />

	<br>
	<p align="center">
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_DATE_CADRE_STANDARD');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_DATE_CADRE_STANDARD')" value="<?php echo dico("reset","F");?>" />
		
	</p>
	</form>
</div>