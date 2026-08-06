
<link rel="stylesheet" href="css/ResultHeader.css" type="text/css">
<div class="Header ResultBar">
	<div class="HeaderTitle"><?php echo($headerTitle); ?></div>
	<ul class="Buttons">
		<li title="Envoyer un email à tous les pratiquants affichés (via votre logiciel de messagerie)">
			<a href="mailto:" target="_blank" id="email"><i class="fas fa-paper-plane"></i> Email à tous</a>
		</li>
	</ul>
	<ul class="Buttons invisible" id="ActionButtons">
		<li onclick="OpenPersonne()"><i class="far fa-file-alt"></i> Afficher</li>
		<?php 
		if(in_array($_SERVER['REMOTE_USER'] ?? '', $admins))
		{ ?>
			<li onclick="DeletePersonne()"><i class="far fa-trash-alt"></i> Supprimer</li>
		<?php } ?>
		
	</ul>
	<script type="text/javascript">
		selectedId = 0;
		selectedFirstName = '';
		selectedLastName = '';
		selectedFamily = '';
		function DeSelect()
		{
			if(selectedId !== 0)
			{
			    try {
                    $('PratRow' + selectedId).classList.remove('Selected');
                    $("ActionButtons").className = "Buttons invisible";
                } catch(exception) {

                }
				selectedId = 0;
                selectedFirstName = '';
                selectedLastName = '';
                selectedFamily = '';
			}
		}
		function Select(id, firstName, lastName, family)
		{
			DeSelect();
			$('PratRow'+id).classList.add('Selected');
			selectedId = id;
            selectedFirstName = firstName;
            selectedLastName = lastName;
            selectedFamily = family;
			$("ActionButtons").className = "Buttons";
		}
		
		function OpenPersonne()
		{
			window.open("new.php?id=" + selectedId);
		}
		
		function DeletePersonne()
		{
			DeletePratiquantWithFamilyCheck(selectedFirstName, selectedLastName, selectedId, selectedFamily);
		}

		// Comme DeletePersonne, mais sans dépendre du pratiquant "sélectionné" globalement :
		// utilisé par les boutons répétés sur chaque carte en mode mobile.
		function DeletePratiquantWithFamilyCheck(firstName, lastName, id, family)
		{
		    if(family != '')
            {
                alert("Vous ne pouvez pas supprimer " + lastName + " " + firstName + " car il est chef de famille. Modifiez d'abord les chefs de famille des pratiquants suivants: " + family);
            }
            else
            {
                DeletePratiquant(firstName, lastName, id);
            }
		}
	</script>
</div>