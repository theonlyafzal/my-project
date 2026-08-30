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
          <img class="ep-brand__logo" src="<?php echo esc_url(get_template_directory_uri().'/assets/images/eproduct-logo.svg'); ?>" alt="<?php echo esc_attr($ep_company); ?>" height="40">
        </a>
        <nav class="ep-nav" id="epNav">
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/')); ?>">Home</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy</a>
          <a class="ep-nav__link" href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a>
          <a class="ep-btn ep-btn--sm" href="https://store.eproduct.org/" target="_blank" rel="noopener">Visit Store</a>
        </nav>
        <button class="ep-burger" id="epBurger" aria-label="Toggle menu"><span></span><span></span><span></span></button>
      </div>
    </header>

    <main class="ep-main" id="primary">
