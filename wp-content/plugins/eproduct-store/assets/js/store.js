jQuery(function ($) {

    /* ---------- Product filter (AJAX) ---------- */
    function epLoad(cat, search) {
        if (cat === undefined) cat = $('.ep-cat.active').data('cat') || '';
        if (search === undefined) search = $('#ep-search').val() || '';
        $.post(EP.ajax, { action: 'eproduct_filter', cat: cat, search: search }, function (html) {
            $('#ep-grid').html(html);
            if (window.epRevealObserve) window.epRevealObserve(document.getElementById('ep-grid'));
        });
    }

    function epFilterByCategory(slug) {
        // Update toolbar buttons
        $('.ep-cat').removeClass('active');
        $('.ep-cat[data-cat="' + slug + '"]').addClass('active');
        // Scroll to products grid
        var $grid = $('#ep-store-grid');
        if ($grid.length) {
            $('html, body').animate({ scrollTop: $grid.offset().top - 80 }, 400);
        }
        // Fire AJAX filter
        epLoad(slug, '');
    }

    // Toolbar category buttons
    $('#ep-search').on('input', function () {
        clearTimeout(window._epT);
        window._epT = setTimeout(function () { epLoad(); }, 300);
    });
    $(document).on('click', '.ep-cat', function () {
        $('.ep-cat').removeClass('active');
        $(this).addClass('active');
        epLoad();
    });

    // Header category dropdown links
    $(document).on('click', '.ep-cat-dropdown__menu a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        // Extract slug from category link (e.g. /product-category/graphics/)
        var match = href.match(/\/product-category\/([^/]+)\/?$/);
        if (match) {
            epFilterByCategory(match[1]);
        } else {
            // "All Products" link - show all
            epFilterByCategory('');
        }
        $('.ep-cat-dropdown').removeClass('is-open');
    });

    // Popular category grid cards
    $(document).on('click', '.ep-cat-card', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var match = href.match(/\/product-category\/([^/]+)\/?$/);
        if (match) {
            epFilterByCategory(match[1]);
        }
    });

    // Product card category tags
    $(document).on('click', '.ep-card__cat-tag', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var slug = $(this).data('cat-slug') || $(this).text().trim().toLowerCase().replace(/\s+/g, '-');
        epFilterByCategory(slug);
    });

    /* ---------- Carousel ---------- */
    $(document).on('click', '[data-carousel-prev]', function () {
        var track = $(this).closest('[data-carousel]').find('[data-carousel-track]');
        var slideW = track.children('.ep-carousel__slide').outerWidth(true);
        track[0].scrollBy({ left: -slideW * 2, behavior: 'smooth' });
    });
    $(document).on('click', '[data-carousel-next]', function () {
        var track = $(this).closest('[data-carousel]').find('[data-carousel-track]');
        var slideW = track.children('.ep-carousel__slide').outerWidth(true);
        track[0].scrollBy({ left: slideW * 2, behavior: 'smooth' });
    });

    /* ---------- Tabs ---------- */
    $(document).on('click', '[data-tab]', function () {
        var tab = $(this).data('tab');
        $(this).closest('[data-tabs]').find('[data-tab]').removeClass('active');
        $(this).addClass('active');
        $(this).closest('[data-tabs]').find('[data-tab-panel]').removeClass('active');
        $(this).closest('[data-tabs]').find('[data-tab-panel="' + tab + '"]').addClass('active');
    });

    /* ---------- Quick View ---------- */
    $(document).on('click', '[data-quick-view]', function (e) {
        e.preventDefault();
        var id = $(this).data('quick-view');
        var $modal = $('#epQuickView');
        var $content = $('#epQvContent');
        $content.html('<div class="ep-qv-loading">Loading...</div>');
        $modal.addClass('is-open');
        $('body').addClass('ep-modal-open');

        $.post(EP.ajax, { action: 'eproduct_quick_view', product_id: id }, function (html) {
            $content.html(html);
        }).fail(function() {
            $content.html('<p>Could not load product details.</p>');
        });
    });

    $(document).on('click', '[data-qv-close]', function () {
        $('#epQuickView').removeClass('is-open');
        $('body').removeClass('ep-modal-open');
    });

    /* ---------- Wishlist toggle ---------- */
    $(document).on('click', '[data-wishlist]', function (e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.toggleClass('is-active');
        if ($btn.hasClass('is-active')) {
            $btn.css('color', '#ec4899');
            $btn.find('svg').attr('fill', '#ec4899');
        } else {
            $btn.css('color', '');
            $btn.find('svg').attr('fill', 'none');
        }
    });

    /* ---------- Cart add notification ---------- */
    $(document).on('click', '.ep-card__action--cart, .ep-card__cart', function (e) {
        var $notify = $('#epCartNotify');
        $notify.addClass('is-show');
        clearTimeout(window._epNotifyT);
        window._epNotifyT = setTimeout(function () {
            $notify.removeClass('is-show');
        }, 4000);
    });

    $(document).on('click', '[data-notify-close]', function () {
        $('#epCartNotify').removeClass('is-show');
    });

    /* ---------- Newsletter form ---------- */
    $(document).on('submit', '[data-newsletter]', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $input = $form.find('input[type="email"]');
        var $btn = $form.find('button');
        $btn.text('Subscribed!').prop('disabled', true).css('background', '#10b981');
        $input.val('');
        setTimeout(function () {
            $btn.text('Sign Up').prop('disabled', false).css('background', '');
        }, 3000);
    });

    /* ---------- Mobile burger ---------- */
    $('#epBurger').on('click', function () {
        $(this).toggleClass('is-active');
        $('#epNav').toggleClass('is-open');
    });

    /* ---------- Category dropdown ---------- */
    $('#epCatToggle').on('click', function (e) {
        e.stopPropagation();
        $(this).closest('.ep-cat-dropdown').toggleClass('is-open');
    });
    $(document).on('click', function () {
        $('.ep-cat-dropdown').removeClass('is-open');
    });

    /* ---------- Sticky header shadow ---------- */
    $(window).on('scroll', function () {
        $('#epHeader').toggleClass('is-scrolled', $(this).scrollTop() > 20);
    });
});
