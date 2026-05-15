// Validaciones Bootstrap para formularios
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Prevenir cantidades negativas en inputs number
const numberInputs = document.querySelectorAll('input[type="number"]');
numberInputs.forEach(input => {
    input.addEventListener('input', () => {
        const min = parseInt(input.getAttribute('min') || '0');
        if (parseInt(input.value || '0') < min) input.value = min;
    });
});
