<?php

	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("core/pmo/PMO_core/class_loader/class_section.php");

?>

<link rel="stylesheet" href="css/SearchArea.css" type="text/css">
<form method="post" action="<?php $_SERVER['REQUEST_URI'] ?>" id="formSearch">
<div id="SearchArea">
	<input type="hidden" id="filterAction">
	<div class="SearchAreaContent" id="AllArea">
		Tous les pratiquants
		<div class="SearchButton"><input type="button" class="btn btn-primary btn-sm" onclick="DeSelect();Search('services/getPratiquants.php','all');" value="Rafraichir"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="SectionArea">
		<div id="CSection" class="criteres">
            Affiche uniquement les pratiquants d'une section.<br/>
			Section: 
			<select class="form-select form-select-sm d-inline-block w-auto" id="filterSection" name="filterSection">
				<?php
					$sections = sections::GetAll();
					foreach($sections as $sec)
					{
						echo('<option value="' . $sec->id . '">' . $sec->libelle . '</option>');
					}
				?>
			</select>
		</div>
		<div class="SearchButton"><input type="button" class="btn btn-primary btn-sm" onclick="DeSelect();Search('services/getPratiquants.php','section',$F('filterSection'));" value="Afficher"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="ExamensArea">
		<div id="CSectionExam" class="criteres">
			Section: 
			<select class="form-select form-select-sm d-inline-block w-auto" id="filterSectionExam" name="filterSectionExam">
				<?php
					$sections = sections::GetAll();
					foreach($sections as $sec)
					{
						echo('<option value="' . $sec->id . '">' . $sec->libelle . '</option>');
					}
				?>
			</select>
		</div>
		<div class="SearchButton"><input type="button" class="btn btn-primary btn-sm" onclick="DeSelect();Search('services/getPratiquants.php','examens',$F('filterSectionExam'));" value="Afficher"></div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="ExpirationArea">
		Affiche uniquement les pratiquants dont la licence a expiré.
		<?php
			$nbFormulairesExpires = 0;
			foreach (pratiquants::GetExpired() as $pratExpire)
			{
				if ($pratExpire->HasFormulaire())
				{
					$nbFormulairesExpires++;
				}
			}
		?>
		<div class="SearchButton">
			<input type="button" class="btn btn-primary btn-sm" onclick="DeSelect();Search('services/getPratiquants.php','license','');" value="Afficher">
			<a class="btn btn-outline-primary btn-sm ms-2" href="services/GetLicencesZip.php" target="_blank"><i class="fas fa-file-archive"></i> Télécharger tous les formulaires (<?php echo($nbFormulairesExpires); ?>)</a>
		</div>
	</div>
	
	<div class="SearchAreaContent Invisible" id="UpArea">
		<div id="CSectionUp" class="criteres">
            Pratiquants en age de changer de section.<br/>
			Section:
			<select class="form-select form-select-sm d-inline-block w-auto" id="filterSectionUp" name="filterSectionUp">
				<?php
					$sections = sections::GetAll();
					foreach($sections as $sec)
					{
						if($sec->ageMax === null || $sec->ageMax === '')
							continue;
						echo('<option value="' . $sec->id . '">' . $sec->libelle . '</option>');
					}
				?>
			</select>
			Date:
			<input type="date" class="form-control form-control-sm d-inline-block w-auto" id="filterDateUp" name="filterDateUp" value="<?php echo(date('Y-m-d')); ?>">
		</div>
		<div class="SearchButton"><input type="button" class="btn btn-primary btn-sm" onclick="DeSelect();Search('services/getPratiquants.php','montee',$F('filterSectionUp'),$F('filterDateUp'));" value="Afficher"></div>
	</div>

    <div class="SearchAreaContent Invisible" id="PoubelleArea">
        Affiche les pratiquants supprimés
        <div class="SearchButton"><input type="button" class="btn btn-primary btn-sm" onclick="DeSelect();Search('services/getPratiquants.php','poubelle',0);" value="Afficher"></div>
    </div>
</div>
</form>
