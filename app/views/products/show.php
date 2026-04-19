<?php
// app/views/products/show.php
// Layout : main
// Données : $product
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Carte détail produit -->
        <div class="table-card">
            <!-- En-tête -->
            <div class="card-header">
                <span>
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Détail du produit
                </span>
                <div class="d-flex gap-2">
                    <a href="<?= url('products') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Retour
                    </a>
                    <?php if (isAdmin()): ?>
                    <a href="<?= url("products/edit/{$product['id']}") ?>"
                       class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-4">
                <!-- Nom & badge catégorie -->
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h3 class="fw-bold mb-1"><?= e($product['name']) ?></h3>
                        <span class="badge bg-light text-dark border fs-6">
                            <i class="bi bi-tag me-1"></i><?= e($product['category_name']) ?>
                        </span>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-primary">
                            <?= formatPrice((float)$product['price']) ?>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Description -->
                <div class="mb-4">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Description</h6>
                    <p class="mb-0">
                        <?= !empty($product['description'])
                            ? nl2br(e($product['description']))
                            : '<em class="text-muted">Aucune description</em>' ?>
                    </p>
                </div>

                <!-- Informations -->
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="text-muted small mb-1">Stock</div>
                            <?php
                                $s   = (int)$product['stock'];
                                $cls = $s === 0 ? 'stock-empty' : ($s < 5 ? 'stock-warning' : 'stock-ok');
                            ?>
                            <span class="stock-badge <?= $cls ?> fs-6"><?= $s ?></span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="text-muted small mb-1">Référence</div>
                            <div class="fw-semibold small">#<?= e($product['id']) ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="text-muted small mb-1">Slug</div>
                            <div class="fw-semibold small text-truncate"><?= e($product['slug']) ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="text-muted small mb-1">Créé le</div>
                            <div class="fw-semibold small">
                                <?= date('d/m/Y', strtotime($product['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isAdmin()): ?>
                <hr>
                <div class="d-flex justify-content-end">
                    <form action="<?= url("products/delete/{$product['id']}") ?>"
                          method="POST"
                          onsubmit="return confirm('Confirmer la suppression de ce produit ?')">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i>Supprimer ce produit
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
