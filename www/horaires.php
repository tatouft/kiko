<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kome Dojo Neupré &mdash; Horaires &amp; Tarifs</title>
  <?php include("links.php"); ?>
  <link rel="stylesheet" href="horaires.css">
</head>
<body>

  <!-- NAVIGATION -->
  <?php include("header.php"); ?> 


  <!-- EN-TÊTE DE PAGE -->
  <div class="page-hero">
    <p class="hero-eyebrow">En pratique</p>
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
    <div class="cours-card cours-card--featured">

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
