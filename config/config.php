<?php
// =============================================================
// config/config.php  –  Configuration globale de l'application
// =============================================================

// --- Base de données ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'php_mvc_shop');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// --- Application ---
define('APP_NAME', 'PHP MVC Shop');

// Détecte automatiquement l'environnement :
//   php -S localhost:8000  (depuis le dossier public/)  → http://localhost:8000
//   Apache / XAMPP                                      → ajuster le chemin
$_isBuiltIn = php_sapi_name() === 'cli-server';
define('APP_URL', $_isBuiltIn
    ? 'http://localhost:8000'                    // php -S localhost:8000
    : 'http://localhost/php-mvc-project/public'  // Apache / XAMPP
);

define('APP_ROOT', dirname(__DIR__));   // racine du projet

// --- Sessions ---
define('SESSION_LIFETIME', 3600);       // 1 heure

// --- Sécurité ---
define('BCRYPT_COST', 12);

// --- Mode debug (false en production) ---
define('DEBUG', true);

// --- Démarrage de la session sécurisée (appelé une seule fois ici) ---
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'secure'   => false,   // true si HTTPS
        'httponly' => true,    // inaccessible via JavaScript
        'samesite' => 'Strict',
    ]);
    session_start();
}
