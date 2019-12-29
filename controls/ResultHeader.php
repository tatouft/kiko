
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
		function DeSelect()
		{
			if(selectedId !== 0)
			{
				$('PratRow'+selectedId).className = "Selectable";
				selectedId = 0;
				$("ActionButtons").className = "Buttons invisible";
			}
		}
		function Select(id, firstName, lastName)
		{
			DeSelect();
			$('PratRow'+id).className = "Selected";
			selectedId = id;
            selectedFirstName = firstName;
            selectedLastName = lastName;
			$("ActionButtons").className = "Buttons";
		}
		
		function OpenPersonne()
		{
			window.open("new.php?id=" + selectedId);
		}
		
		function DeletePersonne()
		{
            DeletePratiquant( selectedFirstName, selectedLastName, selectedId);
		}
	</script>
</div>