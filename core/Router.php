<?php
// =============================================================
// core/Router.php  –  Routeur simple basé sur l'URL
// =============================================================

namespace Core;

class Router
{
    private array $routes = [];

    // ----------------------------------------------------------
    // Enregistrement des routes
    // ----------------------------------------------------------

    public function get(string $path, string $controller, string $action): void
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    private function addRoute(
        string $method,
        string $path,
        string $controller,
        string $action
    ): void {
        // Convertit /products/:id en regex (/products/([^/]+))
        $pattern = preg_replace('/:([a-zA-Z_]+)/', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'     => strtoupper($method),
            'pattern'    => $pattern,
            'path'       => $path,
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    // ----------------------------------------------------------
    // Résolution
    // ----------------------------------------------------------

    public function dispatch(string $uri, string $method): void
    {
        // Supprime la query string (?foo=bar)
        $uri = strtok($uri, '?');
        // Retire le slash final sauf pour "/"
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);   // retire l'entrée complète

                $controllerClass = 'App\\Controllers\\' . $route['controller'];
                $action          = $route['action'];

                if (!class_exists($controllerClass)) {
                    $this->notFound("Contrôleur introuvable : {$controllerClass}");
                    return;
                }

                $ctrl = new $controllerClass();

                if (!method_exists($ctrl, $action)) {
                    $this->notFound("Méthode introuvable : {$action}");
                    return;
                }

                // Appelle la méthode avec les éventuels paramètres d'URL
                call_user_func_array([$ctrl, $action], $matches);
                return;
            }
        }

        $this->notFound("Route non trouvée : {$uri}");
    }

    private function notFound(string $msg = ''): void
    {
        http_response_code(404);
        if (DEBUG) {
            echo "<h1>404 – Page introuvable</h1><p>{$msg}</p>";
        } else {
            require APP_ROOT . '/app/views/errors/404.php';
        }
    }
}
