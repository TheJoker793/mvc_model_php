<?php
// =============================================================
// app/models/Product.php
// =============================================================

namespace App\Models;

use Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    /** Retourne tous les produits avec le nom de leur catégorie */
    public function findAllWithCategory(): array
    {
        $stmt = $this->pdo->query(
            'SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON c.id = p.category_id
             ORDER BY p.created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** Retourne un produit avec son nom de catégorie */
    public function findByIdWithCategory(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON c.id = p.category_id
             WHERE p.id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Recherche par nom ou description */
    public function search(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        $stmt = $this->pdo->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON c.id = p.category_id
             WHERE p.name LIKE :k OR p.description LIKE :k
             ORDER BY p.name ASC'
        );
        $stmt->execute([':k' => $like]);
        return $stmt->fetchAll();
    }

    /** Filtre par catégorie */
    public function findByCategory(int $categoryId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p
             JOIN categories c ON c.id = p.category_id
             WHERE p.category_id = :cid
             ORDER BY p.name ASC'
        );
        $stmt->execute([':cid' => $categoryId]);
        return $stmt->fetchAll();
    }

    /** Vérifie l'unicité du slug */
    public function slugExists(string $slug, int $excludeId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM products WHERE slug = :slug AND id != :id LIMIT 1'
        );
        $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
        return (bool) $stmt->fetch();
    }
}
