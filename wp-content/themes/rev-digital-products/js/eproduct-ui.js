(function () {
  'use strict';
  var doc = document.documentElement;
  doc.classList.add('js');

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  function forEach(nodes, cb) { Array.prototype.forEach.call(nodes, cb); }

  ready(function () {
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
        forEach(entries, function (e) {
          if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
        });
      }, { threshold: 0.12 });
      forEach(reveals, function (el) { io.observe(el); });
    } else {
      forEach(reveals, function (el) { el.classList.add('is-visible'); });
    }

    // ---------- Auth modal ----------
    function getModal() { return document.getElementById('epAuthModal'); }

    function epOpenAuth(tab) {
      var modal = getModal();
      if (!modal) return;
      modal.setAttribute('aria-hidden', 'false');
      modal.classList.add('is-open');
      doc.body.classList.add('ep-modal-open');
      epSetTab(tab || 'login');
      var msg = document.getElementById('epAuthMsg');
      if (msg) { msg.textContent = ''; msg.className = 'ep-auth__msg'; }
    }
    function epCloseAuth() {
      var modal = getModal();
      if (!modal) return;
      modal.setAttribute('aria-hidden', 'true');
      modal.classList.remove('is-open');
      doc.body.classList.remove('ep-modal-open');
    }
    function epSetTab(tab) {
      var modal = getModal();
      if (!modal) return;
      forEach(modal.querySelectorAll('.ep-tab'), function (t) {
        t.classList.toggle('active', t.getAttribute('data-tab') === tab);
      });
      var lf = document.getElementById('epLoginForm'), rf = document.getElementById('epRegisterForm');
      if (lf) lf.hidden = tab !== 'login';
      if (rf) rf.hidden = tab !== 'register';
    }

    // Delegated open / close / tab switch
    document.addEventListener('click', function (e) {
      var opener = e.target.closest && e.target.closest('[data-ep-auth]');
      if (opener) {
        e.preventDefault();
        epOpenAuth(opener.getAttribute('data-tab') || 'login');
        return;
      }
      if (e.target.closest && e.target.closest('[data-ep-modal-close]')) { epCloseAuth(); return; }
      if (e.target.closest && e.target.closest('.ep-modal__overlay')) { epCloseAuth(); return; }
      var tab = e.target.closest && e.target.closest('.ep-tab');
      var m = getModal();
      if (tab && m && m.contains(tab)) { epSetTab(tab.getAttribute('data-tab')); }
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') epCloseAuth(); });

    // AJAX auth submit
    forEach(['epLoginForm', 'epRegisterForm'], function (id) {
      var form = document.getElementById(id);
      if (!form) return;
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var mode = form.getAttribute('data-mode');
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
  });
})();
