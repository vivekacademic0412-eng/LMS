document.addEventListener('DOMContentLoaded', function () {
    var formatCount = function (value, decimals, suffix) {
        return Number(value).toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }) + suffix;
    };

    document.querySelectorAll('[data-student-progress-dashboard]').forEach(function (dashboard) {
        dashboard.querySelectorAll('[data-weekly-chart]').forEach(function (chart) {
            var activateView = function (viewKey) {
                chart.querySelectorAll('[data-weekly-button]').forEach(function (button) {
                    button.classList.toggle('is-active', button.getAttribute('data-weekly-button') === viewKey);
                });

                chart.querySelectorAll('[data-weekly-plot]').forEach(function (plot) {
                    plot.classList.toggle('is-active', plot.getAttribute('data-weekly-plot') === viewKey);
                });

                chart.querySelectorAll('[data-weekly-summary]').forEach(function (summary) {
                    summary.classList.toggle('is-active', summary.getAttribute('data-weekly-summary') === viewKey);
                });
            };

            chart.querySelectorAll('[data-weekly-button]').forEach(function (button) {
                button.addEventListener('click', function () {
                    activateView(button.getAttribute('data-weekly-button'));
                });
            });
        });

        dashboard.querySelectorAll('[data-countup-value]').forEach(function (node, index) {
            var target = parseFloat(node.getAttribute('data-countup-value') || '0');
            var decimals = parseInt(node.getAttribute('data-countup-decimals') || '0', 10);
            var suffix = node.getAttribute('data-countup-suffix') || '';
            var duration = 900 + (index * 120);
            var startTime = null;

            node.textContent = formatCount(0, decimals, suffix);

            var tick = function (timestamp) {
                if (!startTime) {
                    startTime = timestamp;
                }

                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                node.textContent = formatCount(target * eased, decimals, suffix);

                if (progress < 1) {
                    window.requestAnimationFrame(tick);
                }
            };

            window.requestAnimationFrame(tick);
        });

        window.setTimeout(function () {
            dashboard.querySelectorAll('[data-fill-width]').forEach(function (bar, index) {
                window.setTimeout(function () {
                    bar.style.width = bar.getAttribute('data-fill-width') || '0%';
                }, 100 + (index * 50));
            });
        }, 180);
    });
});
