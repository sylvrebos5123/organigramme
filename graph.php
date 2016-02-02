<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
	
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

/***************Lecture table agents********************************/
/*include('connect_db.php');
	
$sql="
SELECT * FROM cpas_agents where (flag_resp_dep=1 or flag_resp_ser=1) and actif=1;
";

$result=mysqli_query($lien, $sql);


mysqli_close($lien);

$tab_resp=fn_ResultToArray($result,'id_agent');

$tab_display_resp_cel=array();
$tab_display_resp_ser=array();
$tab_display_resp_dep=array();

foreach($tab_resp as $key=>$value)
{
	$tab_display_resp_cel[$value['id_dep']]='';
	if($value['flag_resp_dep']==1)
	{
		$tab_display_resp_dep[$value['id_dep']]=$value['nom'].' '.$value['prenom'];
	}
	
	$tab_display_resp_ser[$value['id_ser']]='';
	if($value['flag_resp_ser']==1)
	{
		
		$tab_display_resp_ser[$value['id_ser']]=$value['nom'].' '.$value['prenom'];
		
		$tab_display_resp_cel[$value['id_cel']]='';
		if(($value['id_cel']!='')||($value['id_cel']!=null))
		{	
			$tab_display_resp_cel[$value['id_cel']]=$value['nom'].' '.$value['prenom'];
		}
	}
	
}*/

/**************/

function displayResponsable($nom_champ,$valeur_champ)
{
	$html='';
	
	
	$html.=$nom_champ.','.$valeur_champ;
	return $html;
	
}

/******HORS DEP*********/
	include('connect_db.php');
	
	$sql="
	SELECT * FROM cpas_hors_departements where actif=1 order by indice_ordre;
	";
	
	$result=mysqli_query($lien, $sql);
	
	//$nb_rec_dep=mysqli_num_rows($result);
	
	//mysqli_close($lien);
	
	$tab_hors_dep=fn_ResultToArray($result,'id_hors_dep');

/******DEP*********/
	//include('connect_db.php');
	
	$sql="
	SELECT * FROM cpas_departements where label_F <> 'Secrétaire' and actif=1 order by indice_ordre;
	";
	
	$result=mysqli_query($lien, $sql);
	
	$nb_rec_dep=mysqli_num_rows($result);
	
	//mysqli_close($lien);
	
	$tab_dep=fn_ResultToArray($result,'id_dep');
	/*********************/
	//include('connect_db.php');
	
	$sql="
	SELECT * FROM cpas_services where actif=1 order by label_F;
	";
	
	$result=mysqli_query($lien, $sql);
	
	//mysqli_close($lien);
	
	$tab_ser=fn_ResultToArray($result,'id_ser');
	/*********/
	//include('connect_db.php');
	
	$sql="
	SELECT * FROM cpas_cellules where actif=1 order by id_ser,label_F;
	";
	
	$result=mysqli_query($lien, $sql);
	
	
	
	mysqli_close($lien);
	
	$tab_cel=fn_ResultToArray($result,'id_cel');
	/***********/



//echo $nb_rec_dep.'<br>';
$nb_col='col'.$nb_rec_dep;

/***********Insertion des cellules-services dans un tableau avec l'id-ser comme index**********************************************************************/
foreach($tab_cel as $key_cel=>$value_cel)
{
	
	if(isset($tab_display_cel[$value_cel['id_ser']])==false)
	{
		$tab_display_cel[$value_cel['id_ser']]=array();
		$tab_display_cel[$value_cel['id_ser']]='';
	}
	
	//$tab_display_cel[$value_cel['id_ser']].='<li><a class="info" onclick="DisplayPersonnel(\'id_cel\','.$value_cel['id_cel'].');" onmouseover="DisplayResponsable(\'id_cel\','.$value_cel['id_cel'].');">'.$value_cel['label_F'].'<span id="info_id_cel_'.$value_cel['id_cel'].'"></span></a></li>';
	
	$tab_display_cel[$value_cel['id_ser']].='<li id="info_id_cel_'.$value_cel['id_cel'].'"><a class="info" onclick="DisplayResponsable(\'id_cel\','.$value_cel['id_cel'].');">'.$value_cel['label_F'].'</a></li>';
	
	$tab_display_cel[$value_cel['id_ser']].='<div id="DIV_id_cel_'.$value_cel['id_cel'].'" style="background-color:white;"></div>';
}
/******/

	
?>
<div class="sitemap">		

		<br><br><br>
		
		<ul id="primaryNav" class="<?php echo $nb_col;?>">
			<li id="home" align="center" ><div class="style_top_graph">CPAS</div></li>
			
			
			
			<li><div class="style_top_graph">Président</div>
				<ul>
					<li></li>
					<li></li>
					<li></li>
					
					<li>
						<div class="bnt_dep" title="Ajout d'un département" onclick="LoadFormDepartement(0);">+</div>
					</li>

						
				</ul>
				
			</li>
					
			<!--<li ><div class="style_top_graph">Secrétaire</div>
				<ul>
					<li></li>
					
				</ul>
			</li>-->
			<li align="center">
				<div  class="bnt_hors_dep" title="Ajout d'un service hors-département" onclick="LoadFormHorsDepartement(0);">+</div>
			
			</li>
			<?php
			// vérifie combien de cases hors dep il peut y avoir à la 1ère ligne
			$limite=$nb_rec_dep - 2;
			$nb_hors_dep=0;
			
			foreach($tab_hors_dep as $key_hors_dep => $value_hors_dep)
			{
				$nb_hors_dep++;
				
				echo '<div id="DIV_id_hors_dep_'.$value_hors_dep['id_hors_dep'].'" style="margin-top:-5px;margin-left:20px;background-color:white;z-index:30;"></div>';
				echo '<li><div style="height:auto;cursor:default;"  
				id="info_id_hors_dep_'.$value_hors_dep['id_hors_dep'].'"
				onmouseover="document.getElementById(\'bnt_edit_del_hors_dep_'.$value_hors_dep['id_hors_dep'].'\').style.visibility=\'visible\';" onmouseout="document.getElementById(\'bnt_edit_del_hors_dep_'.$value_hors_dep['id_hors_dep'].'\').style.visibility=\'hidden\';" >
				<a class="style_hors_dep" onclick="DisplayResponsable(\'id_hors_dep\','.$value_hors_dep['id_hors_dep'].');">'.$value_hors_dep['label_F'].'</a>';
				
					echo '<div id="bnt_edit_del_hors_dep_'.$value_hors_dep['id_hors_dep'].'" style="cursor:default;visibility:hidden;height:23px;margin-top:2px;padding-left:10px;width:70px;">';
						//echo '<span class="bnt_ajout_ser" title="Ajouter un service" onclick="LoadFormService(0,\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
						echo '<span class="bnt_modif" title="Modifier ce service hors-département"  onclick="LoadFormHorsDepartement(\''.$value_hors_dep['id_hors_dep'].'\');">&nbsp;</span>';
						echo '<span class="bnt_suppr" title="Supprimer ce service hors-département" onclick="SupprHorsDepartement(\''.$value_hors_dep['id_hors_dep'].'\');">&nbsp;</span>';
						
					echo ' </div>';
				echo '</div>';
			
				echo '</li>';
				//echo $nb_col.' - ';
				//echo $limite;
				if(($nb_hors_dep % $limite)==0)
				{
					echo '<li style="background:none;"><div style="height:auto;cursor:default;"></div></li>';
				} 
				
			}
			?>
			
			
			<div id="box_hors_dep" style="visibility:hidden;z-index:300;"><div class="head"></div></div>
		</ul>
		

		<ul id="primaryNav" class="<?php echo $nb_col;?>">
			
			<!--<li id="home" class="style_hors_dep"><a href="">Président</a></li>
			<br>-->
			<!--<img src="images/vertical-line.png" />-->
			<!--<li id="home" ><a href="">Secrétaire</a></li>
			-->
			
			<?php 
			
			foreach($tab_dep as $key_dep=>$value_dep)
			{
				echo '<div id="DIV_id_dep_'.$value_dep['id_dep'].'" style="margin-top:-5px;margin-left:20px;background-color:white;z-index:30;"></div>';
				echo '<li>';
				echo '<div style="height:auto;padding:10px;" id="info_id_dep_'.$value_dep['id_dep'].'"
				onmouseover="document.getElementById(\'bnt_edit_del_dep_'.$value_dep['id_dep'].'\').style.visibility=\'visible\';" onmouseout="document.getElementById(\'bnt_edit_del_dep_'.$value_dep['id_dep'].'\').style.visibility=\'hidden\';" >
				<a class="info" alt="111" onclick="DisplayResponsable(\'id_dep\','.$value_dep['id_dep'].');">'.$value_dep['label_F'].'</a>';
						echo '<div id="bnt_edit_del_dep_'.$value_dep['id_dep'].'" style="cursor:default;visibility:hidden;height:23px;margin-top:2px;padding-left:10px;margin-left:0px;width:70px;">';
						//echo '<span title="Ajout d\'un service" onclick="LoadFormService(0,\''.$value_dep['id_dep'].'\');">+</span>';
						echo '<span class="bnt_ajout_ser" title="Ajouter un service" onclick="LoadFormService(0,\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
						echo '<span class="bnt_modif" title="Modifier ce département"  onclick="LoadFormDepartement(\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
						echo '<span class="bnt_suppr" title="Supprimer ce département" onclick="SupprDepartement(\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
						
						//echo 'Edit - Del';
						echo ' </div>';
				echo '</div>';
				//echo '<br>';
					echo "<ul>";
					//bnt ajout service
					/*echo '<li><div class="bnt_ser" title="Ajout d\'un service" onclick="LoadFormService(0,\''.$value_dep['id_dep'].'\');">+</div>
					</li>';*/
						foreach($tab_ser as $key_ser=>$value_ser)
						{
							
							if($value_dep['id_dep']==$value_ser['id_dep'])
							{
								echo '<div id="DIV_id_ser_'.$value_ser['id_ser'].'" style="margin-top:-5px;margin-left:20px;background-color:white;z-index:30;"></div>';
								echo '<li>
								<div id="info_id_ser_'.$value_ser['id_ser'].'" 
								onmouseover="document.getElementById(\'bnt_edit_del_ser_'.$value_ser['id_ser'].'\').style.visibility=\'visible\';" onmouseout="document.getElementById(\'bnt_edit_del_ser_'.$value_ser['id_ser'].'\').style.visibility=\'hidden\';" >
								<a class="info"  onclick="DisplayResponsable(\'id_ser\','.$value_ser['id_ser'].');" >'.$value_ser['label_F'].'</a>';
								//echo '<a class="info" onclick="DisplayDivCellules('.$value_ser['id_ser'].');">'.$value_ser['label_F'].'<span id="info_id_ser_'.$value_ser['id_ser'].'"></span></a>';
									echo '<div id="bnt_edit_del_ser_'.$value_ser['id_ser'].'" style="cursor:default;visibility:hidden;height:30px;">';
									//echo 'Edit - Del';
									echo '<span class="bnt_ajout_cel" title="Ajouter une cellule" onclick="LoadFormCellule(0,\''.$value_ser['id_ser'].'\',\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
									//echo '<span class="surligne" onclick="LoadFormCellule(0,'.$valeur_champ.','.$tab_ser['id_dep'].');">Ajout d\'une cellule</span>';
	
									echo '<span class="bnt_modif" title="Modifier ce service"  onclick="LoadFormService(\''.$value_ser['id_ser'].'\',\''.$value_dep['id_dep'].'\');">&nbsp;</span>';
									
									echo '<span class="bnt_suppr" title="Supprimer ce service" onclick="SupprService(\''.$value_ser['id_ser'].'\');">&nbsp;</span>';
						
									echo ' </div>';
								echo '</div>';
								
						//Cellules appartenant au service
								//echo '<div id="CEL_'.$value_ser['id_ser'].'" style="visibility:hidden;height:0px;">';
								if(array_key_exists($value_ser['id_ser'],$tab_display_cel))
								{
								
									//echo '<br>';
									echo '<div id="display_cel'.$value_ser['id_ser'].'" align="center" class="bnt_plus" onclick="DisplayDivCellules('.$value_ser['id_ser'].');">Afficher les cellules</div>';
									echo '<div id="CEL_'.$value_ser['id_ser'].'" style="visibility:hidden;height:0px;"></div>';
								}
								//echo '</div>';	
									
								echo '</li>';
								
							}
						}//fin foreach SERVICES
					echo "</ul>";
				echo '</li>';
				
			}//fin foreach DEPARTEMENTS
			?>
			
			<div id="box" style="visibility:hidden;z-index:300;"><div class="head"></div></div>
		</ul>
			
	</div>
	
