<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Stages, événements et actualités du Kome Dojo de Neupré">
  <meta name="keywords" content="Aïkido, aikido, art martial, Neupré, stages, événements, dojo">
  <title>Kome Dojo Neupré &mdash; Événements</title>
  <?php include("links.php"); ?>
  <link rel="stylesheet" href="evenements.css">
</head>
<body>

  <!-- NAVIGATION -->
  <?php include("header.php"); ?>


  <!-- EN-TÊTE DE PAGE -->
  <div class="page-hero">
    <p class="hero-eyebrow">Agenda</p>
    <h1 class="page-title">Nos <em>&eacute;v&eacute;nements</em></h1>
  </div>

  <!-- SÉPARATEUR
  <div class="section-rule" style="margin-bottom: 48px;">
    <span class="rule-line"></span>
    <span class="rule-diamond"></span>
    <span class="rule-line"></span>
  </div>-->

  <!-- ÉVÉNEMENTS — stages générés depuis Dropbox + événements ajoutés à la main -->
  <section class="events-section">
    <?php
      require_once "dropbox/access.php";

      // Événements sans document Dropbox (rentrée, congés, etc.).
      // Clé au même format que les dossiers Dropbox ("N. Mois") : le numéro détermine
      // l'ordre d'affichage et fusionne l'événement dans le mois correspondant.
      // "image" est optionnelle : chemin vers un visuel stocké sur l'hébergement
      // (ex. images fixes réutilisées d'une saison à l'autre). Sans "image",
      // le titre s'affiche en grand à la place.
      $manualEvents = [
         "1. Septembre" => [
           ["title" => "Rentrée", "image" => "/images/evenements/rentree.svg", "note" => "Enfants: <strong>samedi 5</strong><br/>Adultes: <strong>mardi 8</strong>"],
         ],
        // "3. Novembre" => [
        //   ["title" => "Congés Toussaint", "note" => "Pas de cours du 1er au 8"],
        // ],
      ];

      function GetFolderList($path, $accessToken) {
        $ch = curl_init('https://api.dropboxapi.com/2/files/list_folder');
        curl_setopt_array($ch, array(
          CURLOPT_POST           => TRUE,
          CURLOPT_RETURNTRANSFER => TRUE,
          CURLOPT_HTTPHEADER     => array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
          ),
          CURLOPT_POSTFIELDS => json_encode(array('path' => $path))
        ));
        $response = curl_exec($ch);
        if ($response === FALSE) die(curl_error($ch));
        return json_decode($response, TRUE);
      }

      function RenderDocumentCard($dropboxPath, $name) {
        $dlUrl    = "DropboxDownload.php?path=" . urlencode($dropboxPath);
        $thumbUrl = "DropboxThumb2.php?path="   . urlencode($dropboxPath);
        $displayName = pathinfo($name, PATHINFO_FILENAME);
        echo '
        <a class="event-card" href="' . $dlUrl . '" target="_blank" rel="noopener">
          <div class="event-card-header">
            <span class="event-card-name">' . htmlspecialchars($displayName) . '</span>
          </div>
          <img src="' . $thumbUrl . '" alt="' . htmlspecialchars($name) . '" title="' . htmlspecialchars($name) . '">
          <div class="event-card-footer">
            <span class="event-card-dl">PDF &darr;</span>
          </div>
        </a>';
      }

      // Taille de police décroissante selon la longueur du titre, pour qu'il tienne
      // toujours dans la zone "affiche" sans JS.
      function EventVisualFontSize($title) {
        $len = mb_strlen(trim($title), "UTF-8");
        if ($len <= 6)  return 34;
        if ($len <= 10) return 27;
        if ($len <= 16) return 21;
        if ($len <= 24) return 17;
        if ($len <= 34) return 14;
        return 12;
      }

      function RenderTextCard($event) {
        if (!empty($event["image"])) {
          // Visuel fixe fourni : même gabarit que les cartes Dropbox, avec le titre en en-tête
          // (utile puisqu'un même visuel peut être réutilisé pour plusieurs événements).
          $visual = '
          <div class="event-card-header">
            <span class="event-card-name">' . htmlspecialchars($event["title"]) . '</span>
          </div>
          <img src="' . htmlspecialchars($event["image"]) . '" alt="' . htmlspecialchars($event["title"]) . '">';
        } else {
          // Pas de visuel : le titre sert lui-même d'"image", en grand.
          $fontSize = EventVisualFontSize($event["title"]);
          $visual = '
          <div class="event-card-visual">
            <span class="event-card-visual-text" style="font-size: ' . $fontSize . 'px;">' . htmlspecialchars($event["title"]) . '</span>
            <span class="event-card-visual-mark"></span>
          </div>';
        }

        echo '
        <div class="event-card event-card--text">' . $visual . (!empty($event["note"]) ? '
          <div class="event-card-footer event-card-footer--center">
            <span class="event-card-note">' . $event["note"] . '</span>
          </div>' : '') . '
        </div>';
      }

      // "1. Décembre" → "Décembre" (le numéro ne sert qu'au tri)
      function MonthTitleFromKey($key) {
        $title = strpos($key, ".") !== false ? ltrim(strstr($key, "."), ". ") : "";
        return $title !== "" ? $title : $key;
      }

      function MonthSortValue($key) {
        preg_match('/^\d+/', $key, $match);
        return isset($match[0]) ? (int)$match[0] : 0;
      }

      // Regroupement par nom de mois (insensible à la casse) plutôt que par clé complète,
      // pour qu'un événement manuel fusionne avec le dossier Dropbox du même mois même si
      // son préfixe numérique ne correspond pas exactement. Le numéro Dropbox fait autorité
      // pour le tri quand un dossier existe déjà pour ce mois.
      function NormalizeMonthTitle($title) {
        return mb_strtolower(trim($title), "UTF-8");
      }

      $months = []; // titreNormalisé => ["title", "sort", "dropboxPath", "manual"]

      $folderMetadata = GetFolderList("/Stages", $accessToken);
      foreach ($folderMetadata["entries"] as $folder) {
        if ($folder[".tag"] !== "folder") continue;
        $title = MonthTitleFromKey($folder["name"]);
        $key = NormalizeMonthTitle($title);
        $months[$key] = [
          "title"       => $title,
          "sort"        => MonthSortValue($folder["name"]),
          "dropboxPath" => $folder["path_display"],
          "manual"      => isset($months[$key]) ? $months[$key]["manual"] : [],
        ];
      }

      foreach ($manualEvents as $monthKeyRaw => $events) {
        $title = MonthTitleFromKey($monthKeyRaw);
        $key = NormalizeMonthTitle($title);
        if (!isset($months[$key])) {
          $months[$key] = [
            "title"       => $title,
            "sort"        => MonthSortValue($monthKeyRaw),
            "dropboxPath" => null,
            "manual"      => [],
          ];
        }
        $months[$key]["manual"] = array_merge($months[$key]["manual"], $events);
      }

      uasort($months, function($a, $b) { return $a["sort"] - $b["sort"]; });

      foreach ($months as $month) {
        $documents = [];
        if ($month["dropboxPath"] !== null) {
          $children = GetFolderList($month["dropboxPath"], $accessToken);
          $documents = empty($children["entries"]) ? [] : $children["entries"];
          usort($documents, function($a, $b) {
            return MonthSortValue($a["name"]) - MonthSortValue($b["name"]);
          });
        }

        if (empty($documents) && empty($month["manual"])) continue;

        echo '
        <div class="event-mois">
          <div class="event-mois-header">
            <h2 class="event-mois-title">' . htmlspecialchars($month["title"]) . '</h2>
            <span class="event-mois-line"></span>
            <span class="event-mois-diamond"></span>
          </div>
          <div class="event-grid">';

        foreach ($documents as $file) {
          RenderDocumentCard($file["path_display"], $file["name"]);
        }
        foreach ($month["manual"] as $event) {
          RenderTextCard($event);
        }

        echo '
          </div>
        </div>';
      }
    ?>
  </section>

  <!-- FOOTER -->
  <?php include("footer.php"); ?>


</body>
</html>
