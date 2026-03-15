  <!-- NAVIGATION -->
  <header class="site-header">
    <?php include("seigaiha.php"); ?> 
 
    <nav class="nav-inner">
      <a href="index.php" class="nav-brand">
        <img src="https://kome.be/images/LogoKomeOfficial.png" alt="Kome Dojo" class="nav-logo">
      </a>
      <button class="nav-burger" aria-label="Menu" aria-expanded="false" onclick="toggleMenu(this)">
        <span></span><span></span><span></span>
      </button>
      <ul class="nav-links">
        <li><a href="index.php" onclick="closeMenu()">Accueil</a></li>
        <li><a href="horaires.php" onclick="closeMenu()">En pratique</a></li>
        <li><a href="stages.php" onclick="closeMenu()">Stages</a></li>
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
