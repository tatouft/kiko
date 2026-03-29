<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kome Dojo Neupré &mdash; Aïkido</title>
    <?php include("links.php"); ?>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<?php include("header.php"); ?>
<br/><br/>
<section class="info-section">
    <div class="info-grid">
        <?php
        class SeminarStatus {
            const Closed = 'closed';
            const Open = 'open';
            const AlmostOpen = 'almost_open';
        }
        $state = SeminarStatus::Closed;
        $formLink = "https://forms.gle/M9WCHkW9szyoav4H6";
        ?>


        <?php if ($state === SeminarStatus::AlmostOpen): ?>
        <div class="info-card">
            <h2 class="card-title">🇫🇷 Inscription</h2>
            <p class="card-body">
                ⚠️ Les inscriptions au séminaire de kenjutsu ne sont pas encore ouvertes.<br/>
                    Elles seront disponibles très prochainement — restez connectés !
            </p>
        </div>
        <div class="info-card">

        </div>
        <div class="info-card">
            <h2 class="card-title">🇬🇧 Registration</h2>
            <p class="card-body">
                ⚠️ Registration for the kenjutsu seminar is not open yet.<br/>
                    It will be available very soon — stay tuned!
            </p>
        </div>

        <?php elseif ($state === SeminarStatus::Open): ?>

        <div class="info-card">
            <h2 class="card-title">🇫🇷 Inscription</h2>
            <p class="card-body">
                Ca y est, les inscriptions sont ouvertes, vous allez être redirigés. Si vous n'êtes pas redirigez, cliquez sur le lien ci-dessous
            </p>
            <a href="<?php echo $formLink; ?>" class="card-link">Vers le formulaire &rarr;</a>
        </div>
        <div class="info-card">

        </div>
        <div class="info-card">
            <h2 class="card-title">🇬🇧 Registration</h2>
            <p class="card-body">
                Here we go, registrations are open, you will be redirected. If you are not redirected, please click the link below.
            </p>
            <a href="<?php echo $formLink; ?>" class="card-link">To the form &rarr;</a>
        </div>

        <?php elseif ($state === SeminarStatus::Closed): ?>

        <div class="info-card">
            <h2 class="card-title">🇫🇷 Inscription</h2>
            <p class="card-body">
                ⚠️ Les inscriptions au séminaire sont désormais clôturées. <br/>
                Merci à toutes et tous pour votre participation !
            </p>
        </div>
        <div class="info-card">

        </div>
        <div class="info-card">
            <h2 class="card-title">🇬🇧 Registration</h2>
            <p class="card-body">
                ⚠️ Registration for the seminar is now closed. <br/>
                Thank you all for your interest!
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include("footer.php"); ?>
</body>
</html>