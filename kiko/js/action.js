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

// Validation de certains champs (format de date, chiffres seuls, nom de
// fichier, liste d'emails), même principe que le formulaire d'initiation de
// www/ : message rouge sous le champ dès qu'on le quitte, pas de popup
// natif du navigateur. Chaque "genre" de champ porte une classe CSS
// <genre>-field (ex: date-field, digits-field) qui sélectionne son validateur.
var FieldValidators = {
	date: {
		message: 'Format attendu : jj/mm/aaaa',
		test: function(value) {
			return value === '' || /^\d{1,2}\/\d{1,2}\/\d{4}$/.test(value);
		}
	},
	digits: {
		message: 'Chiffres uniquement',
		test: function(value) {
			return value === '' || /^\d+$/.test(value);
		}
	},
	filename: {
		message: 'Nom de fichier attendu (ex: photo.jpg), sans chemin',
		test: function(value) {
			if(value === '') return true;
			if(/^https?:\/\//i.test(value)) return true;
			return /^[^\/\\]+\.[A-Za-z0-9]+$/.test(value);
		}
	},
	emails: {
		message: "Email(s) invalide(s) - séparez plusieurs adresses par ;",
		test: function(value) {
			if(value === '') return true;
			return value.split(';').every(function(part) {
				part = part.trim();
				return part !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(part);
			});
		}
	},
	phone: {
		// Pas de format imposé (juste des chiffres, ou groupés avec / et espaces,
		// ou / et points) : on vérifie seulement que c'est cohérent - que des
		// chiffres et des séparateurs usuels, pas de lettres ni de symboles au
		// hasard. Inclut aussi le format international que formatPhoneNumber()
		// produit lui-même (+32 (491) 234-567), pour ne pas le rejeter.
		message: 'Numéro invalide : chiffres uniquement, éventuellement groupés avec / et espaces (ou / et points) - + et ( ) autorisés pour un format international',
		test: function(value) {
			if(value === '') return true;
			return /^\+?[0-9][0-9 ./()-]*$/.test(value);
		}
	}
};

function GetFieldKind(input)
{
	for(var kind in FieldValidators)
	{
		if(input.classList.contains(kind + '-field'))
		{
			return kind;
		}
	}
	return null;
}

function ValidateField(input, forceDisplay)
{
	var kind = GetFieldKind(input);
	if(!kind)
	{
		return true;
	}

	var value = input.value.trim();
	var valid = FieldValidators[kind].test(value);

	if(valid)
	{
		input.classList.remove('is-invalid');
	}
	else if(forceDisplay || input.dataset.touched === 'true')
	{
		input.classList.add('is-invalid');
	}
	return valid;
}

function AttachFieldValidation(input)
{
	if(!input || input.dataset.validationAttached === 'true')
	{
		return;
	}
	var kind = GetFieldKind(input);
	if(!kind)
	{
		return;
	}
	input.dataset.validationAttached = 'true';

	var next = input.nextElementSibling;
	if(!next || !next.classList.contains('invalid-feedback'))
	{
		var feedback = document.createElement('div');
		feedback.className = 'invalid-feedback';
		feedback.textContent = FieldValidators[kind].message;
		input.insertAdjacentElement('afterend', feedback);
	}

	var check = function() {
		input.dataset.touched = 'true';
		ValidateField(input);
	};
	input.addEventListener('input', check);
	input.addEventListener('blur', check);
}

var ValidatedFieldsSelector = '.date-field, .digits-field, .filename-field, .emails-field, .phone-field';

function ValidateAllFields()
{
	var allValid = true;
	document.querySelectorAll(ValidatedFieldsSelector).forEach(function(input) {
		if(!ValidateField(input, true))
		{
			allValid = false;
		}
	});
	return allValid;
}

document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll(ValidatedFieldsSelector).forEach(AttachFieldValidation);
});

// form.submit() ne déclenche pas la validation native du navigateur
// (contrairement à un vrai clic sur un bouton submit) : on vérifie donc
// nous-mêmes le formulaire avant de soumettre.
function SubmitIfValid(formId)
{
	var form = $(formId);
	if(form.checkValidity() && ValidateAllFields())
	{
		form.submit();
	}
	else
	{
		form.reportValidity();
	}
}

function Search(baseUrl, action, param1, param2)
{
    $loading = "<div class='loading'><div class='spinner'></div></div>";
    
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
	if(action == "montee")
	{
		$('PratiquantList').innerHTML = $loading;
		url = baseUrl + "?action=montee&section=" + param1 + "&date=" + param2;
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
	text += "<div class='InputField'><input type='text' class='date-field form-control' name='newGrade" + nbNewGrades + "' id='newGrade" + nbNewGrades + "' placeholder='jj/mm/aaaa'><input type='hidden' name='newGradeId" + nbNewGrades + "' id='newGradeId" + nbNewGrades + "' value='" + gradeId + "'></div><br>";
	$('NewGrade').innerHTML += text;
	AttachFieldValidation(document.getElementById('newGrade' + nbNewGrades));

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
		if(childs[i].hasClassName('section-btn') && childs[i].hasClassName('active'))
		{
			ids += ',' + childs[i].readAttribute('data-id');
		}
	}
	return ids;
}

function ToggleSection(btn)
{
	var childs = $('sections').childElements();
	for(i=0;i<childs.length;i++) {
		if(childs[i].hasClassName('section-btn'))
		{
			childs[i].removeClassName('active');
			childs[i].removeClassName('btn-primary');
			childs[i].addClassName('btn-outline-primary');
		}
	}
	btn.removeClassName('btn-outline-primary');
	btn.addClassName('btn-primary');
	btn.addClassName('active');
	Search('services/getPratiquantsNom.php', 'presences', GetSections());
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

