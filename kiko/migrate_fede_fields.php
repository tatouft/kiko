<?php
/**
 * Script de migration pour ajouter les champs de fédération à la table pratiquants
 * Usage: php migrate_fede_fields.php
 */

// Configuration (SiteRoot / DbName viennent de config/config.php)
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
}
require_once __DIR__ . '/config/config.php';

require_once __DIR__ . '/core/pmo/PMO_core/PMO_MyController.php';

try {
    // Obtenir la connexion à la base de données
    $sgbd = PMO_MySgbd::factorySgbd();
    $db = $sgbd->getDB();
    
    if (!$db) {
        throw new \Exception("Impossible de se connecter à la base de données");
    }

    echo "=== Migration des champs de fédération ===\n\n";
    echo "Base de données: {$_SESSION['DbName']}\n\n";

    // Listes des champs à ajouter
    $fields = [
        'fede_licence' => 'INTEGER',
        'fede_naissance' => 'DATE',
        'fede_email' => 'TEXT',
        'fede_adresse' => 'TEXT',
        'fede_licence_date' => 'DATE'
    ];

    foreach ($fields as $fieldName => $fieldType) {
        try {
            // Vérifier si le champ existe déjà
            $result = $db->query("PRAGMA table_info(pratiquants)");
            $columns = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $fieldExists = false;
            foreach ($columns as $col) {
                if ($col['name'] === $fieldName) {
                    $fieldExists = true;
                    break;
                }
            }

            if ($fieldExists) {
                echo "✓ Le champ '{$fieldName}' existe déjà\n";
            } else {
                // Ajouter le champ
                $sql = "ALTER TABLE pratiquants ADD {$fieldName} {$fieldType}";
                $db->exec($sql);
                echo "✓ Champ '{$fieldName}' ({$fieldType}) ajouté avec succès\n";
            }
        } catch (\PDOException $e) {
            echo "✗ Erreur lors de l'ajout du champ '{$fieldName}': " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== Migration terminée ===\n";

} catch (\Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    exit(1);
}
?>

