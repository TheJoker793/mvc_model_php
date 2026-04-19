/**
 * public/assets/js/app.js
 * Scripts globaux de l'application
 */

'use strict';

// ---------------------------------------------------------------
// 1. Fermeture automatique des alertes Flash après 4 secondes
// ---------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {

    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            // Utilise l'API Bootstrap pour fermer proprement
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 4000);
    });

    // ---------------------------------------------------------------
    // 2. Génération dynamique du slug depuis le champ "name"
    //    (utile pour aperçu dans les formulaires)
    // ---------------------------------------------------------------
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slug-preview');

    if (nameInput && slugPreview) {
        nameInput.addEventListener('input', () => {
            slugPreview.textContent = slugify(nameInput.value);
        });
    }

    // ---------------------------------------------------------------
    // 3. Confirmation avant toute suppression (data-confirm)
    // ---------------------------------------------------------------
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm(btn.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

});

// ---------------------------------------------------------------
// Fonction slugify côté client (miroir de la version PHP)
// ---------------------------------------------------------------
function slugify(text) {
    const map = {
        'é':'e','è':'e','ê':'e','ë':'e',
        'à':'a','â':'a','ä':'a',
        'ù':'u','û':'u','ü':'u',
        'î':'i','ï':'i',
        'ô':'o','ö':'o',
        'ç':'c'
    };
    return text
        .toLowerCase()
        .replace(/[éèêëàâäùûüîïôöç]/g, c => map[c] || c)
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/[\s-]+/g, '-');
}
