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
    wp_enqueue_style('eproduct-style', EPRODUCT_URL . 'assets/css/style.css', array(), EPRODUCT_VERSION);
    wp_enqueue_script('eproduct-store', EPRODUCT_URL . 'assets/js/store.js', array('jquery'), EPRODUCT_VERSION, true);
    wp_localize_script('eproduct-store', 'EP', array('ajax' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'eproduct_enqueue_assets');

function eproduct_create_pages() {
    $pages = array(
        'home' => array('eProduct - Digital Products', '[eproduct_home]'),
        'products' => array('Products', '[eproduct_products]'),
        'about' => array('About Us', '[eproduct_about]'),
        'privacy' => array('Privacy Policy', '[eproduct_privacy]'),
        'login' => array('Log In', '[eproduct_login]'),
        'register' => array('Register', '[eproduct_register]'),
        'checkout' => array('Checkout', '[eproduct_checkout]'),
        'account' => array('My Account', '[eproduct_account]'),
    );
    $slug_map = array('home' => '', 'products' => 'products', 'about' => 'about', 'privacy' => 'privacy', 'login' => 'login', 'register' => 'register', 'checkout' => 'checkout', 'account' => 'account');
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
            if ($key === 'home') update_option('page_on_front', $id), update_option('show_on_front', 'page');
        }
    }
}

function eproduct_home_shortcode() {
    ob_start();
    $company = get_option('eproduct_company_name', 'eProduct');
    ?>
    <section class="ep-hero">
        <h1>Welcome to <?php echo esc_html($company); ?></h1>
        <p>Your trusted marketplace for premium digital products. Instant download, secure checkout, global payments.</p>
        <p>
            <a class="ep-btn" href="<?php echo esc_url(home_url('/products/')); ?>">Browse Products</a>
            <a class="ep-btn ep-btn-alt" href="<?php echo esc_url(home_url('/register/')); ?>">Create Account</a>
            <a class="ep-btn ep-btn-alt" href="<?php echo esc_url(home_url('/login/')); ?>">Log In</a>
        </p>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_home', 'eproduct_home_shortcode');

function eproduct_product_item_html($post) {
    $price = get_post_meta($post->ID, '_eproduct_price', true);
    $img = get_the_post_thumbnail_url($post->ID, 'medium');
    ob_start();
    ?>
    <div class="ep-card">
        <a href="<?php echo get_permalink($post->ID); ?>">
            <?php if ($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($post->post_title); ?>"><?php endif; ?>
            <h3><?php echo esc_html($post->post_title); ?></h3>
        </a>
        <p class="ep-price"><?php echo esc_html($price ? '$' . number_format($price, 2) : 'Free'); ?></p>
        <a class="ep-btn" href="<?php echo esc_url(home_url('/product/' . $post->post_name . '/?add_to_cart=' . $post->ID)); ?>">Buy Now</a>
    </div>
    <?php
    return ob_get_clean();
}

function eproduct_products_shortcode() {
    ob_start();
    $cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
    ?>
    <div class="ep-products">
        <div class="ep-filters">
            <input type="text" id="ep-search" placeholder="Search products...">
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

function eproduct_login_shortcode() {
    if (is_user_logged_in()) return '<p>You are logged in. <a href="' . esc_url(home_url('/account/')) . '">My Account</a></p>';
    return wp_login_form(array('echo' => false, 'redirect' => home_url('/account/')));
}
add_shortcode('eproduct_login', 'eproduct_login_shortcode');

function eproduct_register_shortcode() {
    if (is_user_logged_in()) return '';
    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>">
        <p><label>Username</label><br><input type="text" name="user_login" required></p>
        <p><label>Email</label><br><input type="email" name="user_email" required></p>
        <p><input type="submit" value="Register"></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_register', 'eproduct_register_shortcode');

function eproduct_checkout_shortcode() {
    if (!is_user_logged_in()) return '<p>Please <a href="' . esc_url(home_url('/login/')) . '">log in</a> to checkout.</p>';
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
    if (!is_user_logged_in()) return '<p>Please <a href="' . esc_url(home_url('/login/')) . '">log in</a>.</p>';
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

function eproduct_template_include($template) {
    if (is_singular('eproduct')) {
        return EPRODUCT_PATH . 'templates/single-eproduct.php';
    }
    if (is_post_type_archive('eproduct') || is_tax('eproduct_category')) {
        return EPRODUCT_PATH . 'templates/archive-eproduct.php';
    }
    return $template;
}
add_filter('template_include', 'eproduct_template_include');
