function toggleChat() {
    const chatWindow = document.getElementById('chat-widget-window');
    if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
        chatWindow.style.display = 'flex';
    } else {
        chatWindow.style.display = 'none';
    }
}

async function sendChat() {
    const inputField = document.getElementById('chat-widget-input');
    const userMessage = inputField.value;
    const messagesArea = document.getElementById('chat-widget-messages');
    const pageContent = document.body.innerText;

    if (!userMessage.trim()) return;

    messagesArea.innerHTML += `<div class="msg-user">${userMessage}</div>`;
    inputField.value = '';
    messagesArea.scrollTop = messagesArea.scrollHeight;

    const promptWithContext = "Texte de la page web actuelle : \n" + pageContent + "\n\nRéponds à cette question de l'utilisateur de façon concise : " + userMessage;

    const loadingId = 'loading-' + Date.now();
    messagesArea.innerHTML += `<div class="msg-ai" id="${loadingId}">Réflexion en cours...</div>`;
    messagesArea.scrollTop = messagesArea.scrollHeight;

    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: promptWithContext })
        });

        const data = await response.json();
        console.log(data);
        
        document.getElementById(loadingId).remove();
        
        if (data.candidates && data.candidates[0].content.parts[0].text) {
            const aiText = data.candidates[0].content.parts[0].text;
            messagesArea.innerHTML += `<div class="msg-ai">${aiText}</div>`;
        } else if (data.error) {
            messagesArea.innerHTML += `<div class="msg-ai" style="color: red;"><strong>Erreur Google :</strong> ${data.error.message}</div>`;
        } else {
            messagesArea.innerHTML += `<div class="msg-ai">Erreur lors de la génération.</div>`;
        }
    } catch (error) {
        document.getElementById(loadingId).remove();
        messagesArea.innerHTML += `<div class="msg-ai">Erreur de communication avec le serveur.</div>`;
    }

    messagesArea.scrollTop = messagesArea.scrollHeight;
}

document.getElementById('chat-widget-input').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        sendChat();
    }
});