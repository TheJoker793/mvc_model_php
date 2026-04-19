<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Page') ?> – <?= APP_NAME ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS personnalisé -->
    <link href="<?= url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>

<div class="wrapper">

    <!-- ===================== SIDEBAR ===================== -->
    <aside class="sidebar">
        <a href="<?= url('dashboard') ?>" class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-shop"></i></div>
            <span><?= APP_NAME ?></span>
        </a>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Principal</div>

            <a href="<?= url('dashboard') ?>"
               class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>

            <a href="<?= url('products') ?>"
               class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/products') ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span>Produits</span>
            </a>

            <a href="<?= url('categories') ?>"
               class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/categories') ? 'active' : '' ?>">
                <i class="bi bi-tags"></i>
                <span>Catégories</span>
            </a>

            <?php if (isAdmin()): ?>
            <div class="nav-section-title">Administration</div>
            <a href="<?= url('products/create') ?>" class="nav-link">
                <i class="bi bi-plus-circle"></i>
                <span>Nouveau produit</span>
            </a>
            <a href="<?= url('categories/create') ?>" class="nav-link">
                <i class="bi bi-folder-plus"></i>
                <span>Nouvelle catégorie</span>
            </a>
            <?php endif; ?>

            <div class="nav-section-title">Compte</div>
            <a href="<?= url('auth/logout') ?>" class="nav-link text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span>Déconnexion</span>
            </a>
        </nav>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar">
            <span class="topbar-title"><?= e($title ?? '') ?></span>

            <div class="topbar-user">
                <?php if (isAdmin()): ?>
                    <span class="badge bg-warning text-dark role-badge">
                        <i class="bi bi-shield-check"></i> Admin
                    </span>
                <?php endif; ?>
                <div class="avatar">
                    <?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?>
                </div>
                <span class="d-none d-md-inline fw-semibold">
                    <?= e($_SESSION['user']['name'] ?? '') ?>
                </span>
            </div>
        </header>

        <!-- Page body -->
        <main class="page-body">

            <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?= $content ?>

        </main>
    </div><!-- /.main-content -->

</div><!-- /.wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>
