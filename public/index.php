<?php
// =============================================================
// public/index.php  –  Front Controller (point d'entrée unique)
// =============================================================

declare(strict_types=1);

// --- Chargement de la configuration (démarre aussi la session) ---
require_once dirname(__DIR__) . '/config/config.php';

// --- Autoloader PSR-4 ---
require_once APP_ROOT . '/core/Autoloader.php';
\Core\Autoloader::register();

// --- Helpers globaux ---
require_once APP_ROOT . '/core/helpers.php';

// --- Routeur ---
$router = new \Core\Router();

// ---------------------------------------------------------------
// Définition des routes
// ---------------------------------------------------------------

// Auth
$router->get( '/auth/login',    'AuthController', 'loginForm');
$router->post('/auth/login',    'AuthController', 'login');
$router->get( '/auth/register', 'AuthController', 'registerForm');
$router->post('/auth/register', 'AuthController', 'register');
$router->get( '/auth/logout',   'AuthController', 'logout');

// Dashboard
$router->get('/dashboard', 'DashboardController', 'index');
$router->get('/',          'DashboardController', 'index');

// Catégories
$router->get( '/categories',             'CategoryController', 'index');
$router->get( '/categories/create',      'CategoryController', 'createForm');
$router->post('/categories/create',      'CategoryController', 'create');
$router->get( '/categories/edit/:id',    'CategoryController', 'editForm');
$router->post('/categories/edit/:id',    'CategoryController', 'edit');
$router->post('/categories/delete/:id',  'CategoryController', 'delete');

// Produits
$router->get( '/products',              'ProductController', 'index');
$router->get( '/products/show/:id',     'ProductController', 'show');
$router->get( '/products/create',       'ProductController', 'createForm');
$router->post('/products/create',       'ProductController', 'create');
$router->get( '/products/edit/:id',     'ProductController', 'editForm');
$router->post('/products/edit/:id',     'ProductController', 'edit');
$router->post('/products/delete/:id',   'ProductController', 'delete');

// ---------------------------------------------------------------
// Dispatch – compatible php -S ET Apache
// ---------------------------------------------------------------
$uri    = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Retire la query string (?foo=bar)
$uri = strtok($uri, '?');

// Si lancé depuis le sous-dossier public/ avec Apache,
// retire le préfixe du basePath (ex: /php-mvc-project/public)
$basePath = rtrim(parse_url(APP_URL, PHP_URL_PATH) ?? '', '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// Normalise : toujours un "/" au début, jamais à la fin (sauf "/")
$uri = '/' . ltrim($uri, '/');
if ($uri !== '/') {
    $uri = rtrim($uri, '/');
}

$router->dispatch($uri, $method);
