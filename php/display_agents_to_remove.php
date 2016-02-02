<?php
//echo 'coucou';
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include('../includes/php_linguistique.php');
 //function de traduction
include_once('../tools/function_dico.php');

include('params.php');

/*******params**********************/
if(isset($_GET['nom_champ']))
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
}

/*if(isset($_GET['timeStamp']))
{
	$timeStamp=$_GET['timeStamp'];
	
}else{
	if(isset($_POST['timeStamp']))
	{
		$timeStamp=$_POST['timeStamp'];
	}else{
		$timeStamp=0;
	}
}*/

/*****************Fonction qui met le résultat de la requête dans un tableau******************************************************************************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	$tableau=null;
	/*verifier validité de $result*/
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


echo "
<style>
.surligne
{

background-color:none;
}

.surligne:hover
{
	background-color:#666;
}

.style_list_agents
{
font-size:11pt;
color:#666;
font-weight:bold;
margin-top:5px;
margin-bottom:5px;
}


#DIV_CLOSE_PANNEL #box_close 
{
		
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
	
	#DIV_CLOSE_PANNEL #box_close .head 
	{
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
";
/********/
?>
<div align="right">
	<div id="DIV_CLOSE_PANNEL" onmouseover="DisplayTitleClose();" onmouseout="document.getElementById('box_close').style.visibility='hidden';" style="padding-top:2px;margin-right:10px;margin-top:10px;cursor:default;font-size:15pt;font-family:Verdana;font-weight:bold;color:white;height:25px;width:27px;text-align:center;background-color:#3d3d3d;border:2px solid #ccc;" onclick="CloseToLeft('modal_externe',100,0);">
	X<div id="box_close" style="visibility:hidden;z-index:500;font-size:10pt;"><div class="head">box</div></div>
	</div>
	<!--<div id="title" style="visibility:hidden;">aaaa</div>-->
	
	
</div>
<h1>Déplacer des agents vers un autre service</h1><br>
<?php
include('../connect_db.php');

if($nom_champ=="id_dep")
{
	$flag='cpas_contrats.flag_resp_dep=1';
}	
else
{
	$flag='cpas_contrats.flag_resp_ser=1';
}



$sql="
SELECT * FROM `cpas_agents`
 join `cpas_contrats`
 on cpas_agents.id_agent=cpas_contrats.id_agent
 WHERE
 cpas_contrats.".$nom_champ."=".$valeur_champ." and ".$flag." and cpas_contrats.actif=1 ;

";

$result=mysqli_query($lien, $sql);


$affichage_resp='';

if(mysqli_num_rows($result)==0)
{
	$affichage_resp= '<span>Pas de responsable connu</span><br><br>';
	
	//exit;
	//mysqli_close($lien);
}
else
{
	//echo '<div>Responsable : ';
	$tab_resp=mysqli_fetch_assoc($result);
	
	$affichage_resp= "<span style='margin-left:15px;'><input type='checkbox' id='bnt[".$tab_resp['id_agent']."]' name='bnt[".$tab_resp['id_agent']."]' value='".$tab_resp['id_contrat']."' onclick='alert(".$tab_resp['id_agent'].','.$tab_resp['id_contrat'].");' />".$tab_resp['nom'].' '.$tab_resp['prenom']." (Responsable)</span><br><br>";
			
}

mysqli_close($lien);


/*****************************************************************/

include('../connect_db.php');



$sql="
SELECT * FROM `cpas_agents`
 join `cpas_contrats`
 on cpas_agents.id_agent=cpas_contrats.id_agent
 WHERE
 cpas_contrats.".$nom_champ."=".$valeur_champ." 
 and cpas_contrats.actif=1 
 and cpas_contrats.flag_resp_dep=0 
 and cpas_contrats.flag_resp_ser=0 
 order by cpas_agents.nom;
";

$result=mysqli_query($lien, $sql);

//echo $sql.'<br>';
if(mysqli_num_rows($result)==0)
{
	echo '<div style="overflow-y:auto;overflow-x:hidden;height:110px;">';
	echo "Pas d'agent existant";
	echo '</div>';

	exit;
}

$tab_personnel=fn_ResultToArray($result,'id_agent');

/***************DEPARTEMENTS************************************/
$sql="
SELECT * FROM `cpas_departements` order by label_F;
";

$result=mysqli_query($lien, $sql);

//echo $sql.'<br>';
if(mysqli_num_rows($result)==0)
{
	
	echo "No result";
	
	exit;
}
$tab_dep=fn_ResultToArray($result,'id_dep');

/***************HORS DEPARTEMENTS************************************/
$sql="
SELECT * FROM `cpas_hors_departements` order by label_F;
";

$result=mysqli_query($lien, $sql);

//echo $sql.'<br>';
if(mysqli_num_rows($result)==0)
{
	
	echo "No result";
	
	exit;
}
$tab_hors_dep=fn_ResultToArray($result,'id_hors_dep');

mysqli_close($lien);




include('../arrays_libelle/array_article_budgetaire.php');
//include('../arrays_libelle/array_departement.php');
//include('../arrays_libelle/array_hors_departement.php');
include('../arrays_libelle/array_service.php');
include('../arrays_libelle/array_cellule.php');

//echo date('Y-m-d').'<br>';
$disabled='';
?>

<form id="FORM_AGENTS_REMOVE" name="FORM_AGENTS_REMOVE" action="./php/remove_agents.php" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
<?php
	echo $affichage_resp;
	
	echo '<div style="width:80%;overflow-y:auto;overflow-x:hidden;height:270px;">';

	foreach($tab_personnel as $key=>$value)
	{
		if($value['flag_resp_ser']==1)
		{
			$style='style="color:#fd0000;background-color:#999;font-size:11pt;"';
		
		}
		else
		{
			$style='style="background-color:#999;"';
			
		}
		echo "<span style='margin-left:15px;'><input type='checkbox' id='bnt[".$value['id_agent']."]' name='bnt[".$value['id_agent']."]' onclick=\"SetColorButton('FORM_AGENTS_REMOVE');\" onchange=\"SetColorButton('FORM_AGENTS_REMOVE');\" onkeyup=\"SetColorButton('FORM_AGENTS_REMOVE');\" value='".$value['id_contrat']."' />".$value['nom'].' '.$value['prenom']."</span><br>";
								
		
	}
	echo '</div><br>';
	/***********************/
	?>
	<span style="font-weight:bold;">Déplacer les agents vers : </span><br><br>
	<table>
	
	<tr>
	<td width="150px"><?php echo dico("date_debut_service?","F");?> : </td>
	<td>
	 
	<input type="text" id="date_debut_service" name="date_debut_service" size="18" value="<?php echo $date_debut_service;?>" onchange="SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" onkeyup="SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" <?php echo $disabled;?>/>
	<?php echo '<span style="margin-left:18px;">'.dico("date_echeance_service","F").'</span>';?> : 
	<input type="text" id="date_echeance_service" name="date_echeance_service" size="18" value="<?php echo $date_echeance_service;?>" onkeyup="SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" <?php echo $disabled;?>/>
	<br><br>
	</td>
	
	</tr>

	<tr>

		<td><?php echo dico("art_budgetaire(s)","F");?> : </td>
		<td>
		
		
		<select id="id_article_budgetaire" name="id_article_budgetaire" style="width:97%" onchange="SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" <?php echo $disabled;?>>
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

	<td width="150px"><?php echo dico("choisir_groupe","F");?> : </td>
	<td width="520px">
	
		<select id="choix_groupe" name="choix_groupe" width="200px" onchange="DisplayChoixDep('FORM_AGENTS_REMOVE',this.value);SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" <?php echo $disabled;?>>
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
		<select id="id_dep" name="id_dep" style="width:100%" onchange="FiltreSerCel('id_dep',this.value,'FORM_AGENTS_REMOVE');SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" <?php echo $disabled;?>>
		<?php	
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
			<select id="id_ser" name="id_ser" style="width:100%" onchange="FiltreSerCel('id_ser',this.value,'FORM_AGENTS_REMOVE');SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" disabled >
			
			</select>
			</td>
		</tr>
		
		<tr>
			<td><span style="background-color:#e4f8d2;"><?php echo dico("id_cel","F");?> : </span></td>
			<td>
			<select id="id_cel" name="id_cel" style="width:100%" onchange="SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" disabled >
			
			
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
		
		<select id="id_hors_dep" name="id_hors_dep" style="width:100%" onchange="SetValue('modif_service',1,'FORM_AGENTS_REMOVE');" <?php echo $disabled;?>>
		<?php
			
			
			echo '<option value="0" selected>---</option>';
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
<br>
<p align="center">
	<input type="hidden" id="modif_service" name="modif_service" value="0" />

	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_AGENTS_REMOVE');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_AGENTS_REMOVE')" value="<?php echo dico("reset","F");?>" />
</p>	
</form>
