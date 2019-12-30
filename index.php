<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
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
		<?php
                    $CurrentPage = "lists";
                    require_once("controls/PageHeader.php"); 
		?>
		<div id="debug">&nbsp;</div>
		<div class="LittelTabs">
			<?php require_once("controls/MenuTabs.php"); ?>
			<?php require_once("controls/SearchArea.php"); ?>
			<?php $headerTitle = 'Liste des pratiquants'; ?>
		</div>		
		
		<div class="List Contents">
			<?php 
                require_once("services/core/FillTable.php");
				require_once("controls/ResultHeader.php"); 
			?>
            <form method="post" action="<? echo($_SERVER['REQUEST_URI']); ?>" name="formList" id="formList">
                <div id='PratiquantList'>
                    <?php
                        $action = "all";
                        require_once("services/core/getPratiquants.php");
                    ?>
                </div>
            </form>
		</div>
	</body>
</html>