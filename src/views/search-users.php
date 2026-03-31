<?php
$basePath = $basePath ?? '';
$searchQuery = htmlspecialchars($searchQuery ?? '', ENT_QUOTES, 'UTF-8');
$filterFiliere = htmlspecialchars($filterFiliere ?? '', ENT_QUOTES, 'UTF-8');
$filterPromo = htmlspecialchars($filterPromo ?? '', ENT_QUOTES, 'UTF-8');
?>

<div style="flex:1; padding:24px 28px; background: var(--bg-gradient); min-height: 100vh;">
  <div style="max-width:1000px; margin:0 auto;">
    
    <!-- Titre -->
    <div style="margin-bottom:24px;">
      <h1 style="font-family:'Montserrat',sans-serif; font-size:28px; font-weight:800; color:#111827; margin-bottom:8px;">Rechercher des utilisateurs</h1>
      <p style="font-size:14px; color:#6b7280;">Trouvez les étudiants par nom, filière ou promotion</p>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" action="<?= htmlspecialchars($basePath . '/search/users', ENT_QUOTES, 'UTF-8') ?>" style="margin-bottom:24px;">
      <div style="display:grid; grid-template-columns:1fr auto auto; gap:12px; margin-bottom:16px;">
        <div style="position:relative;">
          <input 
            type="text" 
            name="q" 
            placeholder="Rechercher par nom, prénom ou pseudo..." 
            value="<?= $searchQuery ?>"
            style="width:100%; padding:12px 16px; border:1.5px solid #d1d5db; border-radius:8px; font-size:14px; font-family:'Roboto',sans-serif; background:#fff; color:#374151;"
          />
        </div>
        <button type="submit" class="btn-dark" style="padding:12px 24px; white-space:nowrap;">Rechercher</button>
        <?php if ($searchQuery || $filterFiliere || $filterPromo): ?>
        <a href="<?= htmlspecialchars($basePath . '/search/users', ENT_QUOTES, 'UTF-8') ?>" class="btn-outline" style="padding:12px 24px; white-space:nowrap; text-decoration:none; text-align:center; border:1.5px solid #d1d5db; background:#fff; color:#374151;">Réinitialiser</a>
        <?php endif; ?>
      </div>

      <!-- Filtres -->
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:12px;">
        <div>
          <label style="display:block; font-size:12px; font-weight:600; color:#4b5563; margin-bottom:6px; text-transform:uppercase;">Filière</label>
          <select name="filiere" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:6px; font-size:13px; font-family:'Roboto',sans-serif; background:#fff; color:#374151; cursor:pointer;">
            <option value="">Toutes les filières</option>
            <?php foreach ($allFieres as $filiere): ?>
            <option value="<?= htmlspecialchars($filiere, ENT_QUOTES, 'UTF-8') ?>" <?= ($filterFiliere === $filiere) ? 'selected' : '' ?>>
              <?= htmlspecialchars($filiere, ENT_QUOTES, 'UTF-8') ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="display:block; font-size:12px; font-weight:600; color:#4b5563; margin-bottom:6px; text-transform:uppercase;">Promotion</label>
          <select name="promo" style="width:100%; padding:10px 12px; border:1.5px solid #d1d5db; border-radius:6px; font-size:13px; font-family:'Roboto',sans-serif; background:#fff; color:#374151; cursor:pointer;">
            <option value="">Toutes les promotions</option>
            <?php foreach ($allPromos as $promo): ?>
            <option value="<?= htmlspecialchars($promo, ENT_QUOTES, 'UTF-8') ?>" <?= ($filterPromo === $promo) ? 'selected' : '' ?>>
              Promotion <?= htmlspecialchars($promo, ENT_QUOTES, 'UTF-8') ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>

    <!-- Résultats -->
    <?php if (empty($results)): ?>
      <div style="background:rgba(255,255,255,0.9); border:1.5px solid #d1d5db; border-radius:8px; padding:40px; text-align:center;">
        <p style="font-size:16px; color:#6b7280; margin-bottom:8px;">Aucun résultat trouvé</p>
        <p style="font-size:13px; color:#9ca3af;">Essayez avec une autre recherche ou d'autres filtres</p>
      </div>
    <?php else: ?>
      <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
        <?php foreach ($results as $user): ?>
        <?php
          $userId = (int) ($user['id'] ?? 0);
          $userName = trim(((string) ($user['prenom'] ?? '')) . ' ' . ((string) ($user['nom'] ?? '')));
          if ($userName === '') {
            $userName = (string) ($user['username'] ?? 'Utilisateur');
          }
          $userFiliere = (string) ($user['formation'] ?? '');
          $userPromo = (string) ($user['promotion'] ?? '');
          $userProfilePic = (string) ($user['profile_picture'] ?? '');
          $userAvatarUrl = ($userProfilePic !== '') ? ($basePath . $userProfilePic) : '';
        ?>
        <div style="background:var(--container-gradient); backdrop-filter:blur(12px); border-radius:12px; border:1px solid rgba(255,255,255,0.4); overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:transform .2s, box-shadow .2s;">
          <!-- En-tête avec avatar -->
          <div style="padding:20px; text-align:center; background:linear-gradient(135deg, rgba(15,23,42,0.1) 0%, rgba(15,23,42,0.05) 100%);">
            <div style="width:80px; height:80px; border-radius:50%; background:#d1d5db; margin:0 auto 12px; overflow:hidden; border:4px solid #fff;">
              <?php if ($userAvatarUrl !== ''): ?>
              <img src="<?= htmlspecialchars($userAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%; height:100%; object-fit:cover;">
              <?php endif; ?>
            </div>
            <h3 style="font-family:'Montserrat',sans-serif; font-size:16px; font-weight:700; color:#111827; margin:0 0 4px;">
              <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
            </h3>
            <p style="font-size:12px; color:#6b7280; margin:0;">
              @<?= htmlspecialchars((string) ($user['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </p>
          </div>

          <!-- Info académique -->
          <div style="padding:16px; border-top:1px solid rgba(0,0,0,0.1);">
            <?php if ($userFiliere || $userPromo): ?>
            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px;">
              <?php if ($userFiliere): ?>
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase;">📚 Filière:</span>
                <span style="font-size:13px; color:#374151; font-weight:500;"><?= htmlspecialchars($userFiliere, ENT_QUOTES, 'UTF-8') ?></span>
              </div>
              <?php endif; ?>
              <?php if ($userPromo): ?>
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase;">📅 Promo:</span>
                <span style="font-size:13px; color:#374151; font-weight:500;"><?= htmlspecialchars($userPromo, ENT_QUOTES, 'UTF-8') ?></span>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <div style="display:flex; gap:8px;">
              <a href="<?= htmlspecialchars($basePath . '/user?id=' . $userId, ENT_QUOTES, 'UTF-8') ?>" class="btn-outline" style="flex:1; padding:10px; text-align:center; text-decoration:none; font-size:13px; font-weight:600; border:1.5px solid #d1d5db; background:#fff; color:#374151; border-radius:6px; cursor:pointer;">Voir profil</a>
              <a href="<?= htmlspecialchars($basePath . '/messages?with=' . $userId, ENT_QUOTES, 'UTF-8') ?>" class="btn-dark" style="flex:1; padding:10px; text-align:center; text-decoration:none; font-size:13px; font-weight:600; border:none; border-radius:6px; cursor:pointer;">Message</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div style="margin-top:24px; text-align:center; color:#6b7280; font-size:13px;">
        Affichage de <?= count($results) ?> résultat<?= count($results) > 1 ? 's' : '' ?> (limité à 50)
      </div>
    <?php endif; ?>

  </div>
</div>
