<?php
// app/views/products/index.php
// Layout : main
// Données : $products, $categories, $keyword, $catId
?>

<!-- Barre de recherche & filtres -->
<div class="table-card mb-4">
    <div class="card-header">
        <span><i class="bi bi-funnel me-2 text-primary"></i>Filtrer les produits</span>
    </div>
    <div class="p-3">
        <form action="<?= url('products') ?>" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" value="<?= $keyword ?>"
                           class="form-control" placeholder="Nom ou description…">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Catégorie</label>
                <select name="category" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                            <?= (int)$catId === (int)$cat['id'] ? 'selected' : '' ?>>
                        <?= e($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                <a href="<?= url('products') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Liste des produits -->
<div class="table-card">
    <div class="card-header">
        <span>
            <i class="bi bi-box-seam me-2 text-primary"></i>
            Produits
            <span class="badge bg-primary ms-1"><?= count($products) ?></span>
        </span>
        <?php if (isAdmin()): ?>
        <a href="<?= url('products/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nouveau produit
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
                    <th>Ajouté le</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        Aucun produit trouvé
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td class="text-muted small">#<?= e($p['id']) ?></td>
                    <td>
                        <div class="fw-semibold"><?= e($p['name']) ?></div>
                        <?php if (!empty($p['description'])): ?>
                        <small class="text-muted text-truncate-2">
                            <?= e(mb_substr($p['description'], 0, 60)) ?>…
                        </small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-tag me-1"></i><?= e($p['category_name']) ?>
                        </span>
                    </td>
                    <td class="fw-bold text-primary"><?= formatPrice((float)$p['price']) ?></td>
                    <td>
                        <?php
                            $s   = (int)$p['stock'];
                            $cls = $s === 0 ? 'stock-empty' : ($s < 5 ? 'stock-warning' : 'stock-ok');
                        ?>
                        <span class="stock-badge <?= $cls ?>"><?= $s ?> unité<?= $s > 1 ? 's' : '' ?></span>
                    </td>
                    <td class="text-muted small">
                        <?= date('d/m/Y', strtotime($p['created_at'])) ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= url("products/show/{$p['id']}") ?>"
                           class="btn btn-outline-secondary action-btn" title="Voir">
                            <i class="bi bi-eye"></i>
                        </a>
                        <?php if (isAdmin()): ?>
                        <a href="<?= url("products/edit/{$p['id']}") ?>"
                           class="btn btn-outline-primary action-btn" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="<?= url("products/delete/{$p['id']}") ?>"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer ce produit ?')">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-outline-danger action-btn" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
