<?php
ob_clean();
header('Content-Type: text/html; charset=utf-8');

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);

 //function de traduction
include_once('../tools/function_dico.php');

/***************Fonction qui met le résultat des records dans un array*********************************************/
function fn_ResultToArray($result=null,$id_key_unic=null)
{
	$tableau=null;
	
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
/**********/
include('params.php');

	include('../connect_db.php');

	$sql="select * from cpas_cadres 
	
	where cpas_cadres.id_cadre='".$id_cadre."';
	";
	//var_dump( $sql);

	$result=mysqli_query($lien, $sql);

	//var_dump($result);
	if(!$result)
	{
		echo "erreur dans la requete:<i>".$sql."</i>";
		exit;
	}

	mysqli_close($lien);

	$tab_cadres=mysqli_fetch_assoc($result);
	/**********/
	
	
	$date_situation=transformDate($tab_cadres['date_situation']);

	
?>


<div class="systeme_onglets" style="padding-top:30px;padding-left:30px;">
        <div class="onglets">
		
            <span class="onglet_1 onglet" id="onglet_cadre_dirigeant" onclick="javascript:change_onglet_cadre('cadre_dirigeant','cadre_standard');">Cadre dirigeant</span>
            
			<span class="onglet_0 onglet" id="onglet_cadre_standard" onclick="javascript:change_onglet_cadre('cadre_standard','cadre_dirigeant');">Cadre standard </span>
			
		</div>
        <div class="contenu_onglets">
            <div class="contenu_onglet_cadre" id="contenu_onglet_cadre_dirigeant" style="display:block;" > 
                
				<div id="LIST_CADRE_DIRIGEANT"></div>
				<input type="button" onclick="DisplayFormCadreDirigeant(0,<?php echo $id_cadre;?>);" value="Ajout place"/>
				<div id="FORM_CADRE_DIRIGEANT"></div>
            </div>
           
			
			<div class="contenu_onglet_cadre" id="contenu_onglet_cadre_standard" > 
                <div id="DIV_FORM_SELECT_SERVICE">
					<?php include('display_form_select_service.php');?>
				</div>
				<div id="LIST_CADRE_STANDARD"></div>
				
				
				<div id="FORM_CADRE_STANDARD"></div>
            </div>
	
        </div>
</div>
	

   