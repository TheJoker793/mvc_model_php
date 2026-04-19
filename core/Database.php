<?php
// =============================================================
// core/Database.php  –  Connexion PDO (Singleton)
// =============================================================

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,   // vraies requêtes préparées
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // On n'expose pas les détails en production
            if (DEBUG) {
                die('Connexion DB échouée : ' . $e->getMessage());
            }
            die('Erreur de connexion à la base de données.');
        }
    }

    /** Retourne l'instance unique */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Retourne l'objet PDO */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    // Empêche le clonage et la désérialisation
    private function __clone() {}
    public function __wakeup() {}
}
