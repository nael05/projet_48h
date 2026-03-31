# Projet 48H — Réseau Social Ynov

Réseau social interne destiné aux étudiants et personnels Ynov. Seules les adresses `@ynov.com` sont autorisées à s'inscrire.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Serveur | Apache (XAMPP) + PHP 8.x |
| Base de données | MySQL 8.x (via phpMyAdmin) |
| Architecture | MVC custom (sans framework) |
| Chatbot IA | Google Gemini 2.5 Flash |
| CSS | Vanilla CSS |

---

## Prérequis

- [XAMPP](https://www.apachefriends.org/) avec **Apache** et **MySQL** démarrés
- Module Apache **`mod_rewrite`** activé (voir ci-dessous)
- PHP 8.0 minimum

---

## Installation

### 1. Cloner le projet

Placer le dossier dans `C:\xampp\htdocs\` :

```
C:\xampp\htdocs\projet_48h\
```

### 2. Activer mod_rewrite

Ouvrir `C:\xampp\apache\conf\httpd.conf` et décommenter la ligne suivante (retirer le `#`) :

```
LoadModule rewrite_module modules/mod_rewrite.so
```

Chercher également le bloc `<Directory "C:/xampp/htdocs">` et s'assurer que `AllowOverride` est bien sur `All` :

```
AllowOverride All
```

Redémarrer Apache depuis le panneau XAMPP.

### 3. Créer la base de données

1. Aller sur `http://localhost/phpmyadmin`
2. Créer une base de données nommée **`projet_48h`** (collation : `utf8mb4_unicode_ci`)
3. Sélectionner la base, aller dans l'onglet **SQL** et importer dans l'ordre :
   - `database/schema.sql`
   - `database/seed.sql`

### 4. Configurer l'API Google Gemini

Le fichier `api_google/config.php` est ignoré par Git. Le créer manuellement :

```php
<?php
return [
    'GEMINI_API_KEY' => 'VOTRE_CLE_API',
    'GEMINI_API_URL' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent'
];
```

> La clé API est disponible sur [Google AI Studio](https://aistudio.google.com/).

---

## Lancer le projet

Accéder à l'URL suivante dans un navigateur :

```
http://localhost/projet_48h/public/
```

> ⚠️ Ne pas accéder via `http://localhost/projet_48h/` directement, le point d'entrée est `public/index.php`.

---

## Structure du projet

```
projet_48h/
├── api_google/          # Widget chatbot Google Gemini
│   ├── api.php
│   ├── config.php       # ⚠️ À créer manuellement (gitignored)
│   ├── chat-widget.html
│   ├── chat-widget.css
│   └── chat-widget.js
├── database/
│   ├── schema.sql       # Structure des tables
│   └── seed.sql         # Données initiales
├── public/              # Point d'entrée web
│   ├── index.php        # Front controller
│   ├── .htaccess        # Réécriture d'URL
│   └── assets/          # CSS, JS, images
├── src/
│   ├── controllers/     # AuthController, FeedController, etc.
│   ├── core/            # Router
│   ├── models/
│   ├── services/
│   └── views/           # Templates PHP (auth, feed, profile, messages)
└── config.php           # ⚠️ Vide — non utilisé (gitignored)
```

---

## Routes disponibles

| URL | Description |
|---|---|
| `/projet_48h/public/login` | Connexion |
| `/projet_48h/public/register` | Inscription |
| `/projet_48h/public/feed` | Fil d'actualité |
| `/projet_48h/public/profile` | Profil utilisateur |
| `/projet_48h/public/messages` | Messagerie |

---

## Règles métier

