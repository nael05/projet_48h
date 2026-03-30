  <div style="flex:1; padding:24px;">
    <main style="max-width:1200px;margin:0 auto;padding:28px;background:var(--container-gradient);backdrop-filter:blur(10px);border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,0.15);display:grid;grid-template-columns:1fr 360px;gap:24px;">

      <!-- ══ COLONNE GAUCHE ══ -->
      <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Publier -->
        <div class="section-header">
          <h2>Publier</h2><span class="sep"></span>
        </div>
        <div class="card" style="padding:20px;">
          <div style="display:flex;gap:14px;">
            <div style="width:46px;height:46px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
            <div style="flex:1;">
              <textarea rows="3" placeholder="Quoi de neuf sur le campus ?"
                style="width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:6px;padding:12px;font-family:'Roboto',sans-serif;font-size:13.5px;color:#374151;resize:none;outline:none;transition:border-color .2s;"
                onfocus="this.style.borderColor='#374151'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
              <div style="margin-top:10px;display:flex;align-items:center;gap:8px;">
                <button class="btn-outline">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                  + Image
                </button>
                <button class="btn-outline">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                  Compétence
                </button>
                <button class="btn-dark" style="margin-left:auto;">Publier</button>
              </div>
            </div>
          </div>
          <div style="margin-top:14px;padding-top:12px;border-top:1px dashed var(--gray-400);">
            <span class="badge-api">POST /posts — multer upload</span>
          </div>
        </div>

        <!-- Fil d'actualité -->
        <div class="section-header" style="margin-top:6px;">
          <h2>Fil d'actualité</h2><span class="sep"></span>
        </div>

        <!-- Post 1 -->
        <div class="card" style="padding:20px;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
              <div><div class="skel" style="width:120px;height:11px;margin-bottom:6px;"></div><div class="skel" style="width:75px;height:9px;"></div></div>
            </div>
            <span style="font-size:11.5px;color:#9ca3af;font-weight:500;">il y a 5 min</span>
          </div>
          <div style="margin-bottom:14px;"><div class="skel" style="width:100%;height:10px;margin-bottom:7px;"></div><div class="skel" style="width:82%;height:10px;"></div></div>
          <div style="width:100%;height:210px;background:#e5e7eb;border:1px solid #d1d5db;border-radius:6px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
            <span style="color:#9ca3af;font-family:'SFMono-Regular',monospace;font-size:12px;letter-spacing:.05em;">[ image ]</span>
          </div>
          <div style="display:flex;gap:8px;margin-bottom:14px;">
            <span style="padding:3px 12px;border-radius:99px;border:1px solid #d1d5db;font-size:11.5px;color:#374151;background:#f9fafb;font-weight:600;">React</span>
            <span style="padding:3px 12px;border-radius:99px;border:1px solid #d1d5db;font-size:11.5px;color:#374151;background:#f9fafb;font-weight:600;">Frontend</span>
          </div>
          <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #f3f4f6;">
            <button class="btn-secondary"><span style="font-size:14px;">👍</span> Like</button>
            <button class="btn-secondary"><span style="font-size:14px;">💬</span> Commenter</button>
            <button class="btn-outline" style="margin-left:auto;">Envoyer msg →</button>
          </div>
        </div>

        <!-- Post 2 -->
        <div class="card" style="padding:20px;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:42px;height:42px;border-radius:50%;background:#d1d5db;flex-shrink:0;"></div>
              <div><div class="skel" style="width:100px;height:11px;margin-bottom:6px;"></div><div class="skel" style="width:60px;height:9px;"></div></div>
            </div>
            <span style="font-size:11.5px;color:#9ca3af;font-weight:500;">il y a 1h</span>
          </div>
          <div style="margin-bottom:14px;"><div class="skel" style="width:100%;height:10px;margin-bottom:7px;"></div><div class="skel" style="width:70%;height:10px;margin-bottom:7px;"></div><div class="skel" style="width:55%;height:10px;"></div></div>
          <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #f3f4f6;">
            <button class="btn-secondary"><span style="font-size:14px;">👍</span> Like</button>
            <button class="btn-secondary"><span style="font-size:14px;">💬</span> Commenter</button>
          </div>
        </div>

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
