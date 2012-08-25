<?

	require_once("../config/config.php");
	require_once("../core/pmo/PMO_core/PMO_MyController.php");
	require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");

	$search = $_REQUEST['name'];

	$pratiquants = pratiquants::GetByNameAndLastname($search);

	if(count($pratiquants) != 0)
	{
		echo("<ul>");
		foreach($pratiquants as $prat)
		{
			echo("<li>" . ucfirst($prat->nom) . " " . ucfirst($prat->prenom) . "</li>");
		}
		echo("</ul>");
	}
?>