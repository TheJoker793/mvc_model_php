<?php
// =============================================================
// core/helpers.php  –  Fonctions utilitaires globales
// =============================================================

/**
 * Échappe une chaîne pour l'affichage HTML (protection XSS).
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Retourne et supprime le flash message de la session.
 */
function getFlash(): array|null
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Génère une URL absolue à partir d'un chemin relatif.
 */
function url(string $path = ''): string
{
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Génère un slug URL-friendly à partir d'une chaîne.
 */
function slugify(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[àâä]/u',  'a', $text);
    $text = preg_replace('/[ùûü]/u',  'u', $text);
    $text = preg_replace('/[îï]/u',   'i', $text);
    $text = preg_replace('/[ôö]/u',   'o', $text);
    $text = preg_replace('/[ç]/u',    'c', $text);
    $text = preg_replace('/[^a-z0-9\s-]/u', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Formate un prix en euros.
 */
function formatPrice(float $price): string
{
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Vérifie si l'utilisateur connecté est admin.
 */
function isAdmin(): bool
{
    return ($_SESSION['user']['role'] ?? '') === 'admin';
}

/**
 * Vérifie si l'utilisateur est connecté.
 */
function isAuth(): bool
{
    return !empty($_SESSION['user']);
}

/**
 * Affiche le token CSRF sous forme de champ caché.
 */
function csrfField(): string
{
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="_csrf" value="' . e($token) . '">';
}
