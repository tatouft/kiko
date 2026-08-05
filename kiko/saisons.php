<?php
    require_once("config/config.php");
    require_once("core/pmo/PMO_core/PMO_MyController.php");
    require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
    require_once("core/pmo/PMO_core/class_loader/class_periodes.php");
    ?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/theme.css" type="text/css">
        <script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
        <script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
        <script src="js/action.js"						type="text/javascript"></script>
        <script src="js/bootstrap.bundle.min.js"></script>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/general.css" type="text/css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="favicon.png" />
    </head>
    <body>
        <?php
            $CurrentPage = "admin";
            require_once("controls/PageHeader.php");
            ?>
        <div class="container my-3" style="max-width: 700px;">
            <div class="card" id='Saisons'>
                <div class="card-header fw-bold">Saisons</div>
                <div class="card-body">
                <form method="post" action="<?php echo($_SERVER['REQUEST_URI']); ?>" name="formList" id="formList">
                    <?php
                        extract($_GET);
                        extract($_POST);

                        $action = filter_input(INPUT_POST, 'action');
                        $isAdmin = in_array($_SERVER['REMOTE_USER'] ?? '', $admins);
                        if($action == 'add' && $isAdmin)
                        {
                            $i = 0;
                            do
                            {
                                $newPeriodeLong = filter_input(INPUT_POST , 'newPeriodeLong' . $i);
                                $newPeriodeCourt = filter_input(INPUT_POST , 'newPeriodeCourt' . $i);
                                $newPeriodeDebut = filter_input(INPUT_POST , 'newPeriodeDebut' . $i);
                                $newPeriodeFin = filter_input(INPUT_POST , 'newPeriodeFin' . $i);
                                
                                if($newPeriodeDebut != '')
                                {
                                    $periode = PMO_MyObject::factory('periodes');

                                    $periode->libelle = $newPeriodeLong;
                                    $periode->libelleCourt = $newPeriodeCourt;
                                    $datet = explode("/", $newPeriodeDebut);
                                    $date = date_create();
                                    date_date_set($date , $datet[2] , $datet[1], $datet[0]);
                                    $periode->dateDebut = date_format($date, "Y-m-d");
                                    $datet = explode("/", $newPeriodeFin);
                                    $date = date_create();
                                    date_date_set($date , $datet[2] , $datet[1], $datet[0]);
                                    $periode->dateFin = date_format($date, "Y-m-d");
                                    $periode->commit();
                                }

                                ++$i;
                            }while($newPeriodeDebut != '');
                        }

                    ?>
                    <input type="hidden" name="action" id="action">
                    <div class="NewPeriode">
                        <div class="Header">
                            <div class='libelle'> Libellé </div>
                            <div class='court'> Court </div>
                            <div class='debut date'> Début </div>
                            <div class='fin date'> Fin </div>
                        </div>
                        <?php

                            $saisons = periodes::GetAllCurent();
                            foreach($saisons as $saison)
                            {
                                echo("<div>");
                                echo("<div class='libelle'>" . $saison->libelle . "</div>");
                                echo("<div class='court'>" . $saison->libelleCourt . "</div>");
                                echo("<div class='debut date'>" . date_create($saison->dateDebut)->format('d/m/Y') . "</div><div class='fin date'>" . date_create($saison->dateFin)->format('d/m/Y') ."</div>");
                                echo("</div>");
                            }
                        ?>
                    </div>
                    <div class="NewPeriode" id="NewPeriode" name="NewPeriode">
                    </div>
                    <div id="NewPeriodeFields">
                        <div class="mb-3">
                            <a class="btn btn-outline-primary" id="AddSeason" href="#" onClick="var y = prompt('Année de début de la saison (ex: 2025 pour la saison 2025-2026) :', SuggestSeasonStartYear()); if(y) AddSeason(parseInt(y, 10)); return false;"><i class="fas fa-calendar-plus"></i> Créer une saison complète</a>
                        </div>
                        <div class="mb-2"><label class="form-label mb-0">Libellé long:</label> <input type="text" class="form-control d-inline-block w-auto" id="libelleLong" name="libelleLong"></div>
                        <div class="mb-2"><label class="form-label mb-0">Libellé court:</label> <input type="text" class="form-control d-inline-block w-auto" id="libelleCourt" name="libelleCourt"></div>
                        <div class="mb-2"><label class="form-label mb-0">Date de début:</label> <input type="text" class="form-control d-inline-block w-auto" id="dateDebut" name="dateDebut"></div>
                        <div class="mb-2"><label class="form-label mb-0">Date de fin:</label> <input type="text" class="form-control d-inline-block w-auto" id="dateFin" name="dateFin"></div>
                        <div class="d-flex gap-2">
                            <a class="btn btn-primary" id="Add" href="#" onClick="AddNewPeriode($('libelleLong').value, $('libelleCourt').value, $('dateDebut').value, $('dateFin').value); return false;">Ajouter</a>
                            <a class="btn btn-success" id="Save" href="#" onClick="SetHidden('action', 'add'); $('formList').submit()">Enregistrer</a>
                            <a class="btn btn-outline-secondary" id="Cancel" href="#" onClick="CancelNewPeriode(); return false;">Annuler</a>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </body>
</html>
