<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

header('Content-Type: text/html; charset=utf-8');

// Récupérer les données du formulaire
$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
$age = isset($_POST['age']) ? trim($_POST['age']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';

$captcha_solution = isset($_POST['frc-captcha-response']) ? trim($_POST['frc-captcha-response']) : '';

// Variables de validation
$errors = array();
$success = false;
$recaptcha_valid = false; // sera mis à true après vérification FriendlyCaptcha réussie

// ===== VALIDATION DES DONNÉES =====

// Valider nom
if (empty($nom) || strlen($nom) < 2 || strlen($nom) > 100) {
    $errors[] = 'Nom invalide (2 à 100 caractères)';
}

// Valider prénom
if (empty($prenom) || strlen($prenom) < 2 || strlen($prenom) > 100) {
    $errors[] = 'Prénom invalide (2 à 100 caractères)';
}

// Valider âge
if (!in_array($age, array('moins_13', 'plus_13'))) {
    $errors[] = 'Sélection d\'âge invalide';
}

// Valider email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email invalide';
}

// Valider téléphone (simple validation)
if (empty($telephone) || strlen($telephone) < 5 || strlen($telephone) > 20) {
    $errors[] = 'Numéro de téléphone invalide';
}

// ===== VÉRIFICATION FriendlyCaptcha =====

if (empty($captcha_solution)) {
    $errors[] = 'Erreur captcha - veuillez compléter la vérification anti-bot';
} else {
    $verify_url = 'https://global.frcapi.com/api/v2/captcha/siteverify';
    $postdata = http_build_query([
        'sitekey' => $friendly_site_key,
        'response' => $captcha_solution,
    ]);

    $ch = curl_init($verify_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    // Ajouter le header requis (X-API-Key)
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-Key: ' . $friendly_secret_key,
    ]);

    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status === 200 && $response !== false) {
        $response_data = json_decode($response, true);
        // Vérifier que success est true
        if (isset($response_data['success']) && $response_data['success'] === true) {
            $recaptcha_valid = true; // FIX: c'était $captcha_valid, jamais lu ensuite
        } else {
            $errors[] = 'Vérification captcha échouée';
        }
    } else {
        // Le service de vérification est indisponible ou mal configuré (timeout, panne,
        // mauvaise config serveur...). Conformément à la recommandation FriendlyCaptcha,
        // on n'ajoute PAS de blocage : mieux vaut accepter temporairement un éventuel bot
        // que de rejeter tous les utilisateurs légitimes. On journalise l'incident et on
        // envoie une alerte email pour pouvoir investiguer.
        error_log('[FriendlyCaptcha] Échec de la vérification siteverify - http_status=' . $http_status . ' response=' . var_export($response, true));

        $alert_subject = '[ALERTE] Échec vérification FriendlyCaptcha - Kome Dojo';
        $alert_body = "La vérification du captcha (siteverify) a échoué sur initiation.php.\n\n"
            . "Date : " . date('d/m/Y à H:i:s', time()) . "\n"
            . "Code HTTP retourné : " . $http_status . "\n"
            . "Réponse brute : " . var_export($response, true) . "\n\n"
            . "La soumission a été acceptée malgré tout (fail-open) pour ne pas bloquer les utilisateurs légitimes.\n"
            . "Vérifiez le statut du service sur https://status.friendlycaptcha.com et la configuration de la clé API.";
        $alert_headers = "MIME-Version: 1.0\r\n";
        $alert_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $alert_headers .= "From: Kome Dojo <noreply@kome.be>\r\n";
        // On n'utilise pas $mail_sent ici : une éventuelle erreur d'envoi de l'alerte
        // ne doit jamais bloquer ou modifier le traitement du formulaire.
        // On vérifie aussi que le destinataire est bien défini pour éviter un warning
        // "Undefined variable" et l'erreur mail() avec un destinataire null.
        $alert_recipient = (isset($initiation_email) && !empty($initiation_email)) ? $initiation_email : null;
        if ($alert_recipient !== null) {
            @mail($alert_recipient, $alert_subject, $alert_body, $alert_headers);
        } else {
            error_log('[FriendlyCaptcha] Alerte non envoyée : $initiation_email n\'est pas défini (vérifier config.php).');
        }

        $recaptcha_valid = true;
    }
}

// ===== SI TOUTES LES VALIDATIONS SONT OK, ENVOYER L'EMAIL =====

if (empty($errors) && $recaptcha_valid) {

    // Préparer l'email HTML
    $subject = 'Nouvelle réservation d\'initiation - ' . $nom . ' ' . $prenom;

    $age_label = ($age === 'moins_13') ? 'Moins de 13 ans' : '13 ans ou plus';
    $date_submission = date('d/m/Y à H:i:s', time());

    $email_body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #c4601a; color: #f4efe4; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
        .content { background-color: #f4efe4; padding: 20px; border: 1px solid #d8cebc; }
        .field { margin-bottom: 16px; }
        .field-label { font-weight: bold; color: #261e14; margin-bottom: 4px; }
        .field-value { color: #5c5040; padding: 8px; background-color: #ede5d2; border-left: 3px solid #c4601a; }
        .footer { background-color: #ede5d2; padding: 12px; text-align: center; font-size: 12px; color: #5c5040; border-radius: 0 0 4px 4px; }
        .info { color: #9c8e7c; font-size: 12px; margin-top: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nouvelle Réservation d\'Initiation</h2>
        </div>

        <div class="content">
            <div class="field">
                <div class="field-label">Nom</div>
                <div class="field-value">' . htmlspecialchars($nom) . '</div>
            </div>

            <div class="field">
                <div class="field-label">Prénom</div>
                <div class="field-value">' . htmlspecialchars($prenom) . '</div>
            </div>

            <div class="field">
                <div class="field-label">Âge</div>
                <div class="field-value">' . htmlspecialchars($age_label) . '</div>
            </div>

            <div class="field">
                <div class="field-label">Email</div>
                <div class="field-value"><a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></div>
            </div>

            <div class="field">
                <div class="field-label">Téléphone</div>
                <div class="field-value"><a href="tel:' . htmlspecialchars($telephone) . '">' . htmlspecialchars($telephone) . '</a></div>
            </div>

            <div class="info">
                <strong>Date de soumission :</strong> ' . $date_submission . '
            </div>
        </div>

        <div class="footer">
            <p>Cette personne attend votre recontact pour fixer une date d\'initiation.</p>
        </div>
    </div>
</body>
</html>';

    // Headers email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Kome Dojo <noreply@kome.be>\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    // Envoyer l'email
    $mail_sent = mail($initiation_email, $subject, $email_body, $headers);

    if ($mail_sent) {
        $success = true;
    } else {
        $errors[] = 'Erreur lors de l\'envoi du formulaire - veuillez réessayer';
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kome Dojo Neupré &mdash; Confirmation</title>
    <?php include("links.php"); ?>
    <link rel="stylesheet" href="initiation.css">
    <style>
        .confirmation-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 40px;
            text-align: center;
            background: var(--paper-warm);
            border: 1px solid var(--paper-rule);
            border-radius: 4px;
        }

        .confirmation-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .confirmation-title {
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 500;
            color: var(--ink);
            margin-bottom: 16px;
        }

        .confirmation-message {
            font-size: 15px;
            color: var(--ink-mid);
            line-height: 1.85;
            margin-bottom: 24px;
        }

        .confirmation-data {
            background: var(--paper);
            border: 1px solid var(--paper-rule);
            padding: 24px;
            margin-bottom: 24px;
            text-align: left;
            border-radius: 3px;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--paper-rule);
            font-size: 14px;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .data-label {
            font-weight: 500;
            color: var(--ink);
        }

        .data-value {
            color: var(--ink-mid);
            text-align: right;
        }

        .error-container {
            background: #ffebee;
            border: 1px solid #f5c6cb;
            padding: 24px;
            border-radius: 4px;
            margin-bottom: 24px;
        }

        .error-title {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 500;
            color: #c62828;
            margin-bottom: 16px;
        }

        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-list li {
            padding: 8px 0;
            color: #d32f2f;
            font-size: 14px;
        }

        .error-list li:before {
            content: "✕ ";
            font-weight: bold;
            margin-right: 8px;
        }

        .btn-return {
            display: inline-block;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            padding: 12px 24px;
            background: var(--ink);
            color: var(--paper);
            border: 1px solid var(--ink);
            border-radius: 3px;
            text-decoration: none;
            transition: background 0.25s;
            margin-right: 12px;
        }

        .btn-return:hover {
            background: var(--orange);
            border-color: var(--orange);
        }

        .btn-retry {
            display: inline-block;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            padding: 12px 24px;
            background: transparent;
            color: var(--ink-mid);
            border: 1px solid var(--paper-rule);
            border-radius: 3px;
            text-decoration: none;
            transition: border-color 0.25s, color 0.25s;
        }

        .btn-retry:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        @media (max-width: 580px) {
            .confirmation-container {
                margin: 40px 20px;
                padding: 24px;
            }

            .confirmation-data {
                padding: 16px;
            }

            .data-row {
                flex-direction: column;
                padding: 12px 0;
            }

            .data-value {
                text-align: left;
                margin-top: 4px;
                font-weight: 500;
            }
        }
    </style>
</head>
<body>

    <?php include("header.php"); ?>

    <div class="confirmation-container">
        <?php if ($success): ?>
            <!-- SUCCÈS -->
            <div class="confirmation-icon">✓</div>
            <h1 class="confirmation-title">Inscription confirmée !</h1>

            <p class="confirmation-message">
                Merci pour votre réservation. Nous avons bien reçu votre demande d'initiation et vous recontacterons très prochainement pour convenir d'une date.
            </p>

            <div class="confirmation-data">
                <div class="data-row">
                    <span class="data-label">Nom</span>
                    <span class="data-value"><?php echo htmlspecialchars($nom); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Prénom</span>
                    <span class="data-value"><?php echo htmlspecialchars($prenom); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Âge</span>
                    <span class="data-value"><?php echo ($age === 'moins_13') ? 'Moins de 13 ans' : '13 ans ou plus'; ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Email</span>
                    <span class="data-value"><?php echo htmlspecialchars($email); ?></span>
                </div>
                <div class="data-row">
                    <span class="data-label">Téléphone</span>
                    <span class="data-value"><?php echo htmlspecialchars($telephone); ?></span>
                </div>
            </div>

            <p class="confirmation-message">
                À bientôt au Kome Dojo !
            </p>

            <a href="index.php" class="btn-return">Retour à l'accueil</a>

        <?php else: ?>
            <!-- ERREUR -->
            <div class="error-container">
                <h2 class="error-title">Erreur</h2>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <p class="confirmation-message">
                Veuillez corriger les erreurs ci-dessus et réessayer.
            </p>

            <a href="initiation.php" class="btn-retry">Réessayer</a>
            <a href="index.php" class="btn-return">Retour à l'accueil</a>
        <?php endif; ?>
    </div>

    <?php include("footer.php"); ?>

</body>
</html>
