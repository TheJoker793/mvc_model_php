# PHP MVC Shop 🛒

Projet pédagogique MVC en PHP pur (sans framework) — CRUD complet, sessions sécurisées, rôles Admin/User.

---

## 🗂️ Architecture du projet

```
php-mvc-project/
│
├── app/                          ← Code métier de l'application
│   ├── controllers/              ← Contrôleurs (logique HTTP)
│   │   ├── AuthController.php    ← Login, Register, Logout
│   │   ├── DashboardController.php
│   │   ├── ProductController.php ← CRUD produits
│   │   └── CategoryController.php← CRUD catégories
│   │
│   ├── models/                   ← Accès base de données
│   │   ├── User.php
│   │   ├── Product.php
│   │   └── Category.php
│   │
│   └── views/                    ← Templates HTML (PHP)
│       ├── layouts/
│       │   ├── main.php          ← Layout avec sidebar (pages connectées)
│       │   └── auth.php          ← Layout centré (login / register)
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── dashboard/
│       │   └── index.php
│       ├── products/
│       │   ├── index.php         ← Liste + filtres + recherche
│       │   ├── show.php          ← Détail
│       │   └── form.php          ← Création / Édition
│       ├── categories/
│       │   ├── index.php
│       │   └── form.php
│       └── errors/
│           └── 404.php
│
├── config/
│   └── config.php                ← Constantes DB, APP_URL, session…
│
├── core/                         ← Framework maison
│   ├── Autoloader.php            ← PSR-4 sans Composer
│   ├── Database.php              ← Connexion PDO (Singleton)
│   ├── Model.php                 ← CRUD générique (classe abstraite)
│   ├── Controller.php            ← render(), redirect(), flash(), CSRF…
│   ├── Router.php                ← Routeur simple avec paramètres (:id)
│   └── helpers.php               ← e(), url(), slugify(), isAdmin()…
│
├── database/
│   └── database.sql              ← Script SQL complet (tables + données)
│
└── public/                       ← Seul dossier exposé au web
    ├── index.php                 ← Front Controller (point d'entrée)
    ├── .htaccess                 ← Réécriture URL + headers sécurité
    └── assets/
        ├── css/app.css           ← Styles personnalisés
        └── js/app.js             ← Scripts JS (alertes, slugify…)
```

---

## 🚀 Installation

### 1. Prérequis
- PHP 8.1+
- MySQL 5.7+ ou MariaDB
- Apache avec `mod_rewrite` activé (ou Nginx configuré)

### 2. Cloner / déposer le projet
```bash
# Dans le dossier htdocs (XAMPP) ou www (WAMP / Laragon)
cp -r php-mvc-project/ /chemin/vers/htdocs/
```

### 3. Créer la base de données
```bash
mysql -u root -p < database/database.sql
```
Ou importer le fichier via **phpMyAdmin**.

### 4. Configurer
Éditer `config/config.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'php_mvc_shop');
define('DB_USER', 'root');
define('DB_PASS', '');              // votre mot de passe MySQL
define('APP_URL',  'http://localhost/php-mvc-project/public');
```

### 5. Tester
Ouvrir : **http://localhost/php-mvc-project/public**

---

## 🔐 Comptes de test

| Rôle  | Email               | Mot de passe |
|-------|---------------------|--------------|
| Admin | admin@shop.com      | password     |
| User  | user@shop.com       | password     |

---

## 🔒 Sécurité implémentée

| Menace          | Protection                                              |
|-----------------|---------------------------------------------------------|
| SQL Injection   | PDO avec requêtes préparées uniquement                  |
| XSS             | `htmlspecialchars()` via `e()` sur toutes les sorties   |
| CSRF            | Token aléatoire en session vérifié à chaque POST        |
| Session Hijack  | `session_regenerate_id(true)` après connexion           |
| Session Fixation| Même mécanisme ci-dessus                                |
| Passwords       | `password_hash()` bcrypt cost=12                        |
| Clickjacking    | `X-Frame-Options: SAMEORIGIN` via .htaccess             |
| MIME Sniffing   | `X-Content-Type-Options: nosniff`                       |
| Accès direct    | `Options -Indexes` dans .htaccess                       |
| Privilege Escal.| `requireAdmin()` dans chaque action sensible            |

---

## 🛣️ Routes disponibles

### Auth
| Méthode | URL              | Action                  |
|---------|------------------|-------------------------|
| GET     | /auth/login      | Formulaire connexion    |
| POST    | /auth/login      | Traitement connexion    |
| GET     | /auth/register   | Formulaire inscription  |
| POST    | /auth/register   | Traitement inscription  |
| GET     | /auth/logout     | Déconnexion             |

### Dashboard
| Méthode | URL         | Action          |
|---------|-------------|-----------------|
| GET     | /dashboard  | Tableau de bord |

### Produits (admin = CRUD complet, user = lecture)
| Méthode | URL                     | Action          |
|---------|-------------------------|-----------------|
| GET     | /products               | Liste + filtres |
| GET     | /products/show/:id      | Détail          |
| GET     | /products/create        | Formulaire      |
| POST    | /products/create        | Création        |
| GET     | /products/edit/:id      | Formulaire      |
| POST    | /products/edit/:id      | Mise à jour     |
| POST    | /products/delete/:id    | Suppression     |

### Catégories (identique)
| Méthode | URL                        | Action      |
|---------|----------------------------|-------------|
| GET     | /categories                | Liste       |
| GET/POST| /categories/create         | Création    |
| GET/POST| /categories/edit/:id       | Édition     |
| POST    | /categories/delete/:id     | Suppression |

---

## 📐 Concepts MVC illustrés

```
Requête HTTP
    │
    ▼
public/index.php  ──► Router.dispatch()
    │
    ▼
Controller  ──► Model (PDO, requêtes préparées)
    │               │
    │               ▼
    │           Données (array)
    │
    ▼
View (PHP template)
    │
    ▼
Layout (sidebar, topbar, flash)
    │
    ▼
Réponse HTML
```

---

## 💡 Pour aller plus loin

1. **Upload d'images** : ajouter `move_uploaded_file()` dans `ProductController`
2. **Pagination** : ajouter `LIMIT / OFFSET` dans `Model::findAll()`
3. **API REST** : ajouter un layout `json.php` qui retourne `Content-Type: application/json`
4. **Composer** : remplacer `Autoloader.php` par l'autoloader Composer standard
5. **Tests** : ajouter PHPUnit pour tester les modèles
