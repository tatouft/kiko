<?php
/**
 * Script pour synchroniser les pratiquants avec les données de la fédération
 * 
 * Usage:
 *   - Mettre à jour un seul pratiquant: php sync_fede.php --id 50091
 *   - Mettre à jour plusieurs: php sync_fede.php --ids "50091,50092,50093"
 *   - Mode test (affiche seulement sans sauvegarder): php sync_fede.php --id 50091 --dry-run
 */

// Configuration de session
$_SESSION['SiteRoot'] = dirname(__FILE__);
$_SESSION['DbName'] = 'kome'; // Change selon ta base (kome, kobukai, etc.)

require_once __DIR__ . '/config/credentials.php';
require_once __DIR__ . '/scrap.php';
require_once __DIR__ . '/core/pmo/PMO_core/PMO_MyController.php';

// Parsing des arguments en ligne de commande
$options = getopt('', ['id:', 'ids:', 'dry-run']);

if (!isset($options['id']) && !isset($options['ids'])) {
    echo "Usage:\n";
    echo "  php sync_fede.php --id <fede_id>\n";
    echo "  php sync_fede.php --ids <id1,id2,id3>\n";
    echo "  php sync_fede.php --id <fede_id> --dry-run\n";
    echo "\nExemples:\n";
    echo "  php sync_fede.php --id 50091\n";
    echo "  php sync_fede.php --ids \"50091,50092,50093\"\n";
    exit(1);
}

$dryRun = isset($options['dry-run']);
$scraper = new FedeScraper();

try {
    if (!$scraper->login(FEDE_USERNAME, FEDE_PASSWORD)) {
        echo "ERREUR: Échec de la connexion à la fédération\n";
        exit(1);
    }

    $updater = new PratiquantUpdater($scraper);
    
    if (isset($options['id'])) {
        // Mettre à jour un seul pratiquant
        $fedeId = (int)$options['id'];
        
        echo "=== Synchronisation Pratiquant ===\n";
        echo "ID Fédération: {$fedeId}\n";
        echo "Mode: " . ($dryRun ? "DRY-RUN (simulation)" : "LIVE (mise à jour réelle)") . "\n";
        echo "\n";

        try {
            $membre = $scraper->getMembre($fedeId);

            echo "Données extraites:\n";
            echo "  Nom: {$membre['nom']}\n";
            echo "  Email: {$membre['fede_email']}\n";
            echo "  Adresse: {$membre['fede_adresse']}\n";
            echo "  Date de naissance: {$membre['fede_naissance']}\n";
            echo "  N° Licence Fédé: {$membre['fede_licence']}\n";
            echo "  Date Licence: {$membre['fede_licence_date']}\n";
            echo "\n";

            if (!$dryRun) {
                $result = $updater->syncByFedeId($fedeId);
                switch ($result['status']) {
                    case 'updated':
                        echo "✓ Pratiquant mis à jour avec succès (modifié)\n";
                        break;
                    case 'unchanged':
                        echo "✓ Pratiquant vérifié, aucune modification nécessaire\n";
                        break;
                    case 'no_local_match':
                        echo "✗ Aucun pratiquant local avec licenceNbr={$fedeId}\n";
                        exit(1);
                    default:
                        echo "✗ Erreur lors de la mise à jour: " . ($result['message'] ?? '') . "\n";
                        exit(1);
                }
            } else {
                echo "ℹ Mode simulation - aucune modification ne sera apportée\n";
            }

        } catch (MembreIntrouvableException $e) {
            echo "✗ INTROUVABLE CHEZ LA FÉDÉRATION: " . $e->getMessage() . "\n";
            exit(1);
        } catch (\Exception $e) {
            echo "ERREUR: " . $e->getMessage() . "\n";
            exit(1);
        }

    } else if (isset($options['ids'])) {
        // Mettre à jour plusieurs pratiquants
        $ids = array_map('intval', explode(',', $options['ids']));
        
        echo "=== Synchronisation Multiple ===\n";
        echo "Nombre de pratiquants à synchroniser: " . count($ids) . "\n";
        echo "Mode: " . ($dryRun ? "DRY-RUN (simulation)" : "LIVE (mise à jour réelle)") . "\n";
        echo "\n";

        if (!$dryRun) {
            $results = $updater->updateMultiplePratiquants($ids);
            
            echo "Résultats:\n";
            echo "  Réussis: {$results['success']}\n";
            echo "  Modifiés: {$results['modified']}\n";
            echo "  Introuvables chez la fédération: {$results['not_found_in_fede']}\n";
            echo "  Sans pratiquant local correspondant: {$results['no_local_match']}\n";
            echo "  Erreurs: {$results['errors']}\n";
            echo "\n";
            
            echo "Détails:\n";
            foreach ($results['details'] as $id => $status) {
                echo "  ID {$id}: {$status}\n";
            }
        } else {
            echo "ℹ Mode simulation - aucune modification ne sera apportée\n";
            echo "Pratiquants à synchroniser: " . implode(', ', $ids) . "\n";
        }
    }

    echo "\n=== Synchronisation terminée ===\n";

} catch (\Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

