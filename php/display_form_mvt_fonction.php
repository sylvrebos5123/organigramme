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


include('../arrays_libelle/array_fonction.php');
if($id_mvt_fonction==0)
{
	$titre="Ajout d'un mouvement de fonction";
	$id_fonc=0;
	$flag_resp_dep=0;
	$flag_resp_ser=0;
	$autre_type_fonc="";
	$ouvrier_employe="E";
	$categorie="Autre";
	
	// vérifie s'il s'agit du 1er mvt
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_fonctions
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
		
		$date_debut_fonction=transformDate($tab_contrat['start_date']);
		
	}
	else
	{
		$date_debut_fonction='00-00-0000';
	}

	mysqli_close($lien);
	
	$date_echeance_fonction='00-00-0000';
	$actif=0;
	$lien="./php/ajout_mvt_fonction.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_fonctions
	where id_mvt_fonction=".$id_mvt_fonction.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_mvt_fonction=mysqli_fetch_assoc($result);
	
	$titre='Modification d\'un mouvement de fonction';
	$date_debut_fonction=transformDate($tab_mvt_fonction['date_debut_fonction']);
	$date_echeance_fonction=transformDate($tab_mvt_fonction['date_echeance_fonction']);
	$id_fonc=$tab_mvt_fonction['id_fonc'];
	$flag_resp_dep=$tab_mvt_fonction['flag_resp_dep'];
	$flag_resp_ser=$tab_mvt_fonction['flag_resp_ser'];
	$autre_type_fonc="";
	$ouvrier_employe=$tab_mvt_fonction['ouvrier_employe'];
	$categorie=$tab_mvt_fonction['categorie'];
	$actif=$tab_mvt_fonction['actif'];
	
	$lien="./php/modif_mvt_fonction.php";
}
$disabled='';
?><br>
<form id="FORM_MVT_FONCTION" name="FORM_MVT_FONCTION" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="4"><h3><?php echo $titre;?></h3></td>
</tr>

<tr>
	<td><?php echo dico("date_debut_fonction?","F");?> : </td>
	<td>
	
	<input type="text" id="date_debut_fonction" name="date_debut_fonction" size="20" value="<?php echo $date_debut_fonction;?>" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" onkeyup="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>/>
	</td>
	<td>
	<?php echo '<span style="margin-left:15px;">'.dico("date_echeance_fonction","F").'</span>';?> : 
	</td>
	<td>
	<input type="text" id="date_echeance_fonction" name="date_echeance_fonction" size="20" value="<?php echo $date_echeance_fonction;?>" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" onkeyup="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>/>
	<br><br>
	</td>
<tr>	
	<td>
	<?php echo '<span style="background-color:#e4f8d2;">'.dico("flag_resp_dep","F").'</span>';?> : 
	</td>
	<td>
	<select id="flag_resp_dep" name="flag_resp_dep" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>>
			<?php
			if(($flag_resp_dep==0)||($flag_resp_dep=='')||($flag_resp_dep==null))
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

	<td>
		<?php echo '<span style="margin-left:15px;background-color:#fff1cd;">'.dico("flag_resp_ser","F").'</span>';?> : 
	</td>
	<td>
		<select id="flag_resp_ser" name="flag_resp_ser" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>>
			<?php
			if(($flag_resp_ser==0)||($flag_resp_ser=='')||($flag_resp_ser==null))
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
</tr>


	
	
	<tr>
		<td><?php echo dico("id_fonc","F");?> : </td>
		<td colspan="3">
		<select id="id_fonc" name="id_fonc" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');ApparaitreAutreFonction('FORM_MVT_FONCTION','id_fonc');" <?php echo $disabled;?>>
		<?php
		
			foreach($array_fonction as $key => $value)
			{
				//$new_key=str_replace('"', '', $key);
				
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
		
		</select>
		<!--
		<input type="hidden" id="autre_type_fonc" name="autre_type_fonc" value="<?php echo $autre_type_fonc;?>" onkeyup="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');"<?php echo $disabled;?>/>
		-->
		<input type="text" id="autre_type_fonc" name="autre_type_fonc" style="visibility:hidden;" value="<?php echo $autre_type_fonc;?>" onkeyup="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');"<?php echo $disabled;?>/>
		
		</td>
		
		<!--<td><?php echo dico("autre_type_fonc","F");?> </td>-->
		<td>
		</td>
	</tr>

	
	
	<tr>
		<td><?php echo dico("ouvrier/employe","F");?> : </td>
		<td>
		<select id="ouvrier_employe" name="ouvrier_employe" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_ouvrier_employe.php');
			foreach($array_ouvrier_employe as $key =>$value)
			{
				if($ouvrier_employe==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_ouvrier_employe[$key].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_ouvrier_employe[$key].'</option>';
				}
			}
			
			
			?>
		</select>
		</td>
		<td>
		<span style="margin-left:12px;"><?php echo dico("categorie","F");?> : </span>
		</td>
		<td>
		<select id="categorie" name="categorie" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>>
			<?php
			include('../arrays_libelle/array_categorie.php');
			for($i=0;$i<count($array_categorie);$i++)
			{
				if($categorie==$array_categorie[$i])
				{
					echo '<option value="'.$array_categorie[$i].'" selected>'.$array_categorie[$i].'</option>';
				}
				else
				{
					echo '<option value="'.$array_categorie[$i].'">'.$array_categorie[$i].'</option>';
				}
			}
			
			?>
		</select>
		<br><br>
		</td>
		
	</tr>
	<!--
	<tr>
		<td><?php echo dico("indiquer_mvt_actuel","F");?> : </td>
		<td>
		<select id="actif" name="actif" onchange="SetValue('modif_fonction',1,'FORM_MVT_FONCTION');" <?php echo $disabled;?>>
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

<input type="hidden" id="modif_fonction" name="modif_fonction" value="0"/>
<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<input type="hidden" id="id_contrat" name="id_contrat" value="<?php echo $id_contrat;?>" />
<input type="hidden" id="id_mvt_fonction" name="id_mvt_fonction" value="<?php echo $id_mvt_fonction;?>" />
<input type="hidden" id="type_mvt" name="type_mvt" value="fonctions" />
<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_MVT_FONCTION');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_MVT_FONCTION')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_FCT').innerHTML='';" value="<?php echo dico("close","F");?>" />
</p>
</form>
