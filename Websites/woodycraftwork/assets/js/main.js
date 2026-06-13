(function () {
    'use strict';

    /* ---- Mobile drawer ---- */
    var initDrawer = function () {
        var drawer = document.getElementById('drawer');
        var burger = document.querySelector('.burger');
        if (!drawer || !burger) { return; }

        var open = function () {
            drawer.classList.add('open');
            burger.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        };
        var close = function () {
            drawer.classList.remove('open');
            burger.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        };

        burger.addEventListener('click', open);
        drawer.querySelectorAll('[data-drawer-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });
        drawer.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', close);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { close(); }
        });
    };

    /* ---- Sticky header + hero parallax ---- */
    var initScroll = function () {
        var header = document.getElementById('site-header');
        var heroBg = document.getElementById('hero-bg');
        var ticking = false;

        var onScroll = function () {
            var y = window.scrollY;
            if (header) { header.classList.toggle('scrolled', y > 60); }
            if (heroBg && y < window.innerHeight) {
                heroBg.style.transform = 'translateY(' + (y * 0.35) + 'px)';
            }
            ticking = false;
        };

        window.addEventListener('scroll', function () {
            if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
        }, { passive: true });
        onScroll();
    };

    /* ---- Reveal on scroll ---- */
    var initReveal = function () {
        var els = document.querySelectorAll('.reveal');
        if (!els.length || !('IntersectionObserver' in window)) {
            els.forEach(function (el) { el.classList.add('in'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function (el) { io.observe(el); });
    };

    /* ---- Animated stat counter ---- */
    var initCounter = function () {
        var stat = document.querySelector('[data-count]');
        if (!stat || !('IntersectionObserver' in window)) { return; }
        var target = parseInt(stat.dataset.count, 10) || 0;
        var so = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) { return; }
                var n = 0;
                var step = Math.max(1, Math.round(target / 40));
                var t = setInterval(function () {
                    n += step;
                    if (n >= target) { n = target; clearInterval(t); }
                    stat.textContent = n + '+';
                }, 28);
                so.unobserve(e.target);
            });
        }, { threshold: 0.5 });
        so.observe(stat);
    };

    /* ---- Gallery lightbox ---- */
    var initLightbox = function () {
        var grid = document.getElementById('gallery-grid');
        var lb = document.getElementById('lightbox');
        if (!grid || !lb) { return; }

        var cells = Array.prototype.slice.call(grid.querySelectorAll('.cell'));
        var lbImg = document.getElementById('lb-img');
        var index = 0;

        var show = function () {
            var src = cells[index].querySelector('img').currentSrc || cells[index].querySelector('img').src;
            var alt = cells[index].querySelector('img').getAttribute('alt') || '';
            lbImg.src = src;
            lbImg.alt = alt;
        };
        var openLb = function (i) { index = i; show(); lb.classList.add('open'); document.body.style.overflow = 'hidden'; };
        var closeLb = function () { lb.classList.remove('open'); document.body.style.overflow = ''; };
        var step = function (d) { index = (index + d + cells.length) % cells.length; show(); };

        cells.forEach(function (c, i) {
            c.addEventListener('click', function (e) { e.preventDefault(); openLb(i); });
        });
        lb.querySelector('.lb-close').addEventListener('click', closeLb);
        lb.querySelector('.lb-prev').addEventListener('click', function () { step(-1); });
        lb.querySelector('.lb-next').addEventListener('click', function () { step(1); });
        lb.addEventListener('click', function (e) { if (e.target === lb) { closeLb(); } });
        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('open')) { return; }
            if (e.key === 'Escape') { closeLb(); }
            if (e.key === 'ArrowLeft') { step(-1); }
            if (e.key === 'ArrowRight') { step(1); }
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        initDrawer();
        initScroll();
        initReveal();
        initCounter();
        initLightbox();
    });
})();
