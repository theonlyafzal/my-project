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
    burger.addEventListener('click', function () {
      nav.classList.toggle('is-open');
    });
  }

  // Scroll reveal
  var reveals = doc.querySelectorAll('.ep-reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('is-visible');
          io.unobserve(e.target);
        }
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
})();
