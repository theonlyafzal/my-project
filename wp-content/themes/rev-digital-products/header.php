<?php
/**
 * The header for Rev Digital Products (eProduct storefront).
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
    ?>
    <header class="ep-header" id="epHeader">
      <div class="ep-header__inner">
        <a class="ep-brand" href="<?php echo esc_url(home_url('/')); ?>">
          <img class="ep-brand__mark" src="<?php echo esc_url($ep_icon); ?>" alt="<?php echo esc_attr($ep_company); ?>" width="36" height="36">
          <span class="ep-brand__name"><?php echo esc_html($ep_company); ?></span>
        </a>

        <div class="ep-header__search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
          <input type="text" placeholder="Search products..." id="ep-header-search">
        </div>

        <nav class="ep-nav" id="epNav">
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
          <a class="ep-nav__link" href="https://store.eproduct.org/" target="_blank" rel="noopener">Shop</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a>
        </nav>
        <button class="ep-burger" id="epBurger" aria-label="Toggle menu"><span></span><span></span><span></span></button>
      </div>
    </header>

    <!-- Mobile bottom bar -->
    <div class="ep-mobile-bar" id="epMobileBar">
      <a class="ep-mobile-bar__item" href="<?php echo esc_url(home_url('/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Home</span>
      </a>
      <a class="ep-mobile-bar__item" href="https://store.eproduct.org/" target="_blank" rel="noopener">
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
