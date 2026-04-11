<header class="navbar" id="mainNavbar">
  <div class="navbar__inner">
    <!-- ── Brand ── -->
    <a href="/" class="navbar__brand">
      <div class="navbar__logo-icon">
        <!-- Hotel / building icon -->
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 21V7l9-4 9 4v14H3zm2-2h4v-4H5v4zm0-6h4V9H5v4zm6 6h4v-4h-4v4zm0-6h4V9h-4v4zm6 6h4v-4h-4v4zm0-6h4V9h-4v4z"/>
        </svg>
      </div>
      <div class="navbar__brand-text">
        <span class="navbar__brand-name">Grandeur</span>
        <span class="navbar__brand-sub">Hotel &amp; Events</span>
      </div>
    </a>

    <!-- ── Desktop Nav Links ── -->
    <nav class="navbar__nav" aria-label="Main navigation">

      <div class="navbar__nav-item">
        <a href="home.php" class="navbar__nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <rect x="3" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/>
            <rect x="14" y="14" width="7" height="7" rx="1"/>
          </svg>
          Home
        </a>
      </div>

      <div class="navbar__nav-item navbar__nav-dropdown" id="reservationsMenu">
        <button class="navbar__nav-link navbar__dropdown-trigger {{ request()->routeIs('pages.bookings', 'pages.room-reservations') ? 'is-active' : '' }}" id="reservationsTrigger" aria-haspopup="true" aria-expanded="false">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>
          </svg>
          Reservations
          <svg class="navbar__dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </button>
        <div class="navbar__nav-submenu text-white" id="reservationsDropdown">
          <a href="reservations.php" class="navbar__nav-submenu-item {{ request()->routeIs('pages.bookings') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M19 21H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4l2-3h2l2 3h4a2 2 0 0 1 2 2z"/>
              <circle cx="12" cy="13" r="3"/>
            </svg>
            Reserve a Room
          </a>
          <a href="room-reservations.php" class="navbar__nav-submenu-item {{ request()->routeIs('pages.room-reservations') ? 'is-active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M19 21H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4l2-3h2l2 3h4a2 2 0 0 1 2 2z"/>
              <circle cx="12" cy="13" r="3"/>
            </svg>
            My Reservations
          </a>
          <a href="book-event-form.php" class="navbar__nav-submenu-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            Book Event (Form)
          </a>
          <a href="event-bookings.php" class="navbar__nav-submenu-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Browse Events
          </a>
          <a href="user-event-bookings.php" class="navbar__nav-submenu-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            My Event Bookings
          </a>
        </div>
      </div>

      <div class="navbar__nav-item">
        <a href="#" class="navbar__nav-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
          Rooms
        </a>
      </div>


    </nav>

    <!-- ── Right Actions ── -->
    <div class="navbar__actions">

      <!-- Search -->
      <button class="navbar__action-btn" aria-label="Search" title="Search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
          <circle cx="11" cy="11" r="8"/>
          <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </button>

      <!-- Notifications -->
      <button class="navbar__action-btn" aria-label="Notifications" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="navbar__badge"></span>
      </button>

      <div class="navbar__divider"></div>

      <!-- ── User Dropdown ── -->
      <div class="navbar__user" id="userMenu">
        <button
          class="navbar__user-trigger"
          id="userTrigger"
          aria-haspopup="true"
          aria-expanded="false"
          aria-controls="userDropdown"
        >
            <!-- check if user has a profile picture -->
            <?php
              $profile_pic = !empty($_SESSION['profile'])
                  ? '../../../storage/profiles/' . htmlspecialchars($_SESSION['profile'])
                  : '../../../public/assets/img/avatars/1.png';
            ?>
            <img 
                src="<?php echo $profile_pic; ?>" 
                alt="" 
                class="navbar__avatar"
            >

          <div class="navbar__user-info">
            <span class="navbar__user-name" id="navUserName">
                <?php echo ($_SESSION['full_name']); ?>
            </span>
          </div>
          <!-- Chevron -->
          <svg class="navbar__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </button>

        <!-- Dropdown -->
        <div class="navbar__dropdown" id="userDropdown" role="menu">

          <!-- Header -->
          <div class="dropdown__header">
              <img 
                    src="<?php echo $profile_pic; ?>" 
                    alt="" 
                    class="dropdown__avatar-lg"
                >
            <div class="dropdown__user-detail">
              <div class="dropdown__user-name" id="dropUserName"> 
                <?php echo ($_SESSION['full_name']);?> 
              </div>
              <div class="dropdown__user-email" id="dropUserEmail"> 
                <?php echo ($_SESSION['email']); ?> 
              </div>
            </div>
          </div>

          <!-- Group: Account -->
          <div class="dropdown__group">
            <div class="dropdown__label">Account</div>

            <a href="settings.php" class="dropdown__item" role="menuitem">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              <span class="text-white">My Profile</span>
            </a>
          </div>

          <div class="dropdown__group">
            <form method="POST" action="../../../../controllers/logout.php">
                <button type="submit" class="dropdown__item dropdown__item--danger" role="menuitem">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                  </svg>
                  <span>Sign Out</span>
                </button>
              </form>
          </div>
      </div><!-- /user -->

      <!-- ── Hamburger (mobile) ── -->
      <button class="navbar__hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

    </div><!-- /actions -->
  </div><!-- /inner -->
</header>

<nav class="navbar__mobile-panel" id="mobilePanel" aria-label="Mobile navigation">

  <a href="{{route('home')}}" class="navbar__mobile-link is-active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" width="16" height="16">
      <rect x="3" y="3" width="7" height="7" rx="1"/>
      <rect x="14" y="3" width="7" height="7" rx="1"/>
      <rect x="3" y="14" width="7" height="7" rx="1"/>
      <rect x="14" y="14" width="7" height="7" rx="1"/>
    </svg>
    Home
  </a>

  <a href="#" class="navbar__mobile-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" width="16" height="16">
      <path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>
    </svg>
    Reservations
  </a>

  <a href="#" class="navbar__mobile-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" width="16" height="16">
      <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
      <polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    Rooms
  </a>


  <a href="#" class="navbar__mobile-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" width="16" height="16">
      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
    </svg>
    Events
  </a>


  <div class="mobile-divider"></div>

  <a href="settings.php" class="navbar__mobile-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" width="16" height="16">
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
      <circle cx="12" cy="7" r="4"/>
    </svg>
    My Profile
  </a>

  <a href="#" class="navbar__mobile-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" width="16" height="16">
      <circle cx="12" cy="12" r="3"/>
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
    </svg>
    Settings
  </a>
</nav>


<script>
  (() => {
    // ── Elements
    const navbar     = document.getElementById('mainNavbar');
    const hamburger  = document.getElementById('hamburger');
    const mobilePanel = document.getElementById('mobilePanel');
    const userTrigger = document.getElementById('userTrigger');
    const userDropdown = document.getElementById('userDropdown');
    const reservationsTrigger = document.getElementById('reservationsTrigger');
    const reservationsDropdown = document.getElementById('reservationsDropdown');
    const reservationsMenu = document.getElementById('reservationsMenu');

    // ── Scroll shadow
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });

    // ── Hamburger / mobile panel toggle
    hamburger.addEventListener('click', () => {
      const isOpen = hamburger.classList.toggle('is-open');
      mobilePanel.classList.toggle('is-open', isOpen);
      hamburger.setAttribute('aria-expanded', isOpen);
      // close user dropdown if open
      closeUserDropdown();
    });

    // ── User dropdown toggle
    function openUserDropdown() {
      userDropdown.classList.add('is-open');
      userTrigger.setAttribute('aria-expanded', 'true');
    }
    function closeUserDropdown() {
      userDropdown.classList.remove('is-open');
      userTrigger.setAttribute('aria-expanded', 'false');
    }

    // ── Reservations dropdown toggle
    function openReservationsDropdown() {
      reservationsDropdown.classList.add('is-open');
      reservationsTrigger.setAttribute('aria-expanded', 'true');
    }
    function closeReservationsDropdown() {
      reservationsDropdown.classList.remove('is-open');
      reservationsTrigger.setAttribute('aria-expanded', 'false');
    }

    userTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = userDropdown.classList.contains('is-open');
      isOpen ? closeUserDropdown() : openUserDropdown();
      closeReservationsDropdown();
    });

    reservationsTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = reservationsDropdown.classList.contains('is-open');
      isOpen ? closeReservationsDropdown() : openReservationsDropdown();
      closeUserDropdown();
    });

    // ── Close dropdown on outside click
    document.addEventListener('click', (e) => {
      if (!document.getElementById('userMenu').contains(e.target)) {
        closeUserDropdown();
      }
      if (!reservationsMenu.contains(e.target)) {
        closeReservationsDropdown();
      }
    });

    // ── Keyboard: Escape closes both
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeUserDropdown();
        hamburger.classList.remove('is-open');
        mobilePanel.classList.remove('is-open');
        hamburger.setAttribute('aria-expanded', 'false');
      }
    });

    // ── Close mobile panel on nav link click
    mobilePanel.querySelectorAll('.navbar__mobile-link').forEach(link => {
      link.addEventListener('click', () => {
        hamburger.classList.remove('is-open');
        mobilePanel.classList.remove('is-open');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    });

    window.populateUser = function({ name, email, role, badge, initials, avatar }) {
      // Update text nodes
      document.getElementById('navUserName').textContent   = name   || '';
      document.getElementById('navUserRole').textContent   = role   || '';
      document.getElementById('dropUserName').textContent  = name   || '';
      document.getElementById('dropUserEmail').textContent = email  || '';
      document.getElementById('dropUserBadge').textContent = badge  || role || '';

      const buildAvatar = (el, size) => {
        if (avatar) {
          const img = document.createElement('img');
          img.src = avatar;
          img.alt = name;
          img.className = size === 'sm' ? 'navbar__avatar' : 'dropdown__avatar-lg';
          el.replaceWith(img);
        } else {
          el.textContent = initials || (name ? name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase() : '?');
        }
      };

      const small = document.getElementById('navAvatarSmall');
      const large = document.getElementById('dropAvatarLg');
      if (small) buildAvatar(small, 'sm');
      if (large) buildAvatar(large, 'lg');
    };

  })();
</script>