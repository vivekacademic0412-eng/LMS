    @if ($dashboardMode === 'demo')
        <script src="{{ asset('js/student-courses.js') }}" defer></script>
        <script src="{{ asset('js/course-modals.js') }}" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var reloadHost = document.querySelector('[data-demo-kiosk-reload-seconds]');
                if (!reloadHost) {
                    return;
                }

                var remaining = Number.parseInt(reloadHost.getAttribute('data-demo-kiosk-reload-seconds') || '0', 10);
                if (!Number.isFinite(remaining) || remaining <= 0) {
                    return;
                }

                var countdownNodes = document.querySelectorAll('[data-demo-kiosk-countdown]');
                var renderCountdown = function () {
                    countdownNodes.forEach(function (node) {
                        node.textContent = String(Math.max(0, remaining));
                    });
                };

                renderCountdown();

                var timer = window.setInterval(function () {
                    remaining -= 1;
                    renderCountdown();

                    if (remaining <= 0) {
                        window.clearInterval(timer);
                        window.location.reload();
                    }
                }, 1000);
            });
        </script>
    @endif
