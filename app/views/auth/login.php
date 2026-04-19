<?php
// app/views/auth/login.php
// Layout : auth
// Données : $csrf
?>
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo"><i class="bi bi-shop"></i></div>
        <h4 class="fw-bold mb-1"><?= APP_NAME ?></h4>
        <p class="text-muted small mb-0">Connectez-vous à votre espace</p>
    </div>

    <div class="auth-body">
        <form action="<?= url('auth/login') ?>" method="POST" novalidate>
            <?= csrfField() ?>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control border-start-0 ps-0"
                        placeholder="exemple@email.com"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control border-start-0 ps-0"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </button>
        </form>

        <hr class="my-3">

        <p class="text-center text-muted small mb-0">
            Pas encore de compte ?
            <a href="<?= url('auth/register') ?>" class="fw-semibold">S'inscrire</a>
        </p>

        <div class="alert alert-light border mt-4 mb-0 small">
            <strong>Comptes de test :</strong><br>
            Admin : <code>admin@shop.com</code><br>
            User : <code>user@shop.com</code><br>
            Mot de passe : <code>password</code>
        </div>
    </div>
</div>
