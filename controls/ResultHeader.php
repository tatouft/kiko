
<link rel="stylesheet" href="css/ResultHeader.css" type="text/css">
<div class="Header">
	<div class="HeaderTitle"><? echo($headerTitle); ?></div>
	<ul class="Buttons">
		<li>
			<a href="mailto:" target="_blank" id="email"></a>
		</li>
	</ul>
	<ul class="Buttons invisible" id="ActionButtons">
		<li onclick="OpenPersonne()">Afficher</li>
		<?php 
		if(in_array($_SERVER['REMOTE_USER'], $admins))
		{ ?>
			<li onclick="DeletePersonne()">Supprimer</li>
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
                    $('PratRow' + selectedId).className = "Selectable";
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
			$('PratRow'+id).className = "Selected";
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
		    if(selectedFamily != '')
            {
                alert("Vous ne pouvez pas supprimer " + selectedLastName + " " + selectedFirstName + " car il est chef de famille. Modifiez d'abord les chefs de famille des pratiquants suivants: " + selectedFamily);
            }
            else
            {
                DeletePratiquant( selectedFirstName, selectedLastName, selectedId);
            }
		}
	</script>
</div>