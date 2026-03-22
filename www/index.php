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

      <!-- Aïkido + Neuville : au-dessus du layout kanji -->
      <p class="hero-aikido">Aïkido</p>
      <p class="hero-eyebrow">Neuville-en-Condroz</p>

      <!-- Kanji centrés face au titre uniquement -->
      <div class="hero-kanji-layout">

        <div class="hero-kanji-col" aria-hidden="true">
          <span class="hero-kanji-vertical">合気道</span>
        </div>

        <div class="hero-kanji-body">
          <h1 class="hero-title">
            L&rsquo;art<br>
            <em>de la paix</em>
          </h1>
          <p class="hero-body">
            Art martial japonais fondé sur l'harmonie plutôt que l'affrontement, 
            l'aïkido capte l'attaque de l'adversaire pour le neutraliser 
            par des projections ou des immobilisations — sans jamais chercher à le blesser.
          </p>
          <div class="hero-actions">
            <a href="contact.php" class="btn-main">Nous contacter</a>
            <a href="horaires.php" class="btn-ghost">Voir les horaires</a>
          </div>
        </div>

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
        <h2 class="card-title">La voie</h2>
        <p class="card-body">
          L'<a target="_blank" href="http://fr.wikipedia.org/wiki/A%C3%AFkido" class="card-link">aïkido</a> est un art martial sans compétition. 
          La progression de chaque élève est reconnue lors d'examens 
          évaluant la maîtrise technique acquise au fil de la pratique.
        </p>
      </div>

      <div class="info-card info-card--featured">
        <h2 class="card-title">Le dojo</h2>
        <p class="card-body">
          Hall de Neuville-en-Condroz<br>
          Avenue de la Vecqu&eacute;e, 18/B<br>
          4121 Neuville-en-Condroz
        </p>
        <a href="https://maps.google.com/?q=Avenue+de+la+Vecquée+18+Neuville-en-Condroz" target="_blank" rel="noopener" class="card-link">Voir sur la carte &rarr;</a>
      </div>

      <div class="info-card">
        <h2 class="card-title">Contact</h2>
        <p class="card-body">
          <a href="mailto:info@kome.be">info@kome.be</a><br>
          <a href="tel:003242870091">+32 (0)4 287 00 91</a>
        </p>
        <a href="horaires.php" class="card-link">Voir les horaires &rarr;</a>
      </div>

    </div>
  </section>

  <?php include("footer.php"); ?> 

</body>
</html>
