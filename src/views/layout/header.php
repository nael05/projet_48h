<?php
$activeTab = $activeTab ?? 'feed';
$activeNav = $activeNav ?? 'feed';
$mockUrl   = $mockUrl   ?? 'localhost:3000/feed';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ynovNet — Le réseau du campus Paris Ynov</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    /* ── Reset ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    a { text-decoration: none; color: inherit; }

    /* ── Tokens ── */
    :root {
      --teal:        #0d9488;
      --teal-dark:   #134e4a;
      --teal-mid:    #0f766e;
      --magenta:     #be185d;
      --magenta-dk:  #9d174d;
      --orange:      #f97316;
      --orange-bg:   #fff7ed;
      --orange-bd:   #fdba74;
      --gray-50:     #f9fafb;
      --gray-100:    #f4f4f4;
      --gray-200:    #e5e7eb;
      --gray-300:    #d1d5db;
      --gray-400:    #9ca3af;
      --gray-500:    #6b7280;
      --gray-700:    #374151;
      --gray-900:    #111827;
    }

    /* ── Page ── */
    body {
      background: var(--gray-100);
      min-height: 100vh;
      font-family: 'Roboto', sans-serif;
      color: var(--gray-900);
      -webkit-font-smoothing: antialiased;
      display: flex;
      flex-direction: column;
    }

    /* Contenu applicatif */
    .app {
      background: var(--gray-100);
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* ── Barre 1 : onglets applicatifs (fond blanc) ── */
    .app-tabs {
      background: #fff;
      border-bottom: 1.5px solid var(--gray-200);
      display: flex;
      padding: 0 24px;
    }
    .app-tab {
      display: inline-flex;
      align-items: center;
      padding: 13px 16px;
      font-family: 'Montserrat', sans-serif;
      font-size: 13px;
      font-weight: 600;
      color: var(--gray-500);
      border-bottom: 2.5px solid transparent;
      transition: color .2s, border-color .2s;
      cursor: pointer;
    }
    .app-tab:hover { color: var(--teal); }
    .app-tab--active { color: var(--teal-dark); border-bottom-color: var(--teal); }

    /* ── Barre 2 : nav applicative (fond teal sombre) ── */
    .app-nav {
      background: var(--teal-dark);
      display: flex;
      align-items: center;
      padding: 0 28px;
      height: 62px;
      gap: 20px;
    }
    .nav-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-right: auto;
    }
    .nav-logo__text {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      font-size: 22px;
      color: #fff;
      letter-spacing: -.02em;
    }
    .nav-links {
      display: flex;
      gap: 4px;
    }
    .nav-link {
      font-family: 'Montserrat', sans-serif;
      font-size: 13.5px;
      font-weight: 600;
      color: rgba(255,255,255,.78);
      padding: 6px 14px;
      border-radius: 5px;
      transition: background .15s, color .15s;
    }
    .nav-link:hover, .nav-link--active {
      background: rgba(255,255,255,.13);
      color: #fff;
    }
    .nav-bell {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(249,115,22,.22);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background .2s;
      position: relative;
    }
    .nav-bell:hover { background: rgba(249,115,22,.35); }
    .nav-bell svg { color: var(--orange); fill: var(--orange); width: 18px; height: 18px; }
    .nav-bell__dot {
      position: absolute;
      top: 4px; right: 4px;
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--orange);
      border: 2px solid var(--teal-dark);
    }
    .nav-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--gray-300), var(--gray-400));
      border: 2px solid rgba(255,255,255,.25);
    }

    /* ── Utilitaires ── */
    .badge-api {
      display: inline-flex;
      align-items: center;
      font-family: 'SFMono-Regular', 'Consolas', monospace;
      font-size: 11px;
      color: #c2410c;
      background: var(--orange-bg);
      border: 1px solid var(--orange-bd);
      border-radius: 4px;
      padding: 3px 10px;
    }
    .section-title {
      font-family: 'Montserrat', sans-serif;
      font-size: 15px;
      font-weight: 700;
      color: var(--gray-900);
    }
    .section-sep {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 14px;
    }
    .section-sep .line {
      flex: 1;
      height: 1px;
      background: var(--gray-200);
    }
    .card {
      background: #fff;
      border: 1px solid var(--gray-200);
      border-radius: 10px;
      box-shadow: 0 1px 6px rgba(0,0,0,.06);
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      font-size: 13px;
      border-radius: 6px;
      padding: 8px 16px;
      cursor: pointer;
      transition: all .2s;
      border: none;
    }
    .btn-outline-sm {
      background: #fff;
      border: 1.5px solid var(--gray-300);
      color: var(--gray-700);
      font-family: 'Montserrat', sans-serif;
      font-size: 12.5px;
      font-weight: 600;
      border-radius: 6px;
      padding: 7px 14px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: border-color .15s, background .15s;
    }
    .btn-outline-sm:hover { border-color: var(--teal); background: #f0fdfb; }

    .btn-magenta {
      background: linear-gradient(90deg, var(--magenta), var(--magenta-dk));
      color: #fff;
      border: none;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 13.5px;
      border-radius: 7px;
      padding: 9px 22px;
      cursor: pointer;
      box-shadow: 0 2px 10px rgba(190,24,93,.28);
      transition: opacity .2s, transform .1s;
    }
    .btn-magenta:hover { opacity: .9; }
    .btn-teal {
      background: var(--teal-dark);
      color: #fff;
      border: none;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 13px;
      border-radius: 7px;
      padding: 9px 20px;
      cursor: pointer;
      transition: background .2s;
    }
    .btn-teal:hover { background: var(--teal-mid); }
    .btn-teal-outline {
      background: transparent;
      color: var(--teal-dark);
      border: 1.5px solid var(--teal);
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      font-size: 12.5px;
      border-radius: 6px;
      padding: 6px 14px;
      cursor: pointer;
      transition: background .15s;
    }
    .btn-teal-outline:hover { background: rgba(13,148,136,.07); }

    /* ── Skelette de chargement (placeholders) ── */
    .skel { background: var(--gray-200); border-radius: 4px; display: block; }
    .skel--circle { border-radius: 50%; }

    /* ── Barres de progression News ── */
    .news-progress {
      height: 4px;
      background: var(--teal);
      border-radius: 99px;
      margin: 8px 0 6px;
    }
    </style>
</head>
<body>
  <div class="app">

    <!-- ── Barre 1 : Onglets applicatifs blancs ── -->
    <div class="app-tabs">
      <a href="/feed"     class="app-tab <?= $activeNav==='feed'     ? 'app-tab--active':'' ?>">[ Fil d'actualité ]</a>
      <a href="/profile"  class="app-tab <?= $activeNav==='profile'  ? 'app-tab--active':'' ?>">[ Profil ]</a>
      <a href="/messages" class="app-tab <?= $activeNav==='messages' ? 'app-tab--active':'' ?>">[ Messagerie ]</a>
      <a href="/login"    class="app-tab <?= $activeNav==='login'    ? 'app-tab--active':'' ?>">[ Connexion ]</a>
    </div>

    <!-- ── Barre 2 : Nav teal sombre ── -->
    <div class="app-nav">
      <div class="nav-logo">
        <svg viewBox="0 0 100 100" width="38" height="38" style="flex-shrink:0">
          <defs>
            <linearGradient id="g1" x1="0%" y1="100%" x2="100%" y2="0%">
              <stop offset="0%"   stop-color="#f97316"/>
              <stop offset="100%" stop-color="#0d9488"/>
            </linearGradient>
            <linearGradient id="g2" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%"   stop-color="#7c3aed"/>
              <stop offset="100%" stop-color="#be185d"/>
            </linearGradient>
          </defs>
          <path d="M22 78 Q50 50 78 22" stroke="url(#g1)" stroke-width="13" stroke-linecap="round" fill="none"/>
          <path d="M22 22 Q50 50 78 78" stroke="url(#g2)" stroke-width="13" stroke-linecap="round" fill="none"/>
          <polygon points="70,15 84,29 86,12" fill="#0d9488"/>
        </svg>
        <span class="nav-logo__text">ynovNet</span>
      </div>

      <nav class="nav-links">
        <a href="/feed"     class="nav-link <?= $activeNav==='feed'     ? 'nav-link--active':'' ?>">Fil</a>
        <a href="/profile"  class="nav-link <?= $activeNav==='profile'  ? 'nav-link--active':'' ?>">Profil</a>
        <a href="/messages" class="nav-link <?= $activeNav==='messages' ? 'nav-link--active':'' ?>">Messages</a>
        <a href="#"         class="nav-link">Ymatch</a>
      </nav>

      <div class="nav-bell" title="Notifications">
        <svg viewBox="0 0 24 24"><path d="M12 22a2 2 0 002-2H10a2 2 0 002 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        <span class="nav-bell__dot"></span>
      </div>
      <div class="nav-avatar"></div>
    </div>
    <!-- /header -->
