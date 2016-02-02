<?php
//ob_clean();
// header utf-8//
//header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

 //function de traduction
include_once('../tools/function_dico.php');

include('params.php');


//include('../arrays_libelle/array_hors_departement.php');
//include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
//include('../arrays_libelle/array_cellule.php');

/*******************/


include('../connect_db.php');


/**************DEPARTEMENTS*******************************************/

$sql="
		select * from cpas_departements order by label_F;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	
	echo 'No result';
	
	
	exit;
}
$tab_dep=fn_ResultToArray($result,'id_dep');

/**************HORS DEPARTEMENTS*******************************************/

$sql="
		select * from cpas_hors_departements order by label_F;
";
//var_dump($sql);
$result=mysqli_query($lien, $sql);

if(mysqli_num_rows($result)==0)
{
	
	echo 'No result';
	
	
	exit;
}
$tab_hors_dep=fn_ResultToArray($result,'id_hors_dep');

mysqli_close($lien);



$art_budgetaire='';
$id_hors_dep=0;
$id_dep=0;
$id_ser=0;
$option_ser='';
$disabled_ser='disabled';
$id_cel=0;
$option_cel='';
$disabled_cel='disabled';
$actif=0;
$lien="./php/display_list_cadre_standard.php";

$disabled='';
?><br>
<form id="FORM_SELECT_SERVICE" name="FORM_SELECT_SERVICE" style="border:3px dotted #fff;padding:5px;" onclick="SetColorButton('FORM_SELECT_SERVICE');" onchange="SetColorButton('FORM_SELECT_SERVICE');" action="<?php echo $lien;?>" method="post" >
	
<table>

<tr>

	<td width="150px"><?php echo dico("choisir_groupe","F");?> : </td>
	<td width="520px">
	
		<select id="choix_groupe" name="choix_groupe" width="200px" onchange="DisplayChoixDep('FORM_SELECT_SERVICE',this.value);" <?php echo $disabled;?>>
			<option value="DEP" style="background-color:#e4f8d2;" selected>Groupe département</option>
			<option value="HORS_DEP" style="background-color:#ffc477;" >Groupe hors-département</option>
		</select>
	</td>

</tr>
</table>	
<!-- GROUP DEP/HORS DEP-->
<div id="GROUP_DEP" style="visibility:visible;height:90px;">
	<table>	
	<tr>

		<td width="150px"><span style="background-color:#e4f8d2;"><?php echo dico("id_dep","F");?> : </span></td>
		<td width="520px">
		<select id="id_dep" name="id_dep" style="width:100%" onchange="FiltreSerCel('id_dep',this.value,'FORM_SELECT_SERVICE');" <?php echo $disabled;?>>
		<?php
			
			/*foreach($array_departement as $key => $value)
			{
				if($id_dep==$key)
				{
					echo '<option value="'.$key.'" onclick="FiltreSerCel(\'id_dep\',\''.$key.'\',\'FORM_SELECT_SERVICE\');" selected>'.$array_departement[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_departement[$key]['F'].'</option>';
				}
			}*/
			
			foreach($tab_dep as $key => $value)
			{
				if($id_dep==$key)
				{
					echo '<option value="'.$key.'" onclick="FiltreSerCel(\'id_dep\',\''.$key.'\',\'FORM_MVT_SERVICE\');" selected>'.$value['label_F'].'</option>';
				}
				else
				{
					
					if($value['actif']==1)
					{
						echo '<option value="'.$key.'">'.$value['label_F'].'</option>';
					}
					
				}
			}
		?>
		
		</select>
		</td>

	</tr>
		
	<tr>
		
		<td>
		<span style="background-color:#e4f8d2;"><?php echo dico("id_ser","F");?> :</span>
		</td>	
		
		<td>
		<select id="id_ser" name="id_ser" style="width:100%" <?php echo $disabled_ser;?>>
		<?php
			echo $option_ser;
			
		?>
		
		</select>
		</td>
	</tr>
	
	<tr style="visibility:hidden;height:0px;">
		<td><span style="background-color:#e4f8d2;"><?php echo dico("id_cel","F");?> : </span></td>
		<td>
		<select id="id_cel" name="id_cel" style="width:100%" <?php echo $disabled_cel;?>>
		<?php
			echo $option_cel;
			
		?>
		
		</select>
		
		</td>
	</tr>
</table>	
</div>

<div id="GROUP_HORS_DEP" style="visibility:hidden;height:0px;">
<table>	
	<tr>

		<td width="150px"><span style="background-color:#ffc477;"><?php echo dico("id_hors_dep","F");?> : </td>
		<td width="520px" >
		<select id="id_hors_dep" name="id_hors_dep" style="width:100%" <?php echo $disabled;?>>
		<?php
			
			/*foreach($array_hors_departement as $key => $value)
			{
				if($id_hors_dep==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_hors_departement[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_hors_departement[$key]['F'].'</option>';
				}
			}*/
			foreach($tab_hors_dep as $key => $value)
			{
				if($id_hors_dep==$key)
				{
					echo '<option value="'.$key.'" selected>'.$value['label_F'].'</option>';
				}
				else
				{
					
					if($value['actif']==1)
					{
						echo '<option value="'.$key.'">'.$value['label_F'].'</option>';
					}
					
				}
			}
		?>
		
		</select>
		</td>

	</tr>
</table>	
</div>
<!-- FIN GROUP DEP/HORS DEP-->	


<input type="hidden" id="id_cadre" name="id_cadre" value="<?php echo $id_cadre;?>" />

<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="DisplayListCadreStandard('FORM_SELECT_SERVICE');" value="<?php echo dico("afficher","F");?>" disabled />
	<!--
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_SELECT_SERVICE')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('FORM_SELECT_SERVICE').innerHTML='';" value="<?php echo dico("close","F");?>" />
	-->
</p>
</form>
