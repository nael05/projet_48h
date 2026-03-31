<?php
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ynovNet - Inscription</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { 
      font-family: 'Roboto', sans-serif; 
      display: flex; min-height: 100vh; align-items: center; justify-content: center;
      background: linear-gradient(145deg, #16363c 0%, #2f2050 45%, #591b35 100%); 
      overflow: auto;
      padding: 20px;
    }
    .auth-container {
      display: flex; flex-direction: row; width: 100%; max-width: 940px; min-height: 640px;
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
      cursor: pointer; text-decoration: none; margin-bottom: 24px; transition: background .2s;
    }
    .btn-outline:hover { background: #f0fdfa; }
    .badge-api {
      display: inline-flex; font-family: 'SFMono-Regular', monospace; font-size: 11px; 
      color: #fff; background: #4b5563; border-radius: 4px; padding: 4px 10px;
    }

    /* Responsive adjustment for auth pages */
    @media (max-width: 768px) {
      .auth-container {
        flex-direction: column;
        height: auto;
        min-height: auto;
        max-width: 440px;
      }
      body {
        height: auto;
        padding: 40px 20px;
        overflow: auto;
      }
      .auth-container > div:first-child {
        padding: 40px 20px;
        border-right: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
      }
    }
  </style>
</head>
<body>

  <div class="auth-container">
    <!-- PANNEAU GAUCHE -->
    <div style="flex:4; background:linear-gradient(145deg, rgba(42,90,87,0.4) 0%, rgba(60,46,112,0.4) 50%, rgba(110,36,63,0.4) 100%); display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; color:white; position:relative; border-right: 1px solid rgba(0,0,0,0.05);">
      
      <div style="display:flex; align-items:center; gap:0px; margin-bottom:24px; position:relative; z-index:10; font-family:'Montserrat', sans-serif; font-weight:800; font-size:48px; letter-spacing:-.03em; color:#111827;">
        <svg viewBox="0 0 40 46" width="38" height="44" style="flex-shrink:0; margin-right:4px;">
          <polygon points="30,0 40,0 14,46 4,46" fill="#d94c63" />
          <polygon points="0,0 10,0 18.5,15 8.5,15" fill="#36b3a0" />
        </svg>
        <span>novnet</span>
      </div>

      <p style="text-align:center; font-size:18px; font-weight:500; line-height:1.4; color:#374151; position:relative; z-index:10;">
        Le réseau social du campus<br>Paris Ynov
      </p>
    </div>

    <!-- PANNEAU DROIT (Formulaire) -->
    <div style="flex:5; display:flex; align-items:center; justify-content:center; padding:40px; background: rgba(255,255,255,0.6); overflow-y:auto;">
      
      <div style="width:100%; max-width:340px; display:flex; flex-direction:column; align-items:center;">
        
        <h1 style="font-family:'Montserrat',sans-serif; font-weight:800; font-size:26px; color:#111827; margin-bottom:34px;">
          Inscription
        </h1>

        <?php
          $oldName = (string) ($oldInput['name'] ?? '');
          $oldEmail = (string) ($oldInput['email'] ?? '');
          $oldFiliere = (string) ($oldInput['filiere'] ?? '');
          $oldCampus = (string) ($oldInput['campus'] ?? '');
          $oldPromotion = (string) ($oldInput['promotion'] ?? '');

          $filieres = [
            'Informatique',
            'Cybersécurité',
            'IA & Data',
            '3D, Animation & Jeux Vidéo',
            'Création & Design',
            'Marketing & Communication',
            'Audiovisuel',
            "Architecture d'Intérieur",
            'Bâtiment Numérique',
            'Digital & IA',
          ];
          $campuses = [
            'Aix-en-Provence',
            'Bordeaux',
            'Casablanca',
            'Lille',
            'Lyon',
            'Montpellier',
            'Nantes',
            'Nice Sophia Antipolis',
            'Paris Est',
            'Paris Ouest',
            'Rennes',
            'Rouen',
            'Strasbourg',
            'Toulouse',
            'Ynov Connect',
          ];
          $promotions = ['B1', 'B2', 'B3', 'M1', 'M2'];
        ?>

        <?php if (!empty($errorMessage)): ?>
        <div style="width:100%; margin-bottom:14px; padding:10px 12px; background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:10px; font-size:12.5px;">
          <?= htmlspecialchars((string) $errorMessage, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($basePath . '/register', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="width:100%;">
          
          <input type="text" name="name" class="input-field" placeholder="Prénom et Nom" value="<?= htmlspecialchars($oldName, ENT_QUOTES, 'UTF-8') ?>" required>
          <input type="email" name="email" class="input-field" placeholder="prenom.nom@ynov.com" value="<?= htmlspecialchars($oldEmail, ENT_QUOTES, 'UTF-8') ?>" required>

          <select name="filiere" class="input-field" required>
            <option value="">Sélectionner une filière</option>
            <?php foreach ($filieres as $filiere): ?>
            <option value="<?= htmlspecialchars($filiere, ENT_QUOTES, 'UTF-8') ?>" <?= $oldFiliere === $filiere ? 'selected' : '' ?>>
              <?= htmlspecialchars($filiere, ENT_QUOTES, 'UTF-8') ?>
            </option>
            <?php endforeach; ?>
          </select>

          <select name="campus" class="input-field" required>
            <option value="">Sélectionner un campus</option>
            <?php foreach ($campuses as $campus): ?>
            <option value="<?= htmlspecialchars($campus, ENT_QUOTES, 'UTF-8') ?>" <?= $oldCampus === $campus ? 'selected' : '' ?>>
              <?= htmlspecialchars($campus, ENT_QUOTES, 'UTF-8') ?>
            </option>
            <?php endforeach; ?>
          </select>

          <select name="promotion" class="input-field" required>
            <option value="">Sélectionner une promotion</option>
            <?php foreach ($promotions as $promotion): ?>
            <option value="<?= htmlspecialchars($promotion, ENT_QUOTES, 'UTF-8') ?>" <?= $oldPromotion === $promotion ? 'selected' : '' ?>>
              <?= htmlspecialchars($promotion, ENT_QUOTES, 'UTF-8') ?>
            </option>
            <?php endforeach; ?>
          </select>

          <input type="password" name="password" class="input-field" placeholder="••••••••" style="margin-bottom:24px;" required>

          <button type="submit" class="btn-solid">
            Créer mon compte
          </button>

          <a href="<?= htmlspecialchars($basePath . '/login', ENT_QUOTES, 'UTF-8') ?>" class="btn-outline">
            Connexion
          </a>

        </form>
      </div>

    </div>
  </div>

</body>
</html>
