# 🇲🇦 Darija Translator API (LLM-Powered)

Ce projet est un service web RESTful capable de traduire du texte anglais vers le dialecte marocain (Darija). Il utilise l'intelligence artificielle via l'API Groq (modèle Llama 3) pour garantir des traductions naturelles et rapides.

---

## 🚀 Fonctionnalités

### API REST JAX-RS
* Point d'entrée sécurisé pour la traduction
* Architecture RESTful optimisée
* Réponses en JSON structurées

### Traduction par LLM
* Intégration avec **Groq** (llama-3.1-8b-instant)
* Traduction contextuelle et naturelle
* Support multilingue (Anglais → Darija)

### Extension Chrome (Manifest V3)
* Utilisation de l'API `sidePanel` pour traduire sans quitter l'onglet courant
* Interface fluide et moderne
* Intégration directe au navigateur

### Interface Moderne
* Design épuré en HTML/CSS
* Utilisation fluide et intuitive
* Responsive et accessible

### Format de sortie
* Traduction uniquement en alphabet latin (style chat/français)
* Format optimisé pour la lisibilité

---

## 📂 Structure du Projet

```
DarijaTranslatorAPI/
├── src/main/java/org/example/darijatranslatorapi/
│   ├── RestApplication.java          # Configuration JAX-RS (/api)
│   ├── TranslatorResource.java       # Logique métier et appel Groq API
│   ├──TranslationRequest.java       # Modèle de données JSON
│   └── CorsFilter.java
│
├── src/main/webapp/
│   └── WEB-INF/
│       └── web.xml                   # Configuration du déploiement
│
├── chrome-extension/
│   ├── manifest.json                 # Configuration V3
│   ├── sidepanel.html                # Interface de l'extension
│   └── sidepanel.js                  # Logique d'appel Fetch
│
├── php-client/
│   └── index.php                     # Site web PHP (XAMPP)
│
├── pom.xml                           # Dépendances Maven (Jakarta EE 10)
└── README.md                         # Cette documentation
```

---

## 🛠️ Installation et Exécution

### 1️⃣ Prérequis

* **Java** 21 ou supérieur
* **Maven** 3.8+
* **WildFly** 30+ (ou tout conteneur compatible Jakarta EE 10)
* **Une clé API Groq** (disponible sur [console.groq.com](https://console.groq.com))
* **PHP 8.0+** avec XAMPP Control Panel v3.3.0 (pour le site web)

### 2️⃣ Backend (Java)

#### Étape A: Configuration

1. Ouvre le projet dans **IntelliJ IDEA** ou **Eclipse**
2. Dans `TranslatorResource.java`, remplace `GROQ_API_KEY` par ta propre clé:

```java
private static final String GROQ_API_KEY = "gsk_YOUR_API_KEY_HERE";
```

#### Étape B: Déploiement

1. Copie le fichier `.war` généré dans le répertoire `standalone/deployments/` de WildFly
2. Redémarre le serveur WildFly
3. Vérifie que le serveur tourne sur: `http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/`

### 3️⃣ Client Web (PHP)

#### Étape A: Configuration XAMPP

1. Télécharge et installe **XAMPP Control Panel v3.3.0**
2. Démarre **Apache** et **MySQL** depuis le panneau de contrôle

#### Étape B: Installation

1. Copie le dossier `php-client` dans `C:\xampp\htdocs\` (ou votre répertoire `htdocs`)
2. Accède au site: `http://localhost/php-client/index.php`

#### Étape C: Configuration API

Dans `php-client/index.php`, assure-toi que l'URL de l'API est correcte:

```php
$api_url = "http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/api/translator";
```

### 4️⃣ Extension Chrome

1. Ouvre **Chrome** et va sur `chrome://extensions/`
2. Active le **Mode développeur** (en haut à droite)
3. Clique sur **Charger l'extension décompressée**
4. Sélectionne le dossier `chrome-extension` de ce projet
5. Clique sur l'icône de l'extension et sélectionne **"Ouvrir le panneau latéral"**

---

## 🧪 Tests de l'API

### Avec Postman

1. Ouvre **Postman**
2. Crée une nouvelle requête **POST**
3. URL: `http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/api/translator`
4. Headers: `Content-Type: application/json`
5. Body (raw JSON):
```json
{
  "text": "Hello world",
  "translation": ""
}
```
6. Clique sur **Send**

### Réponse attendue

```json
{
  "text": "Hello world",
  "translation": "Salam labas"
}
```

---

## 🔒 Sécurité

### Configuration Actuelle

L'API fonctionne actuellement **SANS authentification** pour faciliter le développement et les tests.

⚠️ **Pour un déploiement en production**, activez l'authentification Basic ci-dessous.

### Activation (Optionnel - Production seulement)

1. Modifie `src/main/webapp/WEB-INF/web.xml` pour protéger l'endpoint:

```xml
<security-constraint>
    <web-resource-collection>
        <web-resource-name>API Translator</web-resource-name>
        <url-pattern>/api/translator</url-pattern>
        <http-method>POST</http-method>
    </web-resource-collection>
    <auth-constraint>
        <role-name>user</role-name>
    </auth-constraint>
</security-constraint>

<login-config>
    <auth-method>BASIC</auth-method>
    <realm-name>DarijaRealm</realm-name>
</login-config>
```

2. Ajoute l'en-tête `Authorization` dans tes clients (PHP, JavaScript):

**PHP:**
```php
$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json\r\nAuthorization: Basic ' . base64_encode('username:password'),
        'content' => json_encode(['text' => $text, 'translation' => ''])
    ]
];
```

**JavaScript (Chrome Extension):**
```javascript
const headers = {
  'Content-Type': 'application/json',
  'Authorization': 'Basic ' + btoa('username:password')
};
```

---

## 📋 Architecture Technique

### Backend Stack
* **Framework**: Jakarta EE 10 (JAX-RS)
* **Serveur**: WildFly 30+
* **Build**: Maven 3.8+
* **Langage**: Java 21
* **LLM**: Groq API (Llama 3)

### Frontend Stack
* **Extension**: Chrome Manifest V3
* **Client Web**: PHP 8.0+ avec XAMPP
* **API Web**: JavaScript (Vanilla) + HTML5 + CSS3
* **Style**: Bootstrap ou CSS personnalisé

### Communication
* **Protocole**: REST HTTP/HTTPS
* **Format**: JSON
* **Authentification**: Jakarta Basic Auth

---

## 🚦 Flux de Traduction

```
Utilisateur tape du texte
        ↓
Client (Extension/PHP/Web) capture le texte
        ↓
fetch() ou cURL appelle POST /api/translator
        ↓
JAX-RS route vers TranslatorResource.translate()
        ↓
Appel API Groq avec le texte anglais
        ↓
Groq retourne la traduction Darija
        ↓
Réponse JSON retournée au client
        ↓
Affichage de la traduction
```

---

## 🛠️ Dépannage

### Erreur: API Key invalide
* Vérifiez que vous avez copié la clé correctement depuis [console.groq.com](https://console.groq.com)
* Assurez-vous que la clé n'a pas d'espaces au début/fin

### Erreur: Extension non chargée
* Assurez-vous que le `manifest.json` est au bon format
* Vérifiez que le dossier `chrome-extension` contient tous les fichiers requis
* Rechargez l'extension avec `Ctrl+Shift+R`

### Erreur 404 sur l'API
* Vérifiez que WildFly est en cours d'exécution
* Vérifiez l'URL: `http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/api/translator`
* Vérifiez que le WAR est déployé dans `standalone/deployments/`

### Le site PHP ne se charge pas
* Vérifiez que Apache est démarré dans XAMPP Control Panel
* Vérifiez que le dossier `php-client` est dans `C:\xampp\htdocs\`
* Vérifiez l'URL: `http://localhost/php-client/index.php`

### Extension/PHP ne communique pas avec l'API
* Vérifiez le CORS dans `TranslatorResource.java`
* Ouvrez la console (F12) pour voir les erreurs
* Vérifiez que l'API est accessible: `curl http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/api/test`
* Vérifiez les credentials Basic Auth si l'authentification est activée

---

## Vidéo Explicative
  https://drive.google.com/file/d/1DltQ3W2xifa5n-TzZAjNg6RKpSfyXiIZ/view?usp=sharing
---

## 👤 Auteur

**BEN FARES Mohamed**

---
