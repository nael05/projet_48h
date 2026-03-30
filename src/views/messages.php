  <div style="flex:1; padding:24px; display:flex; flex-direction:column; align-items:center;">
    <div style="width:100%; max-width:1200px; flex:1; display:flex; background:var(--container-gradient); backdrop-filter:blur(12px); border-radius:16px; overflow:hidden; box-shadow:0 12px 40px rgba(0,0,0,0.15); border:1px solid rgba(255,255,255,0.4);">

    <!-- ══ CONTACTS (gauche) ══ -->
    <div style="width:320px;flex-shrink:0;border-right:1px solid rgba(0,0,0,0.1);display:flex;flex-direction:column;background:transparent;">

      <!-- Search -->
      <div style="padding:16px;border-bottom:1px solid #e5e7eb;">
        <h2 style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#111827;margin-bottom:10px;">Messages</h2>
        <div style="position:relative;">
          <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;fill:none;stroke:#9ca3af;stroke-width:2;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
          <input type="text" placeholder="Rechercher une conv..."
            style="width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:6px;padding:8px 12px 8px 32px;font-family:'Roboto',sans-serif;font-size:13px;color:#374151;outline:none;transition:border-color .2s;"
            onfocus="this.style.borderColor='#374151'" onblur="this.style.borderColor='#e5e7eb'">
        </div>
      </div>

      <!-- Liste contacts -->
      <div style="flex:1;overflow-y:auto;">
        <!-- Alice : non-lu + accent border -->
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-left:3px solid #1f2937;background:#f9fafb;border-bottom:1px solid #f3f4f6;cursor:pointer;">
          <div style="position:relative;flex-shrink:0;">
            <div style="width:42px;height:42px;border-radius:50%;background:#d1d5db;"></div>
            <span style="position:absolute;bottom:1px;right:1px;width:10px;height:10px;border-radius:50%;background:#22c55e;border:2px solid #fff;"></span>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:3px;">
              <span style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:700;color:#111827;">Alice Martin</span>
              <span style="font-size:11px;color:#6b7280;font-weight:500;">14h32</span>
            </div>
            <p style="font-size:12px;color:#374151;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Tu peux m'aider sur le TP?</p>
          </div>
        </div>

        <?php foreach ([['Bob Durand','Hier'],['Clara Chen','Lun'],['David Roy','']] as $c): ?>
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-left:3px solid transparent;border-bottom:1px solid #f3f4f6;cursor:pointer;transition:background .15s;" onmouseenter="this.style.background='#f9fafb'" onmouseleave="this.style.background='#fff'">
          <div style="width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
          <div style="flex:1;min-width:0;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:3px;">
              <span style="font-family:'Montserrat',sans-serif;font-size:13px;font-weight:600;color:#374151;"><?= $c[0] ?></span>
              <span style="font-size:11px;color:#9ca3af;"><?= $c[1] ?></span>
            </div>
            <div style="height:8px;background:#e5e7eb;border-radius:3px;width:70%;"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Badge API -->
      <div style="padding:12px 16px;border-top:1px dashed var(--gray-400);background:transparent;">
        <span class="badge-api">GET /messages — polling 3s</span>
      </div>
    </div>

    <!-- ══ CONVERSATION (droite) ══ -->
    <div style="flex:1;display:flex;flex-direction:column;background:rgba(255,255,255,0.4);">

      <!-- Header conv -->
      <div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;">
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:40px;height:40px;border-radius:50%;background:#d1d5db;"></div>
          <div>
            <p style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#111827;margin-bottom:2px;">Alice Martin</p>
            <p style="font-size:12px;color:#22c55e;font-weight:600;display:flex;align-items:center;gap:5px;">
              <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;"></span>En ligne
            </p>
          </div>
        </div>
        <button class="btn-outline" style="font-size:12.5px;padding:7px 16px;">Voir le profil →</button>
      </div>

      <!-- Messages -->
      <div style="flex:1;overflow-y:auto;padding:24px 28px;display:flex;flex-direction:column;gap:12px;">
        <div style="text-align:center;margin-bottom:8px;">
          <span style="font-size:11.5px;color:#9ca3af;font-weight:600;background:#e5e7eb;padding:4px 14px;border-radius:99px;">Aujourd'hui 14h20</span>
        </div>

        <!-- Reçu -->
        <div style="display:flex;justify-content:flex-start;">
          <div style="background:#fff;border:1px solid #e5e7eb;color:#374151;padding:10px 16px;border-radius:12px 12px 12px 3px;font-size:13.5px;max-width:60%;box-shadow:0 1px 3px rgba(0,0,0,.06);">Salut ! T'as avancé sur le projet ?</div>
        </div>
        <!-- Envoyé -->
        <div style="display:flex;justify-content:flex-end;">
          <div style="background:#1f2937;color:#fff;padding:10px 16px;border-radius:12px 12px 3px 12px;font-size:13.5px;max-width:60%;">Oui, j'ai fini les models hier soir !</div>
        </div>
        <!-- Reçu -->
        <div style="display:flex;justify-content:flex-start;">
          <div style="background:#fff;border:1px solid #e5e7eb;color:#374151;padding:10px 16px;border-radius:12px 12px 12px 3px;font-size:13.5px;max-width:60%;box-shadow:0 1px 3px rgba(0,0,0,.06);">Super, tu peux m'aider sur le TP Node ?</div>
        </div>
        <!-- Envoyé -->
        <div style="display:flex;justify-content:flex-end;">
          <div style="background:#1f2937;color:#fff;padding:10px 16px;border-radius:12px 12px 3px 12px;font-size:13.5px;max-width:60%;">Bien sûr, on se retrouve à 16h ?</div>
        </div>
        <!-- Reçu -->
        <div style="display:flex;justify-content:flex-start;">
          <div style="background:#fff;border:1px solid #e5e7eb;color:#374151;padding:10px 16px;border-radius:12px 12px 12px 3px;font-size:13.5px;max-width:60%;box-shadow:0 1px 3px rgba(0,0,0,.06);">Parfait, à tout à l'heure 🔥</div>
        </div>
        <div style="text-align:right;font-size:11px;color:#9ca3af;font-weight:500;padding-right:2px;">Lu ✓</div>
      </div>

      </div>

      <!-- Input -->
      <div style="background:transparent;border-top:1px solid rgba(0,0,0,0.1);padding:14px 20px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <button style="width:36px;height:36px;border:1.5px solid #e5e7eb;border-radius:6px;background:#f9fafb;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:border-color .2s;" onmouseenter="this.style.borderColor='#374151'" onmouseleave="this.style.borderColor='#e5e7eb'">
            <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
          </button>
          <input type="text" placeholder="Écrire un message..."
            style="flex:1;border:1.5px solid #e5e7eb;border-radius:6px;padding:10px 14px;font-family:'Roboto',sans-serif;font-size:13.5px;color:#374151;background:#f9fafb;outline:none;transition:border-color .2s;"
            onfocus="this.style.borderColor='#374151'" onblur="this.style.borderColor='#e5e7eb'">
          <button class="btn-dark" style="flex-shrink:0;">Envoyer</button>
        </div>
        <div style="margin-top:10px;">
          <span class="badge-api">POST /messages — MessageModel.send()</span>
        </div>
      </div>

    </div>
  </div>
</div>
