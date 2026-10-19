/* Global UI behaviour: mobile nav, alerts, countdowns, password toggle */
(function () {
    'use strict';

    // ---- Mobile navigation ----
    var topbar = document.getElementById('topbar');
    var toggle = document.getElementById('navToggle');
    if (topbar && toggle) {
        toggle.addEventListener('click', function () {
            var open = topbar.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    // ---- Auto-dismiss success / info alerts (errors stay visible) ----
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.alert-success, .alert-info').forEach(function (el) {
            if (el.hasAttribute('data-persist')) return;
            setTimeout(function () {
                el.classList.add('is-hiding');
                setTimeout(function () { el.style.display = 'none'; }, 450);
            }, 5000);
        });
    });

    // ---- Countdown timers: any element with data-end="YYYY-MM-DD HH:MM:SS" ----
    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function format(ms, long) {
        var s = Math.floor(ms / 1000);
        var d = Math.floor(s / 86400);
        var h = Math.floor((s % 86400) / 3600);
        var m = Math.floor((s % 3600) / 60);
        var sec = s % 60;
        if (long) {
            return (d > 0 ? d + 'd ' : '') + pad(h) + 'h ' + pad(m) + 'm ' + pad(sec) + 's';
        }
        if (d > 0) return d + 'd ' + h + 'h';
        if (h > 0) return h + 'h ' + m + 'm';
        return m + 'm ' + pad(sec) + 's';
    }

    function tick() {
        var now = Date.now();
        document.querySelectorAll('[data-end]').forEach(function (el) {
            var end = new Date(String(el.getAttribute('data-end')).replace(' ', 'T')).getTime();
            if (isNaN(end)) return;
            var target = el.querySelector('.t') || el;
            var left = end - now;
            if (left <= 0) {
                target.textContent = 'Ended';
                el.classList.add('is-ended');
                el.classList.remove('is-urgent');
                return;
            }
            target.textContent = format(left, el.hasAttribute('data-long'));
            el.classList.toggle('is-urgent', left < 3600 * 1000);
        });
    }
    {
        document.addEventListener('DOMContentLoaded', tick);
        setInterval(tick, 1000);
    }
    window.refreshCountdowns = tick;

    // ---- Password show/hide ----
    document.addEventListener('click', function (ev) {
        var btn = ev.target.closest('[data-toggle-password]');
        if (!btn) return;
        var input = document.getElementById(btn.getAttribute('data-toggle-password'));
        if (!input) return;
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.textContent = show ? 'Hide' : 'Show';
    });
})();

function confirmAction(message) { return confirm(message); }
