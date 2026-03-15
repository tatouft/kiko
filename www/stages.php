<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Stages d'aïkido organisés par le Kome Dojo de Neupré">
  <meta name="keywords" content="Aïkido, aikido, art martial, Neupré, stages, dojo">
  <title>Kome Dojo Neupré &mdash; Stages</title>
  <link rel="icon" type="image/png" href="/favicon.png">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@300;400;500;700&family=Shippori+Mincho:wght@400;500;600&family=Yuji+Mai&display=swap" rel="stylesheet">

  <style>
    .page-hero {
      max-width: var(--max-w);
      margin: 0 auto;
      padding: calc(var(--nav-h) + 64px) 40px 48px;
    }
    .page-eyebrow {
      font-size: 11px;
      font-weight: 400;
      letter-spacing: 0.20em;
      text-transform: uppercase;
      color: var(--ink-soft);
      margin-bottom: 14px;
    }
    .page-title {
      font-family: var(--font-display);
      font-size: clamp(40px, 5vw, 64px);
      font-weight: 500;
      line-height: 1.05;
      color: var(--ink);
    }
    .page-title em {
      font-style: italic;
      font-weight: 400;
      color: var(--orange);
    }

    .stages-section {
      max-width: var(--max-w);
      margin: 0 auto;
      padding: 0 40px 100px;
      display: flex;
      flex-direction: column;
      gap: 56px;
    }

    .stage-mois {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .stage-mois-header {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .stage-mois-title {
      font-family: var(--font-display);
      font-size: 22px;
      font-weight: 500;
      color: var(--ink);
      white-space: nowrap;
    }

    .stage-mois-line {
      flex: 1;
      height: 1px;
      background: var(--paper-rule);
    }

    .stage-mois-diamond {
      width: 6px;
      height: 6px;
      background: var(--orange);
      transform: rotate(45deg);
      flex-shrink: 0;
    }

    .stage-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 16px;
    }

    .stage-card {
      display: flex;
      flex-direction: column;
      gap: 12px;
      background: var(--paper-warm);
      border: 1px solid var(--paper-rule);
      padding: 14px;
      transition: background 0.2s, border-color 0.2s;
      text-decoration: none;
    }
    .stage-card:hover {
      background: #e8dfc9;
      border-color: var(--orange);
    }
    .stage-card img {
      width: 100%;
      aspect-ratio: 3/4;
      object-fit: cover;
      display: block;
      background: var(--paper-rule);
    }
    .stage-card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }
    .stage-card-name {
      font-size: 12px;
      font-weight: 400;
      color: var(--ink-mid);
      line-height: 1.4;
      flex: 1;
    }
    .stage-card-dl {
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--orange);
      white-space: nowrap;
      flex-shrink: 0;
    }
    .stage-card:hover .stage-card-dl { text-decoration: underline; }

    @media (max-width: 580px) {
      .page-hero      { padding-left: 20px; padding-right: 20px; }
      .stages-section { padding-left: 20px; padding-right: 20px; padding-bottom: 72px; }
      .stage-grid     { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>

  <!-- NAVIGATION -->
  <?php include("header.php"); ?> 


  <!-- EN-TÊTE DE PAGE -->
  <div class="page-hero">
    <p class="page-eyebrow">Agenda</p>
    <h1 class="page-title">Stages &amp; <em>&eacute;v&eacute;nements</em></h1>
  </div>

  <!-- SÉPARATEUR -->
  <div class="section-rule" style="margin-bottom: 48px;">
    <span class="rule-line"></span>
    <span class="rule-diamond"></span>
    <span class="rule-line"></span>
  </div>

  <!-- STAGES — générés dynamiquement depuis Dropbox -->
  <section class="stages-section">
    <?php
      require_once "../dropbox/access.php";

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
        echo '
        <a class="stage-card" href="../' . $dlUrl . '" target="_blank" rel="noopener">
          <img src="../' . $thumbUrl . '" alt="' . htmlspecialchars($name) . '" title="' . htmlspecialchars($name) . '">
          <div class="stage-card-footer">
            <span class="stage-card-name">' . htmlspecialchars($name) . '</span>
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
