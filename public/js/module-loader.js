/**
 * Preloader reutilizable para contenedores que cargan datos (fetch/API).
 */
(function (global) {
    'use strict';

    const defaults = {
        message: 'Cargando datos...',
        minHeight: '220px',
    };

    function resolve(el) {
        if (!el) return null;
        if (typeof el === 'string') return document.querySelector(el);
        return el;
    }

    function ensureHost(target, options) {
        target.classList.add('module-loader-host');
        if (options.minHeight) {
            target.style.minHeight = options.minHeight;
        }
        const pos = global.getComputedStyle(target).position;
        if (pos === 'static') {
            target.style.position = 'relative';
        }
    }

    function getOverlay(target) {
        let overlay = target.querySelector(':scope > .module-loader-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'module-loader-overlay';
            overlay.setAttribute('aria-hidden', 'true');
            overlay.innerHTML =
                '<div class="module-loader-box" role="status" aria-live="polite">' +
                '<div class="module-loader-spinner" aria-hidden="true"></div>' +
                '<p class="module-loader-text"></p>' +
                '</div>';
            target.appendChild(overlay);
        }
        return overlay;
    }

    function show(el, options) {
        options = Object.assign({}, defaults, options || {});
        const target = resolve(el);
        if (!target) return;

        ensureHost(target, options);
        const overlay = getOverlay(target);
        const text = overlay.querySelector('.module-loader-text');
        if (text) text.textContent = options.message;

        target.setAttribute('data-module-loading', '1');
        overlay.classList.add('is-active');
        overlay.setAttribute('aria-hidden', 'false');
    }

    function hide(el) {
        const target = resolve(el);
        if (!target) return;

        target.removeAttribute('data-module-loading');
        const overlay = target.querySelector(':scope > .module-loader-overlay');
        if (overlay) {
            overlay.classList.remove('is-active');
            overlay.setAttribute('aria-hidden', 'true');
        }
    }

    async function run(el, fn, options) {
        show(el, options);
        try {
            return await fn();
        } finally {
            hide(el);
        }
    }

    global.ModuleLoader = {
        show: show,
        hide: hide,
        run: run,
        defaults: defaults,
    };
})(typeof window !== 'undefined' ? window : this);
