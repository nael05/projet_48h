      <!-- ░░ GRILLE PRINCIPALE ░░ -->
      <main style="max-width:1200px;margin:0 auto;padding:28px 28px;display:grid;grid-template-columns:1fr 380px;gap:24px;width:100%;">

        <!-- ══════════════════════════════════
             COLONNE GAUCHE — FIL
        ══════════════════════════════════ -->
        <div style="display:flex;flex-direction:column;gap:22px;">

          <!-- ── Section : Publier ── -->
          <div class="section-sep">
            <span class="section-title">Publier</span>
            <span class="line"></span>
          </div>

          <!-- Carte de publication -->
          <div class="card" style="padding:20px;">
            <div style="display:flex;gap:14px;">
              <!-- Avatar -->
              <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#d1d5db,#9ca3af);flex-shrink:0;"></div>
              <!-- Textarea + actions -->
              <div style="flex:1;">
                <textarea rows="3" placeholder="Quoi de neuf sur le campus ?"
                  style="width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;padding:12px 14px;font-family:'Roboto',sans-serif;font-size:13.5px;color:#374151;resize:none;outline:none;transition:border-color .2s;"
                  onfocus="this.style.borderColor='#0d9488'" onblur="this.style.borderColor='#e5e7eb'"></textarea>

                <div style="margin-top:12px;display:flex;align-items:center;gap:10px;">
                  <button class="btn-outline-sm">
                    <svg width="14" height="14" fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    + Image
                  </button>
                  <button class="btn-outline-sm">
                    <svg width="14" height="14" fill="none" stroke="#7c3aed" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Compétence
                  </button>
                  <button class="btn-magenta" style="margin-left:auto;">Publier</button>
                </div>
              </div>
            </div>
            <!-- Badge API -->
            <div style="margin-top:14px;padding-top:12px;border-top:1px dashed #fdba74;">
              <span class="badge-api">POST /posts — multer upload</span>
            </div>
          </div>

          <!-- ── Section : Fil d'actualité ── -->
          <div class="section-sep" style="margin-top:8px;">
            <span class="section-title">Fil d'actualité</span>
            <span class="line"></span>
          </div>

          <!-- Post 1 : avec image -->
          <div class="card" style="padding:20px;">
            <!-- Header du post -->
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
              <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#a7f3d0,#6ee7b7);flex-shrink:0;"></div>
                <div>
                  <div class="skel" style="width:120px;height:12px;margin-bottom:6px;"></div>
                  <div class="skel" style="width:75px;height:10px;background:#ede9fe;"></div>
                </div>
              </div>
              <span style="font-size:12px;color:#9ca3af;font-weight:500;">il y a 5 min</span>
            </div>
            <!-- Texte du post -->
            <div style="margin-bottom:14px;">
              <div class="skel" style="width:100%;height:11px;margin-bottom:7px;"></div>
              <div class="skel" style="width:83%;height:11px;"></div>
            </div>
            <!-- Image placeholder -->
            <div style="width:100%;height:220px;background:linear-gradient(135deg,#f0fdf4,#f5f3ff);border:1px solid #e5e7eb;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
              <span style="color:#9ca3af;font-family:'SFMono-Regular',monospace;font-size:13px;letter-spacing:.05em;">[ image ]</span>
            </div>
            <!-- Tags compétences -->
            <div style="display:flex;gap:8px;margin-bottom:14px;">
              <span style="padding:4px 12px;border-radius:99px;border:1px solid rgba(13,148,136,.35);font-size:12px;font-weight:600;color:#0f766e;background:rgba(13,148,136,.05);">React</span>
              <span style="padding:4px 12px;border-radius:99px;border:1px solid rgba(124,58,237,.3);font-size:12px;font-weight:600;color:#6d28d9;background:rgba(124,58,237,.05);">Frontend</span>
            </div>
            <!-- Actions sociales -->
            <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #f3f4f6;">
              <button class="btn-outline-sm">
                <span style="font-size:15px;">👍</span> Like
              </button>
              <button class="btn-outline-sm">
                <span style="font-size:15px;">💬</span> Commenter
              </button>
              <button class="btn-outline-sm" style="margin-left:auto;">Envoyer msg →</button>
            </div>
          </div>

          <!-- Post 2 : texte seul -->
          <div class="card" style="padding:20px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;">
              <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#fbcfe8,#f9a8d4);flex-shrink:0;"></div>
                <div>
                  <div class="skel" style="width:105px;height:12px;margin-bottom:6px;"></div>
                  <div class="skel" style="width:60px;height:10px;"></div>
                </div>
              </div>
              <span style="font-size:12px;color:#9ca3af;font-weight:500;">il y a 1h</span>
            </div>
            <div style="margin-bottom:14px;">
              <div class="skel" style="width:100%;height:11px;margin-bottom:7px;"></div>
              <div class="skel" style="width:75%;height:11px;margin-bottom:7px;"></div>
              <div class="skel" style="width:55%;height:11px;"></div>
            </div>
            <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #f3f4f6;">
              <button class="btn-outline-sm"><span style="font-size:15px;">👍</span> Like</button>
              <button class="btn-outline-sm"><span style="font-size:15px;">💬</span> Commenter</button>
            </div>
          </div>

          <!-- Charger plus + badge -->
          <div style="text-align:center;padding-bottom:8px;">
            <button class="btn-outline-sm" style="padding:10px 30px;font-size:13px;">Charger plus...</button>
            <div style="margin-top:14px;padding-top:12px;border-top:1px dashed #fdba74;text-align:left;">
              <span class="badge-api">GET /feed — pagination cursor</span>
            </div>
          </div>

        </div>


        <!-- ══════════════════════════════════
             COLONNE DROITE — SIDEBAR
        ══════════════════════════════════ -->
        <div style="display:flex;flex-direction:column;gap:24px;">

          <!-- ── News Ynov ── -->
          <div>
            <div class="section-sep">
              <span class="section-title">News Ynov</span>
              <span class="line"></span>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">

              <?php
              $news = [
                ['Challenge 48h',      '31 mars 2026',   '100%'],
                ['Tournoi BDS Foot',   '5 avril 2026',   '70%'],
                ['Soirée BDE Printemps','12 avril 2026', '85%'],
              ];
              foreach ($news as $n): ?>
              <div class="card" style="padding:14px 16px;cursor:pointer;transition:box-shadow .2s;" onmouseenter="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)'" onmouseleave="this.style.boxShadow=''">
                <h4 style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:700;color:#134e4a;margin-bottom:2px;"><?= $n[0] ?></h4>
                <div class="news-progress" style="width:<?= $n[2] ?>"></div>
                <p style="font-size:12px;font-weight:600;color:#be185d;"><?= $n[1] ?></p>
              </div>
              <?php endforeach; ?>

            </div>
            <div style="margin-top:12px;padding-top:10px;border-top:1px dashed #fdba74;">
              <span class="badge-api">GET /news — NewsModel</span>
            </div>
          </div>

          <!-- ── Ymatch ── -->
          <div>
            <div class="section-sep">
              <span class="section-title">Ymatch</span>
              <span class="line"></span>
            </div>
            <div class="card" style="padding:18px;text-align:center;">
              <!-- Logo Ymatch (gradient) -->
              <div style="width:100%;height:56px;border-radius:8px;background:linear-gradient(90deg,#0d9488,#7c3aed,#be185d);display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                <span style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:20px;color:#fff;letter-spacing:-.01em;">Ymatch</span>
              </div>
              <p style="font-size:12.5px;color:#6b7280;margin-bottom:14px;font-family:'Roboto',sans-serif;">Trouve ton prochain projet ou stage 🎯</p>
              <button class="btn-teal" style="width:100%;">Voir les offres +</button>
            </div>
            <div style="margin-top:12px;padding-top:10px;border-top:1px dashed #fdba74;">
              <span class="badge-api">GET /ymatch — offres</span>
            </div>
          </div>

          <!-- ── Suggérés ── -->
          <div>
            <div class="section-sep">
              <span class="section-title">Suggérés</span>
              <span class="line"></span>
            </div>
            <div class="card" style="overflow:hidden;">
              <?php
              $colors = [['#a7f3d0','#34d399'],['#ddd6fe','#a78bfa'],['#fbcfe8','#f472b6']];
              foreach ($colors as $i => $c): ?>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;<?= $i>0?'border-top:1px solid #f3f4f6':'' ?>">
                <div style="display:flex;align-items:center;gap:10px;">
                  <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,<?= $c[0] ?>,<?= $c[1] ?>);flex-shrink:0;"></div>
                  <div class="skel" style="width:<?= [90,70,80][$i] ?>px;height:11px;"></div>
                </div>
                <button class="btn-teal-outline">+ Suivre</button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

        </div>
      </main>
