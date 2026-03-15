<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kome Dojo Neupré &mdash; Aïkido</title>
  <?php include("links.php"); ?> 
  <link rel="stylesheet" href="index.css">
</head>
<body>


  <?php include("header.php"); ?> 

  <!-- HERO -->
  <section class="hero">

    <div class="hero-content">
      <p class="hero-kana" aria-hidden="true">合気道</p>
      <p class="hero-eyebrow">Art martial &middot; Neuville-en-Condroz</p>
      <h1 class="hero-title">
        L&rsquo;art<br>
        <em>de la paix</em>
      </h1>
      <p class="hero-body">
        L&rsquo;a&iuml;kido canalise l&rsquo;attaque de l&rsquo;adversaire afin de l&rsquo;annuler
        par projection ou immobilisation &mdash; sans comp&eacute;tition,
        seulement la progression de soi.
      </p>
      <div class="hero-actions">
        <a href="contact.php" class="btn-main">Nous contacter</a>
        <a href="horaires.php" class="btn-ghost">Voir les horaires</a>
      </div>
    </div>

  </section>

  <!-- SÉPARATEUR -->
  <div class="section-rule">
    <span class="rule-line"></span>
    <span class="rule-diamond"></span>
    <span class="rule-line"></span>
  </div>

  <!-- INFO CARDS -->
  <section class="info-section">
    <div class="info-grid">

      <div class="info-card">
        <span class="card-index">01</span>
        <h2 class="card-title">La voie</h2>
        <p class="card-body">
          L&rsquo;a&iuml;kido ne comporte pas de comp&eacute;tition &mdash; l&rsquo;attaque
          ne fait pas partie de l&rsquo;enseignement. Les grades sont
          d&eacute;cern&eacute;s lors d&rsquo;examens &eacute;valuant la progression de chaque &eacute;l&egrave;ve.
        </p>
      </div>

      <div class="info-card info-card--featured">
        <span class="card-index">02</span>
        <h2 class="card-title">Le dojo</h2>
        <p class="card-body">
          Hall de Neuville-en-Condroz<br>
          Avenue de la Vecqu&eacute;e, 18/B<br>
          4121 Neuville-en-Condroz
        </p>
        <a href="https://maps.google.com/?q=Avenue+de+la+Vecquée+18+Neuville-en-Condroz" target="_blank" rel="noopener" class="card-link">Voir sur la carte &rarr;</a>
      </div>

      <div class="info-card">
        <span class="card-index">03</span>
        <h2 class="card-title">Contact</h2>
        <p class="card-body">
          <a href="/cdn-cgi/l/email-protection#a4cdcac2cbe4cfcbc9c18ac6c1"><span class="__cf_email__" data-cfemail="c0a9aea6af80abafada5eea2a5">[email&#160;protected]</span></a><br>
          <a href="tel:003242870091">+32 (0)4 287 00 91</a>
        </p>
        <a href="horaires.php" class="card-link">Voir les horaires &rarr;</a>
      </div>

    </div>
  </section>

  <?php include("footer.php"); ?> 

</body>
</html>