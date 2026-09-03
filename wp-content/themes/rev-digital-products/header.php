<?php
/**
 * The header for Rev Digital Products (eproduct.org).
 * Matching glexmedia.in layout: clean single-row header.
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
    <style>
    .ep-header{position:sticky;top:0;z-index:1000;background:#fff;border-bottom:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.04)}
    .ep-header__inner{max-width:1200px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between;gap:24px}
    .ep-brand{display:flex;align-items:center;text-decoration:none;flex-shrink:0}
    .ep-brand__name{font-size:1.2rem;font-weight:700;color:#111827}
    .ep-nav{display:flex;align-items:center;gap:4px}
    .ep-nav__link{padding:8px 14px;border-radius:6px;font-size:.9rem;font-weight:500;color:#374151;text-decoration:none;transition:background .15s,color .15s}
    .ep-nav__link:hover{background:#f3f4f6;color:#111827}
    .ep-header__actions{display:flex;align-items:center;gap:12px;flex-shrink:0}
    .ep-btn--sm{padding:8px 18px;font-size:.85rem;border-radius:6px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;transition:background .15s,transform .1s}
    .ep-btn--primary{background:#374bff;color:#fff}
    .ep-btn--primary:hover{background:#2b3ae0}
    .ep-btn--full{width:100%}
    .ep-burger{display:none;background:none;border:none;cursor:pointer;padding:8px}
    .ep-burger span{display:block;width:22px;height:2px;background:#374151;margin:5px 0;border-radius:2px;transition:transform .2s,opacity .2s}
    .ep-burger.active span:nth-child(1){transform:translateY(7px) rotate(45deg)}
    .ep-burger.active span:nth-child(2){opacity:0}
    .ep-burger.active span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
    .ep-mobile-menu{display:none;position:fixed;top:64px;left:0;right:0;bottom:0;background:#fff;z-index:999;padding:24px;overflow-y:auto}
    .ep-mobile-menu.open{display:block}
    .ep-mobile-menu__nav{display:flex;flex-direction:column;gap:4px}
    .ep-mobile-menu__nav a{display:block;padding:14px 16px;font-size:1rem;font-weight:500;color:#374151;text-decoration:none;border-radius:8px;transition:background .15s}
    .ep-mobile-menu__nav a:hover{background:#f3f4f6}
    .ep-mobile-menu__nav .ep-btn{margin-top:12px;text-align:center}
    @media(max-width:768px){.ep-nav{display:none}.ep-burger{display:block}.ep-header__inner{height:56px}}
    </style>
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
    ?>
    <header class="ep-header" id="epHeader">
      <div class="ep-header__inner">
        <a class="ep-brand" href="<?php echo esc_url(home_url('/')); ?>">
          <span class="ep-brand__name">eProduct</span>
        </a>

        <nav class="ep-nav" id="epNav">
          <a class="ep-nav__link" href="#who">Who It's For</a>
          <a class="ep-nav__link" href="#what">What You Get</a>
          <a class="ep-nav__link" href="#how">How It Works</a>
          <a class="ep-nav__link" href="#faq">FAQ</a>
        </nav>

        <div class="ep-header__actions">
          <a href="<?php echo esc_url($store_url); ?>" class="ep-btn ep-btn--sm ep-btn--primary">Visit Store</a>
          <button class="ep-burger" id="epBurger" aria-label="Toggle menu"><span></span><span></span><span></span></button>
        </div>
      </div>
    </header>

    <!-- Mobile menu -->
    <div class="ep-mobile-menu" id="epMobileMenu">
      <nav class="ep-mobile-menu__nav">
        <a href="#who">Who It's For</a>
        <a href="#what">What You Get</a>
        <a href="#how">How It Works</a>
        <a href="#faq">FAQ</a>
        <a href="<?php echo esc_url($store_url); ?>" class="ep-btn ep-btn--primary ep-btn--full">Visit Store</a>
      </nav>
    </div>

    <main class="ep-main" id="primary">
