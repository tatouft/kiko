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
  <header class="site-header">
    <div class="bg-pattern" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
        <defs>
          <radialGradient id="grad-header" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
            <stop offset="9%"  stop-color="#e8d5a0"/>
            <stop offset="10%" stop-color="#c4601a"/>
            <stop offset="19%" stop-color="#c4601a"/>
            <stop offset="20%" stop-color="#e8d5a0"/>
            <stop offset="29%" stop-color="#e8d5a0"/>
            <stop offset="30%" stop-color="#c4601a"/>
            <stop offset="39%" stop-color="#c4601a"/>
            <stop offset="40%" stop-color="#e8d5a0"/>
            <stop offset="49%" stop-color="#e8d5a0"/>
            <stop offset="50%" stop-color="#c4601a"/>
            <stop offset="59%" stop-color="#c4601a"/>
            <stop offset="60%" stop-color="#e8d5a0"/>
            <stop offset="69%" stop-color="#e8d5a0"/>
            <stop offset="70%" stop-color="#c4601a"/>
            <stop offset="79%" stop-color="#c4601a"/>
            <stop offset="80%" stop-color="#e8d5a0"/>
            <stop offset="89%" stop-color="#e8d5a0"/>
            <stop offset="90%" stop-color="#c4601a"/>
          </radialGradient>
          <pattern id="seigaiha-header" x="0" y="0" width="100" height="60" patternUnits="userSpaceOnUse">
            <circle fill="url(#grad-header)" cx="0"   cy="27" r="57"/>
            <circle fill="url(#grad-header)" cx="100" cy="27" r="57"/>
            <circle fill="url(#grad-header)" cx="50"  cy="57" r="57"/>
            <circle fill="url(#grad-header)" cx="0"   cy="87" r="57"/>
            <circle fill="url(#grad-header)" cx="100" cy="87" r="57"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#seigaiha-header)" opacity="0.10"/>
      </svg>
    </div>
    <nav class="nav-inner">
      <a href="index.php" class="nav-brand">
        <img src="https://kome.be/images/LogoKomeOfficial.png" alt="Kome Dojo" class="nav-logo">
      </a>
      <button class="nav-burger" aria-label="Menu" aria-expanded="false" onclick="toggleMenu(this)">
        <span></span><span></span><span></span>
      </button>
      <ul class="nav-links">
        <li><a href="index.html" onclick="closeMenu()">Accueil</a></li>
        <li><a href="horaires.html" onclick="closeMenu()">En pratique</a></li>
        <li><a href="photos.php" onclick="closeMenu()">Photos</a></li>
        <li><a href="stages.php" onclick="closeMenu()">Stages</a></li>
        <li><a href="outside.php" onclick="closeMenu()">Au-del&agrave; du dojo</a></li>
        <li><a href="contact.php" class="nav-cta" onclick="closeMenu()">Contact</a></li>
      </ul>
    </nav>
    <script>
      function toggleMenu(btn) {
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', !expanded);
        btn.classList.toggle('open');
        document.querySelector('.nav-links').classList.toggle('open');
      }
      function closeMenu() {
        document.querySelector('.nav-burger').setAttribute('aria-expanded', 'false');
        document.querySelector('.nav-burger').classList.remove('open');
        document.querySelector('.nav-links').classList.remove('open');
      }
    </script>
  </header>

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
  <footer class="site-footer">
    <div class="bg-pattern" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
        <defs>
          <radialGradient id="grad-header" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
            <stop offset="9%"  stop-color="#e8d5a0"/>
            <stop offset="10%" stop-color="#c4601a"/>
            <stop offset="19%" stop-color="#c4601a"/>
            <stop offset="20%" stop-color="#e8d5a0"/>
            <stop offset="29%" stop-color="#e8d5a0"/>
            <stop offset="30%" stop-color="#c4601a"/>
            <stop offset="39%" stop-color="#c4601a"/>
            <stop offset="40%" stop-color="#e8d5a0"/>
            <stop offset="49%" stop-color="#e8d5a0"/>
            <stop offset="50%" stop-color="#c4601a"/>
            <stop offset="59%" stop-color="#c4601a"/>
            <stop offset="60%" stop-color="#e8d5a0"/>
            <stop offset="69%" stop-color="#e8d5a0"/>
            <stop offset="70%" stop-color="#c4601a"/>
            <stop offset="79%" stop-color="#c4601a"/>
            <stop offset="80%" stop-color="#e8d5a0"/>
            <stop offset="89%" stop-color="#e8d5a0"/>
            <stop offset="90%" stop-color="#c4601a"/>
          </radialGradient>
          <pattern id="seigaiha-header" x="0" y="0" width="100" height="60" patternUnits="userSpaceOnUse">
            <circle fill="url(#grad-header)" cx="0"   cy="27" r="57"/>
            <circle fill="url(#grad-header)" cx="100" cy="27" r="57"/>
            <circle fill="url(#grad-header)" cx="50"  cy="57" r="57"/>
            <circle fill="url(#grad-header)" cx="0"   cy="87" r="57"/>
            <circle fill="url(#grad-header)" cx="100" cy="87" r="57"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#seigaiha-header)" opacity="0.10"/>
      </svg>
    </div>
    <div class="footer-inner">
      <div class="footer-brand">
        <img src="https://kome.be/images/LogoKomeOfficial.png" alt="Kome Dojo" class="footer-logo">
      </div>
      <div class="footer-center">
        <p class="footer-name">Kome Dojo Neupr&eacute;</p>
        <p class="footer-copy">&copy; 2025 &mdash; Tous droits r&eacute;serv&eacute;s</p>
      </div>
      <div class="footer-right">
        <div class="footer-social">
          <a href="https://www.facebook.com/KomeDojo/" target="_blank" rel="noopener" class="social-link" aria-label="Facebook">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
              <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
            </svg>
          </a>
          <a href="https://www.instagram.com/komedojo/" target="_blank" rel="noopener" class="social-link" aria-label="Instagram">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
            </svg>
          </a>
        </div>
        <a href="http://www.aikido.be" target="_blank" rel="noopener" class="footer-afa">
          <img src="https://kome.be/images/MembreAfa.png" alt="Membre AFA" class="footer-afa-img">
        </a>
      </div>
    </div>
  </footer>

</body>
</html>
