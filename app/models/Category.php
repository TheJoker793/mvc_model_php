<?php
// =============================================================
// app/models/Category.php
// =============================================================

namespace App\Models;

use Core\Model;

class Category extends Model
{
    protected string $table = 'categories';

    /** Vérifie si un slug existe déjà (optionnellement sauf pour $excludeId) */
    public function slugExists(string $slug, int $excludeId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM categories WHERE slug = :slug AND id != :id LIMIT 1'
        );
        $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
        return (bool) $stmt->fetch();
    }

    /** Retourne les catégories avec le nombre de produits associés */
    public function findAllWithProductCount(): array
    {
        $stmt = $this->pdo->query(
            'SELECT c.*, COUNT(p.id) AS product_count
             FROM categories c
             LEFT JOIN products p ON p.category_id = c.id
             GROUP BY c.id
             ORDER BY c.name ASC'
        );
        return $stmt->fetchAll();
    }
}
