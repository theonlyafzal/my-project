<?php
/**
 * The header for Rev Digital Products (eProduct storefront).
 * Matching special.glexmedia.in layout: dark top bar + second nav row.
 *
 * @package Rev Digital Products
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class('eproduct'); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'rev-digital-products'); ?></a>

    <?php
    $ep_company = get_option('eproduct_company_name', 'eProduct');
    $ep_icon = get_site_icon_url(36);
    if (!$ep_icon) $ep_icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
    $store_url = 'https://store.eproduct.org/';
    $cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => true));
    ?>
    <header class="ep-header" id="epHeader">
      <!-- Top bar: dark background, logo + search + account/cart -->
      <div class="ep-header__top">
        <div class="ep-header__top-inner">
          <a class="ep-brand" href="<?php echo esc_url(home_url('/')); ?>">
            <img class="ep-brand__mark" src="<?php echo esc_url($ep_icon); ?>" alt="<?php echo esc_attr($ep_company); ?>" width="40" height="40">
            <span class="ep-brand__name"><?php echo esc_html($ep_company); ?></span>
          </a>

          <div class="ep-header__search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
            <input type="text" placeholder="Search products..." id="ep-header-search">
          </div>

          <div class="ep-header__right">
            <div class="ep-header__help">
              <div class="ep-header__help-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </div>
              <div class="ep-header__help-text">
                <span class="ep-header__help-label">Need Help?</span>
                <a href="mailto:support@eproduct.org">support@eproduct.org</a>
              </div>
            </div>

            <a class="ep-header__icon-link" href="#" data-ep-auth data-tab="login" title="My Account">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>

            <a class="ep-header__icon-link ep-header__cart-link" href="<?php echo esc_url($store_url); ?>" title="Cart">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
              <span class="ep-header__cart-count" id="epCartCount">0</span>
              <span class="ep-header__cart-text">Cart</span>
            </a>
          </div>

          <button class="ep-burger" id="epBurger" aria-label="Toggle menu"><span></span><span></span><span></span></button>
        </div>
      </div>

      <!-- Second row: category dropdown + main nav + quick links -->
      <div class="ep-header__nav-row">
        <div class="ep-header__nav-inner">
          <?php if (!empty($cats) && !is_wp_error($cats)): ?>
          <div class="ep-cat-dropdown" id="epCatDropdown">
            <button class="ep-cat-dropdown__toggle" id="epCatToggle">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span>Select Category</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="ep-cat-dropdown__menu" id="epCatMenu">
              <a href="<?php echo esc_url($store_url); ?>">All Products</a>
              <?php foreach ($cats as $c): ?>
                <a href="<?php echo esc_url(get_term_link($c)); ?>"><?php echo esc_html($c->name); ?></a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <nav class="ep-nav" id="epNav">
            <a class="ep-nav__link" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <a class="ep-nav__link" href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
            <a class="ep-nav__link" href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy</a>
            <a class="ep-nav__link" href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a>
            <a class="ep-nav__link" href="#" data-ep-auth data-tab="login">My Account</a>
          </nav>

          <div class="ep-header__quick-links">
            <a href="<?php echo esc_url($store_url); ?>" class="ep-quick-link">Visit Store</a>
            <a href="<?php echo esc_url(home_url('/checkout/')); ?>" class="ep-quick-link ep-quick-link--accent">Checkout</a>
          </div>
        </div>
      </div>
    </header>

    <!-- Mobile bottom bar -->
    <div class="ep-mobile-bar" id="epMobileBar">
      <a class="ep-mobile-bar__item" href="<?php echo esc_url(home_url('/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Home</span>
      </a>
      <a class="ep-mobile-bar__item" href="<?php echo esc_url(home_url('/products/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span>Shop</span>
      </a>
      <a class="ep-mobile-bar__item" href="<?php echo esc_url(home_url('/about/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span>About</span>
      </a>
      <a class="ep-mobile-bar__item" href="<?php echo esc_url(home_url('/account/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Account</span>
      </a>
    </div>

    <main class="ep-main" id="primary">
