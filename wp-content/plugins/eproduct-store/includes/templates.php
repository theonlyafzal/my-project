<?php
defined('ABSPATH') || exit;

function eproduct_start_session() {
    if (!is_admin() && !session_id()) {
        session_start();
    }
}
add_action('init', 'eproduct_start_session', 1);

function eproduct_handle_add_to_cart() {
    if (isset($_GET['add_to_cart'])) {
        $id = intval($_GET['add_to_cart']);
        if ($id && get_post_type($id) === 'eproduct') {
            if (!isset($_SESSION['eproduct_cart'])) $_SESSION['eproduct_cart'] = array();
            if (!in_array($id, $_SESSION['eproduct_cart'])) $_SESSION['eproduct_cart'][] = $id;
            wp_redirect(home_url('/checkout/'));
            exit;
        }
    }
}
add_action('init', 'eproduct_handle_add_to_cart');

function eproduct_cart_items() {
    return isset($_SESSION['eproduct_cart']) ? array_unique(array_map('intval', $_SESSION['eproduct_cart'])) : array();
}

function eproduct_enqueue_assets() {
    wp_enqueue_style('eproduct-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('eproduct-style', EPRODUCT_URL . 'assets/css/style.css', array(), EPRODUCT_VERSION);
    wp_enqueue_script('eproduct-store', EPRODUCT_URL . 'assets/js/store.js', array('jquery'), EPRODUCT_VERSION, true);
    wp_localize_script('eproduct-store', 'EP', array('ajax' => admin_url('admin-ajax.php')));
}
// Front-end assets are enqueued by the active theme (Rev Digital Products).
// add_action('wp_enqueue_scripts', 'eproduct_enqueue_assets');

function eproduct_icon($name) {
    $icons = array(
        'bolt'   => '<path d="M13 2L4.5 13.5H11l-1 8.5 8.5-11.5H12l1-8.5z"/>',
        'shield' => '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z"/>',
        'spark'  => '<path d="M12 2l2.2 6.3L20 10l-5.8 1.7L12 18l-2.2-6.3L4 10l5.8-1.7L12 2z"/>',
        'globe'  => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
    );
    $p = isset($icons[$name]) ? $icons[$name] : '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $p . '</svg>';
}

function eproduct_feature($icon, $title, $text) {
    return '<div class="ep-feature"><div class="ep-feature__icon">' . eproduct_icon($icon) . '</div><h3>' . esc_html($title) . '</h3><p>' . esc_html($text) . '</p></div>';
}

function eproduct_create_pages() {
    $pages = array(
        'home' => array('eProduct - Digital Products', '[eproduct_home]'),
        'products' => array('Products', '[eproduct_products]'),
        'about' => array('About Us', '[eproduct_about]'),
        'privacy' => array('Privacy Policy', '[eproduct_privacy]'),
        'checkout' => array('Checkout', '[eproduct_checkout]'),
        'account' => array('My Account', '[eproduct_account]'),
    );
    $slug_map = array('home' => '', 'products' => 'products', 'about' => 'about', 'privacy' => 'privacy', 'checkout' => 'checkout', 'account' => 'account');
    foreach ($pages as $key => $p) {
        $slug = $slug_map[$key];
        if (!get_page_by_path($slug ? $slug : 'home')) {
            $id = wp_insert_post(array(
                'post_title' => $p[0],
                'post_content' => $p[1],
                'post_name' => $slug ? $slug : 'home',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            if ($key === 'home') {
            update_option('page_on_front', $id);
            update_option('show_on_front', 'page');
        }
        }
    }
}

function eproduct_home_shortcode() {
    $company = get_option('eproduct_company_name', 'eProduct');
    ob_start();
    ?>
    <div class="eproduct">
        <section class="ep-hero">
            <h1>Premium digital products, delivered instantly.</h1>
            <p>Welcome to <?php echo esc_html($company); ?> — your trusted marketplace for templates, eBooks, software and creative assets. Secure global checkout, instant download.</p>
            <div class="ep-hero__actions">
                <a class="ep-btn" href="<?php echo esc_url(home_url('/products/')); ?>">Browse Products</a>
                <a class="ep-btn ep-btn--ghost" href="<?php echo esc_url(home_url('/register/')); ?>" data-ep-auth data-tab="register">Create Free Account</a>
            </div>
            <div class="ep-hero__badges">
                <span><?php echo eproduct_icon('bolt'); ?> Instant download</span>
                <span><?php echo eproduct_icon('shield'); ?> Secure payments</span>
                <span><?php echo eproduct_icon('globe'); ?> 150+ countries</span>
            </div>
        </section>

        <section class="ep-section">
            <div class="ep-section__head">
                <h2>Why shop with us</h2>
                <p>Everything you need to create, build and grow — without the friction.</p>
            </div>
            <div class="ep-features">
                <?php echo eproduct_feature('bolt', 'Instant Delivery', 'Get your files the moment payment clears — no waiting, no shipping.'); ?>
                <?php echo eproduct_feature('shield', 'Secure Checkout', 'Pay with Stripe, PayPal or Razorpay. Your data stays protected.'); ?>
                <?php echo eproduct_feature('spark', 'Hand-picked Quality', 'Every product is reviewed to meet a high standard of quality.'); ?>
                <?php echo eproduct_feature('globe', 'Global Access', 'Available in 150+ countries with localized pricing.'); ?>
            </div>
<?php echo '<script>document.addEventListener("DOMContentLoaded",function(){var f=document.querySelectorAll(".ep-feature");f.forEach(function(el,i){el.classList.add("ep-reveal");el.style.transitionDelay=(i*80)+"ms";});if(window.epRevealObserve)window.epRevealObserve(document);});</script>'; ?>
        </section>

        <section class="ep-section">
            <div class="ep-section__head">
                <h2>Featured products</h2>
                <p>Start with some of our most popular digital downloads.</p>
            </div>
            <div class="ep-grid">
                <?php
                $feat = new WP_Query(array('post_type' => 'eproduct', 'posts_per_page' => 4, 'post_status' => 'publish', 'meta_query' => array(array('key' => '_thumbnail_id', 'compare' => 'EXISTS'))));
                if (!$feat->have_posts()) {
                    $feat = new WP_Query(array('post_type' => 'eproduct', 'posts_per_page' => 4, 'post_status' => 'publish'));
                }
                while ($feat->have_posts()) { $feat->the_post(); echo eproduct_product_item_html($feat->post); }
                wp_reset_postdata();
                ?>
            </div>
            <div style="text-align:center;margin-top:34px">
                <a class="ep-btn ep-btn--dark" href="<?php echo esc_url(home_url('/products/')); ?>">View all products</a>
            </div>
        </section>

        <section class="ep-cta">
            <h2>Join thousands of creators</h2>
            <p>Create a free account and start downloading in minutes.</p>
            <a class="ep-btn" href="<?php echo esc_url(home_url('/register/')); ?>" data-ep-auth data-tab="register">Get started free</a>
        </section>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_home', 'eproduct_home_shortcode');

function eproduct_product_item_html($post) {
    $price = get_post_meta($post->ID, '_eproduct_price', true);
    $img = get_the_post_thumbnail_url($post->ID, 'medium');
    $cats = wp_get_post_terms($post->ID, 'eproduct_category', array('fields' => 'names'));
    $excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words(strip_shortcodes(wp_strip_all_tags($post->post_content)), 14);
    $initial = preg_match('/./u', $post->post_title, $m) ? $m[0] : '★';
    ob_start();
    ?>
    <div class="ep-card ep-reveal">
        <a class="ep-card__media" href="<?php echo get_permalink($post->ID); ?>">
            <?php if ($img): ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($post->post_title); ?>" loading="lazy">
            <?php else: ?>
                <span class="ep-card__placeholder"><?php echo esc_html($initial); ?></span>
            <?php endif; ?>
            <?php if (!empty($cats)): ?><span class="ep-card__tag"><?php echo esc_html($cats[0]); ?></span><?php endif; ?>
        </a>
        <div class="ep-card__body">
            <h3 class="ep-card__title"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
            <p class="ep-card__excerpt"><?php echo esc_html($excerpt); ?></p>
            <div class="ep-card__foot">
                <span class="ep-price<?php echo $price ? '' : ' ep-price--free'; ?>"><?php echo $price ? '$' . number_format($price, 2) : 'Free'; ?></span>
                <a class="ep-btn ep-btn--sm" href="<?php echo esc_url(home_url('/product/' . $post->post_name . '/?add_to_cart=' . $post->ID)); ?>">Buy Now</a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function eproduct_products_shortcode() {
    ob_start();
    $cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
    ?>
    <div class="eproduct">
        <section class="ep-section" style="margin-top:30px">
            <div class="ep-section__head">
                <h2>Our Products</h2>
                <p>Browse our curated collection of premium digital products.</p>
            </div>
            <div class="ep-toolbar">
                <div class="ep-search">
                    <?php echo eproduct_icon('search'); ?>
                    <input type="text" id="ep-search" placeholder="Search products...">
                </div>
                <div class="ep-cats">
                    <button class="ep-cat active" data-cat="">All</button>
                    <?php foreach ($cats as $c): ?>
                        <button class="ep-cat" data-cat="<?php echo esc_attr($c->slug); ?>"><?php echo esc_html($c->name); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="ep-grid" class="ep-grid">
                <?php
                $q = new WP_Query(array('post_type' => 'eproduct', 'posts_per_page' => -1, 'post_status' => 'publish'));
                while ($q->have_posts()) { $q->the_post(); echo eproduct_product_item_html($q->post); }
                wp_reset_postdata();
                ?>
            </div>
        </section>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_products', 'eproduct_products_shortcode');

function eproduct_about_shortcode() {
    $company = get_option('eproduct_company_name', 'eProduct');
    return '<section class="ep-about"><h1>About ' . esc_html($company) . '</h1><p>We are a digital products marketplace dedicated to delivering high-quality downloads — templates, software, assets, and more — with a secure, global checkout experience.</p></section>';
}
add_shortcode('eproduct_about', 'eproduct_about_shortcode');

// Login / Register are modal-only now (see eproduct_ajax_login / eproduct_ajax_register).
// No dedicated [eproduct_login] / [eproduct_register] pages or shortcodes.

function eproduct_checkout_shortcode() {
    if (!is_user_logged_in()) return '<p>Please <a href="#" data-ep-auth data-tab="login">log in</a> to checkout.</p>';
    $cart = eproduct_cart_items();
    if (empty($cart)) return '<p>Your cart is empty. <a href="' . esc_url(home_url('/products/')) . '">Browse products</a></p>';
    $total = 0;
    ob_start();
    echo '<h1>Checkout</h1><ul class="ep-cart">';
    foreach ($cart as $id) {
        $price = floatval(get_post_meta($id, '_eproduct_price', true));
        $total += $price;
        echo '<li>' . esc_html(get_the_title($id)) . ' — $' . number_format($price, 2) . '</li>';
    }
    echo '</ul><p class="ep-total">Total: $' . number_format($total, 2) . '</p>';
    ?>
    <form method="post" action="<?php echo esc_url(EPRODUCT_URL . 'includes/payments/payment-server.php?action=create'); ?>">
        <input type="hidden" name="products" value="<?php echo esc_attr(implode(',', $cart)); ?>">
        <input type="hidden" name="amount" value="<?php echo esc_attr($total); ?>">
        <p><label>Payment Method</label><br>
            <select name="gateway">
                <option value="stripe">Stripe</option>
                <option value="paypal">PayPal</option>
                <option value="razorpay">Razorpay</option>
            </select>
        </p>
        <p><button class="ep-btn" type="submit">Pay $<?php echo number_format($total, 2); ?></button></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_checkout', 'eproduct_checkout_shortcode');

function eproduct_account_shortcode() {
    if (!is_user_logged_in()) return '<p>Please <a href="#" data-ep-auth data-tab="login">log in</a>.</p>';
    global $wpdb;
    $uid = get_current_user_id();
    $orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eproduct_orders WHERE user_id=%d ORDER BY created_at DESC", $uid));
    ob_start();
    echo '<h1>My Account</h1><h2>My Orders</h2><table class="ep-orders"><tr><th>Order</th><th>Product</th><th>Amount</th><th>Status</th></tr>';
    foreach ($orders as $o) {
        echo '<tr><td>#' . $o->id . '</td><td>' . esc_html(get_the_title($o->product_id)) . '</td><td>$' . number_format($o->amount, 2) . '</td><td>' . esc_html($o->status) . '</td></tr>';
    }
    echo '</table><p><a href="' . esc_url(wp_logout_url(home_url())) . '">Log out</a></p>';
    return ob_get_clean();
}
add_shortcode('eproduct_account', 'eproduct_account_shortcode');

/* ---------- AJAX auth (modal login / register) ---------- */
add_action('wp_ajax_nopriv_eproduct_login', 'eproduct_ajax_login');
add_action('wp_ajax_eproduct_login', 'eproduct_ajax_login');
add_action('wp_ajax_nopriv_eproduct_register', 'eproduct_ajax_register');
add_action('wp_ajax_eproduct_register', 'eproduct_ajax_register');

function eproduct_ajax_login() {
    if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'eproduct_auth')) {
        wp_send_json_error('Security check failed.');
    }
    $user = wp_signon(array(
        'user_login'    => sanitize_text_field($_POST['log']),
        'user_password' => $_POST['pwd'],
        'remember'      => !empty($_POST['remember']),
    ), false);
    if (is_wp_error($user)) {
        wp_send_json_error($user->get_error_message());
    }
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);
    wp_send_json_success(array('redirect' => home_url('/account/')));
}

function eproduct_ajax_register() {
    if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'eproduct_auth')) {
        wp_send_json_error('Security check failed.');
    }
    $user_login = sanitize_user($_POST['user_login']);
    $user_email = sanitize_email($_POST['user_email']);
    if (empty($user_login) || empty($user_email)) {
        wp_send_json_error('Please fill in all fields.');
    }
    $result = register_new_user($user_login, $user_email);
    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
    }
    wp_send_json_success(array('message' => 'Registration successful! Check your email for your password.'));
}

function eproduct_template_include($template) {
    return $template;
}
add_filter('template_include', 'eproduct_template_include');
