<?php

require_once("../config/config.php");
require_once("../core/pmo/PMO_core/PMO_MyController.php");
require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");

$pratiquants = pratiquants::GetExpired();

$tmpZip = sys_get_temp_dir() . '/licences_' . uniqid() . '.zip';

$zip = new ZipArchive();
$zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$added = 0;
foreach ($pratiquants as $pratiquant)
{
	if (!$pratiquant->HasFormulaire())
		continue;

	$zip->addFile($pratiquant->GetFormulairePath(), $pratiquant->GetFormulaireFilename());
	$added++;
}

$zip->close();

if ($added === 0)
{
	http_response_code(404);
	die('Aucun formulaire disponible pour les pratiquants dont la licence a expiré.');
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="formulaires_expirations.zip"');
header('Content-Length: ' . filesize($tmpZip));
readfile($tmpZip);
unlink($tmpZip);

?>
