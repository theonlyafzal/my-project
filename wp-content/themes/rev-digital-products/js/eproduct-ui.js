(function () {
  var doc = document.documentElement;
  doc.classList.add('js');

  // Sticky header shadow on scroll
  var header = document.getElementById('epHeader');
  if (header) {
    var onScroll = function () {
      if (window.scrollY > 8) header.classList.add('is-scrolled');
      else header.classList.remove('is-scrolled');
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Mobile menu toggle
  var burger = document.getElementById('epBurger');
  var nav = document.getElementById('epNav');
  if (burger && nav) {
    burger.addEventListener('click', function () { nav.classList.toggle('is-open'); });
  }

  // Scroll reveal
  var reveals = doc.querySelectorAll('.ep-reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    window.epRevealObserve = function (root) {
      (root || doc).querySelectorAll('.ep-reveal').forEach(function (el) { io.observe(el); });
    };
    window.epRevealObserve(doc);
  } else {
    reveals.forEach(function (el) { el.classList.add('is-visible'); });
    window.epRevealObserve = function () {};
  }

  // ---------- Auth modal ----------
  var modal = document.getElementById('epAuthModal');
  function epOpenAuth(tab) {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'false');
    modal.classList.add('is-open');
    doc.body.classList.add('ep-modal-open');
    epSetTab(tab || 'login');
    var msg = document.getElementById('epAuthMsg');
    if (msg) { msg.textContent = ''; msg.className = 'ep-auth__msg'; }
  }
  function epCloseAuth() {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'true');
    modal.classList.remove('is-open');
    doc.body.classList.remove('ep-modal-open');
  }
  function epSetTab(tab) {
    if (!modal) return;
    modal.querySelectorAll('.ep-tab').forEach(function (t) { t.classList.toggle('active', t.dataset.tab === tab); });
    var lf = document.getElementById('epLoginForm'), rf = document.getElementById('epRegisterForm');
    if (lf) lf.hidden = tab !== 'login';
    if (rf) rf.hidden = tab !== 'register';
  }

  doc.querySelectorAll('[data-ep-auth]').forEach(function (el) {
    el.addEventListener('click', function (e) { e.preventDefault(); epOpenAuth(el.dataset.tab || 'login'); });
  });
  doc.querySelectorAll('[data-ep-modal-close]').forEach(function (el) {
    el.addEventListener('click', epCloseAuth);
  });
  doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') epCloseAuth(); });

  if (modal) {
    modal.querySelectorAll('.ep-tab').forEach(function (t) {
      t.addEventListener('click', function () { epSetTab(t.dataset.tab); });
    });

    ['epLoginForm', 'epRegisterForm'].forEach(function (id) {
      var form = document.getElementById(id);
      if (!form) return;
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var mode = form.dataset.mode;
        var msg = document.getElementById('epAuthMsg');
        var fd = new FormData(form);
        var data = {
          action: mode === 'login' ? 'eproduct_login' : 'eproduct_register',
          nonce: (window.EP && EP.authNonce) || '',
          log: fd.get('log') || '',
          pwd: fd.get('pwd') || '',
          user_login: fd.get('user_login') || '',
          user_email: fd.get('user_email') || ''
        };
        if (msg) { msg.textContent = 'Please wait...'; msg.className = 'ep-auth__msg is-info'; }
        jQuery.post(EP.ajax, data, function (res) {
          if (res.success) {
            if (mode === 'login') {
              window.location.href = res.data.redirect;
            } else {
              if (msg) { msg.textContent = res.data.message; msg.className = 'ep-auth__msg is-ok'; }
              form.reset();
              epSetTab('login');
            }
          } else {
            if (msg) { msg.textContent = res.data; msg.className = 'ep-auth__msg is-err'; }
          }
        }).fail(function () {
          if (msg) { msg.textContent = 'Something went wrong. Please try again.'; msg.className = 'ep-auth__msg is-err'; }
        });
      });
    });
  }
})();
