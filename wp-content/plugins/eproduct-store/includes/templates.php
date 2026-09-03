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
        'terms' => array('Terms & Conditions', '[eproduct_terms]'),
        'checkout' => array('Checkout', '[eproduct_checkout]'),
        'account' => array('My Account', '[eproduct_account]'),
    );
    $slug_map = array('home' => '', 'products' => 'products', 'about' => 'about', 'privacy' => 'privacy', 'terms' => 'terms', 'checkout' => 'checkout', 'account' => 'account');
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
    $store = 'https://store.eproduct.org/';
    ob_start();
    ?>
    <div class="eproduct">

        <section class="ep-hero">
            <h1>Premium digital products,<br><span>delivered instantly.</span></h1>
            <p>Your trusted source for templates, eBooks, software, courses and creative assets. Secure global checkout, instant download.</p>
            <div class="ep-hero__actions">
                <a class="ep-btn ep-btn--lg" href="<?php echo esc_url($store); ?>" target="_blank" rel="noopener">Visit Store</a>
                <a class="ep-btn ep-btn--ghost ep-btn--lg" href="#features">Explore Products</a>
            </div>
            <div class="ep-hero__badges">
                <span><?php echo eproduct_icon('bolt'); ?> Instant download</span>
                <span><?php echo eproduct_icon('shield'); ?> Secure payments</span>
                <span><?php echo eproduct_icon('globe'); ?> 150+ countries</span>
            </div>
        </section>

        <section class="ep-stats ep-reveal">
            <div class="ep-stats__inner">
                <div class="ep-stat">
                    <div class="ep-stat__num">50+</div>
                    <div class="ep-stat__label">Premium Products</div>
                </div>
                <div class="ep-stat">
                    <div class="ep-stat__num">2K+</div>
                    <div class="ep-stat__label">Happy Customers</div>
                </div>
                <div class="ep-stat">
                    <div class="ep-stat__num">150+</div>
                    <div class="ep-stat__label">Countries Served</div>
                </div>
                <div class="ep-stat">
                    <div class="ep-stat__num">4.9</div>
                    <div class="ep-stat__label">Average Rating</div>
                </div>
            </div>
        </section>

        <section class="ep-section" id="features">
            <div class="ep-section__head">
                <h2>Browse by category</h2>
                <p>One store, every format you'll ever need. All categories organised, previewed and ready to download.</p>
            </div>
            <div class="ep-grid ep-grid--3">
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('spark'); ?></div>
                    <h3>Premium Courses</h3>
                    <p>Structured, video-based courses that take you from zero to confident, module by module.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('bolt'); ?></div>
                    <h3>Templates</h3>
                    <p>Notion, Canva, Excel and presentation templates — fully editable and ready in minutes.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('shield'); ?></div>
                    <h3>Ebooks &amp; Guides</h3>
                    <p>Well-researched, practical ebooks that go deep on the topics that actually move the needle.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('globe'); ?></div>
                    <h3>AI Tools &amp; Prompts</h3>
                    <p>Tested prompt libraries and AI workflow guides so you get better output, faster.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('bolt'); ?></div>
                    <h3>Software &amp; Plugins</h3>
                    <p>Themes, plugins and tools that extend what your website or workflow can do — ready to install.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('shield'); ?></div>
                    <h3>Graphics &amp; Assets</h3>
                    <p>Icons, illustrations, stock photos and design resources for projects that need a polished finish.</p>
                </div>
            </div>
        </section>

        <section class="ep-section" id="for-you">
            <div class="ep-section__head">
                <h2>Made for creators, learners and builders</h2>
                <p>Whatever stage you're at, there's something in the store that fits how you work and learn.</p>
            </div>
            <div class="ep-grid ep-grid--3">
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('spark'); ?></div>
                    <h3>Students</h3>
                    <p>Learn real, in-demand skills with courses and templates built for beginners — study alongside your degree.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('bolt'); ?></div>
                    <h3>Content Creators</h3>
                    <p>Skip the design grind. Grab ready-to-use templates, reels and hooks that keep your feed consistent.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('shield'); ?></div>
                    <h3>Freelancers</h3>
                    <p>Add polished templates and toolkits to your service offering, without spending hours building from scratch.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('globe'); ?></div>
                    <h3>Homepreneurs</h3>
                    <p>Run a knowledge-based side business from your phone — no inventory, no shipping, no fixed hours.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('spark'); ?></div>
                    <h3>AI Enthusiasts</h3>
                    <p>Get curated prompt packs and AI tool guides that save hours of trial and error every single week.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('bolt'); ?></div>
                    <h3>Getting Started</h3>
                    <p>Tried free tutorials but never finished? Our step-by-step products are your shortcut to actually starting.</p>
                </div>
            </div>
        </section>

        <section class="ep-section" id="how">
            <div class="ep-section__head">
                <h2>How it works</h2>
                <p>From browsing to downloading in minutes. No confusing checkout, no waiting around.</p>
            </div>
            <div class="ep-steps">
                <div class="ep-step ep-reveal">
                    <span class="ep-step__num">01</span>
                    <h3>Browse Products</h3>
                    <p>Explore our catalog by category — courses, templates, ebooks, software or graphics.</p>
                </div>
                <div class="ep-step ep-reveal">
                    <span class="ep-step__num">02</span>
                    <h3>Pick Your Product</h3>
                    <p>Find exactly what you need. Every product has a detailed description and preview.</p>
                </div>
                <div class="ep-step ep-reveal">
                    <span class="ep-step__num">03</span>
                    <h3>Secure Checkout</h3>
                    <p>Pay safely via cards, PayPal or Razorpay. Your order is confirmed instantly.</p>
                </div>
                <div class="ep-step ep-reveal">
                    <span class="ep-step__num">04</span>
                    <h3>Instant Access</h3>
                    <p>Your files unlock immediately — no waiting, no manual approval, no delays.</p>
                </div>
            </div>
        </section>

        <section class="ep-section" id="trust">
            <div class="ep-section__head">
                <h2>Why choose us</h2>
                <p>Every checkout is backed by the same four promises — no fine print, no surprises.</p>
            </div>
            <div class="ep-grid ep-grid--4">
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('shield'); ?></div>
                    <h3>Secure Checkout</h3>
                    <p>Every payment is encrypted and processed through a trusted, verified gateway.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('bolt'); ?></div>
                    <h3>Instant Delivery</h3>
                    <p>No waiting for approval — your files unlock the second payment is confirmed.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('spark'); ?></div>
                    <h3>Real Human Support</h3>
                    <p>Stuck on a download or a doubt before buying? Our team replies quickly.</p>
                </div>
                <div class="ep-card ep-card--flat ep-reveal">
                    <div class="ep-card__icon"><?php echo eproduct_icon('globe'); ?></div>
                    <h3>Lifetime Access</h3>
                    <p>Pay once, own it forever. Re-download your products any time you need them.</p>
                </div>
            </div>
        </section>

        <section class="ep-section">
            <div class="ep-section__head">
                <h2>What people are saying</h2>
                <p>Real feedback from people who picked up products from the store.</p>
            </div>
            <div class="ep-testimonials">
                <div class="ep-testimonial ep-reveal">
                    <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <p>"Downloaded the template pack and my content instantly looked more polished. Delivery was truly instant."</p>
                    <span class="ep-testimonial__author">Priya K. &middot; Delhi</span>
                </div>
                <div class="ep-testimonial ep-reveal">
                    <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <p>"The AI prompt pack alone was worth it. Everything is organised so well, I found what I needed in two minutes."</p>
                    <span class="ep-testimonial__author">Aman S. &middot; Pune</span>
                </div>
                <div class="ep-testimonial ep-reveal">
                    <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <p>"Bought the toolkit bundle for my freelance clients. Saved me days of work and the templates look premium."</p>
                    <span class="ep-testimonial__author">Neha R. &middot; Bangalore</span>
                </div>
                <div class="ep-testimonial ep-reveal">
                    <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <p>"Support replied within minutes when I had a download issue. Sorted immediately."</p>
                    <span class="ep-testimonial__author">Sanjay V. &middot; Hyderabad</span>
                </div>
            </div>
        </section>

        <section class="ep-section" id="faq">
            <div class="ep-section__head">
                <h2>Frequently asked questions</h2>
            </div>
            <div class="ep-faq">
                <div class="ep-faq__item ep-reveal">
                    <h3>How do I receive my product after payment?</h3>
                    <p>The moment your payment is confirmed, your product unlocks instantly on the store — no waiting, no manual approval.</p>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <h3>Is this a one-time payment or a subscription?</h3>
                    <p>Every product is a one-time payment. Once you buy it, it's yours to keep — no recurring charges.</p>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <h3>What payment methods are supported?</h3>
                    <p>You can pay securely using cards, PayPal and Razorpay, all processed through a trusted payment gateway.</p>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <h3>Can I use the templates for my own clients?</h3>
                    <p>Yes — most products come with usage rights that let you use them personally or in your own freelance and client work.</p>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <h3>What if I need help after buying?</h3>
                    <p>Our support team is available to help with downloads, access issues or general questions.</p>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <h3>Will I get updates if a product improves later?</h3>
                    <p>Yes, whenever a product you own gets updated or improved, you get the latest version at no extra cost.</p>
                </div>
            </div>
        </section>

        <section class="ep-cta">
            <h2>Ready to explore the store?</h2>
            <p>Courses, templates, ebooks, software and graphics — all waiting for you on the other side of one click.</p>
            <a class="ep-btn ep-btn--lg" href="<?php echo esc_url($store); ?>" target="_blank" rel="noopener">Go To The Store</a>
            <p class="ep-cta__sub">Secure checkout &middot; Instant digital delivery &middot; One-time payment</p>
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
    $excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words(strip_shortcodes(wp_strip_all_tags($post->post_content)), 16);
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
                <a class="ep-card__link" href="<?php echo get_permalink($post->ID); ?>">
                    View Details
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
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
    ob_start();
    ?>
    <div class="eproduct">
        <section class="ep-about ep-reveal">
            <h1>About <?php echo esc_html($company); ?></h1>
            <p>We are a digital products marketplace dedicated to delivering high-quality downloads — templates, software, assets, and more — with a secure, global checkout experience.</p>
            <p>Our mission is to provide creators, students, freelancers and professionals with the tools they need to learn, build and grow — all delivered instantly after purchase.</p>
        </section>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_about', 'eproduct_about_shortcode');

// Login / Register are modal-only now (see eproduct_ajax_login / eproduct_ajax_register).
// No dedicated [eproduct_login] / [eproduct_register] pages or shortcodes.

function eproduct_checkout_shortcode() {
    if (!is_user_logged_in()) return '<div class="ep-checkout"><h1>Checkout</h1><p>Please <a href="#" data-ep-auth data-tab="login">log in</a> to checkout.</p></div>';
    $cart = eproduct_cart_items();
    if (empty($cart)) return '<div class="ep-checkout"><h1>Checkout</h1><p>Your cart is empty. <a href="' . esc_url(home_url('/products/')) . '">Browse products</a></p></div>';
    $total = 0;
    ob_start();
    echo '<div class="ep-checkout"><h1>Checkout</h1><ul class="ep-cart">';
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
    </form></div>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_checkout', 'eproduct_checkout_shortcode');

function eproduct_account_shortcode() {
    if (!is_user_logged_in()) return '<div class="ep-checkout"><h1>My Account</h1><p>Please <a href="#" data-ep-auth data-tab="login">log in</a>.</p></div>';
    global $wpdb;
    $uid = get_current_user_id();
    $orders = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eproduct_orders WHERE user_id=%d ORDER BY created_at DESC", $uid));
    ob_start();
    echo '<div class="ep-checkout"><h1>My Account</h1><h2>My Orders</h2><table class="ep-orders"><tr><th>Order</th><th>Product</th><th>Amount</th><th>Status</th></tr>';
    foreach ($orders as $o) {
        echo '<tr><td>#' . $o->id . '</td><td>' . esc_html(get_the_title($o->product_id)) . '</td><td>$' . number_format($o->amount, 2) . '</td><td>' . esc_html($o->status) . '</td></tr>';
    }
    echo '</table><p><a href="' . esc_url(wp_logout_url(home_url())) . '">Log out</a></p></div>';
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
