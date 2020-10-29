<?php

require_once("../config/config.php");
require_once("../core/pmo/PMO_core/PMO_MyController.php");
require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");

$id = $_REQUEST['id'];

$pratiquant = PMO_MyObject::factory('pratiquants');
$pratiquant->id = $id;
$pratiquant->load();
$new = false;


require('../fpdm/fpdm.php');

$adressSplitted = explode(',', $pratiquant->adresse);
$rue = $adressSplitted[0];
$num = $adressSplitted[count($adressSplitted)-1];

$nais = date('d/m/Y', strtotime($pratiquant->naissance));

$tel = empty($pratiquant->gsm)?$pratiquant->gsm:$pratiquant->telephone;

$mail = explode('@', $pratiquant->email);

//     'untitled20' => $pratiquant->sex,
//    'untitled21' => !$pratiquant->sex,
$fields = array(
    'untitled1' => 'Kome Dojo Neupré',
    'untitled2' => '3019',
    'untitled3' => $pratiquant->nom,
    'untitled4' => $pratiquant->prenom,
    'untitled5' => $pratiquant->licenceNbr,
    'untitled6' => $rue,
    'untitled7' => $num,
    'untitled9' => $pratiquant->codePostal,
    'untitled10' => $pratiquant->commune,
    'untitled11' => 'Belgique',

    'untitled12' => $nais[0],
    'untitled13' => $nais[1],
    'untitled14' => $nais[3],
    'untitled15' => $nais[4],
    'untitled16' => $nais[6],
    'untitled17' => $nais[7],
    'untitled18' => $nais[8],
    'untitled19' => $nais[9],


    'untitled22' => "32",
    'untitled23' => $tel[1],
    'untitled24' => $tel[2],
    'untitled25' => $tel[3],
    'untitled26' => $tel[4],
    'untitled27' => $tel[5],
    'untitled28' => $tel[6],
    'untitled29' => $tel[7],
    'untitled30' => $tel[8],
    'untitled31' => $tel[9],

    'untitled32' => $mail[0],
    'untitled33' => $mail[1],

    'untitled58' => !$pratiquant->HasMoreThan14(),
    'untitled59' => $pratiquant->HasMoreThan14(),

);

$pdf = new FPDM('licence2.pdf');
$pdf->useCheckboxParser = true;
$pdf->Load($fields, true); // second parameter: false if field values are in ISO-8859-1, true if UTF-8

//echo($pdf->parseFDFContent());

$pdf->Fix();
$pdf->Merge();
$pdf->Output('D', $pratiquant->nom . '-' . $pratiquant->prenom . '.pdf');

?>