<?php

ob_clean();
// header utf-8//
header ('Content-type: text/html; charset=utf-8'); 
if(!isset($rootpath)) { $rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]); };

include('params.php');

	echo "<br><h3>Historique des mouvements contenus dans le contrat</h3><br>		
<h2>Encodez différentes situations dans le temps afin de garder toutes traces d'évolution de carrière d'un agent. </h2>";

// Service
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de service</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_SER\',\'service\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_services" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_SER\',\'services\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_SER" >

	</div>	

	<div id="DIV_FORM_MVT_SER" >

	</div>
	
	<!--
	Fonction
	-->
	<?php
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de fonction</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_FCT\',\'fonction\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_fonctions" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_FCT\',\'fonctions\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_FCT" >

	</div>	

	<div id="DIV_FORM_MVT_FCT" >

	</div>
	<!--
	Grade
	-->
	<!--
	<?php
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de grade</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_GRADE\',\'grade\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_grades" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_GRADE\',\'grades\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_GRADE" >

	</div>	

	<div id="DIV_FORM_MVT_GRADE" >

	</div>
	-->
	<!--
	Barème
	-->
	<?php
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de barème/grade et liaison avec le cadre</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_BAREME\',\'bareme\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_baremes" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_BAREME\',\'baremes\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_BAREME" >

	</div>	

	<div id="DIV_FORM_MVT_BAREME" >

	</div>

	<!--
	CODE
	-->
	
	<!--
	<?php
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de code</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_CODE\',\'code\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_codes" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_CODE\',\'codes\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_CODE" >

	</div>	

	<div id="DIV_FORM_MVT_CODE" >

	</div>
	-->
	
	<!--
	STATUT
	-->
	
	<?php
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de statut</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_STATUT\',\'statut\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_statuts" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_STATUT\',\'statuts\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_STATUT" >

	</div>	

	<div id="DIV_FORM_MVT_STATUT" >

	</div>

	<!--
	REGIME
	-->
	<?php
	
	echo '<table class="td_list_title" width="100%">';
	echo '<tr >';
	echo '<td> >> Mouvement(s) de régime</td>';
	echo '<td width="30px" align="right"><div class="bnt_ajout_mvt" title="Ajout d\'un mouvement dans le temps" 
	onclick="DisplayFormMvt(\'DIV_FORM_MVT_REGIME\',\'regime\',0,'.$id_agent.','.$id_contrat.');"></div></td>';
	echo '<td width="30px" align="right"> <div id="bnt_regimes" class="bnt_open_list" title="Ouvrir/fermer la liste des mouvements" 
	onclick="DisplayMvt(\'LIST_MVT_REGIME\',\'regimes\','.$id_agent.','.$id_contrat.');"></div></td>';
	echo '</tr>';
	echo '</table>';
	?>
	<div id="LIST_MVT_REGIME" >

	</div>	

	<div id="DIV_FORM_MVT_REGIME" >

	</div>