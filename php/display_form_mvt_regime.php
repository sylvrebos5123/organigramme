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
		
	}
	return $date_result;
}



include('params.php');

if($id_mvt_regime==0)
{
	$titre="Ajout d'un mouvement de régime";
	
	// vérifie s'il s'agit du 1er mvt
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_regimes
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
		
		$date_debut_regime=transformDate($tab_contrat['start_date']);
		
	}
	else
	{
		$date_debut_regime='00-00-0000';
	}

	mysqli_close($lien);

	$date_echeance_regime="00-00-0000";
	$id_equiv_tp='';
	$id_regime=0;
	$actif=0;
	$lien="./php/ajout_mvt_regime.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_regimes
	where id_mvt_regime=".$id_mvt_regime.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_mvt_regime=mysqli_fetch_assoc($result);
	
	$titre='Modification d\'un mouvement de régime';
	$date_debut_regime=transformDate($tab_mvt_regime['date_debut_regime']);
	$date_echeance_regime=transformDate($tab_mvt_regime['date_echeance_regime']);
	
	$id_regime=$tab_mvt_regime['id_regime'];
	$id_equiv_tp=$tab_mvt_regime['id_equiv_tp'];
	$actif=$tab_mvt_regime['actif'];
	
	$lien="./php/modif_mvt_regime.php";
}
$disabled='';

?><br>
<form id="FORM_MVT_REGIME" name="FORM_MVT_REGIME" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="4"><h3><?php echo $titre;?></h3></td>
</tr>

<tr>
	<td><?php echo dico("date_debut_regime?","F");?> : </td>
	
	<td><input type="text" id="date_debut_regime" name="date_debut_regime" size="20" value="<?php echo $date_debut_regime;?>" onchange="SetValue('modif_regime',1,'FORM_MVT_REGIME');" onkeyup="SetValue('modif_regime',1,'FORM_MVT_REGIME');" <?php echo $disabled;?>/>
	</td>
	
	<td><?php echo dico("date_echeance_regime","F");?> : </td>
		<td>
		<input type="text" id="date_echeance_regime" name="date_echeance_regime" size="20" value="<?php echo $date_echeance_regime;?>" onchange="SetValue('modif_regime',1,'FORM_MVT_REGIME');"/>
		<br><br>
		</td>
</tr>


<tr>
	
		<td><?php echo dico("regime","F");?> : </td>
		<td>
		<select id="id_regime" name="id_regime" onchange="SetValue('modif_regime',1,'FORM_MVT_REGIME');SetValueEquivTP('FORM_MVT_REGIME');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_regime.php');
			foreach($array_regime as $key => $value)
			{	
				if($id_regime==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_regime[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_regime[$key]['F'].'</option>';
				}
			}
			
			?>
		</select>	
		</td>
		
	
			
		<td><?php echo dico("equiv_tp","F");?> : </td>
		<td>
		
		<?php
			echo '<input type="text" id="id_equiv_tp" name="id_equiv_tp" value="'.$id_equiv_tp.'" '.$disabled.'/>';
			
		?>
		<!--
		<select id="id_equiv_tp" name="id_equiv_tp" onchange="SetValue('modif_regime',1,'FORM_MVT_REGIME');" <?php echo $disabled;?>>
			<?php
			
			include('../arrays_libelle/array_equivalent_temps_plein.php');
			foreach($array_equivalent_temps_plein as $key => $value)
			{
				
				if($id_equiv_tp==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_equivalent_temps_plein[$key].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_equivalent_temps_plein[$key].'</option>';
				}
			}
			
			?>
		</select>	-->
		<br><br>
		</td>
	</tr>
	<!--<tr>
		<td><?php echo dico("indiquer_mvt_actuel","F");?> : </td>
		<td>
		<select id="actif" name="actif" onchange="SetValue('modif_regime',1,'FORM_MVT_REGIME');" <?php echo $disabled;?>>
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

<input type="hidden" id="modif_regime" name="modif_regime" value="0"/>
<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<input type="hidden" id="id_contrat" name="id_contrat" value="<?php echo $id_contrat;?>" />
<input type="hidden" id="id_mvt_regime" name="id_mvt_regime" value="<?php echo $id_mvt_regime;?>" />
<input type="hidden" id="type_mvt" name="type_mvt" value="regimes" />
<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_MVT_REGIME');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_MVT_REGIME')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_REGIME').innerHTML='';" value="<?php echo dico("close","F");?>" />

</p>
</form>
