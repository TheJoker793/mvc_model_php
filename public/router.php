<?php
// =============================================================
// public/router.php  –  Router file pour "php -S localhost:8000"
//
// Usage (depuis le dossier public/) :
//   php -S localhost:8000 router.php
//
// Ce fichier indique au serveur built-in PHP de :
//   - servir directement les fichiers statiques existants (CSS, JS, images…)
//   - rediriger TOUT le reste vers index.php (le Front Controller)
// =============================================================

// Si le fichier demandé existe réellement dans public/ (asset statique),
// retourner false = PHP le sert directement
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    // Retire la query string avant de tester l'existence du fichier
    $filePath = strtok($file, '?');
    if (is_file($filePath)) {
        return false;   // PHP sert le fichier statique tel quel
    }
}

// Sinon, tout passe par le Front Controller
require_once __DIR__ . '/index.php';
