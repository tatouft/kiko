<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Réservez votre initiation à l'aïkido au Kome Dojo de Neupré">
  <meta name="keywords" content="Aïkido, initiation, réservation, Neupré">
  <title>Kome Dojo Neupré &mdash; Réserver votre initiation</title>
  <?php include("links.php"); ?>
  <link rel="stylesheet" href="initiation.css">
  <!-- FriendlyCaptcha (privacy-first alternative) -->
  <script type="module" src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.min.js" async defer></script>
  <script nomodule src="https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@0.2.0/site.compat.min.js" async defer></script>
  <!--<script src="https://cdn.jsdelivr.net/gh/friendlycaptcha/friendly-challenge@latest/dist/friendly-challenge.min.js" defer></script>-->
</head>
<body>

  <!-- NAVIGATION -->
  <?php include("header.php"); ?>

  <!-- EN-TÊTE DE PAGE -->
  <div class="page-hero">
    <p class="hero-eyebrow">Rejoignez-nous</p>
    <h1 class="page-title">Réserver votre <em>initiation</em></h1>
  </div>

  <!-- SÉPARATEUR -->
  <div class="section-rule" style="margin-bottom: 48px;">
    <span class="rule-line"></span>
    <span class="rule-diamond"></span>
    <span class="rule-line"></span>
  </div>

  <!-- CONTENU FORMULAIRE -->
  <section class="initiation-section">

    <div class="initiation-intro">
      <h2>Bienvenue au Kome Dojo !</h2>
      <p>
        Vous êtes intéressé par l'aïkido ? Complétez ce formulaire pour réserver votre séance d'initiation gratuite.
        Nous vous recontacterons rapidement pour fixer une date qui vous convient.
      </p>
    </div>

    <form id="initiationForm" class="initiation-form" method="POST" action="initiation_handler.php">

      <div class="form-group">
        <label for="nom">Nom <span class="form-required">*</span></label>
        <input type="text" id="nom" name="nom" required placeholder="Votre nom" maxlength="100">
        <span class="form-error" id="nom-error"></span>
      </div>

      <div class="form-group">
        <label for="prenom">Prénom <span class="form-required">*</span></label>
        <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom" maxlength="100">
        <span class="form-error" id="prenom-error"></span>
      </div>

      <div class="form-group">
        <label for="age">Âge <span class="form-required">*</span></label>
        <select id="age" name="age" required>
          <option value="">-- Sélectionnez une option --</option>
          <option value="moins_13">Moins de 13 ans</option>
          <option value="plus_13">13 ans ou plus</option>
        </select>
        <span class="form-error" id="age-error"></span>
      </div>

      <div class="form-group">
        <label for="email">Email <span class="form-required">*</span></label>
        <input type="email" id="email" name="email" required placeholder="votre@email.com" maxlength="150">
        <span class="form-error" id="email-error"></span>
      </div>

      <div class="form-group">
        <label for="telephone">Numéro de téléphone <span class="form-required">*</span></label>
        <input type="tel" id="telephone" name="telephone" required placeholder="+32 (0)4 XXX XX XX" maxlength="20">
        <span class="form-error" id="telephone-error"></span>
      </div>

      <!-- FriendlyCaptcha widget -->
      <?php require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php'); ?>
      <div class="captcha-group">
        <label class="captcha-label">Vérification anti-bot <span class="form-required">*</span></label>
        <div class="frc-captcha" data-sitekey="<?php echo htmlspecialchars($friendly_site_key); ?>" data-lang="fr"></div>
        <p class="captcha-info">Veuillez compléter cette vérification pour confirmer que vous êtes un utilisateur réel.</p>
      </div>

      <!-- Input caché pour la solution FriendlyCaptcha -->
      <input type="hidden" name="frc-captcha-solution" id="frc-captcha-solution" value="">

      <button type="submit" class="btn-submit" id="submitBtn">Réserver mon initiation</button>

    </form>

  </section>

  <?php include("footer.php"); ?>

  <script>
    // Simplified client-side logic inspired by FriendlyCaptcha docs
    // - Validate input fields
    // - Check that FriendlyCaptcha produced a solution in the hidden input
    // If both pass, allow the normal form submission to proceed.

    document.getElementById('initiationForm').addEventListener('submit', function(e) {
      // Validate fields first
      if (!validateForm()) {
        e.preventDefault();
        return;
      }

      // FriendlyCaptcha injects a hidden input named 'frc-captcha-solution' when solved
      const solutionEl = document.querySelector('input[name="frc-captcha-response"]');
      const solution = solutionEl ? solutionEl.value.trim() : '';
      if (!solution) {
        e.preventDefault();
        alert('Veuillez compléter le captcha FriendlyCaptcha (le puzzle) avant de soumettre.');
        return;
      }

      // allow normal submission; server will verify the solution
    });

    function validateForm() {
      let isValid = true;
      document.querySelectorAll('.form-error').forEach(el => el.textContent = '');

      const nom = document.getElementById('nom').value.trim();
      if (!nom) { document.getElementById('nom-error').textContent = 'Le nom est requis'; isValid = false; }

      const prenom = document.getElementById('prenom').value.trim();
      if (!prenom) { document.getElementById('prenom-error').textContent = 'Le prénom est requis'; isValid = false; }

      const age = document.getElementById('age').value;
      if (!age) { document.getElementById('age-error').textContent = 'Veuillez sélectionner une option'; isValid = false; }

      const email = document.getElementById('email').value.trim();
      if (!email || !isValidEmail(email)) { document.getElementById('email-error').textContent = 'Email invalide'; isValid = false; }

      const telephone = document.getElementById('telephone').value.trim();
      if (!telephone) { document.getElementById('telephone-error').textContent = 'Le téléphone est requis'; isValid = false; }

      return isValid;
    }

    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }
  </script>

</body>
</html>



