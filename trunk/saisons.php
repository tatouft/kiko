<?php
    require_once("config/config.php");
    require_once("core/pmo/PMO_core/PMO_MyController.php");
    require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
    require_once("core/pmo/PMO_core/class_loader/class_periodes.php");
    ?>
<html>
    <head>
        <script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
        <script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
        <script src="js/action.js"						type="text/javascript"></script>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/general.css" type="text/css">
    </head>
    <body>
        <?php
            $CurrentPage = "lists";
            require_once("controls/PageHeader.php");
            ?>
        <div id="debug">&nbsp;</div>

        <div class="List Contents">
            <div id='Saisons'>
                <form method="post" action="<? echo($_SERVER['REQUEST_URI']); ?>" name="formList" id="formList">
                    <?php
                        extract($_GET);
                        extract($_POST);

                        $action = filter_input(INPUT_POST, 'action');
                        if($action == 'add')
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
                        <div>Libellé long: <input type="text" id="libelleLong" name="libelleLong"></div>
                        <div>Libellé court: <input type="text" id="libelleCourt" name="libelleCourt"></div>
                        <div>Date de début: <input type="text" id="dateDebut" name="dateDebut"></div>
                        <div>Date de fin: <input type="text" id="dateFin" name="dateFin"></div>
                        <div><a class="Button" id="Add" href="#" onClick="AddNewPeriode($('libelleLong').value, $('libelleCourt').value, $('dateDebut').value, $('dateFin').value);">Ajouter</a>
                        <a class="Button" id="Save" href="#" onClick="SetHidden('action', 'add'); $('formList').submit()">Enregistrer</a>
                        <a class="Button" id="Save" href="#" onClick="CancelNewPeriode();">Annuler</a></div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
