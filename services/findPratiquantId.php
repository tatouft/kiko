<?php
/*
 * Params:
 * action = all, section, ...
 * section = section id
 *
 *
 * return (tableau de pratiquants)
 */

require_once("../config/config.php");
require_once("../core/pmo/PMO_core/PMO_MyController.php");
require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");

$search = $_REQUEST['search'];

$prat = pratiquants::GetByNameAndLastname($search);

if(count($prat) != 0)
	echo($prat[0]->id);
else
	echo(-1);

?>