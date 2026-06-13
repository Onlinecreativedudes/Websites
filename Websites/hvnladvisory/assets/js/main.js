(function() {
    'use strict';

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Loader splash — slides away once assets are in (1.7s fallback). */
    const initLoader = () => {
        const loader = document.querySelector('[data-loader]');
        if (!loader) return;
        let done = false;
        const finish = () => {
            if (done) return;
            done = true;
            loader.classList.add('is-done');
            setTimeout(() => { loader.style.display = 'none'; }, 1000);
        };
        setTimeout(finish, 1700);
        window.addEventListener('load', () => setTimeout(finish, 400));
    };

    /* Mobile nav panel */
    const initMobileNav = () => {
        const toggle = document.querySelector('[data-nav-toggle]');
        const menu = document.querySelector('[data-nav-menu]');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', () => {
            const open = menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        menu.addEventListener('click', (e) => {
            if (e.target.closest('a')) {
                menu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    /* Sticky nav shadow state, parallax and scroll-triggered card cascades.
       One rAF loop reading layout each frame, matching the source design. */
    const initScrollEffects = () => {
        const nav = document.querySelector('[data-nav]');
        const parallaxItems = Array.prototype.slice.call(document.querySelectorAll('[data-parallax]'));
        const staggers = Array.prototype.slice.call(document.querySelectorAll('[data-stagger]'));
        if (!nav && !parallaxItems.length && !staggers.length) return;

        const runStaggers = (vh) => {
            if (reduceMotion) return;
            for (let s = 0; s < staggers.length; s++) {
                const grid = staggers[s];
                if (grid.__staggered) continue;
                const r = grid.getBoundingClientRect();
                if (r.top < vh * 0.86 && r.bottom > 0) {
                    grid.__staggered = true;
                    const kids = grid.children;
                    for (let i = 0; i < kids.length; i++) {
                        kids[i].style.animation = 'rvlIn .7s cubic-bezier(.16,1,.3,1) ' + (i * 0.11).toFixed(2) + 's both';
                    }
                }
            }
        };

        const update = () => {
            const vh = window.innerHeight;
            if (nav) {
                if (window.scrollY > 40) nav.classList.add('is-scrolled');
                else nav.classList.remove('is-scrolled');
            }
            if (!reduceMotion) {
                parallaxItems.forEach((el) => {
                    const r = el.getBoundingClientRect();
                    const d = (r.top + r.height / 2 - vh / 2) / vh;
                    const sp = parseFloat(el.getAttribute('data-speed') || '0.12');
                    el.style.transform = 'translate3d(0,' + (-d * sp * 100).toFixed(2) + 'px,0)';
                });
            }
            runStaggers(vh);
        };

        const loop = () => { update(); window.requestAnimationFrame(loop); };
        window.requestAnimationFrame(loop);
        window.addEventListener('resize', update);
    };

    document.addEventListener('DOMContentLoaded', () => {
        initLoader();
        initMobileNav();
        initScrollEffects();
    });
})();
