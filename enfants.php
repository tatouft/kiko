<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("core/pmo/PMO_core/class_loader/class_section.php");

	// Get all sections and find the "enfant" section
	$sections = sections::GetAll();
	$enfantSection = null;
	foreach($sections as $sec)
	{
		if(strtolower($sec->libelle) == 'enfant' || strtolower($sec->libelle) == 'enfants')
		{
			$enfantSection = $sec;
			break;
		}
	}

	// Get all children (pratiquants from enfant section), only non-deleted ones (deleted = 0)
	$enfants = array();
	if($enfantSection != null)
	{
		// Use controller to execute query with explicit deleted = 0 filter
		$controler = new PMO_MyController();
		$sql = "SELECT * FROM pratiquants WHERE fk_section = " . $enfantSection->id . " AND deleted = 1 ORDER BY nom, prenom ASC;";
		$map = $controler->queryController($sql);

		// Convert result to pratiquants array
		$i = 0;
		while ($result = $map->fetchMap())
		{
			$enfants[$i] = $result[pratiquants::$TableName];
			$i++;
		}
	}

	// Function to calculate age from birth date
	function calculateAge($birthDate) {
		$today = new DateTime();
		$birth = new DateTime($birthDate);
		$age = $today->diff($birth)->y;
		return $age;
	}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
		<link rel="stylesheet" href="css/enfants.css" type="text/css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/09e5bbb46b.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	</head>
	<body>
		<div class="pageContainer">
			<div class="pageTitle">
				<h1>Enfants</h1>
			</div>

			<?php if($enfants && count($enfants) > 0): ?>
				<div class="enfantsGrid">
					<?php foreach($enfants as $enfant): ?>
						<?php
							$photoPath = $enfant->GetPhotoHttpPath();
							$separator = (strpos($photoPath, '?') !== false) ? '&' : '?';
							$photoBustUrl = $photoPath . $separator . 't=' . time();
						?>
						<div class="enfantCard">
							<div class="enfantPhoto">
								<img src="<?php echo($photoBustUrl); ?>" alt="<?php echo($enfant->prenom . ' ' . $enfant->nom); ?>" title="<?php echo($enfant->GetPhotoTitle()); ?>" onerror="this.src='css/images/NoPhoto.png';" loading="lazy">
							</div>
							<div class="enfantInfo">
								<div class="enfantName"><?php echo(ucfirst($enfant->prenom)); ?></div>
								<div class="enfantLastName"><?php echo(ucfirst($enfant->nom)); ?></div>
								<div class="enfantAge"><?php echo(calculateAge($enfant->naissance)); ?> ans</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="enfantCount">Total: <?php echo(count($enfants)); ?> enfant(s)</div>
			<?php else: ?>
				<div class="noContent">
					<?php if($enfantSection == null): ?>
						<p>Aucune section "enfant" trouvée dans la base de données.</p>
					<?php else: ?>
						<p>Aucun enfant dans la section "<?php echo($enfantSection->libelle); ?>".</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</body>
</html>

