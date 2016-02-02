<?php
//ob_clean();
// header utf-8//
//header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

 //function de traduction
include_once('../tools/function_dico.php');
/*******************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	//verifier validité de $result
	if($result==null)
	{
		echo "pas de paramètre result";
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


 include('../connect_db.php');
 
/**********DEPARTEMENTS**********************************/					
	$sql="select * from cpas_departements order by label_F;
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}
	
    $tab_dep=fn_ResultToArray($result,'id_dep');
	
/**********HORS DEPARTEMENTS**********************************/	
	$sql="select * from cpas_hors_departements order by label_F;
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}
	
    $tab_hors_dep=fn_ResultToArray($result,'id_hors_dep');
	
	mysqli_close($lien);


include('../arrays_libelle/array_article_budgetaire.php');
include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');

if($id_mvt_service==0)
{
	$titre='Ajout d\'un mouvement de service';
	// vérifie s'il s'agit du 1er mvt
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_services
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
		
		$date_debut_service=transformDate($tab_contrat['start_date']);
		
	}
	else
	{
		$date_debut_service='00-00-0000';
	}

	mysqli_close($lien);
	
	$date_echeance_service='00-00-0000';
	//$art_budgetaire='';
	$id_article_budgetaire='';
	$id_hors_dep=0;
	$id_dep=0;
	$id_ser=0;
	$option_ser='';
	$disabled_ser='disabled';
	$id_cel=0;
	$option_cel='';
	$disabled_cel='disabled';
	$actif=0;
	$lien="./php/ajout_mvt_service.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_services
	where id_mvt_service=".$id_mvt_service.";
	";
		
	$result=mysqli_query($lien, $sql);

	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_mvt_ser=mysqli_fetch_assoc($result);
	
	$titre='Modification d\'un mouvement de service';
	$date_debut_service=transformDate($tab_mvt_ser['date_debut_service']);
	$date_echeance_service=transformDate($tab_mvt_ser['date_echeance_service']);
	//$art_budgetaire=$tab_mvt_ser['article_budgetaire'];
	$id_article_budgetaire=$tab_mvt_ser['id_article_budgetaire'];
	$id_dep=$tab_mvt_ser['id_dep'];
	$id_hors_dep=$tab_mvt_ser['id_hors_dep'];
	$id_ser=$tab_mvt_ser['id_ser'];
	$id_cel=$tab_mvt_ser['id_cel'];
	$option_ser='<option value="'.$id_ser.'" >'.$array_service[$id_ser]['F'].'</option>';
	$option_cel='<option value="'.$id_cel.'" >'.$array_cellule[$id_cel]['F'].'</option>';
	//$onchange_ser="FiltreSerCel('id_dep','$id_dep','FORM_MVT_SERVICE');";
	$disabled_ser='disabled';
	$disabled_cel='disabled';
	$actif=$tab_mvt_ser['actif'];
	
	$lien="./php/modif_mvt_service.php";
}
$disabled='';
?><br>
<form id="FORM_MVT_SERVICE" name="FORM_MVT_SERVICE" style="border:3px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="2"><h3><?php echo $titre;?></h3></td>
</tr>


<tr>
	<td width="150px"><?php echo dico("date_debut_service?","F");?> : </td>
	<td>
	 
	<input type="text" id="date_debut_service" name="date_debut_service" size="18" value="<?php echo $date_debut_service;?>" onchange="SetValue('modif_service',1,'FORM_MVT_SERVICE');" onkeyup="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>/>
	<?php echo '<span style="margin-left:18px;">'.dico("date_echeance_service","F").'</span>';?> : 
	<input type="text" id="date_echeance_service" name="date_echeance_service" size="18" value="<?php echo $date_echeance_service;?>" onkeyup="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>/>
	<br><br>
	</td>
	
</tr>

<!--<tr>

	<td><?php echo dico("art_budgetaire(s)","F");?> : </td>
	<td>
	
	<input type="text" id="art_budgetaire" name="art_budgetaire" style="width:97%" value="<?php echo $art_budgetaire;?>" onkeyup="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>/>
	<br><br>
	</td>

</tr>-->
<tr>

	<td><?php echo dico("art_budgetaire(s)","F");?> : </td>
	<td>
		<!--<input type="text" id="id_article_budgetaire" name="id_article_budgetaire" style="width:97%" value="<?php echo $id_article_budgetaire;?>" onkeyup="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>/>
	-->
	<select id="id_article_budgetaire" name="id_article_budgetaire" style="width:97%" onchange="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>>
		<?php
			
			foreach($array_article_budgetaire as $key => $value)
			{
				if($id_article_budgetaire==$key)
				{
					echo '<option value="'.$key.'" selected>'.$array_article_budgetaire[$key]['F'].'</option>';
				}
				else
				{
					echo '<option value="'.$key.'">'.$array_article_budgetaire[$key]['F'].'</option>';
				}
			}
		?>
		
		</select>
	<br><br>
	</td>

</tr>

<tr>

	<td><?php echo dico("choisir_groupe","F");?> : </td>
	<td>
	
		<select id="choix_groupe" name="choix_groupe" width="200px" onchange="DisplayChoixDep('FORM_MVT_SERVICE',this.value);SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>>
			<option value="DEP" style="background-color:#e4f8d2;" selected>Groupe département</option>
			<option value="HORS_DEP" style="background-color:#ffc477;">Groupe hors-département</option>
		</select>
	</td>

</tr>
</table>	
<!-- GROUP DEP/HORS DEP-->
<div id="GROUP_DEP" style="visibility:visible;">
	<table>	
	<tr>

		<td width="150px"><span style="background-color:#e4f8d2;"><?php echo dico("id_dep","F");?> : </span></td>
		<td width="520px">
		<select id="id_dep" name="id_dep" style="width:100%" onchange="FiltreSerCel('id_dep',this.value,'FORM_MVT_SERVICE');SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>>
		<?php
			
			//foreach($array_departement as $key => $value)
			echo '<option value="0" selected>---</option>';
			
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
			<select id="id_ser" name="id_ser" style="width:100%" onchange="FiltreSerCel('id_ser',this.value,'FORM_MVT_SERVICE');SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled_ser;?>>
			<?php
				echo $option_ser;
				
			?>
			
			</select>
			</td>
		</tr>
		
		<tr>
			<td><span style="background-color:#e4f8d2;"><?php echo dico("id_cel","F");?> : </span></td>
			<td>
			<select id="id_cel" name="id_cel" style="width:100%" onchange="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled_cel;?>>
			<?php
				echo $option_cel;
				
			?>
			
			</select>
			<br><br>
			</td>
		</tr>
	</table>	
</div>
<div id="GROUP_HORS_DEP" style="visibility:hidden;">
<table>	
	<tr>

		<td width="150px"><span style="background-color:#ffc477;"><?php echo dico("id_hors_dep","F");?> : </td>
		<td width="520px" >
		<select id="id_hors_dep" name="id_hors_dep" style="width:100%" onchange="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>>
		<?php
			echo '<option value="0" selected>---</option>';
			//foreach($array_hors_departement as $key => $value)
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

<!--
<table>	
	<tr>
		<td><?php echo dico("indiquer_mvt_actuel","F");?> : </td>
		<td>
		<select id="actif" name="actif" onchange="SetValue('modif_service',1,'FORM_MVT_SERVICE');" <?php echo $disabled;?>>
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
	</tr>
</table>-->

<input type="hidden" id="modif_service" name="modif_service" value="0"/>
<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<input type="hidden" id="id_contrat" name="id_contrat" value="<?php echo $id_contrat;?>" />
<input type="hidden" id="id_mvt_service" name="id_mvt_service" value="<?php echo $id_mvt_service;?>" />
<input type="hidden" id="type_mvt" name="type_mvt" value="services" />
<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_MVT_SERVICE');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_MVT_SERVICE')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_SER').innerHTML='';" value="<?php echo dico("close","F");?>" />

</p>
</form>
