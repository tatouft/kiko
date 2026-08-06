<link rel="stylesheet" href="css/fonts.css" type="text/css">
<link rel="stylesheet" href="css/PageHeader.css" type="text/css">

<nav class="navbar navbar-expand-md navbar-dark bg-primary" id="Header">
	<div class="container-fluid">
		<a class="navbar-brand" id="HeaderG" href="index.php"><span class="LogoKanji">気工</span><span class="LogoRomaji">KiKo</span></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#HeaderNav" aria-controls="HeaderNav" aria-expanded="false" aria-label="Menu">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="HeaderNav">
			<ul class="navbar-nav me-auto mb-2 mb-md-0">
				<li class="nav-item"><a class="nav-item nav-link <?php if($CurrentPage == 'lists') echo('active'); ?>" href="index.php">Listes</a></li>
				<li class="nav-item"><a class="nav-item nav-link <?php if($CurrentPage == 'presences') echo('active'); ?>" href="presences.php">Présences</a></li>
				<li class="nav-item"><a class="nav-item nav-link <?php if($CurrentPage == 'admin') echo('active'); ?>" href="saisons.php">Admin</a></li>
			</ul>
			<ul class="navbar-nav" id="HeaderD">
				<?php if(in_array($_SERVER['REMOTE_USER'] ?? '', $admins)){ ?>
					<li class="nav-item"><a class="nav-link" id="new" href="new.php" target="_blank" title="Nouveau pratiquant"><i class="fas fa-user-plus"></i> Nouveau</a></li>
				<?php }?>
				<li class="nav-item"><a class="nav-link" id="print" href="#" onClick="window.print(); return false;" title="Imprimer la liste"><i class="fas fa-print"></i> Imprimer</a></li>
				<li class="nav-item"><a class="nav-link" id="mail" href="mail.php" target="_blank" title="Générer les étiquettes d'adresses pour enveloppes"><i class="fas fa-tags"></i> Étiquettes</a></li>
			</ul>
		</div>
	</div>
</nav>