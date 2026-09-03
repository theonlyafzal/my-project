jQuery(function ($) {

    /* ============================================================
       CART DRAWER
       ============================================================ */

    /* --- helpers --- */
    function epOpenDrawer() {
        $('#epCartDrawer').attr('aria-hidden', 'false');
        $('body').addClass('ep-drawer-open');
    }
    function epCloseDrawer() {
        $('#epCartDrawer').attr('aria-hidden', 'true');
        $('body').removeClass('ep-drawer-open');
    }

    /* --- render items into drawer --- */
    function epRenderCart(data) {
        var items = data.items || [];
        var total = data.total || 0;
        var count = items.length;
        $('#epCartCount').text(count);
        var $body = $('#epCartBody');
        var $foot = $('#epCartFoot');
        $body.empty();
        if (count === 0) {
            $body.html('<div class="ep-drawer__empty">Your cart is empty.</div>');
            $foot.hide();
            return;
        }
        var html = '';
        for (var i = 0; i < items.length; i++) {
            var it = items[i];
            html += '<div class="ep-drawer__item" data-cart-item="' + it.id + '">';
            if (it.img) {
                html += '<img class="ep-drawer__item-img" src="' + it.img + '" alt="">';
            }
            html += '<div class="ep-drawer__item-info">';
            html += '<p class="ep-drawer__item-title">' + $('<span>').text(it.title).html() + '</p>';
            html += '<span class="ep-drawer__item-price">$' + it.price.toFixed(2) + '</span>';
            html += ' <button class="ep-drawer__item-remove" data-remove-cart="' + it.id + '">Remove</button>';
            html += '</div></div>';
        }
        $body.html(html);
        $('#epCartTotal').text(total.toFixed(2));
        $foot.show();
    }

    /* --- AJAX add to cart --- */
    $(document).on('click', '[data-add-to-cart]', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var id = $btn.data('add-to-cart');
        var origText = $btn.text();
        $btn.addClass('is-loading').text('Adding...');
        $.post(EP.ajax, { action: 'eproduct_add_to_cart', product_id: id }, function (res) {
            $btn.removeClass('is-loading').text('Added ✓');
            if (res.success) {
                $('#epCartCount').text(res.data.count);
            }
            setTimeout(function () { $btn.text(origText); }, 1500);
        }).fail(function () {
            $btn.removeClass('is-loading').text(origText);
        });
    });

    /* --- remove from cart --- */
    $(document).on('click', '[data-remove-cart]', function () {
        var id = $(this).data('remove-cart');
        $.post(EP.ajax, { action: 'eproduct_remove_from_cart', product_id: id }, function (res) {
            if (res.success) epRenderCart(res.data);
        });
    });

    /* --- open / close drawer --- */
    $(document).on('click', '[data-drawer-close]', epCloseDrawer);
    $(document).on('click', '[data-open-cart]', function (e) {
        e.preventDefault();
        $.post(EP.ajax, { action: 'eproduct_get_cart' }, function (res) {
            if (res.success) {
                epRenderCart(res.data);
                epOpenDrawer();
            }
        });
    });
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') epCloseDrawer();
    });

    /* ============================================================
       PRODUCT FILTER (AJAX)
       ============================================================ */
    function epLoad(cat, search) {
        if (cat === undefined) cat = $('.ep-cat.active').data('cat') || '';
        if (search === undefined) search = $('#ep-search').val() || '';
        $.post(EP.ajax, { action: 'eproduct_filter', cat: cat, search: search }, function (html) {
            $('#ep-grid').html(html);
            if (window.epRevealObserve) window.epRevealObserve(document.getElementById('ep-grid'));
            epRestoreWishlistState();
        });
    }

    function epFilterByCategory(slug) {
        $('.ep-cat').removeClass('active');
        $('.ep-cat[data-cat="' + slug + '"]').addClass('active');
        var $grid = $('#ep-store-grid');
        if ($grid.length) {
            $('html, body').animate({ scrollTop: $grid.offset().top - 80 }, 400);
        }
        epLoad(slug, '');
    }

    // Toolbar search
    $('#ep-search').on('input', function () {
        clearTimeout(window._epT);
        window._epT = setTimeout(function () { epLoad(); }, 300);
    });

    // Toolbar category buttons
    $(document).on('click', '.ep-cat', function () {
        $('.ep-cat').removeClass('active');
        $(this).addClass('active');
        epLoad();
    });

    // Header category dropdown links
    $(document).on('click', '.ep-cat-dropdown__menu a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var match = href.match(/\/product-category\/([^/]+)\/?$/);
        if (match) {
            epFilterByCategory(match[1]);
        } else {
            epFilterByCategory('');
        }
        $('.ep-cat-dropdown').removeClass('is-open');
    });

    // Popular category grid cards
    $(document).on('click', '.ep-cat-card', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var match = href.match(/\/product-category\/([^/]+)\/?$/);
        if (match) epFilterByCategory(match[1]);
    });

    // Generic data-cat-slug links (header, footer, etc.)
    $(document).on('click', '[data-cat-slug]', function (e) {
        e.preventDefault();
        var slug = $(this).data('cat-slug');
        if (slug !== undefined && slug !== '') epFilterByCategory(String(slug));
    });

    // Fallback: any link with /product-category/ in href
    $(document).on('click', 'a[href*="/product-category/"]', function (e) {
        if ($(this).data('cat-slug')) return;
        var match = $(this).attr('href').match(/\/product-category\/([^/]+)/);
        if (match && match[1]) { e.preventDefault(); epFilterByCategory(match[1]); }
    });

    // Product card category tags
    $(document).on('click', '.ep-card__cat-tag', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var slug = $(this).data('cat-slug') || $(this).text().trim().toLowerCase().replace(/\s+/g, '-');
        epFilterByCategory(slug);
    });

    /* ============================================================
       CAROUSEL
       ============================================================ */
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

    /* ============================================================
       TABS
       ============================================================ */
    $(document).on('click', '[data-tab]', function () {
        var tab = $(this).data('tab');
        $(this).closest('[data-tabs]').find('[data-tab]').removeClass('active');
        $(this).addClass('active');
        $(this).closest('[data-tabs]').find('[data-tab-panel]').removeClass('active');
        $(this).closest('[data-tabs]').find('[data-tab-panel="' + tab + '"]').addClass('active');
    });

    /* ============================================================
       QUICK VIEW
       ============================================================ */
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
        }).fail(function () {
            $content.html('<p>Could not load product details.</p>');
        });
    });

    $(document).on('click', '[data-qv-close]', function () {
        $('#epQuickView').removeClass('is-open');
        $('body').removeClass('ep-modal-open');
    });

    /* ============================================================
       WISHLIST  (localStorage — persistent across sessions)
       ============================================================ */
    function epGetWishlist() {
        try { return JSON.parse(localStorage.getItem('ep_wishlist')) || []; }
        catch (e) { return []; }
    }
    function epSaveWishlist(arr) {
        localStorage.setItem('ep_wishlist', JSON.stringify(arr));
    }
    function epInWishlist(id) {
        return epGetWishlist().indexOf(id) !== -1;
    }

    function epRestoreWishlistState() {
        var wl = epGetWishlist();
        $('[data-wishlist]').each(function () {
            var id = $(this).data('wishlist');
            if (wl.indexOf(id) !== -1) {
                $(this).addClass('is-active');
                $(this).find('svg').attr('fill', '#ec4899');
            } else {
                $(this).removeClass('is-active');
                $(this).find('svg').attr('fill', 'none');
            }
        });
    }

    $(document).on('click', '[data-wishlist]', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var id = $btn.data('wishlist');
        var wl = epGetWishlist();
        var idx = wl.indexOf(id);
        if (idx === -1) {
            wl.push(id);
            $btn.addClass('is-active');
            $btn.find('svg').attr('fill', '#ec4899');
        } else {
            wl.splice(idx, 1);
            $btn.removeClass('is-active');
            $btn.find('svg').attr('fill', 'none');
        }
        epSaveWishlist(wl);
    });

    // restore wishlist state on page load
    epRestoreWishlistState();

    /* ============================================================
       CART NOTIFICATION (simple toast — kept for non-AJAX fallback)
       ============================================================ */
    $(document).on('click', '[data-notify-close]', function () {
        $('#epCartNotify').removeClass('is-show');
    });

    /* ============================================================
       NEWSLETTER
       ============================================================ */
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

    /* ============================================================
       MOBILE BURGER
       ============================================================ */
    $('#epBurger').on('click', function () {
        $(this).toggleClass('is-active');
        $('#epNav').toggleClass('is-open');
    });

    /* ============================================================
       CATEGORY DROPDOWN
       ============================================================ */
    $('#epCatToggle').on('click', function (e) {
        e.stopPropagation();
        $(this).closest('.ep-cat-dropdown').toggleClass('is-open');
    });
    $(document).on('click', function () {
        $('.ep-cat-dropdown').removeClass('is-open');
    });

    /* ============================================================
       STICKY HEADER
       ============================================================ */
    $(window).on('scroll', function () {
        $('#epHeader').toggleClass('is-scrolled', $(this).scrollTop() > 20);
    });
});
