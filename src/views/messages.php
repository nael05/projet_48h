<?php
$basePath = $basePath ?? '';
$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
$selectedUserId = (int) ($selectedUser['id'] ?? 0);
$selectedDisplayName = trim(((string) ($selectedUser['prenom'] ?? '')) . ' ' . ((string) ($selectedUser['nom'] ?? '')));
if ($selectedDisplayName === '') {
    $selectedDisplayName = (string) ($selectedUser['username'] ?? 'Aucun utilisateur');
}
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
              $lastPreview = trim((string) ($contact['last_message'] ?? ''));
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
              <div style="width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
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
        <div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;">
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:50%;background:#d1d5db;"></div>
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
              <div style="display:flex;justify-content:<?= $isMine ? 'flex-end' : 'flex-start' ?>;">
                <div style="<?= $isMine ? 'background:#1f2937;color:#fff;border-radius:12px 12px 3px 12px;' : 'background:#fff;border:1px solid #e5e7eb;color:#374151;border-radius:12px 12px 12px 3px;' ?>padding:10px 16px;font-size:13.5px;max-width:70%;box-shadow:0 1px 3px rgba(0,0,0,.06);white-space:pre-wrap;word-break:break-word;">
                  <?= nl2br(htmlspecialchars((string) ($message['content'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div style="background:transparent;border-top:1px solid rgba(0,0,0,0.1);padding:14px 20px;">
          <form id="message-form" style="display:flex;align-items:center;gap:10px;" action="<?= htmlspecialchars($basePath . '/messages/send', ENT_QUOTES, 'UTF-8') ?>" method="POST">
            <input type="hidden" name="receiver_id" value="<?= $selectedUserId ?>">
            <input id="message-input" type="text" name="content" placeholder="Ecrire un message..." maxlength="2000" required
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
  const messagesContainer = document.getElementById('conversation-messages');
  const form = document.getElementById('message-form');
  const input = document.getElementById('message-input');

  let lastMessageId = <?= json_encode($lastMessageId) ?>;

  function scrollToBottom() {
    if (messagesContainer) {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
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

    const bubble = document.createElement('div');
    bubble.style.padding = '10px 16px';
    bubble.style.fontSize = '13.5px';
    bubble.style.maxWidth = '70%';
    bubble.style.boxShadow = '0 1px 3px rgba(0,0,0,.06)';
    bubble.style.whiteSpace = 'pre-wrap';
    bubble.style.wordBreak = 'break-word';

    if (isMine) {
      bubble.style.background = '#1f2937';
      bubble.style.color = '#fff';
      bubble.style.borderRadius = '12px 12px 3px 12px';
    } else {
      bubble.style.background = '#fff';
      bubble.style.color = '#374151';
      bubble.style.border = '1px solid #e5e7eb';
      bubble.style.borderRadius = '12px 12px 12px 3px';
    }

    bubble.textContent = String(message.content || '');
    row.appendChild(bubble);
    messagesContainer.appendChild(row);
    lastMessageId = Math.max(lastMessageId, Number(message.id || 0));
    scrollToBottom();
  }

  async function sendMessage(event) {
    event.preventDefault();

    const content = input.value.trim();
    if (!content) {
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
  scrollToBottom();
  window.setInterval(pollMessages, 2000);
})();
</script>
<?php endif; ?>
