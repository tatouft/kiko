# Synchronisation des données de Fédération

Ce système permet de scraper les données des pratiquants depuis le site de la fédération et de les synchroniser automatiquement avec la base de données locale.

## Installation et Configuration

### 1. Migration de la base de données

Exécutez le script de migration pour ajouter les nouveaux champs à la table `pratiquants`:

```bash
php migrate_fede_fields.php
```

Cela ajoutera les 5 champs suivants :
- `fede_licence` (INTEGER) : Numéro de licence fédération
- `fede_naissance` (DATE) : Date de naissance au format YYYY-MM-DD
- `fede_email` (TEXT) : Email provenant de la fédération
- `fede_adresse` (TEXT) : Adresse provenant de la fédération
- `fede_licence_date` (DATE) : Date d'expiration de la licence

### 2. Configuration des identifiants

Les identifiants de la fédération doivent être configurés dans `config/credentials.php`:

```php
define('FEDE_USERNAME', 'votre_username');
define('FEDE_PASSWORD', 'votre_password');
define('FEDE_BASE_URL', 'https://xxx');
```

## Utilisation

### Mettre à jour un seul pratiquant

```bash
php sync_fede.php --id 50091
```

### Mettre à jour plusieurs pratiquants

```bash
php sync_fede.php --ids "50091,50092,50093"
```

### Mode test (dry-run)

Pour voir les données qui seraient mises à jour sans effectuer la modification:

```bash
php sync_fede.php --id 50091 --dry-run
```

## Architecture

### Classe FedeScraper
La classe `FedeScraper` (dans `scrap.php`) gère :
- La connexion à la fédération via CURL
- L'authentification
- Le scraping des données des membres
- L'extraction des informations dans le bon format

Les données extraites incluent :
- `fede_licence` : Le numéro de licence actuelle
- `fede_licence_date` : La date d'expiration de la licence actuelle
- `fede_naissance` : Date de naissance convertie au format YYYY-MM-DD
- `fede_email` : Email du personnel
- `fede_adresse` : Adresse du personnel

### Classe PratiquantUpdater
La classe `PratiquantUpdater` (dans `scrap.php`) gère :
- La récupération des données via le scraper
- La mise à jour des objets PMO dans la base de données
- La gestion des erreurs

Elle utilise le système PMO existant pour manipuler les objets `pratiquants`.

## Format des données

### Dates
Les dates provenant de la fédération (format `dd/mm/yyyy`) sont converties au format `yyyy-mm-dd` pour la base de données.

### Numéro de licence
Le numéro de licence est extrait des informations de licence fédération et converti en entier.

### Adresse et Email
Ces champs sont extraits directement de l'onglet "Personnel" de la fédération (accessible si vous avez les permissions).

## Intégration avec l'application

Vous pouvez utiliser les nouvelles données dans vos pages :

```php
// Charger un pratiquant
$prat = PMO_MyObject::factory('pratiquants');
$prat->id = $id;
$prat->load();

// Accéder aux données de fédération
echo $prat->fede_email;
echo $prat->fede_licence;
echo $prat->fede_licence_date;
echo $prat->fede_naissance;
echo $prat->fede_adresse;
```

## Dépannage

### "Session expirée"
Si vous obtenez ce message, les identifiants dans `config/credentials.php` sont probablement incorrects.

### "Impossible de se connecter à la base de données"
Vérifiez que la variable `$_SESSION['DbName']` dans `sync_fede.php` et `migrate_fede_fields.php` correspond à votre base actuelle.

### Le champ ne s'ajoute pas
Si le champ existe déjà ou si SQLite retourne une erreur, vous pouvez vérifier manuellement :

```sql
PRAGMA table_info(pratiquants);
```

## API Scraper

### FedeScraper::getMembre($id)
Retourne un tableau avec toutes les données du membre:

```php
[
    'id'              => int,
    'nom'             => string,
    'numero_licence'  => string,
    'club'            => string,
    'date_naissance'  => string (dd/mm/yyyy),
    'sexe'            => string,
    'grade'           => string,
    'adresse'         => string|null,
    'telephone'       => string|null,
    'email'           => string|null,
    'licences'        => array of ['echeance' => '...', 'club' => '...'],
    'grades'          => array of ['grade' => '...', 'date' => '...', 'type' => '...'],
    'fede_licence'    => int|null,
    'fede_licence_date' => string|null (yyyy-mm-dd),
    'fede_naissance'  => string|null (yyyy-mm-dd),
    'fede_email'      => string|null,
    'fede_adresse'    => string|null
]
```

## Sécurité

- Les identifiants sont stockés dans `config/credentials.php` (assurez-vous de la permission 600)
- Les cookies de session sont temporaires et supprimés après chaque utilisation
- Les données scrapées sont validées avant insertion dans la DB
- Les paramètres en ligne de commande sont convertis en entiers pour éviter les injections


