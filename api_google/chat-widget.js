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
            input.focus();
        }
    };

    btn.addEventListener('click', toggleChat);
    close.addEventListener('click', toggleChat);

    async function sendChat() {
        const userMessage = input.value.trim();
        const pageContent = document.body.innerText;

        if (!userMessage) return;

        const userDiv = document.createElement('div');
        userDiv.className = 'ai-message ai-message-user';
        userDiv.textContent = userMessage;
        body.appendChild(userDiv);
        
        input.value = '';
        body.scrollTop = body.scrollHeight;

        const loadingId = 'loading-' + Date.now();
        const thinkingDiv = document.createElement('div');
        thinkingDiv.className = 'ai-message ai-message-bot';
        thinkingDiv.id = loadingId;
        thinkingDiv.innerHTML = '<i>Gemini réfléchit... ✨</i>';
        body.appendChild(thinkingDiv);
        body.scrollTop = body.scrollHeight;

        const promptWithContext = "Texte de la page web actuelle : \n" + pageContent + "\n\nRéponds à cette question de l'utilisateur de façon concise : " + userMessage;

        try {
            const response = await fetch('/projet_48h/api_google/api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: promptWithContext })
            });

            const data = await response.json();
            
            document.getElementById(loadingId).remove();

            const botDiv = document.createElement('div');
            botDiv.className = 'ai-message ai-message-bot';

            if (data.candidates && data.candidates[0].content.parts[0].text) {
                botDiv.textContent = data.candidates[0].content.parts[0].text;
            } else {
                botDiv.textContent = "Désolé, je rencontre une petite difficulté technique.";
            }
            
            body.appendChild(botDiv);

        } catch (error) {
            document.getElementById(loadingId).remove();
            const errDiv = document.createElement('div');
            errDiv.className = 'ai-message ai-message-bot';
            errDiv.textContent = "Erreur de connexion au serveur.";
            body.appendChild(errDiv);
        }

        body.scrollTop = body.scrollHeight;
    }

    send.addEventListener('click', sendChat);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendChat();
    });
});