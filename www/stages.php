<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Stages d'aïkido organisés par le Kome Dojo de Neupré">
  <meta name="keywords" content="Aïkido, aikido, art martial, Neupré, stages, dojo">
  <title>Kome Dojo Neupré &mdash; Stages</title>
  <?php include("links.php"); ?> 
  <link rel="stylesheet" href="stages.css">
</head>
<body>

  <!-- NAVIGATION -->
  <?php include("header.php"); ?> 


  <!-- EN-TÊTE DE PAGE -->
  <div class="page-hero">
    <p class="hero-eyebrow">Agenda</p>
    <h1 class="page-title">Stages &amp; <em>&eacute;v&eacute;nements</em></h1>
  </div>

  <!-- SÉPARATEUR 
  <div class="section-rule" style="margin-bottom: 48px;">
    <span class="rule-line"></span>
    <span class="rule-diamond"></span>
    <span class="rule-line"></span>
  </div>-->

  <!-- STAGES — générés dynamiquement depuis Dropbox -->
  <section class="stages-section">
    <?php
      require_once "dropbox/access.php";

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

      function RenderStageCard($dropboxPath, $name) {
        $dlUrl    = "DropboxDownload.php?path=" . urlencode($dropboxPath);
        $thumbUrl = "DropboxThumb2.php?path="   . urlencode($dropboxPath);
        $displayName = pathinfo($name, PATHINFO_FILENAME);
        echo '
        <a class="stage-card" href="' . $dlUrl . '" target="_blank" rel="noopener">
          <div class="stage-card-header">
            <span class="stage-card-name">' . htmlspecialchars($displayName) . '</span>
          </div>
          <img src="' . $thumbUrl . '" alt="' . htmlspecialchars($name) . '" title="' . htmlspecialchars($name) . '">
          <div class="stage-card-footer">
            <span class="stage-card-dl">PDF &darr;</span>
          </div>
        </a>';
      }

      function RenderMoisSection($folderPath, $folderTitle, $accessToken) {
        $children = GetFolderList($folderPath, $accessToken);
        if (empty($children["entries"])) return;

        sort($children["entries"]);
        echo '
        <div class="stage-mois">
          <div class="stage-mois-header">
            <h2 class="stage-mois-title">' . htmlspecialchars($folderTitle) . '</h2>
            <span class="stage-mois-line"></span>
            <span class="stage-mois-diamond"></span>
          </div>
          <div class="stage-grid">';

        foreach ($children["entries"] as $file) {
          RenderStageCard($file["path_display"], $file["name"]);
        }

        echo '
          </div>
        </div>';
      }

      // Récupération des dossiers racine /Stages
      $folderMetadata = GetFolderList("/Stages", $accessToken);
      sort($folderMetadata["entries"]);

      foreach ($folderMetadata["entries"] as $folder) {
        if ($folder[".tag"] === "folder") {
          // Nom du dossier "1. Décembre" → on retire le préfixe "N. "
          $folderTitle = ltrim(strstr($folder["name"], "."), ". ");
          RenderMoisSection($folder["path_display"], $folderTitle, $accessToken);
        }
      }
    ?>
  </section>

  <!-- FOOTER -->
  <?php include("footer.php"); ?> 


</body>
</html>
