<?php

	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("core/pmo/PMO_core/class_loader/class_section.php");

?>

<link rel="stylesheet" href="css/SearchArea.css" type="text/css">
<form method="post" action="<? $_SERVER['REQUEST_URI'] ?>" id="formSearch">
<div id="SearchArea">
	<input type="hidden" id="filterAction">
	<div class="SearchAreaContent" id="AllArea">
		Tous les pratiquants
		<div class="SearchButton"><input type="button" onclick="Search('services/getPratiquants.php','all');" value="Rafraichir"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="SectionArea">
		<div id="CSection" class="criteres">
            Affiche uniquement les pratiquants d'une section.<br/>
			Section: 
			<select id="filterSection" name="filterSection">
				<?php
					$sections = sections::GetAll();
					foreach($sections as $sec)
					{
						echo('<option value="' . $sec->id . '">' . $sec->libelle . '</option>');
					}
				?>
			</select>
		</div>
		<div class="SearchButton"><input type="button" onclick="Search('services/getPratiquants.php','section',$F('filterSection'));" value="Afficher"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="ExamensArea">
		<div id="CSectionExam" class="criteres">
			Section: 
			<select id="filterSectionExam" name="filterSectionExam">
				<?php
					$sections = sections::GetAll();
					foreach($sections as $sec)
					{
						echo('<option value="' . $sec->id . '">' . $sec->libelle . '</option>');
					}
				?>
			</select>
		</div>
		<div class="SearchButton"><input type="button" onclick="Search('services/getPratiquants.php','examens',$F('filterSectionExam'));" value="Afficher"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="ExpirationArea">
		Affiche uniquement les pratiquants dont la licence a expiré.
		<div class="SearchButton"><input type="button" onclick="Search('services/getPratiquants.php','license','');" value="Afficher"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="UpArea">
		Sections, date
		<div class="SearchButton"><input type="button" value="Afficher"></div>
	</div>

    <div class="SearchAreaContent Invisible" id="PoubelleArea">
        Affiche les pratiquants supprimés
        <div class="SearchButton"><input type="button" onclick="Search('services/getPratiquants.php','poubelle',0);" value="Afficher"></div>
    </div>
</div>
</form>
