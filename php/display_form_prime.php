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

if($id_prime==0)
{

	$titre="Ajout d'une nouvelle prime";
	$date_octroi='00-00-0000';
	$date_cloture='00-00-0000';
	$id_type_prime=0;
	$id_grade=0;
	//$echeance_prime_compensatoire='00-00-0000';
	$date_echeance_code='00-00-0000';
	$date_echeance_biennale='00-00-0000';
	
	$lien="./php/ajout_prime.php";
}
else
{
	include('../connect_db.php');
					
	$sql="select * from cpas_primes
	where id_prime=".$id_prime.";
	";
		
	$result=mysqli_query($lien, $sql);
	

	mysqli_close($lien);	
	
	$tab_prime=mysqli_fetch_assoc($result);
	
	$titre="Modification de prime";
	
	$date_octroi=transformDate($tab_prime['date_octroi']);
	$date_cloture=transformDate($tab_prime['date_cloture']);
	$id_type_prime=$tab_prime['id_type_prime'];
	$id_grade=$tab_prime['id_grade'];
	//$echeance_prime_compensatoire=transformDate($tab_prime['echeance_prime_compensatoire']);
	$date_echeance_code=transformDate($tab_prime['date_echeance_code']);
	$date_echeance_biennale=transformDate($tab_prime['date_echeance_biennale']);
	
	$lien="./php/modif_prime.php";

}

include('../arrays_libelle/array_type_prime.php');
include('../arrays_libelle/array_grade.php');
$disabled='';

?><br>
<form id="FORM_PRIME" name="FORM_PRIME" style="border:4px dotted #fff;padding:5px;" action="<?php echo $lien;?>" method="post" onchange="SetColorButton(this.id);" onkeyup="SetColorButton(this.id);">
	
<table>
	<tr>
		<td colspan="4"><h3><?php echo $titre;?></h3></td>
	</tr>


	<tr>
		
			<td><?php echo dico("type_prime","F");?> : </td>
			<td colspan="3">
			<select id="id_type_prime" name="id_type_prime"  onchange="DisplayOptionsPrime(this.id);" <?php echo $disabled;?>>
				<?php
			
				foreach($array_type_prime as $key => $value)
				{
					
					if($id_type_prime==$key)
					{
						echo '<option value="'.$key.'" selected>'.$array_type_prime[$key]['F'].'</option>';
					}
					else
					{
						echo '<option value="'.$key.'">'.$array_type_prime[$key]['F'].'</option>';
					}
				}
				/*****************/
				
				?>
			</select>
			</td>
	</tr>
	<tr>

		<td><?php echo '<span>'.dico("date_octroi?","F").'</span>';?> : </td>
		<td><input type="text" id="date_octroi" name="date_octroi" size="18" value="<?php echo $date_octroi;?>"  <?php echo $disabled;?>/>
		</td>
		<td><?php echo '<span>'.dico("date_cloture?","F").'</span>';?> : </td>
		<td><input type="text" id="date_cloture" name="date_cloture" size="18" value="<?php echo $date_cloture;?>"  <?php echo $disabled;?>/>
		</td>
	</tr>
</table>

<table id="div_input_grade" style="display:none;">
	<tr >
		
		<td><?php echo dico("grade","F");?> : </td>
		<td colspan="3">
		<select id="id_grade" name="id_grade" <?php echo $disabled;?>>
			<?php
			
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
	</tr>
</table>
<table id="div_alloc_fonc_sup" style="display:none;">		
	<tr >

		<td><?php echo '<span>'.dico("date_echeance_code?","F").'</span>';?> : </td>
		<td><input type="text" id="date_echeance_code" name="date_echeance_code"  size="18" value="<?php echo $date_echeance_code;?>"  <?php echo $disabled;?>/>
		</td>
		<td><?php echo '<span>'.dico("date_echeance_biennale?","F").'</span>';?> : </td>
		<td><input type="text" id="date_echeance_biennale" name="date_echeance_biennale"  size="18" value="<?php echo $date_echeance_biennale;?>"  <?php echo $disabled;?>/>
		</td>
	</tr>	
</table>
<!--<table id="div_prime_comp" style="display:none;">	
	<tr >

		<td><?php echo '<span>'.dico("echeance_prime_compensatoire?","F").'</span>';?> : </td>
		<td><input type="text" id="echeance_prime_compensatoire" name="echeance_prime_compensatoire" size="18" value="<?php echo $echeance_prime_compensatoire;?>"  <?php echo $disabled;?>/>
		</td>
		
	</tr>
	
</table>
-->

	<input type="hidden" id="id_agent" name="id_agent" value="<?php echo $id_agent;?>" />
	<input type="hidden" id="id_prime" name="id_prime" value="<?php echo $id_prime;?>" />


	<p align="center">
		<input type="button" id="bnt_sauver" style="visibility:visible;" onclick="fFormSubmit('FORM_PRIME');" value="<?php echo dico("sauver","F");?>" disabled />
		<input type="button" id="bnt_reset" style="visibility:visible;" onclick="resetFields('FORM_PRIME')" value="<?php echo dico("reset","F");?>" />
		<input type="button" id="bnt_cancel" style="visibility:visible;" onclick="document.getElementById('DIV_FORM_PRIME').innerHTML='';" value="<?php echo dico("close","F");?>" />
	</p>
</form>
