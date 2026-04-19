<?php
// =============================================================
// app/controllers/AuthController.php
// =============================================================

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ----------------------------------------------------------
    // GET /auth/login
    // ----------------------------------------------------------
    public function loginForm(): void
    {
        if (isAuth()) {
            $this->redirect('/dashboard');
        }

        $this->render('auth/login', [
            'title' => 'Connexion',
            'csrf'  => $this->generateCsrf(),
        ], 'auth');
    }

    // ----------------------------------------------------------
    // POST /auth/login
    // ----------------------------------------------------------
    public function login(): void
    {
        $this->verifyCsrf();

        $email    = $this->input('email');
        $password = $this->input('password');

        // Validation basique
        if (empty($email) || empty($password)) {
            $this->flash('danger', 'Veuillez remplir tous les champs.');
            $this->redirect('/auth/login');
            return;
        }

        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            // Message volontairement vague (ne pas indiquer si email ou mot de passe est faux)
            $this->flash('danger', 'Identifiants incorrects.');
            $this->redirect('/auth/login');
            return;
        }

        // Régénère l'ID de session pour prévenir la fixation de session
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        $this->flash('success', 'Bienvenue, ' . e($user['name']) . ' !');
        $this->redirect('/dashboard');
    }

    // ----------------------------------------------------------
    // GET /auth/register
    // ----------------------------------------------------------
    public function registerForm(): void
    {
        if (isAuth()) {
            $this->redirect('/dashboard');
        }

        $this->render('auth/register', [
            'title' => 'Inscription',
            'csrf'  => $this->generateCsrf(),
        ], 'auth');
    }

    // ----------------------------------------------------------
    // POST /auth/register
    // ----------------------------------------------------------
    public function register(): void
    {
        $this->verifyCsrf();

        $name            = $this->input('name');
        $email           = $this->input('email');
        $password        = $this->input('password');
        $passwordConfirm = $this->input('password_confirm');

        // Validations
        $errors = [];
        if (empty($name))                           $errors[] = 'Le nom est requis.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
        if (strlen($password) < 8)                  $errors[] = 'Mot de passe trop court (8 caractères min).';
        if ($password !== $passwordConfirm)          $errors[] = 'Les mots de passe ne correspondent pas.';

        if ($errors) {
            $this->flash('danger', implode('<br>', $errors));
            $this->redirect('/auth/register');
            return;
        }

        $id = $this->userModel->register($name, $email, $password);

        if (!$id) {
            $this->flash('danger', 'Cet email est déjà utilisé.');
            $this->redirect('/auth/register');
            return;
        }

        $this->flash('success', 'Compte créé ! Vous pouvez vous connecter.');
        $this->redirect('/auth/login');
    }

    // ----------------------------------------------------------
    // GET /auth/logout
    // ----------------------------------------------------------
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }
}
