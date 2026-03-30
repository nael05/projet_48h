  </div><!-- /app -->

  <!-- Chatbot IA Gemini -->
  <div id="ai-chat-btn" class="ai-fab">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" fill="url(#geminiGrad)" />
      <path d="M19.5 16.5L20.5 19.5L23.5 20.5L20.5 21.5L19.5 24.5L18.5 21.5L15.5 20.5L18.5 19.5L19.5 16.5Z" fill="url(#geminiGrad)" />
      <defs>
        <linearGradient id="geminiGrad" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
          <stop stop-color="#ffffff" />
          <stop offset="1" stop-color="#f0f9ff" />
        </linearGradient>
      </defs>
    </svg>
    Demandez à l'IA
  </div>

  <div id="ai-chat-window" class="ai-chat-window hidden">
    <div class="ai-chat-header">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right:10px;">
        <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z" fill="url(#geminiGrad2)" />
        <path d="M19.5 16.5L20.5 19.5L23.5 20.5L20.5 21.5L19.5 24.5L18.5 21.5L15.5 20.5L18.5 19.5L19.5 16.5Z" fill="url(#geminiGrad2)" />
        <defs>
          <linearGradient id="geminiGrad2" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
            <stop stop-color="#3b82f6" />
            <stop offset="1" stop-color="#d946ef" />
          </linearGradient>
        </defs>
      </svg>
      <span style="font-family:'Montserrat', sans-serif; font-weight:700;">Assistant Gemini</span>
      <button id="ai-chat-close" style="margin-left:auto; background:none; border:none; cursor:pointer; color:#6b7280; font-size:22px; line-height:1; display:flex; align-items:center;">&times;</button>
    </div>
    
    <div class="ai-chat-body">
      <div class="ai-message ai-message-bot">
        Bonjour ! 👋<br>Comment puis-je vous aider aujourd'hui ? Je suis l'IA Gemini intégrée à ynovNet.
      </div>
    </div>
    
    <div class="ai-chat-footer">
      <input type="text" id="ai-chat-input" placeholder="Posez une question à l'IA...">
      <button id="ai-chat-send">
        <svg fill="currentColor" viewBox="0 0 20 20" width="16" height="16" style="margin-left:2px;"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
      </button>
    </div>
  </div>

  <style>
    .ai-fab {
      position: fixed;
      bottom: 28px;
      right: 32px;
      background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #d946ef 100%);
      color: white;
      padding: 14px 22px;
      border-radius: 50px;
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      box-shadow: 0 12px 35px rgba(139, 92, 246, 0.4);
      z-index: 1000;
      transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s;
    }
    .ai-fab:hover {
      transform: translateY(-5px) scale(1.03);
      box-shadow: 0 16px 45px rgba(139, 92, 246, 0.55);
    }
    .ai-fab svg {
      animation: sparkle-pulse 3s ease-in-out infinite;
    }
    @keyframes sparkle-pulse {
      0% { transform: scale(1) rotate(0deg); opacity: 0.9; }
      50% { transform: scale(1.15) rotate(10deg); opacity: 1; filter: drop-shadow(0 0 8px rgba(255,255,255,0.8)); }
      100% { transform: scale(1) rotate(0deg); opacity: 0.9; }
    }
    
    .ai-chat-window {
      position: fixed;
      bottom: 95px;
      right: 32px;
      width: 380px;
      height: 540px;
      max-height: calc(100vh - 140px);
      background: linear-gradient(135deg, rgba(250,253,255,0.96) 0%, rgba(250,248,255,0.96) 100%);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      box-shadow: 0 24px 60px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.8) inset;
      border: 1px solid rgba(255,255,255,0.4);
      display: flex;
      flex-direction: column;
      z-index: 999;
      transform-origin: bottom right;
      transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      overflow: hidden;
    }
    .ai-chat-window.hidden {
      opacity: 0;
      pointer-events: none;
      transform: scale(0.8) translateY(40px);
    }
    
    .ai-chat-header {
      padding: 18px 24px;
      background: rgba(255, 255, 255, 0.85);
      border-bottom: 1px solid rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      font-size: 16px;
      color: #111827;
      backdrop-filter: blur(10px);
    }
    
    .ai-chat-body {
      flex: 1;
      padding: 24px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .ai-message {
      max-width: 85%;
      padding: 14px 18px;
      border-radius: 18px;
      font-size: 13.5px;
      line-height: 1.5;
      font-family: 'Roboto', sans-serif;
      box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .ai-message-bot {
      background: linear-gradient(135deg, rgba(59,130,246,0.1) 0%, rgba(217,70,239,0.08) 100%);
      color: #1f2937;
      border-bottom-left-radius: 4px;
      align-self: flex-start;
      border: 1px solid rgba(139,92,246,0.12);
    }
    .ai-message-user {
      background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
      color: white;
      border-bottom-right-radius: 4px;
      align-self: flex-end;
    }
    
    .ai-chat-footer {
      padding: 16px 20px;
      background: rgba(255, 255, 255, 0.85);
      border-top: 1px solid rgba(0,0,0,0.05);
      display: flex;
      gap: 12px;
      backdrop-filter: blur(10px);
    }
    .ai-chat-footer input {
      flex: 1;
      padding: 14px 20px;
      border-radius: 50px;
      border: 1px solid rgba(0,0,0,0.08);
      background: rgba(249, 250, 251, 0.8);
      font-family: 'Roboto', sans-serif;
      font-size: 14px;
      outline: none;
      transition: all 0.25s;
    }
    .ai-chat-footer input:focus {
      border-color: #a855f7;
      background: white;
      box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15);
    }
    .ai-chat-footer button {
      width: 46px;
      height: 46px;
      flex-shrink: 0;
      border-radius: 50px;
      background: linear-gradient(135deg, #3b82f6 0%, #a855f7 100%);
      color: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .ai-chat-footer button:hover {
      transform: scale(1.08);
      box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('ai-chat-btn');
      const win = document.getElementById('ai-chat-window');
      const close = document.getElementById('ai-chat-close');
      const input = document.getElementById('ai-chat-input');
      const send = document.getElementById('ai-chat-send');
      const body = document.querySelector('.ai-chat-body');
      
      const toggleChat = () => {
        win.classList.toggle('hidden');
        if (!win.classList.contains('hidden')) {
          input.focus();
        }
      };
      
      btn.addEventListener('click', toggleChat);
      close.addEventListener('click', toggleChat);
      
      const sendMessage = () => {
        const text = input.value.trim();
        if (!text) return;
        
        // Add user message
        const userMsg = document.createElement('div');
        userMsg.className = 'ai-message ai-message-user';
        userMsg.textContent = text;
        body.appendChild(userMsg);
        
        input.value = '';
        body.scrollTop = body.scrollHeight;
        
        // Indicate loading
        const thinkingMsg = document.createElement('div');
        thinkingMsg.className = 'ai-message ai-message-bot';
        thinkingMsg.innerHTML = '<i>Gemini réfléchit... ✨</i>';
        body.appendChild(thinkingMsg);
        body.scrollTop = body.scrollHeight;
        
        // Mock bot response via fake delay
        setTimeout(() => {
          body.removeChild(thinkingMsg);
          const botMsg = document.createElement('div');
          botMsg.className = 'ai-message ai-message-bot';
          botMsg.textContent = "Ceci est une maquette front-end de la chatbox Gemini. L'API backend traitera votre message: '" + text + "'";
          body.appendChild(botMsg);
          body.scrollTop = body.scrollHeight;
        }, 1200);
      };
      
      send.addEventListener('click', sendMessage);
      input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
      });
    });
  </script>
</body>
</html>
