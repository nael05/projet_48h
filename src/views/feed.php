  <div style="flex:1; padding:24px;">
    <main style="max-width:1200px;margin:0 auto;padding:28px;background:var(--container-gradient);backdrop-filter:blur(10px);border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,0.15);display:grid;grid-template-columns:1fr 360px;gap:24px;">

      <!-- ══ COLONNE GAUCHE ══ -->
      <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Publier -->
        <div class="section-header">
          <h2>Publier</h2><span class="sep"></span>
        </div>
        <div class="card" style="padding:20px;">
          <?php if (!empty($publishError)): ?>
          <div style="margin-bottom:12px;padding:10px 12px;background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:6px;font-size:13px;">
            <?= htmlspecialchars((string) $publishError, ENT_QUOTES, 'UTF-8') ?>
          </div>
          <?php endif; ?>

          <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts', ENT_QUOTES, 'UTF-8') ?>" method="POST" enctype="multipart/form-data" style="display:flex;gap:14px;">
            <div style="width:46px;height:46px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
            <div style="flex:1;">
              <textarea name="content" rows="3" placeholder="Quoi de neuf sur le campus ?" required
                style="width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:6px;padding:12px;font-family:'Roboto',sans-serif;font-size:13.5px;color:#374151;resize:none;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#374151'" onblur="this.style.borderColor='#e5e7eb'"><?= htmlspecialchars((string) ($oldPostContent ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
              <div style="margin-top:10px;display:flex;align-items:center;gap:8px;">
                <label class="btn-outline" style="cursor:pointer;">
                  Ajouter une image
                  <input type="file" name="image" accept="image/*" style="display:none;">
                </label>
                <button class="btn-dark" type="submit" style="margin-left:auto;">Publier</button>
              </div>
            </div>
          </form>
          <div style="margin-top:14px;padding-top:12px;border-top:1px dashed var(--gray-400);">
            <span class="badge-api">POST /posts + image</span>
          </div>
        </div>

        <!-- Fil d'actualité -->
        <div class="section-header" style="margin-top:6px;">
          <h2>Fil d'actualité</h2><span class="sep"></span>
        </div>

        <?php if (empty($posts)): ?>
        <div class="card" style="padding:20px;">
          <p style="font-size:14px;color:#4b5563;">Aucune publication pour le moment. Sois le premier a publier.</p>
        </div>
        <?php else: ?>
        <?php foreach ($posts as $post): ?>
        <?php
          $postId = (int) ($post['id'] ?? 0);
          $postUserId = (int) ($post['user_id'] ?? 0);
          $isOwnPost = ((int) ($post['user_id'] ?? 0) === (int) ($_SESSION['user_id'] ?? 0));
          $displayName = trim(((string) ($post['prenom'] ?? '')) . ' ' . ((string) ($post['nom'] ?? '')));
          if ($displayName === '') {
              $displayName = (string) ($post['username'] ?? 'Utilisateur');
          }
          $publishedAt = '';
          if (!empty($post['created_at'])) {
              $timestamp = strtotime((string) $post['created_at']);
              if ($timestamp !== false) {
                  $publishedAt = date('d/m/Y H:i', $timestamp);
              }
          }
          $likeCount = (int) ($likesCountByPost[$postId] ?? 0);
          $isLiked = !empty($likedPostIds[$postId]);
          $postComments = $commentsByPost[$postId] ?? [];
        ?>
        <div class="card" style="padding:20px;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <a href="<?= htmlspecialchars(($basePath ?? '') . '/user?id=' . $postUserId, ENT_QUOTES, 'UTF-8') ?>" style="display:block;width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></a>
              <div>
                <a href="<?= htmlspecialchars(($basePath ?? '') . '/user?id=' . $postUserId, ENT_QUOTES, 'UTF-8') ?>" style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#111827;display:inline-block;">
                  <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>
                </a>
                <div style="font-size:12px;color:#6b7280;">Publication campus</div>
              </div>
            </div>
            <span style="font-size:11.5px;color:#9ca3af;font-weight:500;"><?= htmlspecialchars($publishedAt, ENT_QUOTES, 'UTF-8') ?></span>
          </div>

          <div style="font-size:14px;line-height:1.55;color:#374151;white-space:normal;word-break:break-word;">
            <?= nl2br(htmlspecialchars((string) ($post['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
          </div>

          <?php if (!empty($post['image_path'])): ?>
          <div style="margin-top:12px;">
            <img
              src="<?= htmlspecialchars(($basePath ?? '') . (string) $post['image_path'], ENT_QUOTES, 'UTF-8') ?>"
              alt="Image du post"
              style="max-width:100%;max-height:340px;border-radius:8px;border:1px solid #e5e7eb;object-fit:cover;"
            >
          </div>
          <?php endif; ?>

          <div style="display:flex;gap:8px;align-items:center;padding-top:12px;margin-top:12px;border-top:1px solid #f3f4f6;">
            <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts/like', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="margin:0;">
              <input type="hidden" name="post_id" value="<?= $postId ?>">
              <button class="btn-secondary" type="submit" style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:18px;color:<?= $isLiked ? '#ef4444' : '#9ca3af' ?>;line-height:1;"><?= $isLiked ? '&#9829;' : '&#9825;' ?></span>
                <span><?= $likeCount ?> Like<?= $likeCount > 1 ? 's' : '' ?></span>
              </button>
            </form>
            <span style="font-size:12px;color:#6b7280;"><?= count($postComments) ?> commentaire<?= count($postComments) > 1 ? 's' : '' ?></span>
            <?php if ($isOwnPost): ?>
            <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts/delete', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="margin:0 0 0 auto;" onsubmit="return confirm('Supprimer ce post ?');">
              <input type="hidden" name="post_id" value="<?= $postId ?>">
              <button type="submit" class="btn-outline" title="Supprimer le post" aria-label="Supprimer le post" style="border-color:#fecaca;color:#b91c1c;background:#fff5f5;padding:6px 10px;line-height:1;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M3 6h18"></path>
                  <path d="M8 6V4h8v2"></path>
                  <path d="M19 6l-1 14H6L5 6"></path>
                  <path d="M10 11v6"></path>
                  <path d="M14 11v6"></path>
                </svg>
              </button>
            </form>
            <?php endif; ?>
          </div>

          <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px;">
            <?php foreach ($postComments as $comment): ?>
            <?php
              $commentAuthor = trim(((string) ($comment['prenom'] ?? '')) . ' ' . ((string) ($comment['nom'] ?? '')));
              if ($commentAuthor === '') {
                  $commentAuthor = (string) ($comment['username'] ?? 'Utilisateur');
              }
            ?>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;">
              <div style="font-size:12px;font-weight:700;color:#111827;margin-bottom:3px;"><?= htmlspecialchars($commentAuthor, ENT_QUOTES, 'UTF-8') ?></div>
              <div style="font-size:13px;color:#374151;line-height:1.45;"><?= nl2br(htmlspecialchars((string) ($comment['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?></div>
            </div>
            <?php endforeach; ?>
          </div>

          <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts/comment', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="margin-top:10px;display:flex;gap:8px;">
            <input type="hidden" name="post_id" value="<?= $postId ?>">
            <input
              type="text"
              name="comment_content"
              placeholder="Ajouter un commentaire"
              maxlength="1000"
              required
              style="flex:1;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:6px;padding:10px 12px;font-family:'Roboto',sans-serif;font-size:13px;color:#374151;outline:none;"
            >
            <button class="btn-dark" type="submit">Commenter</button>
          </form>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Charger plus -->
        <div style="text-align:center;padding:4px 0 12px;">
          <button class="btn-outline" style="padding:10px 32px;font-size:13px;">Charger plus...</button>
          <div style="margin-top:14px;padding-top:10px;border-top:1px dashed var(--gray-400);text-align:left;">
            <span class="badge-api">GET /feed — pagination cursor</span>
          </div>
        </div>
      </div>

      <!-- ══ COLONNE DROITE ══ -->
      <div style="display:flex;flex-direction:column;gap:22px;">

        <!-- News Ynov -->
        <div>
          <div class="section-header"><h3>News Ynov</h3><span class="sep"></span></div>
          <div style="display:flex;flex-direction:column;gap:10px;">
            <?php foreach ([['Challenge 48h','31 mars 2026','100%'],['Tournoi BDS Foot','5 avril 2026','70%'],['Soirée BDE Printemps','12 avril 2026','85%']] as $n): ?>
            <div class="card" style="padding:14px 16px;cursor:pointer;transition:box-shadow .15s;" onmouseenter="this.style.boxShadow='0 3px 12px rgba(0,0,0,.09)'" onmouseleave="this.style.boxShadow=''">
              <h4 style="font-family:'Montserrat',sans-serif;font-size:13.5px;font-weight:700;color:#111827;margin-bottom:6px;"><?= $n[0] ?></h4>
              <div style="height:3px;background:#374151;border-radius:99px;width:<?= $n[2] ?>;margin-bottom:7px;"></div>
              <p style="font-size:11.5px;color:#6b7280;font-weight:500;"><?= $n[1] ?></p>
            </div>
            <?php endforeach; ?>
          </div>
          <div style="margin-top:10px;padding-top:10px;border-top:1px dashed var(--gray-400);">
            <span class="badge-api">GET /news — NewsModel</span>
          </div>
        </div>

        <!-- Ymatch -->
        <div>
          <div class="section-header"><h3>Ymatch</h3><span class="sep"></span></div>
          <div class="card" style="padding:18px;text-align:center;">
            <div style="width:100%;height:52px;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:6px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
              <span style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:18px;color:#374151;letter-spacing:-.01em;">Ymatch</span>
            </div>
            <p style="font-size:12px;color:#6b7280;margin-bottom:14px;">Trouve ton prochain projet ou stage 🎯</p>
            <button class="btn-dark" style="width:100%;">Voir les offres +</button>
          </div>
          <div style="margin-top:10px;padding-top:10px;border-top:1px dashed var(--gray-400);">
            <span class="badge-api">GET /ymatch — offres</span>
          </div>
        </div>

        <!-- Suggérés -->
        <div>
          <div class="section-header"><h3>Suggérés</h3><span class="sep"></span></div>
          <div class="card" style="overflow:hidden;">
            <?php foreach ([[90,70],[75,55],[85,65]] as $i => $w): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;<?= $i>0 ? 'border-top:1px solid #f3f4f6':'' ?>">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
                <div class="skel" style="width:<?= $w[0] ?>px;height:10px;"></div>
              </div>
              <button class="btn-outline" style="font-size:12px;padding:5px 12px;">+ Suivre</button>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </main>
  </div>
