(function () {
    var root = document.documentElement;
    var storageKey = 'lms-theme';
    var mediaQuery = typeof window.matchMedia === 'function'
        ? window.matchMedia('(prefers-color-scheme: dark)')
        : null;

    function applyTheme(theme) {
        root.setAttribute('data-theme', theme);
    }

    function persistTheme(theme) {
        try {
            localStorage.setItem(storageKey, theme);
        } catch (e) {
            // no-op
        }
    }

    function getStoredTheme() {
        try {
            var saved = localStorage.getItem(storageKey);
            if (saved === 'light' || saved === 'dark') {
                return saved;
            }
        } catch (e) {
            // no-op
        }

        return null;
    }

    function resolveTheme() {
        var stored = getStoredTheme();
        if (stored) {
            return stored;
        }

        return mediaQuery && mediaQuery.matches ? 'dark' : 'light';
    }

    applyTheme(resolveTheme());

    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('themeToggle');
        if (toggle) {
            toggle.addEventListener('click', function () {
                var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                applyTheme(next);
                persistTheme(next);
            });
        }

        if (!mediaQuery) {
            return;
        }

        var handlePreferenceChange = function (event) {
            if (getStoredTheme()) {
                return;
            }

            applyTheme(event.matches ? 'dark' : 'light');
        };

        if (typeof mediaQuery.addEventListener === 'function') {
            mediaQuery.addEventListener('change', handlePreferenceChange);
            return;
        }

        if (typeof mediaQuery.addListener === 'function') {
            mediaQuery.addListener(handlePreferenceChange);
        }
    });
})();
