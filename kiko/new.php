<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/theme.css" type="text/css">
		<script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
		<script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
		<script src="js/action.js"						type="text/javascript"></script>
		<script src="js/bootstrap.bundle.min.js"></script>

        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
		<link rel="stylesheet" href="css/New.css" type="text/css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/09e5bbb46b.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	</head>
	<body>
		<?php    
			require_once(dirname(__FILE__)."/config/config.php");
			if($debug)
			{
				error_reporting(E_ERROR);
			}
			require_once(dirname(__FILE__)."/core/pmo/PMO_core/PMO_MyController.php");
			require_once(dirname(__FILE__)."/core/pmo/PMO_core/class_loader/class_pratiquants.php");
			require_once(dirname(__FILE__)."/core/pmo/PMO_core/class_loader/class_section.php");
			require_once(dirname(__FILE__)."/core/pmo/PMO_core/class_loader/class_grades.php");
			require_once(dirname(__FILE__)."/core/pmo/PMO_core/class_loader/class_cotisationsPeriode.php");
		?>
		<form method="post" action="<?php echo($_SERVER['REQUEST_URI']); ?>" name="formNew" id="formNew">
			<div class="container my-3" style="max-width: 700px;">
			<?php

                function formatPhoneNumber($phoneNumber) {
                    $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber ?? '');

                    if(strlen($phoneNumber) > 10) {
                        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
                        $areaCode = substr($phoneNumber, -10, 3);
                        $nextThree = substr($phoneNumber, -7, 3);
                        $lastFour = substr($phoneNumber, -4, 4);

                        $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
                    }
                    else if(strlen($phoneNumber) == 10) {
                        $areaCode = substr($phoneNumber, 0, 4);
                        $firstTwo = substr($phoneNumber, 4, 2);
                        $secondTwo = substr($phoneNumber, 6, 2);
                        $thirdTwo = substr($phoneNumber, 8, 2);

                        $phoneNumber = $areaCode . '/' . $firstTwo . ' ' . $secondTwo . ' ' . $thirdTwo;
                    }
                    else if(strlen($phoneNumber) == 9) {
                        $twoFirst = substr($phoneNumber, 0, 2);
                        $nextThree = substr($phoneNumber, 2, 3);
                        $firstTwo =  substr($phoneNumber, 5, 2);
                        $secondTwo =  substr($phoneNumber, 7, 2);

                        $phoneNumber = $twoFirst . '/' . $nextThree . ' ' . $firstTwo . ' ' . $secondTwo;
                    }

                    return $phoneNumber;
                }

                // Affiche un bouton d'action ; grisé + tooltip si non-admin plutôt que masqué.
                // Le title est sur un <span> englobant : un élément disabled/pointer-events:none
                // ne reçoit plus les événements de survol, donc son propre title ne s'affiche jamais.
                function RenderActionButton($id, $btnClass, $icon, $label, $onclick, $isAdmin) {
                    if ($isAdmin) {
                        echo '<a class="btn ' . $btnClass . '" id="' . $id . '" href="#" onClick="' . $onclick . '"><i class="fas ' . $icon . '"></i> ' . $label . '</a>';
                    } else {
                        echo '<span title="Contactez un administrateur pour modifier ce pratiquant"><a class="btn ' . $btnClass . ' disabled" id="' . $id . '" href="#" tabindex="-1" aria-disabled="true"><i class="fas ' . $icon . '"></i> ' . $label . '</a></span>';
                    }
                }

				//import_request_variables('GP');
				extract($_GET);
				extract($_POST);
				
				$new = true;
				
				//$id = $_REQUEST['id'];
				//$edit = $_REQUEST['edit'];
				
				$id = filter_input(INPUT_GET , 'id');
				$edit = filter_input(INPUT_POST, 'edit');
				$action = filter_input(INPUT_POST, 'action');
				$isAdmin = in_array($_SERVER['REMOTE_USER'] ?? '', $admins);

                $pratiquant = PMO_MyObject::factory('pratiquants');
                if($id)
				{
					$pratiquant->id = $id;
					$pratiquant->load();
					$new = false;
				}
				else
				{
					$edit = true;
				}
				
				if($debug)
				{
					echo('Action: ' . $action);
				}
				if($action == 'undelete' && $isAdmin)
                {
                    $pratiquant->deleted = 0;
                    $pratiquant->commit();

                    $edit = false;
                }
				else if(($action == 'add' || $action == 'save') && $isAdmin)
				{
					if($action == 'add')
					{
						$pratiquant = PMO_MyObject::factory('pratiquants');
					}
						
					$pratiquant->nom = $nom;
					$pratiquant->prenom = $prenom;
					$pratiquant->sexe = $sexe;
					$pratiquant->photo = $photo;
					$pratiquant->adresse = $adresse;
					$pratiquant->codePostal = $cp;
					$pratiquant->commune = $commune;
                    $pratiquant->telephone = $telephone;
                    $pratiquant->gsm = $gsm;
                    $pratiquant->email = $email;
                    $pratiquant->pub = $pub;
                    $pratiquant->fk_famille = $famille;
					$datet = explode("/", $naissance);
					$date = date_create();
					date_date_set($date , $datet[2] , $datet[1], $datet[0]);
					$pratiquant->naissance = date_format($date, "Y-m-d");
					$pratiquant->licenceNbr = $licence;
					$datet = explode("/", $licenceDate);
					date_date_set($date , $datet[2] , $datet[1], $datet[0]);
					$pratiquant->licenceDate = date_format($date, "Y-m-d");
					
					echo($section);
					$pratiquant->fk_section = $section;
					$pratiquant->AddPresences($presences);
					//$new->fk_famille = 
					
                    // Save grades
					if($action != 'add' && $pratiquant->GetGrades() != NULL)
					{
						if($debug)
						{
							echo("- save grades:");
						}
						foreach($pratiquant->GetGrades() as $grade)
						{
							if($debug)
							{
								echo("{");
							}
							//$gradeDate =  $_REQUEST['grade' . $grade->fk_grade];
							$gradeDate = filter_input(INPUT_POST , 'grade' . $grade->fk_grade);
							if($debug)
							{
								echo($gradeDate . " - ");
							}
							$datet = explode("/", $gradeDate);
							date_date_set($date , $datet[2] , $datet[1], $datet[0]);
							$grade->date = date_format($date, "Y-m-d");
							$grade->commit();
							if($debug)
							{
								echo("Grade saved}");
							}
						}
					}
					
                    // Add grades
					$i = 0;
					do
					{
						//$ngradeId =  $_REQUEST['newGradeId' . $i];
						//$ngradeDate = $_REQUEST['newGrade' . $i];
						$ngradeId = filter_input(INPUT_POST , 'newGradeId' . $i);
						$ngradeDate = filter_input(INPUT_POST , 'newGrade' . $i);
						
						if($ngradeId != '')
						{
							$grade = PMO_MyObject::factory('passages');

							$grade->fk_pratiquant = $pratiquant->id;
							$grade->fk_grade = $ngradeId;
							$datet = explode("/", $ngradeDate);
							date_date_set($date , $datet[2] , $datet[1], $datet[0]);
							$grade->date = date_format($date, "Y-m-d");
							if($debug)
							{
								echo($ngradeId . " " . $ngradeDate . " - ");
							}
							$grade->commit();
						}

						++$i;
					}while($ngradeId != '');

                    // Add periode
					$i = 0;
					do
					{
						//$nperiodeId =  $_REQUEST['newPeriodeId' . $i];
						$nperiodeId = filter_input(INPUT_POST , 'newPeriodeId' . $i);
						
						if($nperiodeId != '')
						{
							$periode = PMO_MyObject::factory('cotisationsPeriode');
                            
							$periode->fk_pratiquant = $pratiquant->id;
							$periode->fk_periode = $nperiodeId;
                            $periode->prixPaye = 0;
							$periode->GenerateCommunication($pratiquant->id, $nperiodeId);
                            $periode->enOrdre = 0;
							$periode->commit();
						}
                        
						++$i;
					}while($nperiodeId != '');
                    
                    // Save periode
                    if($action == 'save')
                    {
                    	$i = 0;
                    	do{
	                    	$periodeId    = filter_input(INPUT_POST , 'periodeId'  . $i);
							$periodeEnOrdre = filter_input(INPUT_POST , 'periodeOrdre' . $i);

							if($periodeEnOrdre == 'enOrdre')
							{
								$periode = PMO_MyObject::factory('cotisationsPeriode');
	                            
								$periode->fk_pratiquant = $pratiquant->id;
								$periode->fk_periode = $periodeId;
	                            $periode->prixPaye = 0;
								$periode->GenerateCommunication($pratiquant->id, $nperiodeId);
	                            $periode->enOrdre = 1;
								$periode->commit();
							}
							++$i;
						}while($periodeId != '');
                    }
					
					$pratiquant->commit();
					
					$edit = false;
					//$action = '';
				}
			?>
			<input type="hidden" id="action" name="action" />
			<input type="hidden" id="edit" name="edit" value="<?php echo($edit); ?>" />

			<div class="d-flex gap-2 mb-3 sticky-top bg-body py-2">
                <button type="button" class="btn btn-outline-secondary" onclick="window.close();"><i class="fas fa-times"></i> Fermer</button>
                <?php if(!$edit){ RenderActionButton('Edit', 'btn-primary', 'fa-edit', 'Modifier', "SetHidden('edit', 'true'); \$('formNew').submit()", $isAdmin); } ?>
                <?php if($edit){ RenderActionButton('Save', 'btn-success', 'fa-save', 'Enregistrer', "SetHidden('action', '" . ($new?'add':'save') . "'); \$('formNew').submit()", $isAdmin); } ?>
                <?php if($edit){ RenderActionButton('Cancel', 'btn-outline-secondary', 'fa-window-close', 'Annuler', "SetHidden('edit', ''); \$('formNew').submit()", $isAdmin); } ?>
                <?php if($pratiquant->deleted){ RenderActionButton('Undelete', 'btn-warning', 'fa-recycle', 'Restaurer', "SetHidden('action', 'undelete'); \$('formNew').submit()", $isAdmin); } ?>
			</div>

			<div class="card mb-3">
				<div class="card-header fw-bold <?php echo($pratiquant->deleted?'bg-danger text-white':''); ?>">Identité</div>
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-8">
							<div class="row mb-2 align-items-center">
								<label class="col-sm-4 col-form-label fw-bold">Nom:</label>
								<div class="col-sm-8">
			                        <?php if($edit){ ?>
			                            <input type="text" class="form-control" autocomplete="off" id="nom"				name="nom"		 value="<?php echo($pratiquant->nom); ?>">
			                        <?php } else {
			                            echo($pratiquant->nom);
			                        } ?>
								</div>
							</div>

							<div class="row mb-2 align-items-center">
								<label class="col-sm-4 col-form-label fw-bold">Prénom:</label>
								<div class="col-sm-8">
			                        <?php if($edit){ ?>
			                            <input type="text" class="form-control" autocomplete="off" id="prenom"	name="prenom"	 value="<?php echo($pratiquant->prenom); ?>">
			                        <?php } else {
			                            echo($pratiquant->prenom);
			                        } ?>
								</div>
							</div>

							<div class="row mb-2 align-items-center">
								<label class="col-sm-4 col-form-label fw-bold">Naissance:</label>
								<div class="col-sm-8">
			                        <?php if($edit){ ?>
			                            <input type="text" class="form-control" autocomplete="off" id="naissance" name="naissance" value="<?php echo(date('d/m/Y', strtotime($pratiquant->naissance ?? ''))); ?>">
			                        <?php } else {
			                            echo(date('d/m/Y', strtotime($pratiquant->naissance)));
			                        } ?>
								</div>
							</div>

							<div class="row mb-2 align-items-center">
								<label class="col-sm-4 col-form-label fw-bold">Sexe:</label>
								<div class="col-sm-8">
			                        <?php if($edit){ ?>
			                            <select class="form-select" id="sexe" name="sexe">
			                                <option value="0" <?php echo($pratiquant->sexe?'':'selected'); ?>>Femme</option>
			                                <option value="1" <?php echo($pratiquant->sexe?'selected':''); ?>>Homme</option>
			                            </select>
			                        <?php } else {
			                            echo($pratiquant->sexe?'Homme':'Femme');
			                        } ?>
								</div>
							</div>
                           <div class="row mb-2 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Chef famille:</label>
                                <div class="col-sm-8">
                                    <?php if($edit){ ?>
                                        <select class="form-select" id="famille" name="famille">
                                            <?php
                                            $selected = "";
                                            if($new || !$pratiquant->IsFamilyMember())
                                            {
                                                $selected = "selected";
                                            }
                                            echo('<option value="' . $pratiquant->id . '" ' . $selected . '>-- Pas de famille --</option>');

                                            function RenderChef($chef, $selected)
                                            {
                                                echo('<option value="' . $chef->id . '" ' . $selected . '>' . $chef->nom . " " . $chef->prenom . '</option>');
                                            }

                                            if(!$new)
                                            {
                                                $myNameMembers = pratiquants::GetByLastNameButMe($pratiquant->nom, $pratiquant->id);
                                                foreach($myNameMembers as $myNameMember)
                                                {
                                                    RenderChef($myNameMember, '');
                                                }
                                                if(count($myNameMembers) > 0)
                                                {
                                                    echo('<option value="">-----------</option>');
                                                }
                                            }

                                            if($new)
                                            {
                                                $id = 0;
                                            }
                                            else
                                            {
                                                $id = $pratiquant->id;
                                            }

                                            $potentialsChefs = pratiquants::GetChefsButMe($id);

                                            foreach($potentialsChefs as $potentialChef)
                                            {
                                                $selected = "";
                                                if($pratiquant->fk_famille == $potentialChef->id)
                                                {
                                                    $selected = "selected";
                                                }
                                                RenderChef($potentialChef, $selected);
                                            }
                                            ?>
                                        </select>
                                    <?php } else {
                                        if($pratiquant->GetFamilyHead())
                                        {
                                            echo("<a target='new' href='new.php?id=" . $pratiquant->fk_famille . "'>" . $pratiquant->GetFamilyHead()->nom . " " . $pratiquant->GetFamilyHead()->prenom . "</a>");
                                        }

                                    } ?>
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <label class="col-sm-4 col-form-label fw-bold">Publicité:</label>
                                <div class="col-sm-8">
                                    <?php
                                    if($edit){ ?>
                                        <select class="form-select" id="pub" name="pub">
                                            <option value="0" <?php echo($pratiquant->UnknownPub()?'selected':''); ?>>Inconnu</option>
                                            <option value="1" <?php echo($pratiquant->AllowPub()?'selected':''); ?>>Autorisé</option>
                                            <option value="-1" <?php echo($pratiquant->DisallowPub()?'selected':''); ?>>Interdit</option>
                                        </select>
                                    <?php } else {
                                        if($pratiquant->UnknownPub())
                                        {
                                            echo("Inconnu");
                                        }
                                        elseif ($pratiquant->AllowPub())
                                        {
                                            echo("Autorisé");
                                        }
                                        elseif ($pratiquant->DisallowPub())
                                        {
                                            echo("Interdit");
                                        }
                                    } ?>
                                </div>
                            </div>

						</div>
						<div class="col-4 text-center">
							<img class="img-thumbnail" style="max-height:170px;" src="<?php echo($pratiquant->GetPhotoHttpPath()); ?>" title="<?php echo($pratiquant->GetPhotoTitle()); ?>"/>
						</div>
					</div>

					<?php if($edit){ ?>
					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Photo:</label>
						<div class="col-sm-8">
                            <input type="text" class="form-control" autocomplete="off" id="photo" name="photo" value="<?php echo($pratiquant->photo); ?>">
						</div>
					</div>
					<?php } ?>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Adresse:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" autocomplete="off" id="adresse"		name="adresse"	 value="<?php echo($pratiquant->adresse); ?>">
                            <?php } else {
                                echo($pratiquant->adresse);
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Code postal:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" autocomplete="off" id="cp"		name="cp"		 value="<?php echo($pratiquant->codePostal); ?>">
                            <?php } else {
                                echo($pratiquant->codePostal);
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Commune:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" autocomplete="off" id="commune"		name="commune"	 value="<?php echo($pratiquant->commune); ?>">
                            <?php } else {
                                echo($pratiquant->commune);
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Téléphone:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" autocomplete="off" id="telephone" name="telephone" value="<?php echo($pratiquant->telephone); ?>">
                            <?php } else {
                                    echo(formatPhoneNumber($pratiquant->telephone));
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">GSM:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" autocomplete="off" id="gsm" name="gsm" value="<?php echo($pratiquant->gsm); ?>">
                            <?php } else {
                                echo(formatPhoneNumber($pratiquant->gsm));
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">eMail:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" autocomplete="off" id="email" name="email" value="<?php echo($pratiquant->email); ?>">
                            <?php } else {
                                echo("<a href='mailto:"  . $pratiquant->email . "' target='new'>" . $pratiquant->email . "</a>");
                            } ?>
						</div>
					</div>
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header fw-bold">Club et fédé</div>
				<div class="card-body">
					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">N° licence:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" id="licence" name="licence"	autocomplete="off"	value="<?php echo($pratiquant->licenceNbr); ?>">
                            <?php } else {
                                echo($pratiquant->licenceNbr);
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Expiration:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <input type="text" class="form-control" id="licenceDate" name="licenceDate"		value="<?php echo(date('d/m/Y', strtotime($pratiquant->licenceDate ?? ''))); ?>">
                            <?php } else {
                                echo(date('d/m/Y', strtotime($pratiquant->licenceDate)));
                            } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Grade:</label>
						<div class="col-sm-8">
                                <?php
                                    if($action != 'add')
                                    {
                                        $grade = $pratiquant->GetGrade();
                                        if($grade != NULL)
                                            echo($grade->GetGrade()->libelle);
                                    }
                                ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Section:</label>
						<div class="col-sm-8">
                            <?php if($edit){ ?>
                                <select class="form-select" id="section" name="section">
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
                            <?php } else {
                                echo($pratiquant->GetSection()->libelle);
                            } ?>
						</div>
					</div>
				</div>
			</div>

		<?php if($id){
			// Lecture directe via getAttribute() : PMO_MyObject n'a pas de __isset(),
			// donc isset()/empty()/?? sur $pratiquant->fede_xxx renverraient toujours "vide".
			$fedeLicence = $pratiquant->getAttribute('fede_licence');
			$fedeLicenceDate = $pratiquant->getAttribute('fede_licence_date');
			$fedeNaissance = $pratiquant->getAttribute('fede_naissance');
			$fedeEmail = $pratiquant->getAttribute('fede_email');
			$fedeAdresse = $pratiquant->getAttribute('fede_adresse');
			$licenceNbr = $pratiquant->getAttribute('licenceNbr');
			$fedeFormulaireExiste = $pratiquant->HasFormulaire();

			// Signale un champ fédé quand il diffère de la valeur club - seulement si la
			// fédé a une valeur (sinon "pas encore scrapé" serait signalé comme un conflit).
			$diffLicence = $fedeLicence !== null && $fedeLicence !== '' && (string)$fedeLicence !== (string)$licenceNbr;
			$diffLicenceDate = !empty($fedeLicenceDate) && $fedeLicenceDate !== $pratiquant->licenceDate;
			$diffNaissance = !empty($fedeNaissance) && $fedeNaissance !== $pratiquant->naissance;
			$diffEmail = !empty($fedeEmail) && strcasecmp($fedeEmail, $pratiquant->email ?? '') !== 0;

			// La fédé colle rue et commune dans un seul champ, alors que le club les garde
			// séparés (adresse / commune), sans forcément la même ponctuation ("rue X, 12"
			// vs "Rue X 12Commune") - on normalise (minuscule, lettres/chiffres uniquement)
			// avant de vérifier que les deux morceaux du club se retrouvent dans le texte
			// de la fédé, peu importe l'ordre/la ponctuation utilisés.
			$normalizeAdresse = function ($str) {
				return preg_replace('/[^\p{L}\p{N}]+/u', '', mb_strtolower(trim($str ?? ''), 'UTF-8'));
			};
			$diffAdresse = false;
			if ($fedeAdresse !== null && $fedeAdresse !== '') {
				$fedeAdresseNorm = $normalizeAdresse($fedeAdresse);
				$clubAdresseNorm = $normalizeAdresse(($pratiquant->adresse ?? '') . ' ' . ($pratiquant->commune ?? ''));
				$diffAdresse = $fedeAdresseNorm != $clubAdresseNorm;
			}

			function AlerteDiff($diff) {
				if ($diff) {
					echo '<i class="fas fa-exclamation-triangle text-warning ms-1" title="Différent de la fiche club"></i>';
				}
			}
		?>
			<div class="card mb-3">
				<div class="card-header fw-bold">Fédération</div>
				<div class="card-body">
					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">N° licence:</label>
						<div class="col-sm-8"><?php echo($fedeLicence !== null && $fedeLicence !== '' ? $fedeLicence : '—'); AlerteDiff($diffLicence); ?></div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Expiration:</label>
						<div class="col-sm-8">
							<?php echo($fedeLicenceDate ? date('d/m/Y', strtotime($fedeLicenceDate)) : '—'); AlerteDiff($diffLicenceDate); ?>
							<?php if ($diffLicenceDate && $edit) { ?>
								<button type="button" class="btn btn-sm btn-outline-warning ms-2" onclick="SetHidden('licenceDate', '<?php echo(date('d/m/Y', strtotime($fedeLicenceDate))); ?>')">
									<i class="fas fa-sync"></i> Reporter au club
								</button>
							<?php } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Naissance:</label>
						<div class="col-sm-8"><?php echo($fedeNaissance ? date('d/m/Y', strtotime($fedeNaissance)) : '—'); AlerteDiff($diffNaissance); ?></div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">eMail:</label>
						<div class="col-sm-8">
							<?php if($fedeEmail){ ?>
								<a href="mailto:<?php echo($fedeEmail); ?>" target="new"><?php echo($fedeEmail); ?></a>
							<?php } else { echo('—'); } ?>
							<?php AlerteDiff($diffEmail); ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Adresse:</label>
						<div class="col-sm-8"><?php echo($fedeAdresse !== null && $fedeAdresse !== '' ? $fedeAdresse : '—'); AlerteDiff($diffAdresse); ?></div>
					</div>

					<?php if ($fedeFormulaireExiste) { ?>
					<div class="row mb-2 align-items-center">
						<label class="col-sm-4 col-form-label fw-bold">Formulaire:</label>
						<div class="col-sm-8">
							<a href="download_formulaire.php?id=<?php echo($id); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
								<i class="fas fa-file-pdf"></i> Télécharger le formulaire de renouvellement
							</a>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php if($id){ ?>
			<div class="card mb-3">
				<div class="card-header fw-bold">Grades</div>
				<div class="card-body New">
					<?php
						if($pratiquant != NULL)
						{
							// Récupération des dates de passages de grades
							$grades = $pratiquant->GetGrades();
							if(count($grades) > 0)
							{
								foreach($grades as $grade)
								{
					?>
									<div class="FieldName Grade"><?php echo($grade->GetGrade()->libelle); ?>:</div> 
									<div class="InputField">
										<?php if($edit){ ?>
											<input type="text" name="grade<?php echo($grade->fk_grade); ?>" id="grade<?php echo($grade->fk_grade); ?>" value="<?php echo(date('d/m/Y', strtotime($grade->date))); ?>">
										<?php } else {
											echo(date('d/m/Y', strtotime($grade->date)));
										} ?>
									</div><br>
					<?php		} 
							}
						}
			
				if($edit){
					?>
					<div id="NewGrade"></div>

					<br>
						<select class="form-select form-select-sm d-inline-block w-auto" id="gradeList" name="gradeList">
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

						<a class="btn btn-primary btn-sm ms-2" id="Add" href="#" onClick="AddGrade($('gradeList').value, $('gradeList').options[$('gradeList').options.selectedIndex].innerHTML); return false;">Ajouter</a>
					<br>
			   <?php } ?>
				</div>
			</div>
		<?php } ?>

		<?php if($id){ ?>
			<div class="card mb-3">
				<div class="card-header fw-bold">Statistiques</div>
				<div class="card-body">
					<div class="row mb-2 align-items-center">
						<label class="col-sm-6 col-form-label fw-bold">Présences depuis le dernier grade:</label>
						<div class="col-sm-6">
							<?php echo($pratiquant->GetPresencesCountFromLastGrade()); ?> / <?php echo($pratiquant->GetPresencesNeededForNextGrade()); ?>
							<?php if($edit){ ?>&nbsp;Ajouter des préseces&nbsp;<input type="text" class="form-control d-inline-block w-auto" name="presences" id="presences" value="0" size="3"><?php } ?>
						</div>
					</div>

					<div class="row mb-2 align-items-center">
						<label class="col-sm-6 col-form-label fw-bold">Présences pour cette saison:</label>
						<div class="col-sm-6"><?php echo($pratiquant->GetPresencesCountForThisSeason()); ?></div>
					</div>

					<!--
					<div class="row mb-2 align-items-center">
						<label class="col-sm-6 col-form-label fw-bold">Stages du club cette saison:</label>
						<div class="col-sm-6"><?php echo($pratiquant->GetCountStages()); ?></div>
					</div>
					-->
				</div>
			</div>
		<?php } ?>

        <?php if($id){ ?>
            <div class="card mb-3">
                <div class="card-header fw-bold">Payements</div>
                <div class="card-body New">
                    <div class="FieldName Stat">Périodes payées:</div>
                    <?php
                    $periodes = $pratiquant->GetPaiedPeriodForSeason();
					$count = count($periodes);
					if($count > 0)
					{
						foreach($periodes as $cperiode)
						{
							$periode = $cperiode->GetPeriode();

							?><div class="Periode"><?php echo($periode->libelle);?><img class='Warning' src='css/images/001_06.png'></div><?php
						}
					}
                    
                    ?>
                    <div id="NewPeriode"></div>
                    <br/>
            
					<?php 
						$periodes = $pratiquant->GetNoPayPeriod();
						$count = count($periodes);
					?>
                    <div class="FieldName Stat">Périodes non payées:</div>
                    <div class="InputField"><?php echo($count);?></div>
                    <?php
                        if($count > 0)
						{
							$i = 0;
							foreach($periodes as $periode)
							{

								?><div class="Periode"><?php echo($periode->libelle); ?>: <?php
								if($edit)
								{
									$idid = "periodeId" . $i;
									$idOrdre = "periodeOrdre" . $i;
									?>
										<input type="hidden" name="<?php echo($idid); ?>" id="<?php echo($idid); ?>" value="<?php echo($periode->id); ?>">
										<input type="checkbox" name="<?php echo($idOrdre); ?>" id="<?php echo($idOrdre); ?>" value="enOrdre" />
									<?php
								}
								else
								{
									?><img class='Warning' src='css/images/001_05.png'><?php
								}
								?></div><?php
								++$i;
							}
						}
                    
                    ?>
                    <br/>


                    <?php if($edit){ ?>&nbsp;Ajouter une période&nbsp;
                       <select class="form-select form-select-sm d-inline-block w-auto" id="periodeList" name="periodeList">
						<?php
                            $periodes = periodes::GetNewForPratiquant($pratiquant->id);
                            $i = 0;
                            foreach($periodes as $periode)
                            {
                                $selected = "";
                                if($i == 0)
                                {
                                    $selected = "selected";
                                }
                                echo('<option value="' . $periode->id . '" ' . $selected . '>' . $periode->libelle . '</option>');
                                $i++;
                            }
						?>
						</select>
                        <a class="btn btn-primary btn-sm ms-2" id="AddPeriode" href="#" onClick="AddPeriode($('periodeList').value, $('periodeList').options[$('periodeList').options.selectedIndex].innerHTML); return false;">Ajouter</a>
                    <?php } ?>
                    <br/>
            
            
            
                    <br/>
                    <div class="FieldName Stat">Cours non payés:</div>
                    <div class="InputField">
                        <?php
                        if($pratiquant->GetNoPayLesson())
                        {
                            foreach($pratiquant->GetNoPayLesson() as $presences)
                                echo($presences->date . "<br/>");
                        }
                        ?>
                    </div>
                    <br/>
    
                </div>
            </div>
        <?php } ?>

			</div>
		</form>
	</body>
</html>
