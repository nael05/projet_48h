  <div style="flex:1; padding:24px 28px;">
    <div style="max-width:1200px;margin:0 auto;">

      <!-- Bannière + Avatar -->
      <div class="card" style="overflow:hidden;margin-bottom:24px;background:var(--container-gradient);backdrop-filter:blur(12px);border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,0.15);border:1px solid rgba(255,255,255,0.4);">
        <div style="height:200px;background:rgba(255,255,255,0.5);display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(0,0,0,0.1);overflow:hidden;">
          <?php if (!empty($isOwnProfile)): ?>
            <label for="banner-picture-input" id="banner-picture-trigger" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;position:relative;cursor:pointer;overflow:hidden;">
              <?php if (!empty($profileBanner)): ?>
                <img id="banner-picture-preview" src="<?= htmlspecialchars(($basePath ?? '') . $profileBanner, ENT_QUOTES, 'UTF-8') ?>" alt="Bannière de profil" style="width:100%;height:100%;object-fit:cover;">
              <?php else: ?>
                <span id="banner-picture-placeholder" style="font-family:'SFMono-Regular',monospace;font-size:13px;color:#9ca3af;letter-spacing:.05em;">[ bannière de profil ]</span>
              <?php endif; ?>
              <span style="position:absolute;right:10px;bottom:10px;background:rgba(17,24,39,.7);color:#fff;font-size:11px;font-weight:600;padding:4px 8px;border-radius:4px;">Changer la bannière</span>
            </label>
          <?php else: ?>
            <?php if (!empty($profileBanner)): ?>
              <img src="<?= htmlspecialchars(($basePath ?? '') . $profileBanner, ENT_QUOTES, 'UTF-8') ?>" alt="Bannière de profil" style="width:100%;height:100%;object-fit:cover;">
            <?php else: ?>
              <span style="font-family:'SFMono-Regular',monospace;font-size:13px;color:#9ca3af;letter-spacing:.05em;">[ bannière de profil ]</span>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <div style="padding:0 28px 28px;position:relative;">
          <?php if (!empty($isOwnProfile)): ?>
            <label for="profile-picture-input" id="profile-picture-trigger" style="width:88px;height:88px;border-radius:50%;background:#d1d5db;border:4px solid #fff;margin-top:-44px;margin-bottom:14px;box-shadow:0 1px 6px rgba(0,0,0,.12);overflow:hidden;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;">
              <?php if (!empty($profilePicture)): ?>
                <img id="profile-picture-preview" src="<?= htmlspecialchars(($basePath ?? '') . $profilePicture, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" style="width:100%;height:100%;object-fit:cover;">
              <?php else: ?>
                <span id="profile-picture-placeholder" style="font-size:11px;color:#6b7280;font-weight:700;">Photo</span>
              <?php endif; ?>
              <span style="position:absolute;left:50%;bottom:2px;transform:translateX(-50%);background:rgba(17,24,39,.7);color:#fff;font-size:10px;font-weight:600;padding:2px 6px;border-radius:3px;">Modifier</span>
            </label>
          <?php else: ?>
            <div style="width:88px;height:88px;border-radius:50%;background:#d1d5db;border:4px solid #fff;margin-top:-44px;margin-bottom:14px;box-shadow:0 1px 6px rgba(0,0,0,.12);overflow:hidden;">
              <?php if (!empty($profilePicture)): ?>
                <img src="<?= htmlspecialchars(($basePath ?? '') . $profilePicture, ENT_QUOTES, 'UTF-8') ?>" alt="Photo de profil" style="width:100%;height:100%;object-fit:cover;">
              <?php endif; ?>
            </div>
          <?php endif; ?>

          <div style="display:grid;grid-template-columns:280px 1fr;gap:32px;align-items:start;">

            <!-- Gauche : infos profil -->
            <div style="display:flex;flex-direction:column;gap:18px;">
              <div>
                <h1 style="font-family:'Montserrat',sans-serif;font-size:20px;font-weight:800;color:#111827;margin-bottom:3px;"><?= htmlspecialchars($profileFullName ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></h1>
                <p style="font-size:13px;color:#6b7280;font-weight:500;"><?= htmlspecialchars($profileFormation ?? 'Profil Ynov', ENT_QUOTES, 'UTF-8') ?></p>
                <?php if (!empty($profileCampus) || !empty($profilePromotion)): ?>
                <div style="display:flex;flex-wrap:wrap;gap:7px;margin-top:9px;">
                  <?php if (!empty($profileCampus)): ?>
                  <span style="padding:4px 10px;border-radius:99px;border:1px solid #d1d5db;background:#f9fafb;font-size:12px;color:#374151;font-weight:600;">
                    Campus: <?= htmlspecialchars((string) $profileCampus, ENT_QUOTES, 'UTF-8') ?>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($profilePromotion)): ?>
                  <span style="padding:4px 10px;border-radius:99px;border:1px solid #d1d5db;background:#f9fafb;font-size:12px;color:#374151;font-weight:600;">
                    Promotion: <?= htmlspecialchars((string) $profilePromotion, ENT_QUOTES, 'UTF-8') ?>
                  </span>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
              </div>
              <div style="display:flex;gap:8px;">
                <?php if (!empty($isOwnProfile)): ?>
                <button class="btn-dark" style="flex:1; cursor:not-allowed; opacity:0.5;" disabled>Envoyer message</button>
                <?php else: ?>
                <a href="<?= htmlspecialchars(($basePath ?? '') . '/messages?with=' . (int)($profileUserId ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="btn-dark" style="flex:1; text-align:center; text-decoration:none; display:flex; align-items:center; justify-content:center; min-height:40px;">Envoyer message</a>
                <?php endif; ?>
                <button class="btn-outline" style="padding:8px 12px;">···</button>
              </div>

              <?php if (!empty($isOwnProfile)): ?>
              <form action="<?= htmlspecialchars(($basePath ?? '') . '/profile/update', ENT_QUOTES, 'UTF-8') ?>" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:8px;">
                <?php if (!empty($profileUpdateError)): ?>
                <div style="padding:8px 10px;background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:6px;font-size:12px;">
                  <?= htmlspecialchars((string) $profileUpdateError, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php endif; ?>

                <input id="profile-picture-input" type="file" name="profile_picture" accept="image/*" style="display:none;">
                <input id="banner-picture-input" type="file" name="banner_picture" accept="image/*" style="display:none;">

                <p style="font-size:12px;color:#6b7280;">Cliquez sur votre photo ou votre bannière pour choisir une image locale.</p>
                <label style="font-size:12px;color:#4b5563;font-weight:600;">Biographie
                  <textarea name="bio" rows="4" maxlength="1000" style="width:100%;margin-top:4px;background:#f9fafb;border:1.5px solid #d1d5db;border-radius:6px;padding:10px;font-family:'Roboto',sans-serif;font-size:13px;color:#374151;outline:none;resize:vertical;"><?= htmlspecialchars((string) ($profileBio ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </label>

                <button class="btn-dark" type="submit" style="width:100%;">Mettre à jour mon profil</button>
              </form>
              <?php endif; ?>

              <!-- BIO -->
              <div>
                <p style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Bio</p>
                <div style="background:#f9fafb;border:1.5px dashed #d1d5db;border-radius:6px;padding:12px;">
                  <?php if (!empty($profileBio)): ?>
                    <p style="font-size:13px;color:#374151;line-height:1.45;white-space:pre-wrap;"><?= htmlspecialchars((string) $profileBio, ENT_QUOTES, 'UTF-8') ?></p>
                  <?php else: ?>
                    <p style="font-size:12px;color:#9ca3af;">Aucune biographie pour le moment.</p>
                  <?php endif; ?>
                </div>
              </div>

              <!-- COMPÉTENCES -->
              <div>
                <p style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Compétences</p>
                <div style="display:flex;flex-wrap:wrap;gap:7px;">
                  <?php foreach (['JavaScript','Node.js','MySQL','React'] as $skill): ?>
                  <span style="padding:4px 12px;border-radius:99px;border:1px solid #d1d5db;font-size:12px;color:#374151;background:#f9fafb;font-weight:500;"><?= $skill ?></span>
                  <?php endforeach; ?>
                  <span style="padding:4px 12px;border-radius:99px;border:1.5px dashed #d1d5db;font-size:12px;color:#9ca3af;background:#fff;cursor:pointer;">+ Ajouter</span>
                </div>
              </div>

              <!-- RÉSUMÉ IA -->
              <div style="padding-top:16px;border-top:1px solid rgba(0,0,0,0.1);">
                <p style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#111827;margin-bottom:10px;">Résumé IA ✨</p>
                <div style="background:#eff6ff;border:1.5px dashed #93c5fd;border-radius:6px;padding:12px;margin-bottom:10px;">
                  <div style="height:9px;background:#bfdbfe;border-radius:4px;width:100%;margin-bottom:7px;"></div>
                  <div style="height:9px;background:#bfdbfe;border-radius:4px;width:75%;"></div>
                </div>
                <button class="btn-dark" style="width:100%;margin-bottom:8px;">Générer résumé (IA)</button>
              </div>
            </div> <!-- FERMETURE COLONNE GAUCHE MANQUANTE -->

            <!-- Droite : Publications + Stats -->
            <div style="display:flex;flex-direction:column;gap:20px;">
              <div>
                <div class="section-header"><h2>Publications</h2><span class="sep"></span></div>
              </div>

              <?php if (empty($profilePosts)): ?>
              <div class="card" style="padding:18px;background:rgba(255,255,255,0.6);border:1px solid rgba(255,255,255,0.5);">
                <p style="font-size:14px;color:#4b5563;">Aucune publication pour cet utilisateur.</p>
              </div>
              <?php else: ?>
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:12px;align-items:start;">
              <?php foreach ($profilePosts as $profilePost): ?>
              <?php
                $publishedAt = '';
                if (!empty($profilePost['created_at'])) {
                    $timestamp = strtotime((string) $profilePost['created_at']);
                    if ($timestamp !== false) {
                        $publishedAt = date('d/m/Y H:i', $timestamp);
                    }
                }
                $likeCount = (int) ($profilePost['like_count'] ?? 0);
                $commentCount = (int) ($profilePost['comment_count'] ?? 0);
              ?>
              <div class="card" style="padding:14px;background:rgba(255,255,255,0.6);border:1px solid rgba(255,255,255,0.5);min-height:470px;display:flex;flex-direction:column;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                  <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:50%;background:#d1d5db;overflow:hidden;">
                      <?php if (!empty($profilePicture)): ?>
                      <img src="<?= htmlspecialchars(($basePath ?? '') . $profilePicture, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($profileFullName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
                      <?php endif; ?>
                    </div>
                    <div style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#111827;"><?= htmlspecialchars($profileFullName ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                  <span style="font-size:11px;color:#9ca3af;"><?= htmlspecialchars($publishedAt, ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <div style="font-size:14px;line-height:1.55;color:#374151;white-space:normal;word-break:break-word;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;min-height:86px;">
                  <?= nl2br(htmlspecialchars((string) ($profilePost['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                </div>

                <div style="margin-top:12px;height:220px;border-radius:8px;border:1px solid #d1d5db;background:#f3f4f6;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                  <?php if (!empty($profilePost['image_path'])): ?>
                  <img
                    src="<?= htmlspecialchars(($basePath ?? '') . (string) $profilePost['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Image du post"
                    style="width:100%;height:100%;object-fit:cover;"
                  >
                  <?php else: ?>
                  <span style="font-size:12px;color:#9ca3af;font-family:'Montserrat',sans-serif;">Aucune image</span>
                  <?php endif; ?>
                </div>

                <div style="display:flex;justify-content:flex-start;align-items:center;padding-top:10px;border-top:1px solid #f3f4f6;gap:8px;margin-top:auto;">
                  <span style="font-size:12px;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:3px 10px;display:flex;align-items:center;gap:4px;"><span>❤</span> <?= $likeCount ?></span>
                  <span style="font-size:12px;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:3px 10px;display:flex;align-items:center;gap:4px;"><span>💬</span> <?= $commentCount ?></span>
                </div>
              </div>
              <?php endforeach; ?>
              </div>
              <?php endif; ?>

              <!-- Statistiques -->
              <div>
                <div class="section-header" style="margin-top:8px;"><h2>Statistiques</h2><span class="sep"></span></div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                  <?php foreach ([[(string) ($profileStats['posts'] ?? 0),'Publications'],[(string) ($profileStats['likes'] ?? 0),'Likes'],[(string) ($profileStats['comments'] ?? 0),'Commentaires']] as $s): ?>
                  <div style="background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;padding:18px;text-align:center;">
                    <div style="font-family:'Montserrat',sans-serif;font-size:28px;font-weight:800;color:#111827;"><?= $s[0] ?></div>
                    <div style="font-size:11px;color:#6b7280;font-weight:500;text-transform:uppercase;letter-spacing:.07em;margin-top:4px;"><?= $s[1] ?></div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

<?php if (!empty($isOwnProfile)): ?>
<script>
(function () {
  var profileInput = document.getElementById('profile-picture-input');
  var bannerInput = document.getElementById('banner-picture-input');
  var profileTrigger = document.getElementById('profile-picture-trigger');
  var bannerTrigger = document.getElementById('banner-picture-trigger');

  function previewImage(input, imgId, placeholderId, trigger) {
    if (!input || !input.files || !input.files[0] || !trigger) {
      return;
    }

    var file = input.files[0];
    if (!file.type || file.type.indexOf('image/') !== 0) {
      return;
    }

    var reader = new FileReader();
    reader.onload = function (event) {
      var img = document.getElementById(imgId);
      if (!img) {
        img = document.createElement('img');
        img.id = imgId;
        img.alt = 'Aperçu image';
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'cover';
        trigger.insertBefore(img, trigger.firstChild);
      }

      img.src = String(event.target && event.target.result ? event.target.result : '');

      var placeholder = document.getElementById(placeholderId);
      if (placeholder) {
        placeholder.style.display = 'none';
      }
    };

    reader.readAsDataURL(file);
  }

  if (profileInput) {
    profileInput.addEventListener('change', function () {
      previewImage(profileInput, 'profile-picture-preview', 'profile-picture-placeholder', profileTrigger);
    });
  }

  if (bannerInput) {
    bannerInput.addEventListener('change', function () {
      previewImage(bannerInput, 'banner-picture-preview', 'banner-picture-placeholder', bannerTrigger);
    });
  }
})();
</script>
<?php endif; ?>
