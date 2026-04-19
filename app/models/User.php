<?php
// =============================================================
// app/models/User.php
// =============================================================

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected string $table = 'users';

    /** Trouve un utilisateur par son email */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Inscrit un nouvel utilisateur.
     * Retourne false si l'email est déjà pris.
     */
    public function register(string $name, string $email, string $password): int|false
    {
        if ($this->findByEmail($email)) {
            return false;
        }

        return $this->insert([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]),
            'role'     => 'user',
        ]);
    }

    /**
     * Vérifie les identifiants et retourne l'utilisateur ou false.
     */
    public function authenticate(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        return $user;
    }
}
