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
    ?>
    <header class="ep-header" id="epHeader">
      <div class="ep-header__inner">
        <a class="ep-brand" href="<?php echo esc_url(home_url('/')); ?>">
          <span class="ep-brand__mark">&#9670;</span>
          <span class="ep-brand__name"><?php echo esc_html($ep_company); ?></span>
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

    <main class="ep-main" id="primary">
