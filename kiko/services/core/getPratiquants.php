<?php
	/*
	 * Params:
	 * action = all, section, ...
	 * section = section id
	 *
	 *
	 * return (tableau de pratiquants)
	 */
	$id = $_REQUEST['pratiquantId'] ?? '';
	$baction = $_REQUEST['baction'] ?? '';
	if(!isset($section))
	{
    	$section = '';
	}
	if(!isset($date))
	{
    	$date = '';
	}

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


    $pratiquants = FillTable($action, $section, $date);

?>

	<input type="hidden" id="pratiquantId" name="pratiquantId">
	<input type="hidden" id="baction" name="baction" />

<?php
	if(count($pratiquants) != 0)
	{
        $count = 0;

?>
		<table id="TablePratiquants" class="table table-hover align-middle">
			<thead>
			<tr>
				<th>Nom/Prenom</th>
				<th>Section</th>
                <th>Pub</th>
				<th>Grade</th>
				<th>License</th>
				<th>Fédé</th>
                <th>Cotisation</th>
				<th>Examen</th>
				<!--<th>&nbsp;</th>-->
			</tr>
			</thead>
			<tbody>
<?php
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

				echo("<tr class='Selectable' id='PratRow" . $prat->id . "' onclick='Select(" . $prat->id . ", \"" . htmlspecialchars($prat->prenom ?? '', ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->nom ?? '', ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->GetFamilyNameList() ?? '', ENT_QUOTES | ENT_HTML401) . "\")' ondblclick='Select(" . $prat->id . ", \"" . htmlspecialchars($prat->prenom ?? '', ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->nom ?? '', ENT_QUOTES | ENT_HTML401) . "\", \"" . htmlspecialchars($prat->GetFamilyNameList() ?? '', ENT_QUOTES | ENT_HTML401) . "\");OpenPersonne();'>");
				echo("<td class='PratName'><a name='Prat" . $prat->id . "'></a>");

				// Name
				echo(ucfirst($prat->nom ?? '') . " " . ucfirst($prat->prenom ?? ''));
				echo("</td>\n\t\t\t<td data-label='Section'>");

				// Section
				echo($prat->GetSection()->libelle);
				echo("</td>\n\t\t\t<td data-label='Pub' align='center'>");

				// Pub
                if($prat->UnknownPub())
                {

                }
                elseif($prat->AllowPub())
                {
                    echo("<span class='status-badge success'><i class=\"fas fa-photo-video\"></i></span>");
                }
                elseif($prat->DisallowPub())
                {
                    echo("<span class='status-badge danger'><i class=\"fas fa-photo-video\"></i></span>");
                }
                echo("</td>\n\t\t\t<td data-label='Grade'>");


                // Grade
                $grade = $prat->GetGrade();
                if ($grade == NULL)
                {
                    echo("---");
                }
                else
                {
                    $g = $grade->GetGrade();
                    $color = $g->GetColor();
                    if ($color)
                        echo("<span style='color:" . $color . "; font-weight:bold;'>" . htmlspecialchars($g->GetShortLabel(), ENT_QUOTES | ENT_HTML401) . "</span>");
                    else
                        echo(htmlspecialchars($g->GetShortLabel(), ENT_QUOTES | ENT_HTML401));
                }
				echo("</td>\n\t\t\t<td data-label='License'>");

				// License
                if($prat->HasLicence())
                {
                    if($prat->IsLicenceExpired())
                    {
                        echo($prat->HasFormulaire() ? "<a href='download_formulaire.php?id=" . $prat->id . "'><span class='status-badge danger'><i class=\"fas fa-file-download\"></i></span></a>" : "<span class='status-badge danger' title='Aucun formulaire disponible'><i class=\"fas fa-times\"></i></span>");
                    }
                    else
                    {
                        echo("<span class='status-badge success'><i class=\"fas fa-check\"></i></span>");
                    }
                    $dt = date_create($prat->licenceDate);

                    echo("&nbsp;" . $dt->format('d/m/Y'));
                    if ($prat->IsLicenceExpiredInNextMonth())
                    {
                        echo("&nbsp;&nbsp;");
                    }
                }
				echo("</td>\n\t\t\t<td data-label='Fédé' align='center'>");

				// Fédé : alerte si la date de licence fédération diffère de celle du club
				// (getAttribute() direct : PMO_MyObject n'a pas de __isset(), donc
				// isset()/empty()/?? sur $prat->fede_licence_date renverraient toujours "vide")
				$fedeLicenceDate = $prat->getAttribute('fede_licence_date');
				if ($fedeLicenceDate !== null && $fedeLicenceDate !== '')
				{
					$localLicenceDate = $prat->getAttribute('licenceDate');
					if ($fedeLicenceDate !== $localLicenceDate)
					{
						$fedeFormatted = date('d/m/Y', strtotime($fedeLicenceDate));
						$localFormatted = ($localLicenceDate !== null && $localLicenceDate !== '') ? date('d/m/Y', strtotime($localLicenceDate)) : '—';
						echo("<span class='status-badge warning' title='Fédération: " . $fedeFormatted . " / Club: " . $localFormatted . "'><i class=\"fas fa-exclamation\"></i></span>");
					}
				}
				echo("</td>\n\t\t\t<td data-label='Cotisation'>");

                // Cotisation
                $lessons = $prat->GetCountNoPayLesson();
                $periodes = $prat->GetNoPayPeriod();
                $enOrdre = (count($periodes) > 0 || $lessons > 0);
                echo($enOrdre?"<span class='status-badge danger'><i class=\"fas fa-times\"></i></span>":"<span class='status-badge success'><i class=\"fas fa-check\"></i></span>");
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
				echo("</td>\n\t\t\t<td data-label='Examen'>");

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
                        echo($ready ? "<span class='status-badge success'><i class=\"fas fa-check\"></i></span>" : "");
                        echo("&nbsp;" . $percent . "% ");
                        if ($rest > 0)
                        {
                            echo(" + " . $rest);
                        }
                    }
                } catch (\Throwable $e) {

                }
                echo("</td></tr>\n\t\t");
			}
?>
			</tbody>
		</table>
        <div id='Total'>Total: <?php echo($count); ?></div>
        <script>$('email').href = "mailto:?bcc=<?php echo($mailto); ?>";</script>
<?php
	}
	else
	{
		echo("<div class='alert alert-secondary m-3 mb-0'>Aucun pratiquant</div>");
	}
?>

