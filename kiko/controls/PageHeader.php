<link rel="stylesheet" href="css/PageHeader.css" type="text/css">

<div class="home" id="Header">
	<ul>
		<li id="HeaderG"><a href="index.php">KiKo 1.0</a></li>
		<li class="<?php if($CurrentPage == 'lists') echo('selected'); ?>"><a href="index.php">Listes</a></li>
		<li class="<?php if($CurrentPage == 'presences') echo('selected'); ?>"><a href="presences.php">Présences</a></li>
		<li><a>Banque</a></li>
		<li class="<?php if($CurrentPage == 'admin') echo('selected'); ?>"><a href="saisons.php">Admin</a></li>
		<li id="HeaderD">
            <?php if(in_array($_SERVER['REMOTE_USER'] ?? '', $admins)){ ?>
                <a id="new" href="new.php" target="_blank" title="Nouveau pratiquant" ></a>
            <?php }?>
            <a id="print" href="#" target="_blank" title="Imprimer"></a>
            <a id="mail" href="mail.php" target="_blank" title="Publipostage" ></a>
        </li>
		<li id="HeaderNone" style="float:none;"><a/></li>
	</ul>
</DIV>