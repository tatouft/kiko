<?
	/*
	 * Params:
	 * action = all, section, ...
	 * section = section id
	 *
	 *
	 * return (tableau de pratiquants)
	 */
	$id = $_REQUEST['pratiquantId'];
	$baction = $_REQUEST['baction'];

	if($debug == true)
	{
		echo("<script type='text/javascript'>$('debug').innerHTML = 'a:" . $action . ": " . $section ." ==> " . $_SERVER['REQUEST_URI'] . "';</script>");
	}


	if($baction == 'delete')
	{
		$pratiquant = PMO_MyObject::factory('pratiquants');
		$pratiquant->id = $id;
		$pratiquant->delete();
		if($debug)
			echo("delete " . $id);
	}


	if($action == "all")
		$pratiquants = pratiquants::GetAll();
	else if($action == "section")
		$pratiquants = pratiquants::GetBySection($section);
	else if($action == "examens")
		$pratiquants = pratiquants::GetByExam($section, $date);
?>

	<input type="hidden" id="pratiquantId" name="pratiquantId">
	<input type="hidden" id="baction" name="baction" />

<?
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
				<th>&nbsp;</th>
			</tr>
			<?
			foreach($pratiquants as $prat)
			{
				echo("<tr id='PratRow" . $prat->id . "'>");
				echo("<td><a name='Prat" . $prat->id . "'></a>");
				
				// Name
				echo(ucfirst($prat->nom) . " " . ucfirst($prat->prenom));
				echo("</td>\n\t\t\t<td>");
				
				// Section
				echo($prat->GetSection()->libelle);
				echo("</td>\n\t\t\t<td>");
				
				// Grade
				echo($prat->GetGrade() == NULL?"---":$prat->GetGrade()->GetGrade()->libelle);
				echo("</td>\n\t\t\t<td>");
				
				// License
				echo($prat->IsLicenceExpired()?"<img class='TableButton' src='css/images/001_05.png'>":"<img class='TableButton' src='css/images/001_06.png'>");
				$dt = date_create($prat->licenceDate);
				
				echo("&nbsp;" . $dt->format('d/m/Y'));
				echo("</td>\n\t\t\t<td>");
				
				// Pret
				echo("&nbsp;");
				echo("</td>\n\t\t\t<td>");			
				
				// Bouttons
				echo("<a href='new.php?id=" . $prat->id . "' target='_blank' class='TableButton' id='modify' title='Modifier'></a>");
				echo("<a href='#' class='TableButton' id='delete' title='Supprimer' onClick='if(confirm(\"Voulez-vous supprimer " . $prat->nom . " " . $prat->prenom . " ?\")){SetHidden(\"pratiquantId\", \"" . $prat->id . "\"); SetHidden(\"baction\", \"delete\"); $(\"formList\").submit();}'></a>");
				echo("</td></tr>\n\t\t");
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

