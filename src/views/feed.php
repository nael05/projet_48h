  <style>
    .feed-post-card {
      min-height: 470px;
      display: flex;
      flex-direction: column;
    }

    .feed-post-text {
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
      overflow: hidden;
      min-height: 86px;
    }

    .feed-post-media {
      margin-top: 12px;
      height: 220px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      background: #f3f4f6;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .feed-post-media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>

  <?php
    $sessionProfilePicture = trim((string) ($_SESSION['profile_picture'] ?? ''));
    $sessionAvatarUrl = $sessionProfilePicture !== '' ? (($basePath ?? '') . $sessionProfilePicture) : '';
  ?>

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
            <div style="width:46px;height:46px;border-radius:50%;background:#d1d5db;flex-shrink:0;overflow:hidden;">
              <?php if ($sessionAvatarUrl !== ''): ?>
              <img src="<?= htmlspecialchars($sessionAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Mon avatar" style="width:100%;height:100%;object-fit:cover;">
              <?php endif; ?>
            </div>
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
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:12px;align-items:start;">
        <?php foreach ($posts as $post): ?>
        <?php
          $postId = (int) ($post['id'] ?? 0);
          $postUserId = (int) ($post['user_id'] ?? 0);
          $isOwnPost = ((int) ($post['user_id'] ?? 0) === (int) ($_SESSION['user_id'] ?? 0));
          $displayName = trim(((string) ($post['prenom'] ?? '')) . ' ' . ((string) ($post['nom'] ?? '')));
          if ($displayName === '') {
              $displayName = (string) ($post['username'] ?? 'Utilisateur');
          }
            $postProfilePicture = trim((string) ($post['profile_picture'] ?? ''));
            $postAvatarUrl = $postProfilePicture !== '' ? (($basePath ?? '') . $postProfilePicture) : '';
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
        <div class="card feed-post-card" style="padding:14px;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <a href="<?= htmlspecialchars(($basePath ?? '') . '/user?id=' . $postUserId, ENT_QUOTES, 'UTF-8') ?>" style="display:block;width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;overflow:hidden;">
                <?php if ($postAvatarUrl !== ''): ?>
                <img src="<?= htmlspecialchars($postAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php endif; ?>
              </a>
              <div>
                <a href="<?= htmlspecialchars(($basePath ?? '') . '/user?id=' . $postUserId, ENT_QUOTES, 'UTF-8') ?>" style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#111827;display:inline-block;">
                  <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>
                </a>
                <div style="font-size:12px;color:#6b7280;">Publication campus</div>
              </div>
            </div>
            <span style="font-size:11.5px;color:#9ca3af;font-weight:500;"><?= htmlspecialchars($publishedAt, ENT_QUOTES, 'UTF-8') ?></span>
          </div>

          <div class="feed-post-text" style="font-size:14px;line-height:1.55;color:#374151;white-space:normal;word-break:break-word;">
            <?= nl2br(htmlspecialchars((string) ($post['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
          </div>

          <div class="feed-post-media">
            <?php if (!empty($post['image_path'])): ?>
            <img
              src="<?= htmlspecialchars(($basePath ?? '') . (string) $post['image_path'], ENT_QUOTES, 'UTF-8') ?>"
              alt="Image du post"
            >
            <?php else: ?>
            <span style="font-size:12px;color:#9ca3af;font-family:'Montserrat',sans-serif;">Aucune image</span>
            <?php endif; ?>
          </div>

          <div style="display:flex;gap:8px;align-items:center;padding-top:12px;margin-top:12px;border-top:1px solid #f3f4f6;">
            <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts/like', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="margin:0;">
              <input type="hidden" name="post_id" value="<?= $postId ?>">
              <button class="btn-secondary" type="submit" style="display:flex;align-items:center;gap:8px;">
                <span style="font-size:18px;color:<?= $isLiked ? '#ef4444' : '#9ca3af' ?>;line-height:1;"><?= $isLiked ? '&#9829;' : '&#9825;' ?></span>
                <span><?= $likeCount ?> Like<?= $likeCount > 1 ? 's' : '' ?></span>
              </button>
            </form>
            <button
              class="btn-secondary"
              type="button"
              style="display:flex;align-items:center;gap:8px;"
              onclick="(function(){var box=document.getElementById('comments-box-<?= $postId ?>'); if(!box){return;} box.style.display = (box.style.display === 'none') ? 'block' : 'none';})();"
              aria-expanded="false"
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg>
              <span><?= count($postComments) ?> commentaire<?= count($postComments) > 1 ? 's' : '' ?></span>
            </button>
            <button
              class="btn-secondary"
              type="button"
              style="display:flex;align-items:center;justify-content:center;gap:0;margin-left:auto;padding:8px 10px;"
              data-share-post-id="<?= $postId ?>"
              onclick="openSharePostModal(this)"
              title="Partager en message privé"
              aria-label="Partager en message privé"
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 2L11 13"></path>
                <path d="M22 2L15 22L11 13L2 9L22 2Z"></path>
              </svg>
            </button>
            <?php if ($isOwnPost): ?>
            <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts/delete', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer ce post ?');">
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

          <div id="comments-box-<?= $postId ?>" style="margin-top:12px;display:none;">
            <div style="display:flex;flex-direction:column;gap:8px;">
            <?php foreach ($postComments as $comment): ?>
            <?php
              $commentId = (int) ($comment['id'] ?? 0);
              $isOwnComment = ((int) ($comment['user_id'] ?? 0) === (int) ($_SESSION['user_id'] ?? 0));
              $commentAuthor = trim(((string) ($comment['prenom'] ?? '')) . ' ' . ((string) ($comment['nom'] ?? '')));
              if ($commentAuthor === '') {
                  $commentAuthor = (string) ($comment['username'] ?? 'Utilisateur');
              }
            ?>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;">
              <div style="display:flex;align-items:center;gap:8px;">
                <div style="font-size:12px;font-weight:700;color:#111827;flex:1;"><?= htmlspecialchars($commentAuthor, ENT_QUOTES, 'UTF-8') ?></div>
                <?php if ($isOwnComment): ?>
                <form action="<?= htmlspecialchars(($basePath ?? '') . '/posts/comment/delete', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer ce commentaire ?');">
                  <input type="hidden" name="comment_id" value="<?= $commentId ?>">
                  <button type="submit" class="btn-outline" title="Supprimer le commentaire" aria-label="Supprimer le commentaire" style="border-color:#fecaca;color:#b91c1c;background:#fff5f5;padding:4px 8px;line-height:1;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
        </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Charger plus -->
        <div style="text-align:center;padding:4px 0 12px;">
          <button class="btn-outline" style="padding:10px 32px;font-size:13px;">Charger plus...</button>
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
        </div>

        <!-- Suggérés -->
        <div>
          <div class="section-header"><h3>Suggérés</h3><span class="sep"></span></div>
          <div class="card" style="overflow:hidden;">
            <?php if (empty($suggestedUsers)): ?>
            <div style="padding:12px 16px;font-size:12px;color:#6b7280;">Aucun compte suggéré pour le moment.</div>
            <?php else: ?>
            <?php foreach ($suggestedUsers as $i => $suggestedUser): ?>
            <?php
              $suggestedUserId = (int) ($suggestedUser['id'] ?? 0);
              $suggestedDisplayName = trim(((string) ($suggestedUser['prenom'] ?? '')) . ' ' . ((string) ($suggestedUser['nom'] ?? '')));
              if ($suggestedDisplayName === '') {
                  $suggestedDisplayName = (string) ($suggestedUser['username'] ?? 'Utilisateur');
              }
              $suggestedProfilePicture = trim((string) ($suggestedUser['profile_picture'] ?? ''));
              $suggestedAvatarUrl = $suggestedProfilePicture !== '' ? (($basePath ?? '') . $suggestedProfilePicture) : '';
            ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;<?= $i>0 ? 'border-top:1px solid #f3f4f6':'' ?>">
              <a href="<?= htmlspecialchars(($basePath ?? '') . '/user?id=' . $suggestedUserId, ENT_QUOTES, 'UTF-8') ?>" style="display:flex;align-items:center;gap:10px;color:inherit;text-decoration:none;min-width:0;">
                <div style="width:36px;height:36px;border-radius:50%;background:#d1d5db;flex-shrink:0;overflow:hidden;">
                  <?php if ($suggestedAvatarUrl !== ''): ?>
                  <img src="<?= htmlspecialchars($suggestedAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($suggestedDisplayName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
                  <?php endif; ?>
                </div>
                <div style="font-size:12px;color:#374151;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px;"><?= htmlspecialchars($suggestedDisplayName, ENT_QUOTES, 'UTF-8') ?></div>
              </a>
              <a href="<?= htmlspecialchars(($basePath ?? '') . '/messages?with=' . $suggestedUserId, ENT_QUOTES, 'UTF-8') ?>" class="btn-outline" style="font-size:12px;padding:5px 12px;">Message</a>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </main>
  </div>

  <div id="share-post-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:1000;align-items:center;justify-content:center;padding:16px;">
    <div style="width:100%;max-width:460px;background:#fff;border-radius:12px;box-shadow:0 18px 40px rgba(0,0,0,.2);overflow:hidden;">
      <div style="padding:14px 16px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;gap:12px;">
        <h3 style="font-family:'Montserrat',sans-serif;font-size:15px;font-weight:700;color:#111827;">Partager la publication</h3>
        <button type="button" class="btn-outline" onclick="closeSharePostModal()" style="padding:5px 10px;font-size:12px;">Fermer</button>
      </div>

      <form action="<?= htmlspecialchars(($basePath ?? '') . '/messages/share', ENT_QUOTES, 'UTF-8') ?>" method="POST" style="padding:14px 16px;display:flex;flex-direction:column;gap:10px;">
        <input id="share-post-id-input" type="hidden" name="post_id" value="">

        <?php if (empty($shareRecipients)): ?>
        <div style="padding:10px;border:1px solid #e5e7eb;border-radius:8px;background:#f9fafb;font-size:13px;color:#6b7280;">Aucun destinataire disponible.</div>
        <?php else: ?>
        <div style="max-height:280px;overflow:auto;border:1px solid #e5e7eb;border-radius:8px;">
          <?php foreach ($shareRecipients as $index => $recipient): ?>
          <?php
            $recipientId = (int) ($recipient['id'] ?? 0);
            $recipientName = trim(((string) ($recipient['prenom'] ?? '')) . ' ' . ((string) ($recipient['nom'] ?? '')));
            if ($recipientName === '') {
                $recipientName = (string) ($recipient['username'] ?? 'Utilisateur');
            }
            $recipientProfilePicture = trim((string) ($recipient['profile_picture'] ?? ''));
            $recipientAvatarUrl = $recipientProfilePicture !== '' ? (($basePath ?? '') . $recipientProfilePicture) : '';
          ?>
          <label style="display:flex;align-items:center;gap:10px;padding:10px 12px;cursor:pointer;<?= $index > 0 ? 'border-top:1px solid #f3f4f6;' : '' ?>">
            <input type="radio" name="receiver_id" value="<?= $recipientId ?>" <?= $index === 0 ? 'checked' : '' ?> required>
            <div style="width:34px;height:34px;border-radius:50%;background:#d1d5db;overflow:hidden;flex-shrink:0;">
              <?php if ($recipientAvatarUrl !== ''): ?>
              <img src="<?= htmlspecialchars($recipientAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($recipientName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
              <?php endif; ?>
            </div>
            <span style="font-size:13px;color:#111827;font-weight:600;"><?= htmlspecialchars($recipientName, ENT_QUOTES, 'UTF-8') ?></span>
          </label>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="display:flex;justify-content:flex-end;gap:8px;padding-top:4px;">
          <button type="button" class="btn-outline" onclick="closeSharePostModal()">Annuler</button>
          <button type="submit" class="btn-dark" <?= empty($shareRecipients) ? 'disabled' : '' ?>>Partager</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  (function () {
    var modal = document.getElementById('share-post-modal');
    var postInput = document.getElementById('share-post-id-input');

    window.openSharePostModal = function (trigger) {
      if (!modal || !postInput || !trigger) {
        return;
      }

      var postId = String(trigger.getAttribute('data-share-post-id') || '').trim();
      if (!postId) {
        return;
      }

      postInput.value = postId;
      modal.style.display = 'flex';
    };

    window.closeSharePostModal = function () {
      if (!modal || !postInput) {
        return;
      }

      postInput.value = '';
      modal.style.display = 'none';
    };

    if (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          window.closeSharePostModal();
        }
      });
    }
  })();
  </script>
