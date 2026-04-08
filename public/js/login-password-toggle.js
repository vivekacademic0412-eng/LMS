(function () {
    function updatePasswordToggle(button, input, isVisible) {
        button.dataset.visible = isVisible ? 'true' : 'false';
        button.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');
        button.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
        button.setAttribute('title', isVisible ? 'Hide password' : 'Show password');
        input.type = isVisible ? 'text' : 'password';
    }

    function bindPasswordToggle(button) {
        var inputId = button.getAttribute('aria-controls');
        var input = inputId ? document.getElementById(inputId) : null;

        if (!input) {
            return;
        }

        button.addEventListener('click', function () {
            updatePasswordToggle(button, input, input.type === 'password');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var toggles = document.querySelectorAll('[data-password-toggle]');

        for (var i = 0; i < toggles.length; i++) {
            bindPasswordToggle(toggles[i]);
        }
    });
})();
