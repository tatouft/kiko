<?php
/**
 * Synchronisation web des pratiquants actifs avec la fédération
 * Accessible via bouton sur la page principale
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/credentials.php';
require_once __DIR__ . '/scrap.php';
require_once __DIR__ . '/core/pmo/PMO_core/PMO_MyController.php';
require_once __DIR__ . '/core/pmo/PMO_core/class_loader/class_pratiquants.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Accès non autorisé');
}

// Vérifier si maintenance
if ($maintenance) {
    die('<div class="alert alert-warning">Maintenance en cours. Impossible de synchroniser pour le moment.</div>');
}

// Pousse chaque echo au navigateur immédiatement plutôt que d'attendre la fin du script,
// pour que la barre de progression bouge pendant les ~1s de pause entre chaque pratiquant.
while (ob_get_level() > 0) {
    ob_end_flush();
}
ob_implicit_flush(true);
header('X-Accel-Buffering: no');

function flushOutput() {
    if (ob_get_level() > 0) {
        ob_flush();
    }
    flush();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Synchronisation avec la fédération</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch/dist/minty/bootstrap.min.css">
    <link rel="icon" type="image/png" href="favicon.png" />
</head>
<body>
<div class="container mt-4" id="progressZone">
    <h1>Synchronisation avec la fédération</h1>
    <div class="progress mb-2" style="height: 25px;">
        <div id="syncProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">0%</div>
    </div>
    <div id="syncStatus" class="text-muted">Connexion à la fédération...</div>
</div>
<?php flushOutput(); ?>

<?php
$scraper = new FedeScraper();
$updater = null;

$report = [
    'total' => 0,
    'skipped' => 0,
    'success' => 0,
    'errors' => 0,
    'not_found' => 0,
    'modified' => [],
    'not_found_list' => [],
    'errors_list' => [],
    'start_time' => time()
];

try {
    if (!$scraper->login(FEDE_USERNAME, FEDE_PASSWORD)) {
        throw new \Exception("Impossible de se connecter à la fédération");
    }

    $updater = new PratiquantUpdater($scraper);

    // Récupérer tous les pratiquants actifs
    $pratiquants = pratiquants::GetAll();

    $report['total'] = count($pratiquants);
    $i = 0;

    foreach ($pratiquants as $prat) {
        $i++;
        $percent = $report['total'] > 0 ? round($i / $report['total'] * 100) : 100;
        $nomComplet = $prat->nom . ' ' . $prat->prenom;

        // Pratiquant pas encore lié à la fédération (normal pour un nouvel inscrit local)
        // Note: $prat->licenceNbr (via __get) ne peut pas être testé avec empty()/isset() -
        // PMO_MyObject n'a pas de __isset(), donc empty() le traite toujours comme vide.
        $licenceNbr = $prat->getAttribute('licenceNbr');
        if ($licenceNbr === null || $licenceNbr === '') {
            $report['skipped']++;
            echo "<script>document.getElementById('syncProgressBar').style.width = '{$percent}%'; document.getElementById('syncProgressBar').innerText = '{$percent}%'; document.getElementById('syncStatus').innerText = '{$i} / {$report['total']} — " . addslashes($nomComplet) . " (non lié)';</script>\n";
            flushOutput();
            continue;
        }

        $fedeId = (int)$licenceNbr;
        $result = $updater->syncPratiquant($prat);

        switch ($result['status']) {
            case 'updated':
                $report['success']++;
                $report['modified'][] = [
                    'id' => $fedeId,
                    'nom' => $prat->nom,
                    'prenom' => $prat->prenom
                ];
                break;
            case 'unchanged':
                $report['success']++;
                break;
            case 'not_found_in_fede':
                $report['not_found']++;
                $report['not_found_list'][] = [
                    'id' => $fedeId,
                    'nom' => $prat->nom,
                    'prenom' => $prat->prenom
                ];
                break;
            default:
                $report['errors']++;
                $report['errors_list'][] = [
                    'id' => $fedeId,
                    'nom' => $prat->nom,
                    'prenom' => $prat->prenom,
                    'error' => $result['message'] ?? 'Erreur inconnue'
                ];
        }

        echo "<script>document.getElementById('syncProgressBar').style.width = '{$percent}%'; document.getElementById('syncProgressBar').innerText = '{$percent}%'; document.getElementById('syncStatus').innerText = '{$i} / {$report['total']} — " . addslashes($nomComplet) . "';</script>\n";
        flushOutput();

        // Petite pause
        sleep(1);
    }

    $report['end_time'] = time();
    $report['duration'] = $report['end_time'] - $report['start_time'];

} catch (\Exception $e) {
    $report['errors']++;
    $report['errors_list'][] = ['error' => $e->getMessage()];
}
?>
<script>
    document.getElementById('syncProgressBar').classList.remove('progress-bar-animated');
    document.getElementById('syncProgressBar').style.width = '100%';
    document.getElementById('syncProgressBar').innerText = 'Terminé';
    document.getElementById('syncStatus').innerText = 'Synchronisation terminée';
</script>

<div class="container mt-4">
    <h1>Rapport de synchronisation avec la fédération</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Statistiques</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total pratiquants actifs:</strong> <?php echo $report['total']; ?></p>
                    <p><strong>Non liés à la fédération (pas de n° licence):</strong> <?php echo $report['skipped']; ?></p>
                    <p><strong>Réussis:</strong> <span class="text-success"><?php echo $report['success']; ?></span></p>
                    <p><strong>Introuvables chez la fédération:</strong> <span class="text-warning"><?php echo $report['not_found']; ?></span></p>
                    <p><strong>Erreurs:</strong> <span class="text-danger"><?php echo $report['errors']; ?></span></p>
                    <p><strong>Durée:</strong> <?php echo $report['duration']; ?> secondes</p>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($report['modified'])): ?>
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Pratiquants modifiés (<?php echo count($report['modified']); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['modified'] as $mod): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mod['id']); ?></td>
                            <td><?php echo htmlspecialchars($mod['nom'] . ' ' . $mod['prenom']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($report['not_found_list'])): ?>
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Introuvables chez la fédération (<?php echo count($report['not_found_list']); ?>)</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Ces pratiquants sont actifs chez nous mais n'apparaissent plus dans la base de la fédération — à leur signaler.</p>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>N° Licence</th>
                            <th>Nom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['not_found_list'] as $nf): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nf['id']); ?></td>
                            <td><?php echo htmlspecialchars($nf['nom'] . ' ' . $nf['prenom']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($report['errors_list'])): ?>
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Erreurs (<?php echo count($report['errors_list']); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Erreur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['errors_list'] as $error): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($error['id'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars(($error['nom'] ?? '') . ' ' . ($error['prenom'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($error['error'] ?? 'Erreur inconnue'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="index.php" class="btn btn-primary">Retour à la liste</a>
    </div>
</div>
</body>
</html>
