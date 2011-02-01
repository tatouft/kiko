<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("core/pmo/PMO_core/class_loader/class_section.php");
	require_once("core/pmo/PMO_core/class_loader/class_grades.php");
?>
<html>
	<head>
		<script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
		<script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
		<script src="js/action.js"						type="text/javascript"></script>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
		<link rel="stylesheet" href="css/new.css" type="text/css">
	</head>
	<body>
		<form method="post" action="<? echo($_SERVER['REQUEST_URI']); ?>" name="formNew" id="formNew">
			<?php
				import_request_variables('GP');
				
				$new = true;
				
				$id = $_REQUEST['id'];
				$edit = $_REQUEST['edit'];
				if($id)
				{
					$pratiquant = PMO_MyObject::factory('pratiquants');
					$pratiquant->id = $id;
					$pratiquant->load();
					$new = false;
				}
				else
				{
					$edit = true;
				}
				
				if($debug)
					echo('Action: ' . $action);
				if($action == 'add' || $action == 'save')
				{
					if($action == 'add')
					{
						$pratiquant = PMO_MyObject::factory('pratiquants');
					}
						
					$pratiquant->nom = $nom;
					$pratiquant->prenom = $prenom;
					$pratiquant->adresse = $adresse;
					$pratiquant->codePostal = $cp;
					$pratiquant->commune = $commune;
					$datet = explode("/", $naissance);
					$date = date_create();
					date_date_set($date , $datet[2] , $datet[1], $datet[0]);
					$pratiquant->naissance = date_format($date, "Y-m-d");
					$pratiquant->licenceNbr = $licence;
					$datet = explode("/", $licenceDate);
					date_date_set($date , $datet[2] , $datet[1], $datet[0]);
					$pratiquant->licenceDate = date_format($date, "Y-m-d");
					
					$pratiquant->fk_section = $section;
					//$new->fk_famille = 
					
					if($action != 'add' && $pratiquant->GetGrades() != NULL)
					{
						if($debug)
							echo("save grades:");
						foreach($pratiquant->GetGrades() as $grade)
						{
							if($debug)
								echo("{");
							$gradeDate =  $_REQUEST['grade' . $grade->fk_grade];
							if($debug)
								echo($gradeDate . " - ");
							$datet = explode("/", $gradeDate);
							date_date_set($date , $datet[2] , $datet[1], $datet[0]);
							$grade->date = date_format($date, "Y-m-d");
							$grade->commit();
							if($debug)
								echo("Grade saved}");
						}
					}
					
					$i = 0;
					$gradeId = '';
					do
					{
						$ngradeId =  $_REQUEST['newGradeId' . $i];
						$ngradeDate = $_REQUEST['newGrade' . $i];
						
						if($ngradeId != '')
						{
							$grade = PMO_MyObject::factory('passages');

							$grade->fk_pratiquant = $pratiquant->id;
							$grade->fk_grade = $ngradeId;
							$datet = explode("/", $ngradeDate);
							date_date_set($date , $datet[2] , $datet[1], $datet[0]);
							$grade->date = date_format($date, "Y-m-d");
							if($debug)
								echo($ngradeId . " " . $ngradeDate . " - ");
							$grade->commit();
						}

						++$i;
					}while($ngradeId != '');
					
					
					$pratiquant->commit();
					
					$edit = false;
					//$action = '';
				}
			?>
			<input type="hidden" id="action" name="action" />
			<input type="hidden" id="edit" name="edit" value="<? echo($edit); ?>" />

			<div class="List Contents">
				<div id="NewTitle">Identité</div>
				<div class="New">
					<div class="FieldName">Nom:</div>	
					<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="nom"				name="nom"		 value="<? echo($pratiquant->nom); ?>">
						<? } else {
							echo($pratiquant->nom);
						} ?>
					</div>

					<div class="InputField Photo"><img src="<? echo($pratiquant->GetPhotoHttpPath()); ?>" title="<? echo($pratiquant->GetPhotoTitle()); ?>"/></div>	<div class="FieldName FieldPhoto">Photo:</div>
					
					<br><div class="FieldName">Prénom:</div>
					<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="prenom"	name="prenom"	 value="<? echo($pratiquant->prenom); ?>">
						<? } else {
							echo($pratiquant->prenom);
						} ?>
					</div><br>

					<div class="FieldName">Adresse:</div>	<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="adresse"		name="adresse"	 value="<? echo($pratiquant->adresse); ?>">
						<? } else {
							echo($pratiquant->adresse);
						} ?>
					</div><br>
					
					<div class="FieldName">Code postal:</div>	<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="cp"		name="cp"		 value="<? echo($pratiquant->codePostal); ?>">
						<? } else {
							echo($pratiquant->codePostal);
						} ?>
					</div><br>
					
					<div class="FieldName">Commune:</div>	<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="commune"		name="commune"	 value="<? echo($pratiquant->commune); ?>">
						<? } else {
							echo($pratiquant->commune);
						} ?>
					</div><br>
					
					<div class="FieldName">Naissance:</div>	<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="naissance"	name="naissance" value="<? echo(date('d/m/Y', strtotime($pratiquant->naissance))); ?>">
						<? } else {
							echo(date('d/m/Y', strtotime($pratiquant->naissance)));
						} ?>
					</div><br>
					
					<div class="FieldName">Famille:</div>	<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="famille"		name="famille"	 value="<? echo($pratiquant->fk_famille); ?>">
						<? } else {
							echo($pratiquant->fk_famille);
						} ?>
					</div><br>
				</div>
			</div>

			<div class="List Contents">
				<div id="NewTitle">Club et fédé</div>
				<div class="New">
					<div class="FieldName">N° licence:</div> 
					<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="licence" name="licence"		value="<? echo($pratiquant->licenceNbr); ?>">
						<? } else {
							echo($pratiquant->licenceNbr);
						} ?>
					</div><br>

					<div class="FieldName">Expiration:</div>
					<div class="InputField">
						<? if($edit){ ?>
							<input type="text" id="licenceDate" name="licenceDate"		value="<? echo(date('d/m/Y', strtotime($pratiquant->licenceDate))); ?>">
						<? } else {
							echo(date('d/m/Y', strtotime($pratiquant->licenceDate)));
						} ?>
					</div><br>

					<div class="FieldName">Grade:</div> 
					<div class="InputField">
							<? 
								if($pratiquant != NULL && $action != 'add')
								{
									$grade = $pratiquant->GetGrade();		
									if($grade != NULL)
										echo($grade->GetGrade()->libelle); 
								}
							?>
					</div><br>

					<div class="FieldName">Section:</div> 
					<div class="InputField">
						<? if($edit){ ?>
							<select id="section" name="section">
							<?php
								$sections = sections::GetAll();
								$i = 0;
								foreach($sections as $sec)
								{
									$selected = "";
									if(($new && $i == 0) || $pratiquant->fk_section == $sec->id)
									{
										$selected = "selected";
									}
									echo('<option value="' . $sec->id . '" ' . $selected . '>' . $sec->libelle . '</option>');
									$i++;
								}
							?>
							</select>
						<? } else {
							echo($pratiquant->GetSection()->libelle);
						} ?>
					</div><br>
				</div>
			</div>

		<? if($id){ ?>
			<div class="List Contents">
				<div id="NewTitle">Grades</div>
				<div class="New">
					<?
						if($pratiquant != NULL)
						{
							// Récupération des dates de passages de grades
							$grades = $pratiquant->GetGrades();
							if(count($grades) > 0)
							{
								foreach($grades as $grade)
								{
					?>
									<div class="FieldName Grade"><? echo($grade->GetGrade()->libelle); ?>:</div> 
									<div class="InputField">
										<? if($edit){ ?>
											<input type="text" name="grade<? echo($grade->fk_grade); ?>" id="grade<? echo($grade->fk_grade); ?>" value="<? echo(date('d/m/Y', strtotime($grade->date))); ?>">
										<? } else {
											echo(date('d/m/Y', strtotime($grade->date)));
										} ?>
									</div><br>
					<?			} 
							}
						}
			
				if($edit){
					?>
					<div id="NewGrade"></div>

					<br>
						<select id="gradeList" name="gradeList">
						<?php
							$grades = grades::GetBySection($pratiquant->fk_section);
							$i = 0;
							foreach($grades as $grade)
							{
								$selected = "";
								if($i == 0)
								{
									$selected = "selected";
								}
								echo('<option value="' . $grade->id . '" ' . $selected . '>' . $grade->libelle . '</option>');
								$i++;
							}
						?>
						</select>
						
						<a class="Button" id="Add" href="#" onClick="AddGrade($('gradeList').value, $('gradeList').options[$('gradeList').options.selectedIndex].innerHTML);">Ajouter</a>
					<br>
			   <? } ?>
				</div>
			</div>
		<? } ?>

			<div class="Contents">
				<? if(!$edit){ ?><a class="Button" id="Edit" href="#" onClick="SetHidden('edit', 'true'); $('formNew').submit()"><img src="css/images/24.png"> Modifier</a><? } ?>
				<? if($edit){ ?><a class="Button" id="Save" href="#" onClick="SetHidden('action', '<? echo($new?'add':'save'); ?>'); $('formNew').submit()"><img src="css/images/45.png"> Enregistrer</a><? } ?>
				<? if($edit){ ?><a class="Button" id="Cancel" href="#" onClick="SetHidden('edit', ''); $('formNew').submit()"><img src="css/images/001_29.png"> Annuler</a><? } ?>
			</div>
		</form>
	</body>
</html>