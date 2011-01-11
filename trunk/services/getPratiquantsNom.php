<?
/*
 * Params:
 * section = section id split by ','
 * 
 *
 * return (tableau de pratiquants)
 */

	require_once("../config/config.php");
	require_once("../core/pmo/PMO_core/PMO_MyController.php");
	require_once("../core/pmo/PMO_core/class_loader/class_pratiquants.php");

	$sections = $_REQUEST['sections'];

	if($debug)
	{
		echo("<div>Sections: " . $sections . "</div>");
	}

	$pratiquants = pratiquants::GetBySections($sections);
	$today = date("Y-m-d");

	if(count($pratiquants) != 0)
	{
		foreach($pratiquants as $prat)
		{
			$checked = presences::Exists($prat->id,$today)?'Checked':'Unchecked';
			echo("<div class='" . $checked . "' onClick='AddPresence(" . $prat->id . ",this)'>");
			echo(ucfirst($prat->nom) . " " . ucfirst($prat->prenom));
			echo("</div>");
			
			// License
//			echo($prat->IsLicenceExpired()?"<img class='TableButton' src='css/images/001_05.png'>":"<img class='TableButton' src='css/images/001_06.png'>");
//			$dt = date_create($prat->licenceDate);
			
//			echo("&nbsp;" . $dt->format('d/m/Y'));
//			echo("</td>\n\t\t\t<td>");
		}
	}
	else
	{
		echo("pas de pratiquants");
	}
?>

