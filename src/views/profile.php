  <div style="flex:1; padding:24px 28px;">
    <div style="max-width:1200px;margin:0 auto;">

      <!-- Bannière + Avatar -->
      <div class="card" style="overflow:hidden;margin-bottom:24px;background:var(--container-gradient);backdrop-filter:blur(12px);border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,0.15);border:1px solid rgba(255,255,255,0.4);">
        <div style="height:200px;background:rgba(255,255,255,0.5);display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(0,0,0,0.1);">
          <span style="font-family:'SFMono-Regular',monospace;font-size:13px;color:#9ca3af;letter-spacing:.05em;">[ bannière de profil ]</span>
        </div>
        <div style="padding:0 28px 28px;position:relative;">
          <div style="width:88px;height:88px;border-radius:50%;background:#d1d5db;border:4px solid #fff;margin-top:-44px;margin-bottom:14px;box-shadow:0 1px 6px rgba(0,0,0,.12);"></div>

          <div style="display:grid;grid-template-columns:280px 1fr;gap:32px;align-items:start;">

            <!-- Gauche : infos profil -->
            <div style="display:flex;flex-direction:column;gap:18px;">
              <div>
                <h1 style="font-family:'Montserrat',sans-serif;font-size:20px;font-weight:800;color:#111827;margin-bottom:3px;"><?= htmlspecialchars($profileFullName ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></h1>
                <p style="font-size:13px;color:#6b7280;font-weight:500;"><?= htmlspecialchars($profileFormation ?? 'Profil Ynov', ENT_QUOTES, 'UTF-8') ?></p>
              </div>
              <div style="display:flex;gap:8px;">
                <button class="btn-dark" style="flex:1;">Envoyer message</button>
                <button class="btn-outline" style="padding:8px 12px;">···</button>
              </div>

              <!-- BIO -->
              <div>
                <p style="font-family:'Montserrat',sans-serif;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Bio</p>
                <div style="background:#f9fafb;border:1.5px dashed #d1d5db;border-radius:6px;padding:12px;">
                  <div class="skel" style="width:100%;height:9px;margin-bottom:7px;"></div>
                  <div class="skel" style="width:82%;height:9px;margin-bottom:7px;"></div>
                  <div class="skel" style="width:60%;height:9px;"></div>
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
                <div style="margin-top:10px;padding-top:10px;border-top:1px dashed var(--gray-400);">
                  <span class="badge-api">POST /ai/summarize — Gemini</span>
                </div>
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
              <div class="card" style="padding:18px;background:rgba(255,255,255,0.6);border:1px solid rgba(255,255,255,0.5);">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                  <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:50%;background:#d1d5db;"></div>
                    <div style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#111827;"><?= htmlspecialchars($profileFullName ?? 'Utilisateur', ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                  <span style="font-size:11px;color:#9ca3af;"><?= htmlspecialchars($publishedAt, ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <div style="font-size:14px;line-height:1.55;color:#374151;white-space:normal;word-break:break-word;margin-bottom:12px;">
                  <?= nl2br(htmlspecialchars((string) ($profilePost['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                </div>

                <?php if (!empty($profilePost['image_path'])): ?>
                <div style="margin-bottom:12px;">
                  <img
                    src="<?= htmlspecialchars(($basePath ?? '') . (string) $profilePost['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Image du post"
                    style="max-width:100%;max-height:300px;border-radius:8px;border:1px solid #d1d5db;object-fit:cover;"
                  >
                </div>
                <?php endif; ?>

                <div style="display:flex;justify-content:flex-start;align-items:center;padding-top:10px;border-top:1px solid #f3f4f6;gap:8px;">
                  <span style="font-size:12px;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:3px 10px;display:flex;align-items:center;gap:4px;"><span>❤</span> <?= $likeCount ?></span>
                  <span style="font-size:12px;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:3px 10px;display:flex;align-items:center;gap:4px;"><span>💬</span> <?= $commentCount ?></span>
                </div>
              </div>
              <?php endforeach; ?>
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
