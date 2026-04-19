<?php
// app/views/products/form.php
// Layout : main
// Données : $product (null = création), $categories, $csrf
$isEdit = !empty($product);
$action = $isEdit
    ? url("products/edit/{$product['id']}")
    : url('products/create');
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="table-card">
            <div class="card-header">
                <span>
                    <i class="bi bi-<?= $isEdit ? 'pencil' : 'plus-circle' ?> me-2 text-primary"></i>
                    <?= $isEdit ? 'Modifier le produit' : 'Nouveau produit' ?>
                </span>
                <a href="<?= url('products') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
            </div>

            <div class="p-4">
                <form action="<?= $action ?>" method="POST" novalidate>
                    <?= csrfField() ?>

                    <!-- Nom -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nom du produit <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="<?= e($product['name'] ?? '') ?>"
                            placeholder="Ex : Casque Bluetooth Pro"
                            required
                        >
                    </div>

                    <!-- Catégorie -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-semibold">
                            Catégorie <span class="text-danger">*</span>
                        </label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">— Choisir une catégorie —</option>
                            <?php foreach ($categories as $cat): ?>
                            <option
                                value="<?= $cat['id'] ?>"
                                <?= isset($product['category_id']) && (int)$product['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>
                            >
                                <?= e($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            rows="4"
                            placeholder="Description du produit…"
                        ><?= e($product['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Prix & Stock -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">
                                Prix (€) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    id="price"
                                    name="price"
                                    class="form-control"
                                    value="<?= e($product['price'] ?? '0') ?>"
                                    min="0"
                                    step="0.01"
                                    required
                                >
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold">
                                Stock <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    id="stock"
                                    name="stock"
                                    class="form-control"
                                    value="<?= e($product['stock'] ?? '0') ?>"
                                    min="0"
                                    required
                                >
                                <span class="input-group-text">unités</span>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= url('products') ?>" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'primary' ?> px-4">
                            <i class="bi bi-<?= $isEdit ? 'save' : 'plus-lg' ?> me-2"></i>
                            <?= $isEdit ? 'Mettre à jour' : 'Créer le produit' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
