<?

	require_once("../config/config.php");
	require_once("../core/pmo/PMO_core/PMO_MyController.php");
	require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");

	$action = $_REQUEST['action'];
	$section = $_REQUEST['section'];
?>
<? 	require_once("core/getPratiquants.php"); ?>
