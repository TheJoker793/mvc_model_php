<?php
// =============================================================
// core/Controller.php  –  Classe de base pour tous les contrôleurs
// =============================================================

namespace Core;

abstract class Controller
{
    // ----------------------------------------------------------
    // Rendu d'une vue
    // ----------------------------------------------------------

    /**
     * Charge une vue dans le layout principal.
     *
     * @param string $view    Chemin relatif depuis app/views/  ex: "products/index"
     * @param array  $data    Variables à extraire dans la vue
     * @param string $layout  Layout à utiliser (par défaut "main")
     */
    protected function render(
        string $view,
        array  $data   = [],
        string $layout = 'main'
    ): void {
        // Rend les variables disponibles dans la vue
        extract($data, EXTR_SKIP);

        // Capture le contenu de la vue dans $content
        ob_start();
        $viewFile = APP_ROOT . "/app/views/{$view}.php";
        if (!file_exists($viewFile)) {
            ob_end_clean();
            $this->abort(404, "Vue introuvable : {$view}");
            return;
        }
        require $viewFile;
        $content = ob_get_clean();

        // Charge le layout qui inclura $content
        $layoutFile = APP_ROOT . "/app/views/layouts/{$layout}.php";
        if (!file_exists($layoutFile)) {
            echo $content;   // fallback sans layout
            return;
        }
        require $layoutFile;
    }

    // ----------------------------------------------------------
    // Redirections
    // ----------------------------------------------------------

    protected function redirect(string $url): void
    {
        header('Location: ' . APP_URL . $url);
        exit;
    }

    // ----------------------------------------------------------
    // Flash messages (messages temporaires en session)
    // ----------------------------------------------------------

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    // ----------------------------------------------------------
    // Sécurité : vérification des droits
    // ----------------------------------------------------------

    /** Redirige vers /login si l'utilisateur n'est pas connecté */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->flash('warning', 'Veuillez vous connecter pour accéder à cette page.');
            $this->redirect('/auth/login');
        }
    }

    /** Redirige vers /dashboard si l'utilisateur n'est pas admin */
    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->flash('danger', 'Accès refusé : droits administrateur requis.');
            $this->redirect('/dashboard');
        }
    }

    // ----------------------------------------------------------
    // Erreurs HTTP
    // ----------------------------------------------------------

    protected function abort(int $code = 404, string $message = ''): void
    {
        http_response_code($code);
        $this->render("errors/{$code}", ['message' => $message], 'main');
        exit;
    }

    // ----------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------

    /** Nettoie et retourne une donnée GET/POST */
    protected function input(string $key, string $method = 'POST'): string
    {
        $source = $method === 'GET' ? $_GET : $_POST;
        return trim(htmlspecialchars($source[$key] ?? '', ENT_QUOTES, 'UTF-8'));
    }

    /** Valide le token CSRF */
    protected function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (
            empty($token) ||
            !hash_equals($_SESSION['csrf_token'] ?? '', $token)
        ) {
            $this->flash('danger', 'Token CSRF invalide. Réessayez.');
            $this->redirect('/dashboard');
        }
    }

    /** Génère et stocke un token CSRF */
    protected function generateCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
