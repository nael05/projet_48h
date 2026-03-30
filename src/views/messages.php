<?php
$basePath = $basePath ?? '';
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
$currentUserProfilePicture = trim((string) ($_SESSION['profile_picture'] ?? ''));
$currentUserAvatarUrl = $currentUserProfilePicture !== '' ? ($basePath . $currentUserProfilePicture) : '';
$selectedUserId = (int) ($selectedUser['id'] ?? 0);
$selectedDisplayName = trim(((string) ($selectedUser['prenom'] ?? '')) . ' ' . ((string) ($selectedUser['nom'] ?? '')));
if ($selectedDisplayName === '') {
    $selectedDisplayName = (string) ($selectedUser['username'] ?? 'Aucun utilisateur');
}
$selectedUserProfilePicture = trim((string) ($selectedUser['profile_picture'] ?? ''));
$selectedUserAvatarUrl = $selectedUserProfilePicture !== '' ? ($basePath . $selectedUserProfilePicture) : '';
$lastMessageId = 0;
if (!empty($conversationMessages)) {
    $last = end($conversationMessages);
    $lastMessageId = (int) ($last['id'] ?? 0);
    reset($conversationMessages);
}
?>

<div style="flex:1; padding:24px; display:flex; flex-direction:column; align-items:center;">
  <div style="width:100%; max-width:1200px; flex:1; display:flex; background:var(--container-gradient); backdrop-filter:blur(12px); border-radius:16px; overflow:hidden; box-shadow:0 12px 40px rgba(0,0,0,0.15); border:1px solid rgba(255,255,255,0.4); min-height:620px;">

    <div style="width:320px;flex-shrink:0;border-right:1px solid rgba(0,0,0,0.1);display:flex;flex-direction:column;">
      <div style="padding:16px;border-bottom:1px solid #e5e7eb;">
        <h2 style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#111827;margin-bottom:10px;">Messages</h2>
      </div>

      <div style="flex:1;overflow-y:auto;">
        <?php if (empty($contacts)): ?>
          <div style="padding:16px;font-size:13px;color:#6b7280;">Aucun compte disponible.</div>
        <?php else: ?>
          <?php foreach ($contacts as $contact): ?>
            <?php
              $contactId = (int) ($contact['id'] ?? 0);
              $isActive = ($selectedUserId === $contactId);
              $contactName = trim(((string) ($contact['prenom'] ?? '')) . ' ' . ((string) ($contact['nom'] ?? '')));
              if ($contactName === '') {
                  $contactName = (string) ($contact['username'] ?? 'Utilisateur');
              }
                $contactProfilePicture = trim((string) ($contact['profile_picture'] ?? ''));
                $contactAvatarUrl = $contactProfilePicture !== '' ? ($basePath . $contactProfilePicture) : '';
              $lastPreview = trim((string) ($contact['last_message'] ?? ''));
                $lastMessageHasImages = !empty($contact['last_message_image']) || !empty($contact['last_message_images']);
                $lastMessageHasSharedPost = ((int) ($contact['last_shared_post_id'] ?? 0) > 0);
                if ($lastPreview === '' && $lastMessageHasSharedPost) {
                  $lastPreview = '[Publication partagée]';
                }
                if ($lastPreview === '' && $lastMessageHasImages) {
                  $lastPreview = '[Image]';
                }
                if ($lastPreview === '') {
                  $lastPreview = 'Demarrer la conversation';
              }
              $lastAt = '';
              if (!empty($contact['last_message_at'])) {
                  $time = strtotime((string) $contact['last_message_at']);
                  if ($time !== false) {
                      $lastAt = date('H:i', $time);
                  }
              }
            ?>
            <a
              href="<?= htmlspecialchars($basePath . '/messages?with=' . $contactId, ENT_QUOTES, 'UTF-8') ?>"
              style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-left:3px solid <?= $isActive ? '#1f2937' : 'transparent' ?>;background:<?= $isActive ? '#f9fafb' : 'transparent' ?>;border-bottom:1px solid #f3f4f6;text-decoration:none;"
            >
              <div style="width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;overflow:hidden;">
                <?php if ($contactAvatarUrl !== ''): ?>
                <img src="<?= htmlspecialchars($contactAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($contactName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php endif; ?>
              </div>
              <div style="flex:1;min-width:0;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:3px;gap:8px;">
                  <span style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($contactName, ENT_QUOTES, 'UTF-8') ?></span>
                  <span style="font-size:11px;color:#6b7280;font-weight:500;flex-shrink:0;"><?= htmlspecialchars($lastAt, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <p style="font-size:12px;color:#4b5563;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($lastPreview, ENT_QUOTES, 'UTF-8') ?></p>
              </div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div style="padding:12px 16px;border-top:1px dashed var(--gray-400);">
        <span class="badge-api">GET /messages/poll every 2s</span>
      </div>
    </div>

    <div style="flex:1;display:flex;flex-direction:column;background:rgba(255,255,255,0.4);">
      <?php if ($selectedUserId <= 0 || !$selectedUser): ?>
        <div style="flex:1;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:14px;">Choisis un compte pour commencer a discuter.</div>
      <?php else: ?>
        <?php
          $selectedProfilePicture = trim((string) ($selectedUser['profile_picture'] ?? ''));
          $selectedAvatarUrl = $selectedProfilePicture !== '' ? ($basePath . $selectedProfilePicture) : '';
        ?>
        <div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;">
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:50%;background:#d1d5db;overflow:hidden;">
              <?php if ($selectedAvatarUrl !== ''): ?>
              <img src="<?= htmlspecialchars($selectedAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($selectedDisplayName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
              <?php endif; ?>
            </div>
            <div>
              <p style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#111827;margin-bottom:2px;"><?= htmlspecialchars($selectedDisplayName, ENT_QUOTES, 'UTF-8') ?></p>
              <p style="font-size:12px;color:#22c55e;font-weight:600;display:flex;align-items:center;gap:5px;">
                <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;"></span>Connecte
              </p>
            </div>
          </div>
          <a href="<?= htmlspecialchars($basePath . '/user?id=' . $selectedUserId, ENT_QUOTES, 'UTF-8') ?>" class="btn-outline" style="font-size:12.5px;padding:7px 16px;">Voir le profil</a>
        </div>

        <div id="conversation-messages" style="flex:1;overflow-y:auto;padding:24px 28px;display:flex;flex-direction:column;gap:12px;">
          <?php if (empty($conversationMessages)): ?>
            <div style="text-align:center;color:#6b7280;font-size:13px;">Aucun message pour le moment.</div>
          <?php else: ?>
            <?php foreach ($conversationMessages as $message): ?>
              <?php $isMine = ((int) ($message['sender_id'] ?? 0) === $currentUserId); ?>
              <div style="display:flex;justify-content:<?= $isMine ? 'flex-end' : 'flex-start' ?>;align-items:flex-end;gap:8px;">
                <?php if (!$isMine): ?>
                <div style="width:30px;height:30px;border-radius:50%;background:#d1d5db;overflow:hidden;flex-shrink:0;">
                  <?php if ($selectedUserAvatarUrl !== ''): ?>
                  <img src="<?= htmlspecialchars($selectedUserAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($selectedDisplayName, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
                  <?php endif; ?>
                </div>
                <?php endif; ?>
                <div style="<?= $isMine ? 'background:#1f2937;color:#fff;border-radius:12px 12px 3px 12px;' : 'background:#fff;border:1px solid #e5e7eb;color:#374151;border-radius:12px 12px 12px 3px;' ?>display:inline-block;padding:10px 16px;font-size:13.5px;width:auto;max-width:70%;box-shadow:0 1px 3px rgba(0,0,0,.06);white-space:normal;word-break:break-word;">
                  <?php
                    $sharedPostId = (int) ($message['shared_post_id'] ?? 0);
                    $sharedPostAuthor = trim(((string) ($message['shared_post_author_prenom'] ?? '')) . ' ' . ((string) ($message['shared_post_author_nom'] ?? '')));
                    if ($sharedPostAuthor === '') {
                        $sharedPostAuthor = (string) ($message['shared_post_author_username'] ?? 'Utilisateur');
                    }
                    $sharedPostAuthorPicture = trim((string) ($message['shared_post_author_profile_picture'] ?? ''));
                    $sharedPostAuthorAvatarUrl = $sharedPostAuthorPicture !== '' ? ($basePath . $sharedPostAuthorPicture) : '';
                    $sharedPostPublishedAt = '';
                    if (!empty($message['shared_post_created_at'])) {
                        $timestamp = strtotime((string) $message['shared_post_created_at']);
                        if ($timestamp !== false) {
                            $sharedPostPublishedAt = date('d/m/Y H:i', $timestamp);
                        }
                    }
                  ?>
                  <?php if ($sharedPostId > 0): ?>
                  <div style="margin-bottom:<?= (trim((string) ($message['content'] ?? '')) !== '' || !empty($message['image_path']) || !empty($message['image_paths'])) ? '8px' : '0' ?>;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:10px;max-width:320px;color:#111827;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                      <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                        <div style="width:28px;height:28px;border-radius:50%;background:#d1d5db;overflow:hidden;flex-shrink:0;">
                          <?php if ($sharedPostAuthorAvatarUrl !== ''): ?>
                          <img src="<?= htmlspecialchars($sharedPostAuthorAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar de <?= htmlspecialchars($sharedPostAuthor, ENT_QUOTES, 'UTF-8') ?>" style="width:100%;height:100%;object-fit:cover;">
                          <?php endif; ?>
                        </div>
                        <div style="font-size:12px;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($sharedPostAuthor, ENT_QUOTES, 'UTF-8') ?></div>
                      </div>
                      <div style="font-size:10.5px;color:#9ca3af;flex-shrink:0;"><?= htmlspecialchars($sharedPostPublishedAt, ENT_QUOTES, 'UTF-8') ?></div>
                    </div>

                    <div style="font-size:12px;line-height:1.4;color:#374151;white-space:normal;word-break:break-word;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;">
                      <?= nl2br(htmlspecialchars((string) ($message['shared_post_content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                    </div>

                    <div style="height:140px;border-radius:8px;border:1px solid #e5e7eb;background:#f3f4f6;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                      <?php if (!empty($message['shared_post_image_path'])): ?>
                      <img src="<?= htmlspecialchars($basePath . (string) $message['shared_post_image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Image de la publication" style="width:100%;height:100%;object-fit:cover;">
                      <?php else: ?>
                      <span style="font-size:11px;color:#9ca3af;">Aucune image</span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php endif; ?>

                  <?php
                    $messageImagePaths = [];
                    $messageImagePathsRaw = trim((string) ($message['image_paths'] ?? ''));
                    if ($messageImagePathsRaw !== '') {
                        $decodedPaths = json_decode($messageImagePathsRaw, true);
                        if (is_array($decodedPaths)) {
                            foreach ($decodedPaths as $pathCandidate) {
                                $path = trim((string) $pathCandidate);
                                if ($path !== '') {
                                    $messageImagePaths[] = $path;
                                }
                            }
                        }
                    }

                    $singleMessageImagePath = trim((string) ($message['image_path'] ?? ''));
                    if (empty($messageImagePaths) && $singleMessageImagePath !== '') {
                        $messageImagePaths[] = $singleMessageImagePath;
                    }
                  ?>
                  <?php if (!empty($messageImagePaths)): ?>
                  <div style="margin-bottom:<?= trim((string) ($message['content'] ?? '')) !== '' ? '8px' : '0' ?>;display:flex;flex-wrap:wrap;gap:6px;max-width:300px;">
                    <?php foreach ($messageImagePaths as $messageImagePath): ?>
                    <img src="<?= htmlspecialchars($basePath . $messageImagePath, ENT_QUOTES, 'UTF-8') ?>" alt="Image envoyee" style="display:block;width:92px;height:92px;object-fit:cover;border-radius:10px;">
                    <?php endforeach; ?>
                  </div>
                  <?php endif; ?>
                  <?php if (trim((string) ($message['content'] ?? '')) !== ''): ?>
                  <?= nl2br(htmlspecialchars((string) ($message['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                  <?php endif; ?>
                </div>
                <?php if ($isMine): ?>
                <div style="width:30px;height:30px;border-radius:50%;background:#d1d5db;overflow:hidden;flex-shrink:0;">
                  <?php if ($currentUserAvatarUrl !== ''): ?>
                  <img src="<?= htmlspecialchars($currentUserAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Mon avatar" style="width:100%;height:100%;object-fit:cover;">
                  <?php endif; ?>
                </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div style="background:transparent;border-top:1px solid rgba(0,0,0,0.1);padding:14px 20px;">
          <div id="message-image-preview-wrap" style="display:none;align-items:flex-start;gap:10px;margin-bottom:10px;">
            <div id="message-image-preview-list" style="display:flex;flex-wrap:wrap;gap:8px;max-width:320px;"></div>
            <div style="display:flex;flex-direction:column;gap:6px;">
              <span id="message-image-preview-name" style="font-size:12px;color:#4b5563;max-width:260px;"></span>
              <button id="message-image-clear" type="button" class="btn-outline" style="font-size:11px;padding:5px 10px;line-height:1.2;align-self:flex-start;">Retirer</button>
            </div>
          </div>

          <form id="message-form" style="display:flex;align-items:center;gap:10px;" action="<?= htmlspecialchars($basePath . '/messages/send', ENT_QUOTES, 'UTF-8') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="receiver_id" value="<?= $selectedUserId ?>">
            <label class="btn-outline" style="cursor:pointer;font-size:12px;padding:8px 12px;line-height:1.2;" title="Envoyer une image">
              Image
              <input id="message-image-input" type="file" name="image[]" accept="image/*" multiple style="display:none;">
            </label>
            <input id="message-input" type="text" name="content" placeholder="Ecrire un message..." maxlength="2000"
              style="flex:1;border:1.5px solid #e5e7eb;border-radius:6px;padding:10px 14px;font-family:'Roboto',sans-serif;font-size:13.5px;color:#374151;background:#f9fafb;outline:none;transition:border-color .2s;"
              onfocus="this.style.borderColor='#374151'" onblur="this.style.borderColor='#e5e7eb'">
            <button class="btn-dark" style="flex-shrink:0;" type="submit">Envoyer</button>
          </form>
          <div style="margin-top:10px;">
            <span class="badge-api">POST /messages/send + GET /messages/poll</span>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if ($selectedUserId > 0 && $selectedUser): ?>
<script>
(function () {
  const basePath = <?= json_encode($basePath, JSON_UNESCAPED_SLASHES) ?>;
  const currentUserId = <?= json_encode($currentUserId) ?>;
  const selectedUserId = <?= json_encode($selectedUserId) ?>;
  const currentUserAvatarUrl = <?= json_encode($currentUserAvatarUrl, JSON_UNESCAPED_SLASHES) ?>;
  const selectedUserAvatarUrl = <?= json_encode($selectedUserAvatarUrl, JSON_UNESCAPED_SLASHES) ?>;
  const selectedDisplayName = <?= json_encode($selectedDisplayName, JSON_UNESCAPED_UNICODE) ?>;
  const messagesContainer = document.getElementById('conversation-messages');
  const form = document.getElementById('message-form');
  const input = document.getElementById('message-input');
  const imageInput = document.getElementById('message-image-input');
  const imagePreviewWrap = document.getElementById('message-image-preview-wrap');
  const imagePreviewList = document.getElementById('message-image-preview-list');
  const imagePreviewName = document.getElementById('message-image-preview-name');
  const imageClearButton = document.getElementById('message-image-clear');

  let lastMessageId = <?= json_encode($lastMessageId) ?>;

  function scrollToBottom() {
    if (messagesContainer) {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
  }

  function clearImagePreview() {
    if (imageInput) {
      imageInput.value = '';
    }

    if (imagePreviewList) {
      imagePreviewList.innerHTML = '';
    }

    if (imagePreviewName) {
      imagePreviewName.textContent = '';
    }

    if (imagePreviewWrap) {
      imagePreviewWrap.style.display = 'none';
    }
  }

  function showImagePreview(files) {
    if (!files || !imagePreviewWrap || !imagePreviewList || !imagePreviewName) {
      return;
    }

    imagePreviewList.innerHTML = '';
    const validFiles = [];

    for (const file of Array.from(files)) {
      if (!file || !file.type || file.type.indexOf('image/') !== 0) {
        continue;
      }

      validFiles.push(file);
      const reader = new FileReader();
      reader.onload = function (event) {
        const thumb = document.createElement('img');
        thumb.src = String(event.target && event.target.result ? event.target.result : '');
        thumb.alt = 'Apercu image';
        thumb.style.display = 'block';
        thumb.style.width = '64px';
        thumb.style.height = '64px';
        thumb.style.objectFit = 'cover';
        thumb.style.borderRadius = '8px';
        thumb.style.border = '1px solid #d1d5db';
        thumb.style.background = '#f3f4f6';
        imagePreviewList.appendChild(thumb);
      };
      reader.readAsDataURL(file);
    }

    if (validFiles.length === 0) {
      clearImagePreview();
      return;
    }

    imagePreviewName.textContent = validFiles.length + ' image(s) selectionnee(s)';
    imagePreviewWrap.style.display = 'flex';
  }

  function parseMessageImagePaths(message) {
    const paths = [];
    const raw = message && message.image_paths ? message.image_paths : null;

    if (Array.isArray(raw)) {
      for (const candidate of raw) {
        const path = String(candidate || '').trim();
        if (path) {
          paths.push(path);
        }
      }
    } else if (typeof raw === 'string' && raw.trim() !== '') {
      try {
        const parsed = JSON.parse(raw);
        if (Array.isArray(parsed)) {
          for (const candidate of parsed) {
            const path = String(candidate || '').trim();
            if (path) {
              paths.push(path);
            }
          }
        }
      } catch (error) {
        // ignore invalid JSON and fallback to image_path
      }
    }

    if (paths.length === 0) {
      const singlePath = String(message && message.image_path ? message.image_path : '').trim();
      if (singlePath) {
        paths.push(singlePath);
      }
    }

    return paths;
  }

  function parseSharedPost(message) {
    const sharedPostId = Number(message && message.shared_post_id ? message.shared_post_id : 0);
    if (!sharedPostId) {
      return null;
    }

    const authorPrenom = String(message && message.shared_post_author_prenom ? message.shared_post_author_prenom : '').trim();
    const authorNom = String(message && message.shared_post_author_nom ? message.shared_post_author_nom : '').trim();
    let authorName = (authorPrenom + ' ' + authorNom).trim();
    if (!authorName) {
      authorName = String(message && message.shared_post_author_username ? message.shared_post_author_username : 'Utilisateur');
    }

    const createdRaw = String(message && message.shared_post_created_at ? message.shared_post_created_at : '').trim();
    let createdAtLabel = '';
    if (createdRaw) {
      const date = new Date(createdRaw.replace(' ', 'T'));
      if (!Number.isNaN(date.getTime())) {
        createdAtLabel = date.toLocaleString('fr-FR', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      }
    }

    return {
      id: sharedPostId,
      content: String(message && message.shared_post_content ? message.shared_post_content : ''),
      imagePath: String(message && message.shared_post_image_path ? message.shared_post_image_path : '').trim(),
      authorName: authorName,
      authorAvatar: String(message && message.shared_post_author_profile_picture ? message.shared_post_author_profile_picture : '').trim(),
      createdAtLabel: createdAtLabel
    };
  }

  function createSharedPostCard(sharedPost, hasBottomSpacing) {
    if (!sharedPost) {
      return null;
    }

    const card = document.createElement('div');
    card.style.marginBottom = hasBottomSpacing ? '8px' : '0';
    card.style.background = '#fff';
    card.style.border = '1px solid #e5e7eb';
    card.style.borderRadius = '10px';
    card.style.padding = '10px';
    card.style.maxWidth = '320px';
    card.style.color = '#111827';

    const header = document.createElement('div');
    header.style.display = 'flex';
    header.style.justifyContent = 'space-between';
    header.style.alignItems = 'flex-start';
    header.style.gap = '8px';
    header.style.marginBottom = '8px';

    const left = document.createElement('div');
    left.style.display = 'flex';
    left.style.alignItems = 'center';
    left.style.gap = '8px';
    left.style.minWidth = '0';

    const avatar = document.createElement('div');
    avatar.style.width = '28px';
    avatar.style.height = '28px';
    avatar.style.borderRadius = '50%';
    avatar.style.background = '#d1d5db';
    avatar.style.overflow = 'hidden';
    avatar.style.flexShrink = '0';
    if (sharedPost.authorAvatar) {
      const avatarImg = document.createElement('img');
      avatarImg.src = String(basePath + sharedPost.authorAvatar);
      avatarImg.alt = 'Avatar de ' + String(sharedPost.authorName);
      avatarImg.style.width = '100%';
      avatarImg.style.height = '100%';
      avatarImg.style.objectFit = 'cover';
      avatar.appendChild(avatarImg);
    }

    const author = document.createElement('div');
    author.style.fontSize = '12px';
    author.style.fontWeight = '700';
    author.style.color = '#111827';
    author.style.whiteSpace = 'nowrap';
    author.style.overflow = 'hidden';
    author.style.textOverflow = 'ellipsis';
    author.textContent = String(sharedPost.authorName);

    left.appendChild(avatar);
    left.appendChild(author);

    const date = document.createElement('div');
    date.style.fontSize = '10.5px';
    date.style.color = '#9ca3af';
    date.style.flexShrink = '0';
    date.textContent = String(sharedPost.createdAtLabel || '');

    header.appendChild(left);
    header.appendChild(date);

    const text = document.createElement('div');
    text.style.fontSize = '12px';
    text.style.lineHeight = '1.4';
    text.style.color = '#374151';
    text.style.whiteSpace = 'normal';
    text.style.wordBreak = 'break-word';
    text.style.marginBottom = '8px';
    text.style.display = '-webkit-box';
    text.style.webkitLineClamp = '4';
    text.style.webkitBoxOrient = 'vertical';
    text.style.overflow = 'hidden';
    text.textContent = String(sharedPost.content || '');

    const media = document.createElement('div');
    media.style.height = '140px';
    media.style.borderRadius = '8px';
    media.style.border = '1px solid #e5e7eb';
    media.style.background = '#f3f4f6';
    media.style.display = 'flex';
    media.style.alignItems = 'center';
    media.style.justifyContent = 'center';
    media.style.overflow = 'hidden';
    if (sharedPost.imagePath) {
      const mediaImg = document.createElement('img');
      mediaImg.src = String(basePath + sharedPost.imagePath);
      mediaImg.alt = 'Image de la publication';
      mediaImg.style.width = '100%';
      mediaImg.style.height = '100%';
      mediaImg.style.objectFit = 'cover';
      media.appendChild(mediaImg);
    } else {
      const noImage = document.createElement('span');
      noImage.style.fontSize = '11px';
      noImage.style.color = '#9ca3af';
      noImage.textContent = 'Aucune image';
      media.appendChild(noImage);
    }

    card.appendChild(header);
    card.appendChild(text);
    card.appendChild(media);

    return card;
  }

  function appendMessage(message) {
    if (!messagesContainer || !message || !message.id) {
      return;
    }

    const senderId = Number(message.sender_id || 0);
    const isMine = senderId === Number(currentUserId);

    const row = document.createElement('div');
    row.style.display = 'flex';
    row.style.justifyContent = isMine ? 'flex-end' : 'flex-start';
    row.style.alignItems = 'flex-end';
    row.style.gap = '8px';

    const avatar = document.createElement('div');
    avatar.style.width = '30px';
    avatar.style.height = '30px';
    avatar.style.borderRadius = '50%';
    avatar.style.background = '#d1d5db';
    avatar.style.overflow = 'hidden';
    avatar.style.flexShrink = '0';

    const avatarImg = document.createElement('img');
    avatarImg.style.width = '100%';
    avatarImg.style.height = '100%';
    avatarImg.style.objectFit = 'cover';

    const bubble = document.createElement('div');
    bubble.style.display = 'inline-block';
    bubble.style.padding = '10px 16px';
    bubble.style.fontSize = '13.5px';
    bubble.style.width = 'auto';
    bubble.style.maxWidth = '70%';
    bubble.style.boxShadow = '0 1px 3px rgba(0,0,0,.06)';
    bubble.style.whiteSpace = 'normal';
    bubble.style.wordBreak = 'break-word';

    if (isMine) {
      bubble.style.background = '#1f2937';
      bubble.style.color = '#fff';
      bubble.style.borderRadius = '12px 12px 3px 12px';
      if (currentUserAvatarUrl) {
        avatarImg.src = String(currentUserAvatarUrl);
        avatarImg.alt = 'Mon avatar';
        avatar.appendChild(avatarImg);
      }
      row.appendChild(bubble);
      row.appendChild(avatar);
    } else {
      bubble.style.background = '#fff';
      bubble.style.color = '#374151';
      bubble.style.border = '1px solid #e5e7eb';
      bubble.style.borderRadius = '12px 12px 12px 3px';
      if (selectedUserAvatarUrl) {
        avatarImg.src = String(selectedUserAvatarUrl);
        avatarImg.alt = 'Avatar de ' + String(selectedDisplayName || 'Utilisateur');
        avatar.appendChild(avatarImg);
      }
      row.appendChild(avatar);
      row.appendChild(bubble);
    }

    const imagePaths = parseMessageImagePaths(message);
    const sharedPost = parseSharedPost(message);
    const content = String(message.content || '');

    if (sharedPost) {
      const hasBottomSpacing = imagePaths.length > 0 || content.trim() !== '';
      const sharedCard = createSharedPostCard(sharedPost, hasBottomSpacing);
      if (sharedCard) {
        bubble.appendChild(sharedCard);
      }
    }

    if (imagePaths.length > 0) {
      const imageWrap = document.createElement('div');
      if (content.trim() !== '') {
        imageWrap.style.marginBottom = '8px';
      }
      imageWrap.style.display = 'flex';
      imageWrap.style.flexWrap = 'wrap';
      imageWrap.style.gap = '6px';
      imageWrap.style.maxWidth = '300px';

      for (const imagePath of imagePaths) {
        const image = document.createElement('img');
        image.src = String(basePath + imagePath);
        image.alt = 'Image envoyee';
        image.style.display = 'block';
        image.style.width = '92px';
        image.style.height = '92px';
        image.style.objectFit = 'cover';
        image.style.borderRadius = '10px';
        imageWrap.appendChild(image);
      }
      bubble.appendChild(imageWrap);
    }

    if (content.trim() !== '') {
      const textNode = document.createElement('div');
      textNode.textContent = content;
      bubble.appendChild(textNode);
    }

    if (!sharedPost && imagePaths.length === 0 && content.trim() === '') {
      return;
    }

    messagesContainer.appendChild(row);
    lastMessageId = Math.max(lastMessageId, Number(message.id || 0));
    scrollToBottom();
  }

  async function sendMessage(event) {
    event.preventDefault();

    const content = input.value.trim();
    const hasImage = !!(imageInput && imageInput.files && imageInput.files.length > 0);

    if (!content && !hasImage) {
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch(basePath + '/messages/send', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const data = await response.json();
      if (!response.ok || !data.ok) {
        return;
      }

      appendMessage(data.message);
      input.value = '';
      clearImagePreview();
      input.focus();
    } catch (error) {
      // ignore temporary network errors
    }
  }

  async function pollMessages() {
    try {
      const response = await fetch(
        basePath + '/messages/poll?with=' + encodeURIComponent(String(selectedUserId)) + '&since_id=' + encodeURIComponent(String(lastMessageId)),
        {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        }
      );

      const data = await response.json();
      if (!response.ok || !data.ok || !Array.isArray(data.messages)) {
        return;
      }

      for (const message of data.messages) {
        appendMessage(message);
      }
    } catch (error) {
      // ignore temporary network errors
    }
  }

  form.addEventListener('submit', sendMessage);

  if (imageInput) {
    imageInput.addEventListener('change', function () {
      const hasFile = !!(imageInput.files && imageInput.files[0]);
      if (!hasFile) {
        clearImagePreview();
        return;
      }

      showImagePreview(imageInput.files);
    });
  }

  if (imageClearButton) {
    imageClearButton.addEventListener('click', function () {
      clearImagePreview();
    });
  }

  scrollToBottom();
  window.setInterval(pollMessages, 2000);
})();
</script>
<?php endif; ?>
