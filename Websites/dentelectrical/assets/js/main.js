(function () {
    'use strict';

    const initStickyHeader = () => {
        const nav = document.getElementById('site-nav');
        if (!nav) return;
        const onScroll = () => {
            if (window.scrollY > 80) nav.classList.add('is-scrolled');
            else nav.classList.remove('is-scrolled');
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    };

    const initMobileNav = () => {
        const toggle = document.querySelector('.nav__toggle');
        const menu = document.getElementById('nav-menu');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', () => {
            const open = menu.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(open));
            document.body.classList.toggle('is-nav-open', open);
        });
        menu.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && menu.classList.contains('is-open')) {
                menu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('is-nav-open');
            }
        });
    };

    const initReveals = () => {
        const reveals = document.querySelectorAll('.reveal');
        if (!reveals.length) return;

        if (typeof IntersectionObserver === 'undefined' ||
            window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            reveals.forEach((el) => el.classList.add('is-visible'));
            return;
        }

        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

        reveals.forEach((el) => io.observe(el));
    };

    const initMobileBarSpacer = () => {
        if (document.querySelector('.mobile-bar')) {
            document.body.classList.add('has-mobile-bar');
        }
    };

    const initSmoothAnchorOffset = () => {
        document.querySelectorAll('a[href^="#"]').forEach((link) => {
            const href = link.getAttribute('href');
            if (!href || href === '#' || href.length < 2) return;
            link.addEventListener('click', (e) => {
                const target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                const navHeight = document.getElementById('site-nav')?.offsetHeight || 0;
                const top = target.getBoundingClientRect().top + window.pageYOffset - navHeight - 12;
                window.scrollTo({ top, behavior: 'smooth' });
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initStickyHeader();
        initMobileNav();
        initReveals();
        initMobileBarSpacer();
        initSmoothAnchorOffset();
    });
})();
