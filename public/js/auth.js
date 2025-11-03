document.addEventListener('DOMContentLoaded', function () {
    // Generic password toggle: buttons with class .password-toggle-icon and data-target="#inputId"
    document.querySelectorAll('.password-toggle-icon').forEach(function (btn) {
        const target = btn.getAttribute('data-target');
        if (!target) return;

        btn.addEventListener('click', function () {
            const input = document.getElementById(target);
            if (!input) return;

            const isPassword = input.getAttribute('type') === 'password';
            const newType = isPassword ? 'text' : 'password';
            input.setAttribute('type', newType);

            // Update icon
            btn.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';

            // For accessibility
            btn.setAttribute('aria-pressed', String(isPassword));
        });
    });
});
