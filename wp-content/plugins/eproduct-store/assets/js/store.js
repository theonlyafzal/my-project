jQuery(function ($) {

    /* ---------- Product filter (AJAX) ---------- */
    function epLoad() {
        var cat = $('.ep-cat.active').data('cat');
        var s = $('#ep-search').val();
        $.post(EP.ajax, { action: 'eproduct_filter', cat: cat, search: s }, function (html) {
            $('#ep-grid').html(html);
            if (window.epRevealObserve) window.epRevealObserve(document.getElementById('ep-grid'));
        });
    }
    $('#ep-search').on('input', function () {
        clearTimeout(window._epT);
        window._epT = setTimeout(epLoad, 300);
    });
    $('.ep-cat').on('click', function () {
        $('.ep-cat').removeClass('active');
        $(this).addClass('active');
        epLoad();
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
