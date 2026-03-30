<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ynovNet — Inscription</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    a { text-decoration: none; color: inherit; }

    body {
      font-family: 'Roboto', sans-serif;
      -webkit-font-smoothing: antialiased;
    }

    .page {
      display: flex;
      min-height: 100vh;
    }

    /* Panneau GAUCHE : même gradient que connexion */
    .panel-left {
      width: 42%;
      background: linear-gradient(150deg, #0d9488 0%, #134e4a 25%, #4c1d95 60%, #7b2d42 85%, #9f1239 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 36px;
      position: relative;
      overflow: hidden;
    }
    .panel-left::before {
      content: '';
      position: absolute;
      top: -30%; left: -20%;
      width: 80%; height: 70%;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
    }
    .logo-wrap { display:flex;align-items:center;gap:12px;margin-bottom:20px;position:relative; }
    .logo-text {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      font-size: 2.4rem;
      color: #fff;
      letter-spacing: -.02em;
    }
    .tagline {
      font-family: 'Roboto', sans-serif;
      font-size: 15px;
      font-weight: 400;
      color: rgba(255,255,255,.82);
      text-align: center;
      line-height: 1.55;
      max-width: 210px;
      position: relative;
    }

    /* Panneau DROIT : formulaire */
    .panel-right {
      width: 58%;
      background: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 48px 52px;
    }
    .form-title {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      font-size: 26px;
      color: #111827;
      margin-bottom: 28px;
      text-align: center;
    }
    .form { width: 100%; max-width: 320px; }
    .field { margin-bottom: 14px; }
    .field input {
      width: 100%;
      border: 1.5px solid #e5e7eb;
      border-radius: 99px;
      padding: 12px 20px;
      font-family: 'Roboto', sans-serif;
      font-size: 14px;
      color: #374151;
      background: #f9fafb;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .field input:focus {
      border-color: #0d9488;
      box-shadow: 0 0 0 3px rgba(13,148,136,.12);
      background: #fff;
    }
    .field input::placeholder { color: #9ca3af; }

    .btn-primary {
      width: 100%;
      padding: 13px;
      border: none;
      border-radius: 99px;
      background: #134e4a;
      color: #fff;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 14.5px;
      cursor: pointer;
      transition: background .2s, transform .1s;
      margin-bottom: 12px;
    }
    .btn-primary:hover { background: #0d9488; }
    .btn-primary:active { transform: scale(.98); }

    .btn-secondary {
      width: 100%;
      padding: 12px;
      border: 2px solid #0d9488;
      border-radius: 99px;
      background: transparent;
      color: #134e4a;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 14.5px;
      cursor: pointer;
      transition: background .2s;
    }
    .btn-secondary:hover { background: rgba(13,148,136,.06); }

    .badge-api {
      display: inline-block;
      margin-top: 22px;
      font-family: 'SFMono-Regular', 'Consolas', monospace;
      font-size: 11px;
      color: #c2410c;
      background: #fff7ed;
      border: 1px solid #fdba74;
      border-radius: 4px;
      padding: 3px 10px;
    }
  </style>
</head>
<body>
<div class="page">

  <!-- Panneau gauche : branding -->
  <div class="panel-left">
    <div class="logo-wrap">
      <svg viewBox="0 0 100 100" width="54" height="54" style="flex-shrink:0">
        <defs>
          <linearGradient id="gl1" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%"   stop-color="#fde68a"/>
            <stop offset="100%" stop-color="#6ee7b7"/>
          </linearGradient>
          <linearGradient id="gl2" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%"   stop-color="#f9a8d4"/>
            <stop offset="100%" stop-color="#c4b5fd"/>
          </linearGradient>
        </defs>
        <path d="M22 78 Q50 50 78 22" stroke="url(#gl1)" stroke-width="13" stroke-linecap="round" fill="none"/>
        <path d="M22 22 Q50 50 78 78" stroke="url(#gl2)" stroke-width="13" stroke-linecap="round" fill="none"/>
        <polygon points="70,15 84,29 86,12" fill="#6ee7b7"/>
      </svg>
      <span class="logo-text">ynovNet</span>
    </div>
    <p class="tagline">Rejoins la communauté du campus Paris Ynov ✨</p>
  </div>

  <!-- Panneau droit : formulaire d'inscription -->
  <div class="panel-right">
    <h1 class="form-title">Inscription</h1>

    <form class="form" action="/register" method="POST">
      <div class="field">
        <input type="text" name="name" placeholder="Prénom et Nom" required>
      </div>
      <div class="field">
        <input type="email" name="email" placeholder="prenom.nom@ynov.com" required>
      </div>
      <div class="field">
        <input type="text" name="filiere" placeholder="Filière (ex: B2 Informatique)">
      </div>
      <div class="field" style="margin-bottom:20px;">
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn-primary">Créer mon compte</button>
      <a href="/login">
        <button type="button" class="btn-secondary">Déjà inscrit ? Se connecter</button>
      </a>

      <div style="text-align:center;">
        <span class="badge-api">POST /auth/register — password_hash()</span>
      </div>
    </form>
  </div>

</div><!-- /page -->
</body>
</html>
