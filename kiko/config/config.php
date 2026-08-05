<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/kiko/config/PdfConfig.php');

    $_SESSION['SiteRoot'] = $_SERVER['DOCUMENT_ROOT'] . '/kiko';

    // En prod, REMOTE_USER vient de l'authentification .htaccess (Apache).
    // Le serveur PHP intégré (php -S, utilisé en local) ne la fournit jamais -
    // on simule une connexion admin pour pouvoir tester. php_sapi_name() vaut
    // 'cli-server' uniquement avec `php -S`, jamais en prod (Apache/FPM) :
    // impossible d'oublier de désactiver ça au déploiement.
    if (php_sapi_name() === 'cli-server' && !isset($_SERVER['REMOTE_USER'])) {
        $_SERVER['REMOTE_USER'] = 'tatou';
        $_SESSION['WebSiteRoot'] = '/www';
    }

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