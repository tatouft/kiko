<?
    require('PdfConfig.php');
	$debug = true;

	$_SESSION['SiteRoot'] = $_SERVER['DOCUMENT_ROOT'] . "";

    $pdfConfig = new PDFPubliConfig;
    $pdfConfig->CellWidth = 95;
    $pdfConfig->CellHeight = 37;
    $pdfConfig->NbCellByLine = 2;

	date_default_timezone_set("Europe/Brussels");
?>