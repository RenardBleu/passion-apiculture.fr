document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne tous les groupes de boutons de quantité
    const quantityGroups = document.querySelectorAll('.quantity-group');

    quantityGroups.forEach(group => {
        const input = group.querySelector('input[type="number"]');
        const minusBtn = group.querySelector('.btn-minus');
        const plusBtn = group.querySelector('.btn-plus');

        if (input && minusBtn && plusBtn) {
            // Fonction pour mettre à jour la valeur
            const updateValue = (newValue) => {
                const min = parseInt(input.getAttribute('min')) || 1;
                const max = parseInt(input.getAttribute('max')) || 999;
                
                // S'assure que la valeur est dans les limites
                newValue = Math.max(min, Math.min(max, newValue));
                input.value = newValue;
                
                // Déclenche l'événement change pour informer les autres scripts
                input.dispatchEvent(new Event('change'));
            };

            // Gestionnaire pour le bouton moins
            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 0;
                updateValue(currentValue - 1);
            });

            // Gestionnaire pour le bouton plus
            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 0;
                updateValue(currentValue + 1);
            });

            // Gestionnaire pour la validation directe de l'input
            input.addEventListener('change', () => {
                const currentValue = parseInt(input.value) || 0;
                updateValue(currentValue);
            });
        }
    });
}); 