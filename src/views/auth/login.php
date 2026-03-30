<?php
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ynovNet - Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { 
      font-family: 'Roboto', sans-serif; 
      display: flex; height: 100vh; align-items: center; justify-content: center;
      background: linear-gradient(145deg, #16363c 0%, #2f2050 45%, #591b35 100%); 
      overflow: hidden; 
    }
    .auth-container {
      display: flex; flex-direction: row; width: 100%; max-width: 900px; height: 560px;
      background: linear-gradient(135deg, rgba(220,245,240,0.95) 0%, rgba(235,230,250,0.95) 100%);
      backdrop-filter: blur(12px); border-radius: 20px; box-shadow: 0 24px 60px rgba(0,0,0,0.3);
      overflow: hidden; border: 1px solid rgba(255,255,255,0.4);
    }
    .input-field {
      width: 100%; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 50px; padding: 14px 22px; 
      font-family: 'Roboto', sans-serif; font-size: 14.5px; color: #111827; 
      background: rgba(255,255,255,0.7); outline: none; transition: border-color .25s; margin-bottom: 16px;
    }
    .input-field:focus { border-color: #0d9488; background: #fff; }
    .btn-solid {
      width: 100%; color: #fff; border: none; border-radius: 50px; 
      background: linear-gradient(135deg, #f43f5e 0%, #0ea5e9 100%);
      padding: 14px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; 
      cursor: pointer; margin-bottom: 16px; transition: opacity .2s;
    }
    .btn-solid:hover { opacity: 0.9; }
    .btn-outline {
      display: block; text-align: center; width: 100%; background: #fff; color: #0d9488; 
      border: 1.5px solid #0d9488; border-radius: 50px; padding: 13px; 
      font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; 
      cursor: pointer; text-decoration: none; margin-bottom: 30px; transition: background .2s;
    }
    .btn-outline:hover { background: #f0fdfa; }
    .badge-api {
      display: inline-flex; font-family: 'SFMono-Regular', monospace; font-size: 11px; 
      color: #fff; background: #4b5563; border-radius: 4px; padding: 4px 10px;
    }
  </style>
</head>
<body>

  <div class="auth-container">
    <!-- PANNEAU GAUCHE -->
    <div style="flex:4; background:linear-gradient(145deg, rgba(42,90,87,0.4) 0%, rgba(60,46,112,0.4) 50%, rgba(110,36,63,0.4) 100%); display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; color:white; position:relative; border-right: 1px solid rgba(0,0,0,0.05);">
      
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; position:relative; z-index:10;">
        <svg viewBox="0 0 100 100" width="46" height="46" style="flex-shrink:0">
          <defs>
            <linearGradient id="pg_arr" x1="0%" y1="100%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#dce175"/>
              <stop offset="100%" stop-color="#6bcfa8"/>
            </linearGradient>
            <linearGradient id="pg_lin" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#d1addc"/>
              <stop offset="100%" stop-color="#9daee5"/>
            </linearGradient>
          </defs>
          <line x1="28" y1="28" x2="72" y2="72" stroke="url(#pg_lin)" stroke-width="11" stroke-linecap="round"/>
          <line x1="26" y1="74" x2="72" y2="28" stroke="url(#pg_arr)" stroke-width="11" stroke-linecap="round"/>
          <polygon points="61,24 76,23 75,38" fill="#6bcfa8"/>
        </svg>
        <span style="font-family:'Montserrat', sans-serif; font-weight:800; font-size:42px; letter-spacing:-.03em; color:#111827;">ynovNet</span>
      </div>

      <p style="text-align:center; font-size:18px; font-weight:500; line-height:1.4; color:#374151; position:relative; z-index:10;">
        Le réseau social du campus<br>Paris Ynov
      </p>
    </div>

    <!-- PANNEAU DROIT (Formulaire) -->
    <div style="flex:5; display:flex; align-items:center; justify-content:center; padding:40px; background: rgba(255,255,255,0.6);">
      
      <div style="width:100%; max-width:340px; display:flex; flex-direction:column; align-items:center;">
        
        <h1 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:28px; color:#111827; margin-bottom:44px;">
          Connexion
        </h1>

        <form action="<?= htmlspecialchars($basePath . '/login', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="width:100%;">
          
          <input type="email" name="email" class="input-field" placeholder="prenom.nom@ynov.com" required>
          <input type="password" name="password" class="input-field" placeholder="••••••••" required>

          <div style="text-align:right; margin-bottom:28px; padding-right:10px;">
            <a href="#" style="font-size:12.5px; color:#6b7280; font-weight:500; text-decoration:none; transition:color .2s;" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#6b7280'">Mot de passe oublié ?</a>
          </div>

          <button type="submit" class="btn-solid">
            Se connecter
          </button>

          <a href="<?= htmlspecialchars($basePath . '/register', ENT_QUOTES, 'UTF-8') ?>" class="btn-outline">
            Inscription
          </a>

          <div style="text-align:center;">
            <span class="badge-api">POST /auth/login — bcrypt.compare()</span>
          </div>

        </form>
      </div>

    </div>
  </div>

</body>
</html>
