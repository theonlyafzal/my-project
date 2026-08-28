jQuery(document).ready(function ($) {

   // Search focus handler
  function searchFocusHandler() {
    const searchFirstTab = $('.inner_searchbox input[type="search"]');
    const searchLastTab = $('button.search-close');

    $(".open-search").click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      $('body').addClass("search-focus");
      searchFirstTab.focus();
    });

    $("button.search-close").click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      $('body').removeClass("search-focus");
      $(".open-search").focus();
    });

    // Redirect last tab to first input
    searchLastTab.on('keydown', function(e) {
      if ($('body').hasClass('search-focus') && e.which === 9 && !e.shiftKey) {
        e.preventDefault();
        searchFirstTab.focus();
      }
    });

    // Redirect first shift+tab to last input
    searchFirstTab.on('keydown', function(e) {
      if ($('body').hasClass('search-focus') && e.which === 9 && e.shiftKey) {
        e.preventDefault();
        searchLastTab.focus();
      }
    });

    // Allow escape key to close menu
    $('.inner_searchbox').on('keyup', function(e) {
      if ($('body').hasClass('search-focus') && e.keyCode === 27) {
        $('body').removeClass('search-focus');
        searchLastTab.focus();
      }
    });
  }

  // Call the search focus handler
  searchFocusHandler();

    // Preloader
    jQuery('#preloader').delay(1500).fadeOut();
    jQuery('#loader').delay(1500).fadeOut("slow");

    // Scroll to Top Button
    if ($('.go-to-top').length) {
        var scrollTrigger = $('body').position(); // px
        var goToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > 150) {
                $('.footer-go-to-top').addClass('show');
            } else {
                $('.footer-go-to-top').removeClass('show');
            }
        };
        goToTop();
        $(window).on('scroll', function () {
            goToTop();
        });
        $('.go-to-top').on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: scrollTrigger.top
            }, 700);
        });
    }

    // Mobile Menu Focus
    $(window).resize(rev_digital_products_navTrapp);

    function rev_digital_products_navTrapp() {
        var width = $(window).width();
        if (width < 992) {
            $('.main-navigation').on('keydown', function (e) {
                if ($('.main-navigation').hasClass('toggled')) {
                    var focusableEls = $('.main-navigation a[href]:not([disabled]), .main-navigation button');
                    var firstFocusableEl = focusableEls[0];
                    var lastFocusableEl = focusableEls[focusableEls.length - 1];
                    var KEYCODE_TAB = 9;
                    var KEYCODE_ESC = 27;
                    if (e.key === 'Tab' || e.keyCode === KEYCODE_TAB) {
                        if (e.shiftKey) { // shift + tab
                            if (document.activeElement === firstFocusableEl) {
                                lastFocusableEl.focus();
                                e.preventDefault();
                            }
                        } else { // tab
                            if (document.activeElement === lastFocusableEl) {
                                firstFocusableEl.focus();
                                e.preventDefault();
                            }
                        }
                    }
                    if (e.keyCode === KEYCODE_ESC) {
                        $('.main-navigation').removeClass('toggled');
                        return;
                    }
                }
            });
        }
    }

    rev_digital_products_navTrapp();

    // Main Slider
    var owl = jQuery('#main-slider-wrap .owl-carousel');
    owl.owlCarousel({
        margin: 20,
        nav: true,
        autoplay: true,
        lazyLoad: false,
        autoplayTimeout: 2000,
        loop: true,
        rtl: $('html').attr('dir') === 'rtl',
        dots: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        },
        autoplayHoverPause: true,
        mouseDrag: true
    });

});

// dropdown category
jQuery(document).ready(function(){
  jQuery(".cat-dropdown").hide();
  
  jQuery("button.category-button").click(function(){
    jQuery(".cat-dropdown").toggle();
  });

  // Handle focus using Tab and Shift+Tab
  jQuery(".category-button, .cat-dropdown").on("keydown", function(e) {
    var dropdownItems = jQuery(".cat-dropdown").find("a"); // Assuming dropdown items are represented by <a> tags
    
    if (e.keyCode === 9) { // Tab key
      if (!e.shiftKey && document.activeElement === dropdownItems.last().get(0)) {
        e.preventDefault();
        jQuery(".category-button").focus();
      } else if (e.shiftKey && document.activeElement === dropdownItems.first().get(0)) {
        e.preventDefault();
        jQuery(".category-button").focus();
      }
    }
  });
});

// TABS
function rev_digital_products_projetcs_tab(evt, tabId) {
    // Hide all tab content elements
    jQuery('.tabcontent').hide();
    
    // Show the selected tab content
    jQuery('#' + tabId).show();

    // Destroy any existing Owl Carousel instance in the current tab
    jQuery('.tabcontent .owl-carousel').trigger('destroy.owl.carousel');
    
    // Initialize Owl Carousel only in the active tab content
    jQuery('#' + tabId + ' .owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 3 }
        }
    });

    // Remove 'active' class from all tab buttons
    jQuery('.tab button').removeClass('active');
    
    // Add 'active' class to the clicked tab button
    jQuery(evt.currentTarget).addClass('active');
}

// On page load, trigger click on the first tab to initialize the first carousel
jQuery(document).ready(function($) {
    $('.tab button').first().trigger('click');
});

document.addEventListener('DOMContentLoaded', function() {
  const header = document.querySelector('.sticky-header');
  if (header) { // Check if header exists
      window.addEventListener('scroll', function() {
      if (window.scrollY > 0) {
          header.classList.add('stickyon');
      } else {
          header.classList.remove('stickyon');
      }
      });
  }
});