<?php
    $local = true;

    require($_SERVER['DOCUMENT_ROOT'] . '/kiko/config/PdfConfig.php');
    if($local)
    {
    	$_SESSION['SiteRoot'] = $_SERVER['DOCUMENT_ROOT'] . '/kiko';
    }
    else
    {
    	$_SESSION['SiteRoot'] = $_SERVER['DOCUMENT_ROOT'];
    }
	$debug = true;
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