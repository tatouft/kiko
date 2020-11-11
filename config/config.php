<?
    require('PdfConfig.php');
	$debug = false;
	$maintenance = false;

	$_SESSION['SiteRoot'] = '/homez.462/komewntp/www/kiko';
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