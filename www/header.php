  <!-- NAVIGATION -->
  <header class="site-header">
      <!-- PATTERN SEIGAIHA SVG EN FOND -->
    <div class="bg-pattern" aria-hidden="true">
      <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
        <defs>
          <radialGradient id="grad" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
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
          <pattern id="seigaiha" x="0" y="0" width="100" height="60" patternUnits="userSpaceOnUse">
            <circle fill="url(#grad)" cx="0"   cy="27" r="57"/>
            <circle fill="url(#grad)" cx="100" cy="27" r="57"/>
            <circle fill="url(#grad)" cx="50"  cy="57" r="57"/>
            <circle fill="url(#grad)" cx="0"   cy="87" r="57"/>
            <circle fill="url(#grad)" cx="100" cy="87" r="57"/>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#seigaiha)" opacity="0.10"/>
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
