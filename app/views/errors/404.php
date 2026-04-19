<?php
// app/views/errors/404.php
// Layout : main (ou standalone si layout absent)
?>
<div class="row justify-content-center">
    <div class="col-md-6 text-center py-5">
        <div class="display-1 fw-bold text-primary opacity-25">404</div>
        <h2 class="fw-bold mb-2">Page introuvable</h2>
        <p class="text-muted mb-4">
            <?= !empty($message) ? e($message) : 'La page que vous recherchez n\'existe pas ou a été déplacée.' ?>
        </p>
        <a href="<?= url('dashboard') ?>" class="btn btn-primary px-4">
            <i class="bi bi-house me-2"></i>Retour à l'accueil
        </a>
    </div>
</div>
