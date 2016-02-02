<?php


function heureValid($heure)
{ 
 $expreg='/^((0[7-9]|1[0-9]|2[0-3])\:(0[0-9]|[1-5][0-9]))$/';
 $result=preg_match($expreg,$heure);
 return $result;
}

function dateValid($date)
{ 
//echo $date;
 
	 $expreg='/^(0[0-9]|1[0-9]|2[0-9]|3[0-1])[-](0[0-9]|1[0-2])[-](20([0-9]{2})|19([0-9]{2}))$/';
	 $result=preg_match($expreg,$date);
 
 return $result;
}

function emailValid($email)
{
 // v�rif validit� email
 $reg = "/^[a-z0-9._-]['a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,4}$/";
 $result=preg_match($reg,$email);
 return $result;
}

function nomValid($nom)
{
 // v�rif validit� du login
 $reg="#(([a-zA-Z-z��'���]+$)[-| |.]?){1,6}#";
 //$reg = "^([A-Za-z��'���]+[-| |.]?){1,6}$/";
 //$reg = "/^([A-Za-z\x{00C0}-\x{00FF}]+)[. -]([A-Za-z\x{00C0}-\x{00FF}]+)$/";
 //$reg = "/^([A-Za-z\x{00C0}-\x{00FF}]+)([. -]?)$/";
 $result=preg_match($reg,$nom);
 
 return $result;
}

/*****V�rifie si la date encod�e est plus petite que la date actuelle********************************/
function compareDateToday($date)
{
	$today = date("Ymd");
	$tab_date = explode("-", $date);
	$date_result=($tab_date[2]*10000)+($tab_date[1]*100)+$tab_date[0];
	if($date_result<$today)
	{
		$result=0;
	}
	else
	{
		$result=1;
	}
	return $result;
	
}

/****V�rifie si la date du d�but est plus grande que la date de fin de r�union****************************************/
function compareDate($date1,$date2)
{
	$tab_date1 = explode("-", $date1);
	$tab_date2 = explode("-", $date2);
	
	$date_result1=($tab_date1[2]*10000)+($tab_date1[1]*100)+$tab_date1[0];
	$date_result2=($tab_date2[2]*10000)+($tab_date2[1]*100)+$tab_date2[0];
	
	if(($date_result2-$date_result1)<0)
	{
		$result=0;
	}
	else
	{
		$result=1;
	}
	return $result;	
}

/***Fonction qui transforme la date du format fran�ais vers le format am�ricain ou invers�ment***************************/
function transformDate($date)
{
	if($date!='')
	{
		$tab_date=explode("-", $date);
		$date_result=$tab_date[2].'-'.$tab_date[1].'-'.$tab_date[0];
		
		return $date_result;
	}
}

/***Fonction qui transforme l'heure du format 00:00:00 au format 00:00***************************/
function transformHeure($heure)
{
	$tab_heure=explode(":", $heure);
	$heure_result=$tab_heure[0].':'.$tab_heure[1];
	
	return $heure_result;
}

/****V�rifie si l'heure du d�but est plus grande que la date de fin de r�union****************************************/
function compareHeure($heure1,$heure2)
{
	$tab_heure1 = explode(":", $heure1);
	$tab_heure2 = explode(":", $heure2);
	
	$heure_result1=($tab_heure1[0]*100)+$tab_heure1[1];
	$heure_result2=($tab_heure2[0]*100)+$tab_heure2[1];
	
	if(($heure_result2-$heure_result1)<0)
	{
		$result=0;
	}
	else
	{
		$result=1;
	}
	return $result;
	
}


?>
