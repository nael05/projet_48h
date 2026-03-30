# Guide d'Intégration du Chatbot IA

Ce dossier contient tout le nécessaire pour ajouter un widget de chat intelligent sur une page du site. L'IA scanne automatiquement le texte de la page active pour répondre aux questions si besoin.

## 📂 Contenu du dossier
* **api.php** : Gère la connexion avec Google Gemini 2.5 Flash.
* **chat-widget.html** : Structure du widget (le fichier à inclure).
* **chat-widget.css** : Design et positionnement (bulle noire en bas à droite).
* **chat-widget.js** : Capture le texte de la page et gère l'envoi des messages.

---

## 🚀 Comment l'intégrer ?

Pour afficher le chatbot sur n'importe laquelle de tes pages PHP, ajoute simplement cette ligne tout en bas de ton fichier, juste avant la balise de fermeture `</body>` :

```php
<?php include 'api_google/chat-widget.html'; ?>