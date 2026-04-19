<?php
// =============================================================
// core/Autoloader.php  –  Autoloader PSR-4 maison (sans Composer)
// =============================================================

namespace Core;

class Autoloader
{
    /**
     * Enregistre l'autoloader.
     *
     * Convention :
     *   App\Controllers\ProductController  → app/controllers/ProductController.php
     *   App\Models\Product                 → app/models/Product.php
     *   Core\Database                      → core/Database.php
     */
    public static function register(): void
    {
        spl_autoload_register(function (string $class): void {
            // Mappe les namespaces vers les dossiers
            $namespaceMap = [
                'App\\Controllers\\' => APP_ROOT . '/app/controllers/',
                'App\\Models\\'      => APP_ROOT . '/app/models/',
                'Core\\'             => APP_ROOT . '/core/',
            ];

            foreach ($namespaceMap as $prefix => $dir) {
                if (str_starts_with($class, $prefix)) {
                    $relative = substr($class, strlen($prefix));
                    $file     = $dir . str_replace('\\', '/', $relative) . '.php';

                    if (file_exists($file)) {
                        require_once $file;
                        return;
                    }
                }
            }
        });
    }
}
