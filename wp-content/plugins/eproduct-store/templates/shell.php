<?php
defined('ABSPATH') || exit;

function eproduct_site_header() {
    $company = get_option('eproduct_company_name', 'eProduct');
    $icon = get_site_icon_url(34);
    if (!$icon) $icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
    ?>
    <header class="ep-header" id="epHeader">
      <div class="ep-header__inner">
        <a class="ep-brand" href="<?php echo esc_url(home_url('/')); ?>">
          <img class="ep-brand__mark" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($company); ?>" width="34" height="34">
          <span class="ep-brand__name"><?php echo esc_html($company); ?></span>
        </a>
        <nav class="ep-nav" id="epNav">
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/products/')); ?>">Products</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
          <?php if (is_user_logged_in()): ?>
            <a class="ep-nav__link" href="<?php echo esc_url(home_url('/account/')); ?>">My Account</a>
          <?php else: ?>
            <a class="ep-nav__link" href="<?php echo esc_url(home_url('/login/')); ?>">Log In</a>
            <a class="ep-btn ep-btn--sm" href="<?php echo esc_url(home_url('/register/')); ?>">Get Started</a>
          <?php endif; ?>
        </nav>
        <button class="ep-burger" id="epBurger" aria-label="Toggle menu"><span></span><span></span><span></span></button>
      </div>
    </header>
    <?php
}

function eproduct_site_footer() {
    $company = get_option('eproduct_company_name', 'eProduct');
    $icon = get_site_icon_url(34);
    if (!$icon) $icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
    $year = date('Y');
    ?>
    <footer class="ep-footer">
      <div class="ep-footer__inner">
        <div class="ep-footer__brand">
          <img class="ep-brand__mark" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($company); ?>" width="34" height="34">
          <span class="ep-brand__name"><?php echo esc_html($company); ?></span>
          <p>Premium digital products, delivered instantly to your inbox.</p>
        </div>
        <div class="ep-footer__cols">
          <div>
            <h4>Shop</h4>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">All Products</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Templates</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Graphics</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Courses</a>
          </div>
          <div>
            <h4>Company</h4>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
          </div>
          <div>
            <h4>Account</h4>
            <a href="<?php echo esc_url(home_url('/login/')); ?>">Log In</a>
            <a href="<?php echo esc_url(home_url('/register/')); ?>">Register</a>
          </div>
        </div>
      </div>
      <div class="ep-footer__bar">&copy; <?php echo $year; ?> <?php echo esc_html($company); ?>. All rights reserved.</div>
    </footer>
    <?php
}

function eproduct_site_shell($content, $title) {
    $company = get_option('eproduct_company_name', 'eProduct');
    ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo esc_html($title); ?> | <?php echo esc_html($company); ?></title>
<meta name="description" content="<?php echo esc_attr($company); ?> &mdash; premium digital products, delivered instantly.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo EPRODUCT_URL; ?>assets/css/style.css">
</head>
<body class="eproduct">
<?php eproduct_site_header(); ?>
<main class="ep-main">
<?php echo $content; ?>
</main>
<?php eproduct_site_footer(); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>window.EP = { ajax: '<?php echo admin_url('admin-ajax.php'); ?>' };</script>
<script src="<?php echo EPRODUCT_URL; ?>assets/js/store.js"></script>
<script src="<?php echo EPRODUCT_URL; ?>assets/js/eproduct-ui.js"></script>
</body>
</html>
<?php
}
