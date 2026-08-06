<?php
	require_once("config/config.php");
	require_once("core/pmo/PMO_core/PMO_MyController.php");
	require_once("core/pmo/PMO_core/class_loader/class_pratiquants.php");

	// La commune demande ce rapport chaque année entre décembre et janvier,
	// sur la fenêtre d'inscription de la saison : du 1er août au 20 septembre.
	// $year = année du dernier 1er août passé (donc l'année en cours si on est
	// après août, l'année précédente sinon).
	$year = (int)date('Y');
	if ((int)date('n') < 8)
	{
		$year -= 1;
	}
	// Borne haute exclusive dans GetCount* (pre.date < date2) : on prend le
	// 21 septembre pour inclure le 20 entièrement.
	$lastYeardate1 = ($year - 1) . "-08-01";
	$lastYeardate2 = ($year - 1) . "-09-21";
	$thisYeardate1 = $year . "-08-01";
	$thisYeardate2 = $year . "-09-21";

	$ageBrackets = array(
		array(2, 5),
		array(6, 8),
		array(9, 12),
		array(13, 17),
		array(18, 21),
		array(22, 34),
		array(35, 49),
		array(50, 64),
		array(65, 74),
		array(75, 150),
	);
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
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/09e5bbb46b.js" crossorigin="anonymous"></script>

		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/general.css" type="text/css">
        <link rel="icon" type="image/png" href="favicon.png" />
        <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->
	</head>
	<body>
		<?php
                    $CurrentPage = "report";
                    require_once("controls/PageHeader.php");
		?>
		<div class="container-fluid py-3" style="max-width: 700px;">
			<h1 class="text-center mb-3">Carte d'identité des clubs</h1>

			<div class="row row-cols-1 row-cols-md-3 g-3 mb-3">
				<div class="col">
					<div class="card h-100 text-center">
						<div class="card-body">
							<div class="card-title text-body-secondary">Affiliés <?php echo(($year - 1) . '-' . $year); ?></div>
							<div class="fs-2 fw-bold"><?php echo(pratiquants::GetCount($lastYeardate1, $lastYeardate2)); ?></div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card h-100 text-center">
						<div class="card-body">
							<div class="card-title text-body-secondary">Affiliés <?php echo($year . '-' . ($year + 1)); ?></div>
							<div class="fs-2 fw-bold"><?php echo(pratiquants::GetCount($thisYeardate1, $thisYeardate2)); ?></div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card h-100 text-center">
						<div class="card-body">
							<div class="card-title text-body-secondary">Affiliés de Neupré</div>
							<div class="fs-2 fw-bold"><?php echo(pratiquants::GetCountNeupre($thisYeardate1, $thisYeardate2)); ?></div>
						</div>
					</div>
				</div>
			</div>

			<div class="card mb-3">
				<div class="card-header fw-bold">Affiliés <?php echo($year . '-' . ($year + 1)); ?> par tranche d'âge</div>
				<div class="card-body p-0">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th>Âge</th>
								<th>Femmes</th>
								<th>Hommes</th>
								<th>Neupréens</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($ageBrackets as $bracket): ?>
								<?php list($a1, $a2) = $bracket; ?>
								<tr>
									<td><?php echo($a2 >= 150 ? $a1 . '+' : $a1 . '-' . $a2); ?></td>
									<td><?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 0)); ?></td>
									<td><?php echo(pratiquants::GetCountAge($thisYeardate1, $thisYeardate2, $a1, $a2, 1)); ?></td>
									<td><?php echo(pratiquants::GetCountAgeNeupre($thisYeardate1, $thisYeardate2, $a1, $a2)); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
