/**
 * Solar Naturally — front-end behaviour.
 * Vanilla port of the design's React interactions: header scroll state,
 * entrance reveals, parallax, beams canvas, smooth scrolling, assessment
 * modal, gallery tabs + lightbox, and the reviews carousel.
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- header: shrink urgency bar offset, solid state, logo swap */
  var header = document.querySelector('.js-header');
  var headerLogo = document.querySelector('.js-header-logo');
  function onHeaderScroll() {
    if (!header) return;
    var y = window.scrollY;
    header.style.top = Math.max(0, 44 - y) + 'px';
    var solid = y > 16;
    header.classList.toggle('hd--solid', solid);
    if (headerLogo) {
      var want = solid ? headerLogo.getAttribute('data-solid') : headerLogo.getAttribute('data-light');
      if (want && headerLogo.getAttribute('src') !== want) headerLogo.setAttribute('src', want);
    }
  }
  window.addEventListener('scroll', onHeaderScroll, { passive: true });
  window.addEventListener('resize', onHeaderScroll);
  onHeaderScroll();

  /* ---------- reveals: one-shot entrance animation on scroll into view */
  if (!reduceMotion && 'IntersectionObserver' in window) {
    var revealIO = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('r-play');
          revealIO.unobserve(e.target);
        }
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -6% 0px' });
    document.querySelectorAll('[data-rise]').forEach(function (el) { revealIO.observe(el); });
  }

  /* ---------- parallax images */
  var parallaxEls = Array.prototype.slice.call(document.querySelectorAll('.js-parallax'));
  if (parallaxEls.length) {
    if (reduceMotion) {
      parallaxEls.forEach(function (el) {
        el.style.transform = 'scale(' + (parseFloat(el.getAttribute('data-scale')) || 1.2) + ')';
      });
    } else {
      var pRaf = null;
      var updateParallax = function () {
        var vh = window.innerHeight || document.documentElement.clientHeight;
        parallaxEls.forEach(function (el) {
          var parent = el.parentElement;
          if (!parent) return;
          var r = parent.getBoundingClientRect();
          var speed = parseFloat(el.getAttribute('data-speed')) || 0.16;
          var scale = parseFloat(el.getAttribute('data-scale')) || 1.2;
          var offset = r.top + r.height / 2 - vh / 2;
          el.style.willChange = 'transform';
          el.style.transform = 'translate3d(0, ' + (-offset * speed).toFixed(1) + 'px, 0) scale(' + scale + ')';
        });
        pRaf = null;
      };
      var onPScroll = function () { if (!pRaf) pRaf = requestAnimationFrame(updateParallax); };
      window.addEventListener('scroll', onPScroll, { passive: true });
      window.addEventListener('resize', onPScroll);
      updateParallax();
    }
  }

  /* ---------- beams: soft light-beam canvas behind dark sections */
  document.querySelectorAll('.js-beams').forEach(function (wrap) {
    var canvas = wrap.querySelector('canvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    if (!ctx) return;

    var POOLS = { warm: [42, 46, 38, 50], green: [138, 148, 44, 152], gold: [44, 48, 40] };
    var hues = POOLS[wrap.getAttribute('data-palette')] || POOLS.warm;
    var intensity = parseFloat(wrap.getAttribute('data-intensity')) || 0.55;
    var rand = function (a, b) { return a + Math.random() * (b - a); };

    var beams = [], raf = 0, running = false, w = 1, h = 1;

    function makeBeam() {
      return {
        x: rand(-w * 0.12, w * 1.12),
        y: rand(0, h * 1.5),
        width: rand(70, 190),
        length: h * 1.7,
        angle: -32 + rand(-7, 7),
        speed: rand(0.12, 0.42),
        opacity: rand(0.05, 0.14) * intensity,
        hue: hues[Math.floor(Math.random() * hues.length)] + rand(-5, 5),
        pulse: rand(0, Math.PI * 2),
        pulseSpeed: rand(0.006, 0.018)
      };
    }

    function size() {
      var r = wrap.getBoundingClientRect();
      w = Math.max(1, r.width); h = Math.max(1, r.height);
      var dpr = Math.min(window.devicePixelRatio || 1, 1.75);
      canvas.width = Math.round(w * dpr);
      canvas.height = Math.round(h * dpr);
      canvas.style.width = w + 'px';
      canvas.style.height = h + 'px';
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var count = Math.max(6, Math.min(15, Math.round((w * h) / 95000)));
      beams = [];
      for (var i = 0; i < count; i++) beams.push(makeBeam());
    }

    function draw() {
      ctx.clearRect(0, 0, w, h);
      ctx.filter = 'blur(32px)';
      beams.forEach(function (b) {
        b.y -= b.speed;
        b.pulse += b.pulseSpeed;
        if (b.y + b.length < -60) { b.y = h + rand(40, 200); b.x = rand(-w * 0.12, w * 1.12); }
        ctx.save();
        ctx.translate(b.x, b.y);
        ctx.rotate((b.angle * Math.PI) / 180);
        var op = b.opacity * (0.7 + Math.sin(b.pulse) * 0.3);
        var g = ctx.createLinearGradient(0, 0, 0, b.length);
        g.addColorStop(0, 'hsla(' + b.hue + ',92%,60%,0)');
        g.addColorStop(0.42, 'hsla(' + b.hue + ',92%,62%,' + op + ')');
        g.addColorStop(0.6, 'hsla(' + b.hue + ',92%,62%,' + op + ')');
        g.addColorStop(1, 'hsla(' + b.hue + ',92%,60%,0)');
        ctx.fillStyle = g;
        ctx.fillRect(-b.width / 2, 0, b.width, b.length);
        ctx.restore();
      });
      ctx.filter = 'none';
      raf = requestAnimationFrame(draw);
    }

    function start() { if (running || reduceMotion) return; running = true; raf = requestAnimationFrame(draw); }
    function stop() { running = false; if (raf) cancelAnimationFrame(raf); }

    size();
    if (reduceMotion) { draw(); cancelAnimationFrame(raf); }

    new IntersectionObserver(function (es) {
      es.forEach(function (e) { e.isIntersecting ? start() : stop(); });
    }, { threshold: 0 }).observe(wrap);

    var rt = 0;
    window.addEventListener('resize', function () { clearTimeout(rt); rt = setTimeout(size, 160); });
  });

  /* ---------- smooth scrolling */
  function scrollToTarget(sel) {
    var el = document.querySelector(sel);
    if (!el) return;
    var y = el.getBoundingClientRect().top + window.scrollY - 8;
    window.scrollTo({ top: y, behavior: 'smooth' });
  }
  document.addEventListener('click', function (e) {
    var top = e.target.closest('.js-scroll-top');
    if (top) { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); return; }
    var link = e.target.closest('.js-scroll-link');
    if (link) {
      var sel = link.getAttribute('data-target') || link.getAttribute('href');
      if (sel && sel.charAt(0) === '#') { e.preventDefault(); scrollToTarget(sel); }
    }
  });

  /* ---------- assessment modal */
  var modal = document.querySelector('.js-assess-modal');
  function openModal() {
    if (!modal) return;
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }
  function closeModal() {
    if (!modal) return;
    modal.hidden = true;
    document.body.style.overflow = '';
  }
  document.addEventListener('click', function (e) {
    if (e.target.closest('.js-open-assess')) { openModal(); return; }
    if (e.target.closest('.js-close-assess')) { closeModal(); return; }
    if (modal && !modal.hidden && e.target === modal) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal && !modal.hidden) closeModal();
  });

  /* ---------- gallery: tab filter + lightbox */
  document.querySelectorAll('.js-gallery').forEach(function (gal) {
    var tabs = gal.querySelectorAll('.gal__tab');
    var cards = Array.prototype.slice.call(gal.querySelectorAll('.gcard'));
    var lb = gal.querySelector('.js-lightbox');
    var current = -1;

    function visibleCards() {
      return cards.filter(function (c) { return c.getAttribute('data-filtered') !== 'out'; });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) { t.classList.toggle('on', t === tab); });
        var want = tab.getAttribute('data-tab');
        cards.forEach(function (c) {
          var show = want === 'All' || c.getAttribute('data-cat') === want;
          c.setAttribute('data-filtered', show ? 'in' : 'out');
        });
      });
    });

    function showLightbox(idx) {
      var items = visibleCards();
      if (!lb || !items.length) return;
      current = ((idx % items.length) + items.length) % items.length;
      var card = items[current];
      var img = card.querySelector('img');
      lb.querySelector('.lb__fig img').src = img ? img.src : '';
      lb.querySelector('.lb__fig img').alt = img ? img.alt : '';
      lb.querySelector('.lb__cat').textContent = (card.querySelector('.gcard__cat') || {}).textContent || '';
      lb.querySelector('.lb__title').textContent = (card.querySelector('.gcard__title') || {}).textContent || '';
      lb.querySelector('.lb__sub').textContent = (card.querySelector('.gcard__sub') || {}).textContent || '';
      lb.hidden = false;
    }
    function closeLightbox() { if (lb) { lb.hidden = true; current = -1; } }

    cards.forEach(function (card) {
      card.addEventListener('click', function () {
        showLightbox(visibleCards().indexOf(card));
      });
    });
    if (lb) {
      lb.addEventListener('click', function (e) {
        if (e.target.closest('.js-lb-close')) { closeLightbox(); return; }
        if (e.target.closest('.js-lb-prev')) { e.stopPropagation(); showLightbox(current - 1); return; }
        if (e.target.closest('.js-lb-next')) { e.stopPropagation(); showLightbox(current + 1); return; }
        if (e.target === lb) closeLightbox();
      });
      document.addEventListener('keydown', function (e) {
        if (lb.hidden) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') showLightbox(current + 1);
        if (e.key === 'ArrowLeft') showLightbox(current - 1);
      });
    }
  });

  /* ---------- reviews carousel */
  document.querySelectorAll('.js-reviews').forEach(function (rev) {
    var dataEl = rev.querySelector('.js-rev-data');
    if (!dataEl) return;
    var reviews;
    try { reviews = JSON.parse(dataEl.textContent); } catch (err) { return; }
    if (!reviews || !reviews.length) return;

    var quote = rev.querySelector('.js-rev-quote');
    var name = rev.querySelector('.js-rev-name');
    var loc = rev.querySelector('.js-rev-loc');
    var dots = rev.querySelectorAll('.rev__dot');
    var i = 0, timer = null, paused = false;

    function render() {
      var r = reviews[i];
      quote.textContent = '“' + r.text + '”';
      name.textContent = r.name;
      loc.textContent = r.loc;
      dots.forEach(function (d, k) { d.classList.toggle('on', k === i); });
    }
    function go(n) { i = ((n % reviews.length) + reviews.length) % reviews.length; render(); }
    function tick() {
      timer = setInterval(function () { if (!paused) go(i + 1); }, 6000);
    }

    rev.addEventListener('mouseenter', function () { paused = true; });
    rev.addEventListener('mouseleave', function () { paused = false; });
    rev.addEventListener('click', function (e) {
      if (e.target.closest('.js-rev-prev')) go(i - 1);
      else if (e.target.closest('.js-rev-next')) go(i + 1);
      else {
        var dot = e.target.closest('.rev__dot');
        if (dot) go(parseInt(dot.getAttribute('data-index'), 10) || 0);
      }
    });

    render();
    tick();
  });
})();
