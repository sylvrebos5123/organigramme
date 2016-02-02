<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
//$dico_script='\\annuaire\\tools\\php_dico_array.php';
//include_once($rootpath.'\\organigramme\\tools\\pba_tools.php'); //function de connection
//include_once($rootpath.'\\organigramme/php/genere_list_form5.php');
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


if($id_mvt_bareme==0)
{
	$titre="Ajout d'un mouvement de barème";
	// verifie s'il s'agit d'un premier mvt
	
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_baremes
	where id_contrat=".$id_contrat.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(mysqli_num_rows($result)==0)
	{
		// si c'est la 1ère fois => date de début du contrat = date début mvt
					
		$sql="select start_date from cpas_contrats
		where id_contrat=".$id_contrat.";
		";
			
		$result=mysqli_query($lien, $sql);
		
		if(!$result)
		{
			echo "erreur dans la requete:<i>".$sql."</i>";
			exit;
		}


		$tab_contrat=mysqli_fetch_assoc($result);
		
		$date_debut_bareme=transformDate($tab_contrat['start_date']);
		
	}
	else
	{
		$date_debut_bareme='00-00-0000';
	}

	mysqli_close($lien);
	
	$date_echeance_bareme='00-00-0000';
	$id_bareme=0;
	$id_code=0;
	$id_grade=0;
	$id_bareme_cadre=0;
	$id_code_cadre=0;
	$id_grade_cadre=0;
	$actif=0;
	$type_cadre=0;
	$lien="./php/ajout_mvt_bareme.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_baremes
	where id_mvt_bareme=".$id_mvt_bareme.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_mvt_bareme=mysqli_fetch_assoc($result);
	
	$titre='Modification d\'un mouvement de barème';
	$date_debut_bareme=transformDate($tab_mvt_bareme['date_debut_bareme']);
	$date_echeance_bareme=transformDate($tab_mvt_bareme['date_echeance_bareme']);
	
	$id_bareme=$tab_mvt_bareme['id_bareme'];
	$id_code=$tab_mvt_bareme['id_code'];
	$id_grade=$tab_mvt_bareme['id_grade'];
	$id_bareme_cadre=$tab_mvt_bareme['id_bareme_cadre'];
	$id_code_cadre=$tab_mvt_bareme['id_code_cadre'];
	$id_grade_cadre=$tab_mvt_bareme['id_grade_cadre'];
	$type_cadre=$tab_mvt_bareme['type_cadre'];
	$actif=$tab_mvt_bareme['actif'];
	
	$lien="./php/modif_mvt_bareme.php";
}
$disabled='';
?><br>
<form id="FORM_MVT_BAREME" name="FORM_MVT_BAREME" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="4"><h3><?php echo $titre;?></h3></td>
</tr>

<tr>
	<td><?php echo dico("date_debut_bareme?","F");?> : </td>
	<td>
	
	<input type="text" id="date_debut_bareme" name="date_debut_bareme" size="20" value="<?php echo $date_debut_bareme;?>" onchange="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" onkeyup="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>/>
	
	</td>
	<td><?php echo dico("date_echeance_bareme?","F");?> : </td>
	<td>
	
	<input type="text" id="date_echeance_bareme" name="date_echeance_bareme" size="20" value="<?php echo $date_echeance_bareme;?>" onchange="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" onkeyup="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>/>
	<br><br>
	</td>
</tr>

<tr>
	<td colspan="4"><h2 style="text-decoration:underline;font-weight:bold;">SITUATION POUR LE CONTRAT</h2></td>
</tr>

<tr>
	<td><?php echo dico("bareme","F");?> : </td>
		<td>
		<select id="id_bareme" name="id_bareme" onchange="SetValueCadre('id_bareme','FORM_MVT_BAREME');SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_bareme.php');
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
			/*****************/
			
			
			?>
		</select>
		</td>
		
		<td><?php echo dico("code","F");?> : </td>
		<td>
		<select id="id_code" name="id_code" onchange="SetValueCadre('id_code','FORM_MVT_BAREME');SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_code.php');
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
			/*****************/
			
			
			?>
		</select>
		</td>
</tr>



<tr>

		<td><?php echo dico("grade","F");?> : </td>
		<td>
		<select id="id_grade" name="id_grade" onchange="SetValueCadre('id_grade','FORM_MVT_BAREME');SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_grade.php');
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
			/*****************/
			
			
			?>
		</select>
		</td>


		<td></td>
		<td>
		
		</td>
</tr>

<tr>
	<td colspan="4"><br><br><h2 style="text-decoration:underline;font-weight:bold;">SITUATION POUR LE CADRE</h2></td>
</tr>


<tr>
	<td><?php echo dico("bareme","F");?> : </td>
		<td>
		<select id="id_bareme_cadre" name="id_bareme_cadre" onchange="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_bareme.php');
			foreach($array_bareme as $key_cadre => $value_cadre)
			{
				
				if($id_bareme_cadre==$key_cadre)
				{
					echo '<option value="'.$key_cadre.'" selected>'.$array_bareme[$key_cadre].'</option>';
				}
				else
				{
					echo '<option value="'.$key_cadre.'">'.$array_bareme[$key_cadre].'</option>';
				}
			}
			/*****************/
			
			
			?>
		</select>
		</td>
		
		<td><?php echo dico("code","F");?> : </td>
		<td>
		<select id="id_code_cadre" name="id_code_cadre" onchange="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_code.php');
			foreach($array_code as $key_cadre => $value_cadre)
			{
				
				if($id_code_cadre==$key_cadre)
				{
					echo '<option value="'.$key_cadre.'" selected>'.$array_code[$key_cadre].'</option>';
				}
				else
				{
					echo '<option value="'.$key_cadre.'">'.$array_code[$key_cadre].'</option>';
				}
			}
			/*****************/
			
			
			?>
		</select>
		</td>
</tr>

<tr>

		<td><?php echo dico("grade","F");?> : </td>
		<td>
		<select id="id_grade_cadre" name="id_grade_cadre" onchange="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_grade.php');
			foreach($array_grade as $key_cadre => $value_cadre)
			{
				
				if($id_grade_cadre==$key_cadre)
				{
					echo '<option value="'.$key_cadre.'" selected>'.$array_grade[$key_cadre]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key_cadre.'">'.$array_grade[$key_cadre]['F'].'</option>';
				}
			}
			/*****************/
			
			
			?>
		</select>
		</td>


		<td><?php echo dico("type_cadre","F");?> : </td>
		<td>
		<select id="type_cadre" name="type_cadre" onchange="SetValue('modif_bareme',1,'FORM_MVT_BAREME');" <?php echo $disabled;?>>
		<?php
			
		switch($type_cadre)		
		{
			case 0:
			echo '<option value="0" selected>HORS CADRE</option>';
			echo '<option value="1">CADRE STANDARD</option>';
			echo '<option value="2">CADRE DIRIGEANT</option>';
			break;
			
			case 1:
			echo '<option value="0">HORS CADRE</option>';
			echo '<option value="1" selected>CADRE STANDARD</option>';
			echo '<option value="2">CADRE DIRIGEANT</option>';
			break;
			
			case 2:
			echo '<option value="0">HORS CADRE</option>';
			echo '<option value="1" >CADRE STANDARD</option>';
			echo '<option value="2" selected>CADRE DIRIGEANT</option>';
			break;
			
			default:
			echo '<option value="0" selected>HORS CADRE</option>';
			echo '<option value="1">CADRE STANDARD</option>';
			echo '<option value="2">CADRE DIRIGEANT</option>';
			break;
		}
			
			
		?>
		
		</select>
		<br><br>
		</td>
</tr>

</table>

<!--<input type="hidden" id="FORM_MVT_BAREME" name="FORM_MVT_BAREME" value="0"/>-->
<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<input type="hidden" id="id_contrat" name="id_contrat" value="<?php echo $id_contrat;?>" />
<input type="hidden" id="id_mvt_bareme" name="id_mvt_bareme" value="<?php echo $id_mvt_bareme;?>" />
<input type="hidden" id="modif_bareme" name="modif_bareme" value="0" />
<input type="hidden" id="type_mvt" name="type_mvt" value="baremes" />

<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_MVT_BAREME');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_MVT_BAREME')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_BAREME').innerHTML='';" value="<?php echo dico("close","F");?>" />

</p>
</form>
