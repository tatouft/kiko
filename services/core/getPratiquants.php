<?
	/*
	 * Params:
	 * action = all, section, ...
	 * section = section id
	 *
	 *
	 * return (tableau de pratiquants)
	 */

	if(debug)
	{
		echo("<script type='text/javascript'>$('debug').innerHTML = '" . $action . ": " . $section ." ==> " . $_SERVER['REQUEST_URI'] . "';</script>");
	}

	if($action == "all")
		$pratiquants = pratiquants::GetAll();
	else if($action == "section")
		$pratiquants = pratiquants::GetBySection($section);

	if(count($pratiquants) != 0)
	{
		?>
		<table>
		<tr>
		<th>Nom/Prenom</th>
		<th>Section</th>
		<th>Grade</th>
		<th>License</th>
		<th>Pret</th>
		</tr>
		<?
		foreach($pratiquants as $prat)
		{
			echo("<tr id='PratRow" . $prat->id . "'><td>");
			echo("<a name='Prat" . $prat->id . "'></a>");
			echo(ucfirst($prat->nom) . " " . ucfirst($prat->prenom));
			echo("</td><td>");
			echo($prat->GetSection()->libelle);
			echo("</td><td>");
			echo($prat->GetGrade() == NULL?"---":$prat->GetGrade()->GetGrade()->libelle);
			echo("</td><td>");
			echo($prat->IsLicenceExpired()?"x":"v");
			echo("</td><td>");
			echo("&nbsp;");
			echo("</td></tr>");
		}
		?>
		</table>
		<?
	}
	else
	{
		echo("pas de pratiquants");
	}
?>
