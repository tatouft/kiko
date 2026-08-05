<?php

require_once __DIR__ . '/config/credentials.php';
require_once __DIR__ . '/core/pmo/PMO_core/PMO_MyController.php';
require_once __DIR__ . '/core/pmo/PMO_core/class_loader/class_pratiquants.php';

/**
 * Levée quand un id fédération ne correspond à aucun membre
 * (le site redirige silencieusement vers l'accueil au lieu de renvoyer une 404)
 */
class MembreIntrouvableException extends \RuntimeException {
}

/**
 * Levée quand la page membre a été chargée mais que le parsing n'a rien pu en extraire
 * (structure HTML du site fédé changée). Sans ce garde-fou, getMembre() renverrait
 * silencieusement un tableau de valeurs null, qui écraserait les données déjà en base.
 */
class StructurePageChangeeException extends \RuntimeException {
}

class FedeScraper {
    private $ch;
    private $cookieFile;
    private $baseUrl;
    private $lastDiagnostics = [];
    private $lastHeaders = [];

    public function __construct() {
        $this->baseUrl = FEDE_BASE_URL;
        $this->cookieFile = tempnam(sys_get_temp_dir(), 'fede_cookies_');
        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEJAR      => $this->cookieFile,
            CURLOPT_COOKIEFILE     => $this->cookieFile,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            CURLOPT_SSL_VERIFYPEER => true,
            // Capture les en-têtes bruts de la réponse - pas accessible via SSH sur
            // l'hébergement mutualisé, donc c'est la seule façon de voir ce qu'un WAF/CDN
            // devant le site de la fédération répond réellement à une requête venant de
            // cette IP (Set-Cookie absent, server: cloudflare, etc.)
            CURLOPT_HEADERFUNCTION => function ($ch, $header) {
                $this->lastHeaders[] = rtrim($header, "\r\n");
                return strlen($header);
            },
        ]);
    }

    /**
     * Diagnostics de la dernière tentative de login (utile quand login() échoue
     * silencieusement en prod - cookiejar non inscriptible, TLS bloqué, etc.)
     */
    public function getLastDiagnostics(): array {
        return $this->lastDiagnostics;
    }

    private function get(string $url): string {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        $result = curl_exec($this->ch);
        return $result === false ? '' : $result;
    }

    private function extractCsrfToken(string $html): ?string {
        if (preg_match('/<input[^>]+name=["\']_token["\'][^>]+value=["\'](.*?)["\']/', $html, $m)) {
            return $m[1];
        }
        if (preg_match('/<input[^>]+value=["\'](.*?)["\'][^>]+name=["\']_token["\']/', $html, $m)) {
            return $m[1];
        }
        return null;
    }

    public function login(string $username, string $password): bool {
        $this->lastDiagnostics = [
            'cookie_file'      => $this->cookieFile,
            'cookie_writable'  => is_writable(dirname($this->cookieFile)),
        ];

        $this->lastHeaders = [];
        $loginPageHtml = $this->get($this->baseUrl . '/fr/login');
        $this->lastDiagnostics['get_curl_error'] = curl_error($this->ch);
        $this->lastDiagnostics['get_http_code'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->lastDiagnostics['get_response_headers'] = $this->lastHeaders;
        $this->lastDiagnostics['cookie_file_size'] = is_file($this->cookieFile) ? filesize($this->cookieFile) : null;

        $csrfToken = $this->extractCsrfToken($loginPageHtml);
        $this->lastDiagnostics['csrf_found'] = $csrfToken !== null;

        if (!$csrfToken) {
            throw new \RuntimeException("CSRF token introuvable");
        }

        curl_setopt_array($this->ch, [
            CURLOPT_URL        => $this->baseUrl . '/fr/login',
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'Username' => $username,
                'Password' => $password,
                '_token'   => $csrfToken,
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Referer: ' . $this->baseUrl . '/fr/login',
            ],
        ]);

        $this->lastHeaders = [];
        $postResponseBody = curl_exec($this->ch);
        $finalUrl = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);

        $this->lastDiagnostics['post_curl_error'] = curl_error($this->ch);
        $this->lastDiagnostics['post_http_code'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $this->lastDiagnostics['post_response_headers'] = $this->lastHeaders;
        $this->lastDiagnostics['post_body_snippet'] = $postResponseBody === false ? null : substr($postResponseBody, 0, 500);
        $this->lastDiagnostics['final_url'] = $finalUrl;

        return !str_contains($finalUrl, '/fr/login');
    }

    public function getMembre(int $id): array {
         $html = $this->get($this->baseUrl . '/fr/membre/' . $id);

         if (str_contains($html, 'name="_token"')) {
             throw new \RuntimeException("Session expirée");
         }

         // Un id qui n'existe pas (plus) chez la fédération redirige silencieusement
         // vers l'accueil (HTTP 200, pas de 404) plutôt que de renvoyer une erreur.
         $finalUrl = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
         if (!str_contains($finalUrl, '/membre/' . $id)) {
             throw new MembreIntrouvableException("Membre {$id} introuvable chez la fédération");
         }

         $doc = new DOMDocument();
         @$doc->loadHTML('<?xml encoding="UTF-8">' . $html);
         $xpath = new DOMXPath($doc);

         // Données de la card principale (toujours visibles)
         $membre = [
             'id'              => $id,
             'nom'             => $this->xpathFirst($xpath, '//div[@class="card-header bg-primary text-white text-center user-select-none"]/h2'),
             'numero_licence'  => $this->xpathSibling($xpath, 'N° Licence'),
             'club'            => $this->xpathSibling($xpath, 'Club'),
             'date_naissance'  => $this->xpathSibling($xpath, 'Née le') ?? $this->xpathSibling($xpath, 'Né le'),
             'sexe'            => $this->xpathSibling($xpath, 'Sexe'),
             'grade'           => $this->xpathSibling($xpath, 'Grade'),
         ];

         // Garde-fou : pour un membre réellement trouvé, nom et n° licence sont
         // toujours présents. S'ils sont tous les deux absents, c'est que le parsing
         // a échoué (changement de structure du site) plutôt qu'un membre sans données -
         // on refuse d'écraser des données existantes avec des valeurs vides.
         if ($membre['nom'] === null && $membre['numero_licence'] === null) {
             throw new StructurePageChangeeException(
                 "Impossible d'extraire les données du membre {$id} : la structure de la page a peut-être changé"
             );
         }

         // Onglet Personnel (visible si tu as les droits)
         $membre['adresse'] = $this->xpathPersonnel($xpath, 'Adresse');
         $membre['telephone'] = $this->xpathPersonnel($xpath, 'Téléphone');
         $membre['email'] = $this->xpathPersonnel($xpath, 'Email');

         // Historique des licences (onglet Licence)
         $membre['licences'] = $this->parseLicences($xpath);

         // Historique des grades (onglet Tatamis)
         $membre['grades'] = $this->parseGrades($xpath);

         // Données fédération pour les champs de la DB
         $membre['fede_licence'] = $membre['numero_licence'] !== null ? (int)$membre['numero_licence'] : null;
         $membre['fede_licence_date'] = $this->extractFedeLicenceDate($membre['licences']);
         $membre['fede_naissance'] = $this->formatDate($membre['date_naissance']);
         $membre['fede_email'] = $membre['email'];
         $membre['fede_adresse'] = $membre['adresse'];

         return $membre;
     }

     /**
      * Extrait la date de licence actuelle (première ligne du tableau)
      */
     private function extractFedeLicenceDate(array $licences): ?string {
         if (!empty($licences) && isset($licences[0])) {
             $echeance = $licences[0]['echeance'];
             return $this->parseDate($echeance);
         }
         return null;
     }

     /**
      * Parse une date au format "dd/mm/yyyy" vers "yyyy-mm-dd"
      */
     private function parseDate(?string $date): ?string {
         if (!$date) return null;
         try {
             $d = \DateTime::createFromFormat('d/m/Y', $date);
             return $d ? $d->format('Y-m-d') : null;
         } catch (\Exception $e) {
             return null;
         }
     }

     /**
      * Formate une date au format "dd/mm/yyyy" vers "yyyy-mm-dd"
      */
     private function formatDate(?string $date): ?string {
         return $this->parseDate($date);
     }

    /**
     * Récupère la valeur dans la card principale :
     * cherche le <strong> contenant le label, puis prend le div.col suivant
     */
    private function xpathSibling(DOMXPath $xpath, string $label): ?string {
        $query = '//div[@class="card-body row align-items-center"]
                  //strong[normalize-space(text())="' . $label . '"]
                  /ancestor::div[contains(@class,"col-4")]
                  /following-sibling::div[contains(@class,"col")]';
        return $this->xpathFirst($xpath, $query);
    }

    /**
     * Récupère la valeur dans l'onglet Personnel :
     * même structure mais dans #personal
     */
    private function xpathPersonnel(DOMXPath $xpath, string $label): ?string {
        $query = '//div[@id="personal"]
                  //strong[normalize-space(text())="' . $label . '"]
                  /ancestor::div[contains(@class,"col-4")]
                  /following-sibling::div[contains(@class,"col")]';
        return $this->xpathFirst($xpath, $query);
    }

    /**
     * Parse le tableau des licences (onglet Licence)
     * Retourne un tableau de ['echeance' => '...', 'club' => '...']
     */
    private function parseLicences(DOMXPath $xpath): array {
        $rows = $xpath->query('//div[@id="licence"]//tbody/tr');
        $licences = [];
        foreach ($rows as $row) {
            $cells = $xpath->query('td', $row);
            if ($cells->length >= 2) {
                $licences[] = [
                    'echeance' => trim($cells->item(0)->textContent),
                    'club'     => trim($cells->item(1)->textContent),
                ];
            }
        }
        return $licences;
    }

    /**
     * Parse le tableau des grades (onglet Tatamis)
     * Retourne un tableau de ['grade' => '...', 'date' => '...', 'type' => '...']
     */
    private function parseGrades(DOMXPath $xpath): array {
        $rows = $xpath->query('//div[@id="tatamis"]//tbody/tr');
        $grades = [];
        foreach ($rows as $row) {
            $cells = $xpath->query('td', $row);
            // Ignore les lignes avec un bouton "Ajouter" (colspan=4)
            if ($cells->length >= 3 && !$cells->item(0)->getAttribute('colspan')) {
                $grades[] = [
                    'grade' => trim($cells->item(0)->textContent),
                    'date'  => trim($cells->item(1)->textContent),
                    'type'  => trim($cells->item(2)->textContent),
                ];
            }
        }
        return $grades;
    }

    private function xpathFirst(DOMXPath $xpath, string $query): ?string {
        $nodes = $xpath->query($query);
        return ($nodes && $nodes->length > 0) ? trim($nodes->item(0)->textContent) : null;
    }

    /**
     * Télécharge le PDF "Formulaire de renouvellement" d'un membre (onglet Licence).
     * Retourne null si la réponse n'est pas un PDF (session expirée, membre sans
     * renouvellement en cours, etc.) plutôt que de sauvegarder une page d'erreur HTML.
     */
    public function downloadFormulaireRenouvellement(int $id): ?string {
        $pdf = $this->get($this->baseUrl . '/fr/membre/' . $id . '/formulaire-renouvellement/');
        $contentType = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);

        if ($pdf === '' || !str_contains((string)$contentType, 'application/pdf')) {
            return null;
        }

        return $pdf;
    }

    public function __destruct() {
        @unlink($this->cookieFile);
    }
}

/**
 * Gère la mise à jour des pratiquants dans la base de données
 * avec les données scrapées de la fédération
 */
class PratiquantUpdater {
    private $scraper;

    public function __construct(FedeScraper $scraper) {
        $this->scraper = $scraper;
    }

    /**
     * Cherche un pratiquant local par son numéro de licence fédération (licenceNbr).
     * C'est ce champ, et non l'id local, qui correspond à l'id fédération.
     */
    public function findLocalByLicenceNbr(int $licenceNbr): ?PMO_MyObject {
        return pratiquants::GetByLicenceNbr($licenceNbr);
    }

    /**
     * Synchronise un pratiquant local déjà chargé (doit avoir un licenceNbr) avec la fédération.
     * Ne touche pas aux champs fede_* si le membre n'est plus trouvé chez la fédération :
     * ce cas doit être signalé (pratiquant actif chez nous, disparu chez la fédé), pas effacer l'historique.
     *
     * Retourne ['status' => 'updated'|'unchanged'|'not_found_in_fede'|'error', 'message' => ?string]
     */
    public function syncPratiquant(PMO_MyObject $prat): array {
        $fedeId = (int)$prat->licenceNbr;
        $timings = [];
        $oldLicenceDate = $prat->fede_licence_date;

        $t0 = microtime(true);
        try {
            $membre = $this->scraper->getMembre($fedeId);
        } catch (MembreIntrouvableException $e) {
            $timings['scrape'] = microtime(true) - $t0;
            return ['status' => 'not_found_in_fede', 'message' => $e->getMessage(), 'timings' => $timings];
        } catch (\Exception $e) {
            $timings['scrape'] = microtime(true) - $t0;
            error_log("Erreur lors de la synchronisation du pratiquant (licenceNbr {$fedeId}): " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'timings' => $timings];
        }
        $timings['scrape'] = microtime(true) - $t0;

        $changed = (
            $prat->fede_licence != $membre['fede_licence'] ||
            $prat->fede_licence_date != $membre['fede_licence_date'] ||
            $prat->fede_naissance != $membre['fede_naissance'] ||
            $prat->fede_email != $membre['fede_email'] ||
            $prat->fede_adresse != $membre['fede_adresse']
        );

        if (!$changed) {
            return ['status' => 'unchanged', 'message' => null, 'timings' => $timings];
        }

        $prat->fede_licence = $membre['fede_licence'];
        $prat->fede_licence_date = $membre['fede_licence_date'];
        $prat->fede_naissance = $membre['fede_naissance'];
        $prat->fede_email = $membre['fede_email'];
        $prat->fede_adresse = $membre['fede_adresse'];

        $t1 = microtime(true);
        try {
            $prat->commit();
        } catch (\Exception $e) {
            $timings['commit'] = microtime(true) - $t1;
            error_log("Erreur lors de la sauvegarde du pratiquant (licenceNbr {$fedeId}): " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'timings' => $timings];
        }
        $timings['commit'] = microtime(true) - $t1;

        // Le formulaire de renouvellement n'a de sens que si la date de licence a
        // vraiment changé (nouvelle licence/renouvellement) - pas pour un simple
        // changement d'email ou d'adresse, qui déclenche $changed mais pas ceci.
        $pdfDownloaded = false;
        if ($oldLicenceDate != $membre['fede_licence_date'] && !empty($membre['fede_licence_date'])) {
            $t2 = microtime(true);
            $pdfContent = $this->scraper->downloadFormulaireRenouvellement($fedeId);
            if ($pdfContent !== null) {
                $pdfDownloaded = $this->savePdfFormulaire($fedeId, $pdfContent);
            }
            $timings['pdf'] = microtime(true) - $t2;
        }

        return ['status' => 'updated', 'message' => null, 'timings' => $timings, 'pdf_downloaded' => $pdfDownloaded];
    }

    /**
     * Sauvegarde le PDF de renouvellement dans kiko/uploads/fede_formulaires/{licenceNbr}.pdf
     */
    private function savePdfFormulaire(int $fedeId, string $pdfContent): bool {
        $dir = __DIR__ . '/uploads/fede_formulaires';
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            error_log("Impossible de créer le dossier {$dir}");
            return false;
        }

        return file_put_contents($dir . '/' . $fedeId . '.pdf', $pdfContent) !== false;
    }

    /**
     * Synchronise un pratiquant à partir d'un id fédération : cherche d'abord le
     * pratiquant local correspondant (par licenceNbr) avant de scraper.
     */
    public function syncByFedeId(int $fedeId): array {
        $prat = $this->findLocalByLicenceNbr($fedeId);
        if (!$prat) {
            return ['status' => 'no_local_match', 'message' => "Aucun pratiquant local avec licenceNbr={$fedeId}"];
        }
        return $this->syncPratiquant($prat);
    }

    /**
     * Synchronise plusieurs pratiquants à partir d'un tableau d'ids fédération.
     */
    public function updateMultiplePratiquants(array $fedeIds): array {
        $results = [
            'success' => 0,
            'modified' => 0,
            'not_found_in_fede' => 0,
            'no_local_match' => 0,
            'errors' => 0,
            'details' => []
        ];

        foreach ($fedeIds as $fedeId) {
            $result = $this->syncByFedeId($fedeId);
            switch ($result['status']) {
                case 'updated':
                    $results['success']++;
                    $results['modified']++;
                    $results['details'][$fedeId] = 'OK (modifié)';
                    break;
                case 'unchanged':
                    $results['success']++;
                    $results['details'][$fedeId] = 'OK (inchangé)';
                    break;
                case 'not_found_in_fede':
                    $results['not_found_in_fede']++;
                    $results['details'][$fedeId] = 'INTROUVABLE CHEZ LA FÉDÉRATION';
                    break;
                case 'no_local_match':
                    $results['no_local_match']++;
                    $results['details'][$fedeId] = 'AUCUN PRATIQUANT LOCAL AVEC CE N° LICENCE';
                    break;
                default:
                    $results['errors']++;
                    $results['details'][$fedeId] = 'ERREUR: ' . ($result['message'] ?? '');
            }
        }

        return $results;
    }
}