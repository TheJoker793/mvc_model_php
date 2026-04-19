<?php
// app/views/auth/register.php
// Layout : auth
?>
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo"><i class="bi bi-person-plus"></i></div>
        <h4 class="fw-bold mb-1">Créer un compte</h4>
        <p class="text-muted small mb-0">Rejoignez <?= APP_NAME ?></p>
    </div>

    <div class="auth-body">
        <form action="<?= url('auth/register') ?>" method="POST" novalidate>
            <?= csrfField() ?>

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nom complet</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" id="name" name="name"
                           class="form-control border-start-0 ps-0"
                           placeholder="Jean Dupont" required autocomplete="name">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" id="email" name="email"
                           class="form-control border-start-0 ps-0"
                           placeholder="jean@exemple.com" required autocomplete="email">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" id="password" name="password"
                           class="form-control border-start-0 ps-0"
                           placeholder="8 caractères minimum" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirm" class="form-label fw-semibold">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock-fill text-muted"></i>
                    </span>
                    <input type="password" id="password_confirm" name="password_confirm"
                           class="form-control border-start-0 ps-0"
                           placeholder="Répétez le mot de passe" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                <i class="bi bi-check-circle me-2"></i>Créer mon compte
            </button>
        </form>

        <hr class="my-3">

        <p class="text-center text-muted small mb-0">
            Déjà inscrit ?
            <a href="<?= url('auth/login') ?>" class="fw-semibold">Se connecter</a>
        </p>
    </div>
</div>
