<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("core/pmo/PMO_core/class_loader/class_section.php");
?>
<html>
	<head>
		<script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
		<script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
		<script src="js/action.js"						type="text/javascript"></script>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
	</head>
	<body>
		<div id="debug">&nbsp;</div>
		<div id='sections' name='sections'>
			<?php
				$sections = sections::GetAll();
				foreach($sections as $sec)
				{
					echo('<input type="checkbox" checked value="' . $sec->id . '">' . $sec->libelle . '</input>');
				}
			?>
			<input type="button" value="Afficher" onclick="Search('services/getPratiquantsNom.php', 'presences', GetSections());"/>
		</div>

		<div id='PratiquantList'/>
	</body>
</html>