# Synchronisation fédération — les 3 scripts

Les trois scripts partagent le même moteur (`PratiquantUpdater` dans `scrap.php`), mais ont des points d'entrée différents.

## `sync_fede.php` — usage manuel/ponctuel (CLI)

Pour tester ou forcer un pratiquant précis.

```bash
php sync_fede.php --id 50091 [--dry-run]
php sync_fede.php --ids "50091,50092,50093"
```

Tu donnes des ids fédération à la main. `--dry-run` affiche juste les données scrapées sans rien écrire en base. Rapport texte affiché directement dans le terminal.

## `sync_all.php` — le bouton sur le site (web)

Appelé en POST (bloqué sinon, et bloqué aussi en mode maintenance). Pas d'id à fournir : il charge lui-même tous les pratiquants actifs (`deleted=0`) et les synchronise un par un.

Sort une page HTML avec :
- statistiques (total, non liés à la fédération, réussis, introuvables, erreurs, durée)
- tableau des pratiquants modifiés
- tableau des pratiquants introuvables chez la fédération (à leur signaler)
- tableau des erreurs

C'est le plus complet niveau rapport visuel.

## `sync_batch.php` — pensé pour tourner seul (cron)

```bash
0 2 * * * php sync_batch.php >> /var/log/sync_fede.log 2>&1
```

Pas d'interface web, pas d'argument. Il lit :
- soit `config/sync_pratiquants.json` (liste d'ids fédération choisis à la main) si ce fichier existe,
- sinon il retombe sur tous les pratiquants actifs déjà liés à la fédération (comme `sync_all.php`).

Log ligne par ligne avec timestamp dans le terminal/fichier, et sauvegarde un résumé JSON dans `logs/sync_YYYY-MM-DD.json` à chaque exécution — utile pour garder un historique si c'est un cron quotidien.

## En résumé

| Script | Déclenchement | Cible | Rapport |
|---|---|---|---|
| `sync_fede.php` | Manuel, CLI | Ids donnés à la main | Texte terminal |
| `sync_all.php` | Bouton web (POST) | Tous les actifs | Page HTML |
| `sync_batch.php` | Cron | JSON ou tous les actifs | Log + JSON historique |

## Comment ça matche un pratiquant

Le lien entre un pratiquant local et son id fédération se fait via le champ **`licenceNbr`**, pas via l'id local (`pratiquants.id`) — les deux numérotations sont indépendantes. Un pratiquant actif sans `licenceNbr` n'est simplement pas encore lié à la fédération (normal pour un nouvel inscrit) et est ignoré silencieusement, pas compté en erreur.

## Statuts possibles pour un pratiquant

- **`updated`** — synchronisé, des champs `fede_*` ont changé.
- **`unchanged`** — synchronisé, rien à mettre à jour.
- **`not_found_in_fede`** — actif localement, `licenceNbr` renseigné, mais introuvable chez la fédération. Les champs `fede_*` existants ne sont **pas effacés** (pas question de perdre l'historique). À signaler à la fédération.
- **`no_local_match`** — id fédération donné (via `--id`/`--ids` ou le JSON) qui ne correspond à aucun `licenceNbr` local.
- **`error`** — échec technique (session expirée, connexion perdue, etc.).
