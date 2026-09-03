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

        <section class="ep-section" id="what">
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
            <style>
            .ep-testimonials-scroll{overflow:hidden;width:100%;position:relative;padding:20px 0}
            .ep-testimonials-scroll::before,.ep-testimonials-scroll::after{content:'';position:absolute;top:0;bottom:0;width:80px;z-index:2;pointer-events:none}
            .ep-testimonials-scroll::before{left:0;background:linear-gradient(to right,#fff,transparent)}
            .ep-testimonials-scroll::after{right:0;background:linear-gradient(to left,#fff,transparent)}
            .ep-testimonials-track{display:flex;gap:20px;width:max-content;animation:epScrollTestimonials var(--scroll-duration,30s) linear infinite}
            .ep-testimonials-scroll:hover .ep-testimonials-track{animation-play-state:paused}
            .ep-testimonial{flex:0 0 320px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
            .ep-testimonial__stars{color:#f59e0b;font-size:1rem;margin-bottom:12px;letter-spacing:2px}
            .ep-testimonial p{color:#374151;font-size:.95rem;line-height:1.6;margin:0 0 16px;font-style:italic}
            .ep-testimonial__author{display:flex;align-items:center;gap:10px}
            .ep-testimonial__avatar{width:36px;height:36px;border-radius:50%;background:#374bff;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:600}
            .ep-testimonial__info{display:flex;flex-direction:column}
            .ep-testimonial__name{color:#111827;font-size:.9rem;font-weight:600}
            .ep-testimonial__location{color:#6b7280;font-size:.8rem}
            @keyframes epScrollTestimonials{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
            </style>
            <div class="ep-section__head">
                <h2>Loved By Customers</h2>
                <p>What people are saying</p>
            </div>
            <div class="ep-testimonials-scroll" data-speed="30">
                <div class="ep-testimonials-track">
                    <div class="ep-testimonial">
                        <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"Downloaded the template pack and my content instantly looked more polished. Delivery was truly instant."</p>
                        <div class="ep-testimonial__author">
                            <div class="ep-testimonial__avatar">PK</div>
                            <div class="ep-testimonial__info">
                                <span class="ep-testimonial__name">Priya K.</span>
                                <span class="ep-testimonial__location">Delhi</span>
                            </div>
                        </div>
                    </div>
                    <div class="ep-testimonial">
                        <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"The AI prompt pack alone was worth it. Everything is organised so well, I found what I needed in two minutes."</p>
                        <div class="ep-testimonial__author">
                            <div class="ep-testimonial__avatar">AS</div>
                            <div class="ep-testimonial__info">
                                <span class="ep-testimonial__name">Aman S.</span>
                                <span class="ep-testimonial__location">Pune</span>
                            </div>
                        </div>
                    </div>
                    <div class="ep-testimonial">
                        <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"Bought the toolkit bundle for my freelance clients. Saved me days of work and the templates look premium."</p>
                        <div class="ep-testimonial__author">
                            <div class="ep-testimonial__avatar">NR</div>
                            <div class="ep-testimonial__info">
                                <span class="ep-testimonial__name">Neha R.</span>
                                <span class="ep-testimonial__location">Bangalore</span>
                            </div>
                        </div>
                    </div>
                    <div class="ep-testimonial">
                        <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"Support replied within minutes when I had a download issue. Sorted immediately."</p>
                        <div class="ep-testimonial__author">
                            <div class="ep-testimonial__avatar">SV</div>
                            <div class="ep-testimonial__info">
                                <span class="ep-testimonial__name">Sanjay V.</span>
                                <span class="ep-testimonial__location">Hyderabad</span>
                            </div>
                        </div>
                    </div>
                    <div class="ep-testimonial">
                        <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"I was skeptical about digital products, but the ebook I bought was genuinely well-written and practical."</p>
                        <div class="ep-testimonial__author">
                            <div class="ep-testimonial__avatar">RM</div>
                            <div class="ep-testimonial__info">
                                <span class="ep-testimonial__name">Riya M.</span>
                                <span class="ep-testimonial__location">Mumbai</span>
                            </div>
                        </div>
                    </div>
                    <div class="ep-testimonial">
                        <div class="ep-testimonial__stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p>"The Notion templates saved me an entire weekend of setup. Plugged straight into my workflow."</p>
                        <div class="ep-testimonial__author">
                            <div class="ep-testimonial__avatar">KT</div>
                            <div class="ep-testimonial__info">
                                <span class="ep-testimonial__name">Kabir T.</span>
                                <span class="ep-testimonial__location">Chennai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ep-section" id="faq">
            <div class="ep-section__head">
                <h2>Frequently asked questions</h2>
            </div>
            <div class="ep-faq">
                <div class="ep-faq__item ep-reveal">
                    <button class="ep-faq__toggle" aria-expanded="false">
                        <span>How do I receive my product after payment?</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="ep-faq__answer"><p>The moment your payment is confirmed, your product unlocks instantly on the store — no waiting, no manual approval.</p></div>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <button class="ep-faq__toggle" aria-expanded="false">
                        <span>Is this a one-time payment or a subscription?</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="ep-faq__answer"><p>Every product is a one-time payment. Once you buy it, it's yours to keep — no recurring charges.</p></div>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <button class="ep-faq__toggle" aria-expanded="false">
                        <span>What payment methods are supported?</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="ep-faq__answer"><p>You can pay securely using cards, PayPal and Razorpay, all processed through a trusted payment gateway.</p></div>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <button class="ep-faq__toggle" aria-expanded="false">
                        <span>Can I use the templates for my own clients?</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="ep-faq__answer"><p>Yes — most products come with usage rights that let you use them personally or in your own freelance and client work.</p></div>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <button class="ep-faq__toggle" aria-expanded="false">
                        <span>What if I need help after buying?</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="ep-faq__answer"><p>Our support team is available to help with downloads, access issues or general questions.</p></div>
                </div>
                <div class="ep-faq__item ep-reveal">
                    <button class="ep-faq__toggle" aria-expanded="false">
                        <span>Will I get updates if a product improves later?</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="ep-faq__answer"><p>Yes, whenever a product you own gets updated or improved, you get the latest version at no extra cost.</p></div>
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
    $price = floatval(get_post_meta($post->ID, '_eproduct_price', true));
    $sale_price = get_post_meta($post->ID, '_eproduct_sale_price', true);
    $sale_price = ($sale_price !== '' && floatval($sale_price) > 0) ? floatval($sale_price) : '';
    $on_sale = ($sale_price !== '' && $sale_price < $price);
    $img = get_the_post_thumbnail_url($post->ID, 'medium');
    $cats = wp_get_post_terms($post->ID, 'eproduct_category', array('fields' => 'names'));
    $cat_slugs = wp_get_post_terms($post->ID, 'eproduct_category', array('fields' => 'slugs'));
    $excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words(strip_shortcodes(wp_strip_all_tags($post->post_content)), 16);
    $initial = preg_match('/./u', $post->post_title, $m) ? $m[0] : '★';
    $pct = $on_sale ? round((1 - $sale_price / $price) * 100) : 0;
    $permalink = get_permalink($post->ID);
    $cart_url = add_query_arg('add_to_cart', $post->ID, $permalink);
    $full_img = get_the_post_thumbnail_url($post->ID, 'large');
    ob_start();
    ?>
    <div class="ep-card ep-reveal" data-product-id="<?php echo $post->ID; ?>">
        <div class="ep-card__media">
            <a href="<?php echo esc_url($permalink); ?>">
                <?php if ($img): ?>
                    <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($post->post_title); ?>" loading="lazy">
                <?php else: ?>
                    <span class="ep-card__placeholder"><?php echo esc_html($initial); ?></span>
                <?php endif; ?>
            </a>
            <?php if ($on_sale): ?><span class="ep-card__badge"><?php echo $pct; ?>% OFF</span><?php endif; ?>
            <?php if (!empty($cats)): ?><span class="ep-card__tag"><?php echo esc_html($cats[0]); ?></span><?php endif; ?>
            <div class="ep-card__actions">
                <button class="ep-card__action ep-card__action--view" data-quick-view="<?php echo $post->ID; ?>" title="Quick View">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button class="ep-card__action ep-card__action--wish" data-wishlist="<?php echo $post->ID; ?>" title="Add to Wishlist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </button>
                <button class="ep-card__action ep-card__action--cart" data-add-to-cart="<?php echo $post->ID; ?>" title="Add to Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </button>
            </div>
        </div>
        <div class="ep-card__body">
            <h3 class="ep-card__title"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
            <div class="ep-card__cat-row">
                <?php if (!empty($cats)): foreach (array_slice($cats, 0, 2) as $i => $c): ?>
                    <span class="ep-card__cat-tag" data-cat-slug="<?php echo esc_attr(isset($cat_slugs[$i]) ? $cat_slugs[$i] : sanitize_title($c)); ?>"><?php echo esc_html($c); ?></span>
                <?php endforeach; endif; ?>
            </div>
            <p class="ep-card__excerpt"><?php echo esc_html($excerpt); ?></p>
            <div class="ep-card__foot">
                <div class="ep-card__prices">
                    <?php if ($on_sale): ?>
                        <span class="ep-price ep-price--sale">$<?php echo number_format($sale_price, 2); ?></span>
                        <span class="ep-price ep-price--orig">$<?php echo number_format($price, 2); ?></span>
                    <?php else: ?>
                        <span class="ep-price"><?php echo $price > 0 ? '$' . number_format($price, 2) : 'Free'; ?></span>
                    <?php endif; ?>
                </div>
                <button class="ep-card__cart" data-add-to-cart="<?php echo $post->ID; ?>">Add to Cart</button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function eproduct_products_shortcode() {
    ob_start();
    $cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
    $company = get_option('eproduct_company_name', 'eProduct');
    ?>
    <div class="eproduct">

        <section class="ep-hero ep-hero--store">
            <h1>Premium Digital Products</h1>
            <p>Discover templates, eBooks, courses, software and creative assets — instant download after purchase.</p>
            <div class="ep-hero__actions">
                <a class="ep-btn ep-btn--lg" href="#ep-store-grid">Browse Products</a>
                <a class="ep-btn ep-btn--ghost ep-btn--lg" href="#" data-ep-auth data-tab="register">Sign Up</a>
            </div>
            <div class="ep-hero__badges">
                <span><?php echo eproduct_icon('bolt'); ?> Instant download</span>
                <span><?php echo eproduct_icon('shield'); ?> Secure checkout</span>
                <span><?php echo eproduct_icon('globe'); ?> 150+ countries</span>
            </div>
        </section>

        <?php
        $you_may_like = new WP_Query(array(
            'post_type' => 'eproduct', 'posts_per_page' => 8, 'post_status' => 'publish', 'orderby' => 'rand',
        ));
        if ($you_may_like->have_posts()):
        ?>
        <section class="ep-section ep-section--carousel" id="ep-you-like">
            <div class="ep-section__head">
                <h2>You May Like</h2>
                <p>Handpicked products we think you'll love.</p>
            </div>
            <div class="ep-carousel" data-carousel>
                <button class="ep-carousel__btn ep-carousel__btn--prev" data-carousel-prev>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <div class="ep-carousel__track" data-carousel-track>
                    <?php while ($you_may_like->have_posts()) { $you_may_like->the_post(); ?>
                        <div class="ep-carousel__slide"><?php echo eproduct_product_item_html($you_may_like->post); ?></div>
                    <?php } wp_reset_postdata(); ?>
                </div>
                <button class="ep-carousel__btn ep-carousel__btn--next" data-carousel-next>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($cats) && !is_wp_error($cats)): ?>
        <section class="ep-section" id="ep-categories">
            <div class="ep-section__head">
                <h2>Popular Categories</h2>
                <p>Browse by category to find exactly what you need.</p>
            </div>
            <div class="ep-cats-grid">
                <?php foreach ($cats as $c):
                    $count = $c->count;
                    $cat_url = get_term_link($c);
                    $icons = array('Templates' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
                        'Course' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
                        'Ebooks' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
                        'Software' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                        'AI Tools' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><path d="M12 2l2.2 6.3L20 10l-5.8 1.7L12 18l-2.2-6.3L4 10l5.8-1.7L12 2z"/></svg>',
                        'Reels Bundle' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>',
                        'Toolkit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
                    );
                    $icon = isset($icons[$c->name]) ? $icons[$c->name] : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="28" height="28"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>';
                ?>
                <a class="ep-cat-card" href="<?php echo esc_url($cat_url); ?>">
                    <div class="ep-cat-card__icon"><?php echo $icon; ?></div>
                    <h3 class="ep-cat-card__name"><?php echo esc_html($c->name); ?></h3>
                    <span class="ep-cat-card__count"><?php echo $count; ?> <?php echo $count === 1 ? 'product' : 'products'; ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="ep-section" id="ep-store-grid">
            <div class="ep-section__head">
                <h2>All Products</h2>
                <p>Browse our full collection of premium digital products.</p>
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

        <section class="ep-section ep-newsletter-section">
            <div class="ep-newsletter">
                <h2>Subscribe &amp; Get 10% Off</h2>
                <p>Get email updates about our latest products and special offers.</p>
                <form class="ep-newsletter__form" data-newsletter>
                    <input type="email" placeholder="Enter your email address" required>
                    <button type="submit" class="ep-btn">Sign Up</button>
                </form>
                <p class="ep-newsletter__note">No spam, ever. Unsubscribe anytime.</p>
            </div>
        </section>

        <section class="ep-cta">
            <h2>Can't find what you need?</h2>
            <p>We're adding new products every week. Check back soon or contact us for custom requests.</p>
            <div><a class="ep-btn ep-btn--lg" href="<?php echo esc_url(home_url('/privacy/')); ?>">Contact Us</a></div>
            <p class="ep-cta__sub">Secure checkout &middot; Instant digital delivery &middot; One-time payment</p>
        </section>

    </div>

    <!-- Quick View Modal -->
    <div class="ep-qv-modal" id="epQuickView">
        <div class="ep-qv-modal__overlay" data-qv-close></div>
        <div class="ep-qv-modal__dialog">
            <button class="ep-qv-modal__close" data-qv-close>&times;</button>
            <div class="ep-qv-modal__content" id="epQvContent"></div>
        </div>
    </div>

    <!-- Cart Notification -->
    <div class="ep-cart-notify" id="epCartNotify">
        <div class="ep-cart-notify__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="ep-cart-notify__text">
            <strong>Product added to cart!</strong>
        </div>
        <button class="ep-cart-notify__close" data-notify-close>&times;</button>
    </div>

    <!-- Cart Drawer -->
    <div class="ep-drawer" id="epCartDrawer" aria-hidden="true">
        <div class="ep-drawer__overlay" data-drawer-close></div>
        <div class="ep-drawer__panel">
            <div class="ep-drawer__head">
                <h3>Your Cart (<span id="epCartCount">0</span>)</h3>
                <button class="ep-drawer__close" data-drawer-close>&times;</button>
            </div>
            <div class="ep-drawer__body" id="epCartBody">
                <div class="ep-drawer__empty">Your cart is empty.</div>
            </div>
            <div class="ep-drawer__foot" id="epCartFoot" style="display:none;">
                <div class="ep-drawer__total">Total: <strong>$<span id="epCartTotal">0.00</span></strong></div>
                <a class="ep-btn ep-btn--lg ep-btn--full" href="<?php echo esc_url(home_url('/checkout/')); ?>">Proceed to Checkout</a>
                <button class="ep-btn ep-btn--outline ep-btn--full" data-drawer-close style="margin-top:8px;">Continue Shopping</button>
            </div>
        </div>
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

/* ---------- AJAX quick view ---------- */
add_action('wp_ajax_eproduct_quick_view', 'eproduct_ajax_quick_view');
add_action('wp_ajax_nopriv_eproduct_quick_view', 'eproduct_ajax_quick_view');

function eproduct_ajax_quick_view() {
    $id = intval($_POST['product_id']);
    if (!$id || get_post_type($id) !== 'eproduct') {
        wp_send_json_error('Invalid product.');
    }
    $price = floatval(get_post_meta($id, '_eproduct_price', true));
    $sale_price = get_post_meta($id, '_eproduct_sale_price', true);
    $sale_price = ($sale_price !== '' && floatval($sale_price) > 0) ? floatval($sale_price) : '';
    $on_sale = ($sale_price !== '' && $sale_price < $price);
    $pct = $on_sale ? round((1 - $sale_price / $price) * 100) : 0;
    $img = get_the_post_thumbnail_url($id, 'large');
    $cats = wp_get_post_terms($id, 'eproduct_category', array('fields' => 'names'));
    $excerpt = get_post_field('post_excerpt', $id) ?: wp_trim_words(strip_shortcodes(wp_strip_all_tags(get_post_field('post_content', $id))), 20);
    $permalink = get_permalink($id);
    $cart_url = add_query_arg('add_to_cart', $id, $permalink);
    ?>
    <div class="ep-qv">
        <div class="ep-qv__media">
            <?php if ($img): ?>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title($id)); ?>">
            <?php endif; ?>
            <?php if ($on_sale): ?><span class="ep-qv__badge"><?php echo $pct; ?>% OFF</span><?php endif; ?>
        </div>
        <div class="ep-qv__info">
            <?php if (!empty($cats)): ?>
                <div class="ep-qv__cats">
                    <?php foreach ($cats as $c) echo '<span class="ep-card__cat-tag">' . esc_html($c) . '</span>'; ?>
                </div>
            <?php endif; ?>
            <h2 class="ep-qv__title"><?php echo esc_html(get_the_title($id)); ?></h2>
            <div class="ep-qv__pricing">
                <?php if ($on_sale): ?>
                    <span class="ep-price ep-price--sale">$<?php echo number_format($sale_price, 2); ?></span>
                    <span class="ep-price ep-price--orig">$<?php echo number_format($price, 2); ?></span>
                <?php else: ?>
                    <span class="ep-price"><?php echo $price > 0 ? '$' . number_format($price, 2) : 'Free'; ?></span>
                <?php endif; ?>
            </div>
            <p class="ep-qv__excerpt"><?php echo esc_html($excerpt); ?></p>
            <div class="ep-qv__actions">
                <button class="ep-btn ep-btn--lg" data-add-to-cart="<?php echo $id; ?>">Add to Cart</button>
                <a class="ep-btn ep-btn--outline ep-btn--lg" href="<?php echo esc_url($permalink); ?>">View Full Details</a>
            </div>
            <div class="ep-qv__features">
                <div class="ep-single__feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Instant download after purchase
                </div>
                <div class="ep-single__feature">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Secure payment processing
                </div>
            </div>
        </div>
    </div>
    <?php
    wp_send_json_success(ob_get_clean());
}

function eproduct_template_include($template) {
    return $template;
}
add_filter('template_include', 'eproduct_template_include');
