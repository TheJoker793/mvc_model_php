<?php
// app/views/categories/form.php
// Layout : main
// Données : $category (null = création), $csrf
$isEdit = !empty($category);
$action = $isEdit
    ? url("categories/edit/{$category['id']}")
    : url('categories/create');
?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="table-card">
            <div class="card-header">
                <span>
                    <i class="bi bi-<?= $isEdit ? 'pencil' : 'folder-plus' ?> me-2 text-primary"></i>
                    <?= $isEdit ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?>
                </span>
                <a href="<?= url('categories') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Retour
                </a>
            </div>

            <div class="p-4">
                <form action="<?= $action ?>" method="POST" novalidate>
                    <?= csrfField() ?>

                    <!-- Nom -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nom de la catégorie <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="<?= e($category['name'] ?? '') ?>"
                            placeholder="Ex : Électronique"
                            required
                        >
                        <div class="form-text text-muted">
                            Le slug URL sera généré automatiquement.
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            rows="3"
                            placeholder="Description courte de la catégorie…"
                        ><?= e($category['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Aperçu slug -->
                    <?php if ($isEdit): ?>
                    <div class="alert alert-light border mb-4 small">
                        <i class="bi bi-link-45deg me-1"></i>
                        Slug actuel : <code><?= e($category['slug']) ?></code>
                    </div>
                    <?php endif; ?>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= url('categories') ?>" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'primary' ?> px-4">
                            <i class="bi bi-<?= $isEdit ? 'save' : 'plus-lg' ?> me-2"></i>
                            <?= $isEdit ? 'Mettre à jour' : 'Créer la catégorie' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
