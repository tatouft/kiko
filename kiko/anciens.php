<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");
	require_once("core/pmo/PMO_core/class_loader/class_section.php");

	// Anciens : pratiquants supprimés (deleted = 1), toutes sections, groupés
	// par section - sert à retrouver qui inviter pour des événements exceptionnels.
	$controler = new PMO_MyController();
	$sql = "SELECT * FROM pratiquants WHERE deleted = 1 ORDER BY nom, prenom ASC;";
	$map = $controler->queryController($sql);

	$anciens = array();
	while ($result = $map->fetchMap())
	{
		$anciens[] = $result[pratiquants::$TableName];
	}

	// Regroupe par section, dans l'ordre où sections::GetAll() les renvoie
	$sections = sections::GetAll();
	$anciensBySection = array();
	foreach($sections as $sec)
	{
		$anciensBySection[$sec->id] = array('section' => $sec, 'anciens' => array());
	}
	foreach($anciens as $ancien)
	{
		if(isset($anciensBySection[$ancien->fk_section]))
		{
			$anciensBySection[$ancien->fk_section]['anciens'][] = $ancien;
		}
	}

	function calculateAge($birthDate) {
		if (empty($birthDate)) {
			return null;
		}
		$birth = new DateTime($birthDate);
		// PMO stocke une date "1970" en absence de vraie date de naissance (voir
		// GetSectionAtAgeLimit) - pas une vraie naissance, pas d'âge à afficher.
		if ((int)$birth->format('Y') === 1970) {
			return null;
		}
		$today = new DateTime();
		return $today->diff($birth)->y;
	}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/theme.css" type="text/css">
		<script src="js/scriptaculous/prototype.js"		type="text/javascript"></script>
		<script src="js/scriptaculous/scriptaculous.js"	type="text/javascript"></script>
		<script src="js/action.js"						type="text/javascript"></script>
		<script src="js/bootstrap.bundle.min.js"></script>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
		<link rel="stylesheet" href="css/anciens.css" type="text/css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/09e5bbb46b.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	</head>
	<body>
		<?php
                    $CurrentPage = "anciens";
                    require_once("controls/PageHeader.php");
		?>
		<div class="pageContainer">
			<div class="pageTitle">
				<h1>Anciens</h1>
			</div>

			<?php if(count($anciens) > 0): ?>
				<?php foreach($anciensBySection as $group): ?>
					<?php if(count($group['anciens']) == 0) continue; ?>
					<?php $collapseId = 'ancienSection' . $group['section']->id; ?>
					<h2 class="ancienSectionTitle">
						<button type="button" class="ancienSectionToggle" data-bs-toggle="collapse" data-bs-target="#<?php echo($collapseId); ?>" aria-expanded="true" aria-controls="<?php echo($collapseId); ?>">
							<i class="fas fa-chevron-down"></i>
							<?php echo($group['section']->libelle); ?>
							<span class="ancienSectionCount"><?php echo(count($group['anciens'])); ?></span>
						</button>
					</h2>
					<div class="collapse show" id="<?php echo($collapseId); ?>">
						<div class="anciensGrid">
							<?php foreach($group['anciens'] as $ancien): ?>
								<?php
									$photoPath = $ancien->GetPhotoHttpPath();
									$separator = (strpos($photoPath, '?') !== false) ? '&' : '?';
									$photoBustUrl = $photoPath . $separator . 't=' . time();
									$age = calculateAge($ancien->naissance);
								?>
								<div class="ancienCard">
									<div class="ancienPhoto">
										<div class="ancienPhotoLoading"></div>
										<img src="<?php echo($photoBustUrl); ?>" alt="<?php echo($ancien->prenom . ' ' . $ancien->nom); ?>" title="<?php echo($ancien->GetPhotoTitle()); ?>" style="opacity:0;" onload="this.style.opacity=1; this.previousElementSibling.style.display='none';" onerror="this.src='css/images/NoPhoto.png';" loading="lazy">
									</div>
									<div class="ancienInfo">
										<div class="ancienName"><?php echo(ucfirst($ancien->prenom ?? '')); ?></div>
										<div class="ancienLastName"><?php echo(ucfirst($ancien->nom ?? '')); ?></div>
										<?php if($age !== null){ ?><div class="ancienAge"><?php echo($age); ?> ans</div><?php } ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="ancienCount">Total: <?php echo(count($anciens)); ?> ancien(s)</div>
			<?php else: ?>
				<div class="noContent">
					<p>Aucun ancien pratiquant trouvé.</p>
				</div>
			<?php endif; ?>
		</div>
	</body>
</html>
