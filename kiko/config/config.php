<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/kiko/config/PdfConfig.php');

    $_SESSION['SiteRoot'] = $_SERVER['DOCUMENT_ROOT'] . '/kiko';

	$debug = false;
	$maintenance = false;

    $_SESSION['DbName'] = 'kome';
    $_SESSION['Space'] = '';

    $pdfConfig = new PDFPubliConfig;
    $pdfConfig->CellWidth = 95;
    $pdfConfig->CellHeight = 39;
    $pdfConfig->NbCellByLine = 2;

	date_default_timezone_set("Europe/Brussels");

	if(!$maintenance)
    {
        $admins = array("tatou", "Tatou");
    }
	else
    {
        $admins = array();
    }
?>