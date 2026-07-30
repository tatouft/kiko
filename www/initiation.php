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
          <option value="moins_12">Moins de 12 ans</option>
          <option value="plus_12">12 ans ou plus</option>
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
        <span class="form-error" id="captcha-error"></span>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn" disabled>Réserver mon initiation</button>

    </form>

  </section>

  <?php include("footer.php"); ?>

  <script>
    // Validation en temps réel : chaque champ affiche son erreur dès qu'il est modifié
    // (ligne rouge + message sous le champ), et le bouton reste grisé tant que le
    // formulaire (y compris le captcha) n'est pas entièrement valide. Plus de popup.

    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    function isValidPhone(phone) {
      // Retire espaces, points, tirets et parenthèses pour ne garder que
      // le "+" éventuel et les chiffres, puis vérifie une longueur plausible.
      // Accepte aussi bien un format belge local (04xx xx xx xx, 9 chiffres)
      // qu'un format international (+32 4xx xx xx xx, +33 6 xx xx xx xx, etc.)
      const cleaned = phone.replace(/[\s().-]/g, '');
      return /^\+?[0-9]{8,15}$/.test(cleaned);
    }

    const fields = {
      nom: {
        el: document.getElementById('nom'),
        validate: v => v.trim().length >= 2 ? true : 'Le nom est requis (2 caractères min.)'
      },
      prenom: {
        el: document.getElementById('prenom'),
        validate: v => v.trim().length >= 2 ? true : 'Le prénom est requis (2 caractères min.)'
      },
      age: {
        el: document.getElementById('age'),
        validate: v => v ? true : 'Veuillez sélectionner une option'
      },
      email: {
        el: document.getElementById('email'),
        validate: v => isValidEmail(v.trim()) ? true : 'Email invalide'
      },
      telephone: {
        el: document.getElementById('telephone'),
        validate: v => isValidPhone(v.trim()) ? true : 'Numéro invalide (ex. 04XX XX XX XX ou +32 4XX XX XX XX)'
      }
    };

    let captchaValid = false;
    let formTouched = false;

    function validateField(name, forceDisplay) {
      const field = fields[name];
      const result = field.validate(field.el.value);
      const errorEl = document.getElementById(name + '-error');
      const valid = result === true;

      if (valid) {
        field.el.classList.remove('invalid');
        errorEl.textContent = '';
      } else if (forceDisplay || field.el.dataset.touched === 'true') {
        field.el.classList.add('invalid');
        errorEl.textContent = result;
      }
      return valid;
    }

    function isFormValid() {
      let allValid = true;
      Object.keys(fields).forEach(name => {
        if (fields[name].validate(fields[name].el.value) !== true) allValid = false;
      });
      return allValid && captchaValid;
    }

    function updateSubmitState() {
      document.getElementById('submitBtn').disabled = !isFormValid();
    }

    Object.keys(fields).forEach(name => {
      const el = fields[name].el;
      const eventType = (el.tagName === 'SELECT') ? 'change' : 'input';
      el.addEventListener(eventType, function() {
        el.dataset.touched = 'true';
        validateField(name);
        updateSubmitState();
      });
      el.addEventListener('blur', function() {
        el.dataset.touched = 'true';
        validateField(name);
        updateSubmitState();
      });
    });

    // Événements du widget FriendlyCaptcha (voir doc officielle) pour piloter
    // l'état du bouton sans avoir à lire l'input caché manuellement.
    const captchaWidget = document.querySelector('.frc-captcha');
    const captchaGroup = document.querySelector('.captcha-group');
    const captchaError = document.getElementById('captcha-error');

    captchaWidget.addEventListener('frc:widget.complete', function() {
      captchaValid = true;
      captchaError.textContent = '';
      captchaGroup.classList.add('completed');
      updateSubmitState();
    });

    captchaWidget.addEventListener('frc:widget.error', function(event) {
      captchaValid = false;
      captchaError.textContent = 'Erreur lors de la vérification anti-bot, veuillez réessayer.';
      captchaGroup.classList.remove('completed');
      updateSubmitState();
    });

    captchaWidget.addEventListener('frc:widget.expire', function() {
      captchaValid = false;
      captchaError.textContent = 'La vérification a expiré, veuillez la recompléter.';
      captchaGroup.classList.remove('completed');
      updateSubmitState();
    });

    // FriendlyCaptcha peut se résoudre en arrière-plan très rapidement (voire avant
    // que ce script n'ait eu le temps d'attacher les écouteurs ci-dessus). On vérifie
    // donc aussi, immédiatement et pendant quelques secondes après le chargement, si
    // l'input caché "frc-captcha-response" a déjà une valeur - au cas où l'événement
    // "frc:widget.complete" serait parti avant qu'on ne l'écoute.
    function checkCaptchaAlreadySolved() {
      if (captchaValid) return true;
      const responseInput = captchaWidget.querySelector('input[name="frc-captcha-response"]');
      if (responseInput && responseInput.value) {
        captchaValid = true;
        captchaError.textContent = '';
        captchaGroup.classList.add('completed');
        updateSubmitState();
        return true;
      }
      return false;
    }

    checkCaptchaAlreadySolved();
    const captchaPoll = setInterval(function() {
      if (checkCaptchaAlreadySolved()) {
        clearInterval(captchaPoll);
      }
    }, 300);
    setTimeout(function() { clearInterval(captchaPoll); }, 8000);

    document.getElementById('initiationForm').addEventListener('submit', function(e) {
      formTouched = true;
      let allValid = true;
      Object.keys(fields).forEach(name => {
        if (!validateField(name, true)) allValid = false;
      });
      if (!captchaValid) {
        captchaError.textContent = 'Veuillez compléter la vérification anti-bot avant de soumettre.';
        allValid = false;
      }
      if (!allValid) {
        e.preventDefault();
        updateSubmitState();
      }
    });
  </script>

</body>
</html>



