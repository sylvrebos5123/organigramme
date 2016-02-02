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

if($id_mvt_domicile==0)
{

	$titre="Ajout d'un nouveau domicile";
	$date_mvt='00-00-0000';
	$bxl_hbxl='';
	$adresse_domicile='';
	$num_domicile='';
	$bte_domicile='';
	$code_postal='';
	$localite='';
	$region='';
	$lien="./php/ajout_mvt_domicile.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_mouvements_domiciles
	where id_mvt_domicile=".$id_mvt_domicile.";
	";
		
	$result=mysqli_query($lien, $sql);
	

	mysqli_close($lien);	
	
	$tab_domicile=mysqli_fetch_assoc($result);
	
	$titre="Modification de domicile";
	$date_mvt=transformDate($tab_domicile['date_mvt']);
	$bxl_hbxl=$tab_domicile['bxl_hbxl'];
	$adresse_domicile=$tab_domicile['adresse_domicile'];
	$num_domicile=$tab_domicile['num_domicile'];
	$bte_domicile=$tab_domicile['bte_domicile'];
	$code_postal=$tab_domicile['code_postal'];
	$localite=$tab_domicile['localite'];
	$region=$tab_domicile['region'];
	
	$lien="./php/modif_mvt_domicile.php";

}
$disabled='';

?><br>
<form id="FORM_MVT_DOMICILE" name="FORM_MVT_DOMICILE" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
<tr>
	<td colspan="3" ><h3><?php echo $titre;?></h3></td>
</tr>

<tr>

	<td><?php echo '<span>'.dico("date_changement?","F").'</span>';?> : </td>
	<td><input type="text" id="date_mvt" name="date_mvt" size="18" value="<?php echo $date_mvt;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');" <?php echo $disabled;?>/>
	</td>
</tr>


<tr>
	<td><?php echo dico("adresse_domicile","F");?> : </td>
	<td colspan="3">
	<input type="text" id="adresse_domicile" name="adresse_domicile" size="50" value="<?php echo $adresse_domicile;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');" <?php echo $disabled;?>>
	</td>
</tr>
	
	<tr>	
		<td>
		<?php echo dico("num_domicile","F");?> : 
		</td>
		<td>
		<input type="text" id="num_domicile" name="num_domicile" size="4" value="<?php echo $num_domicile;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');" <?php echo $disabled;?>>
		</td>
		
		<td><?php echo dico("bte_domicile","F");?> : </td>
		<td>
		<input type="text" id="bte_domicile" name="bte_domicile" size="4" value="<?php echo $bte_domicile;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');" <?php echo $disabled;?>>
			
		</td>
	</tr>
	<tr>
		<td><?php echo dico("code_postal","F");?> : </td>
		<td>
		<input type="text" id="code_postal" name="code_postal" value="<?php echo $code_postal;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');"/>
		</td>
		
		<td><?php echo dico("localite","F");?> : </td>
		<td>
		<input type="text" id="localite" name="localite" size="35" value="<?php echo $localite;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');"/>
		</td>	
	</tr>
	
	<tr>
		<td>
		<?php echo dico("region","F");?> : 
		</td>
		<td>
		<input type="text" id="region" name="region" value="<?php echo $region;?>" onkeyup="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');"/>
		</td>
		<td>
		<select id="bxl_hbxl" name="bxl_hbxl" onchange="SetValue('modif_domicile',1,'FORM_MVT_DOMICILE');">
		<?php
		include('../arrays_libelle/array_bxl_hbxl.php');
			for($i=0;$i<count($array_bxl_hbxl);$i++)
			{
				
				if($bxl_hbxl==$array_bxl_hbxl[$i])
				{
					echo '<option value="'.$array_bxl_hbxl[$i].'" selected>'.$array_bxl_hbxl[$i].'</option>';
				}
				else
				{
					echo '<option value="'.$array_bxl_hbxl[$i].'">'.$array_bxl_hbxl[$i].'</option>';
				}
			}
		?>
		</select>
		</td>
	</tr>
</table>

<input type="hidden" id="modif_domicile" name="modif_domicile" value="0"/>
<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
<input type="hidden" id="id_mvt_domicile" name="id_mvt_domicile" value="<?php echo $id_mvt_domicile;?>" />
<input type="hidden" id="type_mvt" name="type_mvt" value="domiciles" />

<p align="center">
	<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_MVT_DOMICILE');" value="<?php echo dico("sauver","F");?>" disabled />
	<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_MVT_DOMICILE')" value="<?php echo dico("reset","F");?>" />
	<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_MVT_DOM').innerHTML='';" value="<?php echo dico("close","F");?>" />
</p>
</form>
