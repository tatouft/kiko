<!DOCTYPE html>
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kiko/config/config.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kiko/core/pmo/PMO_core/PMO_MyController.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kiko/core/pmo/PMO_core/class_loader/class_pratiquants.php");
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/theme.css" type="text/css">
		<script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
		<script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
		<script src="js/action.js"						type="text/javascript"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/09e5bbb46b.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	</head>
	<body>
		<?php
                    $CurrentPage = "lists";
                    require_once("controls/PageHeader.php"); 
		?>
		<div id="debug">&nbsp;</div>
        <?php if($maintenance){ ?>
            <div class="alert alert-warning text-center mx-auto mt-3" style="max-width: 650px;">
                <i class="fas fa-exclamation-triangle"></i>
                Maintenance en cours. Impossible de faire des modifications pour le moment.
            </div>
        <?php }?>
        <?php $isAdmin = in_array($_SERVER['REMOTE_USER'] ?? '', $admins); ?>
        <div class="mb-3 mt-3 text-center">
            <form method="post" action="sync_all.php" onsubmit="return confirm('Êtes-vous sûr de vouloir lancer la synchronisation de tous les membres actifs ? Cette opération peut prendre du temps.');">
                <?php if ($isAdmin) { ?>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync"></i> Synchroniser avec la fédération
                </button>
                <?php } else { ?>
                <span title="Contactez un administrateur pour lancer la synchronisation">
                    <button type="submit" class="btn btn-primary" disabled style="pointer-events: none;">
                        <i class="fas fa-sync"></i> Synchroniser avec la fédération
                    </button>
                </span>
                <?php } ?>
            </form>
        </div>
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
            <form method="post" action="<?php echo($_SERVER['REQUEST_URI']); ?>" name="formList" id="formList">
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