<?php
$activeNav = $activeNav ?? 'feed';
$mockUrl = $mockUrl ?? 'localhost:3000/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ynovNet</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    a { text-decoration: none; color: inherit; }

    :root {
      --dark:     #111827;
      --gray-100: #f4f4f4;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-400: #9ca3af;
      --gray-500: #6b7280;
      --gray-700: #374151;
      --gray-900: #111827;
      --white:    #ffffff;
      
      /* Nouvelles couleurs DA Dégradée */
      --bg-gradient: linear-gradient(145deg, #16363c 0%, #2f2050 45%, #591b35 100%);
      --container-gradient: linear-gradient(135deg, rgba(220,245,240,0.92) 0%, rgba(235,230,250,0.92) 100%);
      --btn-gradient: linear-gradient(135deg, #f43f5e 0%, #0ea5e9 100%); /* Rose corail vers bleu/teal */
      --teal-solid: #0d9488;
    }

    body {
      margin: 0; padding: 0;
      background: var(--bg-gradient);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: 'Roboto', sans-serif;
      -webkit-font-smoothing: antialiased;
      color: var(--white);
    }

    /* ════════════════════════════
       HEADER UNIQUE YNOVNET
    ════════════════════════════ */
    .header {
      background: #151b24; /* Bleu tech sombre */
      display: flex;
      align-items: center;
      padding: 0 28px;
      height: 60px;
      gap: 0;
      box-shadow: 0 2px 4px rgba(0,0,0,.15);
    }

    /* Logo */
    .header__logo {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      font-size: 22px;
      color: #ffffff; /* Titre en blanc */
      letter-spacing: -.02em;
      margin-right: 32px;
      display: flex;
      align-items: center;
    }

    /* Nav secondaire */
    .header__nav {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-left: auto;
    }
    .header__nav-link {
      font-family: 'Montserrat', sans-serif;
      font-size: 13.5px;
      font-weight: 500;
      color: rgba(255,255,255,.7);
      transition: color .15s;
    }
    .header__nav-link:hover, .header__nav-link.active {
      color: #fff;
    }

    /* Cloche notifications */
    .header__bell {
      width: 24px; height: 24px;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; position: relative;
      margin-left: 14px;
    }
    .header__bell svg { width: 18px; height: 18px; fill: var(--orange); }
    .header__bell__dot {
      position: absolute; top: 0px; right: 0px;
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--orange); border: 2px solid #151b24;
    }

    /* Avatar cercle placeholder */
    .header__avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: var(--gray-400);
      margin-left: 20px;
      cursor: pointer;
    }

    /* ════════════════════════
       APP & UTILITAIRES
    ════════════════════════ */
    .app { 
      display: flex; flex-direction: column; flex: 1; 
      /* Le background general de l'app est géré par les pages HTML elles-mêmes via les conteneurs */
    }
    
    .badge-api {
      display: inline-flex;
      font-family: 'SFMono-Regular', monospace;
      font-size: 11px;
      color: #ffffff;
      background: #4b5563; /* Gris moyen pour ne pas perturber la DA */
      border-radius: 4px;
      padding: 4px 10px;
    }
    .card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 8px;
    }
    .btn-dark {
      background: var(--btn-gradient); color: var(--white);
      border: none;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700; font-size: 13.5px;
      border-radius: 6px; padding: 10px 20px;
      cursor: pointer; transition: opacity .15s;
    }
    .btn-dark:hover { opacity: 0.9; }
    
    .btn-secondary {
      background: var(--teal-solid); color: var(--white);
      border: none;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700; font-size: 13.5px;
      border-radius: 6px; padding: 10px 20px;
      cursor: pointer; transition: background .15s;
    }
    .btn-secondary:hover { background: #0c8276; }

    .btn-outline {
      background: var(--white); color: var(--gray-700);
      border: 1.5px solid var(--gray-300);
      font-family: 'Montserrat', sans-serif;
      font-weight: 600; font-size: 12.5px;
      border-radius: 6px; padding: 7px 14px;
      cursor: pointer;
      display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-outline:hover { border-color: var(--dark); background: var(--gray-100); }
    .skel { background: var(--gray-200); border-radius: 4px; display: block; }

  </style>
</head>
<body>

  <!-- HEADER UNIQUE -->
  <header class="header">
    <div class="header__logo">ynovNet</div>

    <nav class="header__nav">
      <a href="/feed"     class="header__nav-link <?= $activeNav==='feed'     ? 'active':'' ?>">Fil</a>
      <a href="/profile"  class="header__nav-link <?= $activeNav==='profile'  ? 'active':'' ?>">Profil</a>
      <a href="/messages" class="header__nav-link <?= $activeNav==='messages' ? 'active':'' ?>">Messages</a>
      <a href="https://ymatch.ynov.com/login" target="_blank" class="header__nav-link">Ymatch</a>
    </nav>

    <div class="header__bell">
      <svg viewBox="0 0 24 24"><path d="M12 22a2 2 0 002-2H10a2 2 0 002 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
      <span class="header__bell__dot"></span>
    </div>

    <div class="header__avatar"></div>
  </header>

  <div class="app">
