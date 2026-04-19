<?php
// app/views/categories/index.php
// Layout : main
// Données : $categories
?>

<div class="table-card">
    <div class="card-header">
        <span>
            <i class="bi bi-tags me-2 text-primary"></i>
            Catégories
            <span class="badge bg-primary ms-1"><?= count($categories) ?></span>
        </span>
        <?php if (isAdmin()): ?>
        <a href="<?= url('categories/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle catégorie
        </a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Produits</th>
                    <th>Créée le</th>
                    <?php if (isAdmin()): ?><th class="text-end">Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        Aucune catégorie
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td class="text-muted small">#<?= e($cat['id']) ?></td>
                    <td class="fw-semibold"><?= e($cat['name']) ?></td>
                    <td>
                        <code class="text-muted small"><?= e($cat['slug']) ?></code>
                    </td>
                    <td class="text-muted small text-truncate-2" style="max-width:200px;">
                        <?= !empty($cat['description']) ? e($cat['description']) : '—' ?>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-box-seam me-1"></i>
                            <?= (int)$cat['product_count'] ?>
                        </span>
                    </td>
                    <td class="text-muted small">
                        <?= date('d/m/Y', strtotime($cat['created_at'])) ?>
                    </td>
                    <?php if (isAdmin()): ?>
                    <td class="text-end">
                        <a href="<?= url("categories/edit/{$cat['id']}") ?>"
                           class="btn btn-outline-primary action-btn" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="<?= url("categories/delete/{$cat['id']}") ?>"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Supprimer la catégorie « <?= e($cat['name']) ?> » ?\nLes produits associés resteront mais sans catégorie valide.')">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-outline-danger action-btn" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
