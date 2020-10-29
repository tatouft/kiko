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
		$pratiquant->load();
        $pratiquant->deleted = 1;
        $pratiquant->commit();
		if($debug)
			echo("delete " . $id);
	}


    $pratiquants = FillTable($action, $section);

?>

	<input type="hidden" id="pratiquantId" name="pratiquantId">
	<input type="hidden" id="baction" name="baction" />

<?
	if(count($pratiquants) != 0)
	{
        $count = 0;

		?>
		<table id="TablePratiquants">
			<tr>
				<th>Nom/Prenom</th>
				<th>Section</th>
                <th>Pub</th>
				<th>Grade</th>
				<th>License</th>
                <th>Cotisation</th>
				<th>Examen</th>
				<!--<th>&nbsp;</th>-->
			</tr>
			<?
			$mailto = "";
			foreach($pratiquants as $prat)
			{
                $count++;

                if($prat->email != "")
                {
                	$emails = explode(";", $prat->email);
                	foreach ($emails as $email) 
                	{
	                	$mailto .= $prat->nom . ' ' . $prat->prenom . '<' . $email . '>;';
                	}
                }

				echo("<tr class='Selectable' id='PratRow" . $prat->id . "' onclick='Select(" . $prat->id . ", \"" . htmlspecialchars($prat->prenom, ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->nom, ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->GetFamilyNameList(), ENT_QUOTES | ENT_HTML401) . "\")' ondblclick='Select(" . $prat->id . ", \"" . htmlspecialchars($prat->prenom, ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->nom, ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->GetFamilyNameList(), ENT_QUOTES | ENT_HTML401) . "\");OpenPersonne();'>");
				echo("<td><a name='Prat" . $prat->id . "'></a>");
				
				// Name
				echo(ucfirst($prat->nom) . " " . ucfirst($prat->prenom));
				echo("</td>\n\t\t\t<td>");
				
				// Section
				echo($prat->GetSection()->libelle);
				echo("</td>\n\t\t\t<td align='center'>");

				// Pub
                if($prat->UnknownPub())
                {

                }
                elseif($prat->AllowPub())
                {
                    echo("<i class=\"fas fa-photo-video\" style='color: green;'></i>");
                }
                elseif($prat->DisallowPub())
                {
                    echo("<i class=\"fas fa-photo-video\" style='color: crimson;'></i>");
                }
                echo("</td>\n\t\t\t<td>");


                // Grade
                $grade = $prat->GetGrade();
				echo(($grade == NULL)?"---":$grade->GetGrade()->libelle);
				echo("</td>\n\t\t\t<td>");
				
				// License
                if($prat->HasLicence())
                {
                    echo($prat->IsLicenceExpired() ? "<a href='./services/GetLicence.php?id=" . $prat->id . "'><i class=\"fas fa-file-download\" style='color: crimson;'></i></a>" : "<i class=\"fas fa-check-circle\" style='color: green;'></i>");
                    $dt = date_create($prat->licenceDate);

                    echo("&nbsp;" . $dt->format('d/m/Y'));
                    if ($prat->IsLicenceExpiredInNextMonth())
                    {
                        echo("&nbsp;&nbsp;");
                    }
                }
				echo("</td>\n\t\t\t<td>");
                
                // Cotisation
                $lessons = $prat->GetCountNoPayLesson();
                $periodes = $prat->GetNoPayPeriod();
                $enOrdre = (count($periodes) > 0 || $lessons > 0);
                echo($enOrdre?"<i class=\"fas fa-times-circle\" style='color: crimson;'></i>":"<i class=\"fas fa-check-circle\" style='color: green;'></i>");
                echo("&nbsp;");
                if(count($periodes) > 0)
                {
                    $enOrdre = 0;
                    foreach($periodes as $periode)
                    {
                        echo($periode->libelleCourt . ", ");
                    }
                }
                if($lessons > 0)
                {
                    $enOrdre = 0;
                    echo($lessons . " cours");
                }
                echo("&nbsp;");
				echo("</td>\n\t\t\t<td>");
				
				// Examen
                try
                {


                    if ($prat->GetPresencesNeededForNextGrade() > 0)
                    {
                        $rest = $prat->GetRestToNextGrade();
                        $ready = (($rest + 4) >= 0);
                        $percent = floor(100 / $prat->GetPresencesNeededForNextGrade() * $prat->GetPresencesCountFromLastGrade());
                        if ($percent > 100)
                            $percent = 100;
                        echo($ready ? "<i class=\"fas fa-check-circle\" style='color: green;'></i>" : "");
                        echo("&nbsp;" . $percent . "% ");
                        if ($rest > 0)
                        {
                            echo(" + " . $rest);
                        }
                    }
                } catch (\Throwable $e) {

                }
//				echo("</td>\n\t\t\t<td>");
 
//                // Bouttons b
//                if(in_array($_SERVER['REMOTE_USER'], $admins))
//                {
//                    echo("<a href='#' class='TableButton' id='delete' title='Supprimer' onClick='DeletePratiquant(\"" . $prat->nom . "\", \"" . $prat->prenom . "\", " . $prat->id . ");'></a>");
//                }
//                echo("<a href='mailto:" . $prat->email . "' class='TableButton' id='singleEmail' title='" . $prat->email . "' target='_blank'></a>");
                echo("</td></tr>\n\t\t");
			}
			?>
		</table>
        <div id='Total'>Total: <? echo($count); ?></div>
        <script>$('email').href = "mailto:?bcc=<? echo($mailto); ?>";</script>
		<? 
	}
	else
	{
		echo("pas de pratiquants");
	}
?>

