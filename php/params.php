<?php

//header('Content-Type: text/html; charset=utf-8');
$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);
include($rootpath.'\\organigramme\\includes\\php_linguistique.php');


if (isset($_GET['id_agent']))
 $id_agent = trim($_GET['id_agent']);
else
{
 if (isset($_POST['id_agent']))
  $id_agent = trim($_POST['id_agent']);
 else
  $id_agent = '';
}
if (($id_agent == '') || ($id_agent == null) || ($id_agent < 1) || (is_numeric($id_agent) != true))
  $id_agent = 0;
/*********PARAMS******************/

if (isset($_GET['session_langue']))
 $session_langue=trim($_GET['session_langue']);
else
{
 if (isset($_POST['session_langue']))
  $session_langue=trim($_POST['session_langue']);
 else
  $session_langue='F';
}

if (isset($_GET['session_username']))
 $session_username=trim($_GET['session_username']);
else
{
 if (isset($_POST['session_username']))
  $session_username=trim($_POST['session_username']);
 else
  $session_username='';
}


if (isset($_GET['nom_champ']))
 $nom_champ=trim($_GET['nom_champ']);
else
{
 if (isset($_POST['nom_champ']))
  $nom_champ=trim($_POST['nom_champ']);
 else
  $nom_champ='';
}

if (isset($_GET['valeur_champ']))
 $valeur_champ=trim($_GET['valeur_champ']);
else
{
 if (isset($_POST['valeur_champ']))
  $valeur_champ=trim($_POST['valeur_champ']);
 else
  $valeur_champ=0;
}


/*************************/

if (isset($_GET['id_registre']))
 $id_registre = trim($_GET['id_registre']);
else
{
 if (isset($_POST['id_registre']))
  $id_registre = trim($_POST['id_registre']);
 else
  $id_registre = '';
}
if (($id_registre == '') || ($id_registre == null) || ($id_registre < 1) || (is_numeric($id_registre) != true))
  $id_registre = '';


/**************************/

if (isset($_GET['nom']))
 $nom=trim($_GET['nom']);
else
{
 if (isset($_POST['nom']))
  $nom=trim($_POST['nom']);
 else
  $nom='';
}

if (isset($_GET['prenom']))
 $prenom=trim($_GET['prenom']);
else
{
 if (isset($_POST['prenom']))
  $prenom=trim($_POST['prenom']);
 else
  $prenom='';
}

if (isset($_GET['initiales']))
 $initiales=trim($_GET['initiales']);
else
{
 if (isset($_POST['initiales']))
  $initiales=trim($_POST['initiales']);
 else
  $initiales='';
}


/*********Genre*********************/
if (isset($_GET['genre']))
 $genre=trim($_GET['genre']);
else
{
 if (isset($_POST['genre']))
  $genre=trim($_POST['genre']);
 else
  $genre='';
}

if (isset($_GET['langue']))
 $langue=trim($_GET['langue']);
else
{
 if (isset($_POST['langue']))
  $langue=trim($_POST['langue']);
 else
  $langue='';
}


if (isset($_GET['niss']))
 $niss=trim($_GET['niss']);
else
{
 if (isset($_POST['niss']))
  $niss=trim($_POST['niss']);
 else
  $niss='';
}

if (isset($_GET['start_date']))
 $start_date=trim($_GET['start_date']);
else
{
 if (isset($_POST['start_date']))
  $start_date=trim($_POST['start_date']);
 else
  $start_date='';
}

if (isset($_GET['end_date']))
 $end_date=trim($_GET['end_date']);
else
{
 if (isset($_POST['end_date']))
  $end_date=trim($_POST['end_date']);
 else
  $end_date='';
}

if (isset($_GET['actif']))
 $actif = trim($_GET['actif']);
else
{
 if (isset($_POST['actif']))
  $actif = trim($_POST['actif']);
 else
  $actif = 0;
}

if (isset($_GET['date_naissance']))
 $date_naissance=trim($_GET['date_naissance']);
else
{
 if (isset($_POST['date_naissance']))
  $date_naissance=trim($_POST['date_naissance']);
 else
  $date_naissance='';
}

if (isset($_GET['lieu_naissance']))
 $lieu_naissance=trim($_GET['lieu_naissance']);
else
{
 if (isset($_POST['lieu_naissance']))
  $lieu_naissance=trim($_POST['lieu_naissance']);
 else
  $lieu_naissance='';
}

if (isset($_GET['nationalite']))
 $nationalite=trim($_GET['nationalite']);
else
{
 if (isset($_POST['nationalite']))
  $nationalite=trim($_POST['nationalite']);
 else
  $nationalite='';
}

if (isset($_GET['motif_sortie']))
 $motif_sortie=trim($_GET['motif_sortie']);
else
{
 if (isset($_POST['motif_sortie']))
  $motif_sortie=trim($_POST['motif_sortie']);
 else
  $motif_sortie='';
}

if (isset($_GET['art_budgetaire']))
 $art_budgetaire=trim($_GET['art_budgetaire']);
else
{
 if (isset($_POST['art_budgetaire']))
  $art_budgetaire=trim($_POST['art_budgetaire']);
 else
  $art_budgetaire='';
}
/*****Langue**************/
if (isset($_GET['linguistique']))
 $linguistique=trim($_GET['linguistique']);
else
{
 if (isset($_POST['linguistique']))
  $linguistique=trim($_POST['linguistique']);
 else
  $linguistique='';
}

if (($linguistique == '') || ($linguistique == null))
 $linguistique = '';
if (isset($array_linguistique[$linguistique]) != true)
 $linguistique = '';


/**********SERVICE, DEP, CELLULE****************************************/
 if (isset($_GET['date_echeance_service']))
 $date_echeance_service = trim($_GET['date_echeance_service']);
else
{
 if (isset($_POST['date_echeance_service']))
  $date_echeance_service = trim($_POST['date_echeance_service']);
 else
  $date_echeance_service = '';
} 


if (isset($_GET['modif_service']))
 $modif_service = trim($_GET['modif_service']);
else
{
 if (isset($_POST['modif_service']))
  $modif_service = trim($_POST['modif_service']);
 else
  $modif_service = 0;
}


if (isset($_GET['id_ser']))
 $id_ser = trim($_GET['id_ser']);
else
{
 if (isset($_POST['id_ser']))
  $id_ser = trim($_POST['id_ser']);
 else
  $id_ser = '';
}
if (($id_ser == '') || ($id_ser == null) || ($id_ser < 1) || (is_numeric($id_ser) != true))
  $id_ser = 0;
  
  
/********************************/
if (isset($_GET['choix_groupe']))
 $choix_groupe = trim($_GET['choix_groupe']);
else
{
 if (isset($_POST['choix_groupe']))
  $choix_groupe = trim($_POST['choix_groupe']);
 else
  $choix_groupe = '';
}  
/********************************/
if (isset($_GET['id_dep']))
 $id_dep = trim($_GET['id_dep']);
else
{
 if (isset($_POST['id_dep']))
  $id_dep = trim($_POST['id_dep']);
 else
  $id_dep = '';
}
if (($id_dep == '') || ($id_dep == null) || ($id_dep < 1) || (is_numeric($id_dep) != true))
  $id_dep = 0;
  
/********************************/
if (isset($_GET['id_hors_dep']))
 $id_hors_dep = trim($_GET['id_hors_dep']);
else
{
 if (isset($_POST['id_hors_dep']))
  $id_hors_dep = trim($_POST['id_hors_dep']);
 else
  $id_hors_dep = '';
}
if (($id_hors_dep == '') || ($id_hors_dep == null) || ($id_hors_dep < 1) || (is_numeric($id_hors_dep) != true))
  $id_hors_dep = 0;
  
  
/***************************/

if (isset($_GET['indice_ordre']))
 $indice_ordre = trim($_GET['indice_ordre']);
else
{
 if (isset($_POST['indice_ordre']))
  $indice_ordre = trim($_POST['indice_ordre']);
 else
  $indice_ordre = 100;
}

  
/***************************/
if (isset($_GET['id_cel']))
 $id_cel = trim($_GET['id_cel']);
else
{
 if (isset($_POST['id_cel']))
  $id_cel = trim($_POST['id_cel']);
 else
  $id_cel = '';
}
if (($id_cel == '') || ($id_cel == null) || ($id_cel < 1) || (is_numeric($id_cel) != true))
  $id_cel = 0;

  
if (isset($_GET['zone_libre_service']))
 $zone_libre_service = trim($_GET['zone_libre_service']);
else
{
 if (isset($_POST['zone_libre_service']))
  $zone_libre_service = trim($_POST['zone_libre_service']);
 else
  $zone_libre_service = '';
}
  /***************************/
 if (isset($_GET['date_echeance_fonction']))
 $date_echeance_fonction = trim($_GET['date_echeance_fonction']);
else
{
 if (isset($_POST['date_echeance_fonction']))
  $date_echeance_fonction = trim($_POST['date_echeance_fonction']);
 else
  $date_echeance_fonction = '';
} 
  
  
if (isset($_GET['modif_fonction']))
 $modif_fonction = trim($_GET['modif_fonction']);
else
{
 if (isset($_POST['modif_fonction']))
  $modif_fonction = trim($_POST['modif_fonction']);
 else
  $modif_fonction = 0;
}
  
  
if (isset($_GET['id_fonc']))
 $id_fonc = trim($_GET['id_fonc']);
else
{
 if (isset($_POST['id_fonc']))
  $id_fonc = trim($_POST['id_fonc']);
 else
  $id_fonc = '';
}
if (($id_fonc == '') || ($id_fonc == null) || ($id_fonc < 1) || (is_numeric($id_fonc) != true))
  $id_fonc = 0;
  

  
if (isset($_GET['autre_type_fonc']))
 $autre_type_fonc = trim($_GET['autre_type_fonc']);
else
{
 if (isset($_POST['autre_type_fonc']))
  $autre_type_fonc = trim($_POST['autre_type_fonc']);
 else
  $autre_type_fonc = '';
}

  
  
if (isset($_GET['zone_libre_fonction']))
 $zone_libre_fonction = trim($_GET['zone_libre_fonction']);
else
{
 if (isset($_POST['zone_libre_fonction']))
  $zone_libre_fonction = trim($_POST['zone_libre_fonction']);
 else
  $zone_libre_fonction = '';
}
/*********************/
  
if (isset($_GET['flag_resp_dep']))
 $flag_resp_dep = trim($_GET['flag_resp_dep']);
else
{
 if (isset($_POST['flag_resp_dep']))
  $flag_resp_dep = trim($_POST['flag_resp_dep']);
 else
  $flag_resp_dep = 0;
}


if (isset($_GET['flag_resp_ser']))
 $flag_resp_ser = trim($_GET['flag_resp_ser']);
else
{
 if (isset($_POST['flag_resp_ser']))
  $flag_resp_ser = trim($_POST['flag_resp_ser']);
 else
  $flag_resp_ser = 0;
}

/******************************************/
if (isset($_GET['ouvrier_employe']))
 $ouvrier_employe = trim($_GET['ouvrier_employe']);
else
{
 if (isset($_POST['ouvrier_employe']))
  $ouvrier_employe = trim($_POST['ouvrier_employe']);
 else
  $ouvrier_employe = '';
}


if (isset($_GET['categorie']))
 $categorie = trim($_GET['categorie']);
else
{
 if (isset($_POST['categorie']))
  $categorie = trim($_POST['categorie']);
 else
  $categorie = '';
}

 /******************************/
if (isset($_GET['id_civilite']))
 $id_civilite = trim($_GET['id_civilite']);
else
{
 if (isset($_POST['id_civilite']))
  $id_civilite = trim($_POST['id_civilite']);
 else
  $id_civilite = '';
}
if (($id_civilite == '') || ($id_civilite == null) || ($id_civilite < 1) || (is_numeric($id_civilite) != true))
  $id_civilite = 0;

/*if (isset($_GET['civilite']))
 $civilite = trim($_GET['civilite']);
else
{
 if (isset($_POST['civilite']))
  $civilite = trim($_POST['civilite']);
 else
  $civilite = '';
}*/


if (isset($_GET['id_etat_civil']))
 $id_etat_civil = trim($_GET['id_etat_civil']);
else
{
 if (isset($_POST['id_etat_civil']))
  $id_etat_civil = trim($_POST['id_etat_civil']);
 else
  $id_etat_civil = '';
}

if (($id_etat_civil == '') || ($id_etat_civil == null) || ($id_etat_civil < 1) || (is_numeric($id_etat_civil) != true))
  $id_etat_civil = 0;
  
  /***************************/
if (isset($_GET['modif_grade']))
 $modif_grade = trim($_GET['modif_grade']);
else
{
 if (isset($_POST['modif_grade']))
  $modif_grade = trim($_POST['modif_grade']);
 else
  $modif_grade = 0;
}

  

if (isset($_GET['id_grade']))
 $id_grade = trim($_GET['id_grade']);
else
{
 if (isset($_POST['id_grade']))
  $id_grade = trim($_POST['id_grade']);
 else
  $id_grade = '';
}
if (($id_grade == '') || ($id_grade == null) || ($id_grade < 1) || (is_numeric($id_grade) != true))
  $id_grade = 0;
  

  
if (isset($_GET['date_echeance_grade']))
 $date_echeance_grade = trim($_GET['date_echeance_grade']);
else
{
 if (isset($_POST['date_echeance_grade']))
  $date_echeance_grade = trim($_POST['date_echeance_grade']);
 else
  $date_echeance_grade = '';
}
  
/* if (isset($_GET['zone_libre_grade']))
 $zone_libre_grade = trim($_GET['zone_libre_grade']);
else
{
 if (isset($_POST['zone_libre_grade']))
  $zone_libre_grade = trim($_POST['zone_libre_grade']);
 else
  $zone_libre_grade = '';
} */

/***************************/
if (isset($_GET['modif_bareme']))
 $modif_bareme = trim($_GET['modif_bareme']);
else
{
 if (isset($_POST['modif_bareme']))
  $modif_bareme = trim($_POST['modif_bareme']);
 else
  $modif_bareme = 0;
}	
	
	
if (isset($_GET['id_bareme']))
 $id_bareme = trim($_GET['id_bareme']);
else
{
 if (isset($_POST['id_bareme']))
  $id_bareme = trim($_POST['id_bareme']);
 else
  $id_bareme = '';
}
if (($id_bareme == '') || ($id_bareme == null) || ($id_bareme < 1) || (is_numeric($id_bareme) != true))
  $id_bareme = 0;
  
  
if (isset($_GET['id_bareme_cadre']))
 $id_bareme_cadre = trim($_GET['id_bareme_cadre']);
else
{
 if (isset($_POST['id_bareme_cadre']))
  $id_bareme_cadre = trim($_POST['id_bareme_cadre']);
 else
  $id_bareme_cadre = '';
}
if (($id_bareme_cadre == '') || ($id_bareme_cadre == null) || ($id_bareme_cadre < 1) || (is_numeric($id_bareme_cadre) != true))
  $id_bareme_cadre = 0;  
  

if (isset($_GET['date_echeance_bareme']))
 $date_echeance_bareme = trim($_GET['date_echeance_bareme']);
else
{
 if (isset($_POST['date_echeance_bareme']))
  $date_echeance_bareme = trim($_POST['date_echeance_bareme']);
 else
  $date_echeance_bareme = '';
}
  
/***************************/
	  
if (isset($_GET['modif_code']))
 $modif_code = trim($_GET['modif_code']);
else
{
 if (isset($_POST['modif_code']))
  $modif_code = trim($_POST['modif_code']);
 else
  $modif_code = 0;
}


if (isset($_GET['id_code']))
 $id_code = trim($_GET['id_code']);
else
{
 if (isset($_POST['id_code']))
  $id_code = trim($_POST['id_code']);
 else
  $id_code = '';
}
if (($id_code == '') || ($id_code == null) || ($id_code < 1) || (is_numeric($id_code) != true))
  $id_code = 0;
  
  
if (isset($_GET['id_code_cadre']))
 $id_code_cadre = trim($_GET['id_code_cadre']);
else
{
 if (isset($_POST['id_code_cadre']))
  $id_code_cadre = trim($_POST['id_code_cadre']);
 else
  $id_code_cadre = '';
}
if (($id_code_cadre == '') || ($id_code_cadre == null) || ($id_code_cadre < 1) || (is_numeric($id_code_cadre) != true))
  $id_code_cadre = 0;
  
  
if (isset($_GET['date_echeance_code']))
 $date_echeance_code = trim($_GET['date_echeance_code']);
else
{
 if (isset($_POST['date_echeance_code']))
  $date_echeance_code = trim($_POST['date_echeance_code']);
 else
  $date_echeance_code = '';
}
  
/***************************/
if (isset($_GET['modif_statut']))
 $modif_statut = trim($_GET['modif_statut']);
else
{
 if (isset($_POST['modif_statut']))
  $modif_statut = trim($_POST['modif_statut']);
 else
  $modif_statut = 0;
}

		
if (isset($_GET['id_statut']))
 $id_statut = trim($_GET['id_statut']);
else
{
 if (isset($_POST['id_statut']))
  $id_statut = trim($_POST['id_statut']);
 else
  $id_statut = '';
}
if (($id_statut == '') || ($id_statut == null) || ($id_statut < 1) || (is_numeric($id_statut) != true))
  $id_statut = 0;
  

  
  
if (isset($_GET['id_statut_special']))
 $id_statut_special = trim($_GET['id_statut_special']);
else
{
 if (isset($_POST['id_statut_special']))
  $id_statut_special = trim($_POST['id_statut_special']);
 else
  $id_statut_special = '';
}
if (($id_statut_special == '') || ($id_statut_special == null) || ($id_statut_special < 1) || (is_numeric($id_statut_special) != true))
  $id_statut_special = 0;  

  
  
if (isset($_GET['date_echeance_statut']))
 $date_echeance_statut = trim($_GET['date_echeance_statut']);
else
{
 if (isset($_POST['date_echeance_statut']))
  $date_echeance_statut = trim($_POST['date_echeance_statut']);
 else
  $date_echeance_statut = '';
}


if (isset($_GET['contractuel_nomme']))
 $contractuel_nomme = trim($_GET['contractuel_nomme']);
else
{
 if (isset($_POST['contractuel_nomme']))
  $contractuel_nomme = trim($_POST['contractuel_nomme']);
 else
  $contractuel_nomme = '';
}

/* if (isset($_GET['zone_libre_statut']))
 $zone_libre_statut = trim($_GET['zone_libre_statut']);
else
{
 if (isset($_POST['zone_libre_statut']))
  $zone_libre_statut = trim($_POST['zone_libre_statut']);
 else
  $zone_libre_statut = '';
} */

   /***************************/
if (isset($_GET['modif_regime']))
 $modif_regime = trim($_GET['modif_regime']);
else
{
 if (isset($_POST['modif_regime']))
  $modif_regime = trim($_POST['modif_regime']);
 else
  $modif_regime = 0;
}


   
if (isset($_GET['id_regime']))
 $id_regime = trim($_GET['id_regime']);
else
{
 if (isset($_POST['id_regime']))
  $id_regime = trim($_POST['id_regime']);
 else
  $id_regime = '';
}
if (($id_regime == '') || ($id_regime == null) || ($id_regime < 1) || (is_numeric($id_regime) != true))
  $id_regime = 0;
  
  
  
  
if (isset($_GET['date_echeance_regime']))
 $date_echeance_regime = trim($_GET['date_echeance_regime']);
else
{
 if (isset($_POST['date_echeance_regime']))
  $date_echeance_regime = trim($_POST['date_echeance_regime']);
 else
  $date_echeance_regime = '';
}


/* if (isset($_GET['zone_libre_regime']))
 $zone_libre_regime = trim($_GET['zone_libre_regime']);
else
{
 if (isset($_POST['zone_libre_regime']))
  $zone_libre_regime = trim($_POST['zone_libre_regime']);
 else
  $zone_libre_regime = '';
} */

   /***************************/
if (isset($_GET['id_equiv_tp']))
 $id_equiv_tp = trim($_GET['id_equiv_tp']);
else
{
 if (isset($_POST['id_equiv_tp']))
  $id_equiv_tp = trim($_POST['id_equiv_tp']);
 else
  $id_equiv_tp = '';
}  
   
/*if (isset($_GET['id_equiv_tp']))
 $id_equiv_tp = trim($_GET['id_equiv_tp']);
else
{
 if (isset($_POST['id_equiv_tp']))
  $id_equiv_tp = trim($_POST['id_equiv_tp']);
 else
  $id_equiv_tp = '';
}
if (($id_equiv_tp == '') || ($id_equiv_tp == null) || ($id_equiv_tp < 1) || (is_numeric($id_equiv_tp) != true))
  $id_equiv_tp = 0;*/
  
  /**********/
if (isset($_GET['niveau_etudes']))
 $niveau_etudes = trim($_GET['niveau_etudes']);
else
{
 if (isset($_POST['niveau_etudes']))
  $niveau_etudes = trim($_POST['niveau_etudes']);
 else
  $niveau_etudes = '';
}

if (isset($_GET['id_selor']))
 $id_selor = trim($_GET['id_selor']);
else
{
 if (isset($_POST['id_selor']))
  $id_selor = trim($_POST['id_selor']);
 else
  $id_selor = '';
}
if (($id_selor == '') || ($id_selor == null) || ($id_selor < 1) || (is_numeric($id_selor) != true))
  $id_selor = 0;
  

if (isset($_GET['zone_libre_selor']))
 $zone_libre_selor = trim($_GET['zone_libre_selor']);
else
{
 if (isset($_POST['zone_libre_selor']))
  $zone_libre_selor = trim($_POST['zone_libre_selor']);
 else
  $zone_libre_selor = '';
}
  
  /*******/
if (isset($_GET['libelle_diplome']))
 $libelle_diplome = trim($_GET['libelle_diplome']);
else
{
 if (isset($_POST['libelle_diplome']))
  $libelle_diplome = trim($_POST['libelle_diplome']);
 else
  $libelle_diplome = '';
}
/*****/
 if (isset($_GET['prime_linguistique']))
 $prime_linguistique = trim($_GET['prime_linguistique']);
else
{
 if (isset($_POST['prime_linguistique']))
  $prime_linguistique = trim($_POST['prime_linguistique']);
 else
  $prime_linguistique = '';
}

/*****/
if (isset($_GET['modif_domicile']))
 $modif_domicile = trim($_GET['modif_domicile']);
else
{
 if (isset($_POST['modif_domicile']))
  $modif_domicile = trim($_POST['modif_domicile']);
 else
  $modif_domicile = 0;
}


if (isset($_GET['id_mvt_domicile']))
 $id_mvt_domicile = trim($_GET['id_mvt_domicile']);
else
{
 if (isset($_POST['id_mvt_domicile']))
  $id_mvt_domicile = trim($_POST['id_mvt_domicile']);
 else
  $id_mvt_domicile = '';
}
if (($id_mvt_domicile == '') || ($id_mvt_domicile == null) || ($id_mvt_domicile < 1) || (is_numeric($id_mvt_domicile) != true))
  $id_mvt_domicile = 0;
  


 if (isset($_GET['adresse_domicile']))
 $adresse_domicile = trim($_GET['adresse_domicile']);
else
{
 if (isset($_POST['adresse_domicile']))
  $adresse_domicile = trim($_POST['adresse_domicile']);
 else
  $adresse_domicile = '';
}

/*****/
 if (isset($_GET['num_domicile']))
 $num_domicile = trim($_GET['num_domicile']);
else
{
 if (isset($_POST['num_domicile']))
  $num_domicile = trim($_POST['num_domicile']);
 else
  $num_domicile = '';
}

/*****/
 if (isset($_GET['bte_domicile']))
 $bte_domicile = trim($_GET['bte_domicile']);
else
{
 if (isset($_POST['bte_domicile']))
  $bte_domicile = trim($_POST['bte_domicile']);
 else
  $bte_domicile = '';
}

/*****/
 if (isset($_GET['code_postal']))
 $code_postal = trim($_GET['code_postal']);
else
{
 if (isset($_POST['code_postal']))
  $code_postal = trim($_POST['code_postal']);
 else
  $code_postal = '';
}

/*****/
 if (isset($_GET['localite']))
 $localite = trim($_GET['localite']);
else
{
 if (isset($_POST['localite']))
  $localite = trim($_POST['localite']);
 else
  $localite = '';
}

/*****/
 if (isset($_GET['bxl_hbxl']))
 $bxl_hbxl = trim($_GET['bxl_hbxl']);
else
{
 if (isset($_POST['bxl_hbxl']))
  $bxl_hbxl = trim($_POST['bxl_hbxl']);
 else
  $bxl_hbxl = '';
}

/*******/
if (isset($_GET['region']))
 $region = trim($_GET['region']);
else
{
 if (isset($_POST['region']))
  $region = trim($_POST['region']);
 else
  $region = '';
}

/*******/

if (isset($_GET['modif_tel']))
 $modif_tel = trim($_GET['modif_tel']);
else
{
 if (isset($_POST['modif_tel']))
  $modif_tel = trim($_POST['modif_tel']);
 else
  $modif_tel = 0;
}


if (isset($_GET['tel_prive']))
 $tel_prive = trim($_GET['tel_prive']);
else
{
 if (isset($_POST['tel_prive']))
  $tel_prive = trim($_POST['tel_prive']);
 else
  $tel_prive = '';
}
/*****************/
if (isset($_GET['id_contrat']))
 $id_contrat = trim($_GET['id_contrat']);
else
{
 if (isset($_POST['id_contrat']))
  $id_contrat = trim($_POST['id_contrat']);
 else
  $id_contrat = '';
}
if (($id_contrat == '') || ($id_contrat == null) || ($id_contrat < 1) || (is_numeric($id_contrat) != true))
  $id_contrat = 0;
  
  
  
if (isset($_GET['id_mvt_service']))
 $id_mvt_service = trim($_GET['id_mvt_service']);
else
{
 if (isset($_POST['id_mvt_service']))
  $id_mvt_service = trim($_POST['id_mvt_service']);
 else
  $id_mvt_service = '';
}
if (($id_mvt_service == '') || ($id_mvt_service == null) || ($id_mvt_service < 1) || (is_numeric($id_mvt_service) != true))
  $id_mvt_service = 0;  
  
  
 if (isset($_GET['id_mvt_fonction']))
 $id_mvt_fonction = trim($_GET['id_mvt_fonction']);
else
{
 if (isset($_POST['id_mvt_fonction']))
  $id_mvt_fonction = trim($_POST['id_mvt_fonction']);
 else
  $id_mvt_fonction = '';
}
if (($id_mvt_fonction == '') || ($id_mvt_fonction == null) || ($id_mvt_fonction < 1) || (is_numeric($id_mvt_fonction) != true))
  $id_mvt_fonction = 0;  
  
 
if (isset($_GET['id_mvt_grade']))
 $id_mvt_grade = trim($_GET['id_mvt_grade']);
else
{
 if (isset($_POST['id_mvt_grade']))
  $id_mvt_grade = trim($_POST['id_mvt_grade']);
 else
  $id_mvt_grade = '';
}
if (($id_mvt_grade == '') || ($id_mvt_grade == null) || ($id_mvt_grade < 1) || (is_numeric($id_mvt_grade) != true))
  $id_mvt_grade = 0;  
  
  
if (isset($_GET['id_mvt_bareme']))
 $id_mvt_bareme = trim($_GET['id_mvt_bareme']);
else
{
 if (isset($_POST['id_mvt_bareme']))
  $id_mvt_bareme = trim($_POST['id_mvt_bareme']);
 else
  $id_mvt_bareme = '';
}
if (($id_mvt_bareme == '') || ($id_mvt_bareme == null) || ($id_mvt_bareme < 1) || (is_numeric($id_mvt_bareme) != true))
  $id_mvt_bareme = 0;  
  
  
if (isset($_GET['id_mvt_code']))
 $id_mvt_code = trim($_GET['id_mvt_code']);
else
{
 if (isset($_POST['id_mvt_code']))
  $id_mvt_code = trim($_POST['id_mvt_code']);
 else
  $id_mvt_code = '';
}
if (($id_mvt_code == '') || ($id_mvt_code == null) || ($id_mvt_code < 1) || (is_numeric($id_mvt_code) != true))
  $id_mvt_code = 0;  
  
  
 
if (isset($_GET['id_mvt_statut']))
 $id_mvt_statut = trim($_GET['id_mvt_statut']);
else
{
 if (isset($_POST['id_mvt_statut']))
  $id_mvt_statut = trim($_POST['id_mvt_statut']);
 else
  $id_mvt_statut = '';
}
if (($id_mvt_statut == '') || ($id_mvt_statut == null) || ($id_mvt_statut < 1) || (is_numeric($id_mvt_statut) != true))
  $id_mvt_statut = 0;  
 
 
 
if (isset($_GET['id_mvt_regime']))
 $id_mvt_regime = trim($_GET['id_mvt_regime']);
else
{
 if (isset($_POST['id_mvt_regime']))
  $id_mvt_regime = trim($_POST['id_mvt_regime']);
 else
  $id_mvt_regime = '';
}
if (($id_mvt_regime == '') || ($id_mvt_regime == null) || ($id_mvt_regime < 1) || (is_numeric($id_mvt_regime) != true))
  $id_mvt_regime = 0;   

  
if (isset($_GET['date_mvt']))
 $date_mvt = trim($_GET['date_mvt']);
else
{
 if (isset($_POST['date_mvt']))
  $date_mvt = trim($_POST['date_mvt']);
 else
  $date_mvt = '';
}


  
  
if (isset($_GET['date_debut_fonction']))
 $date_debut_fonction = trim($_GET['date_debut_fonction']);
else
{
 if (isset($_POST['date_debut_fonction']))
  $date_debut_fonction = trim($_POST['date_debut_fonction']);
 else
  $date_debut_fonction = '';
}

if (isset($_GET['date_debut_service']))
 $date_debut_service = trim($_GET['date_debut_service']);
else
{
 if (isset($_POST['date_debut_service']))
  $date_debut_service = trim($_POST['date_debut_service']);
 else
  $date_debut_service = '';
}

if (isset($_GET['date_debut_grade']))
 $date_debut_grade = trim($_GET['date_debut_grade']);
else
{
 if (isset($_POST['date_debut_grade']))
  $date_debut_grade = trim($_POST['date_debut_grade']);
 else
  $date_debut_grade = '';
}

if (isset($_GET['date_debut_bareme']))
 $date_debut_bareme = trim($_GET['date_debut_bareme']);
else
{
 if (isset($_POST['date_debut_bareme']))
  $date_debut_bareme = trim($_POST['date_debut_bareme']);
 else
  $date_debut_bareme = '';
}

if (isset($_GET['date_debut_code']))
 $date_debut_code = trim($_GET['date_debut_code']);
else
{
 if (isset($_POST['date_debut_code']))
  $date_debut_code = trim($_POST['date_debut_code']);
 else
  $date_debut_code = '';
}

if (isset($_GET['date_debut_statut']))
 $date_debut_statut = trim($_GET['date_debut_statut']);
else
{
 if (isset($_POST['date_debut_statut']))
  $date_debut_statut = trim($_POST['date_debut_statut']);
 else
  $date_debut_statut = '';
}

if (isset($_GET['date_debut_regime']))
 $date_debut_regime = trim($_GET['date_debut_regime']);
else
{
 if (isset($_POST['date_debut_regime']))
  $date_debut_regime = trim($_POST['date_debut_regime']);
 else
  $date_debut_regime = '';
}


if (isset($_GET['date_debut']))
 $date_debut = trim($_GET['date_debut']);
else
{
 if (isset($_POST['date_debut']))
  $date_debut = trim($_POST['date_debut']);
 else
  $date_debut = '';
}

if (isset($_GET['date_fin']))
 $date_fin = trim($_GET['date_fin']);
else
{
 if (isset($_POST['date_fin']))
  $date_fin = trim($_POST['date_fin']);
 else
  $date_fin = '';
}

  
if (isset($_GET['date_debut_out']))
 $date_debut_out = trim($_GET['date_debut_out']);
else
{
 if (isset($_POST['date_debut_out']))
  $date_debut_out = trim($_POST['date_debut_out']);
 else
  $date_debut_out = '';
}

if (isset($_GET['date_fin_out']))
 $date_fin_out = trim($_GET['date_fin_out']);
else
{
 if (isset($_POST['date_fin_out']))
  $date_fin_out = trim($_POST['date_fin_out']);
 else
  $date_fin_out = '';
}

/*****Form service/cellule******************/
if (isset($_GET['label_F']))
 $label_F = trim($_GET['label_F']);
else
{
 if (isset($_POST['label_F']))
  $label_F = trim($_POST['label_F']);
 else
  $label_F = '';
}

if (isset($_GET['label_N']))
 $label_N = trim($_GET['label_N']);
else
{
 if (isset($_POST['label_N']))
  $label_N = trim($_POST['label_N']);
 else
  $label_N = '';
}

if (isset($_GET['fax']))
 $fax = trim($_GET['fax']);
else
{
 if (isset($_POST['fax']))
  $fax = trim($_POST['fax']);
 else
  $fax = '';
}

/*****CADRE******************************/

if (isset($_GET['type_cadre']))
 $type_cadre = trim($_GET['type_cadre']);
else
{
 if (isset($_POST['type_cadre']))
  $type_cadre = trim($_POST['type_cadre']);
 else
  $type_cadre = 0;
}


if (isset($_GET['id_cadre']))
 $id_cadre = trim($_GET['id_cadre']);
else
{
 if (isset($_POST['id_cadre']))
  $id_cadre = trim($_POST['id_cadre']);
 else
  $id_cadre = '';
}
if (($id_cadre == '') || ($id_cadre == null) || ($id_cadre < 1) || (is_numeric($id_cadre) != true))
  $id_cadre = 0; 
  
  
if (isset($_GET['id_cadre_a_dupliquer']))
 $id_cadre_a_dupliquer = trim($_GET['id_cadre_a_dupliquer']);
else
{
 if (isset($_POST['id_cadre_a_dupliquer']))
  $id_cadre_a_dupliquer = trim($_POST['id_cadre_a_dupliquer']);
 else
  $id_cadre_a_dupliquer = '';
}
if (($id_cadre_a_dupliquer == '') || ($id_cadre_a_dupliquer == null) || ($id_cadre_a_dupliquer < 1) || (is_numeric($id_cadre_a_dupliquer) != true))
  $id_cadre_a_dupliquer = 0;   

  
if (isset($_GET['date_situation']))
 $date_situation = trim($_GET['date_situation']);
else
{
 if (isset($_POST['date_situation']))
  $date_situation = trim($_POST['date_situation']);
 else
  $date_situation = '';
}


if (isset($_GET['id_place_cadre']))
 $id_place_cadre = trim($_GET['id_place_cadre']);
else
{
 if (isset($_POST['id_place_cadre']))
  $id_place_cadre = trim($_POST['id_place_cadre']);
 else
  $id_place_cadre = '';
}
if (($id_place_cadre == '') || ($id_place_cadre == null) || ($id_place_cadre < 1) || (is_numeric($id_place_cadre) != true))
  $id_place_cadre = 0; 
  
  
 if (isset($_GET['id_grade_cadre']))
 $id_grade_cadre = trim($_GET['id_grade_cadre']);
else
{
 if (isset($_POST['id_grade_cadre']))
  $id_grade_cadre = trim($_POST['id_grade_cadre']);
 else
  $id_grade_cadre = '';
}
if (($id_grade_cadre == '') || ($id_grade_cadre == null) || ($id_grade_cadre < 1) || (is_numeric($id_grade_cadre) != true))
  $id_grade_cadre = 0; 
  

if (isset($_GET['grade_fonction']))
 $grade_fonction = trim($_GET['grade_fonction']);
else
{
 if (isset($_POST['grade_fonction']))
  $grade_fonction = trim($_POST['grade_fonction']);
 else
  $grade_fonction = '';
}

if (isset($_GET['date_situation_effectifs']))
 $date_situation_effectifs = trim($_GET['date_situation_effectifs']);
else
{
 if (isset($_POST['date_situation_effectifs']))
  $date_situation_effectifs = trim($_POST['date_situation_effectifs']);
 else
  $date_situation_effectifs = '';
}

if (isset($_GET['date_situation_effectifs_tableau']))
 $date_situation_effectifs_tableau = trim($_GET['date_situation_effectifs_tableau']);
else
{
 if (isset($_POST['date_situation_effectifs_tableau']))
  $date_situation_effectifs_tableau = trim($_POST['date_situation_effectifs_tableau']);
 else
  $date_situation_effectifs_tableau = '';
}

if (isset($_GET['id_article_budgetaire']))
 $id_article_budgetaire=trim($_GET['id_article_budgetaire']);
else
{
 if (isset($_POST['id_article_budgetaire']))
  $id_article_budgetaire=trim($_POST['id_article_budgetaire']);
 else
  $id_article_budgetaire='';
}

if (($id_article_budgetaire == '') || ($id_article_budgetaire == null) || ($id_article_budgetaire < 1) || (is_numeric($id_article_budgetaire) != true))
  $id_article_budgetaire = 0; 
  
 /***********Anciennetés en mois et années*****************************/
 if (isset($_GET['anc_prive_annee']))
 $anc_prive_annee = trim($_GET['anc_prive_annee']);
else
{
 if (isset($_POST['anc_prive_annee']))
  $anc_prive_annee = trim($_POST['anc_prive_annee']);
 else
  $anc_prive_annee = 0;
}

if (isset($_GET['anc_prive_mois']))
 $anc_prive_mois = trim($_GET['anc_prive_mois']);
else
{
 if (isset($_POST['anc_prive_mois']))
  $anc_prive_mois = trim($_POST['anc_prive_mois']);
 else
  $anc_prive_mois = 0;
}

if (isset($_GET['anc_public_annee']))
 $anc_public_annee = trim($_GET['anc_public_annee']);
else
{
 if (isset($_POST['anc_public_annee']))
  $anc_public_annee = trim($_POST['anc_public_annee']);
 else
  $anc_public_annee = 0;
}

if (isset($_GET['anc_public_mois']))
 $anc_public_mois = trim($_GET['anc_public_mois']);
else
{
 if (isset($_POST['anc_public_mois']))
  $anc_public_mois = trim($_POST['anc_public_mois']);
 else
  $anc_public_mois = 0;
}

if (isset($_GET['anc_bxl_annee']))
 $anc_bxl_annee = trim($_GET['anc_bxl_annee']);
else
{
 if (isset($_POST['anc_bxl_annee']))
  $anc_bxl_annee = trim($_POST['anc_bxl_annee']);
 else
  $anc_bxl_annee = 0;
}

if (isset($_GET['anc_bxl_mois']))
 $anc_bxl_mois = trim($_GET['anc_bxl_mois']);
else
{
 if (isset($_POST['anc_bxl_mois']))
  $anc_bxl_mois = trim($_POST['anc_bxl_mois']);
 else
  $anc_bxl_mois = 0;
}

/**********Primes**********************/
if (isset($_GET['id_prime']))
 $id_prime=trim($_GET['id_prime']);
else
{
 if (isset($_POST['id_prime']))
  $id_prime=trim($_POST['id_prime']);
 else
  $id_prime='';
}

if (($id_prime == '') || ($id_prime == null) || ($id_prime < 1) || (is_numeric($id_prime) != true))
  $id_prime = 0; 
  
  
  
if (isset($_GET['id_type_prime']))
 $id_type_prime=trim($_GET['id_type_prime']);
else
{
 if (isset($_POST['id_type_prime']))
  $id_type_prime=trim($_POST['id_type_prime']);
 else
  $id_type_prime='';
}

if (($id_type_prime == '') || ($id_type_prime == null) || ($id_type_prime < 1) || (is_numeric($id_type_prime) != true))
  $id_type_prime = 0; 
  

if (isset($_GET['date_octroi']))
 $date_octroi = trim($_GET['date_octroi']);
else
{
 if (isset($_POST['date_octroi']))
  $date_octroi = trim($_POST['date_octroi']);
 else
  $date_octroi = '';
}


if (isset($_GET['date_cloture']))
 $date_cloture = trim($_GET['date_cloture']);
else
{
 if (isset($_POST['date_cloture']))
  $date_cloture = trim($_POST['date_cloture']);
 else
  $date_cloture = '';
}


/* if (isset($_GET['echeance_prime_compensatoire']))
 $echeance_prime_compensatoire = trim($_GET['echeance_prime_compensatoire']);
else
{
 if (isset($_POST['echeance_prime_compensatoire']))
  $echeance_prime_compensatoire = trim($_POST['echeance_prime_compensatoire']);
 else
  $echeance_prime_compensatoire = '';
} */

if (isset($_GET['date_echeance_biennale']))
 $date_echeance_biennale = trim($_GET['date_echeance_biennale']);
else
{
 if (isset($_POST['date_echeance_biennale']))
  $date_echeance_biennale = trim($_POST['date_echeance_biennale']);
 else
  $date_echeance_biennale = '';
}
?>