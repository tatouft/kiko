<?php
/**
 * Script batch pour synchroniser une liste de pratiquants avec la fédération
 * Utilisation recommandée: cron job quotidien ou hebdomadaire
 * 
 * Configuration de cron (exemple):
 *   # Sync chaque jour à 2h du matin
 *   0 2 * * * /usr/bin/php /var/www/html/sync_batch.php >> /var/log/sync_fede.log 2>&1
 */

// Configuration de session
$_SESSION['SiteRoot'] = dirname(__FILE__);
$_SESSION['DbName'] = 'kome'; // Change selon ta base

require_once __DIR__ . '/config/credentials.php';
require_once __DIR__ . '/scrap.php';
require_once __DIR__ . '/core/pmo/PMO_core/PMO_MyController.php';

// Charger la liste des pratiquants à synchroniser
// Option 1: Via un fichier de configuration
$configFile = __DIR__ . '/config/sync_pratiquants.json';
$practiquantsToSync = [];

if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
    $practiquantsToSync = $config['pratiquants'] ?? [];
} else {
    // Option 2: Charger tous les pratiquants actifs depuis la DB
    $practiquantsToSync = getAllActivePratiquants();
}

if (empty($practiquantsToSync)) {
    logMessage("Aucun pratiquant à synchroniser");
    exit(0);
}

logMessage("=== Démarrage de la synchronisation batch ===");
logMessage("Nombre de pratiquants: " . count($practiquantsToSync));

$scraper = new FedeScraper();
$updater = null;

try {
    if (!$scraper->login(FEDE_USERNAME, FEDE_PASSWORD)) {
        logMessage("ERREUR: Impossible de se connecter à la fédération");
        exit(1);
    }

    $updater = new PratiquantUpdater($scraper);
    
    $stats = [
        'total' => count($practiquantsToSync),
        'success' => 0,
        'errors' => 0,
        'modified' => [],
        'notFoundInFede' => [],
        'noLocalMatch' => [],
        'startTime' => time(),
        'details' => []
    ];

    foreach ($practiquantsToSync as $fedeId) {
        $result = $updater->syncByFedeId((int)$fedeId);

        switch ($result['status']) {
            case 'updated':
                $stats['success']++;
                $stats['modified'][] = $fedeId;
                logMessage("✓ Pratiquant {$fedeId}: OK (modifié)");
                break;
            case 'unchanged':
                $stats['success']++;
                logMessage("✓ Pratiquant {$fedeId}: OK (inchangé)");
                break;
            case 'not_found_in_fede':
                $stats['notFoundInFede'][] = $fedeId;
                logMessage("⚠ Pratiquant {$fedeId}: INTROUVABLE CHEZ LA FÉDÉRATION");
                break;
            case 'no_local_match':
                $stats['noLocalMatch'][] = $fedeId;
                logMessage("⚠ Pratiquant {$fedeId}: aucun pratiquant local avec ce n° licence");
                break;
            default:
                $stats['errors']++;
                logMessage("✗ Pratiquant {$fedeId}: " . ($result['message'] ?? 'ERREUR'));
        }

        // Petite pause entre les requêtes pour ne pas surcharger
        sleep(1);
    }

    $stats['endTime'] = time();
    $stats['duration'] = $stats['endTime'] - $stats['startTime'];

    logMessage("\n=== Résumé ===");
    logMessage("Réussis: {$stats['success']}/{$stats['total']}");
    logMessage("Modifiés: " . count($stats['modified']) . "/{$stats['total']}");
    logMessage("Introuvables chez la fédération: " . count($stats['notFoundInFede']) . "/{$stats['total']}");
    logMessage("Sans pratiquant local correspondant: " . count($stats['noLocalMatch']) . "/{$stats['total']}");
    logMessage("Erreurs: {$stats['errors']}/{$stats['total']}");
    logMessage("Durée: {$stats['duration']}s");

    // Sauvegarder les stats dans un fichier log
    saveStats($stats);

} catch (\Exception $e) {
    logMessage("ERREUR CRITIQUE: " . $e->getMessage());
    exit(1);
}

logMessage("=== Synchronisation terminée ===\n");
exit(0);

// ===== Fonctions utilitaires =====

/**
 * Log un message avec timestamp
 */
function logMessage(string $message) {
    $timestamp = date('Y-m-d H:i:s');
    echo "[{$timestamp}] {$message}\n";
}

/**
 * Charge les ids fédération (licenceNbr) de tous les pratiquants actifs et déjà liés.
 * Les pratiquants sans licenceNbr (pas encore synchronisés avec la fédération) sont ignorés.
 */
function getAllActivePratiquants(): array {
    try {
        $controller = new PMO_MyController();
        $map = $controller->queryController("
            SELECT id, licenceNbr FROM pratiquants
            WHERE deleted = 0 AND licenceNbr IS NOT NULL AND licenceNbr != ''
            ORDER BY id ASC
        ");

        $fedeIds = [];
        foreach ($map->getMap() as $line) {
            $obj = $line['pratiquants'];
            if ($obj) {
                $fedeIds[] = (int)$obj->licenceNbr;
            }
        }

        return $fedeIds;
    } catch (\Exception $e) {
        logMessage("Erreur lors du chargement des pratiquants: " . $e->getMessage());
        return [];
    }
}

/**
 * Sauvegarde les statistiques de synchronisation
 */
function saveStats(array $stats) {
    try {
        $logDir = __DIR__ . '/logs';
        
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/sync_' . date('Y-m-d') . '.json';
        
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'stats' => $stats
        ];
        
        file_put_contents($logFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
    } catch (\Exception $e) {
        logMessage("Impossible de sauvegarder les stats: " . $e->getMessage());
    }
}
?>

