<?php
/*
 * Params:
 * id = pratiquant id
 * 
 *
 * return 1 checked
 *        0 unchecked
 */

	require_once("../config/config.php");
	require_once("../core/pmo/PMO_core/PMO_MyController.php");
	require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("../core/pmo/PMO_core/class_loader/class_presences.php");

	$id = $_REQUEST['id'];
	$add = $_REQUEST['add'];


	$today = date("Y-m-d");
	if($add == 'true')
	{
		$pratiquant = pratiquants::GetById($id);
		$pratiquant->AddPresence($today);
	}
	else
	{
		presences::DeleteItem($id, $today);
	}

	if(presences::Exists($id, $today))
	{
		echo(1);
	}
	else
	{
		echo(0);
	}


?>