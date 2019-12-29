<?
    require('PdfConfig.php');
	$debug = false;

	$_SESSION['SiteRoot'] = '/homez.462/komewntp/www/kiko';
    $_SESSION['DbName'] = 'kome';

    $pdfConfig = new PDFPubliConfig;
    $pdfConfig->CellWidth = 95;
    $pdfConfig->CellHeight = 39;
    $pdfConfig->NbCellByLine = 2;

	date_default_timezone_set("Europe/Brussels");

    $admins = array("tatou", "Tatou");
?>