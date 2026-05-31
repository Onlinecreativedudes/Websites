/*
 * Pressure Cleaning Perth — front-end interactions
 * Ported from the design export. Vanilla JS, no dependencies.
 * Loaded deferred in the footer (see inc/enqueue.php).
 */
(function () {
  'use strict';

  // Current year in the footer.
  var yr = document.getElementById('yr');
  if (yr) { yr.textContent = new Date().getFullYear(); }

  // Sticky header shadow.
  var head = document.getElementById('head');
  if (head) {
    addEventListener('scroll', function () {
      head.classList.toggle('scrolled', scrollY > 10);
    }, { passive: true });
  }

  // Mobile drawer.
  var drawer = document.getElementById('drawer');
  var burger = document.getElementById('burger');
  var closeDrawer = document.getElementById('closeDrawer');
  if (drawer && burger) {
    burger.addEventListener('click', function () { drawer.classList.add('open'); });
    if (closeDrawer) { closeDrawer.addEventListener('click', function () { drawer.classList.remove('open'); }); }
    drawer.addEventListener('click', function (e) { if (e.target === drawer) { drawer.classList.remove('open'); } });
    drawer.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { drawer.classList.remove('open'); });
    });
  }

  // Reveal on scroll.
  var io = new IntersectionObserver(function (es) {
    es.forEach(function (e) {
      if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });

  // Stagger grid children.
  document.querySelectorAll('.svc-grid,.why-grid,.proc-grid,.rev-grid,.faq-grid,.gal-grid').forEach(function (g) {
    [].slice.call(g.children).forEach(function (c, i) {
      c.style.transitionDelay = ((i % 3) * 0.08) + 's';
      c.classList.add('reveal');
      io.observe(c);
    });
  });

  // Scroll progress bar + parallax.
  var sp = document.createElement('div');
  sp.className = 'scroll-progress';
  document.body.appendChild(sp);
  var ticking = false;
  function onScroll() {
    if (ticking) { return; }
    ticking = true;
    requestAnimationFrame(function () {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      var pct = max > 0 ? (h.scrollTop / max) * 100 : 0;
      sp.style.width = pct + '%';
      var y = window.scrollY;
      var heroEl = document.querySelector('.hero');
      if (heroEl) {
        var heroRect = heroEl.getBoundingClientRect();
        if (heroRect.bottom > 0) { heroEl.style.backgroundPositionY = 'calc(50% + ' + (y * 0.25) + 'px)'; }
      }
      var ctaEl = document.querySelector('.cta-band');
      if (ctaEl) {
        var r = ctaEl.getBoundingClientRect();
        if (r.bottom > 0 && r.top < window.innerHeight) {
          ctaEl.style.backgroundPositionY = 'calc(50% - ' + ((window.innerHeight - r.top) * 0.12) + 'px)';
        }
      }
      var sealImg = document.querySelector('.sealing-img img');
      if (sealImg) {
        var rs = sealImg.parentElement.getBoundingClientRect();
        if (rs.bottom > 0 && rs.top < window.innerHeight) {
          var cs = rs.top + rs.height / 2 - window.innerHeight / 2;
          sealImg.style.transform = 'translateY(' + (cs * -0.06) + 'px) scale(1.08)';
        }
      }
      var ownerImg = document.querySelector('.owner-media .ph img');
      if (ownerImg) {
        var ro = ownerImg.parentElement.getBoundingClientRect();
        if (ro.bottom > 0 && ro.top < window.innerHeight) {
          var co = ro.top + ro.height / 2 - window.innerHeight / 2;
          ownerImg.style.transform = 'translateY(' + (co * -0.04) + 'px) scale(1.05)';
        }
      }
      ticking = false;
    });
  }
  addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Count-up on stat-card numbers.
  document.querySelectorAll('.stat-card b').forEach(function (el) {
    var m = (el.textContent || '').match(/^(\d+)(.*)$/);
    if (!m) { return; }
    var target = parseInt(m[1], 10), suffix = m[2];
    el.textContent = '0' + suffix;
    var io3 = new IntersectionObserver(function (ents, obs) {
      ents.forEach(function (ent) {
        if (!ent.isIntersecting) { return; }
        var dur = 1400, start = performance.now();
        function tick(t) {
          var p = Math.min((t - start) / dur, 1);
          var eased = 1 - Math.pow(1 - p, 3);
          el.textContent = Math.round(target * eased) + suffix;
          if (p < 1) { requestAnimationFrame(tick); }
        }
        requestAnimationFrame(tick);
        obs.unobserve(ent.target);
      });
    }, { threshold: 0.4 });
    io3.observe(el);
  });

  // Gentle mouse tilt on the hero lead card.
  var leadCard = document.querySelector('.hero .lead-card');
  if (leadCard && !matchMedia('(hover:none)').matches) {
    leadCard.classList.add('tilt-ready');
    leadCard.addEventListener('mousemove', function (e) {
      var r = leadCard.getBoundingClientRect();
      var px = (e.clientX - r.left) / r.width - 0.5;
      var py = (e.clientY - r.top) / r.height - 0.5;
      leadCard.style.transform = 'perspective(900px) rotateY(' + (px * 4) + 'deg) rotateX(' + (-py * 4) + 'deg) translateZ(0)';
    });
    leadCard.addEventListener('mouseleave', function () { leadCard.style.transform = ''; });
  }

  // Button hover light follow.
  document.querySelectorAll('.btn').forEach(function (b) {
    b.addEventListener('mousemove', function (e) {
      var r = b.getBoundingClientRect();
      b.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
      b.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
    });
  });

  // Directional reveal helpers.
  document.querySelectorAll('.local .wrap > .reveal:first-child, .about-owner .wrap > .reveal:first-child, .contact .wrap > .reveal:first-child').forEach(function (el) { el.classList.add('r-left'); });
  document.querySelectorAll('.local .wrap > .reveal:last-child, .about-owner .wrap > .reveal:last-child, .contact .wrap > .reveal:last-child').forEach(function (el) { el.classList.add('r-right'); });
  document.querySelectorAll('.sealing-img.reveal').forEach(function (el) { el.classList.add('r-right'); });
  document.querySelectorAll('.sealing-content.reveal').forEach(function (el) { el.classList.add('r-left'); });
})();
