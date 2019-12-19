document.addEventListener('DOMContentLoaded', () => {

    /**
     * Skip link focus fix
     */
    (function () {
        var isIe = /(trident|msie)/i.test(navigator.userAgent);
        if (isIe && document.getElementById && window.addEventListener) {
            window.addEventListener('hashchange', function () {
                var id = location.hash.substring(1),
                    element;
                if (!(/^[A-z0-9_-]+$/.test(id))) return;
                element = document.getElementById(id);
                if (element) {
                    if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                        element.tabIndex = -1;
                    }
                    element.focus();
                }
            }, false);
        }
    })();

    /**
     * Menu toggle handler
     */
    const toggles = document.querySelectorAll('.toggle');
    [...toggles].forEach(toggle => {
        toggle.addEventListener('click', e => {
            const id = toggle.getAttribute('aria-controls');
            const target = document.querySelector(`#${id}`);
            target.classList.toggle('open');
            target.setAttribute('aria-expanded', target.classList.contains('open'));
            const toggles = document.querySelectorAll(`[aria-controls="${id}"]`)
            toggles.forEach(toggle => {
                toggle.setAttribute('aria-expanded', target.classList.contains('open'));
            });
        });
    });

    /**
     * Closes any open sidebar on ESC key
     */
    document.addEventListener('keyup', e => {
        if (e.key == "Esc" || e.keyCode == 27) {
            const opened = document.querySelectorAll('.open');
            if (opened) {
                [...toggles].forEach(toggle => {
                    toggle.setAttribute('aria-expanded', 'false');
                });
                [...opened].forEach(open => {
                    open.classList.remove('open');
                });
            }
        }
    });

    /**
     * Closes sidebar when tapped outside.
     */
    document.addEventListener('click', e => {
        const menuArea = document.querySelector('.menu-area');
        const toggle = document.querySelector('.menu-toggle');
        if (!menuArea.classList.contains('open')) return;
        if (e.path.includes(toggle)) return;
        if (e.clientX < (window.innerWidth - menuArea.offsetWidth)) {
            menuArea.classList.remove('open');
            document.querySelectorAll('.menu-toggle').forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Close the menu when the last focusable element is blurred
    const menuArea = document.querySelector('.menu-area');
    const focusables = menuArea.querySelectorAll('a', 'select', 'input', 'button', 'textarea');
    const lastFocusable = focusables[focusables.length - 1];
    lastFocusable.onblur = e => {
        menuArea.classList.remove('open');
        document.querySelectorAll('.menu-toggle').forEach(toggle => {
            toggle.setAttribute('aria-expanded', 'false');
        });
    };
});