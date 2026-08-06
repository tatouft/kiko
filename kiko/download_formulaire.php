<?php

require_once(dirname(__FILE__) . "/config/config.php");
require_once(dirname(__FILE__) . "/core/pmo/PMO_core/PMO_MyController.php");
require_once(dirname(__FILE__) . "/core/pmo/PMO_core/class_loader/class_pratiquants.php");

$id = (int)($_GET['id'] ?? 0);

$pratiquant = PMO_MyObject::factory('pratiquants');
$pratiquant->id = $id;
$pratiquant->load();

if (!$pratiquant->HasLicence()) {
    http_response_code(404);
    die('Pratiquant introuvable ou non lié à la fédération.');
}

$path = $pratiquant->GetFormulairePath();
if (!is_file($path)) {
    http_response_code(404);
    die('Aucun formulaire disponible pour ce pratiquant.');
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $pratiquant->GetFormulaireFilename() . '"');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
