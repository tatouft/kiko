
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kome Dojo Neupré &mdash; Aïkido</title>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/links.php"); ?>
    <link rel="stylesheet" href="/index.css">
    <style>
        .info-grid-banner {
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
            padding: 12px 0;
            gap: 1px;
            background: var(--paper-rule);
            border: 1px solid var(--paper-rule);
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 500;
            color: var(--ink);
            line-height: 1.2;
        }
        .banner-open { background: #e6ffed; color: #1f7a3a; border: 1px solid #9ee0b0; }
        .banner-almost_open { background: #fff7e6; color: #7a5a1f; border: 1px solid #ffdf80; }
        .banner-closed { background: #ffecec; color: #7a1f1f; border: 1px solid #ff9b9b; }
    </style>
</head>
<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/header.php");?>
<br/><br/>
<section class="info-section">
    <?php
        // Prepare banner content based on current state
        $bannerClass = "banner-" . $state;
        if ($state === SeminarStatus::Open) {
            $bannerText = '📖 Ouvert | Open';
        } elseif ($state === SeminarStatus::AlmostOpen) {
            $bannerText = '⏳ Bientôt disponible | Coming soon';
        } else {
            $bannerText = '📕 Clôturé | Closed';
        }
    ?>
    <div class="info-grid-banner <?php echo($bannerClass); ?>">
        <?php echo($bannerText); ?>
    </div>
    <div class="info-grid">
        <?php if ($state === SeminarStatus::AlmostOpen): ?>
            <div class="info-card">
                <h2 class="card-title">🇫🇷 <?php echo($title->fr); ?></h2>
                <p class="card-body">
                    ⚠️ <?php echo($desc->fr); ?> ne sont pas encore ouvertes.<br/>
                        Elles seront disponibles très prochainement — restez en contact !
                </p>
            </div>
            <div class="info-card">

            </div>
            <div class="info-card">
                <h2 class="card-title">🇬🇧 <?php echo($title->en); ?></h2>
                <p class="card-body">
                    ⚠️ <?php echo($desc->en); ?> is not open yet.<br/>
                        It will be available very soon — stay tuned!
                </p>
            </div>
        <?php elseif ($state === SeminarStatus::Open): ?>
            <script>
                setTimeout(function() {
                    window.location.href = "<?php echo $formLink; ?>";
                }, 3000); // 3000ms = 3 secondes
            </script>
            <div class="info-card">
                <h2 class="card-title">🇫🇷 <?php echo($title->fr); ?></h2>
                <p class="card-body">
                     <?php echo($desc->fr); ?> sont ouvertes, vous allez être redirigés. Si vous n'êtes pas redirigez, cliquez sur le lien ci-dessous
                </p>
                <a href="<?php echo $formLink; ?>" class="card-link">Vers le formulaire &rarr;</a>
            </div>
            <div class="info-card">

            </div>
            <div class="info-card">
                <h2 class="card-title">🇬🇧 <?php echo($title->en); ?></h2>
                <p class="card-body">
                     <?php echo($desc->en); ?> are open, you will be redirected. If you are not redirected, please click the link below.
                </p>
                <a href="<?php echo $formLink; ?>" class="card-link">To the form &rarr;</a>
            </div>
        <?php elseif ($state === SeminarStatus::Closed): ?>
            <div class="info-card">
                <h2 class="card-title">🇫🇷 <?php echo($title->fr); ?></h2>
                <p class="card-body">
                    ⚠️ <?php echo($desc->fr); ?> sont désormais clôturées. <br/>
                    Merci à toutes et tous pour votre participation !
                </p>
            </div>
            <div class="info-card">

            </div>
            <div class="info-card">
                <h2 class="card-title">🇬🇧 <?php echo($title->en); ?></h2>
                <p class="card-body">
                    ⚠️ <?php echo($desc->en); ?> is now closed. <br/>
                    Thank you all for your interest!
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>
</body>
</html>