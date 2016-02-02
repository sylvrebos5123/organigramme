<?php
//ob_clean();
// header utf-8//
//header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };
//$dico_script='/annuaire/tools/php_dico_array.php';
//include($rootpath.'\\organigramme\\tools\\pba_tools.php'); //function de connection
//include($rootpath.'\\organigramme/php/genere_list_form5.php');
//include($rootpath.$dico_script); 
//function de traduction
 //function de traduction
include_once('../tools/function_dico.php');



	include('params.php');



	include('../connect_db.php');
					
	$sql="select * from cpas_anciennetes
	where id_agent=".$id_agent.";
	";
		
	$result=mysqli_query($lien, $sql);

	/* if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	} */

	
	
	if(mysqli_num_rows($result)>0)
	{
		$tab_anciennetes=mysqli_fetch_assoc($result);
		
		$anc_prive_annee=$tab_anciennetes['anc_prive_annee'];
		$anc_prive_mois=$tab_anciennetes['anc_prive_mois'];
		$anc_public_annee=$tab_anciennetes['anc_public_annee'];
		$anc_public_mois=$tab_anciennetes['anc_public_mois'];
		$anc_bxl_annee=$tab_anciennetes['anc_bxl_annee'];
		$anc_bxl_mois=$tab_anciennetes['anc_bxl_mois'];
	}
	
	
	mysqli_close($lien);
	
	$lien="./php/modif_anciennetes.php";
	$titre='Anciennetés revalorisées';
	$disabled='';
?><br>
<form id="FORM_ANCIENNETE" name="FORM_ANCIENNETE" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="4"><h3><?php echo $titre;?></h3></td>
</tr>
<tr>
	<td colspan="4"><br><h2 style="text-decoration:underline;font-weight:bold;">Dans le privé : </h2></td>
</tr>


<tr>
	<td><?php echo dico("nb_annees","F");?> : </td>
		<td>
			<input type="text" id="anc_prive_annee" name="anc_prive_annee" size="10" value="<?php echo $anc_prive_annee;?>" <?php echo $disabled;?>/>
			<br><br>
		
		</td>
		
		<td><?php echo dico("nb_mois","F");?> : </td>
		<td>
			<input type="text" id="anc_prive_mois" name="anc_prive_mois" size="10" value="<?php echo $anc_prive_mois;?>" <?php echo $disabled;?>/>
			<br><br>
		
		</td>
</tr>

<tr>
	<td colspan="4"><br><h2 style="text-decoration:underline;font-weight:bold;">Dans le public : </h2></td>
</tr>


<tr>
	<td><?php echo dico("nb_annees","F");?> : </td>
		<td>
			<input type="text" id="anc_public_annee" name="anc_public_annee" size="10" value="<?php echo $anc_public_annee;?>" <?php echo $disabled;?>/>
			<br><br>
		
		</td>
		
		<td><?php echo dico("nb_mois","F");?> : </td>
		<td>
			<input type="text" id="anc_public_mois" name="anc_public_mois" size="10" value="<?php echo $anc_public_mois;?>" <?php echo $disabled;?>/>
			<br><br>
		
		</td>
</tr>

<tr>
	<td colspan="4"><br><h2 style="text-decoration:underline;font-weight:bold;">Dans le public bruxellois : </h2></td>
</tr>


<tr>
	<td><?php echo dico("nb_annees","F");?> : </td>
		<td>
			<input type="text" id="anc_bxl_annee" name="anc_bxl_annee" size="10" value="<?php echo $anc_bxl_annee;?>" <?php echo $disabled;?>/>
			<br><br>
		
		</td>
		
		<td><?php echo dico("nb_mois","F");?> : </td>
		<td>
			<input type="text" id="anc_bxl_mois" name="anc_bxl_mois" size="10" value="<?php echo $anc_bxl_mois;?>" <?php echo $disabled;?>/>
			<br><br>
		
		</td>
</tr>
		
		
</table>

<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />


<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_ANCIENNETE');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_ANCIENNETE')" value="<?php echo dico("reset","F");?>" />
	<!--<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_FCT').innerHTML='';" value="<?php echo dico("close","F");?>" />
-->
	</p>
</form>
