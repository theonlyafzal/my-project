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
      var lastScroll = 0;
      var onScroll = function () {
        var st = window.scrollY;
        if (st > 10) header.classList.add('is-scrolled');
        else header.classList.remove('is-scrolled');
        lastScroll = st;
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }

    // Mobile menu toggle
    var burger = document.getElementById('epBurger');
    var nav = document.getElementById('epNav');
    if (burger && nav) {
      burger.addEventListener('click', function () {
        nav.classList.toggle('is-open');
        burger.classList.toggle('is-active');
      });
      // Close nav on outside click
      document.addEventListener('click', function (e) {
        if (!nav.contains(e.target) && !burger.contains(e.target) && nav.classList.contains('is-open')) {
          nav.classList.remove('is-open');
          burger.classList.remove('is-active');
        }
      });
    }

    // Header search -> redirect to /products/?s=query
    var headerSearch = document.getElementById('ep-header-search');
    if (headerSearch) {
      headerSearch.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          var q = headerSearch.value.trim();
          if (q) {
            window.location.href = '/product/?s=' + encodeURIComponent(q);
          }
        }
      });
    }

    // Smooth scroll for anchor links
    document.addEventListener('click', function (e) {
      var link = e.target.closest('a[href^="#"]');
      if (link) {
        var target = document.querySelector(link.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });

    // Scroll reveal (reusable so AJAX-loaded content is also revealed)
    var epRevealObserver = null;
    function epRevealObserve(root) {
      var scope = root || doc;
      var reveals = scope.querySelectorAll('.ep-reveal:not(.is-visible)');
      if (!reveals.length) return;
      if (!('IntersectionObserver' in window)) {
        forEach(reveals, function (el) { el.classList.add('is-visible'); });
        return;
      }
      if (!epRevealObserver) {
        epRevealObserver = new IntersectionObserver(function (entries) {
          forEach(entries, function (e) {
            if (e.isIntersecting) {
              e.target.classList.add('is-visible');
              epRevealObserver.unobserve(e.target);
            }
          });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
      }
      forEach(reveals, function (el) { epRevealObserver.observe(el); });
    }
    window.epRevealObserve = epRevealObserve;
    epRevealObserve(doc);

    // ---------- Auth modals (separate login / register) ----------
    var MODALS = ['epLoginModal', 'epRegisterModal'];

    function syncLock() {
      doc.body.classList.toggle('ep-modal-open', !!doc.querySelector('.ep-modal.is-open'));
    }
    function closeModal(m) {
      if (!m) return;
      m.classList.remove('is-open');
      m.setAttribute('aria-hidden', 'true');
      syncLock();
    }
    function openModal(id) {
      forEach(MODALS, function (mid) {
        var m = document.getElementById(mid);
        if (m) { m.classList.remove('is-open'); m.setAttribute('aria-hidden', 'true'); }
      });
      var m = document.getElementById(id);
      if (!m) return;
      m.classList.add('is-open');
      m.setAttribute('aria-hidden', 'false');
      var msg = m.querySelector('.ep-auth__msg');
      if (msg) { msg.textContent = ''; msg.className = 'ep-auth__msg'; }
      syncLock();
    }
    function epOpenAuth(tab) {
      openModal(tab === 'register' ? 'epRegisterModal' : 'epLoginModal');
    }

    // Delegated open / close
    document.addEventListener('click', function (e) {
      var opener = e.target.closest && e.target.closest('[data-ep-auth]');
      if (opener) {
        e.preventDefault();
        epOpenAuth(opener.getAttribute('data-tab') || 'login');
        return;
      }
      if (e.target.closest && e.target.closest('[data-ep-modal-close]')) {
        closeModal(e.target.closest('.ep-modal'));
        return;
      }
      if (e.target.closest && e.target.closest('.ep-modal__overlay')) {
        closeModal(e.target.closest('.ep-modal'));
        return;
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { var o = doc.querySelector('.ep-modal.is-open'); closeModal(o); }
    });

    // AJAX auth submit
    forEach(['epLoginForm', 'epRegisterForm'], function (id) {
      var form = document.getElementById(id);
      if (!form) return;
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var mode = form.getAttribute('data-mode');
        var msg = form.closest('.ep-modal').querySelector('.ep-auth__msg');
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
            }
          } else {
            if (msg) { msg.textContent = res.data; msg.className = 'ep-auth__msg is-err'; }
          }
        }).fail(function () {
          if (msg) { msg.textContent = 'Something went wrong. Please try again.'; msg.className = 'ep-auth__msg is-err'; }
        });
      });
    });

    // FAQ accordion
    document.addEventListener('click', function (e) {
      var toggle = e.target.closest('.ep-faq__toggle');
      if (!toggle) return;
      var item = toggle.closest('.ep-faq__item');
      if (!item) return;
      var isOpen = item.classList.contains('is-open');
      // Close all others
      forEach(document.querySelectorAll('.ep-faq__item.is-open'), function (el) {
        el.classList.remove('is-open');
        el.querySelector('.ep-faq__toggle').setAttribute('aria-expanded', 'false');
      });
      // Toggle current
      if (!isOpen) {
        item.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
      }
    });
  });
})();
