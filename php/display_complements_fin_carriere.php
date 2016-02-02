			
<?php 
echo "<h3>Compléments fin de carrière pour ".$nom." ".$prenom."</h3><br>";
if(($date_naissance=="0000-00-00")||($date_naissance=="00-00-0000")||($date_naissance=="")||($date_naissance==null))
{
	echo "<div>Date de naissance : <span style='color:red;'>Veuillez indiquer la date de naissance de l'agent dans l'onglet - Informations générales.</span></div><br>";
	echo "<div>Complément fin de carrière (45 ans) : <b>?</b></div><br>";
	echo "<div>Complément fin de carrière (50 ans) : <b>?</b></div><br>";
	echo "<div>Complément fin de carrière (55 ans) : <b>?</b></div><br>";

}
else
{
	echo "<div>Date de naissance : <b>".$date_naissance."</b></div><br>";

	$array_date_naissance=explode('-',$date_naissance);
	echo "<div>Complément fin de carrière (45 ans) : <b>".$array_date_naissance[0].'-'.$array_date_naissance[1].'-'.($array_date_naissance[2] + 45)."</b></div><br>";
	echo "<div>Complément fin de carrière (50 ans) : <b>".$array_date_naissance[0].'-'.$array_date_naissance[1].'-'.($array_date_naissance[2] + 50)."</b></div><br>";
	echo "<div>Complément fin de carrière (55 ans) : <b>".$array_date_naissance[0].'-'.$array_date_naissance[1].'-'.($array_date_naissance[2] + 55)."</b></div><br>";
}

?>