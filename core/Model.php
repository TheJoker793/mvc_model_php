<?php
// =============================================================
// core/Model.php  –  Classe de base pour tous les modèles
// =============================================================

namespace Core;

use PDO;

abstract class Model
{
    protected PDO    $pdo;
    protected string $table  = '';      // à définir dans chaque modèle
    protected string $pk     = 'id';    // clé primaire (par défaut "id")

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }

    // ----------------------------------------------------------
    // CRUD générique
    // ----------------------------------------------------------

    /** Retourne tous les enregistrements */
    public function findAll(string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $dir  = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        $stmt = $this->pdo->prepare(
            "SELECT * FROM `{$this->table}` ORDER BY `{$orderBy}` {$dir}"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Retourne un enregistrement par son ID */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM `{$this->table}` WHERE `{$this->pk}` = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Insère un enregistrement, retourne l'ID inséré */
    public function insert(array $data): int
    {
        $columns = implode('`, `', array_keys($data));
        $params  = implode(', ', array_map(fn($k) => ":{$k}", array_keys($data)));

        $stmt = $this->pdo->prepare(
            "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$params})"
        );
        $stmt->execute($data);
        return (int) $this->pdo->lastInsertId();
    }

    /** Met à jour un enregistrement par son ID, retourne le nombre de lignes affectées */
    public function update(int $id, array $data): int
    {
        $set  = implode(', ', array_map(fn($k) => "`{$k}` = :{$k}", array_keys($data)));
        $data[':id'] = $id;

        $stmt = $this->pdo->prepare(
            "UPDATE `{$this->table}` SET {$set} WHERE `{$this->pk}` = :id"
        );
        $stmt->execute($data);
        return $stmt->rowCount();
    }

    /** Supprime un enregistrement par son ID */
    public function delete(int $id): int
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM `{$this->table}` WHERE `{$this->pk}` = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    /** Compte le nombre de lignes */
    public function count(): int
    {
        return (int) $this->pdo
            ->query("SELECT COUNT(*) FROM `{$this->table}`")
            ->fetchColumn();
    }
}
