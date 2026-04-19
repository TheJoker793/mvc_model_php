<?php
// app/views/dashboard/index.php
// Layout : main
// Données : $totalProducts, $totalCategories, $totalUsers, $recentProducts
?>

<!-- Stat cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card bg-white">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="stat-value text-primary"><?= $totalProducts ?></div>
                <div class="stat-label">Produits</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="stat-card bg-white">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-tags"></i>
            </div>
            <div>
                <div class="stat-value text-success"><?= $totalCategories ?></div>
                <div class="stat-label">Catégories</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="stat-card bg-white">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-value text-warning"><?= $totalUsers ?></div>
                <div class="stat-label">Utilisateurs</div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des produits récents -->
<div class="table-card">
    <div class="card-header">
        <span><i class="bi bi-clock-history me-2 text-primary"></i>Produits récents</span>
        <?php if (isAdmin()): ?>
        <a href="<?= url('products/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus me-1"></i>Nouveau
        </a>
        <?php endif; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($recentProducts)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun produit
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach (array_slice($recentProducts, 0, 8) as $p): ?>
                <tr>
                    <td class="text-muted small">#<?= e($p['id']) ?></td>
                    <td class="fw-semibold"><?= e($p['name']) ?></td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <?= e($p['category_name']) ?>
                        </span>
                    </td>
                    <td class="fw-semibold text-primary"><?= formatPrice((float)$p['price']) ?></td>
                    <td>
                        <?php
                            $s = (int)$p['stock'];
                            $cls = $s === 0 ? 'stock-empty' : ($s < 5 ? 'stock-warning' : 'stock-ok');
                        ?>
                        <span class="stock-badge <?= $cls ?>"><?= $s ?></span>
                    </td>
                    <td class="text-end">
                        <a href="<?= url("products/show/{$p['id']}") ?>"
                           class="btn btn-outline-secondary action-btn">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php if (isAdmin()): ?>
                        <a href="<?= url("products/edit/{$p['id']}") ?>"
                           class="btn btn-outline-primary action-btn">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (count($recentProducts) > 8): ?>
    <div class="p-3 text-center border-top">
        <a href="<?= url('products') ?>" class="btn btn-outline-primary btn-sm">
            Voir tous les produits <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <?php endif; ?>
</div>
