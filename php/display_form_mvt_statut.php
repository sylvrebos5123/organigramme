<?php
ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

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


if($id_mvt_statut==0)
{
	$titre="Ajout d'un mouvement de statut";
		// vérifie s'il s'agit du 1er mvt
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_statuts
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
		
		$date_debut_statut=transformDate($tab_contrat['start_date']);
		
	}
	else
	{
		$date_debut_statut='00-00-0000';
	}
	
	$date_echeance_statut="00-00-0000";
	$contractuel_nomme="C";
	$id_statut=0;
	$id_statut_special=0;
	$actif=0;

	$lien="./php/ajout_mvt_statut.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_statuts
	where id_mvt_statut=".$id_mvt_statut.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_mvt_statut=mysqli_fetch_assoc($result);
	
	$titre='Modification d\'un mouvement de statut';
	$date_debut_statut=transformDate($tab_mvt_statut['date_debut_statut']);
	$date_echeance_statut=transformDate($tab_mvt_statut['date_echeance_statut']);
	
	$id_statut=$tab_mvt_statut['id_statut'];
	$id_statut_special=$tab_mvt_statut['id_statut_special'];
	$contractuel_nomme=$tab_mvt_statut['contractuel_nomme'];
	$actif=$tab_mvt_statut['actif'];
	
	$lien="./php/modif_mvt_statut.php";
}
$disabled='';

?><br>
<form id="FORM_MVT_STATUT" name="FORM_MVT_STATUT" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="4"><h3><?php echo $titre;?></h3></td>
</tr>

<tr>
	<td><?php echo dico("date_debut_statut?","F");?> : </td>
	
	<td><input type="text" id="date_debut_statut" name="date_debut_statut" size="20" value="<?php echo $date_debut_statut;?>" onchange="SetValue('modif_statut',1,'FORM_MVT_STATUT');" onkeyup="SetValue('modif_statut',1,'FORM_MVT_STATUT');" <?php echo $disabled;?>/>
	</td>
	
	<td><?php echo dico("date_echeance_statut","F");?> : </td>
	
	<td><input type="text" id="date_echeance_statut" name="date_echeance_statut" size="20" value="<?php echo $date_echeance_statut;?>" onchange="SetValue('modif_statut',1,'FORM_MVT_STATUT');" onkeyup="SetValue('modif_statut',1,'FORM_MVT_STATUT');" <?php echo $disabled;?>/>
	<br><br>
	</td>
	
</tr>


<tr>
		<td><?php echo dico("statut","F");?> : </td>
		<td>
		<select id="id_statut" name="id_statut" onchange="SetValue('modif_statut',1,'FORM_MVT_STATUT');" <?php echo $disabled;?>>
			<?php
			
			include('../arrays_libelle/array_statut.php');
			
			foreach($array_statut as $key => $value)
			{
				
				if($id_statut==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_statut[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_statut[$key]['F'].'</option>';
				}
			}
			
			?>
		</select>	
		</td>
		
		<td><?php echo dico("statut_special","F");?> : </td>
		<td>
		<select id="id_statut_special" name="id_statut_special" onchange="SetValue('modif_statut',1,'FORM_MVT_STATUT');" <?php echo $disabled;?>>
			<?php
			
			//include('../arrays_libelle/array_statut_special.php');
			
			foreach($array_statut_special as $key => $value)
			{
				
				if($id_statut_special==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_statut_special[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_statut_special[$key]['F'].'</option>';
				}
			}
			
			?>
		</select>	
		
		</td>
		
	</tr>
	
	<tr>
		<td><?php echo dico("contractuel/nomme","F");?> : </td>
		<td>
		<select id="contractuel_nomme" name="contractuel_nomme" onchange="SetValue('modif_statut',1,'FORM_MVT_STATUT');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_contractuel_nomme.php');
			foreach($array_contractuel_nomme as $key =>$value)
			{
				if($contractuel_nomme==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_contractuel_nomme[$key].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_contractuel_nomme[$key].'</option>';
				}
			}
			
			
			?>
		</select>
		</td>
		<td></td>
		<td><br><br></td>
	</tr>
<!--<tr>
	<td><?php echo dico("indiquer_mvt_actuel","F");?> : </td>
	<td>
	<select id="actif" name="actif" onchange="SetValue('modif_statut',1,'FORM_MVT_STATUT');" <?php echo $disabled;?>>
	<?php
			
		if($actif==0)
		{
			echo '<option value="0" selected>NON</option>';
			echo '<option value="1">OUI</option>';
		}
		else
		{
			echo '<option value="0">NON</option>';
			echo '<option value="1" selected>OUI</option>';
		}
		
	?>
	
	</select>
	</td>
</tr>-->
</table>

<input type="hidden" id="modif_statut" name="modif_statut" value="0"/>
<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<input type="hidden" id="id_contrat" name="id_contrat" value="<?php echo $id_contrat;?>" />
<input type="hidden" id="id_mvt_statut" name="id_mvt_statut" value="<?php echo $id_mvt_statut;?>" />
<input type="hidden" id="type_mvt" name="type_mvt" value="statuts" />

<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_MVT_STATUT');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_MVT_STATUT')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_STATUT').innerHTML='';" value="<?php echo dico("close","F");?>" />

</p>
</form>
