<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kome Dojo Neupré &mdash; Horaires &amp; Tarifs</title>
  <?php include("links.php"); ?> 

  <style>
    /* --- PAGE HORAIRES --- */
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

    /* --- GRILLE COURS --- */
    .cours-section {
      max-width: var(--max-w);
      margin: 0 auto;
      padding: 0 40px 100px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
    }

    .cours-card {
      background: var(--paper-warm);
      border: 1px solid var(--paper-rule);
      padding: 48px 40px;
      display: flex;
      flex-direction: column;
      gap: 36px;
    }

    .cours-card--featured {
      border-top: 3px solid var(--orange);
      padding-top: 45px;
    }

    .cours-header {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .cours-label {
      font-family: var(--font-display);
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.18em;
      color: var(--orange);
      text-transform: uppercase;
    }

    .cours-title {
      font-family: var(--font-display);
      font-size: 32px;
      font-weight: 500;
      color: var(--ink);
      line-height: 1.1;
    }

    .cours-subtitle {
      font-size: 13px;
      font-weight: 300;
      color: var(--ink-soft);
      margin-top: 4px;
    }

    /* Blocs info dans la carte */
    .cours-block {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .cours-block-title {
      font-family: var(--font-display);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: var(--ink-soft);
      padding-bottom: 8px;
      border-bottom: 1px solid var(--paper-rule);
    }

    /* Horaires */
    .horaire-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      gap: 12px;
    }

    .horaire-jour {
      font-size: 14px;
      font-weight: 400;
      color: var(--ink);
    }

    .horaire-heure {
      font-family: var(--font-display);
      font-size: 14px;
      font-weight: 500;
      color: var(--orange);
      white-space: nowrap;
    }

    /* Tarifs */
    .tarif-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      gap: 12px;
    }

    .tarif-label {
      font-size: 14px;
      font-weight: 300;
      color: var(--ink-mid);
    }

    .tarif-prix {
      font-family: var(--font-display);
      font-size: 14px;
      font-weight: 500;
      color: var(--ink);
      white-space: nowrap;
    }

    .tarif-annuel {
      margin-top: 4px;
      padding-top: 10px;
      border-top: 1px solid var(--paper-rule);
    }

    .tarif-annuel .tarif-prix {
      color: var(--orange);
      font-size: 16px;
    }

    .tarif-note {
      font-size: 12px;
      font-weight: 300;
      color: var(--ink-soft);
      margin-top: 8px;
      font-style: italic;
    }

    /* Professeur */
    .prof-name {
      font-family: var(--font-display);
      font-size: 18px;
      font-weight: 500;
      color: var(--ink);
    }

    .prof-grade {
      font-size: 13px;
      font-weight: 300;
      color: var(--ink-soft);
      margin-top: 2px;
    }

    /* Bannière essai gratuit */
    .essai-banner {
      max-width: var(--max-w);
      margin: 0 auto 60px;
      padding: 0 40px;
    }

    .essai-inner {
      background: var(--orange);
      padding: 5px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      position: relative;
      overflow: hidden;
    }

    .essai-inner .bg-pattern {
      position: absolute;
      inset: 0;
    }

    .essai-inner > *:not(.bg-pattern) {
      position: relative;
      z-index: 1;
    }

    .essai-text {
      font-family: var(--font-display);
      font-size: 20px;
      font-weight: 500;
      color: #fff;
    }

    .essai-text span {
      display: block;
      font-family: var(--font-body);
      font-size: 13px;
      font-weight: 300;
      color: rgba(255,255,255,0.75);
      margin-top: 4px;
    }

    .essai-cta {
      font-size: 13px;
      font-weight: 400;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #fff;
      border: 1px solid rgba(255,255,255,0.6);
      padding: 10px 22px;
      white-space: nowrap;
      transition: background 0.2s;
      flex-shrink: 0;
    }

    .essai-cta:hover {
      background: rgba(255,255,255,0.15);
    }

    /* Responsive */
    @media (max-width: 820px) {
      .cours-section {
        grid-template-columns: 1fr;
        gap: 24px;
        padding-bottom: 72px;
      }
      .essai-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 18px;
      }
    }

    @media (max-width: 580px) {
      .page-hero { padding-left: 20px; padding-right: 20px; }
      .cours-section { padding-left: 20px; padding-right: 20px; }
      .essai-banner { padding-left: 20px; padding-right: 20px; }
      .cours-card { padding: 32px 24px; }
    }
  </style>
</head>
<body>

  <!-- NAVIGATION -->
  <?php include("header.php"); ?> 


  <!-- EN-TÊTE DE PAGE -->
  <div class="page-hero">
    <p class="page-eyebrow">En pratique</p>
    <h1 class="page-title">Horaires &amp; <em>tarifs</em></h1>
  </div>

  <!-- SÉPARATEUR -->
  <div class="section-rule" style="margin-bottom: 48px;">
    <span class="rule-line"></span>
    <span class="rule-diamond"></span>
    <span class="rule-line"></span>
  </div>

  <!-- COURS -->
  <section class="cours-section">

    <!-- ENFANTS -->
    <div class="cours-card">

      <div class="cours-header">
        <span class="cours-label">01 &mdash; Enfants</span>
        <h2 class="cours-title">Cours<br><em style="font-style:italic;color:var(--orange)">enfants</em></h2>
        <p class="cours-subtitle">De 6 &agrave; 12 ans</p>
      </div>

      <div class="cours-block">
        <p class="cours-block-title">Horaires</p>
        <div class="horaire-row">
          <span class="horaire-jour">Mercredi</span>
          <span class="horaire-heure">14:00 &ndash; 15:00</span>
        </div>
        <div class="horaire-row">
          <span class="horaire-jour">Samedi</span>
          <span class="horaire-heure">10:00 &ndash; 11:00</span>
        </div>
      </div>

      <div class="cours-block">
        <p class="cours-block-title">Tarifs</p>
        <div class="tarif-row">
          <span class="tarif-label">1<sup>er</sup> trimestre</span>
          <span class="tarif-prix">60 &euro;</span>
        </div>
        <div class="tarif-row">
          <span class="tarif-label">2<sup>e</sup> trimestre</span>
          <span class="tarif-prix">50 &euro;</span>
        </div>
        <div class="tarif-row">
          <span class="tarif-label">3<sup>e</sup> trimestre</span>
          <span class="tarif-prix">50 &euro;</span>
        </div>
        <div class="tarif-row tarif-annuel">
          <span class="tarif-label">Ann&eacute;e compl&egrave;te</span>
          <span class="tarif-prix">150 &euro;</span>
        </div>
        <p class="tarif-note">Assurance &amp; cotisations incluses</p>
      </div>

      <div class="cours-block">
        <p class="cours-block-title">Professeur</p>
        <p class="prof-name">Michelle Gaspard</p>
        <p class="prof-grade">6<sup>e</sup> dan A&iuml;kika&iuml; Tokyo</p>
      </div>

    </div>

    <!-- ADULTES -->
    <div class="cours-card cours-card--featured">

      <div class="cours-header">
        <span class="cours-label">02 &mdash; Adultes</span>
        <h2 class="cours-title">Cours<br><em style="font-style:italic;color:var(--orange)">adultes</em></h2>
        <p class="cours-subtitle">D&egrave;s 12 ans</p>
      </div>

      <div class="cours-block">
        <p class="cours-block-title">Horaires</p>
        <div class="horaire-row">
          <span class="horaire-jour">Mardi</span>
          <span class="horaire-heure">18:30 &ndash; 20:00</span>
        </div>
        <div class="horaire-row">
          <span class="horaire-jour">Jeudi</span>
          <span class="horaire-heure">18:30 &ndash; 20:00</span>
        </div>
      </div>

      <div class="cours-block">
        <p class="cours-block-title">Tarifs</p>
        <div class="tarif-row">
          <span class="tarif-label">1<sup>er</sup> trimestre</span>
          <span class="tarif-prix">77 &euro;</span>
        </div>
        <div class="tarif-row">
          <span class="tarif-label">2<sup>e</sup> trimestre</span>
          <span class="tarif-prix">62 &euro;</span>
        </div>
        <div class="tarif-row">
          <span class="tarif-label">3<sup>e</sup> trimestre</span>
          <span class="tarif-prix">62 &euro;</span>
        </div>
        <div class="tarif-row tarif-annuel">
          <span class="tarif-label">Ann&eacute;e compl&egrave;te</span>
          <span class="tarif-prix">185 &euro;</span>
        </div>
        <p class="tarif-note">Assurance &amp; cotisations incluses &mdash; moins de 14 ans&nbsp;: 180&nbsp;&euro;</p>
      </div>

      <div class="cours-block">
        <p class="cours-block-title">Professeur</p>
        <p class="prof-name">Fabian Jacquet</p>
        <p class="prof-grade">5<sup>e</sup> dan A&iuml;kika&iuml; Tokyo</p>
      </div>

    </div>

  </section>

  <!-- BANNIÈRE ESSAI GRATUIT -->
  <div class="essai-banner">
    <div class="essai-inner">
      <div class="essai-text">
        3 cours d&rsquo;essai gratuits
        <span>Pour tous les nouveaux membres &mdash; enfants et adultes</span>
      </div>
      <a href="contact.php" class="essai-cta">Nous contacter &rarr;</a>
    </div>
  </div>

  <!-- FOOTER -->
  <?php include("footer.php"); ?> 


</body>
</html>
