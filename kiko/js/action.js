function SetHidden(name,val)
{
	//var o = (document.all)?document.all[name]:document.getElementById(name);
	
	//o.value = val;
	
	if($(name) == null)
		alert("unknown " + name);
	
	$(name).value = val;
}
function SetAction(ac)
{
	SetHidden('action',ac);
}
function SetParam1(param)
{
	SetHidden('param1',param);
}

function Search(baseUrl, action, param1, param2)
{
    $loading = "<div class='loading'><img src='images/loading.gif'/></div>";
    
	if(action == "all")
	{
        $('PratiquantList').innerHTML = $loading;
		new Ajax.Updater($('PratiquantList'), 
						 baseUrl + "?action=all", {
						 evalScripts: true
						}
		);
        if($('mail'))
            $('mail').href = "mail.php?action=" + action; 
	}
    else
    {
        if($('mail'))
            $('mail').href = "mail.php?action=" + action + "&section=" + param1; 
    }
	if(action == "section")
	{
        $('PratiquantList').innerHTML = $loading;
		url = baseUrl + "?action=section&section=" + param1;
		new Ajax.Updater($('PratiquantList'),					
						 url, {
						 evalScripts: true,
						 onFailure: function(transport) { 
							alert('oups, ajax problem');
							}
						 }		
		);
	}
	if(action == "examens")
	{
        $('PratiquantList').innerHTML = $loading;
		url = baseUrl + "?action=section&section=" + param1 + "&date=" + param2;
		new Ajax.Updater($('PratiquantList'),					
						 url, {
						 evalScripts: true,
						 onFailure: function(transport) { 
							alert('oups, ajax problem');
							}
						 }		
		);
	}
	if(action == "license")
	{
		$('PratiquantList').innerHTML = $loading;
		url = baseUrl + "?action=license";
		new Ajax.Updater($('PratiquantList'),
			url, {
				evalScripts: true,
				onFailure: function(transport) {
					alert('oups, ajax problem');
				}
			}
		);
	}
	if(action == "presences")
	{
        $('PratiquantList').innerHTML = $loading;
		url = baseUrl + "?sections=" + param1;
		new Ajax.Updater($('PratiquantList'),					
						 url, {
						 evalScripts: true,
						 onFailure: function(transport) { 
							alert('oups, ajax problem');
							}
						 }		
		);
	}
    if(action == "poubelle")
	{
        $('PratiquantList').innerHTML = $loading;
		url = baseUrl + "?action=poubelle";
		new Ajax.Updater($('PratiquantList'),					
						 url, {
						 evalScripts: true,
						 onFailure: function(transport) { 
                            alert('oups, ajax problem');
                            }
						 }		
        );
	}
}

function AddPresence(id, checkbox)
{
	url = "services/AddPresence.php?id=" + id + "&add=" + (checkbox.className == 'Unchecked');
	new Ajax.Request(url, {
						method: 'get',
						onSuccess: function(transport) {
							if(transport.responseText == "1")
								checkbox.className = 'Checked';
							else if(transport.responseText == "0")
								checkbox.className = 'Unchecked';
							else if(1)
								alert(transport.responseText);
						}
					 }
	);
}

function FindPratiquantId(search)
{
	url = "services/findPratiquantId.php?search=" + search;
	new Ajax.Request(url, {
					 method: 'get',
					 onSuccess: function(transport) {
					 Effect.Pulsate('PratRow' + transport.responseText, {pulses: 8, duration: 1.5 });
					 location='#Prat' + transport.responseText;
						}
					});
}

var nbNewGrades = 0;
function AddGrade(gradeId, gradeLibelle)
{
	var text = "<div class='FieldName Grade'>" + gradeLibelle + ":</div> ";
	text += "<div class='InputField'><input type='text' name='newGrade" + nbNewGrades + "' id='newGrade" + nbNewGrades + "' ><input type='hidden' name='newGradeId" + nbNewGrades + "' id='newGradeId" + nbNewGrades + "' value='" + gradeId + "'></div><br>";
	$('NewGrade').innerHTML += text;

	// Retire le grade ajouté du menu déroulant pour éviter de l'ajouter 2x
	var gradeList = $('gradeList');
	gradeList.remove(gradeList.selectedIndex);

	++nbNewGrades;
}

var nbNewNewPeriode = 0;
function AddNewPeriode(libelleLong, libelleCourt, dateDebut, dateFin)
{
	var text = "<div class='NotSaved'><div class='libelle'>" + libelleLong + "</div><div class='court'>" + libelleCourt + "</div><div class='debut date'>" + dateDebut +"</div><div class='fin date'>" + dateFin + "</div>";
	text += "<input type='hidden' name='newPeriodeLong" + nbNewNewPeriode + "' id='newPeriodeLong" + nbNewNewPeriode + "' value='" + libelleLong + "'>";
	text += "<input type='hidden' name='newPeriodeCourt" + nbNewNewPeriode + "' id='newPeriodeCourt" + nbNewNewPeriode + "' value='" + libelleCourt + "'>";
	text += "<input type='hidden' name='newPeriodeDebut" + nbNewNewPeriode + "' id='newPeriodeDebut" + nbNewNewPeriode + "' value='" + dateDebut + "'>";
	text += "<input type='hidden' name='newPeriodeFin" + nbNewNewPeriode + "' id='newPeriodeFin" + nbNewNewPeriode + "' value='" + dateFin + "'></div> ";
	$('NewPeriode').innerHTML += text;
	
	++nbNewNewPeriode;
}

// Génère les 4 périodes d'une saison complète (Saison + 3 trimestres) en un
// coup, selon le pattern utilisé chaque année : mi-août -> fin juillet.
function AddSeason(startYear)
{
	var endYear = startYear + 1;
	var label = startYear + "-" + endYear;

	AddNewPeriode("Saison " + label, "Saison", "15/08/" + startYear, "31/07/" + endYear);
	AddNewPeriode("1er Période (" + label + ")", "1er", "15/08/" + startYear, "31/12/" + startYear);
	AddNewPeriode("2e Période (" + label + ")", "2e", "01/01/" + endYear, "31/03/" + endYear);
	AddNewPeriode("3e Période (" + label + ")", "3e", "01/04/" + endYear, "31/07/" + endYear);
}

// Suggère l'année de début de la prochaine saison à créer : l'année scolaire
// commence en août, donc avant août on propose encore l'année précédente.
function SuggestSeasonStartYear()
{
	var now = new Date();
	var month = now.getMonth() + 1;
	return (month >= 8) ? now.getFullYear() : now.getFullYear() - 1;
}

function CancelNewPeriode()
{
	$('NewPeriode').innerHTML = "";
}

var nbNewPeriodes = 0;
function AddPeriode(preiodeId, periodeLibelle)
{
	var text = "<div class='Periode'>" + periodeLibelle + "</div> ";
	//text = "<div class='Periode'>" . periodeLibelle . "<img class='Warning' src='css/images/001_06.png'></div>";

	text += "<div class='InputField'><input type='hidden' name='newPeriodeId" + nbNewPeriodes + "' id='newPeriodeId" + nbNewPeriodes + "' value='" + preiodeId + "'></div><br>";
	$('NewPeriode').innerHTML += text;

	// Retire la période ajoutée du menu déroulant pour éviter de l'ajouter 2x
	var periodeList = $('periodeList');
	periodeList.remove(periodeList.selectedIndex);

    ++nbNewPeriodes;
}

function GetSections()
{
	var childs = $('sections').childElements();
	var ids = '-1';
	for(i=0;i<childs.length;i++) {
		if(childs[i].readAttribute('type') == 'checkbox' && childs[i].checked)
		{
			ids += ',' + childs[i].value;
		}
	}
	return ids;
}

function DeletePratiquant(nom, prenom, id)
{
    if(confirm("Voulez-vous supprimer " + nom + " " + prenom + " ?"))
    {
        SetHidden("pratiquantId", id); 
        SetHidden("baction", "delete"); 
        $("formList").submit();
    }
}

