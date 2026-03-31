document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('ai-chat-btn');
    const win = document.getElementById('ai-chat-window');
    const close = document.getElementById('ai-chat-close');
    const input = document.getElementById('ai-chat-input');
    const send = document.getElementById('ai-chat-send');
    const body = document.getElementById('ai-chat-body');

    const toggleChat = () => {
        win.classList.toggle('hidden');
        if (!win.classList.contains('hidden')) {
            setTimeout(() => input.focus(), 300);
        }
    };

    btn.addEventListener('click', toggleChat);
    close.addEventListener('click', toggleChat);

    const appendMessage = (text, isBot = true, isLoading = false) => {
        const msgDiv = document.createElement('div');
        msgDiv.className = `ai-msg ${isBot ? 'ai-msg-bot' : 'ai-msg-user'}`;
        
        if (isLoading) {
            msgDiv.innerHTML = `<div class="ai-thinking"><div class="ai-dot"></div><div class="ai-dot"></div><div class="ai-dot"></div></div>`;
        } else {
            msgDiv.textContent = text;
        }
        
        body.appendChild(msgDiv);
        body.scrollTop = body.scrollHeight;
        return msgDiv;
    };

    async function sendChat() {
        const userMessage = input.value.trim();
        if (!userMessage) return;

        appendMessage(userMessage, false);
        input.value = '';
        
        const loader = appendMessage('', true, true);
        
        // Get context from current page
        const pageTitle = document.title;
        const pageContent = document.body.innerText.substring(0, 500); // Send only context to save tokens

        const promptWithContext = `CONTEXTE PAGE : ${pageTitle} | ${pageContent}\n\nQUESTION : ${userMessage}`;

        try {
            const endpoint = (window.aiBasePath || '') + '/api-handler.php';
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: promptWithContext })
            });

            const data = await response.json();
            loader.remove();

            let botText = "Désolé, je rencontre une petite difficulté technique.";
            if (data.candidates && data.candidates[0].content.parts[0].text) {
                botText = data.candidates[0].content.parts[0].text;
            } else if (data.error) {
                botText = "Erreur : " + data.error;
            }
            
            appendMessage(botText);

        } catch (error) {
            loader.remove();
            appendMessage("Erreur de connexion au serveur.");
        }
    }

    send.addEventListener('click', sendChat);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendChat();
    });
});
